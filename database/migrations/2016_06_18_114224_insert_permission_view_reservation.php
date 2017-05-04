<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionViewReservation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            'name' => 'view-reservation',
            'display_name' => 'View Reservation',
            'description' => 'This permission should be assigned to the roles allowed to view the reservations',
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
        $id = DB::table('permissions')->select(['id'])->where(['name' => 'view-reservation'])->pluck('id');
        if (!empty($id)) {
            DB::table('permissions')->delete($id[0]);
        }
    }
}
