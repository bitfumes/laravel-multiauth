<?php

namespace Bitfumes\Multiauth\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['title', ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class);
    }
}
