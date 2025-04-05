<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $verification = VerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->orderByDesc('created_at')
            ->first();

        if (! $verification) {
            return response()->json(['message' => 'Invalid verification code.'], 422);
        }

        if (Carbon::now()->gt($verification->expires_at)) {
            return response()->json(['message' => 'Verification code expired.'], 410);
        }

        $user->update([
            'email_verified_at' => now(),
        ]);

        // Optionally delete the used code
        $verification->delete();

        return response()->json(['message' => 'Email verified successfully.']);
    }
    public function resend(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    if ($user->email_verified_at) {
        return response()->json(['message' => 'Email already verified.'], 400);
    }

    // Delete old codes
    VerificationCode::where('user_id', $user->id)->delete();

    // Generate a new code
    $newCode = Str::upper(Str::random(6)); 

    VerificationCode::create([
        'user_id'    => $user->id,
        'code'       => $newCode,
        'expires_at' => Carbon::now()->addMinutes(10),
    ]);

    // Send via email
    Mail::raw("Your new verification code is: $newCode", function ($message) use ($user) {
        $message->to($user->email)->subject('Your New Verification Code');
    });

    return response()->json(['message' => 'New verification code sent.']);
}
}
