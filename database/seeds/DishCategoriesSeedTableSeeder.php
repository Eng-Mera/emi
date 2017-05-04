<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class DishCategoriesSeedTableSeeder extends Seeder
{
    public function run()
    {
        App\DishCategory::where('id', '<>', '')->delete();

        factory(App\DishCategory::class, 10)->create();

        $rests = \App\Restaurant::with('categories')->get();

        $cats = \App\Category::all();

    }
}
