<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function refresh(Request $request)
    {
        $user = $request->user();

        // Delete the current access token
        $request->user()->currentAccessToken()->delete();

        // Create a new token
        $newToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully.',
            'token' => $newToken,
        ]);
    }
}

