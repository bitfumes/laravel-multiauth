<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Bitfumes\Multiauth\Model\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Bitfumes\Multiauth\Model\Admin;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $this->authorize('isSuperAdmin', Admin::class);
        return Role::all();
    }

    public function store(Request $request)
    {
        $this->authorize('isSuperAdmin', Admin::class);
        $request->validate(['name' => 'required']);
        Role::create($request->all());
        return response('created', Response::HTTP_CREATED);
    }

    public function update(Role $role, Request $request)
    {
        $this->authorize('isSuperAdmin', Admin::class);
        $request->validate(['name' => 'required']);
        $role->update($request->all());
        return response('updated', Response::HTTP_ACCEPTED);
    }

    public function destroy(Role $role)
    {
        $this->authorize('isSuperAdmin', Admin::class);
        $role->delete();
        return response('deleted', Response::HTTP_NO_CONTENT);
    }
}
