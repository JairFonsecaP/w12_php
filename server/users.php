<?php

class users
{

    public static function login(string $errorMessage = null)
    {
        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = "Login - " . COMPANY_NAME;
        $PageData['description'] = 'Connect to track to shop and trarck your order and more';


        $content = '<h2>Please connect</h2>';
        $content .= '<form action="index.php" method="POST" style="width: 300px; border: 1px solid black; margin: 30px auto; padding: 10px; border-radius: 1px;">';
        $content .= $errorMessage !== null ? '<p class="error-message">' . $errorMessage . '</p><br/>' : "";
        $content .= '<input type="hidden" value="2" name="op" />';
        $content .= 'Email <input type="email" name="email" required maxlength="126" autofocus /><br/>';
        $content .= 'Password <input type="password" name="pw" required maxlength="8" placeholder="max 8 characters" /><br/>';
        $content .= '<div class="buttons-login"><button>Continue</button>';
        $content .= '<button type="button" onclick="history.back()">Back</button></div>';
        $content .= '</form>';

        $pageData['content'] = $content;
        webpage::render($pageData);
    }

    public static function register($errorMessage = null, $previous_data = [])
    {
        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = "Registration - " . COMPANY_NAME;
        $PageData['description'] = 'Registration form to join us';
        $error = $errorMessage !== null ? '<p class="alert primary">' . $errorMessage . '</p><br/>' : "";

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
                'other_lang' => ""
            ];
        }

        $provinceSelect = "";
        foreach ($provinces as $province) {
            $provinceSelect .= '<option value="' . $province['code'] . '" ' . ($previous_data['province'] !== "" && $previous_data['province'] === $province['code'] ? " selected " : "") . '>' . $province['name'] . "</option>";
        }

        $languagesRadio = "";
        foreach ($languages as $language) {
            $selected = $previous_data['language'] !== "" && $previous_data['language'] === $language['code'] ? " checked " : "";
            $languagesRadio .= '<br/><input type="radio" id="' . $language['code'] . '" name="language" value="' . $language['code'] . '" ' . $selected . '/><label for="' . $language['code'] . '">' . $language['name'] . '</label>';
        }

        $content = <<< HTML
        <h2>Registration</h2>
        <form class="form control" action="index.php" method="POST" style="width: 300px; border: 1px solid black; margin: 30px auto; padding: 10px; border-radius: 1px;">
        {$error}
        <input type="hidden" value="4" name="op" />
            <label for="fullname">Name:</label>
                <input type="text" name="fullname" id="fullname" maxlength="50" required placeholder="Fistname and lastname" value="{$previous_data['fullname']}" autofocus /><br/>
            <label for="address">Address:</label>
                <input type="text" name="address_one" id="address" maxlength="255" value="{$previous_data['address_one']}" placeholder="Address Line 1"/><br/>
                <input type="text" name="address_two" id="address" maxlength="255" value="{$previous_data['address_two']}" placeholder="Address Line 2"/><br/>
            <label for="city">City:</label>
                <input type="text" name="city" maxlength="50" placeholder="Chambly" value="{$previous_data['city']}"/><br/>
            <label for="province">Province:</label>
                <select name="province" id="province">
                {$provinceSelect}
                </select><br/>
            <label for="postal_code">Postal code:</label>
                <input name="postal_code" id="postal_code" placeholder="ex. A1B-2C3" maxlength="7" value="{$previous_data['postal_code']}" minlength="7" /><br/>
            <h3>Language</h3>
            $languagesRadio <input type="text" name="other_lang" value="{$previous_data['other_lang']}"/>
            <h2>Connectin info (required)</h2>
            <input type="email" name="email" maxlength="126" required placeholder="Email" value="{$previous_data['email']}"><br/>
            <input type="password" name="pw" maxlength="8" minlength="8" required placeholder="password - must be 8 char." /><br/>
            <input type="password" name="pw2" maxlength="8" minlength="8" required placeholder="repeat password" /><br/>
            <input type="checkbox" name="spam_ok" id="spam_ok" value="1" checked /><label for="spam_ok">I accept to periodically receive information about new products</label><br/>
            <button type="submit" class="btn btn-primary">Continue</button>
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
        $email = checkInput('email', 126);
        $language = checkInput('language', 5);
        $pw = checkInput('pw', 8);
        $pw2 = checkInput('pw2', 8);

        // $error = "";

        // if ($pw == "" || $email == "") {
        //     $error = "Missing email or password";
        //     self::login($error);
        // }
        $userInfo = $_POST;
        unset($userInfo['pw']);
        unset($userInfo['pw2']);

        if ($pw !== $pw2) {
            $error = 'Both password must be same';
            header("HTTP/1.0 400 $error");
            self::register($error, $userInfo);
            die();
        }

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $error = 'This email already in use, please select a different email.';
                header("HTTP/1.0 400 $error");
                self::register($error, $userInfo);
                die();
            }
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
