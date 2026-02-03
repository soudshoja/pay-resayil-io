<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\MyFatoorahService;
use App\Services\ResayilWhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MyFatoorahWebhookController extends Controller
{
    public function __construct(
        private ResayilWhatsAppService $whatsappService
    ) {}

    /**
     * Handle MyFatoorah payment callback (redirect from payment page)
     */
    public function handleCallback(Request $request)
    {
        try {
            $paymentId = $request->input('paymentId');

            if (!$paymentId) {
                Log::warning('MyFatoorah callback: Missing paymentId');
                return redirect()->route('payment.error')
                    ->with('error', __('messages.payments.invalid_id'));
            }

            // Find payment request
            $paymentRequest = PaymentRequest::where('myfatoorah_payment_id', $paymentId)
                ->orWhere('myfatoorah_invoice_id', $paymentId)
                ->first();

            if (!$paymentRequest) {
                // Try to find by invoice ID from response
                Log::warning('MyFatoorah callback: Payment not found', ['payment_id' => $paymentId]);
                return redirect()->route('payment.error')
                    ->with('error', __('messages.payments.not_found'));
            }

            // Get payment status from MyFatoorah
            $myfatoorah = new MyFatoorahService($paymentRequest->agency_id);
            $statusResponse = $myfatoorah->getPaymentStatus($paymentId);

            $invoiceStatus = $statusResponse['Data']['InvoiceStatus'] ?? 'Pending';
            $transaction = $statusResponse['Data']['InvoiceTransactions'][0] ?? null;

            if ($invoiceStatus === 'Paid') {
                $paymentRequest->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'myfatoorah_payment_id' => $paymentId,
                    'myfatoorah_response' => $statusResponse,
                    'webhook_received_at' => now(),
                    'reference_id' => $transaction['ReferenceId'] ?? null,
                    'track_id' => $transaction['TrackId'] ?? null,
                ]);

                // Log activity
                ActivityLog::log('payment_paid', 'Payment completed', [
                    'amount' => $paymentRequest->amount,
                    'reference' => $transaction['ReferenceId'] ?? 'N/A',
                ], $paymentRequest);

                // Notify accountants
                $this->notifyAccountants($paymentRequest, $statusResponse);

                return redirect()->route('payment.success')
                    ->with('success', __('messages.payments.success'));
            } else {
                $paymentRequest->update([
                    'status' => 'failed',
                    'myfatoorah_response' => $statusResponse,
                ]);

                return redirect()->route('payment.error')
                    ->with('error', __('messages.payments.failed'));
            }

        } catch (\Exception $e) {
            Log::error('MyFatoorah Callback Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->route('payment.error')
                ->with('error', __('messages.payments.processing_error'));
        }
    }

    /**
     * Handle MyFatoorah error callback
     */
    public function handleError(Request $request)
    {
        $paymentId = $request->input('paymentId');

        if ($paymentId) {
            $paymentRequest = PaymentRequest::where('myfatoorah_invoice_id', $paymentId)
                ->orWhere('myfatoorah_payment_id', $paymentId)
                ->first();

            if ($paymentRequest && $paymentRequest->isPending()) {
                $paymentRequest->update(['status' => 'failed']);
            }
        }

        return redirect()->route('payment.error')
            ->with('error', __('messages.payments.cancelled'));
    }

    /**
     * Handle MyFatoorah webhook (server-to-server notification)
     */
    public function handleWebhook(Request $request)
    {
        try {
            $payload = $request->all();

            Log::info('MyFatoorah Webhook received', $payload);

            $invoiceId = $payload['Data']['InvoiceId'] ?? null;
            $transactionStatus = $payload['Data']['TransactionStatus'] ?? null;

            if (!$invoiceId) {
                return response()->json(['error' => 'Missing invoice ID'], 400);
            }

            $paymentRequest = PaymentRequest::where('myfatoorah_invoice_id', $invoiceId)->first();

            if (!$paymentRequest) {
                Log::warning('Webhook: Payment request not found', ['invoice_id' => $invoiceId]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Already processed
            if ($paymentRequest->isPaid()) {
                return response()->json(['success' => true, 'message' => 'Already processed']);
            }

            if (strtoupper($transactionStatus) === 'SUCCESS') {
                $paymentRequest->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'myfatoorah_payment_id' => $payload['Data']['PaymentId'] ?? null,
                    'myfatoorah_response' => $payload,
                    'webhook_received_at' => now(),
                    'reference_id' => $payload['Data']['ReferenceId'] ?? null,
                    'track_id' => $payload['Data']['TrackId'] ?? null,
                ]);

                // Log activity
                ActivityLog::log('payment_paid', 'Payment confirmed via webhook', [
                    'amount' => $paymentRequest->amount,
                    'invoice_id' => $invoiceId,
                ], $paymentRequest);

                // Notify accountants
                $this->notifyAccountants($paymentRequest, $payload);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => true, 'status' => 'ignored']);

        } catch (\Exception $e) {
            Log::error('MyFatoorah Webhook Error', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json(['error' => 'Webhook processing error'], 500);
        }
    }

    /**
     * Notify all accountants in the agency
     */
    private function notifyAccountants(PaymentRequest $paymentRequest, array $paymentData): void
    {
        $accountants = User::where('agency_id', $paymentRequest->agency_id)
            ->where('role', 'accountant')
            ->where('is_active', true)
            ->get();

        // Also notify admins
        $admins = User::where('agency_id', $paymentRequest->agency_id)
            ->where('role', 'admin')
            ->where('is_active', true)
            ->get();

        $recipients = $accountants->merge($admins);

        if ($recipients->isEmpty()) {
            Log::warning('No accountants to notify', ['agency_id' => $paymentRequest->agency_id]);
            return;
        }

        $referenceId = $paymentData['Data']['ReferenceId']
            ?? $paymentData['Data']['InvoiceTransactions'][0]['ReferenceId']
            ?? 'N/A';

        foreach ($recipients as $recipient) {
            try {
                $this->whatsappService->sendPaymentConfirmation(
                    phoneNumber: $recipient->username,
                    agencyName: $paymentRequest->agency->agency_name,
                    amount: $paymentRequest->amount,
                    currency: $paymentRequest->currency,
                    customerPhone: $paymentRequest->customer_phone ?? 'N/A',
                    referenceId: $referenceId,
                    agencyId: $paymentRequest->agency_id
                );
            } catch (\Exception $e) {
                Log::error('Failed to notify accountant', [
                    'recipient' => $recipient->username,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
