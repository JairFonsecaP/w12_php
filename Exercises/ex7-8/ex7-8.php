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
const PATH = 'products_images';
const VISITOR_LOG_FILE =  'visitors.log';
const LOG_DIRECTORY = 'log';
/* web page variable properties */
$lang = 'en-CA';
$title = 'ManchesterUnitedCanada.com';
$description = 'Scale Models of Classic Cars, Trucks, Planes, Motorcyles and more';
$author = 'Stéphane Lapointe';
$content = 'Invalid operation';
$counter = 0;

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
    global $title;
    global $content;

    $title = 'Product List - ' . COMPANY_NAME;

    $output = '<h2>Product list</h2> <table><thead><tr>';
    foreach ($products[0] as $key => $_) {
        $output .= "<th>$key</th>";
    }
    $output .= '</tr></thead><tbody>';
    foreach ($products as $product) {
        $output .= "<tr><td>" . $product['id'] . "</td><td>" . $product['name'] . "</td><td>";
        $output .= $product['description'] . "</td><td>" . $product['price'] . "</td><td>" . $product['pic'];
        $output .= "</td><td>" . $product['qty_in_stock'] . '</td>';
    }
    $output .= ' </tbody></table>';
    $content = $output;
}
function productsCataloge()
{
    global $products;
    global $title;
    global $content;

    $title = 'Product Catalog - ' . COMPANY_NAME;

    $output = '';
    foreach ($products as $product) {
        if ($product['price'] <= 0) {
            continue;
        }
        $output .= '<div class="product"><img src="' . PATH . '/' . $product['pic'] . '" alt="' . $product['description'] . '" title="' . $product['description'] . '">';
        $output .= '<p class="name ">' . $product['name'] . '</p><p class="description">' . $product['description'] . '</p><p class="price">' . $product['price'] . '</p>';
        $output .= '</div>';
    }
    $content = $output;
}




function counterFile()
{
    global $counter;
    $file_path = getcwd() . DIRECTORY_SEPARATOR . LOG_DIRECTORY . DIRECTORY_SEPARATOR;

    if (!file_exists(LOG_DIRECTORY)) {
        mkdir(LOG_DIRECTORY);
    }
    if ($_REQUEST['op'] == 1) {
        $file_path .= 'log_visit_list_products.txt';
        if (!file_exists($file_path)) {
            file_put_contents($file_path, $counter);
        }

        $counter = file_get_contents($file_path);
    } else if ($_REQUEST['op'] == 2) {
        $file_path .= 'log_visit_catalog_products.txt';
        if (!file_exists($file_path)) {
            file_put_contents($file_path, $counter);
        }
        $counter = file_get_contents($file_path);
    }
    $counter++;
    file_put_contents($file_path, $counter);
}

function logVisitor()
{
    $f = fopen(LOG_DIRECTORY . DIRECTORY_SEPARATOR . VISITOR_LOG_FILE, "a");
    fwrite($f, date(DATE_RFC2822) . PHP_EOL);
    fclose($f);
}

counterFile();
logVisitor();
if (!isset($_REQUEST['op'])) {
    $_REQUEST['op'] = 1;
}

if ($_REQUEST['op'] == 1) {
    productsList();
} else if ($_REQUEST['op'] == 2) {
    productsCataloge();
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
            <img style="width: 50px" src="<?= COMPANY_LOGO; ?>" alt="Manchester United Home Jersey, red, sponsored by Chevrolet" />
            ManchesterUnitedCanada.com
        </h2>
    </header>

    <!-- NAVIGATION BAR-->
    <nav>
        <a href='ex7-8.php?op=1'>Product List</a>
        |
        <a href='ex7-8.php?op=2'>Product Catalog</a>
    </nav>

    <!-- CONTENT -->
    <main>
        <?php
        echo $content;
        ?>
    </main>


    <!-- FOOTER -->
    <footer>
        Designed by <?= $author; ?> &copy;<br>
        <?= COMPANY_NAME; ?> </br>
        <?= COMPANY_STREET_ADDRESS . " " . COMPANY_CITY  . " " . COMPANY_PROVINCE  . " " . COMPANY_POSTAL_CODE; ?> </br>
        <?= PHONE_NUMBER . ' | ' . EMAIL; ?> </br>
        View: <?= $counter ?>

    </footer>
    </div>
</body>

</html>