<?php
class orders
{
    public static function list()
    {

        $DB = new db_pdo();
        $orders =  $DB->table("orders");
        $pageData = DEFAULT_PAGE_DATA;
        $PageData['description'] = 'List of all orders';
        $pageData['title'] = "Orders list - " . COMPANY_NAME;

        /*Validation if exist orders */
        if (count($orders) === 0) {
            $pageData['title'] = "List order empty - " . COMPANY_NAME;
            $content = <<<HTML
                    <div class="alert alert-warning" role="alert">
                        A simple warning alert—check it out!
                    </div>
            HTML;
            $pageData['content'] = $content;
            webpage::render($pageData);
        }
        $size = 'Number of orders found: ' . count($orders);

        $table_body = '';

        foreach ($orders as $order) {
            $orderNumber = $order['orderNumber'];
            $orderDate = $order['orderDate'];
            $status = $order['status'];
            $customerNumber = $order['customerNumber'];

            $table_body .= <<<HTML
                <tr>
                    <th scope="row" header="orderNumber"><a href='index.php?op=202&orderNumber={$orderNumber}'>{$orderNumber}</a></th>
                    <td header="orderDate">{$orderDate}</td>
                    <td header="status">{$status}</td>
                    <td header="customerNumber">{$customerNumber}</td>
                    <td header="edit action"><a style="text-decoration: none;color:gray" href="index.php?op=203&employeeNumber={$orderNumber}"><i class='fas fa-user-edit'></i></a></td>
                </tr>
            HTML;
        }

        $content = <<<HTML
                        <div class="card m-2">
                <div class="card-body">

                </div>
            </div>
            <div class="card m-2">
                <h5 class="card-header d-flex justify-content-between align-items-center"><p class="align-self-center">{$size}</p><a type="button" class="btn btn-primary" href="index.php?op=303">New Order</a></h5>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover m-0">
                        <thead >
                            <tr>
                                <th scope="col" id="orderNumber" >Order Number</th>
                                <th scope="col" id="orderDate" >Order Date</th>
                                <th scope="col" id="status" >Status</th>
                                <th scope="col" id="customerNumber" >Customer Number</th>
                                <th scope="col" id="actions" >Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            {$table_body}
                        </tbody>
                    </table>
                </div>
            </div>
        HTML;

        $pageData = DEFAULT_PAGE_DATA;
        $PageData['description'] = 'List of all orders';
        $pageData['title'] = "Orders list - " . COMPANY_NAME;
        $pageData['content'] = $content;

        webpage::render($pageData);
    }
    public static function display()
    {
        if (!isset($_GET['orderNumber']) || $_GET['orderNumber'] === '') {
            header('location: index.php?op=200');
        }

        $DB = new db_pdo();
        $query = '  SELECT orderNumber, orderDate, requiredDate, shippedDate, status, comments, contactFirstName, contactLastName, orders.customerNumber   FROM orders
                    inner join customers on customers.customerNumber = orders.customerNumber
                    where orderNumber = :orderNumber';
        $params = ['orderNumber' => $_GET['orderNumber']];
        $order = $DB->querySelectParam($query, $params);

        if (count($order) > 0) {
            $order = $order[0];
            $orderNumber = $order['orderNumber'];
            $orderDate = $order['orderDate'];
            $requiredDate = $order['requiredDate'];
            $shippedDate = $order['shippedDate'];
            $status = $order['status'];
            $comments = $order['comments'];
            $customerName = $order['contactFirstName'] . ' ' . $order['contactLastName'];
            $customerNumber = $order['customerNumber'];

            $DB = new db_pdo();
            $params = ['orderNumber' => $orderNumber];
            $products = $DB->querySelectParam('SELECT * FROM orderdetails WHERE orderNumber = :orderNumber', $params);
            if (count($products) > 0) {

                $tableBody = '';
                $total = 0;
                foreach ($products as $product) {
                    $subtotal = $product['quantityOrdered'] * $product['priceEach'];
                    $total += $subtotal;
                    $subtotal = number_format($subtotal, 2);
                    $tableBody .= <<<HTML
                        <tr>
                            <td>{$product['productCode']}</td>
                            <td>{$product['quantityOrdered']}</td>
                            <td>{$product['priceEach']}</td>
                            <td>$ {$subtotal}</td>
                        </tr>
                    HTML;
                }
                $total = number_format($total, 2);
                $tableBody .= <<<HTML
                        <tr><td></td><td></td><th>Total: </th><td>$ {$total}</td></tr>
                HTML;
                $products = <<<HTML
                    <table class="table table-striped table-hover" style="width: 500px">
                        <thead>
                            <tr>
                                <th id="code">Product Code</th>
                                <th id="qty">Qty ordered</th>
                                <th id="unit">Unit Price</th>
                                <th id="subtotal">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$tableBody}
                        </tbody>
                    </table>
                HTML;
            } else {
                $products = <<<HTML
                        <div class="alert alert-warning" role="alert">
                            There are not product in this order
                        </div>
                HTML;
            }

            $content = <<<HTML
            <div class="card mt-3 mb-3"  style="width:700px; margin:auto">
                <h5 class="card-header">Order #{$orderNumber}</h5>
                <div class="card-body">
                    <h5 class="card-title">Customer: {$customerName} - Number of customer: {$customerNumber}</h5>
                    <p class="card-text">Order Status: {$status}</p>
                    <p class="card-text">Order Date: {$orderDate}</p>
                    <p class="card-text">Required Date: {$requiredDate}</p>
                    <p class="card-text">Shipped Date: {$shippedDate}</p>
                    {$products}
                    <p class="card-text">Comments: {$comments}</p>

                    <a href="index.php?op=200" class="btn btn-primary">Back to list</a>
                    <a href="index.php?op=203&orderNumber=${orderNumber}" class="btn btn-secondary">Edit</a>
                </div>
            </div>
            HTML;
        } else {
            header('HTTP/1.0 404 Order not found');
            $content = <<<HTML
                            <div class="alert alert-warning" role="alert">
                                Order not found
                            </div>
            HTML;
        }


        $pageData = DEFAULT_PAGE_DATA;
        $PageData['description'] = 'List of all orders';
        $pageData['title'] = "Order #{$orderNumber} - " . COMPANY_NAME;
        $pageData['content'] = $content;

        webpage::render($pageData);
    }
    public static function edit(array $errors = [], array $previusData = [])
    {
    }
    public static function save()
    {
    }
}
