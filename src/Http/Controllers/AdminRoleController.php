<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Model\Role;

class AdminRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('super:admin');
    }

    public function attach(Admin $admin, Role $role)
    {
        $admin->roles()->attach($role->id);
        return redirect()->back();
    }

    public function detach(Admin $admin, Role $role)
    {
        $admin->roles()->detach($role->id);
        return redirect()->back();
    }
}
