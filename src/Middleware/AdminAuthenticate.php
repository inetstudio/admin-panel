<?php

namespace InetStudio\AdminPanel\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (! Auth::guard($guard)->check()) {
            return redirect(route('admin.login.form'));
        }

        return $next($request);
    }
}
