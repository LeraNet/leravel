<?php

if (!get_loaded_extensions('gd') && !function_exists('gd_info')) {
    echo "You can't use the admin features because gd plugin is not downloaded. We use gd to generate the captcha image. Please download the gd extention. <a href='https://www.php.net/manual/en/image.setup.php'>[ Tutorial From PHP Documentation ]</a>";
    exit;
}

require $_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/functions.php";
require $_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/variables.php";


switch ($route) {
    case "login":
        if (isset($_SESSION["loggedIn"])) {
            redirect("/");
            exit;
        }
        include "views/login/login.php";
        break;

    case "captcha":
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            include "views/login/captcha.php";
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["captcha"])) {
                if ($_SESSION["captcha"] == $_POST["captcha"]) {
                    $_SESSION["captchaPassed"] = true;
                    echo "true";
                } else {
                    $_SESSION["captchaPassed"] = false;
                    unset($_SESSION["captcha"]);
                    echo "false";
                }
            }
        }
        break;

    case "logout":
        checkLogin();
        session_destroy();
        redirect("login");
        exit;

    case "stats":
        checkLogin();
        include "views/base/stats.php";
        break;

    case "database":
        checkLogin();
        include "views/base/database.php";
        break;

    case "localization":
        checkLogin();
        include "views/base/localization.php";
        break;

    case "settings":
        checkLogin();
        include "views/base/settings.php";
        break;

    case "plugins":
        checkLogin();
        include "views/base/plugins.php";
        break;

    case "update":
        checkLogin();
        include "views/base/update.php";
        break;

    case "tool":
        checkLogin();
        if (!isset($_GET["tool"])) {
            include "views/tools/index.php";
        } else {
            if (!checkPerm($toolPerms[$_GET["tool"]])) {
                redirect("noaccess");
                exit;
            }
            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/tools/{$_GET["tool"]}.php")) {
                include "views/404.php";
                exit;
            }
            include "views/tools/{$_GET["tool"]}.php";
        }
        break;

    case "noaccess":
        checkLogin();
        include "views/noaccess.php";
        break;

    case "css":
        checkLogin();
        header("Content-Type: text/css");
        include "views/style.css";
        break;
    case "/":
        checkLogin();
        include "views/index.php";
        break;
    default:
        include "views/404.php";
        break;
}
