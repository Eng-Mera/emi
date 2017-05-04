<?php
/**
 * Alter Restaurants
 *
 * Add columns to table restaurants for both
 * amount as a double
 * and a flag for controlling whether the restaurant should be reservable online
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRestaurants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('restaurants')) {
            Schema::table('restaurants', function ($table) {
                $table->boolean('reservable_online')->default(false)->after('instagram');
                $table->double('amount')->comment('A fixed amount of advance payment per person')->after('instagram');
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
        Schema::table('restaurants', function ($table) {
            $table->dropColumn(['reservable_online', 'amount']);
        });
    }
}
