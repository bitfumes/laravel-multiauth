<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Model\Role;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->authorize('isSuperAdmin', Admin::class);
    }

    public function attach(Admin $admin, Role $role)
    {
        $admin->roles()->attach($role->id);
        return response('success', Response::HTTP_CREATED);
    }

    public function detach(Admin $admin, Role $role)
    {
        $admin->roles()->detach($role->id);
        return response('success', Response::HTTP_ACCEPTED);
    }
}
