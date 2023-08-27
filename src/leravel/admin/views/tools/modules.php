<?php
$modules = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/modules.json"), true);
$moduleFiles = scandir($_SERVER["DOCUMENT_ROOT"] . "/leravel/modules");
foreach ($moduleFiles as $moduleFile) {
    if ($moduleFile != "." && $moduleFile != "..") {
        $moduleName = explode(".", $moduleFile)[0];
        if (!isset($modules[$moduleName])) {
            $modules[$moduleName] = true;
        }
    }
}

$moduleInfo = [
    "asset" => "Allows you to easily send assets with the router",
    "errorHandler" => "Adds a custom error handler",
    "extra" => "Adds some functions",
    "leravelVar" => "Adds the \$Leravel variable. You can't disable this.",
    "mysql" => "WIP",
    "pluginHandler" => "WIP",
    "router" => "Adds the router class. You can't disable this.",
    "stats" => "Records users every time they use your website",
    "template" => "Adds a templating system",
    "variableCleanUp" => "Cleans all the variables used in Leravel startup",
    "leravelJsonDatabase" => "Adds a simple key-value tree type json database",
];

$cantDisableModules = [
    "leravelVar", "router",
];

$moduleIcons = [
    "asset" => "Allows you to easily send assets with the router",
    "errorHandler" => "Adds a custom error handler",
    "extra" => "Adds some functions",
    "leravelVar" => "Essential",
    "mysql" => "WIP",
    "pluginHandler" => "WIP",
    "router" => "Essential",
    "stats" => "Tool",
    "template" => "Function",
    "variableCleanUp" => "Cleans all the variables used in Leravel startup",
    "leravelJsonDatabase" => "Adds a simple key-value tree type json database",
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($moduleInfo as $moduleName => $moduleDescription) {
        if (isset($_POST[$moduleName]) && $_POST[$moduleName] == "on") {
            $modules[$moduleName] = true;
        } else {
            $modules[$moduleName] = false;
        }
    }

    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/modules.json", json_encode($modules, JSON_PRETTY_PRINT));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Admin Index</title>
    <link rel="stylesheet" href="/?admin&route=css&<?= time() ?>">
</head>

<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/header.php" ?>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/sidebar.php" ?>
    <div class="content">
        <h1><img src='https://img.icons8.com/?size=512&id=jzaaE6lUq9fx&format=png' draggable="false"> Modules Manager</h1>
        <div class="tab-content">
            <h2>Manage Modules</h2>
            <form action="" method="post">
                <table>
                    <?php foreach ($modules as $moduleName => $moduleStatus) : ?>
                        <tr>
                            <td><img src="<?= $moduleIcons[$moduleName] ?? "" ?>" alt="" class="module-icon"></td>
                            <td><?= $moduleName ?> <?php if (in_array($moduleName, $cantDisableModules)) {
                                                        echo '<img src="https://img.icons8.com/?size=512&id=znpDNZWhQe6p&format=png" alt="" class="lock" draggable="false">';
                                                    } ?></td>
                            <td><?= $moduleInfo[$moduleName] ?? "" ?></td>
                            <td>
                                <?php if (!in_array($moduleName, $cantDisableModules)) : ?>
                                    <div class="switch">
                                        <input id="<?= $moduleName ?>switch" type="checkbox" name="<?= $moduleName ?>" <?= $moduleStatus ? 'checked' : '' ?>>
                                        <label for="<?= $moduleName ?>switch">Toggle</label>
                                    </div>
                                <?php else : ?>
                                    <input type="hidden" name="<?= $moduleName ?>" value="<?= $moduleStatus ? 'on' : 'off' ?>">
                                    <input type="checkbox" name="<?= $moduleName ?>" <?= $moduleStatus ? 'checked' : '' ?> disabled style='display: none;'>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
                <br>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>
</body>

</html>