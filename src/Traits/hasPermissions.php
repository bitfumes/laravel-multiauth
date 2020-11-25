<?php

namespace Bitfumes\Multiauth\Traits;

use Illuminate\Support\Collection;

trait hasPermissions
{
    public function directPermissions()
    {
        $permission = config('multiauth.models.permission');
        return $this->belongsToMany($permission);
    }

    public function permissionsByRole()
    {
        $permissionsOfRole = $this->roles->pluck('permissions');
        $allPermissions    = new Collection();
        $permissionsOfRole->each(function ($permissions) use ($allPermissions) {
            $permissions->each(function ($permission) use ($allPermissions) {
                $allPermissions->add($permission);
            });
        });
        return $allPermissions;
    }

    public function hasPermission($permission)
    {
        if ($this->hasDirectPermission($permission)) {
            return true;
        }
        if ($this->hasPermissionByRole($permission)) {
            return true;
        }
        return false;
    }

    public function hasPermissionByRole($permission)
    {
        if (is_numeric($permission)) {
            return $this->permissionsByRole()->contains('id', $permission);
        }
        return $this->permissionsByRole()->contains('name', $permission);
    }

    public function hasDirectPermission($permission)
    {
        if (is_numeric($permission)) {
            return $this->directPermissions->contains('id', $permission);
        }
        return $this->directPermissions->contains('name', $permission);
    }

    public function addDirectPermission($permissionId)
    {
        $this->directPermissions()->attach($permissionId);
    }

    public function removeDirectPermission($permissionId)
    {
        $this->directPermissions()->detach($permissionId);
    }

    public function allPermissions()
    {
        $byRole = $this->permissionsByRole()->map(function ($role) {
            return $role->only(['id', 'name', 'parent']);
        })->toArray();
        $direct = $this->directPermissions->map(function ($role) {
            return $role->only(['id', 'name', 'parent']);
        })->toArray();
        return array_merge($direct, $byRole);
    }
}
