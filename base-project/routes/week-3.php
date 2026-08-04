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


// throttle check / test

Route::get('/throttle', function () {
    return "many request";
})->middleware('throttle: 5,1'); // 1 min = 5 request
