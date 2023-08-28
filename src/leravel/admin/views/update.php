<?php
require "include/toast.php";

$leravelInfo = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/json/leravel.json"), true);
$leravelVersion = $leravelInfo["version"];

$url = "https://api.github.com/repos/lera2od/leravel/releases/latest";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36",
    "Accept: application/vnd.github+json"
));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$latestVersion = $data["tag_name"];
$latestVersion = str_replace("v", "", $latestVersion);

$changelog = $data["body"];

$text = preg_replace('/^- (.*)$/m', '<li>$1</li>', $changelog);

$text = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $text);
$text = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $text);
$text = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $text);
$text = preg_replace('/^#### (.*)$/m', '<h4>$1</h4>', $text);
$text = preg_replace('/^##### (.*)$/m', '<h5>$1</h5>', $text);
$text = preg_replace('/^###### (.*)$/m', '<h6>$1</h6>', $text);
$text = preg_replace('/^- (.*)$/m', '<li>$1</li>', $text);
$text = preg_replace('/^(\*|\+) (.*)$/m', '<li>$2</li>', $text);
$text = preg_replace('/(<li>.*<\/li>)/', '<ul>$0</ul>', $text);
$text = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $text);
$text = preg_replace('/\*(.*?)\*/s', '<em>$1</em>', $text);
$text = preg_replace('/```(.*?)```/s', '<code>$1</code>', $text);
$text = preg_replace('/^(?!<h|<ul|<code|<strong|<em)(.*)$/m', '<p>$1</p>', $text);
$changelog = $text;

if (isset($_GET["update"]) && $_GET["update"] == "true") {
    if ($latestVersion > $leravelVersion) {
        $_SESSION["Update"] = true;
        header("Location: /?update");
    }
}

if (isset($_GET["success"])) {
    if ($_SESSION["Update"] == false) {
        toast("Update Complated", "success");
    } else {
        toast("Update Failed", "error");
    }
    $_SESSION["Update"] = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Update Checker</title>
    <link rel="stylesheet" href="/?admin&route=css">
</head>

<body>
    <?php include "include/header.php" ?>
    <?php include "include/sidebar.php" ?>
    <div class="content">
        <h1><img src="<?= $icons["update"] ?>">Update</h1>
        <div class="tab-content">
            <?php if (isset($latestVersion)) : ?>
                <h1>Leravel v<?= $leravelVersion ?></h1>
                <p>Current version: <?= $leravelVersion ?></p>
                <p>Latest version: <?= $latestVersion ?></p>
                <?php if ($latestVersion > $leravelVersion) { ?>
                    <h2>Update logs :</h2>
                    <div class="update-logs">
                        <?= $changelog ?>
                    </div>
                    <a href="/?admin&route=update&update=true" class="btn">Update Using Leravel Updater</a>
                    <a href="<?= $data["html_url"] ?>" class="btn">Update Manually</a>
                <?php } else { ?>
                    <p>You are up to date!</p>
                <?php } ?>
            <?php else : ?>
                <p>Unable to fetch latest version</p>
            <?php endif; ?>
        </div>
    </div>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/styles/default.min.css">
    <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/highlight.min.js"></script>
</body>

</html>