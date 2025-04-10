<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\ResendVerificationRequest;
use App\Http\Requests\PasswordRecoveryRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\TwoFactorRequest;
use App\Services\Auth\VerificationService;
use App\Services\Auth\PasswordResetService;
use App\Services\Auth\TokenService;
use App\Services\Auth\UserService;
use App\Services\Auth\TwoFactorService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private VerificationService $verificationService,
        private PasswordResetService $passwordResetService,
        private TokenService $tokenService,
        private UserService $userService,
        private TwoFactorService $twoFactorService
    ) {}

  
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());
        $this->verificationService->sendVerificationCode($user, 'email');

        return self::Success(
            ['user' => $user],
            'Registration successful. Please check your email for the verification code.'
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->userService->authenticateUser($request->email, $request->password);
    
        if (!$user->email_verified_at) {
            return self::Error([], 'Email not verified.', 403);
        }
    
        $this->twoFactorService->generate2FACode($user);
        $data = $this->verificationService->sendVerificationCode($user, '2fa');
    
        return self::Success($data['data'], $data['message']);
    }
    


    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        $response = $this->verificationService->verifyCode($request->validated(), $request->user_id);
        $this->userService->markEmailVerified($response['data']);

        $tokens = $this->tokenService->generateTokens($response['data']);

        return self::Success([
            'user' => $response['data'],
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
        ], $response['message']);
    }


    public function resendVerification(ResendVerificationRequest $request): JsonResponse
    {
        $user = $this->userService->getUserByEmail($request->email);
        $this->verificationService->sendVerificationCode($user, 'email');

        return self::Success([], 'Verification code resent successfully.');
    }

    public function refreshToken(Request $request): JsonResponse
    {
        $response = $this->tokenService->refreshToken($request);

        return self::Success([
            'access_token' => $response['access_token'],
        ], $response['message']);
    }

    public function sendPasswordRecoveryLink(PasswordRecoveryRequest $request): JsonResponse
    {
        $response = $this->passwordResetService->sendPasswordResetLink($request->validated());

        return self::Success([], $response['message'], $response['status']);
    }

    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {
        $response = $this->passwordResetService->resetPassword($request->validated());

        return self::Success([], $response['message'], $response['status']);
    }

    public function verify2FA(TwoFactorRequest $request): JsonResponse
    {
        $response = $this->twoFactorService->verify2FACode($request->user(), $request->code);

        return self::Success(['verified' => $response['data']], $response['message']);
    }
}
