<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Exceptions\CustomException;
use App\Services\CodeExpirationService;
use Illuminate\Support\Str;

class TwoFactorService
{
    protected CodeExpirationService $codeExpirationService;

    public function __construct(CodeExpirationService $codeExpirationService)
    {
        $this->codeExpirationService = $codeExpirationService;
    }

    public function generate2FACode(User $user): string
    {
        $code = Str::random(6);

        $stored = $this->codeExpirationService->storeCode($user->email, $code, '2fa');

        if (! $stored) {
            throw new CustomException('Failed to generate 2FA code.', 500);
        }

        return $code;
    }

    public function verify2FACode(User $user, string $inputCode): bool
    {
        $storedCode = $this->codeExpirationService->getCode($user->email);

        if (! $storedCode || $storedCode !== $inputCode) {
            throw new CustomException('Invalid or expired 2FA code.', 403);
        }

        $this->codeExpirationService->forgetCode($user->email);

        return true;
    }
}
