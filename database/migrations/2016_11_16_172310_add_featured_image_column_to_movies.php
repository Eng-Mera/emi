<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeaturedImageColumnToMovies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('movies')) {
            Schema::table('movies', function (Blueprint $table) {
                if (!in_array('add_to_featured', $table->getColumns())) {
                    $table->boolean('add_to_featured')->after('enable_booking');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public
    function down()
    {
        if (Schema::hasTable('movies')) {
            Schema::table('movies', function (Blueprint $table) {
                if (in_array('add_to_featured', $table->getColumns())) {
                    $table->dropColumn('add_to_featured');
                }
            });
        }
    }
}
