<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected ?Client $client = null;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');

        if ($sid && $token && str_starts_with($sid, 'AC')) {
            try {
                $this->client = new Client($sid, $token);
                Log::info('[Twilio] Client initialized successfully.');
            } catch (\Exception $e) {
                Log::error('[Twilio] Failed to initialize: ' . $e->getMessage());
            }
        } else {
            Log::warning('[Twilio] Credentials not configured. SMS disabled.');
        }
    }

    public function sendOTP(string $phone, string $code): bool
    {
        if (!$this->client) {
            Log::info("[OTP] Twilio not configured. MOCK OTP for {$phone}: {$code}");
            return true;
        }

        try {
            Log::info("[OTP] Sending to {$phone} via Messaging Service");
            $msg = $this->client->messages->create($phone, [
                'messagingServiceSid' => config('services.twilio.messaging_service_sid'),
                'body' => "Your BloodLink donor verification code is: {$code}",
            ]);
            Log::info("[OTP] Sent successfully. SID: {$msg->sid}");
            return true;
        } catch (\Exception $e) {
            Log::error("[OTP] Error: {$e->getMessage()}");
            return false;
        }
    }

    public function sendEmergencySMS(array $phoneNumbers, $request): void
    {
        if (!$this->client) {
            Log::info('[SMS] Twilio not configured. Would send to: ' . implode(', ', $phoneNumbers));
            return;
        }

        $message = "URGENT [{$request->urgency_level}]: {$request->patient_name} needs {$request->blood_type} at {$request->hospital_name}, {$request->city}. Call: {$request->contact_number} -BloodLink";

        foreach ($phoneNumbers as $number) {
            try {
                $this->client->messages->create($number, [
                    'messagingServiceSid' => config('services.twilio.messaging_service_sid'),
                    'body' => $message,
                ]);
            } catch (\Exception $e) {
                Log::error("[SMS] Failed to send to {$number}: {$e->getMessage()}");
            }
        }

        Log::info("[SMS] Sent " . count($phoneNumbers) . " emergency SMS alerts.");
    }
}
