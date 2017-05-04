<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropRestaurantUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('restaurant_users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('restaurant_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('restaurant_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }
}
