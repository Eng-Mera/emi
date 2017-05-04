<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function ($table) {
                $table->double('amount')->after('date')->comment('amount = the number of people * the fixed amount per person');
                $table->double('discount')->after('date');
                $table->double('total')->after('date')->comment('total = amount - discount');
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
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function ($table) {
                $table->dropColumn(['amount', 'discount', 'total']);
            });
        }

    }
}
