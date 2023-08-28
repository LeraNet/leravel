<?php

printlog("Leravel Server is Starting...");
printlog("---------------------------------------------------------------");

chdir('..');
system("php -S localhost:8000 index.php");