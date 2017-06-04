<?php

namespace InetStudio\AdminPanel\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::user() && Auth::user()->name == 'admin') {
            return $next($request);
        }

        return redirect(route('admin.login.form'));
    }
}
