<?php
require_once 'globals.php';
require_once 'tools.php';
require_once 'view/webpage.php';
require_once 'products.php';
function main()
{
    $op = 0;
    logVisitor();
    if (isset($_REQUEST['op'])) {
        $op = $_REQUEST['op'];
    }

    switch ($op) {
        case 0:
            $pageData = DEFAULT_PAGE_DATA;
            $pageData['title'] = "Home - " . COMPANY_NAME;
            $pageData['content'] = '<h2 style="color:red">Welcome to Manchester United Canada!</h2>';
            webpage::render($pageData);
            break;
        case 100:
            products::productsList();
            break;
        case 101:
            products::productsCataloge();
            break;
        default: {
                $pageData = DEFAULT_PAGE_DATA;
                $pageData['title'] = "Invalid option";
                $pageData['description'] = "Invalid option";
                $pageData['content'] = '<b style="color:red"> Invalid opetarion</b>';
                webpage::render($pageData);
            }
    }
}
main();
