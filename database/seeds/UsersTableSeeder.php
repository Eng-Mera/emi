<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        App\User::where('id', '<>', '')->delete();

        factory(App\User::class, 100)->create()->each(function ($u) {

            $mediaFile = new \App\File();

            $u->profile()->save(factory(App\Profile::class)->make());
            
            $u->profilePicture()->save(factory(App\File::class)->make([
                'user_id' => $u->id,
                'category' => \App\File::getCategorySlug('user_profile_picture')
            ]));

            $u->attachRole(\App\Role::all()->random(1));
        });

    }
}
