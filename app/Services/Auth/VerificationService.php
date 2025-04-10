<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Services\MailService;
use App\Exceptions\Auth\VerificationCodeExpiredException;
use App\Exceptions\Auth\VerificationCodeInvalidException;
use App\Exceptions\Auth\VerificationCodeTypeMismatchException;

class VerificationService
{
    public function __construct(private MailService $mailService) {}

    public function generateCode(User $user, string $type): int
    {
        $code = rand(100000, 999999);

        Cache::put(
            $this->getCacheKey($user),
            ['code' => $code, 'type' => $type],
            now()->addMinutes(10)
        );

        return $code;
    }

    public function sendVerificationCode(User $user, string $type): void
    {
        $code = $this->generateCode($user, $type);
        $this->mailService->sendVerificationEmail($user, $code);
    }

    public function verifyCode(array $request, int $userId): array
    {
        $user = User::findOrFail($userId);
        $data = Cache::get($this->getCacheKey($user));

        if (!$data) {
            throw new VerificationCodeExpiredException();
        }

        if ($data['code'] != $request['code']) {
            throw new VerificationCodeInvalidException();
        }

        if ($data['type'] !== $request['type']) {
            throw new VerificationCodeTypeMismatchException();
        }

        Cache::forget($this->getCacheKey($user));

        return [
            'data' => $user,
            'message' => 'Verification code confirmed successfully.'
        ];
    }

    public function refreshCode(array $request, int $userId): array
    {
        $user = User::findOrFail($userId);
        Cache::forget($this->getCacheKey($user));
        $this->sendVerificationCode($user, $request['type']);

        return [
            'data' => $user,
            'message' => 'Verification code refreshed and resent.'
        ];
    }

    private function getCacheKey(User $user): string
    {
        return "user_code_{$user->id}";
    }
}
