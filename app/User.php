<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'public_key', 'private_key', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get all the credentials of the user
     */
    public function credentials() {
        return $this->hasMany('App\Credential', 'user_id', 'id');
    }

    /**
     * Get the role of the user
     */
    public function role() {
        return $this->belongsTo('App\Role', 'role_id', 'id');
    }

    /**
     * Return true if the user has the role given
     */
    public function hasPermission($permission_name) {
        $role = $this->role;
        foreach($role->permissions as $permission) {
            if($permission->name == $permission_name || $permission->name == "admin") {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role_name) {
        return $this->role->name == $role_name;
    }
}
