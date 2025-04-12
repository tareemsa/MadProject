<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\CodeExpirationService;
use App\Services\MailService;
use App\Services\Auth\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Exceptions\Auth\PasswordResetTokenInvalidException;

class PasswordResetService
{
    public function __construct(
        protected UserService $userService,
        protected CodeExpirationService $codeExpirationService,
        protected MailService $mailService
    ) {}

    public function sendPasswordResetLink(array $data): array
    {
        $user = $this->userService->getUserByEmail($data['email']);

        $token = Password::createToken($user);

        $this->codeExpirationService->storeCode($user->email, $token, 'reset_password');

        $resetLink = url("/password/reset/{$token}");

        $this->mailService->sendResetLink($user, $resetLink);

        return [
            'data' => [], 
            'message' => 'Password reset link sent ckeck your mail .',
            'code' => 200
        ];
        
    }

    public function resetPassword(array $data): array
    {
        $user = $this->userService->getUserByEmail($data['email']);

        $cachedToken = $this->codeExpirationService->getCode($user->email);

        if ($cachedToken !== $data['token']) {
            throw new PasswordResetTokenInvalidException();
        }

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        $this->codeExpirationService->forgetCode($user->email);

        return [
            'data' => [], 
            'message' => 'Password reset successfully.',
            'code' => 200
        ];
        
    }
}
