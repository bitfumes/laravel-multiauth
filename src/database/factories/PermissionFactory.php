<?php

use Faker\Generator as Faker;
use Bitfumes\Multiauth\Model\Permission;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
