<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUnusedColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function (Blueprint $table) {
                if (Schema::hasColumn('reservations', 'parent_reservation_id')) {
                    $table->dropColumn('parent_reservation_id');
                }
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
        //
    }
}
