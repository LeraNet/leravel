<?php
$experimentsFile = $_SERVER["DOCUMENT_ROOT"] . '/leravel/admin/experiments.json';
$activeExperiments = [];
if (file_exists($experimentsFile)) {
    $activeExperiments = json_decode(file_get_contents($experimentsFile), true);
}

function updateExperiments($activeExperiments)
{
    global $experimentsFile;
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/leravel/admin/experiments.json', json_encode($activeExperiments, JSON_PRETTY_PRINT));
}

if (isset($_POST["updateExperiments"])) {
    foreach ($activeExperiments as $experiment => $status) {
        if (isset($_POST[$experiment])) {
            $activeExperiments[$experiment] = true;
        } else {
            $activeExperiments[$experiment] = false;
        }
    }
    updateExperiments($activeExperiments);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Experiments Manager</title>
    <link rel="stylesheet" href="/?admin&route=css&<?= time() ?>">
</head>

<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/header.php" ?>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/sidebar.php" ?>
    <div class="content">
        <h1><img src='https://img.icons8.com/?size=512&id=XO5nRSypAbfH&format=png' draggable="false">Experiments Manager</h1>
        <form action="" method="post" class="experiments_form">
            <input type="hidden" name="updateExperiments" value="true">
            <?php foreach ($activeExperiments as $experiment => $status) : ?>
                <label for="<?= $experiment ?>">
                    <input type="checkbox" name="<?= $experiment ?>" id="<?= $experiment ?>" <?= $status ? 'checked' : '' ?>>
                    <?= $experiment ?>
                </label><br>
            <?php endforeach; ?>
            <input type="submit" value="Update">
        </form>
    </div>
</body>

</html>