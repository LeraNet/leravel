<?php 

$plugins = glob(__DIR__ . "/plugins/*", GLOB_ONLYDIR);
$pluginList = [];

foreach ($plugins as $plugin) {
    $pluginList[basename($plugin)] = $plugin . "/index.php";
}

function includePlugin($plugin) {
    global $Leravel;
    include $Leravel["plugins"][$plugin];
}