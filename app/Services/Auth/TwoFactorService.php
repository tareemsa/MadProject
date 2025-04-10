<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\Auth\TwoFactorFailedException;

class TwoFactorService
{
    /**
     * توليد كود 2FA وتخزينه بالكاش
     */
    public function generate2FACode(User $user): array
    {
        $code = (string) rand(100000, 999999);

        Cache::put($this->getCacheKey($user), $code, now()->addMinutes(10)); // يخزن الكود لمدة 10 دقايق

        return [
            'data' => $code,
            'message' => '2FA code generated and cached successfully.',
        ];
    }

    /**
     * التحقق من كود 2FA المخزن بالكاش
     */
    public function verify2FACode(User $user, string $code): array
    {
        $cachedCode = Cache::get($this->getCacheKey($user));

        if (!$cachedCode || $cachedCode !== $code) {
            throw new TwoFactorFailedException();
        }

        Cache::forget($this->getCacheKey($user)); // حذف الكود بعد التحقق

        return [
            'data' => true,
            'message' => '2FA verification successful.',
        ];
    }

    /**
     * توليد اسم الكاش كي للمستخدم
     */
    private function getCacheKey(User $user): string
    {
        return '2fa_code_user_' . $user->id;
    }
}
