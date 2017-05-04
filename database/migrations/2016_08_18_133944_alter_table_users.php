<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `users` MODIFY `fb_id` BIGINT(18) UNSIGNED NULL;');
        DB::statement('ALTER TABLE `users` MODIFY `google_id` BIGINT(18) UNSIGNED NULL;');
        DB::statement('ALTER TABLE `users` MODIFY `intgm_id` BIGINT(18) UNSIGNED NULL;');
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
