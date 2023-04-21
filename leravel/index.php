<?php
session_start();

require "error.php";

$Leravel = array();

$Leravel["settings"] = json_decode(file_get_contents("app/settings.json"), true);
$Leravel["current_lang"] = $_COOKIE["leravel_lang"] ?? $Leravel["settings"]["lang"]["default"];
$Leravel["lang"] = json_decode(file_get_contents("app/localization/" . $Leravel["settings"]["lang"][$Leravel["current_lang"]] . ".json"), true);

if ($Leravel["settings"]["database"]["enabled"] == "true" || $Leravel["settings"]["database"]["enabled"] == "1") {
    $Leravel["conn"] = new Mysqli($Leravel["settings"]["database"]["host"], $Leravel["settings"]["database"]["username"], $Leravel["settings"]["database"]["password"], $Leravel["settings"]["database"]["database"]);

    if ($Leravel["conn"]->connect_error) {
        die("Connection failed: " . $Leravel["conn"]->connect_error);
    }
}

require "template.php";
require "asset.php";
require "router.php";
