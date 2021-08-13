# Loopy!
[![Latest Stable Version](https://poser.pugx.org/midnite81/loopy/version)](https://packagist.org/packages/midnite81/loopy) [![Total Downloads](https://poser.pugx.org/midnite81/loopy/downloads)](https://packagist.org/packages/midnite81/loopy) [![Latest Unstable Version](https://poser.pugx.org/midnite81/loopy/v/unstable)](https://packagist.org/packages/midnite81/loopy) [![License](https://poser.pugx.org/midnite81/loopy/license.svg)](https://packagist.org/packages/midnite81/loopy) [![Build](https://travis-ci.org/midnite81/loopy.svg?branch=master)](https://travis-ci.org/midnite81/loopy) [![Coverage Status](https://coveralls.io/repos/github/midnite81/loopy/badge.svg?branch=master)](https://coveralls.io/github/midnite81/loopy?branch=master)

This is a PHP package which adds some namespaced array functions. Some of these functions
can be natively accessed using array_* however there are few which aren't natively 
available. 

## Installation

This package is available for PHP7.4+. To install use composer

```
composer require midnite81\loopy
```

## Available Functions

- [each](#each)
- [all](#all) 
- [some](#some)
- [map](#map)
- [reduce](#reduce)
- [filter](#filter)
- [times](#times)
- [once](#once)

## Definitions

### each
`each(iterable $items, Closure $callback): void`

This function loops over each item. If the result of the closure returns false, then it 
will break the iteration at the point it returns false.

_Example_
```php
use function Midnite81\Loopy\each;

$colours = [
    'blue',
    'red',
    'green',
    'yellow'
];

each($colours, function($colour, $key) {
    echo $colour . " is at index " . $key . "\n";
});
```
_Result_
```html
blue is at index 0
red is at index 1
green is at index 2
yellow is at index 3
```


### all
`all(iterable $items, Closure $callback): bool`

This function checks to see if the result of the closure ($callback) is true for all 
iterations of the iterable passed ($items)

_Example_
```php
use function Midnite81\Loopy\all;

$employees = [
    "id395" => ["name" => 'bob', "age" => 42, "dept" => 2],
    "id492" => ["name" => 'dave', "age" => 34, "dept" => 2],
    "id059" => ["name" => 'susan', "age" => 23, "dept" => 2],
];

$allBobs = all($employees, fn($employee) => $employee['name'] === 'bob');
// please note the key is also passed to the closure; therefore if the key is necessary
// to your function you could for example do the following 
// $allBobs = all($employees, fn($employee, $key) => $employee['name'] === 'bob' && $key != 'id000');

```
_Result_
```php
$allBobs = false;
// thankfully, not everyone in the department is called bob
```

### some
`some(iterable $items, Closure $callback): bool`

This function checks to see if the result of the closure ($callback) is true for one or 
more iterations of the iterable passed ($items)

_Example_
```php
use function Midnite81\Loopy\some;

$employees = [
    "id395" => ["name" => 'bob', "age" => 42, "dept" => 2],
    "id492" => ["name" => 'dave', "age" => 34, "dept" => 2],
    "id059" => ["name" => 'susan', "age" => 23, "dept" => 2],
];

$allBobs = some($employees, fn($employee) => $employee['name'] === 'bob');

// please note the key is also passed to the closure; therefore if the key is necessary
// to your function you could for example do the following 
// $allBobs = some($employees, fn($employee, $key) => $employee['name'] === 'bob' && $key != 'id000');

```
_Result_
```php
$allBobs = true;
// one or more people in the department are called bob
```

### map
`map(iterable $items, Closure $callback): array`


_Example_
```php
use function Midnite81\Loopy\map;

$employees = [
    "id395" => ["name" => 'bob', "age" => 42, "dept" => 2],
    "id492" => ["name" => 'dave', "age" => 34, "dept" => 2],
    "id059" => ["name" => 'susan', "age" => 23, "dept" => 2],
];

$allBobs = map($employees, fn($employee, $key) => $employee['name']');
```

_Result_
```php
$allBobs = [
    'bob',
    'dave',
    'susan',
]
```

### reduce
```reduce(iterable $items, Closure $callback, string|int|float $initial = ""): string|int|float```

This function reduces down the values of an array to a single string, integer or float. 

_Example_
```php
use function Midnite81\Loopy\reduce;

$moneyReceived = [
    20.00,
    3.92,
    3.01,
    27.00
];

$totalMoneyReceived = reduce($moneyReceived, fn($current, $value, $key) => (float)$current + $value);
// $current is the current value of the reducer
```

_Result_
```php
$totalMoneyReceived = 53.93;
```

### filter
`filter(iterable $items, Closure $callback, bool $preserveKey = false): array`

This function filters down the iterable ($items) passed by only including what is true
in the Closure ($callback). By default, the key is not preserved, but you can set it to 
true, if you wish to preserve the key.

_Example_
```php
use function Midnite81\Loopy\filter;

$users = [
            ["name" => 'dave'],
            ["name" => 'susan'],
            ["name" => 'ingrid'],
            ["name" => 'patricia'],
            ["name" => 'sally'],
        ];

$usersWhoseNamesDontStartWithS = filter($users, fn($user) => !str_starts_with($user['name'], "s"));

```

_Result_
```php
$usersWhoseNamesDontStartWithS = [
    'dave',
    'ingrid',
    'patricia'
]
```

### times
`times(iterable $items, Closure $callback, int $times): bool`

This function checks to see that the instance of the call back ($callback) should only be 
found the specified number of times in the iterable ($items)

_Example_
```php
use function Midnite81\Loopy\times;

$peopleOnTheBus = [
    'andy',
    'bob',
    'sally',
    'wendy',
    'bob'
];

$areThereTwoBobsOnTheBus = times($peopleOnTheBus, fn($people) => $people === 'bob', 2);
```

_Result_
```php
$areThereTwoBobsOnTheBus = true;
```

### once
`once(iterable $items, Closure $callback): bool`

Once is exactly the same as `times` however it will ensure the result of the closure only
appears once in the iterable passed;

_Example_
```php
use function Midnite81\Loopy\once;

$peopleOnTheBus = [
    'andy',
    'bob',
    'sally',
    'wendy'
];

$isThereJustOneAndyOnTheBus = once($peopleOnTheBus, fn($people) => $people === 'andy');
```

_Result_
```php
$isThereJustOneAndyOnTheBus = true;
```


