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
        case 50:
            //file download from server to client
            // set the file type, here a PDF file, see link below for other file types
            header('Content-type: application/pdf');
            // file name is some_file.pdf, the web browser asks to confirm the download
            header('Content-Disposition: attachment; filename=some_file.pdf');
            // send out the file, read and send directly with readfile() function
            readfile('some_file.pdf');
            break;
        case 51:
            //redirect to amazon.ca
            header('location: https://www.amazon.ca/ ');
            break;
        case 100:
            products::productsList();
            break;
        case 101:
            products::productsCataloge();
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
