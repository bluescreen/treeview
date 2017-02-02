<?php

use ITaikai\Competition;
use ITaikai\Competitor;
use ITaikai\Group;
use ITaikai\Match;
use ITaikai\Referee;
use ITaikai\Team;

$factory(Competition::class, function () use ($faker) {

    return [
        'name'       => $faker->sentence,
        'date'       => $faker->dateTime,
        'group_size' => 3,
        'type'       => 'individual',
        'mode'       => 'elimination',
        'location'   => $faker->city
    ];
});

$factory(Competitor::class, function () use ($faker) {
    $firstName = $faker->firstName;
    $lastName  = $faker->lastName;

    return [
        'name'       => $lastName,
        'first_name' => $firstName,
        'alias'      => '',
        'birth_date' => $faker->dateTime,
        'pass_nr'    => microtime(),
        'grading_id' => rand(1, 8),
    ];
});

$factory(Referee::class, function () use ($faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name'  => $faker->lastName,
        'alias'      => '',
        'grading_id' => rand(1, 8)
    ];
});

$factory(Team::class, function () use ($faker) {
    return [
        'name'          => "Team " . uniqid(),
        'tournament_id' => 1
    ];
});

$factory(Group::class, function () use ($faker) {
    return [
        'name'          => "Group " . uniqid(),
        'tournament_id' => 1
    ];
});

$factory(Match::class, function () use ($faker) {
    return [
        'title'          => "TestMatch",
        'red_id'        => 1,
        'white_id'      => 2,
        'tournament_id' => 1
    ];
});
