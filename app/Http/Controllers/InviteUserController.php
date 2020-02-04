<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use Mail;
use DateTime;
use DateInterval;

// Providers
use App\Providers\RouteServiceProvider;

// Model
use App\Invitation;

class InviteUserController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after actions.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showInviteUserForm() {
        return view('userManagement.inviteUser');
    }

    private function randomStringGenerator($length = 20) 
    { 
        $generated_string = ""; 
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
        $len = strlen($domain); 
          
        for ($i = 0; $i < $length; $i++) 
        { 
            $generated_string .= $domain[rand(0, $len - 1)]; 
        } 
        return $generated_string; 
    } 

    public function inviteUser(Request $request) {
        $this->validateEmail($request->all())->validate();

        $invitation = new Invitation();
        $invitation->email = $request->input('email');

        $unique = false;
        do {
            $invitation->link = $this->randomStringGenerator();
            $sameLinkInvitation = Invitation::where('link', $invitation->link)->first();
            
            if($sameLinkInvitation != NULL) {
                $unique = false;
            } else {
                $unique = true;
            }
        } while(!$unique);

        $expired_at = new DateTime();
        $expired_at->add(new DateInterval('P3D'));
        $invitation->expired_at = $expired_at;
        $invitation->save();

        $to_email = $request->input('email');
        $data = [
            'link' => route('registerForm', $invitation->link)
        ];
        Mail::send('emails.invite', $data, function($message) use ($to_email) {
            $message
                ->to($to_email)
                ->subject('Invitation from Data4You');
            $from = config('mail.from');
            $message
                ->from(
                    $from['address'],
                    $from['name']
                );
        });

        return redirect($this->redirectPath());
    }

    protected function validateEmail(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:invitations'],
        ]);
    }
}
