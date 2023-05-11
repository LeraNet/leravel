<?php 

$settingsJson = json_decode(file_get_contents(dirname(dirname(dirname( dirname(__FILE__) ))) . "/app/settings.json"), true);

$action = $args[1] ?? null;
$from = $args[2] ?? null;
$to = $args[3] ?? null;

if(!isset($action)) {
    printlog("Set an action!");
    waitForInput();
}

switch ($action) {
    case 'get':
        print_r($settingsJson);
        break;
    case "set":
        $successColor = new textColor("green","black","bold");
        if(isset($from) && isset($to)) {
            $category = explode("." , $from)[0];
            $subject = explode(".", $from)[1];
            if($category == null || $subject == null) {
                printlog("Example : setting set admin.enabled 0");
            }
            $settingsJson[$category][$subject] = $to;
            file_put_contents(dirname(dirname(dirname(__FILE__))) . "/app/settings.json" , json_encode($settingsJson));
            printlog($successColor->getColoredString("Settings changed successfully!"));
            printlog("Changed \"$from\" to \"$to\"");
        }else{
            printlog("Example : setting set admin.enabled 0");
        }
        break;
    default:
        printlog("Command not found!");
        break;
}
