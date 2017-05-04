<?php

use Illuminate\Database\Seeder;

class RatesReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurants = \App\Restaurant::all();

        foreach ($restaurants as $restaurant) {

            for ($i = 0; $i < rand(10, 50); $i++) {

                $user = \App\User::all()->random(1);

                //Reviews
                $review = $restaurant->reviews()->save(factory(App\Review::class)->make([
                    'restaurant_id' => $restaurant->id,
                    'user_id' => $user->id,
                ]));

                //Rates
                for ($y = 1; $y < 13; $y++) {
                    $restaurant->rates()->save(factory(App\Rate::class)->make([
                        'restaurant_id' => $restaurant->id,
                        'user_id' => $user->id,
                        'type' => $y,
                        'review_id' => $review->id
                    ]));
                }
                
            }
        }
    }
}
