<?php

use Bitfumes\Multiauth\Model\Role;

$factory->define(Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});
