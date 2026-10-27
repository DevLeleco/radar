<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $destination = match ((int) Auth::user()->perfil) {
                2 => 'tickets.index',
                3 => 'admin.tickets.queue',
                default => 'admin.dashboard',
            };

            return redirect()->route($destination);
        }

        return $next($request);
    }
}
