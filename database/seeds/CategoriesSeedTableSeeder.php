<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CategoriesSeedTableSeeder extends Seeder
{
    public function run()
    {
        App\Category::where('id', '<>', '')->delete();

        factory(App\Category::class, 10)->create();

        $rests = \App\Restaurant::with('categories')->get();

        $cats = \App\Category::all();

        foreach ($rests as $rest) {
            $rest->categories()->sync($cats->random(2)->pluck('id')->toArray());
        }

    }
}
