<?php

if (!get_loaded_extensions('gd') && !function_exists('gd_info')) {
    echo "You can't use the admin features because gd plugin is not downloaded. We use gd to generate the captcha image. Please download the gd extention. <a href='https://www.php.net/manual/en/image.setup.php'>[ Tutorial From PHP Documentation ]</a>";
    exit;
}

require $_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/functions.php";

$loggedIn = $_SESSION['username'] ?? null;
$route = $_GET['route'] ?? "/";

$icons = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/icons.json"), true);
$adminAccounts = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccounts.json"), true);
$allTools = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/views/tools/tools.json"), true);

$allPerms = [];
$toolPerms = [];

foreach ($allTools as $category) {
    foreach ($category["tools"] as $tool) {
        $toolPerms[$tool["file"]] = $tool["perm"];
        if (!in_array($tool["perm"], $allPerms)) {
            $allPerms[] = $tool["perm"];
        }
    }
}

switch ($route) {
    case "login":
        if (isset($_SESSION["loggedIn"])) {
            redirect("/");
            exit;
        }
        include "views/login.php";
        break;
    case "captcha":
        if($_SERVER["REQUEST_METHOD"] == "GET") {
            include "views/captcha.php";
        }else if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    case "stats":
        checkLogin();
        include "views/stats.php";
        break;
    case "logout":
        checkLogin();
        session_destroy();
        redirect("login");
        exit;
    case "css":
        checkLogin();
        header("Content-Type: text/css");
        include "views/style.css";
        break;
    case "database":
        checkLogin();
        include "views/database.php";
        break;
    case "localization":
        checkLogin();
        include "views/localization.php";
        break;
    case "settings":
        checkLogin();
        include "views/settings.php";
        break;
    case "plugins":
        checkLogin();
        include "views/plugins.php";
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
            include "views/tools/{$_GET["tool"]}.php";
        }
        break;
    case "update":
        checkLogin();
        include "views/update.php";
        break;
    case "noaccess":
        checkLogin();
        include "views/noaccess.php";
        break;
    case "/":
        checkLogin();
        include "views/index.php";
        break;
    default:
        include "views/404.php";
        break;
}
