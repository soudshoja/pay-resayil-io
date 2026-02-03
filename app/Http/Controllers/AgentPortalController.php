<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentAuthorizedPhone;
use App\Models\OtpVerification;
use App\Models\PaymentRequest;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AgentPortalController extends Controller
{
    /**
     * Show Registration Step 1 - Email Input
     */
    public function showRegisterStep1()
    {
        return view('agent.register.step1');
    }

    /**
     * Send OTP to Email
     */
    public function sendOTP(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $otpService = new OTPService();
        $result = $otpService->sendOTP($validated['email'], 'agent_registration');

        if ($result['success']) {
            Session::put('agent_registration_email', $validated['email']);
            return redirect()->route('agent.register.step2')
                ->with('success', __('messages.otp_sent'));
        }

        return back()->with('error', $result['message'] ?? __('messages.otp_failed'));
    }

    /**
     * Show Registration Step 2 - OTP Verification
     */
    public function showRegisterStep2()
    {
        if (!Session::has('agent_registration_email')) {
            return redirect()->route('agent.register.step1');
        }

        $email = Session::get('agent_registration_email');
        return view('agent.register.step2', compact('email'));
    }

    /**
     * Verify OTP
     */
    public function verifyOTP(Request $request)
    {
        $validated = $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $email = Session::get('agent_registration_email');
        if (!$email) {
            return redirect()->route('agent.register.step1');
        }

        $otpService = new OTPService();
        $result = $otpService->verifyOTP($email, $validated['otp_code']);

        if ($result['success']) {
            Session::put('agent_registration_verified', true);
            return redirect()->route('agent.register.step3');
        }

        return back()->with('error', $result['message'] ?? __('messages.otp_invalid'));
    }

    /**
     * Show Registration Step 3 - Company Details
     */
    public function showRegisterStep3()
    {
        if (!Session::get('agent_registration_verified')) {
            return redirect()->route('agent.register.step1');
        }

        $email = Session::get('agent_registration_email');
        return view('agent.register.step3', compact('email'));
    }

    /**
     * Complete Registration
     */
    public function completeRegistration(Request $request)
    {
        if (!Session::get('agent_registration_verified')) {
            return redirect()->route('agent.register.step1');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'iata_number' => 'nullable|string|max:50',
            'accountant_whatsapp' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $email = Session::get('agent_registration_email');

        // For now, create agent without client (will be assigned by client admin)
        // In production, you'd have client selection or invitation flow
        $agent = Agent::create([
            'client_id' => 1, // Default client - should be handled via invitation
            'company_name' => $validated['company_name'],
            'iata_number' => $validated['iata_number'],
            'email' => $email,
            'email_verified_at' => now(),
            'accountant_whatsapp' => $validated['accountant_whatsapp'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_active' => true,
        ]);

        // Clear session
        Session::forget(['agent_registration_email', 'agent_registration_verified']);

        return redirect()->route('agent.register.success')
            ->with('agent_id', $agent->id);
    }

    /**
     * Registration Success Page
     */
    public function registrationSuccess()
    {
        return view('agent.register.success');
    }

    /**
     * Agent Login (via authorized phone)
     */
    public function showLogin()
    {
        return view('agent.login');
    }

    /**
     * Agent Login - Send OTP to Phone
     */
    public function sendPhoneOTP(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|max:20',
        ]);

        $phone = AgentAuthorizedPhone::normalizePhone($validated['phone_number']);

        $authorizedPhone = AgentAuthorizedPhone::where('phone_number', $phone)
            ->where('is_active', true)
            ->first();

        if (!$authorizedPhone) {
            return back()->with('error', __('messages.phone_not_authorized'));
        }

        $otpService = new OTPService();
        $result = $otpService->sendWhatsAppOTP($phone, 'agent_login');

        if ($result['success']) {
            Session::put('agent_login_phone', $phone);
            Session::put('agent_login_agent_id', $authorizedPhone->agent_id);
            return redirect()->route('agent.login.verify');
        }

        return back()->with('error', $result['message'] ?? __('messages.otp_failed'));
    }

    /**
     * Agent Login - Verify OTP
     */
    public function showLoginVerify()
    {
        if (!Session::has('agent_login_phone')) {
            return redirect()->route('agent.login');
        }

        return view('agent.login-verify');
    }

    /**
     * Agent Login - Complete
     */
    public function verifyLoginOTP(Request $request)
    {
        $validated = $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $phone = Session::get('agent_login_phone');
        $agentId = Session::get('agent_login_agent_id');

        if (!$phone || !$agentId) {
            return redirect()->route('agent.login');
        }

        $otpService = new OTPService();
        $result = $otpService->verifyOTP($phone, $validated['otp_code']);

        if ($result['success']) {
            Session::put('agent_authenticated', true);
            Session::put('agent_id', $agentId);
            Session::forget(['agent_login_phone', 'agent_login_agent_id']);
            return redirect()->route('agent.dashboard');
        }

        return back()->with('error', $result['message'] ?? __('messages.otp_invalid'));
    }

    /**
     * Agent Dashboard
     */
    public function dashboard()
    {
        $agent = $this->getAuthenticatedAgent();

        $stats = [
            'total_payments' => PaymentRequest::where('agent_id', $agent->id)->count(),
            'pending_payments' => PaymentRequest::where('agent_id', $agent->id)->where('status', 'pending')->count(),
            'paid_payments' => PaymentRequest::where('agent_id', $agent->id)->where('status', 'paid')->count(),
            'total_amount' => PaymentRequest::where('agent_id', $agent->id)->where('status', 'paid')->sum('amount'),
        ];

        $recentPayments = PaymentRequest::where('agent_id', $agent->id)
            ->latest()
            ->take(10)
            ->get();

        $authorizedPhones = $agent->authorizedPhones()->where('is_active', true)->get();

        return view('agent.dashboard', compact('agent', 'stats', 'recentPayments', 'authorizedPhones'));
    }

    /**
     * Agent Payments List
     */
    public function payments(Request $request)
    {
        $agent = $this->getAuthenticatedAgent();

        $query = PaymentRequest::where('agent_id', $agent->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20);

        return view('agent.payments.index', compact('payments'));
    }

    /**
     * Agent Phones List
     */
    public function phones()
    {
        $agent = $this->getAuthenticatedAgent();

        $phones = $agent->authorizedPhones()->paginate(20);

        return view('agent.phones.index', compact('phones'));
    }

    /**
     * Add Authorized Phone
     */
    public function addPhone(Request $request)
    {
        $agent = $this->getAuthenticatedAgent();

        $validated = $request->validate([
            'phone_number' => 'required|string|max:20|unique:agent_authorized_phones,phone_number',
            'full_name' => 'nullable|string|max:255',
        ]);

        AgentAuthorizedPhone::create([
            'agent_id' => $agent->id,
            'phone_number' => $validated['phone_number'],
            'full_name' => $validated['full_name'],
            'is_active' => true,
        ]);

        return back()->with('success', __('messages.phone_added'));
    }

    /**
     * Remove Authorized Phone
     */
    public function removePhone(AgentAuthorizedPhone $phone)
    {
        $agent = $this->getAuthenticatedAgent();

        if ($phone->agent_id !== $agent->id) {
            abort(403);
        }

        $phone->delete();

        return back()->with('success', __('messages.phone_removed'));
    }

    /**
     * Agent Settings
     */
    public function settings()
    {
        $agent = $this->getAuthenticatedAgent();

        return view('agent.settings', compact('agent'));
    }

    /**
     * Update Agent Settings
     */
    public function updateSettings(Request $request)
    {
        $agent = $this->getAuthenticatedAgent();

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'iata_number' => 'nullable|string|max:50',
            'accountant_whatsapp' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $agent->update($validated);

        return back()->with('success', __('messages.settings_updated'));
    }

    /**
     * Logout
     */
    public function logout()
    {
        Session::forget(['agent_authenticated', 'agent_id']);
        return redirect()->route('agent.login');
    }

    /**
     * Get authenticated agent
     */
    private function getAuthenticatedAgent(): Agent
    {
        $agentId = Session::get('agent_id');

        if (!$agentId || !Session::get('agent_authenticated')) {
            abort(redirect()->route('agent.login'));
        }

        $agent = Agent::find($agentId);

        if (!$agent || !$agent->is_active) {
            Session::forget(['agent_authenticated', 'agent_id']);
            abort(redirect()->route('agent.login')->with('error', __('messages.agent_inactive')));
        }

        return $agent;
    }
}
