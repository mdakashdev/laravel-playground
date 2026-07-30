<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/web', function () {
//    return [
//        'message' => 'from web'
//    ];
    throw new Exception('web error');
});
