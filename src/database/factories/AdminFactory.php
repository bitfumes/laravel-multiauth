<?php

use Illuminate\Support\Str;
use Bitfumes\Multiauth\Model\Admin;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Admin::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => '$2y$04$xjUrUueX1ZrTGffLOvR4RuepA3JbmdmDItQXgx2BbDx5GJF4YkNHO', //secret123
        'remember_token' => Str::random(10),
        'active'         => 1,
    ];
});
