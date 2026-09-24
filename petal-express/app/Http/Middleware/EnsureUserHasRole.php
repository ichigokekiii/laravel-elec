<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// CheckRole-style middleware. route uses role:admin or role:user
class EnsureUserHasRole
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // must be logged in and match the role string on the route
        if (! $user || $user->role !== $role) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}
