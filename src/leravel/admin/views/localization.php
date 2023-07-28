<?php

include "include/toast.php";

$languages = glob($_SERVER["DOCUMENT_ROOT"] . "/app/localization/*");
$language = $_GET["language"] ?? null;
$currentLang = $language;

$current = null;

$default = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/localization/default.json"), true);
if(isset($currentLang)) {
    $current = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/localization/$currentLang"), true);
}

if(isset($_POST["action"]) && $_POST["action"] == "saveLang") {
    $newLang = [];
    foreach ($_POST as $key => $value) {
        if($key != "action") {
            $newLang[$key] = $value;
        }
    }
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/localization/$currentLang", json_encode($newLang));
    header("Location: ?admin&route=localization&language=$currentLang&success=1");
}

if(isset($_POST["action"]) && $_POST["action"] == "addDefault") {
    if($_POST["key"] == "" || $_POST["value"] == "") {
        header("Location: ?admin&route=localization&default&error=1");
        exit;
    }
    $default[$_POST["key"]] = $_POST["value"];
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/localization/default.json", json_encode($default));
    header("Location: ?admin&route=localization&default&success=2");
}

if(isset($_POST["action"]) && $_POST["action"] == "createTable") {
    if($_POST["language"] == "") {
        header("Location: ?admin&route=localization&tablecreate=1&error=2");
        exit;
    }
    $newLang = [];
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/localization/" . $_POST["language"] . ".json", json_encode($newLang));
    header("Location: ?admin&route=localization&language=" . $_POST["language"] . ".json&success=3");
}

if(isset($_GET["Ddelete"])) {
    $key = $_GET["Ddelete"];
    unset($default[$key]);
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/localization/default.json", json_encode($default));
    header("Location: ?admin&route=localization&default&success=4");
}

if(isset($_GET["deleteLang"])) {
    unlink($_SERVER["DOCUMENT_ROOT"] . "/app/localization/$currentLang");
    header("Location: ?admin&route=localization&success=5");
}

if(isset($_GET["defaultLang"])) {
    $settings = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/settings.json"), true);
    $currentLang = substr($currentLang, 0, -5);
    $settings["lang"]["default"] = $currentLang;
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/settings.json", json_encode($settings));
    header("Location: ?admin&route=localization&language=$currentLang.json&success=6");
}

if(isset($_GET["success"])) {
    if($_GET["success"] == 1) {
        toast("Language saved successfully!", "success");
    } else if($_GET["success"] == 2) {
        toast("Default language saved successfully!", "success");
    } else if($_GET["success"] == 3) {
        toast("Language created successfully!", "success");
    } else if($_GET["success"] == 4) {
        toast("Default language deleted successfully!", "success");
    } else if($_GET["success"] == 5) {
        toast("Language deleted successfully!", "success");
    } else if($_GET["success"] == 6) {
        toast("Default language changed successfully!", "success");
    }
}

if(isset($_GET["error"])) {
    if($_GET["error"] == 1) {
        toast("Please fill all the fields!", "error");
    } else if($_GET["error"] == 2) {
        toast("Please fill the language name!", "error");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Localization Manager</title>
    <link rel="stylesheet" href="/?admin&route=css&<?= time() ?>">
</head>

<body>
    <?php include "include/header.php" ?>
    <?php include "include/sidebar.php" ?>
    <div class="content">
        <h1><img src="<?= $icons["localization"] ?>" draggable="false">Localization Manager</h1>
        <div class="tabs">
            <a href='?admin&route=localization&default' class='tab '>#</a>
            <?php
            foreach ($languages as $ll) {
                $ll = basename($ll);
                if($ll == "default.json") {
                    continue;
                }
                if(isset($_GET["language"]) && $ll == $_GET["language"]) {
                    echo "<a href='?admin&route=localization&language=$ll' class='tab tab-active'><img src='$icons[language]'>$ll</a>";
                } else {
                    echo "<a href='?admin&route=localization&language=$ll' class='tab'><img src='$icons[language]'>$ll</a>";
                }
            }
            ?>
            <a href="?admin&route=localization&tablecreate=1" class="tab">+</a>
        </div>
        <?php if(isset($currentLang)) : ?>
            <div class="tab-content">
                <h2><?php echo $currentLang; ?></h2>
                <form action="?admin&route=localization&language=<?php echo $currentLang; ?>" method="post" autocomplete="off">
                    <input type="hidden" name="action" value="saveLang"> 
                    <table>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                        </tr>
                        <?php 
                        foreach ($default as $key => $value) {
                            if(isset($current[$key])) {
                                echo "<tr><td>$key</td><td><input type='text' name='$key' value='$current[$key]'></td></tr>";
                            } else {
                                echo "<tr><td>$key</td><td><input type='text' name='$key' value='$value' class='localization-notset'></td></tr>";
                            }
                        }
                        ?>
                    </table>
                    <br>
                    <input type="submit" value="Save">
                </form>
                <br>
                <a id="openscary" class="btn">Scary Buttons</a>
                <br>
                <div id="scary" style="display: none;">
                    <h1>Danger!</h1>
                    <h2>Ultra scary buttons!</h2>
                    <a href="?admin&route=localization&language=<?php echo $currentLang; ?>&defaultLang" class="btn btn-primary">Set as default language</a>
                <a href="?admin&route=localization&language=<?php echo $currentLang; ?>&deleteLang" class="btn btn-danger">Delete language</a>
                </div>
                <script>
                    let scary = 0
                    document.getElementById("openscary").addEventListener("click", function() {
                        if (scary == 0) {
                            document.getElementById("scary").style.display = "block";
                            scary = 1;
                        } else {
                            document.getElementById("scary").style.display = "none";
                            scary = 0;
                        }
                    })
                </script>
            </div>
        <?php elseif(isset($_GET["default"])) : ?>
            <div class="tab-content">
                <h2>Default</h2>
                <fieldset>
                <legend>New Variable</legend>
                <form action="?admin&route=localization" method="post" autocomplete="off">
                    <input type="hidden" name="action" value="addDefault">  
                    <input type="text" name="key" placeholder="key">
                    <input type="text" name="value" placeholder="value">
                    <input type="submit" value="Create">
                </form>
                </fieldset>
                <br>
                <div class="hr"></div>
                <br>
                <form action="?admin&route=localization&default" method="post" autocomplete="off">
                    <input type="hidden" name="action" value="saveLang"> 
                    <table>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                            <th></th>
                        </tr>
                        <?php 
                        foreach ($default as $key => $value) {
                            echo "<tr><td>$key</td><td><input type='text' name='$key' value='$value'></td><td><a href='?admin&route=localization&default&Ddelete=$key' class='btn btn-danger'>Delete</a></td></tr>";
                        }
                        ?>
                    </table>
                    <br>
                    <input type="submit" value="Save">
                </form>
            </div>

        <?php elseif($_GET["tablecreate"] ?? null) : ?>
            <div class="tab-content">
                <h2>Create a language.</h2>
                <form action="?admin&route=localization" method="post" autocomplete="off">
                    <input type="hidden" name="action" value="createTable">  
                    <input type="text" name="language" placeholder="language">
                    <input type="submit" value="Create">
                </form>
            </div>
        <?php else : ?>
            <div class="tab-content">
                <h2>Select a language!</h2>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>