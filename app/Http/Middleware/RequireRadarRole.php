<?php

namespace App\Http\Middleware;

use Closure;

class RequireRadarRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (! $user || ! in_array((string) $user->perfil, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
