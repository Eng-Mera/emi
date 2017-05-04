<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `users` MODIFY `fb_id` INTEGER UNSIGNED NULL;');
        DB::statement('ALTER TABLE `users` MODIFY `google_id` INTEGER UNSIGNED NULL;');
        DB::statement('ALTER TABLE `users` MODIFY `intgm_id` INTEGER UNSIGNED NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `users` MODIFY `fb_id` INTEGER UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE `users` MODIFY `google_id` INTEGER UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE `users` MODIFY `intgm_id` INTEGER UNSIGNED NOT NULL;');
    }
}
