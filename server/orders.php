<?php
class orders
{
    public static function list(string $message = '', bool $success = false)
    {
        if ($message !== '') {
            if ($success) {
                $message = <<<HTML
                <div class="alert alert-success m-2" role="alert">
                    {$message}
                </div>
            HTML;
            } else {
                $message = <<<HTML
                <div class="alert alert-danger m-2" role="alert">
                    {$message}
                </div>
            HTML;
            }
        }

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
                    <td header="edit action"><a style="text-decoration: none;color:gray" href="index.php?op=203&orderNumber={$orderNumber}"><i class='fas fa-user-edit'></i></a></td>
                </tr>
            HTML;
        }

        $content = <<<HTML
            <div class="card m-2">
                <h5 class="card-header d-flex justify-content-between align-items-center"><p class="align-self-center">{$size}</p><a type="button" class="btn btn-primary" href="index.php?op=303">New Order</a></h5>
                <div class="card-body p-0">
                {$message}
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
        $showError = '';
        if ($errors !== []) {
            foreach ($errors as $key => $value) {
                $showError .= <<<HTML
                    <div class="alert alert-danger" role="alert">
                        <b>{$key}</b> {$value}
                    </div>
                HTML;
            }
        }

        if (isset($_GET['orderNumber'])) {
            if ($previusData === []) {
                $query = 'SELECT * FROM orders WHERE orderNumber = :orderNumber';
                $params = ['orderNumber' => $_GET['orderNumber']];
                $DB = new db_pdo();
                $previusData = $DB->querySelectParam($query, $params);
                if (count($previusData) > 0) {
                    $previusData = $previusData[0];
                } else {
                    displayError('Order not found', 404);
                }
            }
        } else {
            displayError('You must to enter a orderNumber', 400);
        }
        $DB = new db_pdo();
        $customers = $DB->table('customers');
        $customersSelect = <<<HTML
            <div class="col-md-6">
                                <label for="customerNumber" class="form-label"><b>Customer:</b></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='fas fa-user-alt'></i></span>
                                    <select class="form-select" name="customerNumber" id="customerNumber" required>
        HTML;
        foreach ($customers as $customer) {
            $selected = ($previusData['customerNumber'] === $customer['customerNumber'] ? 'selected' : '');
            $customersSelect .= <<< HTML
                                <option value="{$customer['customerNumber']}" {$selected} required>{$customer['customerName']}</option>
                            HTML;
        }
        $customersSelect .= <<<HTML
                                    </select>
                                </div>
                            </div>
                        HTML;

        $DB = new db_pdo();
        $statusList = $DB->querySelect('SELECT DISTINCT status FROM orders ORDER BY status;');


        $statusRadio = <<<HTML
                    <div>
                        <label class="form-label"><b>Status:</b></label>

            HTML;

        foreach ($statusList as  $value) {
            $checked = ($previusData['status'] === $value['status'] ? 'checked' : '');
            $statusRadio .= <<< HTML
                        <div class="form-check  form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="{$value['status']}" value="{$value['status']}" required {$checked}>
                            <label class="form-check-label" for="{$value['status']}">{$value['status']}</label>
                        </div>
            HTML;
        }

        $statusRadio .= '</div>';


        $optionForm = ROUTES['order_save'];
        $content = <<<HTML
                    <div class="card m-4">
                    <h5 class="card-header">Order #{$previusData['orderNumber']}</h5>
                    <div class="card-body">
                        {$showError}
                        <form action="index.php" method="POST" class="row g-3 needs-validation" novalidate>
                            <input value="{$optionForm}" name="op" type="hidden"/>
                            <input value="{$previusData['orderNumber']}" name="orderNumber" type="hidden"/>
                            {$customersSelect}
                            <div class="col-md-6">
                                <label for="orderDate" class="form-label"><b>Order Date</b> (dd/mm/yyyy):</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="material-icons">date_range</i></span>
                                    <input type="date" class="form-control" id="orderDate" name="orderDate" value="{$previusData['orderDate']}" required>
                                </div>
                                <div class="invalid-feedback">
                                    Enter a date valid dd/mm/yyyy
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="requiredDate" class="form-label"><b>Required Date</b> (dd/mm/yyyy):</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="requiredDate"><i class="material-icons">date_range</i></span>
                                    <input type="date" class="form-control" id="requiredDate" name="requiredDate" value="{$previusData['requiredDate']}" required>
                                </div>
                                <div class="invalid-feedback">
                                    Enter a date valid dd/mm/yyyy
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="shippedDate" class="form-label"><b>Shipped Date</b> (dd/mm/yyyy):</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="shippedDate"><i class="material-icons">date_range</i></span>
                                    <input type="date" class="form-control" id="shippedDate" name="shippedDate" value="{$previusData['shippedDate']}">
                                </div>
                                <div class="invalid-feedback">
                                    Enter a date valid dd/mm/yyyy
                                </div>
                            </div>

