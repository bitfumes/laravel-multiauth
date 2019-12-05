<?php

namespace Bitfumes\Multiauth\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Bitfumes\Multiauth\Traits\hasPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Bitfumes\Multiauth\Notifications\AdminResetPasswordNotification;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable, hasPermissions;

    protected $casts = ['active' => 'boolean'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        $roleModel = config('multiauth.models.role');
        return $this->belongsToMany($roleModel);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'active'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
