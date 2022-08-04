<?php

function getLetterGrade(float $grade): string
{
    if ($grade <= 100 && $grade >= 90) {
        return "A";
    } elseif ($grade < 90 && $grade >= 80) {
        return "B";
    } elseif ($grade < 80 && $grade >= 70) {
        return "C";
    } elseif ($grade < 70 && $grade >= 60) {
        return "D";
    } elseif ($grade < 60) {
        return "F";
    }
}


const normalCost = 0.05;
const extraCost = 0.03;
function getPicturesCost(int $nbPics): float
{
    $value = 0;

    if ($nbPics > 100) {
        $temp = normalCost * 100;
        $value += $temp;
        $temp = ($nbPics - 100) * extraCost;
        $value += $temp;
    } else {
        $temp = normalCost * $nbPics;
        $value += $temp;
    }

    return $value;
}



const RATE_PER_DAY = 0.50;
const PRICE_KWH_NORMAL = 0.30;
const PRICE_KWH_PREFERENTIAL = 0.20;
function getElectricityTotal(float $nbDays, float $nbKWh): float
{
    $total = $nbDays * RATE_PER_DAY;

    if ($nbKWh > 200) {
        $total += PRICE_KWH_NORMAL * 200;
        $total += PRICE_KWH_PREFERENTIAL * ($nbKWh - 200);
    } else {
        $total += PRICE_KWH_NORMAL * $nbKWh;
    }

    return $total;
}


function getMax(int $n1, int $n2, int $n3): int
{
    if ($n1 > $n2 && $n1 > $n3) {
        return $n1;
    } elseif ($n2 > $n1 && $n2 > $n3) {
        return $n2;
    }
    return $n3;
}



function sortAsc(int $n1, int $n2, int $n3)
{
    $numbers = [$n1, $n2, $n3];
    sort($numbers);
    echo "$numbers[0], $numbers[1], $numbers[2] </br>";
}

// Test exercise 6.2.1

// using function getLetterGrade()
$letterGrade = getLetterGrade(70); // 70%
echo $letterGrade . '</br>';  // must print C
$letterGrade = getLetterGrade(90); // 90%
echo $letterGrade . '</br>';  // must print A
echo '</br>';


// Test exercise 6.2.2

//using function getPicturesCost()
$cost = getPicturesCost(70); // 70 downloads
echo $cost . '</br>';  // must print 3.5

$cost = getPicturesCost(130); // 130 downloads
echo $cost . '</br>';  // must print 5.90
echo '</br>';


// Test exercise 6.2.3

// using function getElectricityTotal()
$elecCost = getElectricityTotal(60, 180); // 60 days and 180 kWh
echo $elecCost . '</br>';  // must print 84
$elecCost = getElectricityTotal(60, 350); // 60 days and 350 kWh
echo $elecCost . '</br>';  // must print 120
echo '</br>';

// Test 6.2.4

// using function getMax()
echo (getMax(8, 52, 34) . '</br>'); // must print 52
echo (getMax(108, 52, 34) . '</br>'); // must print 108
echo (getMax(8, 52, 65) . '</br>'); // must print 65
echo '</br>';


// Test 6.2.5
// using function
sortAsc(34, 356, 999); // all show on console 34, 356, 999
sortAsc(356, 34, 999);
sortAsc(999, 356, 34);
sortAsc(34, 999, 356);
