<?php
class webpage
{
    static public function render($pageData)
    {
        //WEBSITE TEMPLEATE
        require_once 'head.php';
        require_once 'header.php';
        require_once 'nav.php';
        echo $pageData['content'];
        require_once 'footer.php';
        exit();
    }
}
