<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Http\Requests\AdminRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Bitfumes\Multiauth\Notifications\RegistrationNotification;

class RegisterController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return Admin
     */
    protected function register(AdminRequest $data)
    {
        $this->authorize('CreateAdmin', Admin::class);
        $admin            = new Admin();
        $data['password'] = bcrypt($data['password']);
        $fields           = $this->tableFields();
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $admin->$field = $data[$field];
            }
        }

        $admin->save();
        $admin->roles()->sync(request('role_id'));
        $this->sendConfirmationNotification($admin, request('password'));
        $resource = config('multiauth.resources.admin');
        return new $resource($admin);
    }

    protected function sendConfirmationNotification($admin, $password)
    {
        if (config('multiauth.registration_notification_email')) {
            try {
                $admin->notify(new RegistrationNotification($password));
            } catch (\Exception $e) {
                session()->flash('message', 'Email not sent properly, Please check your mail configurations');
            }
        }
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'oldPassword' => 'required',
            'password'    => 'required|confirmed',
        ]);
        auth()->user()->update(['password' => bcrypt($data['password'])]);
        return redirect(route('admin.home'))->with('message', 'Your password is changed successfully');
    }

    protected function tableFields()
    {
        return collect(\Schema::getColumnListing('admins'));
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
            'user'         => auth('admin')->user(),
        ]);
    }
}
