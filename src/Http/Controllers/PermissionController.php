<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('role:super');
    }

    public function index()
    {
        $permissions = Permission::all();

        return view('multiauth::permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('multiauth::permissions.create');
    }

    public function edit(Permission $permission)
    {
        return view('multiauth::permissions.edit', compact('permission'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Permission::create($request->all());

        return redirect(route('admin.permissions'))->with('message', 'New Permission is stored successfully successfully');
    }

    public function update(Permission $permission, Request $request)
    {
        $request->validate(['name' => 'required']);

        $permission->update($request->all());

        return redirect(route('admin.permissions'))->with('message', 'You have updated Permission successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->back()->with('message', 'You have deleted Permission successfully');
    }
}
