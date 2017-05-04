<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewStatusToReservation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {

            $status = [
                'PAID',
                'PENDING',
                'CANCELLED',
                'APPROVED',
                'REJECTED',
                'RESCHEDULED',
                'CHANGE_REQUESTED',
                'ARRIVED'
            ];


            $table->enum('status', $status)->default('PENDING')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
}
