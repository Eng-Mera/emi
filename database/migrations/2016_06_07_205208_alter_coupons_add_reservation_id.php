<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCouponsAddReservationId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('coupons') && Schema::hasTable('reservations')) {
            Schema::table('coupons', function ($table) {
                $table->integer('reservation_id')->unsigned()->nullable()->default(null)->after('type');
                $table->foreign('reservation_id')->references('id')->on('reservations');
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
        if (Schema::hasTable('coupons') && Schema::hasTable('reservations')) {
            Schema::table('coupons', function ($table) {
                $table->dropForeign('reservation_id');
                $table->dropColumn('reservation_id');
            });
        }
    }
}
