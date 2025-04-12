<?php

namespace App\Services\Auth;

use App\Enums\TokenAbility;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\Auth\UserNotFoundException;

class TokenService
{
    /**
     * Generate access and refresh tokens.
     */
    public function generateTokens(User $user): array
    {
        if (! $user) {
            throw new UserNotFoundException();
        }

        // Generate access token with specific ability
        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value]
        );

        // Generate refresh token (different name, can have different scopes)
        $refreshToken = $user->createToken(
            'refresh_token',
            ['refresh']
        );

        return [
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'message' => 'Tokens generated successfully.',
            'status' => 200
        ];
    }

    /**
     * Refresh access token using a valid refresh token.
     */
    public function refreshToken(Request $request): array
    {
        $user = $request->user();

        if (! $user) {
            throw new UserNotFoundException();
        }

        // Invalidate current access token
        $user->currentAccessToken()?->delete();

        // Create a new access token
        $newAccessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value]
        );

        // Optionally invalidate old refresh tokens (single use)
        $user->tokens()->where('name', 'refresh_token')->delete();

        return [
            'access_token' => $newAccessToken->plainTextToken,
            'message' => 'Access token refreshed successfully.',
            'status' => 200
        ];
    }
}
