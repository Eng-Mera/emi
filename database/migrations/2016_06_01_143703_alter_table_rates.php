<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('rates', function (Blueprint $table) {
            $table->integer('review_id')->unsigned()->index()->after('user_id');
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
            $table->dropColumn('last_visit_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropForeign('rates_review_id_foreign');
            $table->dropColumn('review_id');
        });
    }
}
