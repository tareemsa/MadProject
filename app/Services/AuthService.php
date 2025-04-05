<?php

namespace App\Services;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthService
{
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $code = Str::random(6);
        VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)->subject('Email Verification Code');
        });

        return ['success' => true, 'message' => 'User registered successfully. Please check your email.', 'data' => $user];
    }

    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return ['success' => false, 'message' => 'Invalid credentials.', 'status' => 401];
        }

        if (!$user->email_verified_at) {
            return ['success' => false, 'message' => 'Email not verified.', 'status' => 403];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ['success' => true, 'message' => 'Login successful.', 'data' => ['token' => $token, 'user' => $user]];
    }

    public function verifyEmail(string $email, string $code)
    {
        $user = User::where('email', $email)->first();
        if (!$user) return ['success' => false, 'message' => 'User not found.'];

        $verification = VerificationCode::where('user_id', $user->id)->where('code', $code)->first();

        if (!$verification || Carbon::now()->gt($verification->expires_at)) {
            return ['success' => false, 'message' => 'Invalid or expired verification code.'];
        }

        $user->update(['email_verified_at' => now()]);
        $verification->delete();

        return ['success' => true, 'message' => 'Email verified successfully.'];
    }
    
}