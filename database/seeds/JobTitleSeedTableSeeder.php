<?php

use Illuminate\Database\Seeder;


class JobTitleSeedTableSeeder extends Seeder
{
    public function run()
    {
        App\JobTitle::where('id', '<>', '')->delete();

        factory(App\JobTitle::class, 30)->create();

    }
}
