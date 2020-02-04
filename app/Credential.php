<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    protected $table = "credentials";

    /**
     * Get the user for this credential
     */
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function credential_group() {
        return $this->belongsTo('App\CredentialGroup', 'credential_group_id', 'id');
    }
}
