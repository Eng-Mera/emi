<?php

use Illuminate\Database\Seeder;


class JobVacancySeedTableSeeder extends Seeder
{
    public function run()
    {
        App\JobVacancy::where('id', '<>', '')->delete();

        $jobs = \App\JobTitle::all();

        $restaurants = \App\Restaurant::all();

        foreach ($restaurants as $restaurant) {

            $jobsRandom = $jobs->random(rand(2, 5));

            foreach ($jobsRandom as $jobObj) {

                $data = factory(App\JobVacancy::class, 1)->make([
                    'job_title_id' => $jobObj->id,
                    'user_id' => $restaurant->owner_id,
                    'restaurant_id' => $restaurant->id,
                ])->toArray();

                \App\JobVacancy::create($data);

            }
        }

    }
}
