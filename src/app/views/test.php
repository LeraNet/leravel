<?php 

$db = new LeravelJsonDatabase();

for ($i = 0; $i < 1000; $i++) {
    $db->set("users.$i.username", "sa");
}