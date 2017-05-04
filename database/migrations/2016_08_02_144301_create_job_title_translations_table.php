<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTitleTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_title_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_title_id')->unsigned();
            $table->string('locale');
            $table->index('locale');

            $table->string('job_title');
            $table->string('description');

            $table->unique(['job_title_id','locale']);
            $table->foreign('job_title_id')->references('id')->on('jobs_titles')->onDelete('cascade');
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
        Schema::drop('job_title_translations');
    }
}
