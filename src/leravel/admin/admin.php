<?php

$loggedIn = $_SESSION['username'] ?? null;
$route = $_GET['route'] ?? "/";
$icons = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/icons.json"), true);
$adminAccounts = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccounts.json"), true);
$allPerms = [];
$allTools = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/views/tools/tools.json"), true);
$toolPerms = [];

foreach ($allTools as $category) {
    foreach ($category["tools"] as $tool) {
        $toolPerms[$tool["file"]] = $tool["perm"];
    }
}


foreach ($allTools as $category) {
    foreach ($category["tools"] as $tool) {
        if(!in_array($tool["perm"], $allPerms)) {
            $allPerms[] = $tool["perm"];
        }
    }
}

if (!get_loaded_extensions('gd') && !function_exists('gd_info')) {
    echo "You can't use the admin features because gd plugin is not downloaded. We use gd to generate the captcha image. Please download the gd extention. <a href='https://www.php.net/manual/en/image.setup.php'>[ Tutorial From PHP Documentation ]</a>";
    exit;
}

function redirect($path)
{
    header("Location: /?admin&route=$path");
}

function checkPerm($perm) {
    $adminAccounts = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccounts.json"), true);

    $allPerms = [];
    $allTools = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/views/tools/tools.json"), true);

    foreach ($allTools as $category) {
        foreach ($category["tools"] as $tool) {
            if(!in_array($tool["perm"], $allPerms)) {
                $allPerms[] = $tool["perm"];
            }
        }
    }

    $perms = [];
    foreach ($adminAccounts as $account) {
        if ($account["username"] === $_SESSION["username"] && $account["password"] === $_SESSION["password"]) {
            $perms = $account["permissions"];
        }
    }
    if($perm == "NONE") {
        return true;
    }
    if (!$_SESSION["loggedIn"]) {
        return false;
    }
    if(!in_array($perm, $allPerms)) {
        return false;
    }
    if(in_array("ROOT", $perms)) {
        return true;
    }
    if(in_array($perm, $perms)) {
        return true;
    }
    return false;
}

function hasAccess($requiredPerm = "ROOT")
{
    global $loggedIn;
    if (!$_SESSION["loggedIn"]) {
        redirect("login");
        exit;
    }
    if(!checkPerm($requiredPerm)) {
        redirect("noaccess");
        exit;
    }
    
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
}

switch ($route) {
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
    case "stats":
        if ($loggedIn) {
            include "views/stats.php";
        } else {
            redirect("login");
            exit;
        }
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
    case "plugins":
        if ($loggedIn) {
            include "views/plugins.php";
        } else {
            redirect("login");
            exit;
        }
        break;
    case "tool":
        if ($loggedIn) {
            if(!isset($_GET["tool"])) {
                include "views/tools/index.php";
            }else{
                if(!checkPerm($toolPerms[$_GET["tool"]])) {
                    redirect("noaccess");
                    exit;
                }
                include "views/tools/{$_GET["tool"]}.php";
            }
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
    case "noaccess":
        if ($loggedIn) {
            include "views/noaccess.php";
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