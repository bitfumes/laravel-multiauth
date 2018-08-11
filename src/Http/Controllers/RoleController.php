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
        $this->middleware('super:admin');
    }

    public function create()
    {
        return view('multiauth::roles.create');
    }

    public function edit()
    {
        return view('multiauth::roles.edit');
    }

    public function store(Request $request)
    {
        Role::create($request->all());

        return redirect()->back();
    }

    public function update(Role $role, Request $request)
    {
        $role->update($request->all());

        return redirect()->back();
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->back();
    }
}
