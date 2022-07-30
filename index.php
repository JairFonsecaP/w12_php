<!DOCTYPE html>
<html lang="en-CA">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
</head>

<body>
    <h1>First page</h1>
    <?php
    // $name = 'Jair'; // New variable
    // Reads input value
    if (isset($_REQUEST['name'])) {
        $name = $_REQUEST['name'];
        echo 'My name is ' . $name; // Prints in the screen
    } else {
        echo 'No name input recived';
    }
    ?>
    <br>
    <h1>First PHP file</h1>
    <?php
    //echo function outputs text
    echo "Hello World !";
    ?>

    <p>Today's date and time: <?php echo date('d/m/Y h:i:s A'); ?></p>
    <p>
        <?php
        //show this text bold with the <b> tag
        echo "<b>This text is in a b tag for bold</b>";
        ?>
    </p>

    <p>
        <?php echo "This text contains a quotation mark \"." ?>
    </p>

</body>

</html>
<?php
phpinfo();
for ($i = 0; $i < $_REQUEST['number']; $i++) {
    echo '<b>' . $i . '</b>' . '</br>';
}

/**
 * Shows the input name on screen
 *
 * @param string input from the user
 * @return 34 integer
 */
function allo($name)
{
    echo 'Allo ' . $name;
    return 34;
}

allo('jair');
