<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('langs', function (Blueprint $table) {
                $table->string('lang');
                $table->index('lang');
                $table->primary('lang');
            });
            
            \App\Lang::create(['lang' => 'ar']);
            \App\Lang::create(['lang' =>'en']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('langs');
    }
}
