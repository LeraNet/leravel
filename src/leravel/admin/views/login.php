<?php

include "include/toast.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["username"], $_POST["password"], $_POST["captcha"])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $captcha = $_POST['captcha'];

        if ($captcha == $_SESSION["captcha"]) {
            $adminAccounts = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccounts.json"), true);
            
            foreach ($adminAccounts as $account) {
                if ($account["username"] === $username && $account["password"] === $password) {
                    $_SESSION["username"] = $username;
                    $_SESSION["password"] = $password;
                    $_SESSION["loggedIn"] = true;
                    $_SESSION["permissions"] = $account["permissions"];
                    redirect("/");
                    toast("Logged in successfully", "success");
                }
                echo $account["username"] . " " . $account["password"] . "<br>";
            }

            toast("Wrong username or password", "error");
        } else {
            toast("Wrong captcha", "error");
        }
    } else {
        toast("Missing fields", "error");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Login</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #333;
        }

        header {
            padding: 20px;
            background-color: #333;
            color: #fff;
            border-radius: 10px;
        }

        header h1 {
            margin: 0;
            text-align: center;
        }

        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        input {
            display: block;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }

        .captcha {
            display: flex;
            flex-direction: row;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .captcha img {
            width: 100%;
            max-width: 200px;
        }

        .captcha input {
            width: 100%;
            max-width: 200px;
        }


        .toast {
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 4px;
            padding: 20px;
            margin: 20px;
            position: fixed;
            top: -100px;
            right: 20px;
            z-index: 9999;
            transition: all 0.5s ease-in-out;
        }

        .toast-success {
            background-color: #d3ffab;
            border: 1px solid #a3ff7b;
            border-radius: 4px;
        }

        .toast-error {
            background-color: #fd9696;
            border: 1px solid #ff9999;
            border-radius: 4px;
        }

        .toast-info {
            background-color: #cdedfc;
            border: 1px solid #b3ebff;
            border-radius: 4px;
        }

        .toast-close:hover {
            background-color: #cccccc;
        }
    </style>
</head>

<body>
    <header>
        <h1>Leravel Login</h1>
    </header>
    <br>
    <form action="/?admin&route=login" method="post" autocomplete="off">
        <input type="text" name="username" placeholder="username">
        <input type="password" name="password" placeholder="password">
        <?php if ($Leravel["settings"]["admin"]["captcha"] == "1" || $Leravel["settings"]["admin"]["captcha"] == "true") : ?>
            <div class="captcha">
                <img src="/?admin&route=captcha" alt="captcha">
                <input type="text" name="captcha" placeholder="captcha">
            </div>
        <?php endif; ?>
        <input type="submit" value="Login">
    </form>
</body>

</html>