<?php
$leravelInfo = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/leravel.json"), true);
$leravelVersion = $leravelInfo["version"] ?? "Leravel Version Not Found";

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

$latestVersion = $data["tag_name"] ?? "GitHub API Rate Limit Exceeded";
$latestVersion = str_replace("v", "", $latestVersion);

if (isset($_POST["update"]) && $_POST["update"] == "true") {
    $zip = file_get_contents($data["assets"][1]["browser_download_url"]);
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel.zip", $zip);
    $zip = new ZipArchive;
    $res = $zip->open($_SERVER["DOCUMENT_ROOT"] . "/leravel.zip");
    if ($res === TRUE) {
        $zip->extractTo($_SERVER["DOCUMENT_ROOT"] . "/");
        $zip->close();
        unlink($_SERVER["DOCUMENT_ROOT"] . "/leravel.zip");
        $_SESSION["Update"] = false;
        header("Location: /?admin&route=update&success=1");
    } else {
        echo 'failed';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Updater </title>
    <style>
        body {
            background-color: #4C5844;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #FFFFFF;
            margin: 0;
            scroll-behavior: smooth;
        }

        header {
            background-color: #4C5844;
            border-bottom: 1px solid #808080;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            height: 50px;
            line-height: 50px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            font-size: 18px;
            font-weight: 300;
            margin: 0 20px;
            display: flex;
            align-items: center;
        }

        header a {
            color: #333;
            text-decoration: none;
        }

        .content {
            margin: 20px;
            padding: 20px;
            min-height: calc(100% - 50px);
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content {
                margin-left: 0;
                margin-top: 70px;
            }
        }

        .tab-content {
            background-color: #3E4637;
            border: 1px solid #282E22;
            padding: 20px;
        }

        button {
            border-width: 1px;
            border-style: solid;
            padding: 5px;
            border-color: #808080 #282e22 #282e22 #808080;
            background-color: #566d47;
            cursor: pointer;
        }

        button:hover {
            border-color: #282e22 #808080 #808080 #282e22;
        }


        header {
            height: 110px;
        }

        header img {
            width: 100px;
            height: 100px;
            margin: 10px;
            cursor: pointer;
        }

        .sidebar {
            margin-top: 60px;
        }

        .content {
            margin-top: 90px;
        }
    </style>
</head>

<body>
    <header>
        <img id="logo" src="https://cdn.discordapp.com/attachments/996815021109674054/1101189711474729040/leravellogo2.png" alt="">
        <button onclick="window.location.reload();">leravelUpdater</button>
        <div>
            <a href="/?admin&route=/&noupdate">admin</a>
        </div>
    </header>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/checkUpdate.php" ?>
    <div class="content">
        <h1>Leravel Updater</h1>
        <div class="tab-content">
            <h2>Are you sure you want to update?</h2>
            <p>Current Version: <?php echo $leravelVersion ?></p>
            <p>Latest Version: <?php echo $latestVersion ?></p>
            <form action="" method="post">
                <input type="hidden" name="update" value="true">
                <button type="submit"><?php
                                        if ($leravelVersion == $latestVersion) {
                                            echo "Reinstall Leravel";
                                        } else {
                                            echo "Update";
                                        }
                                        ?>
                </button>
            </form>
        </div>
    </div>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/styles/default.min.css">
    <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/highlight.min.js"></script>
    <script>
        let logo = document.querySelector("#logo");
        logo.addEventListener("mouseover", () => {
            logo.src = "https://cdn.discordapp.com/attachments/996815021109674054/1101189711797694504/leravellogo3.png";
        });
        logo.addEventListener("mouseout", () => {
            logo.src = "https://cdn.discordapp.com/attachments/996815021109674054/1101189711474729040/leravellogo2.png";
        });
        logo.addEventListener("click", () => {
            window.location.href = "/?update";
        });
    </script>
</body>

</html>