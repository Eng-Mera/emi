<?php

use Illuminate\Database\Seeder;

class FacilitiesTableSeeder extends Seeder
{
    public function run()
    {

        App\Facility::where('id', '<>', '')->delete();

        factory(App\Facility::class, 20)->create();

        $rests = \App\Restaurant::with('facilities')->get();

        $facilities = \App\Facility::all();

        foreach ($rests as $rest) {
            $rest->facilities()->sync($facilities->random(7)->pluck('id')->toArray());
        }
    }
}
