<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_providers', function (Blueprint $table) {

            $table->increments('id');
            $table->text('pid');
            $table->text('token');
            $table->text('refresh_token');
            $table->text('secret');
            $table->dateTime('expires_at');
            $table->tinyInteger('expires');
            $table->string('provider_type', 100);
            $table->integer('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('social_providers');
    }
}
