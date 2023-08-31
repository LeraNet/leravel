<?php 
$experimentsFile = $_SERVER["DOCUMENT_ROOT"] . '/leravel/admin/experiments.json';
$modulesFile = $_SERVER["DOCUMENT_ROOT"] . "/app/modules.json";
$activeExperiments = [];
$activeModules = [];

if (file_exists($experimentsFile)) {
    $activeExperiments = json_decode(file_get_contents($experimentsFile), true);
}

if (file_exists($modulesFile)) {
    $activeModules = json_decode(file_get_contents($modulesFile), true);
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

function checkLogin() 
{
    global $loggedIn;
    $username = $_SESSION["username"] ?? null;
    $password = $_SESSION["password"] ?? null;

    $adminAccounts = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccounts.json"), true);

    $spossedUser = $adminAccounts[array_search($username, array_column($adminAccounts, 'username'))] ?? null;

    if(!isset($username) || !isset($password) || !isset($spossedUser)) {
        unset($_SESSION["username"]);
        unset($_SESSION["password"]);
        unset($_SESSION["loggedIn"]);
        redirect("login");
        exit;
    }

    if (!$_SESSION["loggedIn"]) {
        unset($_SESSION["username"]);
        unset($_SESSION["password"]);
        unset($_SESSION["loggedIn"]);
        redirect("login");
        exit;
    }
    
    if ($spossedUser["password"] !== $password) {
        unset($_SESSION["username"]);
        unset($_SESSION["password"]);
        unset($_SESSION["loggedIn"]);
        redirect("login");
        exit;
    }
}

function isExperimentActive($experiment, $activeExperiments)
{
    if (in_array($experiment, $activeExperiments) && $activeExperiments[$experiment] == true) {
        return true;
    } else {
        return false;
    }
}

function isModuleActive($module, $activeModules)
{
    if (in_array($module, $activeModules) && $activeModules[$module] == true) {
        return true;
    } else {
        return false;
    }
}