<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Bitfumes\Multiauth\Model\Role;
use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Admin;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminRoleController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function attach(Admin $admin, Role $role)
    {
        $this->authorize('isSuperAdmin', Admin::class);
        $admin->roles()->attach($role->id);
        return response('success', Response::HTTP_CREATED);
    }

    public function detach(Admin $admin, Role $role)
    {
        $this->authorize('isSuperAdmin', Admin::class);
        $admin->roles()->detach($role->id);
        return response('success', Response::HTTP_ACCEPTED);
    }
}
