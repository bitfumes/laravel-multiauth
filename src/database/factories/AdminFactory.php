<?php

use Illuminate\Support\Str;
use Bitfumes\Multiauth\Model\Admin;

$factory->define(Admin::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => '$2y$04$sJbJqpv7TH5RrgTPq0raburfQ6g1XOQtgd59Dgz.VCGlr8f5gUvm6', //secret
        // 'password'       => 'secret', //secret
        'remember_token' => Str::random(10),
        'active'         => true,
    ];
});
