<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id')->unsigned();
            $table->string('locale');
            $table->index('locale');

            $table->string('address');

            $table->unique(['branch_id','locale']);
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('locale')->references('lang')->on('langs')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('branch_translations');
    }
}
