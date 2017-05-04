<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips',function ( Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->unsigned()->nullable();
            $table->foreign('rider_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('driver_id')->unsigned()->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');

            $table->string('start_address',512)->nullable();
            $table->string('start_lat',255)->nullable();
            $table->string('start_long',255)->nullable();
            $table->string('end_address',512)->nullable();
            $table->string('end_lat',255)->nullable();
            $table->string('end_long',255)->nullable();
            $table->enum('status', ['send', 'accepted','canceled','started','progress','arrived']);

            $table->timestamp('start_date');
            $table->timestamp('end_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('trips');
    }
}
