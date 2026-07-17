<?php
use Illuminate\Support\Facades\Route;

use App\Experiments\DependencyInjection\VersionA\UserController as A;
use App\Experiments\DependencyInjection\VersionB\UserController as B;
use App\Experiments\DependencyInjection\DependencyController;
use App\Experiments\Bindings\BindingController;

/**
 * Dependency & Dependency Injection & Inverse of Control (IoC)
 */
Route::get('/experiments/dependency', [DependencyController::class, 'dependency']);
Route::get('/experiments/dependency-injection', [DependencyController::class, 'dependencyInjection']);


/**
 * Binding - as like Phone Contact
 */
Route::get('/experiments/bindings', [BindingController::class, 'register']);


// di -> dependency injection
Route::get('/experiments/di/a', function () {
    return (new A())->register();
});

Route::get('/experiments/di/b', function (B $controller) {
    return $controller->register();
});

/**
 * php reflection - class scan (as like close box)
 * get constructor parameter using reflection
 * use reflection check constructor in class , reflection use kore.
 * check class / abstract class / interface / trait for make object or not
 */
Route::get('/experiments/reflection', function (App\Experiments\PhpReflection\MailService $controller) {

    $reflection = new ReflectionClass($controller);
    //var_dump($reflection);
    // get constructor property
    foreach ($reflection->getProperties() as $name) {
        echo $name->getName() . PHP_EOL;
    }
    //var_dump($reflection->getProperties());
    //return $controller->register();
});


//$reflection = new ReflectionClass(
//    \App\Experiments\PhpReflection\TraitMailService::class
//);
//
//var_dump($reflection->isInstantiable());


//$reflection = new ReflectionClass(
//    \App\Experiments\PhpReflection\MailService::class
//);
//
//var_dump($reflection->getConstructor());
