<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReservationsColAdvancePaymentDefaultValue extends Migration
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
                if (Schema::hasColumn('reservations', 'advance_payment')) {
                    $table->dropColumn('advance_payment');
                }
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
                $table->boolean('advance_payment')->default(false)->after('amount');
            });
        }
    }
}
