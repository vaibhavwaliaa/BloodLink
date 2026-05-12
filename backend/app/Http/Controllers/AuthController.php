<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TwilioService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // Redirect to Google OAuth
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    // Handle Google OAuth callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                ]
            );

            $token = JWT::encode([
                'id' => (string) $user->_id,
                'email' => $user->email,
                'name' => $user->name,
                'exp' => time() + (7 * 24 * 60 * 60), // 7 days
            ], config('services.jwt.secret'), 'HS256');

            $clientUrl = config('app.client_url', 'http://127.0.0.1:8000');

            return redirect("{$clientUrl}/oauth/callback?token={$token}");
        } catch (\Exception $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect(config('app.client_url', 'http://127.0.0.1:8000') . '?error=auth_failed');
        }
    }

    // Get current user from JWT
    public function me(Request $request)
    {
        $user = $this->getUserFromToken($request);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($user);
    }

    // Send OTP to phone number
    public function sendOtp(Request $request)
    {
        $request->validate(['phoneNumber' => 'required|string']);

        $user = $this->getUserFromToken($request);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $phone = $request->input('phoneNumber');

        // Store OTP in cache for 5 minutes
        Cache::put("otp_{$user->_id}", [
            'code' => $otp,
            'phone' => $phone,
            'expires' => now()->addMinutes(5)->timestamp,
        ], 300);

        $twilio = new TwilioService();
        $sent = $twilio->sendOTP($phone, $otp);

        if ($sent) {
            return response()->json(['message' => 'OTP sent successfully']);
        }
        return response()->json(['error' => 'Failed to send OTP'], 500);
    }

    // Verify OTP and register as donor
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
            'bloodType' => 'required|string',
            'city' => 'required|string',
        ]);

        $user = $this->getUserFromToken($request);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cached = Cache::get("otp_{$user->_id}");

        // Accept 123456 as mock override when Twilio isn't configured
        $isMockOverride = !config('services.twilio.sid') && $request->input('otp') === '123456';

        if (!$isMockOverride) {
            if (!$cached || $cached['expires'] < now()->timestamp) {
                return response()->json(['error' => 'OTP expired or not requested'], 400);
            }
            if ($cached['code'] !== $request->input('otp')) {
                return response()->json(['error' => 'Invalid OTP'], 400);
            }
        }

        $phone = $cached ? $cached['phone'] : '0000000000';

        $user->update([
            'blood_group' => $request->input('bloodType'),
            'city' => $request->input('city'),
            'contact_number' => $phone,
            'is_donor' => true,
            'is_available' => true,
        ]);

        Cache::forget("otp_{$user->_id}");

        return response()->json(['message' => 'Donor registered successfully!']);
    }

    // Helper: extract user from JWT Bearer token
    private function getUserFromToken(Request $request): ?User
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }

        try {
            $token = substr($authHeader, 7);
            $decoded = JWT::decode($token, new Key(config('services.jwt.secret'), 'HS256'));
            return User::find($decoded->id);
        } catch (\Exception $e) {
            Log::error('JWT decode error: ' . $e->getMessage());
            return null;
        }
    }
}
