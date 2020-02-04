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
use App\User;


class ShareCredentialController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after actions.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showShareCredentialForm($credentialId) {
        /**
         * Access denied if the given credentialId don't match a credential of the user
         */
        $currentCredential = Credential::find($credentialId);
        if($currentCredential->user_id != Auth::user()->id) {
            abort(403, 'Access denied.');
        }

        /**
         * Users that doesn't have access to the credential
         */
        $users = User::whereDoesntHave('credentials', function($query) use($currentCredential) {
            $query->where('credential_group_id', "=", $currentCredential->credential_group_id);
        })->where('id', '!=', Auth::user()->id)->get();

        /**
         * Users that have access to the credential
         */
        $sharedUsers = User::whereHas('credentials', function($query) use($currentCredential) {
            $query->where('credential_group_id', "=", $currentCredential->credential_group_id);
        })->where('id', '!=', Auth::user()->id)->get();

        return view('credentialManager.shareCredentialForm')->with([
            'credentialId'  => $credentialId,
            'users'         => $users,
            'sharedUsers'   => $sharedUsers
        ]);
    }

    public function shareCredential(Request $request, $credentialId) {
        /**
         * Access denied if the given credentialId don't match a credential of the user
         */
        $currentCredential = Credential::find($credentialId);
        $currentUser = Auth::user();
        if($currentCredential->user_id != $currentUser->id) {
            abort(403, 'Access denied.');
        }

        /**
         * List of users Id that should have the credential after this function
         */
        $idUsersList = $request->input("sharedUsersList", []);
        $idUsersList[] = $currentUser->id;

        /**
         * List of user being in the sharing list
         */
        $usersList = User::whereIn('id', $idUsersList)->get();

        /**
         * List of user that currently have the credential and shouldn't according to the new list
         */
        $previousUsersList = User::whereHas("credentials", function($query) use($currentCredential) {
            $query->where('credential_group_id', "=", $currentCredential->credential_group_id);
        })->whereNotIn('id', $idUsersList)->get();


        $credentialDecrypted = CredentialManagerProvider::decryptCredential(Credential::find($credentialId));

        /**
         * foreach of the user in the list:
         *  - make a list of the credential group id
         *  - check if the current credential group is in the previous list
         *  - if not, add the credential for this user
         */
        foreach($usersList as $user) {
            $userCredentialGroupsId = array_map(function($credential) {
                    return $credential['credential_group_id'];
                },
                $user->credentials->toArray()
            );

            if(!in_array($currentCredential->credential_group_id, $userCredentialGroupsId)) {
                CredentialManagerProvider::shareCredential($currentCredential->credential_group, $credentialDecrypted->password, $user);
            }
        }

        foreach($previousUsersList as $user) {
            $credentialIndexToRemove = null;
            foreach($user->credentials as $credential) {
                if($credential->credential_group_id == $currentCredential->credential_group_id) {
                    $credential->delete();
                }
            }
        }

        return redirect($this->redirectPath());
    }
}
