<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Http\Request;
use Bitfumes\Multiauth\Model\Role;
use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Admin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    public function redirectTo()
    {
        return $this->redirectTo = '/admin/show';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('super:admin');
    }

    public function showRegistrationForm()
    {
        $roles = Role::all();

        return view('multiauth::admin.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, config('multiauth.validations'));
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
        $admin = new Admin;

        $data['password'] = bcrypt($data['password']);

        $fields = $this->tableFields();

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $admin->$field = $data[$field];
            }
        }

        $admin->save();

        $admin->roles()->sync(request('role_id'));

        return $admin;
    }

    protected function tableFields()
    {
        return \Schema::getColumnListing('admins');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect('/admin/show')->with('message', 'You have deleted admin successfully');
    }

    public function update(Admin $admin, Request $request)
    {
        $admin->update($request->except('role_id'));
        $admin->roles()->sync(request('role_id'));

        return redirect('/admin/show')->with('message', "{$admin->name} details are successfully updated");
    }

    public function edit(Admin $admin)
    {
        $roles = Role::all();

        return view('multiauth::admin.edit', compact('admin', 'roles'));
    }
}
