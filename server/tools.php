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

function checkInput(string $name, int $maxlength = 0): string
{

    if (isset($_REQUEST[$name])) {
        if ($maxlength <= 0 && strlen($_REQUEST[$name]) > $maxlength) {
            header('HTTP/1.0 400 Input ' . $name . ' too long');
            exit('Input ' . $name . ' too long');
        }
        return htmlspecialchars($_REQUEST[$name]);
    } else {
        header('HTTP/1.0 400 Input ' . $name . ' missing');
        exit('Input ' . $name . ' missing');
    }
}


/**
 * Check uploaded file contains a valid image
 * extension must be: .jpg , .JPG , .gif ou .png.
 *
 * @param string $file_input the file input name on the HTML form
 * @param int    $Max_Size   maximum file size, default 500kb
 *
 * @return 'OK' or error message
 */
function Picture_Uploaded_Is_Valid($file_input, $Max_Size = 500000)
{
    //Form must have <form enctype="multipart/form-data" ..
    //otherwise $_FILE is undefined
    // $file_input is the file input name on the HTML form
    if (!isset($_FILES[$file_input])) {
        return 'No image uploaded';
    }

    //check for upload error
    if ($_FILES[$file_input]['error'] != UPLOAD_ERR_OK) {
        return 'Error picture upload: code=' . $_FILES[$file_input]['error'];
    }

    // Check image size
    if ($_FILES[$file_input]['size'] > $Max_Size) {
        return 'Image too big, max file size is ' . $Max_Size . ' Kb';
    }

    // Check that file actually contains an image
    $check = getimagesize($_FILES[$file_input]['tmp_name']);
    if ($check === false) {
        return 'This file is not an image';
    }

    // Check extension is jpg,JPG,gif,png
    $filePathArray = pathinfo($_FILES[$file_input]['name']);
    $fileExtension = $filePathArray['extension'];
    if ($fileExtension != 'jpg' && $fileExtension != 'JPG' && $fileExtension != 'gif' && $fileExtension != 'png') {
        return 'Invalid image file type, valid extensions are: .jpg .JPG .gif .png';
    }

    return 'OK';
}

/**
 *  Function to save uploaded image in a target directory
 *  (and display image for testing).
 *
 *  @param string $file_input the file input name on the HTML form
 *  @param string $target_dir the directory where to save picture
 *
 *  @return string OK or error message
 */
function Picture_Uploaded_Save_File($file_input, $target_dir)
{
    $message = Picture_Uploaded_Is_Valid($file_input); // voir fonction
    if ($message === 'OK') {
        // Check that no file with the same name already exists
        // in the target directory
        $target_file = $target_dir . DIRECTORY_SEPARATOR . basename($_FILES[$file_input]['name']);
        if (file_exists($target_file)) {
            return 'This file already exists';
        }

        // Create the file with move_uploaded_file()
        if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $target_file)) {
            // ALL OK
            //display image for testing, comment next line when done
            echo '<img src="' . $target_file . '">';

            return 'OK';
        } else {
            return 'Error in move_upload_file';
        }
    } else {
        // upload error, invalid image or file too big
        return $message;
    }
}

/**
 * Return the image MIME type (content-type).
 *
 * Attention: Use function Photo_Uploaded_Is_Valid() before !
 * We take for granted the photo validity was verified before.
 *
 * @param string $file_input the file name input on the form
 *
 * @return string the MIME type for example 'image/jpeg' or 'ERROR'
 */
function Picture_Uploaded_Mime_Type($file_input)
{
    $filePathArray = pathinfo($_FILES[$file_input]['name']);
    $fileExtension = $filePathArray['extension'];
    switch ($fileExtension) {
        case 'jpg':
        case 'JPG':
            return 'image/jpeg';

        case 'gif':
        case 'GIF':
            return 'image/gif';

        case 'png':
        case 'PNG':
            return 'image/png';
    }

    return 'ERROR';
}

/**
 * Converts image in base64 to put in a database.
 *
 * @param string $file_input the file name input on the form
 */
function Picture_Uploaded_base64($file_input)
{
    $message = Picture_Uploaded_Is_Valid($file_input); // see function
    if ($message !== 'OK') {
        return $message;
    }
    $image = file_get_contents($_FILES[$file_input]['tmp_name']);

    // convert image in base64
    // https://www.php.net/manual/en/function.base64-encode.php
    $image_base64 = base64_encode($image);

    return $image_base64;
}

/**
 * Exemple function to save image
 * in a DB, in a column of type BLOB.
 */
function Picture_Save_BLOB($file_input)
{
    $message = Picture_Uploaded_Is_Valid($file_input); // voir fonction
    if ($message === 'OK') {
        $mime_type = Picture_Uploaded_Mime_Type($file_input); // voir function
        if ($mime_type != 'ERROR') {
            $image = file_get_contents($_FILES[$file_input]['tmp_name']);

            // converts image in base64
            // https://www.php.net/manual/en/function.base64-encode.php
            $image_base64 = base64_encode($image);

            //display image in binary format
            echo '<h2>image received</h2>';
            echo '<img src="data:' . $mime_type . ';base64,' . $image_base64 . '" alt="an image" />';

            // insert in database classicmodels, see db_pdo.php
            // table productlines, image field mediumblob
            // BLOB = binary long object to save images in DB mysql
            $DB = new db_pdo();
            $DB->query('UPDATE productlines SET image="' . $image_base64 . '" WHERE productLine="planes"');

            //extract and display to test
            $records = $DB->querySelect('SELECT * from productlines WHERE productLine="planes"');
            $image_base64 = $records[0]['image'];
            echo '<h2>image in database</h2>';
            echo '<img src="data:image;base64,' . $image_base64 . '" alt="an image" />';
        } else {
            echo 'Error file type not supported';
        }
    } else {
        echo $message;
    }
}
