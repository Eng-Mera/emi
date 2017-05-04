<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionViewUserRelated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            'name' => 'view-user-related',
            'display_name' => 'View User Related Models',
            'description' => 'This permission should be assigned to the roles allowed to view models related to main user model',
            'created_at' => strftime('%Y-%m-%d %H:%M:%S'),
            'updated_at' => strftime('%Y-%m-%d %H:%M:%S')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $id = DB::table('permissions')->select(['id'])->where(['name' => 'view-user-related'])->pluck('id');
        if (!empty($id)) {
            DB::table('permissions')->delete($id[0]);
        }
    }
}
