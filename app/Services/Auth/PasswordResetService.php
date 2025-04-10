<?php


namespace App\Services\Auth;


use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\Auth\UserNotFoundException;
use App\Exceptions\Auth\PasswordResetTokenInvalidException;
use App\Exceptions\Auth\EmailSendFailedException;

class PasswordResetService
{
    public function sendPasswordResetLink(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        $token = Password::createToken($user);
        Cache::put("password_reset_{$data['email']}", $token, 600);

        Mail::raw("Reset your password using this link: " . url("/password/reset/{$token}"), function ($message) use ($data) {
            $message->to($data['email'])->subject('Password Reset');
        });

        if (count(Mail::failures()) > 0) {
            throw new EmailSendFailedException();
        }

        return [
            'success' => true,
            'message' => 'Password recovery link sent successfully.',
            'status' => 200
        ];
    }

    public function resetPassword(array $data): array
    {
        $cachedToken = Cache::get("password_reset_{$data['email']}");

        if ($cachedToken !== $data['token']) {
            throw new PasswordResetTokenInvalidException();
        }

        User::where('email', $data['email'])->update([
            'password' => Hash::make($data['password'])
        ]);

        Cache::forget("password_reset_{$data['email']}");

        return [
            'success' => true,
            'message' => 'Password reset successfully.',
            'status' => 200
        ];
    }
}
