<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Auth;

// Providers
use App\Providers\CredentialManagerProvider;
use App\Providers\RouteServiceProvider;

// Model
use App\Credential;

class RemoveCredentialController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after actions.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function removeCredential($credentialId) {
        /**
         * Access denied if the given credentialId don't match a credential of the user
         */
        $currentCredential = Credential::find($credentialId);
        if($currentCredential->user_id != Auth::user()->id) {
            abort(403, 'Access denied.');
        }

        CredentialManagerProvider::removeCredentials($currentCredential->credential_group);

        return redirect($this->redirectPath());
    }
}
