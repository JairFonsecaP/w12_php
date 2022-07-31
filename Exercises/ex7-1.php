<?php
$provinces = [
    ['id' => 0, 'code' => 'QC', 'name' => 'Québec'],
    ['id' => 1, 'code' => 'ON', 'name' => 'Ontario'],
    ['id' => 2, 'code' => 'NB', 'name' => 'New-Brunswick'],
    ['id' => 4, 'code' => 'NS', 'name' => 'Nova-Scotia'],
    ['id' => 5, 'code' => 'MN', 'name' => 'Manitoba'],
    ['id' => 6, 'code' => 'SK', 'name' => 'Saskatchewan'],
];
?>
<!DOCTYPE html>
<html lang="en-CA">

<head>
    <meta charset="UTF-8">
    <title>Exercise 7-1</title>
</head>

<body>
    <select name="provinces">
        <?php
        foreach ($provinces as $province) {
            echo '<option value="' . $province['code'] . '"> ' . $province['name'] . '</option>';
        }
        ?>
    </select>
</body>

</html>