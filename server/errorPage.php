<?php

function displayError(string $message = "", int $code = 400)
{
    header("HTTP/1.0 $code $message");
    $pageData = DEFAULT_PAGE_DATA;
    $pageData['title'] = "Error - " . COMPANY_NAME;
    $PageData['description'] = 'Error';
    $pageData['content'] = "<div class='alert alert-danger m-2' role='alert'>
                                $message
                            </div>";
    webpage::render($pageData);
}
