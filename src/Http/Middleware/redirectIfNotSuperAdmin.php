<?php

namespace Bitfumes\Multiauth\Http\Middleware;

use Auth;
use Closure;

class redirectIfNotSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $roles = auth($guard)->user()->roles()->pluck('name');
        if (! in_array('super', $roles->toArray())) {
            return redirect(route('admin.home'));
        }

        return $next($request);
    }
}
