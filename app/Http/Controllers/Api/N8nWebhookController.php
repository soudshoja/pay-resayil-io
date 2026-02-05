<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PaymentRequest;
use App\Services\MyFatoorahService;
use App\Services\ResayilWhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class N8nWebhookController extends Controller
{
    public function __construct(
        private ResayilWhatsAppService $whatsappService
    ) {}

    /**
     * Handle incoming WhatsApp message from n8n
     * Agent requests top-up via WhatsApp
     */
    public function handleIncomingMessage(Request $request)
    {
        try {
            $phoneNumber = $request->input('from');
            $message = $request->input('message');
            $amount = $this->extractAmount($message);

            if (!$phoneNumber) {
                return response()->json([
                    'success' => false,
                    'error' => 'Missing phone number',
                ], 400);
            }

            if ($amount <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Could not parse amount from message',
                ], 400);
            }

            // Normalize phone number
            $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);

            // Find agent by phone number
            $agent = User::where('username', $normalizedPhone)
                ->where('is_active', true)
                ->first();

            if (!$agent) {
                Log::warning('N8n: Agent not found', ['phone' => $normalizedPhone]);
                return response()->json([
                    'success' => false,
                    'error' => 'Agent not found',
                ], 404);
            }

            $agency = $agent->agency;
            if (!$agency || !$agency->is_active) {
                return response()->json([
                    'success' => false,
                    'error' => 'Agency not active',
                ], 403);
            }

            if (!$agency->hasValidCredentials()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Agency MyFatoorah not configured',
                ], 403);
            }

            // Generate payment link using agency's MyFatoorah credentials
            $myfatoorah = new MyFatoorahService($agency->id);

            $paymentResponse = $myfatoorah->executePayment(
                amount: $amount,
                agencyName: $agency->agency_name,
                iataNumber: $agency->iata_number,
                customerPhone: $phoneNumber,
                metadata: [
                    'source' => 'n8n_whatsapp',
                    'agent_id' => $agent->id,
                ]
            );

            // Store payment request
            $paymentRequest = PaymentRequest::create([
                'agency_id' => $agency->id,
                'agent_user_id' => $agent->id,
                'myfatoorah_invoice_id' => $paymentResponse['Data']['InvoiceId'],
                'payment_url' => $paymentResponse['Data']['PaymentURL'],
                'amount' => $amount,
                'currency' => 'KWD',
                'customer_phone' => $phoneNumber,
                'description' => "{$agency->agency_name} (IATA: {$agency->iata_number}) - via WhatsApp",
                'status' => 'pending',
                'expires_at' => now()->addHours(24),
                'myfatoorah_response' => $paymentResponse,
            ]);

            // Send payment link via WhatsApp
            $this->whatsappService->sendPaymentLink(
                phoneNumber: $phoneNumber,
                paymentUrl: $paymentResponse['Data']['PaymentURL'],
                amount: $amount,
                currency: 'KWD',
                agencyName: $agency->agency_name,
                iataNumber: $agency->iata_number,
                agencyId: $agency->id
            );

            Log::info('N8n: Payment link generated', [
                'payment_id' => $paymentRequest->id,
                'agent' => $agent->full_name,
                'amount' => $amount,
            ]);

            return response()->json([
                'success' => true,
                'payment_request_id' => $paymentRequest->id,
                'payment_url' => $paymentResponse['Data']['PaymentURL'],
                'invoice_id' => $paymentResponse['Data']['InvoiceId'],
                'agency' => $agency->agency_name,
                'amount' => $amount,
                'currency' => 'KWD',
            ]);

        } catch (\Exception $e) {
            Log::error('N8n Webhook Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate payment link via API
     */
    public function generatePaymentLink(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string',
                'amount' => 'required|numeric|min:0.100',
                'customer_name' => 'nullable|string|max:255',
                'send_whatsapp' => 'boolean',
            ]);

            $normalizedPhone = $this->normalizePhoneNumber($request->phone);

            // Find agent
            $agent = User::where('username', $normalizedPhone)
                ->where('is_active', true)
                ->first();

            if (!$agent || !$agent->agency) {
                return response()->json([
                    'success' => false,
                    'error' => 'Agent or agency not found',
                ], 404);
            }

            $agency = $agent->agency;

            if (!$agency->hasValidCredentials()) {
                return response()->json([
                    'success' => false,
                    'error' => 'MyFatoorah not configured',
                ], 403);
            }

            $myfatoorah = new MyFatoorahService($agency->id);

            $paymentResponse = $myfatoorah->executePayment(
                amount: $request->amount,
                agencyName: $agency->agency_name,
                iataNumber: $agency->iata_number,
                customerName: $request->customer_name,
                customerPhone: $request->phone
            );

            $payment = PaymentRequest::create([
                'agency_id' => $agency->id,
                'agent_user_id' => $agent->id,
                'myfatoorah_invoice_id' => $paymentResponse['Data']['InvoiceId'],
                'payment_url' => $paymentResponse['Data']['PaymentURL'],
                'amount' => $request->amount,
                'currency' => 'KWD',
                'customer_phone' => $request->phone,
                'customer_name' => $request->customer_name,
                'status' => 'pending',
                'expires_at' => now()->addHours(24),
                'myfatoorah_response' => $paymentResponse,
            ]);

            // Send WhatsApp if requested
            if ($request->boolean('send_whatsapp', true)) {
                $this->whatsappService->sendPaymentLink(
                    phoneNumber: $request->phone,
                    paymentUrl: $paymentResponse['Data']['PaymentURL'],
                    amount: $request->amount,
                    currency: 'KWD',
                    agencyName: $agency->agency_name,
                    iataNumber: $agency->iata_number,
                    agencyId: $agency->id
                );
            }

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'payment_url' => $paymentResponse['Data']['PaymentURL'],
                'invoice_id' => $paymentResponse['Data']['InvoiceId'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(Request $request, $paymentId)
    {
        $payment = PaymentRequest::find($paymentId);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'error' => 'Payment not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'payment' => [
                'id' => $payment->id,
                'status' => $payment->status,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'customer_phone' => $payment->customer_phone,
                'created_at' => $payment->created_at->toIso8601String(),
                'paid_at' => $payment->paid_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Extract amount from message
     */
    private function extractAmount(string $message): float
    {
        // Remove common words
        $message = str_ireplace([
            'top up', 'topup', 'recharge', 'pay', 'payment',
            'شحن', 'دفع', 'دينار', 'KD', 'kd', 'KWD', 'kwd'
        ], '', $message);

        // Extract number (supports decimals)
        preg_match('/(\d+(?:\.\d{1,3})?)/', $message, $matches);

        return (float) ($matches[1] ?? 0);
    }

    /**
     * Normalize phone number to E.164 format
     */
    private function normalizePhoneNumber(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (!str_starts_with($phoneNumber, '965') && strlen($phoneNumber) == 8) {
            $phoneNumber = '965' . $phoneNumber;
        }

        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
    }
}
