<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use function Midnite81\Loopy\all;
use function Midnite81\Loopy\filter;
use function Midnite81\Loopy\map;
use function Midnite81\Loopy\once;
use function Midnite81\Loopy\reduce;
use function Midnite81\Loopy\some;
use function Midnite81\Loopy\each;
use function Midnite81\Loopy\times;

class IterationsTest extends TestCase
{
    /** @test */
    public function it_does_not_contain_all()
    {
        $users = [
            ["name" => 'dave', "age" => 34, "dept" => 2],
            ["name" => 'susan', "age" => 23, "dept" => 2],
        ];

        $sut = all($users, fn($user) => $user['name'] === 'dave');

        expect($sut)
            ->toBeBool()
            ->toBeFalse();
    }

    /** @test */
    public function it_does_contain_all()
    {
        $users = [
            ["name" => 'dave', "age" => 34, "dept" => 2],
            ["name" => 'susan', "age" => 23, "dept" => 2],
        ];

        $sut = all($users, fn($user) => $user['dept'] === 2);

        expect($sut)
            ->toBeBool()
            ->toBeTrue();
    }

    /** @test */
    public function it_does_not_contain_some()
    {
        $users = [
            ["name" => 'dave', "age" => 34, "dept" => 2],
            ["name" => 'susan', "age" => 23, "dept" => 2],
        ];

        $sut = some($users, fn($user) => $user['name'] === 'pete');

        expect($sut)
            ->toBeBool()
            ->toBeFalse();
    }

    /** @test */
    public function it_does_contain_some()
    {
        $users = [
            ["name" => 'dave', "age" => 34, "dept" => 2],
            ["name" => 'susan', "age" => 23, "dept" => 2],
        ];

        $sut = some($users, fn($user) => $user['name'] === 'dave');

        expect($sut)
            ->toBeBool()
            ->toBeTrue();
    }

    /** @test */
    public function it_iterates()
    {
        $colours = [
            'blue',
            'red',
            'green',
            'yellow'
        ];

        $newColours = [];

        each($colours, function($colour) use (&$newColours) {
            $newColours[] = $colour . "_new";
        });

        expect($newColours)
            ->toBeArray()
            ->toHaveCount(4)
            ->sequence(
                fn ($item) => $item->toEqual('blue_new'),
                fn($item) => $item->toEqual('red_new'),
                fn($item) => $item->toEqual('green_new'),
                fn($item) => $item->toEqual('yellow_new'),
            );
    }

    /** @test */
    public function it_maps_to_new_array()
    {
        $users = [
            ["name" => 'dave', "age" => 34, "dept" => 2],
            ["name" => 'susan', "age" => 23, "dept" => 2],
        ];

        $sut = map($users, fn($user) => $user['name']);

        expect($sut)
            ->toBeArray()
            ->toHaveCount(2)
            ->sequence(
                fn ($item) => $item->toEqual('dave'),
                fn($item) => $item->toEqual('susan')
            );
    }

    /** @test */
    public function it_maps_from_object_to_new_array()
    {
        $users = [
            (object)["name" => 'dave', "age" => 34, "dept" => 2],
            (object)["name" => 'susan', "age" => 23, "dept" => 2],
        ];

        $sut = map($users, fn($user) => $user->name);

        expect($sut)
            ->toBeArray()
            ->toHaveCount(2)
            ->sequence(
                fn ($item) => $item->toEqual('dave'),
                fn($item) => $item->toEqual('susan')
            );
    }

    /** @test */
    public function it_reduces_integers()
    {
        $users = [
            (object)["name" => 'dave', "age" => 34, "dept" => 2],
            (object)["name" => 'susan', "age" => 23, "dept" => 2],
        ];

        $sut = reduce($users, fn($current, $item, $key) => (int)$current + $item->age);

        expect($sut)
            ->toBeInt()
            ->toBe(57);
    }

    /** @test */
    public function it_reduces_strings()
    {
        $users = [
            (object)["name" => 'dave', "age" => 34, "dept" => 2],
            (object)["name" => 'susan', "age" => 23, "dept" => 2],
        ];

       $sut = reduce($users, fn($current, $item, $key) => $current . substr($item->name, 0, 1));

       expect($sut)
           ->toBeString()
           ->toBe("ds");
    }

    /** @test */
    public function it_reduces_with_current()
    {
        $array = [15, 120, 45, 78];

        $sut = reduce($array, fn($current, $item, $key) => $current . " and " . $item, 'Initial');

        expect($sut)
            ->toBeString()
            ->toBe('Initial and 15 and 120 and 45 and 78');
    }

    /** @test */
    public function it_filters_an_array()
    {
        $users = [
            ["name" => 'dave'],
            ["name" => 'susan'],
            ["name" => 'ingrid'],
            ["name" => 'patricia'],
            ["name" => 'sally'],
        ];

        $sut = filter($users, fn($user) => !str_starts_with($user['name'], "s"));

        expect($sut)
            ->toBeArray()
            ->toHaveCount(3)
            ->sequence(
                fn($item) => $item->name->toEqual('dave'),
                fn($item) => $item->name->toEqual('ingrid'),
                fn($item) => $item->name->toEqual('patricia'),
            )
            ->toHaveKeys([
                0,
                1,
                2
            ]);
    }

    /** @test */
    public function it_filters_an_array_and_preserves_keys()
    {
        $users = [
            "id_dave" => ["name" => 'dave'],
            "id_susan" => ["name" => 'susan'],
            "id_ingrid" => ["name" => 'ingrid'],
            "id_patricia" => ["name" => 'patricia'],
            "id_sally" => ["name" => 'sally'],
        ];

        $sut = filter($users, fn($user) => !str_starts_with($user['name'], "s"), true);

        expect($sut)
            ->toHaveKeys([
               'id_dave',
               'id_ingrid',
               'id_patricia'
            ]);
    }

    /** @test */
    public function it_checks_only_one_instance_is_found()
    {
        $array = [
            2,
            432,
            243,
            245
        ];

        $sut = times($array, fn($item) => $item === 2, 1);

        expect($sut)
            ->toBeBool()
            ->toBeTrue();
    }

    /** @test */
    public function it_fails_when_looking_for_once_instance_but_there_are_two()
    {
        $array = [
            2,
            432,
            2,
            245
        ];

        $sut = times($array, fn($item) => $item === 2, 1);

        expect($sut)
            ->toBeBool()
            ->toBeFalse();
    }

    /** @test */
    public function it_fails_when_it_cannot_find_the_closure_six_times()
    {
        $array = [
            2,
            432,
            243,
            245
        ];

        $sut = times($array, fn($item) => $item === 2, 6);

        expect($sut)
            ->toBeBool()
            ->toBeFalse();
    }

    /** @test */
    public function it_checks_only_one_instance_is_found_on_once_alias()
    {
        $array = [
            2,
            432,
            243,
            245
        ];

        $sut = once($array, fn($item) => $item === 2);

        expect($sut)
            ->toBeBool()
            ->toBeTrue();
    }
}