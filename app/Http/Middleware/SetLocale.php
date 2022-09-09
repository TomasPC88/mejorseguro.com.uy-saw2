<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Sets User's locale to the system
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::has('locale'))
            App::setLocale(Session::get('locale'));
        else if(Auth::check())
            App::setLocale(Auth::user()->locale);

        return $next($request);
    }
}
