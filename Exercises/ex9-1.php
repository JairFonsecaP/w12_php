<?php
//Exercise 9-1 Date and Time functions
// reference https://www.php.net/manual/fr/function.date.php
// date formating https://www.php.net/manual/fr/datetime.format.php


function showTitle(string $title)
{
    echo "<h2>&#9830; $title</h2>";
    echo '<hr/>';
}

showTitle('timezone specified in php.ini');
echo 'timezone:' . date_default_timezone_get();

showTitle('current timestamp, seconds since january 1st, 1970');
$t = time();
print_r($t);

showTitle('Create a timestamp for a given date 20h:25min:10s on 10 january 2019');
$t = mktime(20, 25, 10, 01, 10, 2019);
echo date($t) . "</br>";

//2nd method with strotime()
$t = strtotime("10 January 2019 20:25:10");
echo $t;

showTitle('Exercise 1 full date and time in RFC2822 format');
echo date(DATE_RFC2822, $t);

showTitle('Exercise 2 Day only');
echo date("d", $t);

showTitle('Exercise 3 The Month only');
echo date("n", $t) . '</br>';
echo date("F", $t);

showTitle('Exercise 4 The Year only');
echo date("Y", $t);

showTitle('Exercise 5 Date displayed like 10,january,2019');
echo date("d,F,Y", $t);

showTitle('Exercise 6 Add 1 month and full display');
$t = strtotime("10 January 2019 20:25:10" . '+1 month');
echo date(DATE_RFC2822, $t);

showTitle('Exercice 7 Number of days since 31 décembre 1973');
$t = new DateTime('2019-01-10');
$t2 = new DateTime('1973-12-31');
echo ($t->diff($t2))->days;


showTitle('Exercice 8 Date displayed like Thurday, 10 january 2019');
echo date('D, d F Y', strtotime("10 January 2019 20:25:10"));
