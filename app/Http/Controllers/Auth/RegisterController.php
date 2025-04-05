<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate verification code
        $code = Str::random(6);
        VerificationCode::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send email
        Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Email Verification Code');
        });

        return response()->json([
            'message' => 'User registered successfully. Please check your email for the verification code.'
        ], 201);
    }
}
