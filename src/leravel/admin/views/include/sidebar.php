<?php

$experimentsFile = $_SERVER["DOCUMENT_ROOT"] . '/leravel/admin/experiments.json';
$modulesFile = $_SERVER["DOCUMENT_ROOT"] . "/app/modules.json";
$activeExperiments = [];
$activeModules = [];
if (file_exists($experimentsFile)) {
    $activeExperiments = json_decode(file_get_contents($experimentsFile), true);
}

if (file_exists($modulesFile)) {
    $activeModules = json_decode(file_get_contents($modulesFile), true);
}

function isExperimentActive($experiment, $activeExperiments)
{
    if(in_array($experiment, $activeExperiments) && $activeExperiments[$experiment] == true){
        return true;
    }else{
        return false;
    }
}

function isModuleActive($module, $activeModules)
{
    if(in_array($module, $activeModules) && $activeModules[$module] == true){
        return true;
    }else{
        return false;
    }
}

$navigation = [
    "Home" => [
        "title" => "Home",
        "icon" => "home",
        "uri" => "/?admin&route=/",
        "enabled" => true
    ],
    "Database" => [
        "title" => "Database",
        "icon" => "database",
        "uri" => "/?admin&route=database",
        "enabled" => true
    ],
    "Localization" => [
        "title" => "Localization",
        "icon" => "localization",
        "uri" => "/?admin&route=localization",
        "enabled" => true
    ],
    "Stats" => [
        "title" => "Stats",
        "icon" => "stats",
        "uri" => "/?admin&route=stats",
        "enabled" => true
    ],
    "Plugins" => [
        "title" => "Plugins",
        "icon" => "plugins",
        "uri" => "/?admin&route=plugins",
        "enabled" => false,
        "experiment" => "pluginsmanager_07_23"
    ],
    "Tools" => [
        "title" => "Tools",
        "icon" => "tools",
        "uri" => "/?admin&route=tool",
        "enabled" => true
    ],
    "Settings" => [
        "title" => "Settings",
        "icon" => "settings",
        "uri" => "/?admin&route=settings",
        "enabled" => true
    ],
    "Update" => [
        "title" => "Update",
        "icon" => "update",
        "uri" => "/?admin&route=update",
        "enabled" => true
    ]
];
?>

<div class="sidebar">
    <ul>
        <?php foreach ($navigation as $itemKey => $item) : ?>
            <?php
            $isEnabled = $item['enabled'];
            if (isset($item['experiment']) && isExperimentActive($item['experiment'], $activeExperiments) == true) {
                $isEnabled = true;
            }
            ?>

            <?php if ($isEnabled) : ?>
                <li>
                    <a href="<?= $item['uri'] ?>">
                        <img src="<?= $icons[$item['icon']] ?>" draggable="false">
                        <?= $item['title'] ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<div class="mobile-sidebar">
    <select name="" id="selectPage" onchange="navigate()">
        <option value="">Select Page</option>
        <?php foreach ($navigation as $itemKey => $item) : ?>
            <?php
            $isEnabled = $item['enabled'];
            if (isset($item['experiment']) && !empty($item['experiment'])) {
                $isEnabled = $isEnabled && isExperimentActive($item['experiment'], $activeExperiments);
            }
            ?>

            <?php if ($isEnabled) : ?>
                <option value="<?= $item['uri'] ?>"><?= $item['title'] ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
</div>


<script>
    let selectPage = document.querySelector("#selectPage");

    function navigate() {
        window.location.href = selectPage.value;
    }
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/checkUpdate.php" ?>