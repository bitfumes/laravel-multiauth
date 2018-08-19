<?php

namespace Bitfumes\Multiauth\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($role) {
            if ($role->admins()->count() > 0) {
                throw new \Exception('Can not delete, Role is assigned to Admins.');
            }
        });
    }

    protected $fillable = ['name'];

    public function admins()
    {
        return $this->belongsToMany(Admin::class);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }
}
