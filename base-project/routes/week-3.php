<?php

use Illuminate\Support\Facades\Route;

Route::get('/profile', function () {
    return 'profile';
})->middleware('checkAge');
