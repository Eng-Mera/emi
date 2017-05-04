<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDishCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dish_category_id')->unsigned();
            $table->string('locale');
            $table->index('locale');

            $table->string('category_name');

            $table->unique(['dish_category_id','locale']);
            $table->foreign('dish_category_id')->references('id')->on('dish_categories')->onDelete('cascade');
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
        Schema::drop('dish_category_translations');
    }
}
