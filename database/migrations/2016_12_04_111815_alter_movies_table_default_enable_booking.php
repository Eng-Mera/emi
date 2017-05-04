<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMoviesTableDefaultEnableBooking extends Migration
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
                $table->boolean('enable_booking')->nullable()->default(false)->change();
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
                $table->dropColumn('enable_booking');
            });
            Schema::table('movies', function ($table) {
                $table->enum('enable_booking', [0, 1])->after('user_id');
            });
        }

    }
}
