<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableReservation extends Migration
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
                $table->integer('coupon_id')
                    ->unsigned()
                    ->after('total')
                    ->default(null)
                    ->nullable();
                $table->foreign('coupon_id')->references('id')
                    ->on('coupons');
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
                $table->dropColumn(['coupon_id']);
            });
        }
    }
}
