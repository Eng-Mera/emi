<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminReviewsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('admin_reviews', function (Blueprint $table) {
            $table->dropColumn(['restaurant_name', 'description']);
        });

        Schema::create('admin_reviews_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_review_id')->unsigned();
            $table->foreign('admin_review_id')->references('id')->on('admin_reviews')->onDelete('cascade');
            $table->string('locale');
            $table->index('locale');
            $table->foreign('locale')->references('lang')->on('langs')->onDelete('cascade');

            $table->string('restaurant_name');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_reviews_translations');
    }
}
