<?php

namespace Bitfumes\Multiauth\Exception;

use App\Exceptions\Handler as AppHandler;
use Illuminate\Auth\AuthenticationException;

class MultiAuthHandler extends AppHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $guard = array_get($exception->guards(), 0);

        switch ($guard) {
            case 'admin':
                return redirect()->guest(route('admin.login'));
                break;

            default:
                return redirect()->guest(route('login'));
                break;
        }
    }
}
