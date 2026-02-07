<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Services\MyFatoorahService;
use Illuminate\Http\Request;

class PaymentPageController extends Controller
{
    /**
     * Find a payment by multiple identifier fields
     */
    private function findPayment(string $invoiceId, array $with = [])
    {
        $query = PaymentRequest::where('myfatoorah_invoice_id', $invoiceId)
            ->orWhere('id', $invoiceId)
            ->orWhere('reference_id', $invoiceId)
            ->orWhere('track_id', $invoiceId);

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->firstOrFail();
    }

    /**
     * Show Custom Payment Page
     */
    public function show(string $invoiceId)
    {
        $payment = $this->findPayment($invoiceId, ['client', 'agent']);

        // Check if already paid
        if ($payment->status === 'paid') {
            return redirect()->route('payment.success', $invoiceId);
        }

        // Check if expired (24 hours)
        if ($payment->created_at->diffInHours(now()) > 24 && $payment->status === 'pending') {
            $payment->update(['status' => 'expired']);
            return view('payment.expired', compact('payment'));
        }

        // Calculate amounts
        $client = $payment->client;
        $serviceFee = 0;

        if ($client && $client->service_fee_payer === 'customer') {
            $serviceFee = $client->calculateServiceFee($payment->amount);
        }

        $totalAmount = $payment->amount + $serviceFee;

        return view('payment.show', compact('payment', 'serviceFee', 'totalAmount'));
    }

    /**
     * Redirect to MyFatoorah Payment
     */
    public function redirect(string $invoiceId)
    {
        $payment = $this->findPayment($invoiceId);

        if ($payment->status !== 'pending') {
            return redirect()->route('payment.show', $invoiceId)
                ->with('error', 'This payment is no longer pending.');
        }

        // Use the myfatoorah_invoice_id as the canonical identifier for callback URLs
        $canonicalId = $payment->myfatoorah_invoice_id ?? $payment->id;

        // Check if existing payment_url has matching callback URLs
        $urlIsValid = false;
        if ($payment->payment_url) {
            // Verify the stored URL callbacks point to the correct invoiceId
            $expectedCallback = route('payment.callback', $canonicalId);
            // If we can't verify, regenerate to be safe
            \Log::info('Payment redirect: checking stored URL', [
                'invoice_id' => $invoiceId,
                'canonical_id' => $canonicalId,
                'has_url' => true,
            ]);
            // Always regenerate for pending payments to ensure callback URLs are correct
            $urlIsValid = false;
        }

        // Create new payment via MyFatoorah with correct callback URLs
        try {
            $myfatoorah = new MyFatoorahService($payment->client_id);

            $response = $myfatoorah->executePayment(
                amount: $payment->total_amount ?? $payment->amount,
                agencyName: $payment->agent?->company_name ?? $payment->client->name,
                iataNumber: $payment->agent?->iata_number ?? 'N/A',
                customerName: $payment->customer_name,
                customerPhone: $payment->customer_phone,
                callbackUrl: route('payment.callback', $canonicalId),
                errorUrl: route('payment.failed', $canonicalId)
            );

            if ($response['IsSuccess'] && isset($response['Data']['PaymentURL'])) {
                $updateData = [
                    'payment_url' => $response['Data']['PaymentURL'],
                ];

                // Update myfatoorah_invoice_id if a new one was returned
                if (isset($response['Data']['InvoiceId'])) {
                    $updateData['myfatoorah_invoice_id'] = $response['Data']['InvoiceId'];
                }

                $payment->update($updateData);

                \Log::info('Payment redirect: new MyFatoorah URL generated', [
                    'invoice_id' => $invoiceId,
                    'canonical_id' => $canonicalId,
                    'new_mf_invoice_id' => $response['Data']['InvoiceId'] ?? 'N/A',
                    'callback_url' => route('payment.callback', $canonicalId),
                    'error_url' => route('payment.failed', $canonicalId),
                ]);

                return redirect()->away($response['Data']['PaymentURL']);
            }

        } catch (\Exception $e) {
            \Log::error('Payment redirect failed', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('payment.show', $invoiceId)
            ->with('error', 'Unable to process payment. Please try again.');
    }

    /**
     * Payment Callback (Success)
     */
    public function callback(Request $request, string $invoiceId)
    {
        $payment = $this->findPayment($invoiceId);

        $paymentId = $request->input('paymentId');

        if ($paymentId) {
            try {
                $myfatoorah = new MyFatoorahService($payment->client_id);
                $status = $myfatoorah->getPaymentStatus($paymentId);

                if ($status['IsSuccess'] && $status['Data']['InvoiceStatus'] === 'Paid') {
                    $payment->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'myfatoorah_payment_id' => $paymentId,
                        'myfatoorah_response' => $status,
                    ]);

                    // TODO: Send WhatsApp notifications

                    return redirect()->route('payment.success', $invoiceId);
                }
            } catch (\Exception $e) {
                \Log::error('Payment callback verification failed', [
                    'invoice_id' => $invoiceId,
                    'payment_id' => $paymentId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // If no paymentId or verification failed, check status
        if ($payment->status === 'paid') {
            return redirect()->route('payment.success', $invoiceId);
        }

        return redirect()->route('payment.failed', $invoiceId);
    }

    /**
     * Payment Success Page
     */
    public function success(string $invoiceId)
    {
        $payment = $this->findPayment($invoiceId, ['client', 'agent']);

        return view('payment.success', compact('payment'));
    }

    /**
     * Payment Failed Page
     */
    public function failed(string $invoiceId)
    {
        $payment = $this->findPayment($invoiceId, ['client', 'agent']);

        return view('payment.failed', compact('payment'));
    }
}
