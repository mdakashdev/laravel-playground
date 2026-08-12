<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        // User authenticated?
        if (! $request->user()) {
            throw new AuthenticationException('Unauthenticated');
        }

        //check করবে user-এর **admin অথবা manager** role আছে কিনা।
        if (! $request->user()->hasAnyRole($roles)) {
            throw new AuthorizationException('Unauthorized, You do not have permission to access this resource.');
        }
        //`hasAnyRole()` কাজ করার জন্য `User` model-এ Spatie-এর `HasRoles` trait থাকতে হবে

        return $next($request);
    }
}
