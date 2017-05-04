<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class MoviesSeedTableSeeder extends Seeder
{
    public function run()
    {
        App\Movie::where('id', '<>', '')->delete();

        factory(App\Movie::class, 25)->create()->each(function ($movie) {

            $movie->poster()->save(factory(App\File::class)->make([
                'user_id' => $movie->user_id,
                'category' => \App\File::getCategorySlug('movie_poster')
            ]));

        });


    }
}
