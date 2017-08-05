<?php

namespace InetStudio\AdminPanel\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (Auth::user() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        Auth::logout();

        return redirect(route('back.login.form'));
    }
}
