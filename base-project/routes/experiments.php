<?php
use Illuminate\Support\Facades\Route;

use App\Experiments\DependencyInjection\VersionA\UserController as A;
use App\Experiments\DependencyInjection\VersionB\UserController as B;


Route::get('/experiments/di/a', function () {
    return (new A())->register();
});

Route::get('/experiments/di/b', function (B $controller) {
    return $controller->register();
});
