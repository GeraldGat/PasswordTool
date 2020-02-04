<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Credential;
use App\Providers\CredentialManagerProvider;
use Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $encoded_credentials = $user->credentials;

        $decoded_credentials = [];
        foreach($encoded_credentials as $encoded_credential) {
            array_push($decoded_credentials, CredentialManagerProvider::decryptCredential($encoded_credential));
        }

        return view('home')->with([
            'credentials'   => $decoded_credentials,
            'user'          => $user
        ]);
    }
}
