<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


/**
 * Users Factory using Faker generator
 */
$factory->define(App\User::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'dob' => $faker->dateTimeThisCentury->format('Y-m-d'),
        'username' => $faker->userName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(123456),
        'remember_token' => str_random(10),
    ];
});

/**
 * Users Factory using Faker generator
 */
$factory->define(App\Category::class, function (Faker\Generator $faker) {

    return [
        'category_name:en' => $faker->name,
        'category_name:ar' => $faker->name,
    ];
});

/**
 * Users Factory using Faker generator
 */
$factory->define(App\Movie::class, function (Faker\Generator $faker) {

    $roles = \App\Role::with(['users'])->where('name', \App\Role::SUPER_ADMIN)->first();

    $user = $roles->users->random(1);

    return [
        'name' => $faker->name,
        'description' => $faker->text(250),
        'booking_url' => $faker->url,
        'enable_booking' => 1,
        'user_id' => $user->id
    ];
});

/**
 * Job Titles Factory using Faker generator
 */
$factory->define(App\JobTitle::class, function (Faker\Generator $faker) {
    return [
        'job_title:en' => $faker->jobTitle,
        'description:en' => $faker->text(200),
        'job_title:ar' => $faker->jobTitle,
        'description:ar' => $faker->text(200),
    ];
});

/**
 * Job Titles Factory using Faker generator
 */
$factory->define(App\JobVacancy::class, function (Faker\Generator $faker) {

    return [
        'description' => $faker->text(200),
        'status' => rand(0, 1)
    ];
});


/**
 * Cities Factory using Faker generator
 */
$factory->define(App\City::class, function (Faker\Generator $faker) {

    return [
        'city_name:en' => $faker->city,
        'city_name:ar' => $faker->city,
    ];
});

/**
 * Users Factory using Faker generator
 */
$factory->define(App\DishCategory::class, function (Faker\Generator $faker) {

    return [
        'category_name:en' => $faker->name,
        'category_name:ar' => $faker->name,
//        'remember_token' => str_random(10),
    ];
});

$factory->define(App\File::class, function (Faker\Generator $faker, $attr) {

    switch ($attr['category']) {

        case 'restaurant_logo':
        case 'restaurant_gallery':
        case 'restaurant_featured':
        case 'restaurant_menu_item':
            $imageType = 'food';
            break;

        case 'user_profile_picture':
        default:
            $imageType = 'people';
            break;
    }

    $name = str_replace('/tmp/', '', $faker->image('/tmp', 500, 500, $imageType));

    if ($name) {

        $file = new \Illuminate\Http\UploadedFile('/tmp/' . $name, $name);

        \Illuminate\Support\Facades\Storage::disk('local')->put($name, \Illuminate\Support\Facades\File::get($file));

        return [
            'mime' => $file->getClientMimeType(),
            'original_filename' => $file->getClientOriginalName(),
            'filename' => $name,
        ];
    }

});

/**
 * Users Factory using Faker generator
 */
$factory->define(App\Profile::class, function (Faker\Generator $faker) {

    return [

        'mobile' => $faker->numberBetween(10000000000, 99999999999),
        'address' => $faker->address,
        'qualification' => str_random(10),
        'current_employee' => $faker->company,
        'current_position' => $faker->jobTitle,
        'previous_employee' => $faker->company,
        'previous_position' => $faker->jobTitle,
        'experience_years' => $faker->numberBetween(1, 20),
        'current_salary' => $faker->randomFloat(2, 1000, 15000),
        'expected_salary' => $faker->randomFloat(2, 3000, 20000),
    ];
});

/**
 * Users Factory using Faker generator
 */
$factory->define(App\Review::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->text(60),
        'description' => $faker->realText(300),
        'parent_id' => NULL,
        'last_visit_date' => $faker->date(),
    ];
});

/**
 * Users Factory using Faker generator
 */
$factory->define(App\Rate::class, function (Faker\Generator $faker) {

    return [
        'rate_value' => rand(1, 5),
    ];
});

/**
 * Restaurant Factory
 */
