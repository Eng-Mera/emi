<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExternalReferenceToReservation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('reservations')){
            Schema::table('reservations', function(Blueprint $table){
                $table->string('external_reference')->nullable()->after('user_id');
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
        if(Schema::hasTable('reservations')){
            Schema::table('reservations', function(Blueprint $table){
                $table->dropColumn('external_reference');
            });
        }
    }
}
