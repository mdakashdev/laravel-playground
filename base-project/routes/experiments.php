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


/**
 * Nested dependency
 *
 * Test: binding, make = what output for dependency
 */

//Route::get('/experiments/nest-dependency', [\App\Experiments\NestedDependency\UserController::class, 'register']);

Route::get('/experiments/nest-dependency', function (\App\Experiments\NestedDependency\Container $container) {
    $container->make(\App\Experiments\NestedDependency\UserController::class);
});



//Route::get('/experiments/nest-dependency', function(\App\Experiments\NestedDependency\UserController $userController) {
//    $ref = new ReflectionClass($userController);
//    $constructor = $ref->getConstructor();
//
//    //var_dump($ref);
//
//    foreach ($constructor->getParameters() as $item) {
//        echo $item->getName();
//    }
//});

//ekhane hobe na, ami bindings(store) koreachi and make korechi, ekhane make a first object diye resolve hocche na.
//thats why error dicce


// but ei make diye korle eita resolve korteche. kono error diche
// Laravel পুরো dependency tree resolve করে সব object তৈরি করেছে। UserController -> MailService -> Logger -> Config
// object create hoye asbe : Config -> Logger -> MailService->UserController
Route::get('/experiments/nest-laravel', function () {
    app()->make(\App\Experiments\NestedDependency\AppController::class);
});




/**
 * Test: self created container
 * Singleton
 */

Route::get('/experiments/singleton', [\App\Experiments\Singleton\UserController::class, 'register']);

/**
 * Test: in laravel system
 * check bind and singleton , behaviour object
 *
 * resolve, app, and dependency injection diye resolve kora jai.
 */
Route::get('/experiments/singleton-3', function() {
    dump(spl_object_id(resolve(\App\Services\ReportService::class)));
    dump(spl_object_id(app(\App\Services\ReportService::class)));
});
