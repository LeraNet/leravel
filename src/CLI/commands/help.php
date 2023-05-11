<?php 

$commands = glob("commands/*");
printlog("List of commands :");
printlog("  -> !");
foreach($commands as $command) {
    printlog("  -> " . explode(".",basename($command))[0]);
}