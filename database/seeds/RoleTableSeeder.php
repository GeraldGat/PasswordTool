<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new Role();
        $role_user->name = 'User';
        $role_user_permission = Permission::where('name', 'add_credential')->first();
        $role_user->save();
        $role_user->permissions()->attach($role_user_permission->id);

        $role_admin = new Role();
        $role_admin->name = 'Admin';
        $role_admin_permission = Permission::where('name', 'admin')->first();
        $role_admin->save();
        $role_admin->permissions()->attach($role_admin_permission->id);
    }
}
