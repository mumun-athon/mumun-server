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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $email = $faker->email;

    return [
        'name' => $faker->name,
        'email' => $email,
        'password' => bcrypt('qweasd123'),
        'remember_token' => str_random(10),
        'x_auth_token' => bcrypt($email),
    ];
});

$factory->define(App\Raid::class, function (Faker\Generator $faker) {
    return [
        'doc_number' => $faker->phoneNumber,
        'start_date' => Carbon\Carbon::now()->addDay($faker->numberBetween(0, 3))->format('Y-m-d'),
        'end_date' => Carbon\Carbon::now()->addDay($faker->numberBetween(4, 8))->format('Y-m-d'),
        'description' => $faker->sentence(9),
    ];
});

$factory->define(App\RaidLocation::class, function (Faker\Generator $faker) {
    return [
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
    ];
});

$factory->define(App\IlegalReport::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'ktp' => $faker->phoneNumber,
        'description' => $faker->sentence(9),
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
        'photo' => $faker->imageUrl(240, 320),
    ];
});
