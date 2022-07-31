<?php

//the NAV bar items
const HOME = 'Welcome';
const PRODUCT = 'Product Catalogue';
const ABOUT = 'About Us';
const IDEA = 'Gift Ideas';

// the selected item
$selected = ABOUT;
$nav_items = ['HOME' => HOME, 'PRODUCT' => PRODUCT, 'ABOUT' => ABOUT, 'IDEA' => IDEA];

if (isset($_REQUEST['selection'])) {
    $selected = $_REQUEST['selection'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Exercice 6-1 conditionnal CSS</title>
    <style>
        nav ul li {
            display: inline-block;
            width: 150px;
            padding: 4px;
            margin: 4px;
            text-align: center;
        }

        .menu-item {
            background-color: #e1f4f3;
            color: #0000cc;
        }

        .selected {
            background-color: #fea799;
        }
    </style>
</head>

<body>
    <h2>Exercise 6-1: Highlight the selected item in the nav bar</h2>
    <nav>
        <ul>
            <?php
            function select($value)
            {
                global $selected;
                $selected = $value;
            }
            foreach ($nav_items as $key => $item) {
                if ($key == $selected) {
                    echo '<li class="selected"><a href="">' . $item . '</a></li>';
                    continue;
                }
                echo '<li class="menu-item"><a href="ex6-1.php?selection=' . $key . '">' . $item . '</a></li>';
            }
            ?>
        </ul>
    </nav>
</body>

</html>