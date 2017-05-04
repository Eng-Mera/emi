<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFilemetaAddLikesDislikesShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files_meta', function (Blueprint $table) {
            $table->integer('likes')->unsigned()->after('method');
            $table->integer('dislikes')->unsigned()->after('likes');
            $table->integer('shares')->unsigned()->after('dislikes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files_meta', function (Blueprint $table) {
            $table->dropColumn('likes');
            $table->dropColumn('dislikes');
            $table->dropColumn('sjares');
        });
    }
}
