<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\User;
use App\Services\TwilioService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BloodRequestController extends Controller
{
    // Create a new blood request and notify donors
    public function store(Request $request)
    {
        $request->validate([
            'patientName' => 'required|string',
            'bloodType' => 'required|string',
            'hospitalName' => 'required|string',
            'unitsRequired' => 'required|integer|min:1',
            'urgencyLevel' => 'required|in:NORMAL,HIGH,CRITICAL',
            'contactNumber' => 'required|string',
            'city' => 'required|string',
        ]);

        $userId = $this->getUserIdFromToken($request);

        $bloodRequest = BloodRequest::create([
            'requester_id' => $userId,
            'patient_name' => $request->input('patientName'),
            'blood_type' => $request->input('bloodType'),
            'hospital_name' => $request->input('hospitalName'),
            'units_required' => $request->input('unitsRequired'),
            'urgency_level' => $request->input('urgencyLevel'),
            'contact_number' => $request->input('contactNumber'),
            'city' => $request->input('city'),
            'location' => $request->input('location', [
                'type' => 'Point',
                'coordinates' => [76.7794, 30.7333],
            ]),
            'status' => 'PENDING',
        ]);

        Log::info("\n==============================");
        Log::info("  NEW BLOOD REQUEST SAVED");
        Log::info("  Patient: {$bloodRequest->patient_name} | Blood: {$bloodRequest->blood_type} | City: {$bloodRequest->city}");
        Log::info("==============================");

        // Find ALL verified donors, exclude the requester
        $query = User::where('is_donor', true)
            ->where('is_available', true)
            ->whereNotNull('contact_number')
            ->where('contact_number', '!=', '')
            ->where('contact_number', '!=', '0000000000');

        if ($userId) {
            $query->where('_id', '!=', $userId);
        }

        $donors = $query->get();

        Log::info("[Notification] Found {$donors->count()} donors to notify.");
        foreach ($donors as $d) {
            Log::info("  -> {$d->email} | {$d->contact_number}");
        }

        if ($donors->count() > 0) {
            $phones = $donors->pluck('contact_number')->filter()->toArray();
            Log::info("[Notification] Sending SMS to " . count($phones) . " numbers...");
            $twilio = new TwilioService();
            $twilio->sendEmergencySMS($phones, $bloodRequest);
        } else {
            Log::info("[Notification] No available donors found.");
        }

        return response()->json([
            'message' => 'Request created successfully',
            'request' => $bloodRequest,
            'notifiedDonorsCount' => $donors->count(),
        ], 201);
    }

    // List pending requests
    public function index(Request $request)
    {
        $query = BloodRequest::where('status', 'PENDING');

        if ($city = $request->query('city')) {
            $query->where('city', 'regexp', "/{$city}/i");
        }

        $requests = $query->orderBy('created_at', 'desc')->limit(50)->get();

        return response()->json($requests);
    }

    // Debug: list all users
    public function debugDonors()
    {
        $users = User::all(['email', 'name', 'is_donor', 'is_available', 'contact_number', 'blood_group', 'city']);
        return response()->json($users);
    }

    // Helper: get user ID from JWT
    private function getUserIdFromToken(Request $request): ?string
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }

        try {
            $token = substr($authHeader, 7);
            $decoded = JWT::decode($token, new Key(config('services.jwt.secret'), 'HS256'));
            return $decoded->id;
        } catch (\Exception $e) {
            return null;
        }
    }
}
