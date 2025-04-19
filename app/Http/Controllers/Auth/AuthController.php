<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\TwoFactorRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\ResendVerificationRequest;
use App\Services\Auth\UserService;
use App\Services\Auth\authService;
use App\Services\Auth\VerificationService;
use App\Services\MailService;
use App\Services\Auth\TwoFactorService;
use App\Http\Requests\PasswordRecoveryRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Services\Auth\PasswordResetService;
use App\Services\MediaService;
use App\Services\Auth\TokenService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{
    
    use ApiResponseTrait;

    protected AuthService $authService;
    protected UserService $userService;
    protected TwoFactorService $twoFactorService;
    protected TokenService $tokenService;
    protected MailService $mailService;
    protected PasswordResetService $passwordResetService;
    protected VerificationService $verificationService;
    

    public function __construct(
        AuthService $authService,
        UserService $userService,
        TwoFactorService $twoFactorService,
        TokenService $tokenService,
        MailService $mailService,
        PasswordResetService $passwordResetService,
        MediaService $mediaService,
        VerificationService $verificationService
    ) {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->twoFactorService = $twoFactorService;
        $this->tokenService = $tokenService;
        $this->mailService = $mailService;
        $this->passwordResetService = $passwordResetService;
        $this->mediaService = $mediaService;
        $this->verificationService = $verificationService;
    }
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->userService->createUser($request->validated());

        if ($request->hasFile('image')) {
            $this->mediaService->storeForModel($data['user'], $request->file('image'));
        }
        $code = $this->verificationService->generateCode($data['user'],'registration');
        $this->mailService->sendVerificationCode($data['user'], $code);

        return self::Success([
            'user' => $data['user'],
            'code' => $code,
        ], $data['message']);
    }        
    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
{
 
    $user = $this->userService->getUserByEmail($request->email);

    $this->verificationService->verifyCode($user, $request->code);

    $user->update(['email_verified_at' => now()]);

    return self::Success([], 'Email verified successfully.');
}
public function resendVerification(ResendVerificationRequest $request): JsonResponse
{

    $user = $this->userService->getUserByEmail($request->email);

    $result = $this->verificationService->resendVerificationCode($user);

    if (! $result['success']) {
        return self::Error([], $result['message'], $result['status']);
    }

    return self::Success([], $result['message'], $result['status']);
}

public function refreshToken(Request $request): JsonResponse
{
    $result = $this->tokenService->refreshToken($request);

    return self::Success([
        'access_token' => $result['access_token'],
    ], $result['message'], $result['status']);
}

public function login(LoginRequest $request): JsonResponse
{
    $result = $this->authService->login($request->validated());

    $code = $this->twoFactorService->generate2FACode($result['user']);

    $this->mailService->sendVerificationCode($result['user'], $code);

    return self::Success([], $result['message'], $result['status']);
}

public function verify2FA(TwoFactorRequest $request): JsonResponse
{
    $user = $this->userService->getUserByEmail($request->email);

    $this->twoFactorService->verify2FACode($user, $request->code);

    $tokens = $this->tokenService->generateTokens($user);

    return self::Success([
        'access_token' => $tokens['access_token'],
        'refresh_token' => $tokens['refresh_token'],
    ], $tokens['message'], $tokens['status']);
}


public function sendResetLink(PasswordRecoveryRequest $request): JsonResponse
{
    $result = $this->passwordResetService->sendPasswordResetLink($request->validated());

    return self::Success($result['data'], $result['message'], $result['code']);
}


public function resetPassword(PasswordResetRequest $request): JsonResponse
{
    $result = $this->passwordResetService->resetPassword($request->validated());

    return self::Success($result['data'], $result['message'], $result['code']);
}
}


