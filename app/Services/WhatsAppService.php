<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send OTP message via WhatsApp (StarSender)
     *
     * @param string $phone
     * @param string $otp
     * @return bool
     */
    public function sendOTP($phone, $otp): bool
    {
        $apiKey = Settings::where('key', 'whatsapp_api_key')->value('value');

        if (!$apiKey) {
            Log::error('WhatsApp API Key not found in settings.');
            return false;
        }

        $message = "*{$otp}* adalah kode verifikasi Anda. Jangan berikan kode ini kepada siapapun.";

        // Use cURL as per user request snippet, but wrapped in Laravel HTTP client for better testing/mocking if possible.
        // However, user specifically asked for cURL implementation style or at least functionality.
        // Let's stick to a clean implementation using Laravel's Http client which is cleaner but accomplishes the same.
        // If strict cURL is needed we can do that, but Http client is standard.
        // User's snippet used cURL, let's try to mimic that to be safe or use Http if robust.
        // Let's use standard Http client for maintainability unless there's a specific constraint.
        
        $payload = [
            "messageType" => "text",
            "to" => $phone,
            "body" => $message,
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $apiKey
            ])->post('https://api.starsender.online/api/send', $payload);

            if ($response->successful()) {
                return true;
            }

            Log::error('WhatsApp API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp API Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification message via WhatsApp
     *
     * @param string $phone Phone number
     * @param string $message Message content
     * @return bool
     */
    public function sendMessage(string $phone, string $message): bool
    {
        $apiKey = Settings::where('key', 'whatsapp_api_key')->value('value');

        if (!$apiKey) {
            Log::error('WhatsApp API Key not found in settings.');
            return false;
        }

        $payload = [
            "messageType" => "text",
            "to" => $phone,
            "body" => $message,
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $apiKey
            ])->post('https://api.starsender.online/api/send', $payload);

            if ($response->successful()) {
                Log::info('WhatsApp notification sent successfully', ['phone' => $phone]);
                return true;
            }

            Log::error('WhatsApp API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp API Exception: ' . $e->getMessage());
            return false;
        }
    }
}

