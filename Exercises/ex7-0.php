<?php
$products = [
    [
        'id' => 'td1234',
        'name' => 'lawn mower',
        'price' => 199.99,
        'weight' => 50,
    ],
    [
        'id' => 'ra9xfg',
        'name' => 'rake',
        'price' => 19.99,
        'weight' => 5,
    ],
    [
        'id' => 'pe4532',
        'name' => 'shovel',
        'price' => 19.99,
        'weight' => 5,
    ],
]; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        td,
        th {
            border: black 1px solid;
            padding: 5px;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <?php
                foreach ($products[0] as $key => $value) {
                    $key = ucwords($key);
                    echo "<th>$key</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($products as $product) {
                echo "<tr>";
                foreach ($product as $value) {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }
            ?>

        </tbody>
    </table>
</body>

</html>