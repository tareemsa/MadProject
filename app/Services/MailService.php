<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;


class MailService
{
    public function sendVerificationEmail(User $user, string $code): void
    {
        Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            $message->to($user->email)->subject('Email Verification Code');
        });

      
    }
}
