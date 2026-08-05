<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/ping', function () {
    return \App\Support\ApiResponse::success(
        message: 'pong',
        data: [
            'name' => 'p1',
            'description' => 'd1'
        ]
    );
});

Route::get('/error-test', function () {
    throw ValidationException::withMessages([
        'email' => ['Email required']
    ]);
});

Route::get('/error-500', function () {
    throw new Exception('500');
});


/**
 * register validation test
 */

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

//authenticated endpoint using auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/logout', [AuthController::class, 'logout']);
});


/**
 * check temporary route
 */
//Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//    return response()->json([
//        'message' => 'Temporary verification route',
//    ]);
//})->middleware(['signed'])->name('verification.verify');
//

/**
 * instead of
 * http://localhost:8000/api/v1/email/verify/10/269f7529c829200c01aa86ba04c0d5
 */

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');


/**
 * resend email verification
 */

Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail']);

/**
 * forgot password
 */

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

//temp forgot pass
//Route::get('/reset-password/{token}', function (string $token) {
//    return response()->json([
//        'token' => $token,
//    ]);
//})->name('password.reset');

Route::get('/reset-password/{token}', [AuthController::class, 'passwordResetPage'])
    ->name('password.reset');

/**
 * reset password
 */

Route::post('/reset-password', [AuthController::class, 'resetPassword']);
