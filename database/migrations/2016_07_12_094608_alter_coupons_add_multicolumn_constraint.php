<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCouponsAddMulticolumnConstraint extends Migration
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
                $table->unique(['code', 'user_id']);
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
                $table->dropUnique(['coupons_code_unique', 'coupons_user_id_unique']);
            });
        }
    }
}
