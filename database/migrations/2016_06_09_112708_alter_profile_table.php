<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('profiles');

        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('mobile', 14);
            $table->string('address', 150);
            $table->text('qualification');

            $table->string('current_employee', 100);
            $table->string('current_position', 80);
            $table->string('previous_employee', 100);
            $table->string('previous_position', 80);
            $table->integer('experience_years')->unsigned();
            $table->float('current_salary');
            $table->float('expected_salary');

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
        Schema::drop('profiles');
    }
}
