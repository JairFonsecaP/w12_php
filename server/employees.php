<?php
class employees
{
    public static function list()
    {
        $pageData = DEFAULT_PAGE_DATA;
        $PageData['description'] = 'List all employees';
        $id = isset($_POST['id']) ? $_POST['id'] : false;

        $DB = new db_pdo();
        if (!$id) {
            $employees =  $DB->querySelect("SELECT emp.employeeNumber, CONCAT(emp.firstName, ' ',emp.lastName) as fullname, emp.email, emp.extension, offices.officeCode, offices.postalCode, offices.city, offices.state, emp.jobTitle, boss.employeeNumber as bossNumber, CONCAT(boss.firstName, ' ',boss.lastName) as fullnameBoss FROM employees emp
            INNER JOIN offices ON offices.officeCode = emp.officeCode
            LEFT JOIN employees boss
            ON boss.employeeNumber = emp.reportsTo;");
            $pageData['title'] = "Employees - " . COMPANY_NAME;
        } else {
            $params = ['customerNumber' => $id];
            $employees =  $DB->querySelectParam("SELECT * from employees WHERE employeeNumber = ?", $params);
            $pageData['title'] = "Employees #$id - " . COMPANY_NAME;
        }

        $size = 'Number of employees found: ' . count($employees);
        $table_body = '';

        foreach ($employees as $employee) {
            $number = $employee['employeeNumber'];
            $fullname = $employee['fullname'];
            $email = $employee['email'];
            $extension = $employee['extension'];
            $officeCode = $employee['officeCode'];
            $postalCode = $employee['postalCode'];
            $city = $employee['city'];
            $state = $employee['state'];
            $bossNumber = $employee['bossNumber'];
            $fullnameBoss = $employee['fullnameBoss'];
            $jobtitle = $employee['jobTitle'];
            $table_body .= <<<HTML
                <tr>
                    <th scope="row">{$number}</th>
                    <td>{$fullname}</td>
                    <td>{$email}</td>
                    <td>{$extension}</td>
                    <td>{$officeCode}</td>
                    <td>{$postalCode}</td>
                    <td>{$city}</td>
                    <td>{$state}</td>
                    <td>{$bossNumber}</td>
                    <td>{$fullnameBoss}</td>
                    <td>{$jobtitle}</td>
                </tr>
            HTML;
        }

        $showAllOp = ROUTES['employee_list'];
        $content = <<<HTML
            <!-- <form class="row g-3 m-2" method="POST" action="index.php">
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
            </form> -->
            <table class="table table-striped ms-2 me-5">
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Extension</th>
                        <th scope="col">Office code</th>
                        <th scope="col">Postal code</th>
                        <th scope="col">City</th>
                        <th scope="col">State</th>
                        <th scope="col">Boss number</th>
                        <th scope="col">Boss name</th>
                        <th scope="col">Job title</th>
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
