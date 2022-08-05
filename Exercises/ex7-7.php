<?php
/* exercise 7-7 */
function showTitle($title)
{
    echo "<br/><b>&#9830; $title</b><br/>";
    echo '<hr/>';
}

$users = [
    [
        'id' => 2135,
        'first_name' => 'John',
        'last_name' => 'Doe',
    ],
    [
        'id' => 3245,
        'first_name' => 'Sally',
        'last_name' => 'Smith',
    ],
    [
        'id' => 5342,
        'first_name' => 'Jane',
        'last_name' => 'Jones',
    ],
    [
        'id' => 5623,
        'first_name' => 'Peter',
        'last_name' => 'Doe',
    ],
];

showTitle('Exercise 1: Display firstnames only without iterations using array_column() and implode()');
$first_names = array_column($users, 'first_name');
echo implode(", ", $first_names);

showTitle('Exercise 2: Display values that are different between $firstArray and $secondArray using array_diff()');
$firstArray = ['a' => 'car', 'b' => 'motorcycle', 'c' => 'plane'];
$secondArray = ['a' => 'car', 'f' => 'motorcycle'];
$result = array_diff($firstArray, $secondArray);
var_dump($result);

showTitle('Exercise 3: Reverse keys and values in $firstArray with array_flip()');
$result = array_flip($firstArray);
var_dump($result);
