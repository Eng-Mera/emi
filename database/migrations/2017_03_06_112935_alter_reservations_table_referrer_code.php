<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReservationsTableReferrerCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'referrer_code')) {
                $table->string('referrer_code')->after('option')->nullable();
            }
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
            if (Schema::hasTable('reservations') ) {
                Schema::table('reservations', function ($table) {
                    $table->dropColumn('referrer_code');
                });
            }
        });
    }
}
