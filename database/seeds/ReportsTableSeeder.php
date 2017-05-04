<?php

use Illuminate\Database\Seeder;

class ReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        App\Report::where('id', '<>', '')->delete();

        factory(App\Report::class, 100)->create();

    }
}
