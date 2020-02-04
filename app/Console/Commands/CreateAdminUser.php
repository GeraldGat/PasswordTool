<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Validator;
use Illuminate\Console\Command;
use phpseclib\Crypt\RSA;
use App\Providers\CrypterServiceProvider;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;

class CreateAdminUser extends Command
{
    private $defaultRole = 'Admin';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:admin:create {email} {password} {password_confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data['email'] = $this->argument('email');
        $data['password'] = $this->argument('password');
        $data['password_confirmation'] = $this->argument('password_confirmation');
        $validator = $this->validateUser($data);

        if ($validator->fails()) {
            $this->info('User not created. See error messages below:');
        
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Create the private and public key for the user
        $rsa = new RSA();
        $keys = $rsa->createKey();
        // Encrypt the private key with the password of the user
        $keys['encryptedPrivateKey'] = CrypterServiceProvider::encrypt($keys['privatekey'], $data['password'], true);

        $role = Role::where('name', $this->defaultRole)->first();

        return User::create([
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'public_key'    => $keys['publickey'],
            'private_key'   => $keys['encryptedPrivateKey'],
            'role_id'       => $role->id
        ]);
    }

    protected function validateUser(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
