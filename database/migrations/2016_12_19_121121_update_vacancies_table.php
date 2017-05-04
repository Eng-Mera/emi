<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = [];
        $sql[] = 'ALTER TABLE `job_vacancies` DROP FOREIGN KEY `job_vacancies_restaurant_id_foreign`';
        $sql[] = 'ALTER TABLE `job_vacancies` ADD CONSTRAINT `job_vacancies_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;';

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
