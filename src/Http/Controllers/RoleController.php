<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Http\Request;
use Bitfumes\Multiauth\Model\Role;
use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Admin;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('super:admin');
    }

    public function index()
    {
        $roles = Role::all();
        return view('multiauth::roles.index', compact('roles'));
    }

    public function create()
    {
        return view('multiauth::roles.create');
    }

    public function edit(Role $role)
    {
        return view('multiauth::roles.edit', compact('role'));
    }

    public function store(Request $request)
    {
        Role::create($request->all());

        return redirect('/admin/roles')->with('message', 'New Role is stored successfully successfully');
    }

    public function update(Role $role, Request $request)
    {
        $role->update($request->all());

        return redirect('/admin/roles')->with('message', 'You have updated Role successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->back()->with('message', 'You have deleted Role successfully');
    }
}
