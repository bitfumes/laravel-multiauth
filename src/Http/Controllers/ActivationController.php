<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Routing\Controller;
use Bitfumes\Multiauth\Model\Admin;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivationController extends Controller
{
    use AuthorizesRequests;

    public function activate(Admin $admin)
    {
        $this->authorize('isSuperAdmin', Admin::class);
        $admin->update(['active' => true]);
        return response('activated', Response::HTTP_ACCEPTED);
    }

    public function deactivate(Admin $admin)
    {
        $this->authorize('isSuperAdmin', Admin::class);
        $admin->update(['active' => false]);
        return response('deactivated', Response::HTTP_ACCEPTED);
    }
}
