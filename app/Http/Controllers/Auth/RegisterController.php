<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Providers\CrypterServiceProvider;
use App\User;
use App\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use phpseclib\Crypt\RSA;
use App\Invitation;
use DateTime;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    private $defaultRole = 'User';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm($secretString)
    {
        $invitation = Invitation::where('link', $secretString)->firstOrFail();
        if($invitation->expired_at->lessThan(new DateTime())) {
            abort(403, 'Invitation expired.');
        }

        return view('auth.register', [
            'secretString'  => $secretString,
            'email'         => $invitation->email
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, $secretString)
    {
        $this->validator($request->all())->validate();

        $invitation = Invitation::where('link', $secretString)->firstOrFail();
        if($invitation->expired_at->lessThan(new DateTime())) {
            abort(403, 'Invitation expired.');
        }
        if($request->input('email') != $invitation->email) {
            abort(403, 'Email mismatch from the invitation link.');
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        $invitation->delete();

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Create the private and public key for the user
        $rsa = new RSA();
        $keys = $rsa->createKey();
        // Encrypt the private key with the password of the user
        $keys['encryptedPrivateKey'] = CrypterServiceProvider::encrypt($keys['privatekey'], $data['password'], true);
        session(['privateKey' => json_encode($keys['privatekey'])]);

        $role = Role::where('name', $this->defaultRole)->first();

        return User::create([
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'public_key'    => $keys['publickey'],
            'private_key'   => $keys['encryptedPrivateKey'],
            'role_id'       => $role->id
        ]);
    }
}
