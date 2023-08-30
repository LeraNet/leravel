<?php

include "include/toast.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["username"], $_POST["password"])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($_SESSION["captchaPassed"] === false) {
            toast("Wrong captcha", "error");
        } else {
            if ($_SESSION["captchaPassed"] === true) {
                $adminAccounts = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccounts.json"), true);

                $account = $adminAccounts[array_search($username, array_column($adminAccounts, "username"))];

                if (isset($account) && $account["username"] === $username && $account["password"] === $password) {
                    $_SESSION["username"] = $username;
                    $_SESSION["password"] = $password;
                    $_SESSION["loggedIn"] = true;
                    $_SESSION["permissions"] = $account["permissions"];
                    redirect("/&loginsuccess");
                    toast("Logged in successfully", "success");
                }

                toast("Wrong username or password", "error");
            } else {
                toast("Wrong username or password", "error");
            }
        }
    } else {
        toast("Wrong username or password", "error");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Login</title>
    <style>
        body {
            background-image: url(https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YWJzdHJhY3R8ZW58MHx8MHx8fDA%3D&w=1000&q=80);
            background-size: cover;
            background-position: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #333;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .half {
            width: 25vw;
            height: 50vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border: 2px solid #3f3f3f;
            border-left: none;
            border-radius: 0 15px 15px 0;
        }

        form {
            width: 25vw;
            height: 50vh;
            padding: 20px;
            background-color: #fff;
            border: 2px solid #ccc;
            border-right: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
            border-radius: 15px 0 0 15px;
        }

        input {
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
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }

        .captcha img {
            max-width: 200px;
            width: 100%;
        }

        .toast {
            background-color: #fff;
            border: 1px solid #ccc;
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

        dialog {
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            display: none;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        dialog[open] {
            display: flex;
        }

        dialog[open] .captcha {
            width: 25vw;
            height: 50vh;
            padding: 20px;
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }

        dialog[open] input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        dialog[open] input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }

        .leracaptcha {
            background-color: #ccc;
            border-radius: 5px;
            padding: 5px;
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .leracaptcha p {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }

        .leracaptcha button {
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <form action="/?admin&route=login" method="post" autocomplete="off">
        <h2>Login to Leravel Admin</h2>
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <?php if ($Leravel["settings"]["admin"]["captcha"] == "1" || $Leravel["settings"]["admin"]["captcha"] == "true") : ?>
            <div class="leracaptcha">
                <p onclick="document.querySelector('dialog').showModal()">LeraCaptcha</p>
                <button type="button" onclick="document.querySelector('dialog').showModal()" id="captchabutton">❌</button>
            </div>
        <?php endif; ?>
        <input type="submit" value="Login">
    </form>
    <div class="half">
        <header>
            <h1>Leravel Admin</h1>
        </header>
        <p>Default admin panel for Leravel</p>
    </div>
    <dialog>
        <div class="captcha">
            <h2>leracaptcha</h2>
            <img src="/?admin&route=captcha&<?= time() ?>" alt="Captcha">
            <input type="text" name="captcha" placeholder="Captcha" id="captchaValue">
            <input type="submit" value="Submit" id="checkCaptcha">
        </div>
    </dialog>

    <script>
        document.querySelector("#checkCaptcha").addEventListener("click", () => {
            const captchaValue = document.querySelector("#captchaValue").value;
            const formData = new FormData();
            formData.append("captcha", captchaValue);
            fetch("/?admin&route=captcha", {
                method: "POST",
                body: formData
            }).then(res => res.text()).then(res => {
                if (res == "true") {
                    document.querySelector("dialog").close();
                    document.querySelector("#captchabutton").disabled = true;
                    document.querySelector("#captchabutton").innerText = "✅";
                } else {
                    alert("Wrong captcha");
                    document.querySelector("dialog img").src = "/?admin&route=captcha&" + new Date().getTime();
                }
            });
        });

        document.querySelector("dialog").addEventListener("click", (e) => {
            if (e.target == document.querySelector("dialog")) {
                document.querySelector("dialog").close();
            }
        });

        document.querySelector("form").addEventListener("submit", (e) => {
            if (document.querySelector("#captchabutton").disabled == false) {
                e.preventDefault();
                alert("Please complete the captcha");
            }
        });
    </script>
</body>

</html>