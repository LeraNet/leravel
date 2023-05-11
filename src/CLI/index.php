<?php

require "leraphpcli.php";

$settingsJson = json_decode(file_get_contents(dirname(dirname(__FILE__)) . "/app/settings.json"), true);

$titleColor = new textColor("black", "green", "normal");
$warningColor = new textColor("white", "red", "bold");

startText();
function startText()
{
    global $titleColor;
    global $warningColor;
    global $settingsJson;
    $conn = null;
    if ($settingsJson["database"]["enabled"] == "true" || $settingsJson["database"]["enabled"] == "1") {
        printlog($warningColor->getColoredString("WARNING : The default mysql database is enabled it may casue problems. You can disable it if you don't use a MySql database. To disable the default database use the \"settings set database.enabled 0\" command"));
    }

    if ($settingsJson["admin"]["captcha"] == "0") {
        printlog($warningColor->getColoredString("WARNING : Admin CAPTCHA is disabled. Download the GD extention if you don't have it and use \"settings set admin.captcha 1\" to enable CAPTCHA"));
    }
    printlog($titleColor->getColoredString("                          Leravel CLI                          "));
    printlog($titleColor->getColoredString("                      >help   :   Get the list of commands     "));
    printlog($titleColor->getColoredString("                    >start    :   Start the leravel server     "));
    printlog($titleColor->getColoredString("        >settings [get/set]   :   Get or set any settings      "));
}
waitForInput();

function printlog($text)
{
    echo "$text \n";
}

function waitForInput()
{
    global $titleColor;
    global $warningColor;
    echo ">";
    $command = get_input();
    $args = explode(" ", $command);
    echo chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J';
    if ($command == "!") {
        startText();
    } else {
        $success = false;

        if (file_exists("commands/" . $args[0] . ".php")) {
            printlog($titleColor->getColoredString("                          Leravel CLI                          "));
            printlog($titleColor->getColoredString("                                                               "));
            include "commands/" . $args[0] . ".php";
            $success = true;
        }
        if ($success == false) {
            $folders = glob("commands/*", GLOB_ONLYDIR);
            foreach ($folders as $folder) {
                $folder = basename($folder);
                if (!file_exists("commands/$folder/" . $args[0] . ".php")) {
                    continue;
                } else {
                    printlog($titleColor->getColoredString("                          Leravel CLI                          "));
                    printlog($titleColor->getColoredString("                                                               "));
                    include "commands/$folder/" . $args[0] . ".php";
                    $success = true;
                }
            }
        }


        if ($success == false) {
            printlog($titleColor->getColoredString("                          Leravel CLI                          "));
            printlog($warningColor->getColoredString("WARNING : Command not found! \nFor the list of commands use the \"help\" command!"));
        }
    }
    waitForInput();
}
