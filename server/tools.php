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
    fwrite($f, date(DATE_RFC2822) . PHP_EOL);
    fclose($f);
}
