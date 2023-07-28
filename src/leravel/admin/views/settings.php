<?php
$settings = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/settings.json"), true);
$acoount = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "/app/adminAccount.ini");
require "include/toast.php";

$vocabulary = array(
    "router" => array(
        "views" => "Views Path",
        "404" => "404 Page Path"
    ),
    "lang" => array(
        "default" => "Default Language",
    ),
    "database" => array(
        "enabled" => "Enabled",
        "host" => "Database Host",
        "username" => "Database Username",
        "password" => "Database Password",
        "database" => "Database Name"
    ),
    "admin" => array(
        "enabled" => "Enabled",
        "captcha" => "Captcha"
    ),
    "errors" => array(
        "devErrorPage" => "Developer Error Page",
        "errorFile" => "Custom Error Filename"
    ),
    "stats" => array(
        "enabled" => "Enabled",
    )
);

$categoryVocabulary = array(
    "router" => "Router",
    "lang" => "Language",
    "database" => "Database",
    "admin" => "Admin Panel",
    "stats" => "Statistics",
    "errors" => "Errors"

);

$syntaxVocabulary = array(
    "router" => array(
        "views" => "The path to the views folder. The default value is \"app/views\".",
        "404" => "The path to the 404 page. The default value is \"views/404.php\"."
    ),
    "lang" => array(
        "default" => "The default language. The default value is \"en\".",
    ),
    "database" => array(
        "enabled" => "Enable or disable the database. The default value is \"true\" or \"1\".",
        "host" => "The database host. The default value is \"localhost\".",
        "username" => "The database username. The default value is \"root\".",
        "password" => "The database password. The default value is \"\".",
        "database" => "The database name. The default value is \"leravel\"."
    ),
    "admin" => array(
        "enabled" => "Enable or disable the admin panel. The default value is \"true\" or \"1\".",
        "captcha" => "Enable or disable the admin panel captcha. The default value is \"true\" or \"1\"."
    ),
    "errors" => array(
        "devErrorPage" => "Enable or disable the developer error page. The default value is \"true\" or \"1\".",
        "errorFile" => "The custom error page filename."
    ),
    "stats" => array(
        "enabled" => "Enable or disable the statistics. The default value is \"true\" or \"1\"."
    )
);

if (isset($_GET["success"])) {
    toast("Settings saved successfully", "success");
}

if (isset($_POST["action"]) && $_POST["action"] == "save") {
    $file = $_SERVER["DOCUMENT_ROOT"] . $_POST["file"];
    $data = $_POST["data"];
    $type = $_POST["type"];
    $source = file_get_contents($file);

    if ($type == "json") {
        $source = json_decode($source, true);
        foreach ($data as $category => $value) {
            foreach ($value as $key => $value) {
                $source[$category][$key] = $value;
            }
        }
        $source = json_encode($source, JSON_PRETTY_PRINT);
    } else if ($type == "ini") {
        $source = parse_ini_file($file);
        foreach ($data as $key => $value) {
            $source[$key] = $value;
        }
        $source = "";
        foreach ($data as $key => $value) {
            $source .= $key . " = " . $value . "\n";
        }
    }

    file_put_contents($file, $source);
    header("Location: ?admin&route=settings&success");
}

$tabs = array(
    "settings" => "settings.json",
    "account" => "adminAccount.ini"
);

$currentTab = $_GET["tab"] ?? "settings.json";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Settings</title>
    <link rel="stylesheet" href="/?admin&route=css&<?= time() ?>">
</head>

<body>
    <?php include "include/header.php" ?>
    <?php include "include/sidebar.php" ?>
    <div class="content">
        <h1><img src="<?= $icons["settings"]?>">Settings</h1>
        <div class="tabs">
            <?php foreach ($tabs as $tab) : ?>
                    <a href="?admin&route=settings&tab=<?= $tab ?>" class="tab <?php
                                                        if ($currentTab == $tab) {
                                                            echo "tab-active";
                                                        } ?>"><?= $tab ?></a>
            <?php endforeach; ?>
        </div>
        <div class="tab-content">
            <?php if($currentTab == "settings.json") : ?>
            <h2>settings.json</h2>
            <div class="tab-content">
                <?php foreach ($settings as $category => $data) : ?>
                    <div class="tab-pane" id="<?= $category ?>">
                        <h2><?= $categoryVocabulary[$category] ?? $category ?></h2>
                        <form action="" method="post">
                            <input type="hidden" name="action" value="save">
                            <input type="hidden" name="data[<?= $category ?>]" value="">
                            <input type="hidden" name="file" value="/app/settings.json">
                            <input type="hidden" name="type" value="json">
                            <table>
                                <?php foreach ($data as $key => $value) : ?>
                                    <tr>
                                        <td><?= $vocabulary[$category][$key] ?? $key ?></td>
                                        <td><input type="text" name="data[<?= $category ?>][<?= $key ?>]" value="<?= $value ?>"></td>
                                        <td><span class="syntax"><?= $syntaxVocabulary[$category][$key] ?? "" ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <br>
                            <input type="submit" value="Save">
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php elseif($currentTab == "adminAccount.ini") : ?>
            <h2>adminAccount.ini</h2>
            <div class="tab-content">
                <div class="tab-pane" id="account">
                    <h2>Account</h2>
                    <form action="?admin&route=settings" method="post">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="file" value="/app/adminAccount.ini">
                        <input type="hidden" name="type" value="ini">
                        <table>
                            <tr>
                                <td>Username</td>
                                <td><input type="text" name="data[username]" value="<?= $acoount["username"] ?>"></td>
                                <td><span class="syntax">The username for the admin panel.</span></td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td><input type="password" name="data[password]" value="<?= $acoount["password"] ?>"></td>
                                <td><span class="syntax">The password for the admin panel.</span></td>
                            </tr>
                        </table>
                        <br>
                        <input type="submit" value="Save">
                    </form>
                </div>
            </div>
            <?php else : ?>
            <h2>Select a file</h2>
            <?php endif; ?>
        </div>


        <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/styles/default.min.css">
        <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/highlight.min.js"></script>
</body>

</html>