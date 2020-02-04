<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;

// Providers
use App\Providers\CredentialManagerProvider;
use App\Providers\RouteServiceProvider;

// Model
use App\Credential;

class EditCredentialController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after actions.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showEditCredentialForm($credentialId) {
        /**
         * Access denied if the given credentialId don't match a credential of the user
         */
        $currentCredential = Credential::find($credentialId);
        if($currentCredential->user_id != Auth::user()->id) {
            abort(403, 'Access denied.');
        }

        return view('credentialManager.editCredentialForm', [
            'credentialId'      => $credentialId,
            'credentialGroup'   => $currentCredential->credential_group
        ]);
    }

    public function editCredential(Request $request, $credentialId) {
        /**
         * Access denied if the given credentialId don't match a credential of the user
         */
        $currentCredential = Credential::find($credentialId);
        if($currentCredential->user_id != Auth::user()->id) {
            abort(403, 'Access denied.');
        }

        $this->validateEditedCredential($request->all())->validate();
        
        $credentialGroup = $currentCredential->credential_group;
        $credentialGroup->for = $request->input('name');
        $credentialGroup->username = $request->input('username');

        CredentialManagerProvider::editCredential($credentialGroup, $request->input('password'));
        
        return redirect($this->redirectPath());
    }

    protected function validateEditedCredential(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed'],
        ]);
    }
}