$factory->define(App\Restaurant::class, function (Faker\Generator $faker) use ($factory) {

    $users = \App\Role::with('users')->where('name', 'restaurant-manager')->get();
    if (!isset($users->random(1)->users[0]->id)) {
        echo "Restaurant admin user is missing \n";
    }

    $name = $faker->streetName;

    return [
        'owner_id' => $users->random(1)->users[0]->id,
        'name:en' => $name,
        'name:ar' => $name,
        'slug' => str_slug($name),
        'address' => $faker->address,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'phone' => $faker->numberBetween('01000000000', '01299999999'),
        'email' => $faker->email,
        'description:en' => $faker->text,
        'description:ar' => $faker->text,
        'dress_code' => $faker->postcode,
        'facebook' => 'fb_' . $faker->userName,
        'twitter' => 'tw_' . $faker->userName,
        'instagram' => 'insta_' . $faker->userName,
    ];
});

/**
 * Opening Days Factory
 */
$factory->define(App\OpeningDay::class, function (Faker\Generator $faker) use ($factory) {

    $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

    return [
        'status' => 1,
        'day_name' => $days[array_rand($days)],
        'from' => $faker->time(),
        'to' => $faker->time(),
    ];

});


//$factory->define(App\RestaurantTranslation::class, function (Faker\Generator $faker) use ($factory) {
//
//    return [
//        'locale' => array_rand(['en', 'ar']),
//    ];
//
//});

/**
 * Restaurant Gallery
 */
$factory->define(App\Gallery::class, function (Faker\Generator $faker) use ($factory) {

    $name = $faker->streetName;

    return [
        'name:en' => $name,
        'name:ar' => $name,
        'slug' => str_slug($name),
        'description:en' => $faker->text(500),
        'description:ar' => $faker->text(500),
    ];

});

/**
 * Categories
 */
$factory->define(App\Category::class, function (Faker\Generator $faker) use ($factory) {

    return [
        'category_name:en' => $faker->colorName,
        'category_name:ar' => $faker->colorName,
    ];
});


/**
 * Categories
 */
$factory->define(App\ReservationPolicy::class, function (Faker\Generator $faker) use ($factory) {

    $start_date = $faker->date();

    return [
        'name' => $faker->name,
        'start_date' => $start_date,
        'end_date' => date('Y-m-d', strtotime($start_date) + (rand(3, 31) * 24 * 60 * 60)),
        'amount' => $faker->randomNumber(2),
        'status' => 1
    ];
});

/**
 * Menu Items
 */
$factory->define(App\MenuItem::class, function (Faker\Generator $faker) use ($factory) {

    $name = $faker->streetName;

    return [
        'name:en' => $name,
        'name:ar' => $name,
        'slug' => str_slug($name),
        'price' => $faker->randomFloat(3, 100),
        'popular_dish' => rand(0, 1),
        'description:en' => $faker->text(400),
        'description:ar' => $faker->text(400),
    ];

});

/**
 * Faclilites
 */
$factory->define(App\Facility::class, function (Faker\Generator $faker) use ($factory) {

    return [
        'name:en' => $faker->name,
        'description:en' => $faker->text(300),
        'name:ar' => $faker->name,
        'description:ar' => $faker->text(300),
    ];
});

/**
 * Boggers
 */
$factory->define(App\Role::class, function (Faker\Generator $faker) use ($factory) {

    $users = App\User::all();

    if (!$users->count()) {
        $userSeeder = new UsersTableSeeder();
        $userSeeder->run();

        $users = App\User::all();
    }

    return [
        'owner_id' => $users->random(1)->id,
        'restaurnt_name' => $faker->streetName,
    ];
});

/**
 * Reports
 */

$factory->define(App\Report::class, function (Faker\Generator $faker) use ($factory) {
    $users = App\User::all();
    return [
        'report_type' => array_rand(['Restaurant' => 'Restaurant', 'Review' => 'Review', 'Photo' => 'Photo']),
        'report_subject' => array_rand(['Spam' => 'Spam', 'Offensive Language' => 'Offensive Language', 'Wrong Restaurant' => 'Wrong Restaurant', 'Irrelevant' => 'Irrelevant']),
        'reported_id' => $faker->numberBetween(1, 100),
        'user_id' => $users->random(1)->id,
        'details' => $faker->text(300),
    ];
});

