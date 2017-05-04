<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTypeRestaurantPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_type_restaurant', function (Blueprint $table) {
            $table->integer('payment_type_id')->unsigned()->index();
            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onDelete('cascade');
            $table->integer('restaurant_id')->unsigned()->index();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->primary(['payment_type_id', 'restaurant_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_type_restaurant');
    }
}
