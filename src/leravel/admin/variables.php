<?php 

$loggedIn = $_SESSION['username'] ?? null;
$route = $_GET['route'] ?? "/";

$icons = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/icons.json"), true);
$adminAccounts = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccounts.json"), true);
$allTools = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/views/tools/tools.json"), true);
$currentUser = isset($_SESSION["username"]) ? $adminAccounts[array_search($_SESSION['username'], array_column($adminAccounts, 'username'))] : null;

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