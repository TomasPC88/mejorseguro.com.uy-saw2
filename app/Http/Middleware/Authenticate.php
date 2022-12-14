<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized', 401);
            } else {
                switch($guard){
                    case 'admin':
                        return redirect()->route('admin.login');
                    default:
                        return redirect()->route('page.auth.login');
                }
            }
        }

        Auth::shouldUse($guard);
        return $next($request);
    }
}
