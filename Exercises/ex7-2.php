<?php
// Exercise 7-2
/* Months of the year, array of 12 items.
 the key is the month name, the value is a color name
*/
$months_colors = [
    'January' => 'blue',
    'February' => 'white',
    'March' => 'Red',
    'April' => 'Yellow',
    'May' => 'Grey',
    'June' => 'Lime',
    'July' => 'lightblue',
    'August' => 'fuchsia',
    'September' => 'lightgrey',
    'October' => 'olive',
    'November' => 'pink',
    'December' => 'purple',
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Months table</title>
    <style>
        .container {
            width: 80%;
            border: 1px solid brown;
            margin: 10px;
            padding: 20px;
        }
    </style>
</head>

<body>

    <h1>Exercise 7-2 Months table</h1>
    <main>
        <div class="container">
            <!--First table -->
            <table>
                <?php
                foreach ($months_colors as $month => $color) {
                    $style = 'style="background-color:' . $color . '"';
                    echo "<tr><td $style>$month</td></tr>";
                }
                ?>
            </table>
        </div>
        <div class="container">
            <!--Second table -->
            <table>
                <?php
                echo '<tr>';
                foreach ($months_colors as $month => $color) {
                    $style = 'style="background-color:' . $color . '"';
                    echo "<th $style>$month</th>";
                }
                echo '</tr>';
                ?>
            </table>
        </div>
    </main>

</body>

</html>