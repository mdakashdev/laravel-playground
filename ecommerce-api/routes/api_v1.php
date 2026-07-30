<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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
