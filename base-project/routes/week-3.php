<?php

use Illuminate\Support\Facades\Route;

Route::get('/profile', function () {
    return 'profile';
})->middleware('checkAge');


//observe middleware order

Route::get('/middleware-order', function () {
    return ['oder observe'];
})->middleware([
    'firstM',
    'secondM',
    'thirdM'
]);
