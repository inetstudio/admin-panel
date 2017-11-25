<?php

namespace InetStudio\AdminPanel\Http\Middleware\Back\Auth;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        Auth::logout();

        return redirect(route('back.login.form'));
    }
}
