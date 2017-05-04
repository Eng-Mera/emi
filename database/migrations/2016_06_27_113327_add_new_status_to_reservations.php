<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewStatusToReservations extends Migration
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
                $table->enum('status', [
                    'PAID', 'PENDING', 'CANCELLED', 'APPROVED', 'REJECTED', 'RESCHEDULED',
                    //new
                    'CHANGE_REQUESTED'
                ])->after('user_id')
                    ->default('PENDING');
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
                $table->dropColumn('status');
            });
        }
    }
}
