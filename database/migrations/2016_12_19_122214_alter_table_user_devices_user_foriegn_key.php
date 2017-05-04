<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserDevicesUserForiegnKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = [];
        $sql[] = 'ALTER TABLE `user_devices` DROP FOREIGN KEY `user_devices_user_id_foreign`';
        $sql[] = 'ALTER TABLE `user_devices` ADD CONSTRAINT `user_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;';

        foreach ($sql as $q) {
            DB::statement($q);
        }
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
