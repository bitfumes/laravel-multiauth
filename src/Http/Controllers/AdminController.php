<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Bitfumes\Multiauth\Http\Requests\AdminRequest;
use Bitfumes\Multiauth\Model\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;

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
            'password' => 'required'
        ]);
        $credentials = request(['email', 'password']);

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
        $this->authorize('isSuperAdmin', Admin::class);
        return Admin::all();
    }

    public function me()
    {
        return response(auth('admin')->user(), Response::HTTP_ACCEPTED);
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
        $this->authorize('isSuperAdmin', Admin::class);
        $admin->update($request->except('role_id'));
        $admin->roles()->sync(request('role_id'));
        return response('updated', Response::HTTP_ACCEPTED);
    }

    public function destroy(Admin $admin)
    {
        $this->authorize('isSuperAdmin', Admin::class);
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
            'user'         => auth('admin')->user()
        ]);
    }
}
