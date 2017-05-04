<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('facility_id')->unsigned();
            $table->string('locale');
            $table->index('locale');

            $table->string('name');
            $table->text('description');

            $table->unique(['facility_id','locale']);
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->foreign('locale')->references('lang')->on('langs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('facility_translations');
    }
}
