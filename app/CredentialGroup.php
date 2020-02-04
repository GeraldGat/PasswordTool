<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CredentialGroup extends Model
{
    protected $table = "credential_groups";

    public function credentials() {
        return $this->hasMany('App\Credential', 'credential_group_id', 'id');
    }
}
