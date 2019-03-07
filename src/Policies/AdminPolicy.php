<?php

namespace Bitfumes\Multiauth\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Bitfumes\Multiauth\Model\Admin;

class AdminPolicy
{
    use HandlesAuthorization;

    public function isSuperAdmin(Admin $admin)
    {
        return in_array('super', $admin->roles->pluck('name')->toArray());
    }
}
