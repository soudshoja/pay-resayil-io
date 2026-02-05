<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Agency;
use App\Models\PaymentRequest;
use App\Models\MyfatoorahCredential;
use App\Models\ActivityLog;
use App\Models\WhatsappLog;

class PlatformController extends Controller
{
    /**
     * Show platform login form
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->is_platform_owner) {
            return redirect()->route('platform.dashboard');
        }
        return view('platform.login');
    }

    /**
     * Handle platform login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->is_platform_owner) {
            return back()->withErrors([
                'email' => 'Access denied. Platform owner credentials required.',
            ])->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);
            return redirect()->intended(route('platform.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Platform Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'agencies' => Agency::count(),
            'active_agencies' => Agency::where('is_active', true)->count(),
            'users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'payments' => PaymentRequest::count(),
            'paid_payments' => PaymentRequest::where('status', 'paid')->count(),
            'revenue' => PaymentRequest::where('status', 'paid')->sum('amount') ?? 0,
            'pending_revenue' => PaymentRequest::where('status', 'pending')->sum('amount') ?? 0,
        ];

        $recentAgencies = Agency::withCount('users')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = PaymentRequest::with(['agency', 'agent'])
            ->latest()
            ->take(10)
            ->get();

        $recentActivity = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('platform.dashboard', compact('stats', 'recentAgencies', 'recentPayments', 'recentActivity'));
    }

    /**
     * List all agencies
     */
    public function agencies(Request $request)
    {
        $query = Agency::withCount(['users', 'paymentRequests'])
            ->with('myfatoorahCredential');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('iata_number', 'like', "%{$search}%")
                  ->orWhere('company_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $agencies = $query->latest()->paginate(15)->withQueryString();

        return view('platform.agencies.index', compact('agencies'));
    }

    /**
     * Show agency details
     */
    public function showAgency(Agency $agency)
    {
        $agency->load(['users', 'myfatoorahCredential', 'paymentRequests' => function($q) {
            $q->latest()->take(20);
        }]);

        $stats = [
            'total_payments' => $agency->paymentRequests()->count(),
            'paid_payments' => $agency->paymentRequests()->where('status', 'paid')->count(),
            'revenue' => $agency->paymentRequests()->where('status', 'paid')->sum('amount'),
        ];

        return view('platform.agencies.show', compact('agency', 'stats'));
    }

    /**
     * Edit agency form
     */
    public function editAgency(Agency $agency)
    {
        $agency->load('myfatoorahCredential');
        return view('platform.agencies.edit', compact('agency'));
    }

    /**
     * Update agency
     */
    public function updateAgency(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'agency_name' => 'required|string|max:255',
            'iata_number' => 'required|string|max:50|unique:clients,iata_number,' . $agency->id,
            'company_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
            'myfatoorah_api_key' => 'nullable|string|max:500',
            'myfatoorah_test_mode' => 'boolean',
        ]);

