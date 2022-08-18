<!-- NAVIGATION BAR-->
<nav>
    |
    <a href='index.php'><i class='fas fa-home'></i> Home</a>
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
        $optionCustomers = ROUTES['customers'];
        $optionLogout = ROUTES['logout'];
        $optionEmployees = ROUTES['employee_list'];
        echo "<a href='index.php?op=$optionEmployees'>Employees</a> | ";
        echo "<a href='index.php?op=$optionCustomers'>Customers</a> | ";
        echo "<a href='index.php?op=$optionLogout'>Logout</a> | ";
        echo "<b>" . $_SESSION['email'] . "</b> | ";
        echo "<img class='avatar' src='" . USER_IMAGES . DIRECTORY_SEPARATOR . $_SESSION['picture'] . "' /> |";
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