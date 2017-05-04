<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cache', function($table) {
            $table->string('key')->unique();
            $table->text('value');
            $table->integer('expiration');
        });
        Schema::table('reservations', function($table){
            $table->boolean('advance_payment')->default(false)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cache', function (Blueprint $table) {
            Schema::drop('cache');
        });
        Schema::table('reservations', function($table){
            $table->dropColumn('advance_payment');
        });
    }
}
