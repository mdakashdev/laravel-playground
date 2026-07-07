<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::put('/profile', [AuthController::class, 'update']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

//using middleware for this endpoint
/**
 * change-password
 * PUT /change-password -> routes -> controller -> request validate ->
 * -> service -> repository -> back-to-controller -> response
 */
/**
 * logout
 * post /logout -> routes -> controller -> services -> response
 */

/**
 * me (current user)
 * GET /me -> routes -> controller
 */
