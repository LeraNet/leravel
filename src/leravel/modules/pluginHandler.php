<?php 

$plugins = glob($_SERVER["DOCUMENT_ROOT"] . "/leravel/plugins/*", GLOB_ONLYDIR);
$pluginList = [];

foreach ($plugins as $plugin) {
    $pluginList[basename($plugin)] = $plugin . "/index.php";
}

function includePlugin($plugin) {
    global $Leravel;
    ob_start();
    require $Leravel["plugins"][$plugin];
    $output = $PluginClass;
    ob_end_clean();

    return $output;
    unset($output);
    unset($PluginClass);
}