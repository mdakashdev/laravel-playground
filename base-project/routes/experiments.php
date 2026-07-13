<?php
use Illuminate\Support\Facades\Route;

use App\Experiments\DependencyInjection\VersionA\UserController as A;
use App\Experiments\DependencyInjection\VersionB\UserController as B;
use App\Experiments\DependencyInjection\DependencyController;

/**
 * Dependency & Dependency Injection & Inverse of Control (IoC)
 */
Route::get('/experiments/dependency', [DependencyController::class, 'dependency']);
Route::get('/experiments/dependency-injection', [DependencyController::class, 'dependencyInjection']);

// di -> dependency injection
Route::get('/experiments/di/a', function () {
    return (new A())->register();
});

Route::get('/experiments/di/b', function (B $controller) {
    return $controller->register();
});

Route::get('/experiments/reflection', function (App\Experiments\PhpReflection\MailService $controller) {
    $reflection = new ReflectionClass($controller);
    var_dump($reflection);
    //return $controller->register();
});

//$reflection = new ReflectionClass(
//    \App\Experiments\PhpReflection\MailService::class
//);
//
//var_dump($reflection->getMethods());
