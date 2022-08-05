</main>
<!-- FOOTER -->
<footer>
    Designed by <?= $pageData['author'] ?> &copy;<br>
    <?= COMPANY_NAME; ?> </br>
    <?= COMPANY_STREET_ADDRESS . " " . COMPANY_CITY  . " " . COMPANY_PROVINCE  . " " . COMPANY_POSTAL_CODE; ?> </br>
    <?= PHONE_NUMBER . ' | ' . EMAIL; ?> </br>
    View: <?= $pageData['viewCounter'] ?>

</footer>

</body>

</html>