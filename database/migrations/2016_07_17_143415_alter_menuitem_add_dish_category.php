<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMenuitemAddDishCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('menu_items', function (Blueprint $table) {
//            $table->integer('dish_category_id')->unsigned()->index();
//            $table->foreign('dish_category_id')->references('id')->on('dish_categories')->onDelete('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('dish_category_id');
        });
    }
}
