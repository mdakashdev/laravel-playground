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
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    return response()->json([
        'message' => 'Temporary verification route',
    ]);
})->middleware(['signed'])->name('verification.verify');
