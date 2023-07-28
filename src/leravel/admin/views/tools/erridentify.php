<?php
include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/toast.php";

$commonErrors = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/commonErrors.json"), true) ?? array();
$errIndentifyJson = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/tools/erridentify.json"), true);

if ($errIndentifyJson["lastCount"] == "" || $errIndentifyJson["lastCheck"] < time() - 86400 || isset($_GET["check"])) {
    $json = json_decode(file_get_contents("https://raw.githubusercontent.com/lera2od/leravel/master/data/commonErrors.json"), true) ?? json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/tools/erridentify.json"), true);
    $errIndentifyJson["lastCount"] = count($json);
    $errIndentifyJson["lastCheck"] = time();
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/tools/erridentify.json", json_encode($errIndentifyJson));
    header("Location: ?admin&route=tool&tool=erridentify");
    exit();
}

$githubCount = $errIndentifyJson["lastCount"];

if (isset($_GET["download"])) {
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/commonErrors.json", file_get_contents("https://raw.githubusercontent.com/lera2od/leravel/master/data/commonErrors.json"));
    header("Location: ?admin&route=tool&tool=erridentify&success");
    exit();
}

if (isset($_GET["success"])) {
    toast("Common Errors are Updated!", "success");
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
        <h1><img src='https://img.icons8.com/?size=512&id=4Ywlu1XtAw14&format=png' draggable="false"> Error Identifier</h1>
        <div class="tab-content">
            <h2>Error Identifier</h2>
            <table>
                <tr>
                    <th>From</th>
                    <th>Data</th>
                </tr>
                <tr>
                    <td>Local</td>
                    <td>Total of <?= count($commonErrors) ?> errors are documented.</td>
                </tr>
                <tr>
                    <td>GitHub</td>
                    <td>Total of <?= $githubCount ?> errors are documented. (<?php if ($githubCount <= count($commonErrors)) {
                                                                                    echo "Synced";
                                                                                } else {
                                                                                    echo $githubCount - count($commonErrors) . " errors not synced.";
                                                                                } ?>) <a class="btn" href="?admin&route=tool&tool=erridentify&check">🔄</a></td>
                </tr>
            </table>
            <br>
            <a class="btn" href="?admin&route=tool&tool=erridentify&download">Download Common Errors</a>
        </div>
    </div>
</body>

</html>