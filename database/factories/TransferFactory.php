<?php

use App\Models\Transfer;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Transfer::class, function (Faker $faker) {
    return [
        'sender_id' => function () {
            return factory(User::class)->create()->id;
        },
        'recipient_id' => function () {
            return factory(User::class)->create()->id;
        },
        'time' => $faker->dateTime,
        'sum' => $faker->randomDigitNotNull * 10,
        'completed' => $faker->randomKey([0, 1]),
    ];
});

$factory->state(Transfer::class, 'unprocessed', [
    'completed' => 0,
    'with_error' => 0,
]);

$factory->state(Transfer::class, 'completed', [
    'completed' => 1,
    'with_error' => 0,
]);

$factory->state(Transfer::class, 'failed', [
    'completed' => 0,
    'with_error' => 1,
]);
