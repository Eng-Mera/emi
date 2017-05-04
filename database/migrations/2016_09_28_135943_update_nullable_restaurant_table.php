<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNullableRestaurantTable extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('double', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `restaurants` MODIFY `owner_id` int(10) UNSIGNED NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `address` varchar(255) COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `latitude` varchar(255) COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `longitude` varchar(255) COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `phone` varchar(255) COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `email` varchar(255) COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `dress_code` int(10) NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `facebook` varchar(255) COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `twitter` varchar(255) COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `instagram` varchar(255) COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `snap_chat` varchar(255) COLLATE utf8_unicode_ci NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `type` int(11) NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `htr_stars` int(11) NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `price_from` double NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `price_to` double NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `city_id` int(10) UNSIGNED NULL;');
        DB::statement('ALTER TABLE `restaurants` MODIFY `amount` float(8,2) NULL;');

        if (Schema::hasTable('restaurant_translations')) {
            Schema::table('restaurant_translations', function (Blueprint $table) {
                $table->string('name')->nullable()->change();
                $table->string('description')->nullable()->change();
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
        //
    }
}
