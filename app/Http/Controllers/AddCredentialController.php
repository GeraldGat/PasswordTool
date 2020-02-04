<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

// Providers
use App\Providers\CredentialManagerProvider;
use App\Providers\RouteServiceProvider;

// Model
use App\CredentialGroup;
use App\Credential;


class AddCredentialController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after actions.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showAddCredentialForm() {
        return view('credentialManager.addCredentialForm');
    }

    public function addCredential(Request $request) {
        $this->validateCredential($request->all())->validate();

        $credentialGroup = new CredentialGroup();
        $credentialGroup->for = $request->input('name');
        $credentialGroup->username = $request->input('username');

        CredentialManagerProvider::addCredential($credentialGroup, $request->input('password'));

        return redirect($this->redirectPath());
    }

    protected function validateCredential(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed'],
        ]);
    }
}
