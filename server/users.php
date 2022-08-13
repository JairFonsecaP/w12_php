<?php

class users
{

    public static function login(string $errorMessage = null)
    {
        if (!isset($_SESSION['loginCount'])) {
            $_SESSION['loginCount'] = 0;
        }

        $_SESSION['loginCount']++;

        if ($_SESSION['loginCount'] > MAX_LOGIN_ATTEMPS) {
            $error = "You have exceeded the maximum number of attempts, please wait";
            $pageData = DEFAULT_PAGE_DATA;
            $pageData['title'] = "Try later - " . COMPANY_NAME;
            $PageData['description'] = 'Try later';
            $pageData['content'] = '<div class="alert alert-danger mt-2 mb-2 ms-1 me-1" role="alert">
                                        Try again later
                                    </div>';
            webpage::render($pageData);
        }

        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = "Login - " . COMPANY_NAME;
        $PageData['description'] = 'Connect to track to shop and trarck your order and more';
        $option = ROUTES['login-verify'];

        $error = $errorMessage !== null ? '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>' : "";

        $content = <<< HTML
        <h2>Please connect</h2>
        <form action="index.php" method="POST" style="width: 350px; border: 1px solid black; margin: 30px auto; padding: 10px; border-radius: 1px;">
        {$error}
        <input type="hidden" value="{$option}" name="op" />
        <lable class="form-label" for="email">Email: </lable><input class="form-control" type="email" id="email" name="email" required maxlength="126" autofocus /><br/>
        <lable class="form-label" for="pw">Password: </label><input class="form-control" type="password" id="pw" name="pw" required maxlength="8" placeholder="max 8 characters" /><br/>
        <div class="buttons-login"><button class="btn btn-primary">Continue</button>
        <button type="button" class="btn btn-danger" onclick="history.back()">Back</button></div>
        </form>
HTML;



        $pageData['content'] = $content;
        webpage::render($pageData);
    }

    public static function register(array $errorsMsg = [], array $previous_data = [])
    {
        $errorLabel = '';
        if ($errorsMsg !== []) {
            foreach ($errorsMsg as $key => $error) {
                $errorLabel .= <<< HTML
                <p class="alert alert-danger"><b>{$key}: </b>{$error}</p><br/>
            HTML;
            }
        }

        $db = new db_pdo();
        $provinces = $db->table("provinces");

        $languages = [
            ['code' => 'en', 'name' =>  'English'],
            ['code' => 'fr', 'name' =>  'French'],
            ['code' => 'other', 'name' =>  'Other']
        ];



        if ($previous_data === []) {
            $previous_data = [
                'fullname' => "",
                "address_line_1" => "",
                'address_line_2' => "",
                'province' => "",
                'city' => "",
                'postal_code' => "",
                'email' => "",
                'language' => "",
                'other_lang' => "",
                'spam_ok' => 1,
            ];
        }

        $provinceSelect = "";
        foreach ($provinces as $province) {
            $selected = ($previous_data['province'] !== "" && $previous_data['province'] === $province['code'] ? " selected " : "");
            $provinceSelect .= <<< HTML
            <option class="form-control" value="{$province['code']}" {$selected}>{$province['name']}</option>
        HTML;
        }

        $languagesRadio = "";
        $attribute_input = $previous_data['language'] === 'other' ? 'required' : '';
        $other_lang_input = <<<HTML
            <input class="form-control col" type="text" name="other_lang" id="other_lang" value="{$previous_data['other_lang']}" {$attribute_input}/>
        HTML;

        foreach ($languages as $language) {
            $other_lang = $language['code'] === 'other' ? $other_lang_input : '';
            $selected = $previous_data['language'] !== "" && $previous_data['language'] === $language['code'] ? "checked" : "";

            $languagesRadio .= <<< HTML
            <div class="form-check">
                <input class="form-check-input col-auto" type="radio" id="{$language['code']}" name="language" value="{$language['code']}" {$selected} required/>
                <div class="row">
                    <label class="form-check-label col-auto" for="{$language['code']}">{$language['name']}</label> {$other_lang}
                </div>
            </div>
        HTML;
        }

        $op = ROUTES['register-verify'];
        $spam_checked = $previous_data["spam_ok"] ? "checked" : "";
        $content = <<< HTML
        <h2>Register as a new user</h2>
        <form enctype="multipart/form-data" action="index.php" method="POST" style="width: 400px; border: 1px solid black; margin: 30px auto; padding: 30px; border-radius: 1px;">
        {$errorLabel}
        <input type="hidden" value="{$op}" name="op" />

        <div>
            <h3>General Information</h3>
                <input class="form-control" type="text" name="fullname" id="fullname" maxlength="50" required placeholder="Fistname and lastname" value="{$previous_data['fullname']}" autofocus />
            <label class="form-label" for="address">Address (Optional):</label>
                <input class="form-control" type="text" name="address_line_1" id="address" maxlength="255" value="{$previous_data['address_line_1']}" placeholder="Address Line 1"/>
                <input class="form-control" type="text" name="address_line_2" id="address" maxlength="127" value="{$previous_data['address_line_2']}" placeholder="Address Line 2"/>
            <label class="form-label" for="city">City:</label>
                <input class="form-control" type="text" name="city" maxlength="50" placeholder="Chambly" value="{$previous_data['city']}"/>
            <label class="form-label" for="province">Province:</label>
                <select class="form-select" name="province" id="province">
                {$provinceSelect}
                </select>
            <label class="form-label" for="postal_code">Postal code:</label>
                <input class="form-control" name="postal_code" id="postal_code" placeholder="ex. A1B-2C3" maxlength="7" value="{$previous_data['postal_code']}" minlength="7" />
        </div>

            <!-- Lenguages section -->
            <div class="mb-4">
                <h5 class="mb-2">Language</h5>
                {$languagesRadio}
            </div>

            <!-- connection info -->

            <h4>Connectin info (required)</h4>
            <div class="mb-2">
                <input class="form-control" type="email" name="email" maxlength="126" required placeholder="Email" value="{$previous_data['email']}">
            </div>
            <div class="mb-2">
                <input class="form-control" type="password" name="pw" maxlength="8" minlength="8" required placeholder="password - must be 8 char." />
            </div>
            <div class="mb-2">
                <input class="form-control" type="password" name="pw2" maxlength="8" minlength="8" required placeholder="repeat password" />
            </div>

            <!-- Check box of spam -->
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="spam_ok" id="spam_ok" value="1" {$spam_checked} />
                <label class="form-check-label" for="spam_ok">I accept to periodically receive information about new products</label>
            </div>

            <!-- file picture-->
            <label class="form-label mt-3" for="my_picture">Select a picture:</label>
            <input class="form-control" type="file" name="my_picture" id="my_picture" value="none" accept=".jpg, .png, .PNG, .gif, .GIF, .jpeg, .JPG"/>

            <!-- button to continue -->
            <button type="submit" class="btn btn-primary mt-3">Continue</button>
        </form>
        HTML;

        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = "Registration - " . COMPANY_NAME;
        $PageData['description'] = 'Registration form to join us';
        $pageData['content'] = $content;
        webpage::render($pageData);
    }

