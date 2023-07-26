<?php
session_start();

$files = glob(__DIR__ . "/*.php");
$packages = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/packages.json"), true);
$using = [];

foreach ($files as $file) {
    $sadeName = explode(".", basename($file))[0];
    if ($sadeName == "index" || $sadeName == "leravelVar") {
        continue;
    }
    if(isset($packages[$sadeName]) && $packages[$sadeName] == false) {
        continue;
    }
    $using[] = $sadeName;
    require_once($file);
}

include "leravelVar.php";

if(function_exists("cleanVariables")) {
    cleanVariables();
}