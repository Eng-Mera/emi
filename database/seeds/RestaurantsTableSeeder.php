<?php

use Illuminate\Database\Seeder;

class RestaurantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Remove Old Restaurant.
         */
        App\Restaurant::where('id', '<>', '')->delete();

        $dishCategories = \App\DishCategory::all();

        /**
         * Start Creating Random Restaurants.
         */
        factory(App\Restaurant::class, 100)->create()->each(function ($restaurant) use ($dishCategories) {

            $owner = $restaurant->owner;

            //Facilities
            $ids = array_pluck(\App\Facility::all()->random(rand(6, 14))->toArray(), 'id');
            $restaurant->facilities()->sync($ids);

            //Favorite Users
            $restaurant->favoriteUsers()->sync(App\User::all()->random(3));


            //Gallery
            $restaurant->gallery()->save(factory(App\Gallery::class)->make([
                'user_id' => $owner->id,
                'restaurant_id' => $restaurant->id,
            ]));

            for ($i = rand(10, 15); $i < 16; $i++) {

                $user = \App\User::with(['roles' => function ($q) {
                    $q->where('name', 'diner');
                }])->first();

                if ($user->id) {

                    $restaurant->gallery->file()->save(factory(App\File::class)->make([
                        'user_id' => $user->id,
                        'category' => \App\File::getCategorySlug('restaurant_gallery')
                    ]));
                }

            }

            //Opening days
            for ($i = rand(0, 6); $i < 7; $i++) {
                $restaurant->openingDays()->save(factory(App\OpeningDay::class)->make([
                    'restaurant_id' => $restaurant->id,
                ]));
            }

            //Menu Items
            for ($i = rand(0, 20); $i < 21; $i++) {

                $menuItem = $restaurant->menuItems()->save(factory(App\MenuItem::class)->make([
                    'restaurant_id' => $restaurant->id,
                    'dish_category_id' => $dishCategories->random(1)->id
                ]));

                $menuItem->image()->save(factory(App\File::class)->make([
                    'user_id' => $owner->id,
                    'category' => \App\File::getCategorySlug('restaurant_menu_item')
                ]));
            }

        });

    }
}
