<?php
class customers
{
    public static function list()
    {
        $pageData = DEFAULT_PAGE_DATA;
        $PageData['description'] = 'List all customers';
        $id = isset($_POST['id']) ? $_POST['id'] : false;

        $DB = new db_pdo();
        if (!$id) {
            $customers =  $DB->table("customers");
            $pageData['title'] = "Customers - " . COMPANY_NAME;
        } else {
            $params = ['customerNumber' => $id];
            $customers =  $DB->querySelectParam("SELECT * from customers WHERE customerNumber = ?", $params);
            $pageData['title'] = "Customer #$id - " . COMPANY_NAME;
        }

        $size = 'Number of customers found: ' . count($customers);
        $table_body = '';

        foreach ($customers as $customer) {
            $number = $customer['customerNumber'];
            $name = $customer['customerName'];
            $phone = $customer['phone'];
            $address = $customer['addressLine1'] . ' ' . $customer['addressLine2'];
            $city = $customer['city'];
            $state = $customer['state'];
            $country = $customer['country'];
            $postal_code = $customer['postalCode'];
            $table_body .= <<<HTML
                <tr>
                    <th scope="row">{$number}</th>
                    <td>{$name}</td>
                    <td>{$phone}</td>
                    <td>{$address}</td>
                    <td>{$city}</td>
                    <td>{$state}</td>
                    <td>{$country}</td>
                    <td>{$postal_code}</td>
                </tr>
            HTML;
        }

        $showAllOp = ROUTES['customers'];
        $content = <<<HTML
            <form class="row g-3 m-2" method="POST" action="index.php">
            <input type="hidden" value="{$showAllOp}" name="op" />
                <div class="alert alert-dark" role="alert">
                    {$size}
                </div>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="id" id="id" placeholder="Search by id" aria-label="Id">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success">Go</button>
                </div>
                <div class="col-auto">
                    <a type='button' class="btn btn-primary" href="index.php?op={$showAllOp}">Show all</a>
                </div>
            </form>
            <table class="table table-striped ms-2 me-5">
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Name</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">City</th>
                        <th scope="col">State</th>
                        <th scope="col">Country</th>
                        <th scope="col">Postal code</th>
                    </tr>
                </thead>
                <tbody>
                    {$table_body}
                </tbody>
            </table>
        HTML;

        $pageData['content'] = $content;
        webpage::render($pageData);
    }
}
