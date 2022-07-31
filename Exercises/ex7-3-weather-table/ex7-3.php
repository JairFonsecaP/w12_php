<?php
//Exercise 7-3
const IMG_PATH = 'weather_images/';

$forecasts = [
    '2018-02-11' => [
        'image_file' => 'degagement.gif',
        'image_desc' => 'clearing skies',
        'temperature' => '-17ºC',
    ],
    '2018-02-12' => [
        'image_file' => 'soleil_nuage.gif',
        'image_desc' => 'sunny with clouds',
        'temperature' => '-11ºC',
    ],
    '2018-02-13' => [
        'image_file' => 'neige.gif',
        'image_desc' => 'snow',
        'temperature' => '-12ºC',
    ],
    '2018-02-14' => [
        'image_file' => 'soleil.gif',
        'image_desc' => 'sunny',
        'temperature' => '-15ºC',
    ],
    '2018-02-15' => [
        'image_file' => 'neige.gif',
        'image_desc' => 'snow',
        'temperature' => '-11ºC',
    ],
    '2018-02-16' => [
        'image_file' => 'soleil.gif',
        'image_desc' => 'sunny',
        'temperature' => '-15ºC',
    ],
];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Exercise 7-3 Weather forecast</title>
    <style>
        body {
            width: 300px;
            margin: auto;
            border: 1px solid darkgrey;
            padding: 5px;
        }

        td {
            display: flex;
        }
    </style>

</head>

<body>
    <div>
        <header>
            <h1>Weather forecast</h1>
        </header>
        <main>
            <table>

                <?php
                foreach ($forecasts as $key => $value) {
                    $img = '<img src="weather_images/' . $value['image_file'] . '" alt="' . $value['image_desc'] . '"/>';
                    $temperature = $value['temperature'];
                    $td = '<td><p>' . $key . '</p>' . $img . '<p>' . $temperature . '</p></td>';
                    echo '<tr>' . $td . '</tr>';
                }
                ?>
            </table>

        </main>
    </div>
</body>

</html>