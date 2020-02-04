<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use phpseclib\Crypt\RSA;
use Auth;

// Model
use App\CredentialGroup;
use App\Credential;
use App\User;
use App\Role;


class CredentialManagerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Add a credential and share it with the user
     * 
     * @param CredentialGroup $credentialGroup - credential group of the credential
     * @param String $password - plain password for the credential
     */
    public static function addCredential(CredentialGroup $credentialGroup, String $password) {
        $user = Auth::user();
        $usersGetPassword = User::where('role_id', Role::select('id')->where('name', 'Admin')->first()->id)->get();

        $credentialGroup->save();

        if(!$user->hasRole('Admin')) {
            $usersGetPassword->prepend($user);
        }

        foreach($usersGetPassword as $user) {
            self::shareCredential($credentialGroup, $password, $user);
        }
    }

    /**
     * Share a credential for the given user
     * 
     * @param CredentialGroup $credentialGroup - credential group of the credential
     * @param String $password - plain password for the credential
     * @param User $user - the user that will get the credential
     */
    public static function shareCredential(
        CredentialGroup $credentialGroup, 
        String $password, 
        User $user, 
        Credential $credential = null
    ) {
        $rsa = new RSA();
        $rsa->loadKey($user->public_key);

        if($credential == null) {
            $credential = new Credential();
        }
        $credential->password = base64_encode($rsa->encrypt($password));
        $credential->user_id = $user->id;
        $credentialGroup->credentials()->save($credential);
    }

    /**
     * Edit the password for all a credential group
     * 
     * @param CredentialGroup $credentialGroup - credential group of the credential
     * @param String $password - plain password for the credential
     */
    public static function editCredential(CredentialGroup $credentialGroup, String $password) {
        $credentialGroup->save();

        foreach($credentialGroup->credentials as $credential) {
            $rsa = new RSA();
            $rsa->loadKey($credential->user->public_key);

            $credential->password = base64_encode($rsa->encrypt($password));
            $credential->save();
        }
    }

    /**
     * Remove all the password associated to a credentialGroup
     * 
     * @param CredentialGroup $credentialGroup - credential group of the credential
     */
    public static function removeCredentials(CredentialGroup $credentialGroup) {
        $credentialGroup->delete();
    }

    /**
     * Return the credential with plain text password
     * 
     * @param Credential $credential - credential with plain text password
     */
    public static function decryptCredential(Credential $credential) {
        $rsa = new RSA();
        $rsa->loadKey(json_decode(session('privateKey')));

        $credential->password = $rsa->decrypt(base64_decode($credential->password));

        return $credential;
    }
}
