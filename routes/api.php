<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MediaController;

Route::prefix('auth')->name('auth.')->group(function () {
    
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');
    Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('resend-verification');
    Route::post('/password/recover', [AuthController::class, 'sendResetLink'])->name('password.recover');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/2fa/verify', [AuthController::class, 'verify2FA'])->name('2fa.verify');
   
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('token.refresh');
       Route::post('/user/upload-image', [MediaController::class, 'uploadMedia']);
    });

});


