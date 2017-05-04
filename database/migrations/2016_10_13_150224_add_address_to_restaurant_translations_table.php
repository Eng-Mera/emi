<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressToRestaurantTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_translations', 'address')) {
                $table->string('address')->after('description')->nullable();
            }
        });
        Schema::table('restaurants', function (Blueprint $table) {

            $table->dropColumn('address');

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
