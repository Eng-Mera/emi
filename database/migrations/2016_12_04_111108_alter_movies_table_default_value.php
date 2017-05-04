<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMoviesTableDefaultValue extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('movies')) {
            Schema::table('movies', function ($table) {
                $table->boolean('add_to_featured')->nullable()->default(false)->change();
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
        if (Schema::hasTable('movies')) {
            Schema::table('movies', function ($table) {
                $table->dropColumn('add_to_featured');
            });
            Schema::table('movies', function ($table) {
                $table->boolean('add_to_featured')->after('booking_url');
            });
        }
    }
}
