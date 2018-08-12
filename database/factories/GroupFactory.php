<?php

use Faker\Generator as Faker;

$factory->define(App\Group::class, function (Faker $faker) {
    return [
        'name'        => $faker->company,
        'description' => $faker->sentence,
        'url'         => $faker->url,
        'private'     => false,
        'user_id'     => function ($group) {
            return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->state(App\Group::class, 'private', function ($faker) {
    return [
        'private' => true
    ];
});


$factory->define(App\GroupInvite::class, function (Faker $faker) {
    return [
        'group_id' => function () {
            return factory(App\Group::class)->create()->id;
        },
        'sender_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'email' => $faker->email
    ];
});
