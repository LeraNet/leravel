<?php
session_start();

$files = glob(__DIR__ . "/*.php");
foreach ($files as $file) {
    if ($file != __FILE__) {
        require_once($file);
    }
}