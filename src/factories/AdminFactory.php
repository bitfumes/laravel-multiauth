<?php

use Bitfumes\Multiauth\Model\Admin;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Admin::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => 'secret',
        'remember_token' => str_random(10),
    ];
});
