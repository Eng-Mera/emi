<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePhotoCommentsAddForignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = [];
        $sql[] = 'ALTER TABLE `photo_comments` DROP FOREIGN KEY `photo_comments_file_id_foreign`';
        $sql[] = 'ALTER TABLE `photo_comments` ADD CONSTRAINT `photo_comments_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;';

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
