<?php
/* EXERCISE 3-1A and 3-1B */
/* GLOBAL CONSTANTS */
const COMPANY_LOGO = 'web-site-icon.jpg';
const COMPANY_NAME = 'ClassicModels.com';
const COMPANY_STREET_ADDRESS = '5340 St-Laurent';
const COMPANY_CITY = 'Montréal';
const COMPANY_PROVINCE = 'QC';
const COMPANY_COUNTRY = 'Canada';
const COMPANY_POSTAL_CODE = 'J0P 1T0';
const PHONE_NUMBER = '+1 (514)-345-6789';
const EMAIL = 'info@classicmodels.com';
/* web page variable properties */
$lang = 'en-CA';
$title = 'ClassicModels.com - Home Page';
$description = 'Scale Models of Classic Cars, Trucks, Planes, Motorcyles and more';
$author = 'Stéphane Lapointe';
$content = 'bla bla bla bla bla this is the page content';
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
    <style>
        header {
            background-color: black;
            color: white;
            padding: 10px;
        }

        nav {
            background-color: grey;
            color: white;
            padding: 10px;
        }

        footer {
            background-color: black;
            color: white;
            padding: 10px
        }
    </style>
</head>

<body>

    <!-- PAGE HEADER -->
    <header>
        <h2>
            <img src="<?= COMPANY_LOGO; ?>" alt="" />
            ClassicModels.com
        </h2>
    </header>

    <!-- NAVIGATION BAR-->
    <nav>
        <a href='ex3-1.php'>Home</a>
    </nav>

    <!-- CONTENT -->
    <?= $content; ?>

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