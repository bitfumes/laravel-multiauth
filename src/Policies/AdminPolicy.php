<?php

namespace Bitfumes\Multiauth\Policies;

use Bitfumes\Multiauth\Model\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function isSuperAdmin(Admin $admin)
    {
        return in_array('super', $admin->roles->pluck('name')->toArray());
    }
}
