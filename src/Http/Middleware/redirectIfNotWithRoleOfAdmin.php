<?php

namespace Bitfumes\Multiauth\Http\Middleware;

use Auth;
use Closure;

class redirectIfNotWithRoleOfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $role
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role = 'super')
    {
        // If not logged in, redirect to the login page.
        if (!auth('admin')->user()) {
            return redirect(route('admin.login'));
        }

        $roles = auth('admin')->user()->/* @scrutinizer ignore-call */roles()->pluck('name');
        if (in_array('super', $roles->toArray())) {
            return $next($request);
        }

        if (!in_array(strtolower($role), $roles->toArray())) {
            return redirect(route('admin.home'));
        }

        return $next($request);
    }
}
