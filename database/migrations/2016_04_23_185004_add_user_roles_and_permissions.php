<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserRolesAndPermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $this->down();

        $user = \App\User::create([
            'name' => 'Super Admin',
            'dob' => '1988-07-30',
            'username' => 'devadmin',
            'email' => 'admin@howtheyrate.net',
            'password' => bcrypt(123456),
        ]);

        /**
         * Create Rules
         */

        $roles = new \App\Role();

        foreach (\App\Role::getRoles() as $role) {

            $roleModel = new \App\Role();
            $roleModel->name = $role['name'];
            $roleModel->display_name = $role['display_name']; // optional
            $roleModel->description = $role['description']; // optional
            $roleModel->save();

            if ($user && $user->count()) {
                $user->attachRole($roleModel);
            }

        }


        $adminRole = \App\Role::whereName('super-admin')->first();

        /**
         * Create Rules
         */
        foreach (\App\Permission::getPermissions() as $permission) {

            $permissionModel = new \App\Permission();
            $permissionModel->name = $permission['name'];
            $permissionModel->display_name = $permission['display_name']; // optional
            $permissionModel->save();

            if ($adminRole && $adminRole->count()) {
                $adminRole->attachPermission($permissionModel);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        \App\User::whereUsername('devadmin')->delete();

        $roles = \App\Role::all();

        foreach ($roles as $r) {
            $r->delete();
        }

        $permission = \App\Permission::all();

        foreach ($permission as $p) {
            $p->delete();
        }

    }
}
