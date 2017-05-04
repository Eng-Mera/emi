<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnReservationIdForChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->integer('parent_reservation_id')->unsigned()->references('id')->on('reservations')->after('advance_payment');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function (Blueprint $table) {
                if (in_array('parent_reservation_id', $table->getColumns())) {
                    $table->dropColumn('parent_reservation_id');
                }
            });
        }
    }
}
