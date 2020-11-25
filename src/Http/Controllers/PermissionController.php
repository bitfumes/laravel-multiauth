<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('role:super');
        $this->permissionModel = config('multiauth.models.permission');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => $this->permissionModel::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($permissionId)
    {
        $permission = $this->permissionModel::findOrFail($permissionId);
        return response(['data' => $permission], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permission = $this->permissionModel::create($request->all());
        return response(['data' => $permission], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Bitfumes\Multiauth\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $permissionId)
    {
        $permission = $this->permissionModel::findOrFail($permissionId);
        $permission->update($request->all());
        return response(['data' => $permission], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Bitfumes\Multiauth\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($permissionId)
    {
        $permission = $this->permissionModel::findOrFail($permissionId);
        $permission->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
