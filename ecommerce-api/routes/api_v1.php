<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return \App\Support\ApiResponse::success(
        message: 'pong',
        data: [
            'name' => 'p1',
            'description' => 'd1'
        ]
    );
});
