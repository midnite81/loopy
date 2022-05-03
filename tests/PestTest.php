<?php

use function Midnite81\Loopy\all;

it('does not contain all', function() {
    $users = [
        ["name" => 'dave', "age" => 34, "dept" => 2],
        ["name" => 'susan', "age" => 23, "dept" => 2],
    ];

    $sut = all($users, fn($user) => $user['name'] === 'dave');

    expect($sut)
        ->toBeBool()
        ->toBeFalse();
});

test('does contain all', function() {
    $users = [
        ["name" => 'dave', "age" => 34, "dept" => 2],
        ["name" => 'susan', "age" => 23, "dept" => 2],
    ];

    $sut = all($users, fn($user) => $user['dept'] === 2);

    expect($sut)
        ->toBeBool()
        ->toBeTrue();
});