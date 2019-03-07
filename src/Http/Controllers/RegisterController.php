<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Bitfumes\Multiauth\Http\Requests\AdminRequest;
use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\Notifications\RegistrationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    public $redirectTo;

    /**
     * Where to redirect users after registration.
     *
     * @return string
     */
    public function redirectTo()
    {
        return $this->redirectTo = route('admin.show');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('role:super');
    }

    public function showRegistrationForm()
    {
        $roles = Role::all();

        return view('multiauth::admin.register', compact('roles'));
    }

    public function register(AdminRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));

        return redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return Admin
     */
    protected function create(array $data)
    {
        $admin = new Admin();

        $fields           = $this->tableFields();
        $data['password'] = bcrypt($data['password']);
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $admin->$field = $data[$field];
            }
        }

        $admin->save();
        $admin->roles()->sync(request('role_id'));
        $this->sendConfirmationNotification($admin, request('password'));

        return $admin;
    }

    protected function sendConfirmationNotification($admin, $password)
    {
        if (config('multiauth.registration_notification_email')) {
            try {
                $admin->notify(new RegistrationNotification($password));
            } catch (\Exception $e) {
                request()->session()->flash('message', 'Email not sent properly, Please check your mail configurations');
            }
        }
    }

    protected function tableFields()
    {
        return collect(\Schema::getColumnListing('admins'));
    }

    public function edit(Admin $admin)
    {
        $roles = Role::all();
        return view('multiauth::admin.edit', compact('admin', 'roles'));
    }

    public function update(Admin $admin, AdminRequest $request)
    {
        $request['active'] = request('activation') ?? 0;
        unset($request['activation']);
        $admin->update($request->except('role_id'));
        $admin->roles()->sync(request('role_id'));

        return redirect(route('admin.show'))->with('message', "{$admin->name} details are successfully updated");
    }

    public function destroy(Admin $admin)
    {
        $prefix = config('multiauth.prefix');
        $admin->delete();

        return redirect(route('admin.show'))->with('message', "You have deleted {$prefix} successfully");
    }
}