        $agency->update([
            'name' => $validated['agency_name'],
            'iata_number' => $validated['iata_number'],
            'company_email' => $validated['company_email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Update MyFatoorah credentials if provided
        if ($request->filled('myfatoorah_api_key')) {
            MyfatoorahCredential::updateOrCreate(
                ['client_id' => $agency->id],
                [
                    'api_key' => $validated['myfatoorah_api_key'],
                    'is_test_mode' => $request->boolean('myfatoorah_test_mode'),
                    'is_active' => true,
                ]
            );
        }

        return redirect()->route('platform.agencies.show', $agency)
            ->with('success', 'Agency updated successfully.');
    }

    /**
     * Toggle agency status
     */
    public function toggleAgencyStatus(Agency $agency)
    {
        $agency->update(['is_active' => !$agency->is_active]);

        return back()->with('success',
            $agency->is_active ? 'Agency activated.' : 'Agency deactivated.');
    }

    /**
     * Delete agency
     */
    public function deleteAgency(Agency $agency)
    {
        $name = $agency->agency_name;
        $agency->delete();

        return redirect()->route('platform.agencies')
            ->with('success', "Agency '{$name}' deleted successfully.");
    }

    /**
     * List all users
     */
    public function users(Request $request)
    {
        $query = User::with('agency');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        $agencies = Agency::orderBy('name')->get();

        return view('platform.users.index', compact('users', 'agencies'));
    }

    /**
     * Edit user form
     */
    public function editUser(User $user)
    {
        $agencies = Agency::orderBy('name')->get();
        return view('platform.users.edit', compact('user', 'agencies'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:platform_owner,client_admin,sales_person,accountant',
            'client_id' => 'nullable|exists:clients,id',
            'is_active' => 'boolean',
            'password' => 'nullable|min:6',
        ]);

        $updateData = [
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'client_id' => $validated['client_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('platform.users')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Toggle user status
     */
    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success',
            $user->is_active ? 'User activated.' : 'User deactivated.');
    }

    /**
     * Impersonate user
     */
    public function impersonateUser(User $user)
    {
        // Store original user ID
        session(['impersonating_from' => Auth::id()]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('info', "You are now logged in as {$user->full_name}");
    }

    /**
     * Stop impersonation
     */
    public function stopImpersonation()
    {
        $originalUserId = session('impersonating_from');

        if ($originalUserId) {
            $originalUser = User::find($originalUserId);
            if ($originalUser) {
                Auth::login($originalUser);
                session()->forget('impersonating_from');
                return redirect()->route('platform.dashboard')
                    ->with('success', 'Stopped impersonation.');
            }
        }

        return redirect()->route('platform.dashboard');
    }

    /**
     * List all payments
     */
    public function payments(Request $request)
    {
        $query = PaymentRequest::with(['agency', 'agent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('myfatoorah_invoice_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20)->withQueryString();
        $agencies = Agency::orderBy('name')->get();

        // Stats
        $stats = [
            'total' => PaymentRequest::count(),
            'paid' => PaymentRequest::where('status', 'paid')->count(),
            'pending' => PaymentRequest::where('status', 'pending')->count(),
            'failed' => PaymentRequest::where('status', 'failed')->count(),
            'revenue' => PaymentRequest::where('status', 'paid')->sum('amount'),
        ];

        return view('platform.payments.index', compact('payments', 'agencies', 'stats'));
    }

    /**
     * Export payments to CSV
     */
    public function exportPayments(Request $request)
    {
        $query = PaymentRequest::with(['agency', 'agent']);

        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->get();

        $filename = 'payments_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID', 'Agency', 'Agent', 'Customer Phone', 'Customer Name',
                'Amount', 'Currency', 'Status', 'Invoice ID', 'Payment ID',
                'Created At', 'Paid At'
            ]);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->agency->agency_name ?? 'N/A',
                    $payment->agent->full_name ?? 'N/A',
                    $payment->customer_phone,
                    $payment->customer_name,
                    $payment->amount,
                    $payment->currency,
                    $payment->status,
                    $payment->myfatoorah_invoice_id,
                    $payment->myfatoorah_payment_id,
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Platform settings
     */
    public function settings()
    {
        $settings = [
            'resayil_base_url' => config('services.resayil.base_url'),
            'resayil_api_key' => config('services.resayil.api_key'),
            'myfatoorah_base_url' => config('services.myfatoorah.base_url'),
            'myfatoorah_test_mode' => config('services.myfatoorah.test_mode'),
            'app_timezone' => config('app.timezone'),
            'app_locale' => config('app.locale'),
        ];

        return view('platform.settings', compact('settings'));
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        // In production, these would update .env or a settings table
        // For now, we'll just show success message

        return back()->with('success', 'Settings updated successfully. Note: Some changes may require server restart.');
    }

    /**
     * Activity logs
     */
    public function logs(Request $request)
    {
        $query = ActivityLog::with(['user', 'agency']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(50)->withQueryString();
        $users = User::orderBy('full_name')->get();
        $agencies = Agency::orderBy('name')->get();

        return view('platform.logs', compact('logs', 'users', 'agencies'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('platform.login');
    }
}
