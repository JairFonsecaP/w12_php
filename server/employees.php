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
                "SELECT emp.employeeNumber,emp.firstName, emp.lastName, emp.email, emp.extension, offices.officeCode, offices.postalCode, offices.city, offices.state, emp.jobTitle, boss.employeeNumber as reportsTo, boss.firstName as firstNameBoss, boss.lastName as lastNameBoss FROM employees emp
                INNER JOIN offices ON offices.officeCode = emp.officeCode
                LEFT JOIN employees boss
                ON boss.employeeNumber = emp.reportsTo
                ORDER BY emp.employeeNumber;"
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
            $fullname = $employee['firstName'] . ' ' . $employee['lastName'];
            $email = $employee['email'];
            $extension = $employee['extension'];
            $officeCode = $employee['officeCode'];
            $postalCode = $employee['postalCode'];
            $city = $employee['city'];
            $state = $employee['state'];
            $bossNumber = $employee['reportsTo'];
            $fullnameBoss = $employee['firstNameBoss'] . ' ' . $employee['lastNameBoss'];
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
                    <td header="edit action" class="table-warning text-center"><a style="text-decoration: none;color:gray" href="index.php?op=303&employeeNumber={$number}"><i class='fas fa-user-edit'></i></a></td>
                    <td header="delete action" class="table-warning text-center"><a style="text-decoration: none;color:red" href="index.php?op=304&employeeNumber={$number}"><i class="material-icons">delete_forever</i></a></td>
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
                                <th scope="col" id="aciton" colspan="2" class="table-warning">Actions</th>
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
        $error = '';

        if ($errors !== []) {
            foreach ($errors as $key => $value) {
                $error .=   <<<HTML
                                <div class="alert alert-danger m-1" role="alert">
                                    <b>{$key}</b>{$value}
                                </div>
                            HTML;
            }
        }

        /*Validation for editing or create new employee with or without previus data  */

        if (isset($_GET['employeeNumber'])) {
            $previusData = self::getEmployeeByNumber($_GET['employeeNumber']);
        }


        if ($previusData === [] && (!isset($previusData['employeeNumber']) || $previusData['employeeNumber'] === '-1')) {
            $previusData = [
                'employeeNumber' => -1,
                'lastName' => '',
                'firstName' => '',
                'extension' => '',
                'email' => '',
                'officeCode' => '',
                'reportsTo' => null,
                'jobTitle' => '',
            ];
        } else if (isset($previusData['employeeNumber']) && $previusData['employeeNumber'] !== '-1') {
            $card_title = "Edit employee";
            $pageData['title'] = "Editing to " . $previusData['firstName'] . ' ' . $previusData['lastName'] . ' - ' . COMPANY_NAME;
        } elseif ((isset($_GET['employeeNumber']) && count($previusData) >= 1)) {
            $previusData = $previusData[0];
            $card_title = "Edit employee";
            $pageData['title'] = "Editing to " . $previusData['firstName'] . ' ' . $previusData['lastName'] . ' - ' . COMPANY_NAME;
        }


        /*Input selection REPORTS TO*/
        $DB = new db_pdo();
        $boss = [['employeeNumber'  => null, 'firstName' => '', 'lastName' => 'Nobody']];
        $boss = array_merge($boss, $DB->table('employees'));
        $bossSelect = <<<HTML
                            <div class="col-md-6">
                                <label for="reportsTo" class="form-label">Reports to:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='fas fa-user-friends'></i></span>
                                    <select class="form-select" id="reportsTo" name="reportsTo">
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
                            </div>
                        HTML;

        /*Input selection OFFICE*/

        $DB = new db_pdo();
        $offices = $DB->table('offices');
        $officesSelect = <<<HTML
                            <div class="col-md-6">
                                <label for="offices" class="form-label">Office:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='fas fa-home'></i></span>
                                    <select class="form-select" name="officeCode" id="office">
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
                            </div>
                        HTML;


        $optionForm = ROUTES['employee_register_verify'];

        /*CONTENT*/
        $content = <<<HTML
                <div class="card m-4">
                    <h5 class="card-header">{$card_title}</h5>
                    <div class="card-body">
                        {$error}
                        <form action="index.php" method="POST" class="row g-3 needs-validation" novalidate>
                            <input value="{$optionForm}" name="op" type="hidden"/>
                            <input value="{$previusData['employeeNumber']}" name="employeeNumber" type="hidden"/>
                            <div class="col-md-6">
                                <label for="fisrtName" class="form-label">Firstname:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='fas fa-list'></i></span>
                                    <input type="text" class="form-control" id="fisrtName" name="firstName" value="{$previusData['firstName']}" required maxlength="50">
                                </div>
                                <div class="invalid-feedback">
                                    Enter a firstname (max 50 char)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Lastname:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='fas fa-list'></i></span>
                                    <input type="text" class="form-control" id="lastName" name="lastName" value="{$previusData['lastName']}" required maxlength="50">
                                </div>
                                <div class="invalid-feedback">
                                    Enter a lastname (max 50 char)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="extension" class="form-label">Extension:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='fas fa-phone-alt'></i></span>
                                    <input type="text" class="form-control" id="extension" name="extension" value="{$previusData['extension']}" required maxlength="10">
                                </div>
                                <div class="invalid-feedback">
                                    Enter a lastname (max 10 char)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="material-icons">email</i></span>
                                    <input type="email" class="form-control" id="email" name="email" value="{$previusData['email']}" required maxlength="100">
                                </div>
                                <div class="invalid-feedback">
                                    You must enter a valid enter
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="jobTitle" class="form-label">Job title:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="material-icons">computer</i></span>
                                    <input type="text" class="form-control" id="jobTitle" name="jobTitle" value="{$previusData['jobTitle']}" required maxlength="50">
                                </div>
                                <div class="invalid-feedback">
                                    You must enter a job title (max 50 char)
                                </div>
                            </div>
                            {$bossSelect}
                            {$officesSelect}
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
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
        $errorMsg = [];
        $firstName = checkInput('firstName', 50);
        $lastName = checkInput('lastName', 50);
        $extension = checkInput('extension', 10);
        $email = checkInput('email', 100);
        $officeCode = checkInput('officeCode', 10);
        $reportsTo = checkInput('reportsTo', 11) === '' ? null : $_POST['reportsTo'];
        $jobTitle = checkInput('jobTitle', 50);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg['email: '] = 'Email format is wrong';
        }
        if ($firstName === '') {
            $errorMsg['Firstname: '] = 'Cannot be empty';
        }
        if ($lastName === '') {
            $errorMsg['Lastname: '] = 'Cannot be empty';
        }
        if ($extension === '') {
            $errorMsg['Extension: '] = 'Cannot be empty';
        }
        if ($officeCode === '') {
            $errorMsg['Office code: '] = 'Cannot be empty';
        }
        if ($jobTitle === '') {
            $errorMsg['Job title: '] = 'Cannot be empty';
        }

        $employeeData = [
            'employeeNumber' => $_POST['employeeNumber'],
            'firstName' => $firstName,
            'lastName' => $lastName,
            'extension' => $extension,
            'email' => $email,
            'officeCode' => $officeCode,
            'reportsTo' => $reportsTo,
            'jobTitle' => $jobTitle,
        ];

        if ($errorMsg !== []) {
            header("HTTP/1.0 400");
            self::registrer($employeeData, $errorMsg);
        }

        if ($employeeData['employeeNumber'] === '-1') {
            $DB = new db_pdo();
            $employeeData['employeeNumber'] = $DB->querySelect('SELECT MAX(employeeNumber) FROM employees;')[0]['MAX(employeeNumber)'];
            $employeeData['employeeNumber']++;
            self::registerNewEmployee($employeeData);
        } else {
            self::editEmployee($employeeData);
        }


        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = "Register a employee - " . COMPANY_NAME;
    }

    private static function registerNewEmployee(array $data)
    {
        $DB = new db_pdo();
        $query = 'INSERT INTO employees (employeeNumber ,firstname, lastname, extension, email, officeCode, reportsTo, jobTitle)
        VALUES (:employeeNumber, :firstName, :lastName, :extension, :email, :officeCode, :reportsTo, :jobTitle);';
        $response = $DB->queryParam($query, $data);
        if ($response->rowCount() === 1) {
            header('HTTP/1.0 201');
            $route = ROUTES['employee-detail'];
            $idEmployee = $data['employeeNumber'];
            header("location: index.php?op=$route&employeeNumber=$idEmployee");
        } else {
            displayError('Employee could not be created', 400);
        }
    }

    private static function editEmployee(array $data)
    {
        $DB = new db_pdo();
        $query =
            'UPDATE employees
                SET firstname = :firstName,
                lastname = :lastName,
                extension = :extension,
                email = :email,
                officeCode = :officeCode,
                reportsTo = :reportsTo,
                jobTitle = :jobTitle
                WHERE employeeNumber = :employeeNumber;';
        $response = $DB->queryParam($query, $data);
        if ($response->rowCount() === 1) {
            header("location: index.php?op=" . ROUTES['employee-detail'] . "&employeeNumber=" . $data['employeeNumber']);
        } else {
            displayError("Employee could not be edited the employee.", 400);
        }
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
                <h5 class="card-header text-center"><b>#{$employee['employeeNumber']}</b> | {$fullname}</h5>
                <div class="card-body">
                    <div>
                        <i class='fas fa-phone'></i> {$employee['extension']}
                    </div>
                    <div>
                        <i class='fas fa-mail-bulk'></i> <a href="mailto:{$employee['email']}">{$employee['email']}</a>
                    </div>
                    <div class=''>
                        <i class="material-icons">computer</i> {$employee['jobTitle']}
                    </div>
                </div>
                <div class="card-body d-flex flex-row justify-content-around">
                    <a href="index.php?op=&employeeNumber=" class="btn btn-primary">Edit</a>
                    <a href="index.php?op=&employeeNumber=" class="btn btn-danger">Delete</a>
                </div>
            </div>
        HTML;

        $pageData = DEFAULT_PAGE_DATA;
        $PageData['description'] = 'Details of ' . $employee['firstName'] . " " . $employee['lastName'];
        $pageData['title'] = $employee['firstName'] . " " . $employee['lastName'] . " - " . COMPANY_NAME;
        $pageData['content'] = $content;

        webpage::render($pageData);
    }

    public static function deleteEmployee()
    {
        if (!isset($_GET['employeeNumber'])) {
            header('location: index.php?op=300');
        }
        $DB = new db_pdo();
        $params = ['employeeNumber' => $_GET['employeeNumber']];
        $response = $DB->queryParam('DELETE FROM employees WHERE employeeNumber = :employeeNumber', $params);

        if ($response->rowCount() === 1) {
            header("location: index.php?op=" . ROUTES['employee_list']);
        } else {
            displayError("Couldn't delete this employee", 409);
        }
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
        header('HTTP/1.0 200');
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
