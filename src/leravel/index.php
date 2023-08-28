<?php
session_start();

require("modulesLoader.php");

if(function_exists("cleanVariables")) {
    cleanVariables();
}