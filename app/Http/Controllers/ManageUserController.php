<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Auth;

// Providers
use App\Providers\RouteServiceProvider;

// Model
use App\User;
use App\Role;

class ManageUserController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after actions.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showManageUserForm() {
        $users = User::withTrashed()->where('id', '!=', Auth::user()->id)->orderBy('email', 'asc')->get();
        $roles = Role::all();

        return view('userManagement.manageUsers', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function manageUser(Request $request) {
        $usersList = $request->input('users');
        foreach($usersList as $userArray) {
            if($userArray['change'] == 0) {
                continue;
            }

            $user = User::withTrashed()->find($userArray['id']);
            $role = Role::find($userArray['role_id']);
            
            $user->role_id = $role->id;
            
            if(isset($userArray['trashed'])) {
                $user->delete();
            } else {
                $user->deleted_at = null;
            }

            $user->save();
        }

        return redirect($this->redirectPath());
    }
}
