<?php
class employees
{
    public static function list()
    {
        $pageData = DEFAULT_PAGE_DATA;
        $PageData['description'] = 'List all employees';
        $id = isset($_GET['id']) ? $_GET['id'] : false;

        if (!$id) {
            $DB = new db_pdo();
            $employees =  $DB->querySelect("SELECT emp.employeeNumber, CONCAT(emp.firstName, ' ',emp.lastName) as fullname, emp.email, emp.extension, offices.officeCode, offices.postalCode, offices.city, offices.state, emp.jobTitle, boss.employeeNumber as bossNumber, CONCAT(boss.firstName, ' ',boss.lastName) as fullnameBoss FROM employees emp
            INNER JOIN offices ON offices.officeCode = emp.officeCode
            LEFT JOIN employees boss
            ON boss.employeeNumber = emp.reportsTo;");
            $pageData['title'] = "Employees - " . COMPANY_NAME;
        } else {
            $employees =  self::getEmployeeByNumber($id);
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
            $jobTitle = $employee['jobTitle'];
            $table_body .= <<<HTML
                <tr>
                    <th scope="row" header="number"><a href='index.php?op=302&employeeNumber={$number}'>{$number}</a></th>
                    <td header="jobTitle">{$jobTitle}</td>
                    <td header="name">{$fullname}</td>
                    <td header="email"><a href="mailto:{$email}">{$email}</a></td>
                    <td header="extension">{$extension}</td>
                    <td header="officeCode office">{$officeCode}</td>
                    <td header="postalCode office">{$postalCode}</td>
                    <td header="city office">{$city}</td>
                    <td header="state office">{$state}</td>
                    <td header="bossNumber boss"><a href='index.php?op=302&employeeNumber={$number}'>{$bossNumber}</a></td>
                    <td header="bossName boss">{$fullnameBoss}</td>
                    <td header="view action"><a href="index.php?op=302&employeeNumber={$number}">View</a></td>
                    <td header="edit action"><a href="index.php?op=303&employeeNumber={$number}">Edit</a></td>
                    <td header="delete action"><a href="index.php?op=304&employeeNumber={$number}">Delete</a></td>
                </tr>
            HTML;
        }

        $showAllOp = ROUTES['employee_list'];
        $content = <<<HTML
            <form class="row g-3 m-2" method="GET" action="index.php">
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
            <table class="table table-striped table-bordered ms-2 me-5">
                <thead class="text-center">
                    <tr>
                        <td colspan="5"></td>
                        <th scope="col" id="office" colspan="4">Office</th>
                        <th scope="col" id="boss" colspan="2">Boss</th>
                        <th scope="col" id="aciton" colspan="3">Action</th>
                    </tr>
                    <tr>
                        <th scope="col" id="number">Number</th>
                        <th scope="col" id="jobTitle">Job title</th>
                        <th scope="col" id="name">Name</th>
                        <th scope="col" id="email">Email</th>
                        <th scope="col" id="extension">Extension</th>
                        <th scope="col" id="officeCode office">Code</th>
                        <th scope="col" id="postalCode office">Postal code</th>
                        <th scope="col" id="city office">City</th>
                        <th scope="col" id="state office">State</th>
                        <th scope="col" id="bossNumber boss">Number</th>
                        <th scope="col" id="bossName boss">Name</th>
                        <th scope="col" id="view action">View</th>
                        <th scope="col" id="edit action">Edit</th>
                        <th scope="col" id="delete action">Delete</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    {$table_body}
                </tbody>
            </table>
        HTML;

        $pageData['content'] = $content;
        webpage::render($pageData);
    }

    public static function employeesJsonList()
    {
        $DB = new db_pdo();
        $employees =  $DB->querySelect("SELECT emp.employeeNumber, CONCAT(emp.firstName, ' ',emp.lastName) as fullname, emp.email, emp.extension, offices.officeCode, offices.postalCode, offices.city, offices.state, emp.jobTitle, boss.employeeNumber as bossNumber, CONCAT(boss.firstName, ' ',boss.lastName) as fullnameBoss FROM employees emp
            INNER JOIN offices ON offices.officeCode = emp.officeCode
            LEFT JOIN employees boss
            ON boss.employeeNumber = emp.reportsTo;");
        $employees = json_encode($employees, JSON_PRETTY_PRINT);
        header('Content-Type: application/json; charset:UTF-8');
        http_response_code(200);
        echo $employees;
    }

    public static function getEmployeeByNumber($number): array
    {
        $DB = new db_pdo();
        $params = ['number' => $number];
        $employee =  $DB->querySelectParam("SELECT emp.employeeNumber, CONCAT(emp.firstName, ' ',emp.lastName) as fullname, emp.email, emp.extension, offices.officeCode, offices.postalCode, offices.city, offices.state, emp.jobTitle, boss.employeeNumber as bossNumber, CONCAT(boss.firstName, ' ',boss.lastName) as fullnameBoss FROM employees emp
            INNER JOIN offices ON offices.officeCode = emp.officeCode
            LEFT JOIN employees boss
            ON boss.employeeNumber = emp.reportsTo
            WHERE emp.employeeNumber = :number;", $params);
        return $employee;
    }
}
