<?php

function checkDirectory()
{
    if (!file_exists(LOG_DIRECTORY)) {
        mkdir(LOG_DIRECTORY);
    }
}

function counterListProducts($pageData): int
{
    $file_path = getcwd() . DIRECTORY_SEPARATOR . LOG_DIRECTORY . DIRECTORY_SEPARATOR;
    checkDirectory();
    $file_path .= 'log_visit_list_products.txt';
    if (!file_exists($file_path)) {
        file_put_contents($file_path, $pageData['viewCounter']);
    }

    $pageData['viewCounter'] = file_get_contents($file_path);
    $pageData['viewCounter']++;
    file_put_contents($file_path, $pageData['viewCounter']);
    return $pageData['viewCounter'];
}

function counterCatalogeProducts($pageData): int
{
    $file_path = getcwd() . DIRECTORY_SEPARATOR . LOG_DIRECTORY . DIRECTORY_SEPARATOR;
    checkDirectory();
    $file_path .= 'log_visit_catalog_products.txt';
    if (!file_exists($file_path)) {
        file_put_contents($file_path, $pageData['viewCounter']);
    }
    $pageData['viewCounter'] = file_get_contents($file_path);
    $pageData['viewCounter']++;
    file_put_contents($file_path, $pageData['viewCounter']);
    return $pageData['viewCounter'];
}

function logVisitor()
{
    $f = fopen(LOG_DIRECTORY . DIRECTORY_SEPARATOR . VISITOR_LOG_FILE, "a");
    fwrite($f, date(DATE_RFC2822) . " " . $_SERVER['REMOTE_ADDR']  . PHP_EOL);
    fclose($f);
}

function checkInput(string $name, int $maxlength = 0)
{

    if (isset($_REQUEST[$name])) {
        if ($maxlength <= 0 || strlen($_REQUEST[$name]) > $maxlength) {
            header('HTTP/1.0 400 Input ' . $name . ' too long');
            exit('Input ' . $name . ' too long');
        }
        return $_REQUEST[$name];
    } else {
        header('HTTP/1.0 400 Input ' . $name . ' missing ont the login form in user.php');
        exit('Input ' . $name . ' missing ont the login form in user.php');
    }
}
