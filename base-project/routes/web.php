<?php

use App\Services\TestService;
use Illuminate\Support\Facades\Route;

//echo __DIR__;
//echo "<br>";
//echo dirname(__DIR__);
//echo "<br>";
//echo PHP_EOL;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function() {
    $test = new TestService();
    return $test->hello();
});

Route::get('/hello', function() {
    return response()->json([
        'name' => "anaya",
        'role' => 'student'
    ]);
});

// three closure route
Route::get('/a', fn () => 'A');
Route::get('/b', fn () => 'B');
Route::get('/c', fn () => 'C');

Route::get('/home', [\App\Http\Controllers\PageController::class, 'home']);
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about']);
Route::get('/contact', [\App\Http\Controllers\PageController::class, 'contact']);
