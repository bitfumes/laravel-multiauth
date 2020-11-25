<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivationController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->adminModel = config('multiauth.models.admin');
    }

    public function activate($adminId)
    {
        $admin = $this->adminModel::findOrFail($adminId);
        $this->authorize('UpdateAdmin', $this->adminModel);
        $admin->update(['active' => true]);
        return redirect()->back();
    }

    public function deactivate($adminId)
    {
        $admin = $this->adminModel::findOrFail($adminId);
        $this->authorize('UpdateAdmin', $this->adminModel);
        $admin->update(['active' => false]);
        return redirect()->back();
    }
}