    public static function registerVerify()
    {
        $errorMsg = [];
        $upload_image = Picture_Uploaded_Save_File('my_picture', USER_IMAGES);
        if ($upload_image !== 'OK') {
            $errorMsg['Uploading image'] = $upload_image;
        }

        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = "Registration - " . COMPANY_NAME;

        $fullname = checkInput('fullname', 50);
        $address_line_1 = checkInput('address_line_1', 255);
        $address_line_2 = checkInput('address_line_2', 127);
        $city = checkInput('city', 50);
        $province = checkInput('province', 13);
        $postal_code = checkInput('postal_code', 7);
        $language = checkInput('language', 5);
        $other_lang = checkInput('other_lang', 25);
        $email = checkInput('email', 126);
        $pw = checkInput('pw', 8);
        $pw2 = checkInput('pw2', 8);
        $spam_ok = isset($_POST['spam_ok']) ? $_POST['spam_ok'] : 0;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg['email'] = 'Email format is wrong';
        }

        $user = self::getUserByEmail($email);


        if (count($user) > 0) {
            $errorMsg['email used'] = 'This email already in use, please select a different email.';
        }


        if ($pw !== $pw2) {
            $errorMsg['password'] = 'Both password must be same';
        }

        if ($errorMsg !== []) {
            $userInfo = $_POST;
            $userInfo['spam_ok'] = $spam_ok;
            unset($userInfo['pw']);
            unset($userInfo['pw2']);
            header("HTTP/1.0 400");
            self::register($errorMsg, $userInfo);
        }

        $params = [
            'fullname' => $fullname,
            'pw' => password_hash($pw, PASSWORD_DEFAULT),
            "address_line_1" => $address_line_1,
            'address_line_2' => $address_line_2,
            'province' => $province,
            'city' => $city,
            'postal_code' => $postal_code,
            'email' => $email,
            'language' => $language,
            'other_lang' => $other_lang,
            'level' => 'Client',
            'spam_ok' => $spam_ok,
            'file_name' => $_FILES['my_picture']['name'],
            'customerNumber' => 1,
        ];

        $DB = new db_pdo();
        $result = $DB->queryParam("INSERT into users(email,pw,level,fullname,address_line_1,address_line_2,city,province,postal_code,language,other_lang,spam_ok,picture,customerNumber)
        values(:email,:pw,:level,:fullname,:address_line_1, :address_line_2, :city, :province, :postal_code, :language, :other_lang, :spam_ok,:file_name, :customerNumber)", $params);

        if ($result->rowCount() <> 1) {
            $userInfo = $_POST;
            $userInfo['spam_ok'] = $spam_ok;
            unset($userInfo['pw']);
            unset($userInfo['pw2']);
            $errorMsg['Insert Data'] = "Error inserting the new user.";
            header("HTTP/1.0 400");
            self::register($errorMsg, $userInfo);
        }


        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = 'Account created - ' . COMPANY_NAME;
        $pageData['content'] = '<div class="alert alert-success m-2" role="alert">
                                    Your account was created successful
                                </div>';
        webpage::render($pageData);
    }

    public static function loginVerify()
    {
        $pw = checkInput('pw', 8);
        $email = checkInput('email', 126);
        $error = "";

        if ($pw == "" || $email == "") {
            $error = "Missing email or password";
            self::login($error);
        }

        $user = self::getUserByEmail($email);

        if (count($user) === 1 && password_verify($pw, $user[0]["pw"])) {
            $_SESSION['email'] = $email;
            $_SESSION['picture'] = $user[0]['picture'];

            logVisitor();

            $pageData = DEFAULT_PAGE_DATA;
            $pageData['title'] = $email . " - " . COMPANY_NAME;
            $PageData['description'] = 'Private zone';
            $pageData['content'] = "$email are connected";
            webpage::render($pageData);
        }

        $error = 'Check your credentials';
        header("HTTP/1.0 401 $error");
        self::login($error);
    }

    public static function logout()
    {
        unset($_SESSION['email']);
        unset($_SESSION['loginCount']);
        unset($_SESSION['picture']);
        //$_SESSION = [];
        header('location: index.php');
    }

    public static function getUserByEmail(string $email)
    {
        $db_pdo = new db_pdo();
        return $db_pdo->querySelect("SELECT * FROM users WHERE email = '$email' LIMIT 1");
    }
}
