<?php 

$commandsVocabulary = json_decode(file_get_contents("commands/commands.json"), true);

$commandColor = new textColor("green");
$folderColor = new textColor("red","","bold");

$commands = glob("commands/*.php");
$folders = glob("commands/*", GLOB_ONLYDIR);

printlog("List of commands :");
printlog($folderColor->getColoredString("  Default Commands :"));
printlog($commandColor->getColoredString("    -> ! : Go back to start."));

foreach($commands as $command) {
    $command = explode(".",basename($command))[0];
    $commandDesc = $commandsVocabulary["default"]["$command"] ?? "No description given";
    printlog($commandColor->getColoredString("    -> " . $command . " : $commandDesc"));
}

foreach($folders as $folder) {
    $commands = glob("commands/". basename($folder) . "/*.php");
    printlog($folderColor->getColoredString("  " . basename($folder)));
    foreach($commands as $command) {
        $command = explode(".",basename($command))[0];
        $commandDesc = $commandsVocabulary[basename($folder)]["$command"] ?? "No description given";
        printlog($commandColor->getColoredString("    -> " . $command . " : $commandDesc"));
    }
}