<?php
session_start();

require_once 'globals.php';
require_once 'tools.php';
require_once 'db_pdo.php';

require_once 'view/webpage.php';
require_once 'products.php';
require_once 'users.php';
require_once 'customers.php';

function homePage()
{
    setcookie('home', time(), time() + (10 * 365 * 24 * 60 * 60));
    $pageData = DEFAULT_PAGE_DATA;
    $pageData['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $pageData['title'] = "Home - " . COMPANY_NAME;
    switch ($pageData['lang']) {
        case 'fr':
            $pageData['content'] = "<h1>Bienvenue! Ceci est la page d'eacceuel!</h1>";
            break;
        case 'es':
            $pageData['content'] = "<h1>Bienvenido! Esta es la pagina principal!</h1>";
            break;
        case 'en':
        default:
            $pageData['content'] = "<h1>Welcome! This is the home page</h1>";
    }
    $pageData['content'] .= '<div class="alert alert-dark ms-2 me-2" role="alert">';

    if (!isset($_COOKIE['home'])) {
        $pageData['content'] .= 'Welcome, this is your first visit';
    } else {
        $time = date("l, M Y H:i:s", $_COOKIE['home']);
        $pageData['content'] .= "Your last visit was " . $time;
    }
    $pageData['content'] .= '</div>';
    //$pageData['content'] = '<h2 style="color:red">Welcome to Manchester United Canada!</h2>';
    webpage::render($pageData);
}

function main()
{
    $op = ROUTES['default'];

    if (isset($_REQUEST['op'])) {
        $op = $_REQUEST['op'];
    }

    switch ($op) {
        case ROUTES['default']:
            homePage();
            break;
        case ROUTES['login']:
            users::login();
            break;
        case ROUTES['login-verify']:
            users::loginVerify();
            break;
        case ROUTES['register']:
            users::register();
            break;
        case ROUTES['register-verify']:
            users::registerVerify();
            break;
        case ROUTES['logout']:
            users::logout();
            break;
        case ROUTES['download-file']:
            //file download from server to client
            // set the file type, here a PDF file, see link below for other file types
            header('Content-type: application/pdf');
            // file name is some_file.pdf, the web browser asks to confirm the download
            header('Content-Disposition: attachment; filename=some_file.pdf');
            // send out the file, read and send directly with readfile() function
            readfile('some_file.pdf');
            break;
        case ROUTES['redirect-amazon']:
            //redirect to amazon.ca
            header('location: https://www.amazon.ca/ ');
            break;
        case ROUTES['product-list']:
            products::productsList();
            break;
        case ROUTES['product-cataloge']:
            products::productsCataloge();
            break;
        case ROUTES['products_json']:
            products::listJSON();
            break;
        case ROUTES['customers']:
            if (isset($_SESSION['email'])) {
                customers::list();
            } else {
                header('HTTP/1.0 401 Must login, you are not authorized');
                users::login('You are not authorized, please enter your credentials');
            }
            break;
        case ROUTES['customers_json']:
            if (isset($_SESSION['email'])) {
                customers::listJSON();
            } else {
                header('HTTP/1.0 401 Must login, you are not authorized');
                users::login('You are not authorized, please enter your credentials');
            }
            break;
        default: {
                header('HTTP/1.0 400 This my own message, invalid opration');
                $pageData = DEFAULT_PAGE_DATA;
                $pageData['title'] = "Invalid option";
                $pageData['description'] = "Invalid option";
                $pageData['content'] = '<b style="color:red"> Invalid opetarion</b>';
                webpage::render($pageData);
            }
    }
}


main();
