<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropRedundantColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories',function ($table){
            $table->dropColumn('category_name');
        });
        Schema::table('cities',function ($table){
            $table->dropColumn('city_name');
        });
        Schema::table('dish_categories',function ($table){
            $table->dropColumn('category_name');
        });
        Schema::table('facilities',function ($table){
            $table->dropColumn('name');
            $table->dropColumn('description');
        });
        Schema::table('galleries',function ($table){
            $table->dropColumn('name');
            $table->dropColumn('description');
        });
        Schema::table('jobs_titles',function ($table){
            $table->dropColumn('job_title');
            $table->dropColumn('description');
        });
        Schema::table('job_vacancies',function ($table){
            $table->dropColumn('description');
        });
        Schema::table('menu_items',function ($table){
            $table->dropColumn('name');
            $table->dropColumn('description');
        });
        Schema::table('restaurants',function ($table){
            $table->dropColumn('name');
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
