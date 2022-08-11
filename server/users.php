<?php

class users
{

    public static function login(string $errorMessage = null)
    {
        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = "Login - " . COMPANY_NAME;
        $PageData['description'] = 'Connect to track to shop and trarck your order and more';

        $error = $errorMessage !== null ? '<p class="error-message">' . $errorMessage . '</p><br/>' : "";

        $content = <<< HTML
        <h2>Please connect</h2>
        <form action="index.php" method="POST" style="width: 350px; border: 1px solid black; margin: 30px auto; padding: 10px; border-radius: 1px;">
        {$error}
        <input type="hidden" value="' . ROUTES['login-verify'] . '" name="op" />
        <lable class="form-label" for="email">Email: </lable><input class="form-control" type="email" id="email" name="email" required maxlength="126" autofocus /><br/>
        <lable class="form-label" for="pw">Password: </label><input class="form-control" type="password" id="pw" name="pw" required maxlength="8" placeholder="max 8 characters" /><br/>
        <div class="buttons-login"><button class="btn btn-primary">Continue</button>
        <button type="button" class="btn btn-danger" onclick="history.back()">Back</button></div>
        </form>
HTML;



        $pageData['content'] = $content;
        webpage::render($pageData);
    }

    public static function register($errorMessage = null, $previous_data = [])
    {
        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = "Registration - " . COMPANY_NAME;
        $PageData['description'] = 'Registration form to join us';
        $error = $errorMessage !== null ? '<p class="alert alert-danger">' . $errorMessage . '</p><br/>' : "";

        $provinces = [
            ['id' => 0, 'code' => 'QC', 'name' => 'Québec'],
            ['id' => 1, 'code' => 'ON', 'name' => 'Ontario'],
            ['id' => 2, 'code' => 'NB', 'name' => 'New-Brunswick'],
            ['id' => 4, 'code' => 'NS', 'name' => 'Nova-Scotia'],
            ['id' => 5, 'code' => 'MN', 'name' => 'Manitoba'],
            ['id' => 6, 'code' => 'SK', 'name' => 'Saskatchewan'],
        ];
        $languages = [
            ['code' => 'en', 'name' =>  'English'],
            ['code' => 'fr', 'name' =>  'French'],
            ['code' => 'other', 'name' =>  'Other']
        ];



        if ($previous_data === []) {
            $previous_data = [
                'fullname' => "",
                "address_one" => "",
                'address_two' => "",
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
        <form  action="index.php" method="POST" style="width: 400px; border: 1px solid black; margin: 30px auto; padding: 30px; border-radius: 1px;">
        {$error}
        <input type="hidden" value="{$op}" name="op" />

        <div>
            <h3>General Information</h3>
                <input class="form-control" type="text" name="fullname" id="fullname" maxlength="50" required placeholder="Fistname and lastname" value="{$previous_data['fullname']}" autofocus />
            <label class="form-label" for="address">Address (Optional):</label>
                <input class="form-control" type="text" name="address_one" id="address" maxlength="255" value="{$previous_data['address_one']}" placeholder="Address Line 1"/>
                <input class="form-control" type="text" name="address_two" id="address" maxlength="255" value="{$previous_data['address_two']}" placeholder="Address Line 2"/>
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

            <!-- button to continue -->
            <button type="submit" class="btn btn-primary mt-3">Continue</button>
        </form>
HTML;

        $pageData['content'] = $content;
        webpage::render($pageData);
    }

    public static function registerVerify()
    {
        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = "Registration - " . COMPANY_NAME;

        $users = [
            ['id' => 0, 'email' => 'abc@test.com', 'pw' => '12345678'],
            ['id' => 1, 'email' => 'def@test.com', 'pw' => '12345678'],
            ['id' => 2, 'email' => 'abc@gmail.com', 'pw' => '11111111'],
        ];

        $fullname = checkInput('fullname', 50);
        $address_one = checkInput('address_one', 255);
        $address_two = checkInput('address_two', 255);
        $city = checkInput('city', 50);
        $province = checkInput('province', 13);
        $postal_code = checkInput('postal_code', 7);
        $language = checkInput('language', 5);
        $other_lang = checkInput('other_lang', 25);
        $email = checkInput('email', 126);
        $pw = checkInput('pw', 8);
        $pw2 = checkInput('pw2', 8);

        $userInfo = $_POST;
        $userInfo['spam_ok'] = isset($_POST['spam_ok']) ? $_POST['spam_ok'] : 0;

        unset($userInfo['pw']);
        unset($userInfo['pw2']);

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $error = 'This email already in use, please select a different email.';
                header("HTTP/1.0 400 $error");
                self::register($error, $userInfo);
                die();
            }
        }

        if ($pw !== $pw2) {
            unset($userInfo['pw']);
            unset($userInfo['pw2']);
            $error = 'Both password must be same';
            header("HTTP/1.0 400 $error");
            self::register($error, $userInfo);
            die();
        }
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

        $users = [
            ['id' => 0, 'email' => 'Yannick@gmail.com', 'pw' => '12345678'],
            ['id' => 1, 'email' => 'Victor@test.com', 'pw' => '11111111'],
            ['id' => 2, 'email' => 'Christian@victoire.ca', 'pw' => '22222222'],
        ];

        foreach ($users as $user) {
            if ($user['email'] === $email && $user['pw'] === $pw) {
                $pageData = DEFAULT_PAGE_DATA;
                $pageData['title'] = $email . " - " . COMPANY_NAME;
                $PageData['description'] = 'Private zone';
                $pageData['content'] = "$email are connected";
                webpage::render($pageData);
                die();
            }
        }
        $error = 'Check your credentials';
        header("HTTP/1.0 401 $error");
        self::login($error);
    }
}
