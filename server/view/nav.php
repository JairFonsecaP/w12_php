<!-- NAVIGATION BAR-->
<nav>
    <a href='index.php'>Home</a>
    |
    <a href='index.php?op=<?= ROUTES['product-list'] ?>'>Product List</a>
    |
    <a href='index.php?op=<?= ROUTES['product-cataloge'] ?>'>Product Catalog</a>
    |
    <a href='index.php?op=<?= ROUTES['download-file'] ?>'>Download</a>
    |
    <a href='index.php?op=<?= ROUTES['redirect-amazon'] ?>'>Redirect</a>
    |
    <?php

    if (isset($_SESSION['email'])) {
        $option = ROUTES['logout'];
        echo "<a href='index.php?op=$option'>Logout</a> | ";
        echo "<b>" . $_SESSION['email'] . "</b>";
    } else {
        $optionLogin = ROUTES['login'];
        $optionRegister = ROUTES['register'];
        echo  "<a href='index.php?op=$optionLogin'>Login</a>
        |
        <a href='index.php?op=$optionRegister'>Register</a>
        |";
    }
    ?>


</nav>
<!-- CONTENT -->
<main>