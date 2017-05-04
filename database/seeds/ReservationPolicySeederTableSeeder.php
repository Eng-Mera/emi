<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ReservationPolicySeederTableSeeder extends Seeder
{
    public function run()
    {
        App\ReservationPolicy::where('id', '<>', '')->delete();

        $restaurants = \App\Restaurant::all();

        foreach ($restaurants as $restaurant) {
            for ($i = 0; $i < 11; $i++) {

                $data = factory(App\ReservationPolicy::class, 1)->make([
                    'restaurant_id' => $restaurant->id,
                    'user_id' => $restaurant->owner_id
                ])->toArray();

                \App\ReservationPolicy::create($data);
            }

        }
    }
}
