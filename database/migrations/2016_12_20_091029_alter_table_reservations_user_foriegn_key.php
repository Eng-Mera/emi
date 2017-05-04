<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableReservationsUserForiegnKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = [];
        $sql[] = 'ALTER TABLE `reservations` DROP FOREIGN KEY `reservations_user_id_foreign`';
        $sql[] = 'ALTER TABLE `reservations` ADD CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;';

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
