<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRestaurantIdToCoupns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasTable('coupons') && Schema::hasTable('reservations')) {
                Schema::table('coupons', function ($table) {
                    $table->integer('restaurant_id')->unsigned()->nullable()->default(null)->after('type');
                    $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
                });
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
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasTable('coupons') && Schema::hasTable('reservations')) {
                Schema::table('coupons', function ($table) {
                    $table->dropForeign('coupons_restaurant_id_foreign');
                    $table->dropColumn('restaurant_id');
                });
            }
        });
    }
}
