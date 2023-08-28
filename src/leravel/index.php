<?php
session_start();

require("checkStuff.php");

require("modulesLoader.php");

if(function_exists("cleanVariables")) {
    cleanVariables();
}