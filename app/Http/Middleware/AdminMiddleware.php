<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminMiddleware
 *
 * Ensures only authenticated users with 'admin' role
 * can access protected routes.
 */
class AdminMiddleware {
    
    public function handle(Request $request, Closure $next): Response {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
