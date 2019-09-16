<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Comment::class, function (Faker $faker) {
    return [
        'parent_id' => mt_rand(0, 100),
        'nickname'  => $faker->name,
        'head_pic'  => $faker->url,
        'content'   => $faker->text
    ];
});
