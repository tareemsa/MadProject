<?php


namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class CodeExpirationService
{
    public function storeCode(string $email, string $code, string $type): bool
    {
        $minutes = config("verification.{$type}_code_expiration");

        return Cache::put(
            $this->getCacheKey($email),
            $code,
            Carbon::now()->addMinutes($minutes)
        );
    }

    public function getCode(string $email): ?string
    {
        return Cache::get($this->getCacheKey($email));
    }

    public function forgetCode(string $email): void
    {
        Cache::forget($this->getCacheKey($email));
    }

    private function getCacheKey(string $email): string
    {
        return 'verification_code_' . md5($email);
    }
}
