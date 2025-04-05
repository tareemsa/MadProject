<?php

/*use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;


//Route::post('/register', [RegisterController::class, 'register']);
//Route::post('/verify-code', [VerificationController::class, 'verify']);
//Route::post('/resend-code', [VerificationController::class, 'resend']);
//Route::post('/login', [LoginController::class, 'login']);*/

use App\Http\Controllers\Auth\TokenController;
use App\Http\Controllers\Auth\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/resend-verification', [AuthController::class, 'resendVerification']);
Route::middleware('auth:sanctum')->post('/refresh-token', [TokenController::class, 'refresh']);

