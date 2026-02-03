<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Services\MyFatoorahService;
use Illuminate\Http\Request;

class PaymentPageController extends Controller
{
    /**
     * Show Custom Payment Page
     */
    public function show(string $invoiceId)
    {
        $payment = PaymentRequest::where('myfatoorah_invoice_id', $invoiceId)
            ->orWhere('id', $invoiceId)
            ->with(['client', 'agent'])
            ->firstOrFail();

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
        $payment = PaymentRequest::where('myfatoorah_invoice_id', $invoiceId)
            ->orWhere('id', $invoiceId)
            ->firstOrFail();

        if ($payment->status !== 'pending') {
            return redirect()->route('payment.show', $invoiceId)
                ->with('error', 'This payment is no longer pending.');
        }

        // If we have a stored MyFatoorah URL, redirect to it
        if ($payment->payment_url) {
            return redirect()->away($payment->payment_url);
        }

        // Otherwise create new payment via MyFatoorah
        try {
            $myfatoorah = new MyFatoorahService($payment->client_id);

            $response = $myfatoorah->executePayment(
                amount: $payment->total_amount ?? $payment->amount,
                agencyName: $payment->agent?->company_name ?? $payment->client->name,
                iataNumber: $payment->agent?->iata_number ?? 'N/A',
                customerName: $payment->customer_name,
                customerPhone: $payment->customer_phone,
                callbackUrl: route('payment.callback', $invoiceId),
                errorUrl: route('payment.failed', $invoiceId)
            );

            if ($response['IsSuccess'] && isset($response['Data']['PaymentURL'])) {
                $payment->update([
                    'payment_url' => $response['Data']['PaymentURL'],
                    'myfatoorah_invoice_id' => $response['Data']['InvoiceId'] ?? $payment->myfatoorah_invoice_id,
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
        $payment = PaymentRequest::where('myfatoorah_invoice_id', $invoiceId)
            ->orWhere('id', $invoiceId)
            ->firstOrFail();

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
        $payment = PaymentRequest::where('myfatoorah_invoice_id', $invoiceId)
            ->orWhere('id', $invoiceId)
            ->with(['client', 'agent'])
            ->firstOrFail();

        return view('payment.success', compact('payment'));
    }

    /**
     * Payment Failed Page
     */
    public function failed(string $invoiceId)
    {
        $payment = PaymentRequest::where('myfatoorah_invoice_id', $invoiceId)
            ->orWhere('id', $invoiceId)
            ->with(['client', 'agent'])
            ->firstOrFail();

        return view('payment.failed', compact('payment'));
    }
}
