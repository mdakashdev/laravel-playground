<?php
namespace App\Exceptions;

use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionRenderer
{
    public static function render(Throwable $e, Request $request)
    {

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

    }
}
