<?php

namespace Bitfumes\Multiauth\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'parent'];

    public function roles()
    {
        $roleModel = config('multiauth.models.role');
        return $this->belongsToMany($roleModel);
    }

    public function admins()
    {
        $adminModel = config('multiauth.models.admin');
        return $this->belongsToMany($adminModel);
    }
}
