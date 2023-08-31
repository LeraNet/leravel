<?php 
hasAccess("PLUGIN_MANAGER");
?>
<?php

$tab = $_GET["tab"] ?? "pluginstore";

if ($tab == "getPlugins") {
    $pluginsBlank;
    function curl_get($url)
    {
        global $requestNumber;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "anan :DDD");
        $response = curl_exec($ch);

        if ($response === false) {
            echo $requestNumber . " cURL Error: " . curl_error($ch) . "(This probably means you exceeded the api limit which means you need to use a vpn.)\n";
            exit();
        }

        curl_close($ch);
        $requestNumber++;
        return $response;
    }

    $pluginsResponse = curl_get("https://api.github.com/repos/lera2od/leravel/contents/data/plugins");
    if ($pluginsResponse) {
        $plugins = json_decode($pluginsResponse, true);
        foreach ($plugins as $plugin) {
            $pluginName = $plugin["name"];
            $infoUrl = "https://api.github.com/repos/lera2od/leravel/contents/data/plugins/$pluginName/info.json";

            $pluginResponse = curl_get($infoUrl);

            if ($pluginResponse) {
                $plugin = json_decode(base64_decode(json_decode($pluginResponse, true)["content"]), true);
                if ($plugin["logo"] == "") {
                    $plugin["logo"] = "https://img.icons8.com/?size=512&id=LV1toaPaA7ia&format=png";
                }
            }
            $pluginsBlank[] = $plugin;
        }
    }
    fopen($_SERVER["DOCUMENT_ROOT"] . "/leravel/plugins/entireList.json", "w");
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/plugins/entireList.json", json_encode($pluginsBlank));
    header("Location: /?admin&route=plugins&tab=pluginstore");
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
<?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/header.php" ?>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/sidebar.php" ?>
    <div class="content">
        <h1><img src='<?= $icons["plugins"] ?>' draggable="false">Plugins</h1>
        <div class="tabs">
            <a href="?admin&route=plugins&tab=pluginstore" class="tab <?php if ($tab == "pluginstore") {
                                                                            echo "tab-active";
                                                                        } ?>">Plugin Store</a>
            <a href="?admin&route=plugins&tab=plugins" class="tab <?php if ($tab == "plugins") {
                                                                        echo "tab-active";
                                                                    } ?>">Plugins</a>
        </div>
        <div class="tab-content">
            <div class="alert alert-warning">
                <div>
                    <h2>Warning</h2>
                    <b>This feature is still work in progress.</b>
                </div>
            </div>
            <?php if ($tab == "pluginstore") : ?>
                <h2>Plugin Store <button onclick="window.location.href = '/?admin&route=plugins&tab=getPlugins'">🔄</button></h2>
                <div class="plugins">
                    <?php
                    if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/leravel/plugins/entireList.json")) {
                        echo "Reload the plugin list.";
                    } else {
                        $plugins = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/plugins/entireList.json"), true);
                        foreach ($plugins as $plugin) :
                            if ($plugin["logo"] == "") {
                                $plugin["logo"] = "https://img.icons8.com/?size=512&id=LV1toaPaA7ia&format=png";
                            }
                    ?>
                            <div class="plugin">
                                <div>
                                    <div>
                                        <img src="<?= $plugin["logo"] ?>" alt="" draggable="false">
                                    </div>
                                    <div>
                                        <h3><?= $plugin["name"] ?><span class="author">@<?= $plugin["author"] ?></span></h3>
                                        <p><?= $plugin["desc"] ?></p>
                                    </div>
                                </div>
                                <a href="" class="btn btn-success">Download</a>
                            </div>
                    <?php endforeach;
                    }
                    ?>
                </div>
            <?php elseif ($tab == "plugins") : ?>
                <h2>Plugins</h2>
            <?php else : ?>
                <h2>Select a tab</h2>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>