<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\User;
use App\Exceptions\CustomException;
use App\Services\CodeExpirationService;
use App\Services\MailService;


class VerificationService
{

    protected CodeExpirationService $codeExpirationService;
    protected MailService $mailService;
    
    public function __construct(CodeExpirationService $codeExpirationService, MailService $mailService)
    {
        $this->codeExpirationService = $codeExpirationService;
        $this->mailService = $mailService; 
    }
    

    public function generateCode(User $user, string $type = 'registration'): string
    {
        $code = Str::random(6);

        $stored = $this->codeExpirationService->storeCode($user->email, $code, $type);

        if (!$stored) {
            throw new CustomException('Failed to store verification code', 500);
        }

        return $code;
    }

    public function verifyCode(User $user, string $inputCode): array
    {
        $storedCode = $this->codeExpirationService->getCode($user->email);

        if (!$storedCode) {
            throw new CustomException('Verification code expired or not found', 404);
        }

        if ($storedCode !== $inputCode) {
            throw new CustomException('Invalid verification code', 400);
        }

        $this->codeExpirationService->forgetCode($user->email);

        return ['data' => $user, 'message' => 'The code is correct. You have successfully verified.', 'code' => 200];

    }
    public function resendVerificationCode(User $user, string $type = 'registration'): array
{
    if ($user->email_verified_at) {
        return [
            'success' => false,
            'message' => 'Email already verified.',
            'status' => 400,
        ];
    }

    $code = $this->generateCode($user, $type);

    $this->mailService->sendVerificationCode($user, $code);


    return [
        'success' => true,
        'message' => 'New verification code sent.',
        'status' => 200,
    ];
}

}

