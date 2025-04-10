<?php

namespace App\Services\Auth;

use App\Enums\TokenAbility;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Exceptions\Auth\UserNotFoundException;
use App\Exceptions\Auth\TokenExpiredException;



class TokenService
{
    public function generateTokens(User $user): array
    {
        if (!$user) {
            throw new UserNotFoundException();
        }

        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $refreshToken = $user->createToken('refresh_token', ['refresh']);

        return [
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ];
    }

    public function refreshToken(Request $request): array
    {
        $user = $request->user();

        if (!$user) {
            throw new UserNotFoundException();
        }

        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $user->tokens()->where('name', 'refresh_token')->delete();

        return [
            'access_token' => $accessToken->plainTextToken,
            'message' => 'Access token generated successfully',
        ];
    }
}
