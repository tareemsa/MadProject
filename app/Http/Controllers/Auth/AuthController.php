<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\ResendVerificationRequest;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        if ($result['success']) {
            return $this->successResponse($result['message'], $result['data']);
        }

        return $this->errorResponse($result['message'], 400);
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request->validated());

        if (!$response['success']) {
            return $this->errorResponse($response['message'], $response['status']);
        }

        return $this->successResponse($response['message'], $response['data'], $response['status']);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $result = $this->authService->verifyEmail($request->email, $request->code);

        if ($result['success']) {
            return $this->successResponse($result['message']);
        }

        return $this->errorResponse($result['message'], 422);
    }

    public function resendVerification(ResendVerificationRequest $request)
    {
        $result = $this->authService->resendVerificationCode($request->email);

        if ($result['success']) {
            return $this->successResponse($result['message']);
        }

        return $this->errorResponse($result['message'], 400);
    }
}

