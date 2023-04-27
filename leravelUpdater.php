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

if(isset($_GET["update"]) && $_GET["update"] == "true"){
    if($latestVersion > $leravelVersion){
        $zip = file_get_contents($data["assets"][0]["browser_download_url"]);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel.zip", $zip);
        $zip = new ZipArchive;
        $res = $zip->open($_SERVER["DOCUMENT_ROOT"] . "/leravel.zip");
        if ($res === TRUE) {
            $zip->extractTo($_SERVER["DOCUMENT_ROOT"] . "/leravel");
            $zip->close();
            unlink($_SERVER["DOCUMENT_ROOT"] . "/leravel.zip");
            $_SESSION["Update"] = true;
            header("Location: /?update");
        } else {
            echo 'failed';
        }
    }
}
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
            <h1>leravel admin</h1>
        </a>
        <div>
            <a href="/?admin&route=logout">Logout</a>
        </div>
    </header>
    <div class="sidebar">
        <ul>
            <li><a href="/?admin&route=/">Home</a></li>
            <li><a href="/?admin&route=database">Database</a></li>
            <li><a href="/?admin&route=localization">Localization</a></li>
            <li><a href="/?admin&route=settings">Settings</a></li>
            <li><a href="/?admin&route=update">Update</a></li>
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
            <h2>Are you sure you want to update?</h2>
            <p>Current Version: <?php echo $leravelVersion ?></p>
            <p>Latest Version: <?php echo $latestVersion ?></p>
            <form action="" method="post">
                <input type="hidden" name="update" value="true">
                <button type="submit">Update</button>
            </form>
        </div>
    </div>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/styles/default.min.css">
    <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/highlight.min.js"></script>
</body>

</html>