<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserRestaurants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('manage_restaurant_id')->unsigned()->after('created_by')->nullable();
                $table->foreign('manage_restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (in_array('manage_restaurant_id', $table->getColumns())) {
                    $table->dropColumn('manage_restaurant_id');
                }
            });
        }
    }
}
