<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCoupons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function ($table) {
                $table->unique('code');
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
        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function ($table) {
                $table->dropUnique('code');
            });
        }
    }
}
