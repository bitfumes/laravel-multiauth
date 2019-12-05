<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$roleModel = config('multiauth.models.role');
$factory->define($roleModel, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});
