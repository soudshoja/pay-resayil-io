<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\WhatsappLog;

class ResayilWhatsAppService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.resayil.base_url');
        $this->apiKey = config('services.resayil.api_key');
    }

    /**
     * Send OTP code via WhatsApp
     */
    public function sendOTP(
        string $phoneNumber,
        string $otpCode,
        int $validityMinutes = 10,
        ?int $agencyId = null
    ): array {
        $message = $this->buildOTPMessage($otpCode, $validityMinutes);

        return $this->sendTextMessage(
            to: $phoneNumber,
            message: $message,
            messageType: 'otp',
            agencyId: $agencyId
        );
    }

    /**
     * Send payment link to customer
     */
    public function sendPaymentLink(
        string $phoneNumber,
        string $paymentUrl,
        float $amount,
        string $currency,
        string $agencyName,
        string $iataNumber,
        ?int $agencyId = null
    ): array {
        $message = $this->buildPaymentLinkMessage(
            $paymentUrl,
            $amount,
            $currency,
            $agencyName,
            $iataNumber
        );

        return $this->sendTextMessage(
            to: $phoneNumber,
            message: $message,
            messageType: 'payment_link',
            agencyId: $agencyId
        );
    }

    /**
     * Send payment confirmation to accountant
     */
    public function sendPaymentConfirmation(
        string $phoneNumber,
        string $agencyName,
        float $amount,
        string $currency,
        string $customerPhone,
        string $referenceId,
        ?int $agencyId = null
    ): array {
        $message = $this->buildPaymentConfirmationMessage(
            $agencyName,
            $amount,
            $currency,
            $customerPhone,
            $referenceId
        );

        return $this->sendTextMessage(
            to: $phoneNumber,
            message: $message,
            messageType: 'payment_confirmation',
            agencyId: $agencyId
        );
    }

    /**
     * Send custom message
     */
    public function sendCustomMessage(
        string $phoneNumber,
        string $message,
        ?int $agencyId = null,
        ?int $userId = null
    ): array {
        return $this->sendTextMessage(
            to: $phoneNumber,
            message: $message,
            messageType: 'custom',
            agencyId: $agencyId,
            userId: $userId
        );
    }

    /**
     * Core text message sending method
     */
    private function sendTextMessage(
        string $to,
        string $message,
        string $messageType,
        ?int $agencyId = null,
        ?int $userId = null
    ): array {
        $to = $this->normalizePhoneNumber($to);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/messages/send', [
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            $data = $response->json();
            $success = $response->successful() && ($data['success'] ?? false);

            // Log to database
            $this->logMessage(
                agencyId: $agencyId,
                userId: $userId,
                recipient: $to,
                messageType: $messageType,
                payload: ['message' => $message],
                response: $data,
                status: $success ? 'sent' : 'failed',
                messageId: $data['message_id'] ?? null
            );

            return [
                'success' => $success,
                'message_id' => $data['message_id'] ?? null,
                'data' => $data
            ];

        } catch (\Exception $e) {
            Log::error('Resayil WhatsApp Error', [
                'to' => $to,
                'type' => $messageType,
                'error' => $e->getMessage()
            ]);

            // Log failed attempt
            $this->logMessage(
                agencyId: $agencyId,
                userId: $userId,
                recipient: $to,
                messageType: $messageType,
                payload: ['message' => $message],
                response: ['error' => $e->getMessage()],
                status: 'failed'
            );

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check message delivery status
     */
    public function getMessageStatus(string $messageId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/messages/' . $messageId . '/status');

            return $response->json();

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Build OTP message (bilingual Arabic/English)
     */
    private function buildOTPMessage(string $otpCode, int $validityMinutes): string
    {
        return <<<MSG
رمز التحقق الخاص بك: *{$otpCode}*

Your verification code: *{$otpCode}*

صالح لمدة {$validityMinutes} دقائق
Valid for {$validityMinutes} minutes

⚠️ لا تشارك هذا الرمز مع أحد
⚠️ Do not share this code with anyone
MSG;
    }

    /**
     * Build payment link message (bilingual Arabic/English)
     */
    private function buildPaymentLinkMessage(
        string $paymentUrl,
        float $amount,
        string $currency,
        string $agencyName,
        string $iataNumber
    ): string {
        $formattedAmount = number_format($amount, 3);

        return <<<MSG
🏢 *{$agencyName}*
IATA: {$iataNumber}

━━━━━━━━━━━━━━━━━━

💰 المبلغ المطلوب: *{$formattedAmount} {$currency}*
💰 Amount Due: *{$formattedAmount} {$currency}*

━━━━━━━━━━━━━━━━━━

🔒 رابط الدفع الآمن:
🔒 Secure Payment Link:

{$paymentUrl}

━━━━━━━━━━━━━━━━━━

✅ ادفع الآن بأمان عبر KNET
✅ Pay now securely via KNET

⏰ صالح لمدة 24 ساعة
⏰ Valid for 24 hours
MSG;
    }

    /**
     * Build payment confirmation message (bilingual Arabic/English)
     */
    private function buildPaymentConfirmationMessage(
        string $agencyName,
        float $amount,
        string $currency,
        string $customerPhone,
        string $referenceId
    ): string {
        $formattedAmount = number_format($amount, 3);
        $timestamp = now()->timezone('Asia/Kuwait')->format('Y-m-d H:i:s');

        return <<<MSG
✅ *تأكيد دفعة جديدة*
✅ *New Payment Confirmed*

━━━━━━━━━━━━━━━━━━

🏢 الوكالة: {$agencyName}
🏢 Agency: {$agencyName}

💰 المبلغ: *{$formattedAmount} {$currency}*
💰 Amount: *{$formattedAmount} {$currency}*

📱 رقم العميل: {$customerPhone}
📱 Customer: {$customerPhone}

🔢 المرجع: {$referenceId}
🔢 Reference: {$referenceId}

🕐 التاريخ: {$timestamp} (Kuwait)
🕐 Date: {$timestamp} (Kuwait)

━━━━━━━━━━━━━━━━━━

تم الإستلام بنجاح ✓
Successfully received ✓
MSG;
    }

    /**
     * Normalize phone number to E.164 format
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Handle Kuwait 8-digit numbers
        if (strlen($phone) === 8) {
            $phone = '965' . $phone;
        }

        // Remove leading zeros
        $phone = ltrim($phone, '0');

        // Add + prefix
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Log message to database
     */
    private function logMessage(
        ?int $agencyId,
        ?int $userId,
        string $recipient,
        string $messageType,
        array $payload,
        array $response,
        string $status,
        ?string $messageId = null
    ): void {
        WhatsappLog::create([
            'agency_id' => $agencyId,
            'user_id' => $userId,
            'recipient' => $recipient,
            'message_type' => $messageType,
            'payload' => $payload,
            'response' => $response,
            'status' => $status,
            'message_id' => $messageId,
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }
}
