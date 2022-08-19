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
    }
    public static function edit()
    {
    }
    public static function save()
    {
    }
}
