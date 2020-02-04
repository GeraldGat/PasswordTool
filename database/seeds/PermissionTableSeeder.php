<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_invite_user = new Permission();
        $permission_invite_user->name = "invite_user";
        $permission_invite_user->save();

        $permission_add_credential = new Permission();
        $permission_add_credential->name = "add_credential";
        $permission_add_credential->save();

        $permission_share_credential = new Permission();
        $permission_share_credential->name = "share_credential";
        $permission_share_credential->save();

        $permission_edit_credential = new Permission();
        $permission_edit_credential->name = "edit_credential";
        $permission_edit_credential->save();

        $permission_remove_credential = new Permission();
        $permission_remove_credential->name = "remove_credential";
        $permission_remove_credential->save();

        $permission_manage_user_role = new Permission();
        $permission_manage_user_role->name = "manage_user_role";
        $permission_manage_user_role->save();

        $permission_manage_role_permission = new Permission();
        $permission_manage_role_permission->name = "manage_role_permission";
        $permission_manage_role_permission->save();

        $permission_admin = new Permission();
        $permission_admin->name = "admin";
        $permission_admin->save();
    }
}
