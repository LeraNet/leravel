<?php
$tools = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/tools/tools.json"), true);

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
        <h1><img src='<?= $icons["tools"] ?>' draggable="false"> Tools</h1>
        <div class="tab-content">
            <h2>Tools</h2>
            <?php
            foreach ($tools as $category) :
            ?>
                <ul class="tools">
                    <p><img src="<?= $category["icon"] ?>" alt="" draggable="false"><?= $category["title"] ?></p>
                    <?php
                    foreach ($category["tools"] as $tool) :
                        if ($tool["type"] == "int") :
                    ?>
                            <li>↳ <a href="<?= "?admin&route=tool&tool=" . $tool["file"]; ?>"><img src="<?= $tool["icon"] ?>" alt="" draggable="false"><?= $tool["title"] ?></a></li>
                        <?php elseif ($tool["type"] == "ext") : ?>

                            <li>↳ <a href="<?= $tool["file"]; ?>"><img src="<?= $tool["icon"] ?>" alt="" draggable="false"><?= $tool["title"] ?></a></li>
                        <?php elseif ($tool["type"] == "experiment" && isExperimentActive($tool["experiment"], $activeExperiments)) :
                        ?>
                            <li>↳ <a href="<?= $tool["file"]; ?>"><img src="<?= $tool["icon"] ?>" alt="" draggable="false"><?= $tool["title"] ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>