<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableReviewsAddTitleRateId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {

            $table->addColumn('string', 'title', ['length' => '60'])->after('user_id');
            $table->integer('rate_id')->unsigned()->index()->after('user_id');
            $table->foreign('rate_id')->references('id')->on('rates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {

            $table->dropColumn(['title', 'rate_id']);
        });
    }
}
