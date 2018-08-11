<?php

use Bitfumes\Multiauth\Model\Role;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Role::class, function (Faker\Generator $faker) {
    return [
        'name' => 'super',
    ];
});
