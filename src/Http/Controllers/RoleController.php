<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('role:super');
        $this->roleModel        = config('multiauth.models.role');
        $this->adminModel       = config('multiauth.models.admin');
        $this->permissionModel  = config('multiauth.models.permission');
    }

    public function index()
    {
        $roles = $this->roleModel::all();
        return view('multiauth::roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->permissionModel::all()->groupBy('parent');
        return view('multiauth::roles.create', compact('permissions'));
    }

    public function edit($roleId)
    {
        $role        = $this->roleModel::findOrFail($roleId);
        $role        = $role->load('permissions');
        $permissions = $this->permissionModel::all()->groupBy('parent');
        return view('multiauth::roles.edit', compact('role', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        $role = $this->roleModel::create($request->all());
        $role->addPermission($request->permissions);
        return redirect(route('admin.roles'))->with('message', 'New Role is stored successfully successfully');
    }

    public function update($roleId, Request $request)
    {
        $role = $this->roleModel::findOrFail($roleId);
        $request->validate(['name' => 'required']);

        $role->update($request->all());
        $role->syncPermissions($request->permissions);

        return redirect(route('admin.roles'))->with('message', 'You have updated Role successfully');
    }

    public function destroy($roleId)
    {
        $role = $this->roleModel::findOrFail($roleId);
        $role->delete();

        return redirect()->back()->with('message', 'You have deleted Role successfully');
    }
}
