<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersSocialMediaSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE  `users` CHANGE  `fb_id` `fb_id`  VARCHAR( 100 ) NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE  `users` CHANGE  `intgm_id` `intgm_id` VARCHAR( 100 ) NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE  `users` CHANGE  `google_id` `google_id` VARCHAR( 100 ) NULL DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
