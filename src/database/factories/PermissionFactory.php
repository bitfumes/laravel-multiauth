<?php

use Faker\Generator as Faker;

$factory->define(Bitfumes\Multiauth\Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
