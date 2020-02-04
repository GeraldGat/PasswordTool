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

class EditRoleController extends Controller
{
    use RedirectsUsers;

    /**
     * Where to redirect users after actions.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showAllRole() {
        $roles = Role::orderBy('name', 'asc')->where('name', '!=', 'Admin')->get();

        return view('roleManager.listRole', [
            'roles' => $roles
        ]);
    }

    public function showEditRoleForm($role_id) {
        $role = Role::find($role_id);
        if($role->name == "Admin") {
            abort(403, 'Impossible to edit admin role.');
        }
        $permissions = Permission::where('name', '!=', 'admin')->get();

        return view('roleManager.editRoleForm', [
            'role'          => $role,
            'permissions'   => $permissions
        ]);
    }

    public function editRole(Request $request, $role_id) {
        $this->validateAddRole($request->all())->validate();

        $role = Role::find($role_id);
        if($role->name == "Admin") {
            abort(403, 'Impossible to edit admin role.');
        }
        $role->name = $request->input('name');
        $role->save();

        foreach($request->input('permissions') as $permission) {
            if(isset($permission['checked'])) {
                $role->permissions()->attach(Permission::find($permission['id']));
            } else {
                $role->permissions()->detach(Permission::find($permission['id']));
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
