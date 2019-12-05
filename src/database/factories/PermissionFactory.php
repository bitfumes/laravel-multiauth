<?php

use Faker\Generator as Faker;

$permissionModel = config('multiauth.models.permission');
$factory->define($permissionModel, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
