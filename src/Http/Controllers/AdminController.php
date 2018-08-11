<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Admin;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('multiauth::admin.home');
    }

    public function show()
    {
        $admins = Admin::all();

        return view('multiauth::admin.show', compact('admins'));
    }
}
