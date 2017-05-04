<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCouponsDropUniqueConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('coupons')){
            Schema::table('coupons', function(Blueprint $table){
                $table->dropUnique('coupons_code_unique');
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
                $table->unique('code');
            });
        }
    }
}
