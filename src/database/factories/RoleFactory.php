<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

use Bitfumes\Multiauth\Model\Role;

$factory->define(Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});
