<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobVacancyTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_vacancy_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_vacancy_id')->unsigned();
            $table->string('locale');
            $table->index('locale');

            $table->string('description');

            $table->unique(['job_vacancy_id','locale']);
            $table->foreign('job_vacancy_id')->references('id')->on('job_vacancies')->onDelete('cascade');
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
        Schema::drop('job_vacancy_translations');
    }
}
