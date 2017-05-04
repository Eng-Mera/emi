<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionListing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            'name' => 'list-reservations',
            'display_name' => 'List Reservations',
            'description' => 'This permission should be assigned to the roles allowed to list the reservations',
            'created_at' => strftime('%Y-%m-%d %H:%M:%S'),
            'updated_at' => strftime('%Y-%m-%d %H:%M:%S')
        ]);

        DB::table('permissions')->insert([
            'name' => 'list-restaurant-reservations',
            'display_name' => 'List Restaurant Reservations',
            'description' => 'This permission should be assigned to the roles allowed to list the restaurant reservations',
            'created_at' => strftime('%Y-%m-%d %H:%M:%S'),
            'updated_at' => strftime('%Y-%m-%d %H:%M:%S')
        ]);

        DB::table('permissions')->insert([
            'name' => 'reservation-receive-notification',
            'display_name' => 'Reservation Receive Notification',
            'description' => 'This permission should be assigned to the roles allowed to receive notifications for the restaurant reservations',
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
        $id = DB::table('permissions')->select(['id'])->where(['name' => 'list-reservations'])->pluck('id');
        if (!empty($id)) {
            DB::table('permissions')->delete($id[0]);
        }

        $id = DB::table('permissions')->select(['id'])->where(['name' => 'list-restaurant-reservations'])->pluck('id');
        if (!empty($id)) {
            DB::table('permissions')->delete($id[0]);
        }

        $id = DB::table('permissions')->select(['id'])->where(['name' => 'reservation-receive-notification'])->pluck('id');
        if (!empty($id)) {
            DB::table('permissions')->delete($id[0]);
        }
    }
}
