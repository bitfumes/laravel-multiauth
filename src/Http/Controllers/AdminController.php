<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Admin;
use Symfony\Component\HttpFoundation\Response;
use Bitfumes\Multiauth\Http\Requests\AdminRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminController extends Controller
{
    use AuthorizesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['login', 'register']);
        $this->resource   = config('multiauth.resources.admin');
        $this->adminModel = config('multiauth.models.admin');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        request()->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        $credentials           = request(['email', 'password']);
        $credentials['active'] = true;

        if (!$token = auth('admin')->attempt($credentials)) {
            return response()->json(['error' => 'These credentials does not match our record'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admin')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function all()
    {
        $this->authorize('ReadAdmin', $this->adminModel);
        return $this->resource::collection($this->adminModel::all());
    }

    public function me()
    {
        $admin    = new $this->resource(auth('admin')->user());
        return response($admin, Response::HTTP_ACCEPTED);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    public function update(Admin $admin, AdminRequest $request)
    {
        $adminResource = config('multiauth.resources.admin');
        $this->authorize('UpdateAdmin', $this->adminModel);
        $admin->update($request->except('role_ids', 'permission_ids'));
        $admin->roles()->sync(request('role_ids'));
        $admin->directPermissions()->sync(request('permission_ids'));
        return response(new $adminResource($admin), Response::HTTP_ACCEPTED);
    }

    public function destroy(Admin $admin)
    {
        $this->authorize('DeleteAdmin', $this->adminModel);
        $admin->delete();
        return response('success', Response::HTTP_ACCEPTED);
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'oldPassword' => 'required',
            'password'    => 'required|confirmed',
        ]);
        auth()->user()->update(['password' => bcrypt($data['password'])]);
        return response('password successfully changed', Response::HTTP_ACCEPTED);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('admin')->factory()->getTTL() * 60,
            'user'         => new $this->resource(auth('admin')->user()),
        ]);
    }
}
