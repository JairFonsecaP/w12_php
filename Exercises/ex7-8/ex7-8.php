<?php
/* EXERCISE 3-1A and 3-1B */
/* GLOBAL CONSTANTS */
const COMPANY_LOGO = 'web-site-icon.jpg';
const COMPANY_NAME = 'ManchesterUnitedCanada.com';
const COMPANY_STREET_ADDRESS = '5340 St-Laurent';
const COMPANY_CITY = 'Montréal';
const COMPANY_PROVINCE = 'QC';
const COMPANY_COUNTRY = 'Canada';
const COMPANY_POSTAL_CODE = 'J0P 1T0';
const PHONE_NUMBER = '+1 (514)-345-6789';
const EMAIL = 'info@manchesterunitedcanada.com';
/* web page variable properties */
$lang = 'en-CA';
$title = 'ManchesterUnitedCanada.com - Home Page';
$description = 'Scale Models of Classic Cars, Trucks, Planes, Motorcyles and more';
$author = 'Stéphane Lapointe';

$products = [
    [
        'id' => 0,
        'name' => 'Red Jersey',
        'description' => 'Manchester United Home Jersey, red, sponsored by Chevrolet',
        'price' => 59.99,
        'pic' => 'red_jersey.jpg',
        'qty_in_stock' => 200,
    ],
    [
        'id' => 1,
        'name' => 'White Jersey',
        'description' => 'Manchester United Away Jersey, white, sponsored by Chevrolet',
        'price' => 49.99,
        'pic' => 'white_jersey.jpg',
        'qty_in_stock' => 133,
    ],
    [
        'id' => 2,
        'name' => 'Black Jersey',
        'description' => 'Manchester United Extra Jersey, black, sponsored by Chevrolet',
        'price' => 54.99,
        'pic' => 'black_jersey.jpg',
        'qty_in_stock' => 544,
    ],
    [
        'id' => 3,
        'name' => 'Blue Jacket',
        'description' => 'Blue Jacket for cold and raniy weather',
        'price' => 129.99,
        'pic' => 'blue_jacket.jpg',
        'qty_in_stock' => 14,
    ],
    [
        'id' => 4,
        'name' => 'Snapback Cap',
        'description' => 'Manchester United New Era Snapback Cap- Adult',
        'price' => 24.99,
        'pic' => 'cap.jpg',
        'qty_in_stock' => 655,
    ],
    [
        'id' => 5,
        'name' => 'Champion Flag',
        'description' => 'Manchester United Champions League Flag',
        'price' => 24.99,
        'pic' => 'champion_league_flag.jpg',
        'qty_in_stock' => 321,
    ],
];

function productsList()
{
    global $products;
    foreach ($products as $product) {
        $output = "<tr><td>" . $product['id'] . "</td><td>" . $product['name'] . "</td><td>";
        $output .= $product['description'] . "</td><td>" . $product['price'] . "</td><td>" . $product['pic'];
        $output .= "</td><td>" . $product['qty_in_stock'] . "</td><tr>";
        echo $output;
    }
}
?>

<!-- WEB PAGE TEMPLATE BELOW ========================== -->
<!DOCTYPE html>
<html lang="<?= $lang; ?>">

<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <meta name="description" content="XXXX">
    <meta name="author" content="XXXX">

    <!--IMPORTANT for responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= COMPANY_LOGO; ?>" type="image/jpeg">
    <link type="text/css" rel="stylesheet" href="./global.css">
</head>

<body>

    <!-- PAGE HEADER -->
    <header>
        <h2>
            <img src="<?= COMPANY_LOGO; ?>" alt="" />
            ManchesterUnitedCanada.com
        </h2>
    </header>

    <!-- NAVIGATION BAR-->
    <nav>
        <a href='ex7-8.php'>Product List</a>
        |
        <a href='ex7-8.php'>Product Catalog</a>
    </nav>

    <!-- CONTENT -->
    <main>
        <table>
            <thead>
                <tr>
                    <?php
                    foreach ($products[0] as $key => $_) {
                        echo "<th>$key</th>";
                    }

                    ?>
                </tr>
            </thead>
            <tbody>
                <?= productsList() ?>
            </tbody>
        </table>
    </main>


    <!-- FOOTER -->
    <footer>
        Designed by <?= $author; ?> &copy;<br>
        <?= COMPANY_NAME; ?> </br>
        <?= COMPANY_STREET_ADDRESS . " " . COMPANY_CITY  . " " . COMPANY_PROVINCE  . " " . COMPANY_POSTAL_CODE; ?> </br>
        <?= PHONE_NUMBER . ' | ' . EMAIL; ?>

    </footer>
    </div>
</body>

</html>