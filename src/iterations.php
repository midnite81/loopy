<?php

namespace Midnite81\Loopy;

use Closure;

/**
 * Run the closure on each iteration of the array
 *
 * @param iterable $items
 * @param Closure  $callback
 */
function each(iterable $items, Closure $callback): void
{
    foreach ($items as $key => $item) {
        if ($callback($item, $key) === false) {
            break;
        }
    }
}

/**
 * Checks to see the result of the closure is true for each iteration of the iterable passed
 *
 * @param iterable $items
 * @param Closure  $callback
 *
 * @return bool
 */
function all(iterable $items, Closure $callback): bool
{
    foreach ($items as $key => $item) {
        if ($callback($item, $key) === false) {
            return false;
        }
    }

    return true;
}

/**
 * Checks to see the result of the closure is true for one or more iteration of the iterable passed
 *
 * @param iterable $items
 * @param Closure  $callback
 *
 * @return bool
 */
function some(iterable $items, Closure $callback): bool
{
    foreach ($items as $key => $item) {
        if ($callback($item, $key) === true) {
            return true;
        }
    }

    return false;
}

/**
 * Creates a new array based on the value of the callback
 *
 * @param iterable $items
 * @param Closure  $callback
 *
 * @return array
 */
function map(iterable $items, Closure $callback): array
{
    $response = [];

    foreach ($items as $key => $item) {
        $response[] = $callback($item, $key);
    }

    return $response;
}

/**
 * Reduces down the iterable to a single string, int or float value.
 *
 * @param iterable         $items
 * @param Closure          $callback Closure provides ($item, $key, $current)
 * @param string|int|float $initial
 *
 * @return string|int|float
 */
function reduce(iterable $items, Closure $callback, string|int|float $initial = ""): string|int|float
{
    $current = $initial;
    $total = iterator_count(new \ArrayIterator($items));

    foreach ($items as $key => $item) {
        $current = $callback($current, $item, $key, $total);
    }

    return $current;
}

/**
 * @param iterable $items
 * @param Closure  $callback
 * @param bool     $preserveKey
 *
 * @return array
 */
function filter(iterable $items, Closure $callback, bool $preserveKey = false): array
{
    $array = [];

    foreach($items as $key => $item) {
        if ($callback($item, $key) === true) {
            if ($preserveKey) {
                $array[$key] = $item;
            } else {
                $array[] = $item;
            }
        }
    }

    return $array;
}

/**
 * Instance of callback should only be found in the array the specified number of times
 *
 * @param iterable $items
 * @param Closure  $callback
 * @param int      $times
 *
 * @return bool
 */
function times(iterable $items, Closure $callback, int $times): bool
{
    $count = 0;

    foreach ($items as $key => $item) {
        if ($callback( $item, $key)) {
            $count++;
        };
    }

    return $count === $times;
}

/**
 * Instance of callback should only be found in the array once
 *
 * @param iterable $items
 * @param Closure  $callback
 *
 * @return bool
 */
function once(iterable $items, Closure $callback): bool
{
    return times($items, $callback, 1);
}