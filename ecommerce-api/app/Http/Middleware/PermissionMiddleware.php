<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {

        // User authenticated?, ekhane user verify na hole user null pabo, then error throw kore dicci.
        if (! $request->user()) {
            // ekhane default message dekhane or custome message o deya jabe
            throw new AuthenticationException('Unauthenticated User');
        }

        // Permission আছে?
        if (! $request->user()->hasAnyPermission($permissions)) {
            throw new AuthorizationException(
                'You do not have permission to access this resource.'
            );
        }

        return $next($request);
    }
}
