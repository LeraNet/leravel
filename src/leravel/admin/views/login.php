<?php 

include "include/toast.php";

if(isset($_POST["username"])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $captcha = $_POST['captcha'] ?? '';

    $account = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccount.ini");
    $adminUsername = $account['username'];
    $adminPassword = $account['password'];

    if($username == $adminUsername && $password == $adminPassword) {
        if($Leravel["settings"]["admin"]["captcha"] == "1" || $Leravel["settings"]["admin"]["captcha"] == "true"){
            if($captcha != $_SESSION['captcha']){
                die("wrong captcha");
            }
        }
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        redirect("lsuccess");
        exit;
    } else {
        toast("Invalid username or password", "error");
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

        h1 {
            text-align: center;
            margin: 0;
            padding: 20px 0;
            background-color: #333;
            color: #fff;
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
        
    </style>
</head>
<body>
    <h1>Leravel Login</h1>
    <br>
    <form action="/?admin&route=login" method="post" autocomplete="off">
        <input type="text" name="username" placeholder="username">
        <input type="password" name="password" placeholder="password">
        <?php if($Leravel["settings"]["admin"]["captcha"] == "1" || $Leravel["settings"]["admin"]["captcha"] == "true") :?>
        <div class="captcha">
            <img src="/?admin&route=captcha" alt="captcha">
            <input type="text" name="captcha" placeholder="captcha">
        </div>
        <?php endif; ?>
        <input type="submit" value="Login">
    </form>
</body>
</html>