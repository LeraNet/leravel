<?php
session_start();
error_reporting(0);

$files = glob(__DIR__ . "/*.php");
foreach ($files as $file) {
    if ($file != __FILE__) {
        require_once($file);
    }
}