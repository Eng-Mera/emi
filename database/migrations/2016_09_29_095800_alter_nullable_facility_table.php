<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNullableFacilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('facilities')) {
            Schema::table('facilities', function (Blueprint $table) {
                $table->string('icon')->nullable()->change();
            });
        }

        if (Schema::hasTable('facility_translations')) {
            Schema::table('facility_translations', function (Blueprint $table) {
                $table->string('name')->nullable()->change();
                $table->string('description')->nullable()->change();
            });
        }

        if (Schema::hasTable('category_translations')) {
            Schema::table('category_translations', function (Blueprint $table) {
                $table->string('category_name')->nullable()->change();
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
        if(Schema::hasTable('facilities')){
            Schema::table('facilities', function(Blueprint $table){
                $table->string('icon')->nullable(false)->change();
            });
        }
        if(Schema::hasTable('facility_translations')){
            Schema::table('facility_translations', function(Blueprint $table){
                $table->string('name')->nullable(false)->change();
                $table->string('description')->nullable(false)->change();
            });
        }
        if(Schema::hasTable('category_translations')){
            Schema::table('category_translations', function(Blueprint $table){
                $table->string('category_name')->nullable(false)->change();
            });
        }
    }
}
