<?php

$leravelInfo = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/leravel.json"), true);
$leravelVersion = $leravelInfo["version"];

if (!isset($leravelInfo["lastMotdCheck"]) || $leravelInfo["lastMotdCheck"] < time() - 86400) {
    $leravelInfo["lastMotdCheck"] = time();

    $motd = file_get_contents("https://raw.githubusercontent.com/lera2od/leravel/master/data/motd.txt");
    $news = json_decode(file_get_contents("https://raw.githubusercontent.com/lera2od/leravel/master/data/news.json"), true);

    $leravelInfo["motd"] = $motd;
    $leravelInfo["news"] = json_encode($news);

    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/leravel.json", json_encode($leravelInfo));
} else {
    $news = json_decode($leravelInfo["news"], true);
    $motd = $leravelInfo["motd"];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Admin Index</title>
    <link rel="stylesheet" href="/?admin&route=css&<?= time() ?>">
</head>

<body>
    <?php include "include/header.php" ?>
    <?php include "include/sidebar.php" ?>
    <div class="content">
        <h1>Welcome, <?= $_SESSION["username"] ?>!</h1>
        <?php if (isset($leravelInfo["latestVersion"]) && $leravelInfo["latestVersion"] > $leravelVersion) { ?>
            <?php if(!isset($Leravel["settings"]["update"]["enabled"]) || $Leravel["settings"]["update"]["enabled"] == "1") { ?>
            <div class="newVersionReminderBanner">
                <p>New version of Leravel is available. <a href="/?admin&route=update" class="btn">Update now</a></p>
            </div>
            <br>
            <?php } ?>
        <?php  } ?>
        <div class="news-and-motd">
            <div class="news">
                <h2>Message Of The Day!</h2>
                <p style="margin-left: 10px;"><?= $motd ?></p>
            </div>
            <div class="news">
                <h2>News</h2>
                <div class="news-scroll">
                    <?php foreach ($news as $new) : ?>
                        <div class="new">
                            <h3><?= $new["title"] ?></h3>
                            <p><?= $new["content"] ?></p>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>