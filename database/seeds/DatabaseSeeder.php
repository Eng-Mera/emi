<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Users
        $this->call(UsersTableSeeder::class);

        //Facilities
        $this->call(FacilitiesTableSeeder::class);

        //Dish Categories
        $this->call(DishCategoriesSeedTableSeeder::class);

        //Job Titles
        $this->call(JobTitleSeedTableSeeder::class);

        //Restaurants
        $this->call(RestaurantsTableSeeder::class);

        //Rating and Reviews
        $this->call(RatesReviewsTableSeeder::class);

        //Categories
        $this->call(CategoriesSeedTableSeeder::class);
    }
}
