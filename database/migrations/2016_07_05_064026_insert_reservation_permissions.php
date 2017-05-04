<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertReservationPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            'name' => 'change-reservation',
            'display_name' => 'Change Reservation',
            'description' => 'This permission should be assigned to the roles allowed to change the reservations',
            'created_at' => strftime('%Y-%m-%d %H:%M:%S'),
            'updated_at' => strftime('%Y-%m-%d %H:%M:%S')
        ]);

        DB::table('permissions')->insert([
            'name' => 'reschedule-reservation',
            'display_name' => 'Reschedule Reservation',
            'description' => 'This permission should be assigned to the roles allowed to reschedule the reservations',
            'created_at' => strftime('%Y-%m-%d %H:%M:%S'),
            'updated_at' => strftime('%Y-%m-%d %H:%M:%S')
        ]);

        DB::table('permissions')->insert([
            'name' => 'cancel-reservation',
            'display_name' => 'Cancel Reservation',
            'description' => 'This permission should be assigned to the roles allowed to cancel the reservations',
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
        $id = DB::table('permissions')->select(['id'])->where(['name' => 'change-reservation'])->pluck('id');
        if (!empty($id)) {
            DB::table('permissions')->delete($id[0]);
        }

        $id = DB::table('permissions')->select(['id'])->where(['name' => 'reschedule-reservation'])->pluck('id');
        if (!empty($id)) {
            DB::table('permissions')->delete($id[0]);
        }

        $id = DB::table('permissions')->select(['id'])->where(['name' => 'cancel-reservation'])->pluck('id');
        if (!empty($id)) {
            DB::table('permissions')->delete($id[0]);
        }
    }
}
