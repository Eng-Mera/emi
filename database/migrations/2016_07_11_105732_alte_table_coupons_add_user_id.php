<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteTableCouponsAddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('coupons'))
        {
            Schema::table('coupons', function(Blueprint $table){
                $table->integer('user_id')->unsigned()->default(null)->reference('id')->on('users')->after('reservation_id');
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
        if(Schema::hasTable('coupons')){
            Schema::table('coupons', function(Blueprint $table){
                $table->dropColumn('user_id');
            });
        }
    }
}
