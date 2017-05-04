<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CitiesSeedTableSeeder extends Seeder
{
    public function run()
    {
        App\City::where('id', '<>', '')->delete();

        factory(App\City::class, 100)->create();

        $rests = \App\Restaurant::all();

        $cities = \App\City::all();

        foreach ($rests as $rest) {
            $rest->city_id = $cities->random(1)->id;
            $rest->save();
        }

    }
}
