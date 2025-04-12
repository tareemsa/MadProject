<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\CustomException;

class AuthService
{
    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new CustomException('Invalid email or password.', 401);
        }

        $user = Auth::user();

        if (! $user) {
            throw new CustomException('User not found.', 404);
        }

        return [
            'user' => $user,
            'message' => 'Login successful. 2FA code sent.',
            'status' => 200
        ];
    }
    
public function sendResetLink(array $data): array
{
    $status = Password::sendResetLink(['email' => $data['email']]);

    if ($status !== Password::RESET_LINK_SENT) {
        throw new CustomException('Failed to send password reset link.', 500);
    }

    return [
        'message' => 'Password recovery link sent successfully.',
        'status' => 200
    ];
}

public function resetPassword(array $data): array
{
    $status = Password::reset(
        $data,
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    if ($status !== Password::PASSWORD_RESET) {
        throw new CustomException('Invalid token or email.', 400);
    }

    return [
        'message' => 'Password has been reset successfully.',
        'status' => 200
    ];
}
}
