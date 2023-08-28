<?php 
$files = glob(__DIR__ . "/modules/*.php");
$packages = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/modules.json"), true);
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

include "modules/leravelVar.php";