                            {$statusRadio}
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="comments" name="comments" >{$previusData['comments']}</textarea>
                                <label for="comments">Comments</label>
                            </div>
                            <input type="submit" class="btn btn-primary col-md-2" value="Update order"/>
                        </form>
                    </div>
                </div>
                <script>
                    /*Validation of fields for input*/
                    ( ()=> {
                    'use strict'

                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.querySelectorAll('.needs-validation')

                    // Loop over them and prevent submission
                    Array.prototype.slice.call(forms)
                        .forEach( (form) =>{
                        form.addEventListener('submit',  (event)=> {
                            if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                        })
                    })()
                </script>
        HTML;

        $pageData = DEFAULT_PAGE_DATA;
        $pageData['content'] = $content;
        $pageData['title'] = 'Editing order: #' . $previusData['orderNumber'] . ' - ' . COMPANY_NAME;

        webpage::render($pageData);
    }
    public static function save()
    {
        $errorMsg = [];

        $orderNumber = checkInput('orderNumber', 11);
        $customerNumber = checkInput('customerNumber', 11);
        $orderDate = checkInput('orderDate', 10);
        $requiredDate = checkInput('requiredDate', 10);
        $shippedDate = checkInput('shippedDate', 100) === '' ? null : $_POST['shippedDate'];
        $status = checkInput('status', 15);
        $comments = checkInput('comments', 65000) === '' ? null : $_POST['comments'];

        if ($orderNumber === '') {
            $errorMsg = ['Order number:' => 'You must enter a valid value'];
        }

        if ($customerNumber === '') {
            $errorMsg = ['Customer:' => 'You must enter a valid value'];
        } else {
            $DB = new db_pdo();
            $params = ['customerNumber' => $customerNumber];
            $customer = $DB->querySelectParam('SELECT * FROM customers WHERE customerNumber = :customerNumber', $params);
            if (count($customer) === 0) {
                $errorMsg = ['Customer number:' => "The customer doesn't exist"];
            }
        }

        if ($orderDate === '') {
            $errorMsg = ['Order Date' => 'You must enter a valid value'];
        }

        if ($requiredDate === '') {
            $errorMsg = ['Required Date' => 'You must enter a valid value'];
        }

        if ($status === '') {
            $errorMsg = ['status' => 'You must enter a valid value'];
        }

        $orderData = [
            'orderNumber' => $orderNumber,
            'orderDate' => $orderDate,
            'requiredDate' => $requiredDate,
            'shippedDate' => $shippedDate,
            'status' => $status,
            'comments' => $comments,
            'customerNumber' => $customerNumber,
        ];


        if ($errorMsg !== []) {
            header("HTTP/1.0 400");
            self::edit($errorMsg, $orderData);
        }

        //SAVE IN DB
        $DB = new db_pdo();
        $query =
            'UPDATE orders
                SET
                orderDate = :orderDate,
                requiredDate = :requiredDate,
                shippedDate = :shippedDate,
                status = :status,
                comments = :comments,
                customerNumber = :customerNumber
                WHERE orderNumber = :orderNumber;';
        $response = $DB->queryParam($query, $orderData);

        if ($response->rowCount() === 1) {
            self::list('Order was edited', true);
        } else {
            self::list("Order couldn't be edited", false);
        }
    }
}
