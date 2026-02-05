<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\ActivityLog;
use App\Services\MyFatoorahService;
use App\Services\ResayilWhatsAppService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private ResayilWhatsAppService $whatsappService
    ) {}

    /**
     * List all payments
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $agencyId = $user->agency_id;

        $payments = PaymentRequest::with(['agent', 'agency'])
            ->when(!$user->isSuperAdmin(), function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('customer_phone', 'like', '%' . $request->search . '%')
                      ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                      ->orWhere('myfatoorah_invoice_id', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show create payment form
     */
    public function create()
    {
        return view('payments.create');
    }

    /**
     * Store new payment request
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.100|max:10000',
            'customer_phone' => 'required|string|min:8|max:20',
            'customer_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'send_whatsapp' => 'boolean',
        ]);

        $user = $request->user();
        $agency = $user->agency;

        if (!$agency->hasValidCredentials()) {
            return back()->withErrors([
                'error' => __('messages.payments.no_credentials')
            ]);
        }

        try {
            // Create payment with MyFatoorah
            $myfatoorah = new MyFatoorahService($agency->id);
            $paymentResponse = $myfatoorah->executePayment(
                amount: $request->amount,
                agencyName: $agency->agency_name,
                iataNumber: $agency->iata_number,
                customerName: $request->customer_name,
                customerPhone: $request->customer_phone
            );

            // Store payment request
            $payment = PaymentRequest::create([
                'agency_id' => $agency->id,
                'agent_user_id' => $user->id,
                'myfatoorah_invoice_id' => $paymentResponse['Data']['InvoiceId'],
                'payment_url' => $paymentResponse['Data']['PaymentURL'],
                'amount' => $request->amount,
                'currency' => 'KWD',
                'customer_phone' => $request->customer_phone,
                'customer_name' => $request->customer_name,
                'description' => $request->description ?? "{$agency->agency_name} (IATA: {$agency->iata_number})",
                'status' => 'pending',
                'expires_at' => now()->addHours(24),
                'myfatoorah_response' => $paymentResponse,
            ]);

            // Log activity
            ActivityLog::log('payment_created', 'Created payment request', [
                'amount' => $request->amount,
                'customer' => $request->customer_phone,
            ], $payment);

            // Send WhatsApp if requested
            if ($request->boolean('send_whatsapp', true)) {
                $this->whatsappService->sendPaymentLink(
                    phoneNumber: $request->customer_phone,
                    paymentUrl: $paymentResponse['Data']['PaymentURL'],
                    amount: $request->amount,
                    currency: 'KWD',
                    agencyName: $agency->agency_name,
                    iataNumber: $agency->iata_number,
                    agencyId: $agency->id
                );
            }

            return redirect()->route('payments.show', $payment)
                ->with('success', __('messages.payments.created'));

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Show payment details
     */
    public function show(Request $request, PaymentRequest $payment)
    {
        $user = $request->user();

        // Check access
        if (!$user->isSuperAdmin() && $payment->agency_id !== $user->agency_id) {
            abort(403);
        }

        $payment->load(['agent', 'agency']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Resend payment link via WhatsApp
     */
    public function resendLink(Request $request, PaymentRequest $payment)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && $payment->agency_id !== $user->agency_id) {
            abort(403);
        }

        if (!$payment->isPending()) {
            return back()->withErrors([
                'error' => __('messages.payments.cannot_resend')
            ]);
        }

        $agency = $payment->agency;

        $this->whatsappService->sendPaymentLink(
            phoneNumber: $payment->customer_phone,
            paymentUrl: $payment->payment_url,
            amount: $payment->amount,
            currency: $payment->currency,
            agencyName: $agency->agency_name,
            iataNumber: $agency->iata_number,
            agencyId: $agency->id
        );

        return back()->with('success', __('messages.payments.link_resent'));
    }

    /**
     * Cancel payment
     */
    public function cancel(Request $request, PaymentRequest $payment)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && $payment->agency_id !== $user->agency_id) {
            abort(403);
        }

        if (!$payment->isPending()) {
            return back()->withErrors([
                'error' => __('messages.payments.cannot_cancel')
            ]);
        }

        $payment->update(['status' => 'cancelled']);

        ActivityLog::log('payment_cancelled', 'Cancelled payment request', [
            'payment_id' => $payment->id,
        ], $payment);

        return back()->with('success', __('messages.payments.cancelled'));
    }

    /**
     * Payment success page
     */
    public function success()
    {
        return view('payments.success');
    }

    /**
     * Payment error page
     */
    public function error()
    {
        return view('payments.error');
    }
}
