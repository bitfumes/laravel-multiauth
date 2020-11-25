<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Routing\Controller;

class AdminRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super');
        $this->adminModel = config('multiauth.models.admin');
        $this->roleModel  = config('multiauth.models.role');
    }

    public function attach($adminId, $roleId)
    {
        $role   = $this->roleModel::findOrFail($roleId);
        $admin  = $this->adminModel::findOrFail($adminId);
        $admin->roles()->attach($role->id);

        return redirect()->back();
    }

    public function detach($adminId, $roleId)
    {
        $role   = $this->roleModel::findOrFail($roleId);
        $admin  = $this->adminModel::findOrFail($adminId);
        $admin->roles()->detach($role->id);

        return redirect()->back();
    }
}
