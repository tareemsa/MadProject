<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\SubscriptionController;
Route::prefix('auth')->name('auth.')->group(function () {
    
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify-email');
    Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('resend-verification');
    Route::post('/password/recover', [AuthController::class, 'sendResetLink'])->name('password.recover');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/2fa/verify', [AuthController::class, 'verify2FA'])->name('2fa.verify');
    Route::get('/podcasts/{podcast}/nested', [PodcastController::class, 'showWithAllNestedComments']);
    Route::get('/filterPodcastsByCategory', [PodcastController::class, 'filterPodcastsByCategory']);
    Route::post('/podcasts/{podcast}/view', [PodcastController::class, 'view']);
   



   
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('token.refresh');
       Route::post('/user/upload-image', [MediaController::class, 'uploadMedia']);
       Route::post('/upload_podcast', [PodcastController::class, 'store']);
       Route::post('/comments', [CommentController::class, 'store']);
       Route::post('/podcasts/{podcast}/like', [PodcastController::class, 'toggleLike']);
       Route::post('/podcasts/{podcast}/categories', [PodcastController::class, 'updateCategories']);
       Route::get('/podcasts/random', [PodcastController::class, 'random']);
       Route::post('/podcasts/{podcast}/view', [PodcastController::class, 'view']);
       //Route::get('/podcasts/most-viewed', [PodcastController::class, 'mostViewed']);
       //Route::get('/podcasts/trending', [PodcastController::class, 'trending']);
       Route::get('/podcasts/filter', [PodcastController::class, 'filterByCategoryWithMetrics']);
       Route::post('/channel', [ChannelController::class, 'create']);
       Route::post('/channels/{channel}/subscribe', [SubscriptionController::class, 'toggle']);


  
 

    });

});


