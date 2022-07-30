<?php
function printFiveNumbers()
{
    echo "While loop </br>";
    $i = 0;
    while ($i <= 5) {
        echo ">$i </br>";
        $i++;
    }

    echo "</br>Do while loop </br>";
    $i = 0;
    do {
        echo ">$i </br>";
        $i++;
    } while ($i <= 5);

    echo "</br>For loop </br>";
    for ($i = 0; $i <= 5; $i++) {
        echo ">$i </br>";
    }
}

function displayBetween(int $n1, int $n2)
{
    echo '</br>Display Between function</br>';
    if ($n1 < $n2) {
        while ($n1 <= $n2) {
            echo ">$n1 </br>";
            $n1++;
        }
    } else {
        while ($n1 >= $n2) {
            echo ">$n1 </br>";
            $n1--;
        }
    }
}

function sumBetween(int $n1, int $n2): int
{
    echo '</br>Sum bewtween function</br>';
    $total = 0;
    while ($n1 <= $n2) {
        $total += $n1;
        $n1++;
    }
    return $total;
}

printFiveNumbers();
// Test function displayBetween(n1,n2)
displayBetween(5, 10);
displayBetween(10, 5);

// Test Exercise sumBetween()

$total = sumBetween(5, 10);
echo ($total); // must print 45
$total = sumBetween(8, 12);
echo ($total); // must print 50
