<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentAuthorizedPhone;
use App\Models\Client;
use App\Models\PaymentRequest;
use App\Services\MyFatoorahService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class N8nApiController extends Controller
{
    /**
     * Check if phone is authorized
     * POST /api/n8n/check-phone
     */
    public function checkPhone(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'client_whatsapp' => 'required|string',
        ]);

        $phone = AgentAuthorizedPhone::normalizePhone($validated['phone']);
        $clientWhatsapp = AgentAuthorizedPhone::normalizePhone($validated['client_whatsapp']);

        // Find client by WhatsApp number
        $client = Client::where('whatsapp_number', $clientWhatsapp)
            ->where('is_active', true)
            ->first();

        if (!$client) {
            return response()->json([
                'authorized' => false,
                'error' => 'Client not found',
            ], 404);
        }

        // Find authorized phone for this client's agents
        $authorizedPhone = AgentAuthorizedPhone::where('phone_number', $phone)
            ->where('is_active', true)
            ->whereHas('agent', function ($q) use ($client) {
                $q->where('client_id', $client->id)->where('is_active', true);
            })
            ->with(['agent'])
            ->first();

        if (!$authorizedPhone) {
            return response()->json([
                'authorized' => false,
                'error' => 'Phone not authorized',
                'client_id' => $client->id,
            ]);
        }

        $agent = $authorizedPhone->agent;

        return response()->json([
            'authorized' => true,
            'agent_id' => $agent->id,
            'client_id' => $client->id,
            'agent' => [
                'company_name' => $agent->company_name,
                'iata_number' => $agent->iata_number,
                'accountant_whatsapp' => $agent->accountant_whatsapp,
                'email' => $agent->email,
            ],
            'client' => [
                'name' => $client->name,
                'service_fee_type' => $client->service_fee_type,
                'service_fee_value' => $client->service_fee_value,
                'service_fee_payer' => $client->service_fee_payer,
            ],
        ]);
    }

    /**
     * Confirm agent details
     * POST /api/n8n/confirm-details
     */
    public function confirmDetails(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
        ]);

        $phone = AgentAuthorizedPhone::normalizePhone($validated['phone']);

        $authorizedPhone = AgentAuthorizedPhone::where('phone_number', $phone)
            ->where('is_active', true)
            ->with(['agent.salesPerson', 'agent.client'])
            ->first();

        if (!$authorizedPhone || !$authorizedPhone->agent) {
            return response()->json([
                'success' => false,
                'error' => 'Agent not found',
            ], 404);
        }

        $agent = $authorizedPhone->agent;

        return response()->json([
            'success' => true,
            'company_name' => $agent->company_name,
            'iata' => $agent->iata_number,
            'accountant_whatsapp' => $agent->accountant_whatsapp,
            'sales_person_whatsapp' => $agent->salesPerson?->username,
            'client_name' => $agent->client?->name,
        ]);
    }

    /**
     * Create payment request
     * POST /api/n8n/create-payment
     */
    public function createPayment(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:0.100',
            'customer_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $phone = AgentAuthorizedPhone::normalizePhone($validated['phone']);

        $authorizedPhone = AgentAuthorizedPhone::where('phone_number', $phone)
            ->where('is_active', true)
            ->with(['agent.client.myfatoorahCredential', 'agent.salesPerson'])
            ->first();

        if (!$authorizedPhone || !$authorizedPhone->agent) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized phone number',
            ], 403);
        }

        $agent = $authorizedPhone->agent;
        $client = $agent->client;

        if (!$client || !$client->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'Client not active',
            ], 403);
        }

        // Calculate service fee
        $amount = (float) $validated['amount'];
        $serviceFee = $client->calculateServiceFee($amount);
        $totalAmount = $amount + $serviceFee;

        // Generate unique invoice ID
        $invoiceId = 'CR-' . strtoupper(Str::random(8));

        try {
            // Create payment request record
            $paymentRequest = PaymentRequest::create([
                'client_id' => $client->id,
                'agent_id' => $agent->id,
                'myfatoorah_invoice_id' => $invoiceId,
                'amount' => $amount,
                'service_fee' => $serviceFee,
                'total_amount' => $totalAmount,
                'currency' => 'KWD',
                'customer_phone' => $phone,
                'customer_name' => $validated['customer_name'] ?? $authorizedPhone->full_name,
                'description' => $validated['description'] ?? "Payment for {$agent->company_name}",
                'status' => 'pending',
            ]);

            // Generate payment URL (our custom page)
            $paymentUrl = route('payment.show', $invoiceId);

            // Optionally create MyFatoorah invoice now
            if ($client->myfatoorahCredential && $client->myfatoorahCredential->is_active) {
                try {
                    $myfatoorah = new MyFatoorahService($client->id);
                    $response = $myfatoorah->executePayment(
                        amount: $totalAmount,
                        agencyName: $agent->company_name,
                        iataNumber: $agent->iata_number ?? 'N/A',
                        customerName: $paymentRequest->customer_name,
                        customerPhone: $phone,
                        callbackUrl: route('payment.callback', $invoiceId),
                        errorUrl: route('payment.failed', $invoiceId)
                    );

                    if ($response['IsSuccess'] && isset($response['Data'])) {
                        $paymentRequest->update([
                            'payment_url' => $response['Data']['PaymentURL'] ?? null,
                            'myfatoorah_response' => $response,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::warning('MyFatoorah pre-creation failed', [
                        'invoice_id' => $invoiceId,
                        'error' => $e->getMessage()
                    ]);
                    // Continue without MyFatoorah - we'll create on redirect
                }
            }

            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl,
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'service_fee' => $serviceFee,
                'total_amount' => $totalAmount,
                'currency' => 'KWD',
                'sales_person_whatsapp' => $agent->salesPerson?->username,
                'accountant_whatsapp' => $agent->accountant_whatsapp,
                'agent' => [
                    'company_name' => $agent->company_name,
                    'iata_number' => $agent->iata_number,
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Create payment failed', [
                'phone' => $phone,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Payment completed webhook
     * POST /api/n8n/payment-completed
     */
    public function paymentCompleted(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|string',
            'status' => 'required|string|in:paid,failed',
            'myfatoorah_data' => 'nullable|array',
        ]);

        $payment = PaymentRequest::where('myfatoorah_invoice_id', $validated['invoice_id'])
            ->with(['agent.salesPerson', 'client'])
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'error' => 'Payment not found',
            ], 404);
        }

        if ($validated['status'] === 'paid') {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'myfatoorah_response' => array_merge(
                    $payment->myfatoorah_response ?? [],
                    $validated['myfatoorah_data'] ?? []
                ),
            ]);
        } else {
            $payment->update([
                'status' => 'failed',
                'myfatoorah_response' => array_merge(
                    $payment->myfatoorah_response ?? [],
                    $validated['myfatoorah_data'] ?? []
                ),
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => $payment->status,
            'agent' => $payment->agent ? [
                'company_name' => $payment->agent->company_name,
                'iata_number' => $payment->agent->iata_number,
                'accountant_whatsapp' => $payment->agent->accountant_whatsapp,
            ] : null,
            'sales_person_phone' => $payment->agent?->salesPerson?->username,
            'accountant_phone' => $payment->agent?->accountant_whatsapp,
            'agent_whatsapp' => $payment->customer_phone,
            'amount' => $payment->amount,
            'total_amount' => $payment->total_amount,
        ]);
    }

    /**
     * Get payment status
     * GET /api/n8n/payment/{invoiceId}/status
     */
    public function getPaymentStatus(string $invoiceId)
    {
        $payment = PaymentRequest::where('myfatoorah_invoice_id', $invoiceId)
            ->with(['agent', 'client'])
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'error' => 'Payment not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'invoice_id' => $payment->myfatoorah_invoice_id,
            'status' => $payment->status,
            'amount' => $payment->amount,
            'service_fee' => $payment->service_fee,
            'total_amount' => $payment->total_amount,
            'currency' => $payment->currency,
            'created_at' => $payment->created_at->toIso8601String(),
            'paid_at' => $payment->paid_at?->toIso8601String(),
            'agent' => $payment->agent ? [
                'company_name' => $payment->agent->company_name,
                'iata_number' => $payment->agent->iata_number,
            ] : null,
        ]);
    }
}
