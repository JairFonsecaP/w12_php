<?php

function showTitle($title)
{
    echo "<h2>&#9830; $title</h2>";
    echo '<hr/>';
}
// Exercise 10-0
showTitle('1.display the current working directory');
echo getcwd();

showTitle('2.verify that file ex10-0_text.txt exists in current directory, if not display message "file does not exist"');
$output;
$file_name = "ex10-0_text.txt";
if (file_exists($file_name)) {
    $output = 'The file ' . $file_name . ' exists';
} else {
    $output = 'The file ' . $file_name . ' does not exists';
}
echo $output;



showTitle('3.display the file size of file ex10-0_text.txt in bytes');
$size = filesize($file_name);
echo 'The size of ' . $file_name . ' file is ' . $size . ' bytes.';


showTitle('4.read whole content of file ex10-0_text.txt and save in a variable. Display content on web page.');
$content = file_get_contents($file_name);
echo $content;


showTitle('5.copy file ex10-0_text.txt to HELLO.txt');
copy($file_name, 'HELLO.txt');
echo 'File copied';


showTitle('6.replace whole file content of HELLO.txt by the text "Hello World"');
file_put_contents($file_name, "Hello World");
echo 'File changed';


showTitle('7.rename file HELLO.TXT to HELLO2.txt');
rename('HELLO.txt', 'HELLO2.txt');
echo 'name changed';


showTitle('8.delete file HELLO2.txt');
if (file_exists("HELLO2.txt")) {
    unlink('HELLO2.txt');
    echo 'File deleted';
}
