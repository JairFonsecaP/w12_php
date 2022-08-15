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
            $employees =  $DB->querySelect(
                "SELECT emp.employeeNumber, CONCAT(emp.firstName, ' ',emp.lastName) as fullname, emp.email, emp.extension, offices.officeCode, offices.postalCode, offices.city, offices.state, emp.jobTitle, boss.employeeNumber as bossNumber, CONCAT(boss.firstName, ' ',boss.lastName) as fullnameBoss FROM employees emp
                INNER JOIN offices ON offices.officeCode = emp.officeCode
                LEFT JOIN employees boss
                ON boss.employeeNumber = emp.reportsTo;"
            );
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
                    <th scope="row" header="number" class="table-danger"><a href='index.php?op=302&employeeNumber={$number}'>{$number}</a></th>
                    <td header="jobTitle" class="table-danger">{$jobTitle}</td>
                    <td header="name" class="table-danger">{$fullname}</td>
                    <td header="email" class="table-danger"><a href="mailto:{$email}">{$email}</a></td>
                    <td header="extension" class="table-danger">{$extension}</td>
                    <td header="officeCode office"  class="table-primary">{$officeCode}</td>
                    <td header="postalCode office"  class="table-primary">{$postalCode}</td>
                    <td header="city office"  class="table-primary">{$city}</td>
                    <td header="state office"  class="table-primary">{$state}</td>
                    <td header="bossNumber boss" class="table-success"><a href='index.php?op=302&employeeNumber={$number}'>{$bossNumber}</a></td>
                    <td header="bossName boss" class="table-success">{$fullnameBoss}</td>
                    <td header="view action" class="table-warning"><a href="index.php?op=302&employeeNumber={$number}">View</a></td>
                    <td header="edit action" class="table-warning"><a href="index.php?op=303&employeeNumber={$number}">Edit</a></td>
                    <td header="delete action" class="table-warning"><a href="index.php?op=304&employeeNumber={$number}">Delete</a></td>
                </tr>
            HTML;
        }

        $showAllOp = ROUTES['employee_list'];
        $content = <<<HTML
            <div class="card m-2">
                <div class="card-body">
                    <form class="row g-3 m-2" method="GET" action="index.php">
                        <input type="hidden" value="{$showAllOp}" name="op" />

                        <div class="col">
                            <input type="text" class="form-control" name="id" id="id" placeholder="Search by id" aria-label="Id">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">Go</button>
                        </div>
                        <div class="col-auto">
                            <a type='button' class="btn btn-primary" href="index.php?op={$showAllOp}">Show all</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card m-2">
                <h5 class="card-header d-flex justify-content-between">{$size}<a type="button" class="btn btn-primary" href="index.php?op=303">New employee</a></h5>
                <div class="card-body p-0">
                    <table class="table table-striped m-0">
                        <thead class="text-center">
                            <tr>
                                <th colspan="5" class="table-danger">Employee</th>
                                <th scope="col" id="office" colspan="4" class="table-primary">Office</th>
                                <th scope="col" id="boss" colspan="2" class="table-success">Boss</th>
                                <th scope="col" id="aciton" colspan="3" class="table-warning">Actions</th>
                            </tr>
                            <tr>
                                <th scope="col" id="number" class="table-danger">Number</th>
                                <th scope="col" id="jobTitle" class="table-danger">Job title</th>
                                <th scope="col" id="name" class="table-danger">Name</th>
                                <th scope="col" id="email" class="table-danger">Email</th>
                                <th scope="col" id="extension" class="table-danger">Extension</th>
                                <th scope="col" id="officeCode office"  class="table-primary">Code</th>
                                <th scope="col" id="postalCode office"  class="table-primary">Postal code</th>
                                <th scope="col" id="city office"  class="table-primary">City</th>
                                <th scope="col" id="state office"  class="table-primary">State</th>
                                <th scope="col" id="bossNumber boss" class="table-success">Number</th>
                                <th scope="col" id="bossName boss" class="table-success">Name</th>
                                <th scope="col" id="view action" class="table-warning">View</th>
                                <th scope="col" id="edit action" class="table-warning">Edit</th>
                                <th scope="col" id="delete action" class="table-warning">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            {$table_body}
                        </tbody>
                    </table>
                </div>
            </div>
        HTML;

        $pageData['content'] = $content;
        webpage::render($pageData);
    }

    public static function registrer(array $previusData = [], array $errors = [])
    {
        $pageData = DEFAULT_PAGE_DATA;
        $PageData['description'] = 'Registration of new user';
        $pageData['title'] = "New employee - " . COMPANY_NAME;
        $card_title = 'New employee';
        $employeeNumber = -1;
        $error = '';

        $button = <<<HTML
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
        HTML;

        /*Validation for editing or create new employee with or without previus data  */
        if ($previusData !== [] && $errors !== []) {
            foreach ($errors as $e) {
                $error .=   <<<HTML
                                <div class="alert alert-danger m-1" role="alert">
                                    {$e}
                                </div>
                            HTML;
            }
        } else {
            if (isset($_GET['employeeNumber'])) {
                $previusData = self::getEmployeeByNumber($_GET['employeeNumber']);
            }
            if (count($previusData) >= 1) {
                $previusData = $previusData[0];
                $card_title = "Edit employee";
                $employeeNumber = $previusData['employeeNumber'];
                $pageData['title'] = "Editing to " . $previusData['firstName'] . ' ' . $previusData['lastName'] . ' - ' . COMPANY_NAME;
                $button = <<<HTML
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Edit</button>
                    </div>
                HTML;
            } else {
                $previusData = [
                    'lastName' => '',
                    'firstName' => '',
                    'extension' => '',
                    'email' => '',
                    'officeCode' => '',
                    'reportsTo' => null,
                    'jobTitle' => '',
                ];
            }
        }

        /*Input selection REPORTS TO*/
        $DB = new db_pdo();
        $boss = [['employeeNumber'  => null, 'firstName' => '', 'lastName' => 'Nobody']];
        $boss = array_merge($boss, $DB->table('employees'));
        $bossSelect = <<<HTML
                            <div class="col-md-6">
                                <label for="reportsTo" class="form-label">Reports to:</label>
                                <select class="form-select" id="reportsTo">
                        HTML;
        foreach ($boss as $b) {
            $selected = ($previusData['reportsTo'] === $b['employeeNumber'] ? 'selected' : '');
            $bossSelect .= <<< HTML
                            <option value="{$b['employeeNumber']}" {$selected}>{$b['firstName']} {$b['lastName']}</option>
                        HTML;
        }
        $bossSelect .= <<<HTML
                                </select>
                            </div>
                        HTML;

        /*Input selection OFFICE*/

        $DB = new db_pdo();
        $offices = $DB->table('offices');
        $officesSelect = <<<HTML
                            <div class="col-md-6">
                                <label for="offices" class="form-label">Office:</label>
                                <select class="form-select" id="office">
                        HTML;
        foreach ($offices as $office) {
            $selected = ($previusData['officeCode'] === $office['officeCode'] ? 'selected' : '');
            $officesSelect .= <<< HTML
                                <option value="{$office['officeCode']}" {$selected} required>{$office['addressLine1']}, {$office['city']}, {$office['country']}</option>
                            HTML;
        }
        $officesSelect .= <<<HTML
                                </select>
                            </div>
                        HTML;


        $optionForm = ROUTES['employee_register_verify'];

        /*CONTENT*/
        $content = <<<HTML
                <div class="card m-4">
                    <h5 class="card-header">{$card_title}</h5>
                    <div class="card-body">
                        <form action="index.php" method="POST" class="row g-3 needs-validation" novalidate>
                            <input value="{$optionForm}" name="op" type="hidden"/>
                            <input value="{$employeeNumber}" name="employeeNumber" type="hidden"/>
                            <div class="col-md-6">
                                <label for="fisrtName" class="form-label">Firstname:</label>
                                <input type="text" class="form-control" id="fisrtName" value="{$previusData['firstName']}" required maxlength="50">
                                <div class="invalid-feedback">
                                    Enter a firstname (max 50 char)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Lastname:</label>
                                <input type="text" class="form-control" id="lastName" value="{$previusData['lastName']}" required maxlength="50">
                                <div class="invalid-feedback">
                                    Enter a lastname (max 50 char)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="extension" class="form-label">Extension:</label>
                                <input type="text" class="form-control" id="extension" value="{$previusData['extension']}" required maxlength="10">
                                <div class="invalid-feedback">
                                    Enter a lastname (max 10 char)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" value="{$previusData['email']}" required maxlength="100">
                                <div class="invalid-feedback">
                                    You must enter a valid enter
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="jobTitle" class="form-label">Job title:</label>
                                <input type="text" class="form-control" id="jobTitle" value="{$previusData['jobTitle']}" required maxlength="50">
                                <div class="invalid-feedback">
                                    You must enter a job title (max 50 char)
                                </div>
                            </div>
                            {$bossSelect}
                            {$officesSelect}
                            {$button}
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

        $pageData['content'] = $content;
        webpage::render($pageData);
    }

    public static function registrerVerify()
    {
        echo $_POST['op'];
    }

    public static function employeeDetail()
    {
        if (!isset($_GET['employeeNumber'])) {
            header('location: index.php?op=300');
        }
        $employee = self::getEmployeeByNumber($_GET['employeeNumber'])[0];
        $fullname = $employee['firstName'] . " " . $employee['lastName'];

        $content = <<<HTML
            <div class="card m-4">
                <h5 class="card-header"><b>#{$employee['employeeNumber']}</b> | {$fullname}</h5>
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        HTML;

        $pageData = DEFAULT_PAGE_DATA;
        $PageData['description'] = 'Details of ' . $employee['firstName'] . " " . $employee['lastName'];
        $pageData['title'] = $employee['firstName'] . " " . $employee['lastName'] . " - " . COMPANY_NAME;
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

    private static function getEmployeeByNumber($number): array
    {
        $DB = new db_pdo();
        $params = ['number' => $number];
        $employee =  $DB->querySelectParam("SELECT emp.employeeNumber, emp.firstName,emp.lastName, emp.email, emp.extension, offices.officeCode, offices.postalCode, offices.city, offices.state, emp.jobTitle, boss.employeeNumber as reportsTo, CONCAT(boss.firstName, ' ',boss.lastName) as fullnameBoss FROM employees emp
            INNER JOIN offices ON offices.officeCode = emp.officeCode
            LEFT JOIN employees boss
            ON boss.employeeNumber = emp.reportsTo
            WHERE emp.employeeNumber = :number;", $params);
        return $employee;
    }
}
