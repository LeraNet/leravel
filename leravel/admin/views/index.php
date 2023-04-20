<?php 

$leravelInfo = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/leravel.json"), true);
$leravelVersion = $leravelInfo["version"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Admin Index</title>
    <link rel="stylesheet" href="/?admin&route=css">
</head>

<body>
    <?php include "include/header.php" ?>
    <?php include "include/sidebar.php" ?>
    <div class="content">
        <h1>Welcome, <?= $_SESSION["username"] ?>!</h1>
        <?php if(isset($leravelInfo["latestVersion"]) && $leravelInfo["latestVersion"] > $leravelVersion){ ?>
            <div class="newVersionReminderBanner">
                <p>New version of Leravel is available. <a href="/?admin&route=update" class="btn">Update now</a></p>
            </div>
        <?php } ?>
    </div>
</body>

</html>