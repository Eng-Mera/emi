<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReservationsAppendEnumStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function ($table) {
                $table->dropColumn('status');
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
            Schema::table('reservations', function ($table) {
                $table->enum('status', [
                    'PAID',
                    'PENDING',
                    'CANCELLED'
                ])->after('user_id');
            });
        }
    }
}
