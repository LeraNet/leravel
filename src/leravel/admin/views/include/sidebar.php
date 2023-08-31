<?php
$navigation = $adminAccounts[array_search($_SESSION['username'], array_column($adminAccounts, 'username'))]['sidebar'] ?? [];
$navigationTemp = [];
$allToolsX = [];
foreach ($allTools as $category) {
    foreach ($category["tools"] as $tool) {
        $allToolsX[] = $tool;
    }
}

foreach ($navigation as $nav) {
    $navX['enabled'] = true;

    $navX["title"] = $allToolsX[array_search($nav, array_column($allToolsX, 'id'))]["title"];
    $navX["icon"] = $allToolsX[array_search($nav, array_column($allToolsX, 'id'))]["icon"];
    $navX["file"] = $allToolsX[array_search($nav, array_column($allToolsX, 'id'))]["file"];
    $navX["type"] = $allToolsX[array_search($nav, array_column($allToolsX, 'id'))]["type"];
    $navX["perm"] = $allToolsX[array_search($nav, array_column($allToolsX, 'id'))]["perm"];

    if($navX["type"] == "experimentExt" || $navX["type"] == "experimentInt"){
        $navX["experiment"] = $allToolsX[array_search($nav, array_column($allToolsX, 'id'))]["experiment"];
    }

    $navX["uri"] = "";

    switch ($navX["type"]) {
        case "ext":
            $navX["uri"] = $navX["file"];
            break;
        case "int":
            $navX["uri"] = "?admin&route=tool&tool=" . $navX["file"];
            break;
        case "experimentExt":
            $navX["uri"] = $navX["file"];
            break;
        case "experimentInt":
            $navX["uri"] = "?admin&route=tool&tool=" . $navX["file"];
            break;
    }

    $navigationTemp[] = $navX;
}

$navigation = $navigationTemp;
?>

<div class="sidebar">
    <ul>
        <li>
            <a href="?admin">
                <img src="<?= $icons["home"] ?>" draggable="false">
                Home
            </a>
        </li>
        <?php foreach ($navigation as $itemKey => $item) : ?>
            <?php
            $isEnabled = $item['enabled'];
            if (isset($item['experiment']) && isExperimentActive($item['experiment'], $activeExperiments) == true) {
                $isEnabled = true;
            }

            if(checkPerm($item["perm"]) == false){
                $isEnabled = false;
            }
            ?>

            <?php if ($isEnabled && checkPerm($item["perm"])) : ?>
                <li>
                    <a href="<?= $item['uri'] ?>">
                        <img src="<?= $item['icon'] ?>" draggable="false">
                        <?= $item['title'] ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
        <li>
            <a href="?admin&route=tool">
                <img src="<?= $icons["tools"] ?>" draggable="false">
                Tools
            </a>
        </li>
    </ul>
</div>

<div class="mobile-sidebar">
    <select name="" id="selectPage" onchange="navigate()">
        <option value="">Select Page</option>
        <option value="?admin">Home</option>
        <?php foreach ($navigation as $itemKey => $item) : ?>
            <?php
            $isEnabled = $item['enabled'];
            if (isset($item['experiment']) && isExperimentActive($item['experiment'], $activeExperiments) == true) {
                $isEnabled = true;
            }

            if(checkPerm($item["perm"]) == false){
                $isEnabled = false;
            }
            ?>

            <?php if ($isEnabled && checkPerm($item["perm"])) : ?>
                <option value="<?= $item['uri'] ?>"><?= $item['title'] ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
        <option value="?admin&route=tool">Tools</option>
    </select>
</div>


<script>
    let selectPage = document.querySelector("#selectPage");

    function navigate() {
        window.location.href = selectPage.value;
    }
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/checkUpdate.php" ?>