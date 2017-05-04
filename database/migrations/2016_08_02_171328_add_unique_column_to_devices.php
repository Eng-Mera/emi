<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueColumnToDevices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('user_devices')){
            Schema::table('user_devices', function(Blueprint $table){
                $table->unique([
                    'user_id',
                    'device_type'
                ]);
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
        if(Schema::hasTable('user_devices')){
            Schema::table('user_devices', function(Blueprint $table){
                $table->dropUnique([
                    'user_id',
                    'device_type'
                ]);
            });
        }
    }
}
