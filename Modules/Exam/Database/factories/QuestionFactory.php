<?php

use Faker\Generator as Faker;

$factory->define(\Modules\Exam\Entities\Question::class, function (Faker $faker) {
    $now = now();

    return [
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
