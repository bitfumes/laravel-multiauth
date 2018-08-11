<?php

namespace Bitfumes\Multiauth\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function admins()
    {
        return $this->belongsToMany(Admin::class);
    }
}
