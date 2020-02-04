<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

// Providers
use App\Providers\RouteServiceProvider;

// Model
use App\Permission;
use App\Role;

class AddRoleController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after actions.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showAddRoleForm() {
        $permissions = Permission::where('name', '!=', 'admin')->get();

        return view('roleManager.addRoleForm', [
            'permissions'   => $permissions
        ]);
    }

    public function addRole(Request $request) {
        $this->validateAddRole($request->all())->validate();

        $new_role = new Role();
        $new_role->name = $request->input('name');
        $new_role->save();

        foreach($request->input('permissions') as $permission) {
            if(isset($permission['checked'])) {
                $new_role->permissions()->attach(Permission::find($permission['id']));
            }
        }

        return redirect($this->redirectPath());
    }

    protected function validateAddRole(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255']
        ]);
    }
}
