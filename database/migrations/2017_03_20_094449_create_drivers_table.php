<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('drivers',function ( Blueprint $table){
//            $table->increments('id');
//            $table->string('name',255);
//            $table->string('username',255)->unique();
//            $table->string('password',255);
//            $table->string('phone',255)->unique();
//            $table->string('email',255)->unique();
//            $table->string('address',512)->nullable();
//            $table->text('about')->nullable();
//            $table->integer('age')->default(0);
//            $table->boolean('gender')->default(0);
//            $table->boolean('accept_gender')->default(0);
//            $table->string('national_id',255)->nullable();
//            $table->string('national_id_image',512)->nullable();
//            $table->string('driver_license_id',255)->nullable();
//            $table->string('driver_license_image',512)->nullable();
//            $table->string('car_license_id',255)->nullable();
//            $table->string('car_license_image',512)->nullable();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('drivers');
    }
}
