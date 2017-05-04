<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceTypeToTableUserDevices extends Migration
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
                $table->enum('device_type', [
                    'IOS',
                    'ANDROID'
                ])->after('device_id');
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
                $table->dropColumn('device_type');
            });
        }
    }
}
