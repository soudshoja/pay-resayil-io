<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\MyfatoorahCredential;

class MyFatoorahService
{
    private string $apiKey;
    private string $baseUrl;
    private bool $isTestMode;
    private string $countryCode;
    private ?int $agencyId;

    public function __construct(?int $agencyId = null)
    {
        $this->agencyId = $agencyId;

        if ($agencyId) {
            $credentials = MyfatoorahCredential::where('agency_id', $agencyId)
                ->where('is_active', true)
                ->first();

            if (!$credentials) {
                throw new \Exception('MyFatoorah credentials not configured for this agency');
            }

            $this->apiKey = $credentials->api_key;
            $this->isTestMode = $credentials->is_test_mode;
            $this->countryCode = $credentials->country_code;
            $this->baseUrl = $credentials->base_url;
        } else {
            // Fallback to default config
            $this->apiKey = config('services.myfatoorah.api_key');
            $this->isTestMode = config('services.myfatoorah.test_mode', true);
            $this->countryCode = config('services.myfatoorah.country_code', 'KWT');
            $this->baseUrl = config('services.myfatoorah.base_url', 'https://apitest.myfatoorah.com');
        }
    }

    /**
     * Get KNET payment method ID
     */
    public function getKnetPaymentMethodId(float $amount): int
    {
        $cacheKey = 'myfatoorah_knet_id_' . ($this->agencyId ?? 'default');

        return Cache::remember($cacheKey, 3600, function () use ($amount) {
            $response = $this->initiatePayment($amount, 'KWD');

            foreach ($response['Data']['PaymentMethods'] as $method) {
                if ($method['PaymentMethodCode'] === 'kn') {
                    return $method['PaymentMethodId'];
                }
            }

            throw new \Exception('KNET payment method not available');
        });
    }

    /**
     * Initiate payment (get available payment methods)
     */
    public function initiatePayment(float $amount, string $currency = 'KWD'): array
    {
        return $this->request('POST', '/v2/InitiatePayment', [
            'InvoiceAmount' => $amount,
            'CurrencyIso' => $currency,
        ]);
    }

    /**
     * Execute payment (create payment link)
     */
    public function executePayment(
        float $amount,
        string $agencyName,
        string $iataNumber,
        ?string $customerName = null,
        ?string $customerPhone = null,
        ?string $customerEmail = null,
        ?string $callbackUrl = null,
        ?string $errorUrl = null,
        ?array $metadata = null
    ): array {
        // Get KNET payment method ID
        $paymentMethodId = $this->getKnetPaymentMethodId($amount);

        // Clean phone number
        $cleanPhone = $customerPhone ? preg_replace('/[^0-9]/', '', $customerPhone) : null;
        if ($cleanPhone && strlen($cleanPhone) > 8) {
            $cleanPhone = substr($cleanPhone, -8); // Get last 8 digits for Kuwait
        }

        $payload = [
            'PaymentMethodId' => $paymentMethodId,
            'InvoiceValue' => $amount,
            'DisplayCurrencyIso' => 'KWD',
            'CallBackUrl' => $callbackUrl ?? route('myfatoorah.callback'),
            'ErrorUrl' => $errorUrl ?? route('myfatoorah.error'),
            'Language' => 'ar',
            'CustomerName' => $customerName ?? 'Customer',
            'MobileCountryCode' => '+965',
            'CustomerMobile' => $cleanPhone,
            'CustomerEmail' => $customerEmail,
            'InvoiceItems' => [
                [
                    'ItemName' => "Payment for {$agencyName} (IATA: {$iataNumber})",
                    'Quantity' => 1,
                    'UnitPrice' => $amount,
                ]
            ],
            'UserDefinedField' => json_encode(array_merge([
                'agency_name' => $agencyName,
                'iata_number' => $iataNumber,
            ], $metadata ?? [])),
        ];

        return $this->request('POST', '/v2/ExecutePayment', $payload);
    }

    /**
     * Create simple payment link
     */
    public function createPayment(
        float $amount,
        string $customerName,
        string $customerPhone,
        string $description,
        array $metadata = []
    ): array {
        // Get agency info from metadata or use defaults
        $agencyName = $metadata['agency_name'] ?? 'Collect Resayil';
        $iataNumber = $metadata['iata_number'] ?? 'N/A';

        return $this->executePayment(
            amount: $amount,
            agencyName: $agencyName,
            iataNumber: $iataNumber,
            customerName: $customerName,
            customerPhone: $customerPhone,
            metadata: $metadata
        );
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $key, string $keyType = 'PaymentId'): array
    {
        return $this->request('POST', '/v2/getPaymentStatus', [
            'Key' => $key,
            'KeyType' => $keyType,
        ]);
    }

    /**
     * Get payment status by invoice ID
     */
    public function getPaymentStatusByInvoice(string $invoiceId): array
    {
        return $this->getPaymentStatus($invoiceId, 'InvoiceId');
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(array $payload, string $signature): bool
    {
        $expectedSignature = hash_hmac('sha256', json_encode($payload), $this->apiKey);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Make HTTP request to MyFatoorah API
     */
    private function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->send($method, $this->baseUrl . $endpoint, [
                'json' => $data
            ]);

            $result = $response->json();

            if (!$response->successful() || !($result['IsSuccess'] ?? false)) {
                $errors = collect($result['ValidationErrors'] ?? [])
                    ->pluck('Error')
                    ->implode(', ');

                $message = $result['Message'] ?? 'Unknown error';
                if ($errors) {
                    $message .= ': ' . $errors;
                }

                Log::error('MyFatoorah API Error', [
                    'endpoint' => $endpoint,
                    'error' => $message,
                    'response' => $result
                ]);

                throw new \Exception($message);
            }

            return $result;

        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('MyFatoorah HTTP Error', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);

            throw new \Exception('Payment service unavailable: ' . $e->getMessage());
        }
    }
}
