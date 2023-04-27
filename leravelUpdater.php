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
        header("Location: /?admin");
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
    <title>Leravel Settings</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
            margin: 0;
            scroll-behavior: smooth;
        }

        header {
            background-color: #ffffff;
            border-bottom: 1px solid #e7e7e7;
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

        .sidebar {
            background-color: #d3d3d3;
            color: white;
            position: fixed;
            top: 50px;
            left: 0;
            z-index: 1000;
            display: flex;
            padding: 20px;
            overflow-x: hidden;
            overflow-y: auto;
            border-right: 1px solid #000000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            flex-direction: column;
            width: 200px;
            height: calc(100% - 50px);
        }

        .sidebar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar ul li {
            margin: 0;
            padding: 0;
        }

        .sidebar ul li a {
            color: #333;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            border-radius: 15px;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }

        .sidebar ul li a img {
            width: 25px;
            height: 25px;
            margin-right: 10px;
        }

        .sidebar ul li a:hover {
            background-color: white;
            color: #333;
        }

        .content {
            margin: 20px;
            padding: 20px;
            margin-left: 250px;
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
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 0 4px 4px 4px;
            padding: 20px;
        }
        
        button {
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 4px;
            color: #333;
            display: inline-block;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            margin-bottom: 0;
            padding: 6px 12px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
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
        <div>
            <a href="/?admin&route=/">admin</a>
        </div>
    </header>
    <div class="sidebar">
        <ul>
            <li><a href="/?update">Update</a></li>
        </ul>
    </div>

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