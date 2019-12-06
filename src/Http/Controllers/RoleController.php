<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Http\Request;
use Bitfumes\Multiauth\Model\Role;
use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Admin;
use Symfony\Component\HttpFoundation\Response;
use Bitfumes\Multiauth\Http\Resources\RoleResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $this->authorize('ReadRole', Admin::class);
        return RoleResource::collection(Role::all());
    }

    public function store(Request $request)
    {
        $this->authorize('CreateRole', Admin::class);
        $request->validate(['name' => 'required']);
        $role = Role::create($request->all());
        $role->addPermission($request->permissions);
        return response('created', Response::HTTP_CREATED);
    }

    public function update(Role $role, Request $request)
    {
        $this->authorize('UpdateRole', Admin::class);
        $request->validate(['name' => 'required']);
        $role->update($request->all());
        $role->syncPermissions($request->permissions);
        return response('updated', Response::HTTP_ACCEPTED);
    }

    public function destroy(Role $role)
    {
        $this->authorize('DeleteRole', Admin::class);
        $role->delete();
        return response('deleted', Response::HTTP_NO_CONTENT);
    }
}
