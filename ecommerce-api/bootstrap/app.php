<?php

use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api/v1')
                ->middleware('api')
                ->group(base_path('routes/api_v1.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Exception handling configuration
        $exceptions->render(function (Throwable $e, Request $request) {


            // api na hole, laravel default korbe; mane api endpoint a thaka lagbe.
            if (! $request->is('api/*')) {
                return null;
            }

            return match (true) {

                $e instanceof ValidationException =>
                ApiResponse::error(
                    message: 'Validation Failed',
                    errors: $e->errors(),
                    statusCode: 422,
                ),

                $e instanceof AuthenticationException =>
                ApiResponse::error(
                    message: 'Unauthenticated',
                    statusCode: 401,
                ),

                $e instanceof AuthorizationException =>
                ApiResponse::error(
                    message: 'Forbidden',
                    statusCode: 403,
                ),

                $e instanceof ModelNotFoundException =>
                ApiResponse::error(
                    message: 'Resource Not Found',
                    statusCode: 404,
                ),

                $e instanceof NotFoundHttpException =>
                ApiResponse::error(
                    message: 'Route Not Found',
                    statusCode: 404,
                ),

                default =>
                ApiResponse::error(
                    message: 'Internal Server Error',
                    statusCode: 500,
                ),
            };

        });
    })->create();
