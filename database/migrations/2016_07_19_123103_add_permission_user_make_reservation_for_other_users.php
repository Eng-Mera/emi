<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionUserMakeReservationForOtherUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            'name' => 'make-reservation-for-other-user',
            'display_name' => 'Make a Reservation for other User',
            'description' => 'This permission should be assigned to the roles allowed to make a reservation for other user under the same restaurant',
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
        $id = DB::table('permissions')->select(['id'])->where(['name' => 'make-reservation-for-other-user'])->pluck('id');
        if (!empty($id)) {
            DB::table('permissions')->delete($id[0]);
        }
    }
}
