<?php
$leravelInfo = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/leravel.json"), true);
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Settings</title>
    <link rel="stylesheet" href="/?admin&route=css">
</head>

<body>
    <header>
        <a href="/?admin">
            <h1><img src="<?= $icons["admin"] ?>">leravel admin</h1>
        </a>
        <div>
            <a href="/?admin&route=logout">Logout</a>
        </div>
    </header>
    <div class="sidebar">
        <ul>
            <li><a href="/?admin&route=/"><img src="<?= $icons["home"] ?>">Home</a></li>
            <li><a href="/?admin&route=database"><img src="<?= $icons["database"] ?>">Database</a></li>
            <li><a href="/?admin&route=localization"><img src="<?= $icons["localization"] ?>">Localization</a></li>
            <li><a href="/?admin&route=settings"><img src="<?= $icons["settings"] ?>">Settings</a></li>
            <li><a href="/?admin&route=update"><img src="<?= $icons["update"] ?>">Update</a></li>
        </ul>
    </div>

    <div class="mobile-sidebar">
        <select name="" id="selectPage" onchange="navigate()">
            <option value="">Select Page</option>
            <option value="/?admin&route=/">Home</option>
            <option value="/?admin&route=database">Database</option>
            <option value="/?admin&route=localization">Localization</option>
            <option value="/?admin&route=settings">Settings</option>
            <option value="/?admin&route=update">Update</option>
        </select>
    </div>

    <script>
        let selectPage = document.querySelector("#selectPage");

        function navigate() {
            window.location.href = selectPage.value;
        }
    </script>

    <?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/checkUpdate.php" ?>
    <div class="content">
        <h1>Update</h1>
        <div class="tab-content">
            <?php if (isset($latestVersion)) : ?>
                <h1>Leravel v<?= $leravelVersion ?></h1>
                <p>Current version: <?= $leravelVersion ?></p>
                <p>Latest version: <?= $latestVersion ?></p>
                <?php if ($latestVersion > $leravelVersion) { ?>
                    <div class="update-logs">
                        <h2>Update logs</h2>
                        <pre><code class="json"><?= $data["body"] ?></code></pre>
                    </div>
                    <a href="//github.com/lera2od/leravel/releases/latest" target="_blank" class="btn">Download</a>
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