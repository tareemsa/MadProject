<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendVerificationCode(User $user, string $code): void
    {
        Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Email Verification Code');
        });
    }
    public function sendResetLink(User $user, string $link): void
{
    Mail::raw("Reset your password using this link: $link", function ($message) use ($user) {
        $message->to($user->email)->subject('Password Reset');
    });
}
}
