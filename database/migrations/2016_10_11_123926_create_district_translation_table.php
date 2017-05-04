<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('district_id')->unsigned();
            $table->string('locale');
            $table->index('locale');

            $table->string('district_name');

            $table->unique(['district_id','locale']);
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('locale')->references('lang')->on('langs')->onDelete('cascade');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->integer('district_id')->after('city_id')->unsigned()->index()->nullable();
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('district_translations');
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign('district_id');
            $table->dropIndex('branches_district_id_foreign');
            $table->dropColumn('district_id');
        });
    }
}
