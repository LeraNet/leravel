<?php

function redirect($path)
{
    header("Location: /?admin&route=$path");
}

$loggedIn = $_SESSION['loggedIn'] ?? false;
$route = $_GET['route'] ?? "";

if ($route == "") {
    redirect("/");
    exit;
}

if ($route != "login" && $route != "captcha") {
    if ($loggedIn == null) {
        redirect("login");
        exit;
    }

    if (!isset($_SESSION["username"]) || !isset($_SESSION["password"])) {
        session_destroy();
        redirect("login");
        exit;
    }

    $account = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccount.ini");
    if ($account["username"] != $_SESSION["username"] || $account["password"] != $_SESSION["password"]) {
        session_destroy();
        redirect("login");
        exit;
    }
}

switch ($route) {
    case "lsuccess":
        if ($loggedIn) {
            include "views/loginSuccess.php";
        }else{
            redirect("login");
            exit;
        }
        break;
    case "login":
        if ($loggedIn) {
            redirect("/");
            exit;
        }
        include "views/login.php";
        break;
    case "captcha":
        include "views/captcha.php";
        break;
    case "logout":
        session_destroy();
        redirect("login");
        exit;
    case "css":
        if ($loggedIn) {
            header("Content-Type: text/css");
            include "views/style.css";
        } else {
            redirect("login");
            exit;
        }
        break;
    case "database":
        if ($loggedIn) {
            include "views/database.php";
        } else {
            redirect("login");
            exit;
        }
        break;
    case "localization":
        if ($loggedIn) {
            include "views/localization.php";
        } else {
            redirect("login");
            exit;
        }
        break;
    case "settings":
        if ($loggedIn) {
            include "views/settings.php";
        } else {
            redirect("login");
            exit;
        }
        break;
    case "update":
        if ($loggedIn) {
            include "views/update.php";
        } else {
            redirect("login");
            exit;
        }
        break;
    case "/":
        if ($loggedIn) {
            include "views/index.php";
        } else {
            redirect("login");
            exit;
        }
        break;
    default:
        if ($loggedIn) {
            include "views/404.php";
        } else {
            redirect("login");
            exit;
        }
        break;
}
