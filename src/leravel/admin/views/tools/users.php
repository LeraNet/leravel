<?php
$action = null;
$user = null;
if (isset($_GET["user"]) && $_GET["user"] != "new") {
    $action = "edit";
    $user = $_GET["user"];
    $user = $adminAccounts[array_search($user, array_column($adminAccounts, "username"))];
}
if (isset($_GET["user"]) && $_GET["user"] == "new") {
    $action = "new";
}

if (isset($_POST["action"])) {
    if ($_POST["action"] == "new") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $adminAccounts[] = [
            "username" => $username,
            "password" => $password,
            "permissions" => []
        ];

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/adminAccounts.json", json_encode($adminAccounts, JSON_PRETTY_PRINT));
        header("Location: ?admin&route=tool&tool=users&user=" . $user["username"]);
    }
    if ($_POST["action"] == "editData") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $adminAccounts[array_search($user["username"], array_column($adminAccounts, "username"))] = [
            "username" => $username,
            "password" => $password,
            "permissions" => $user["permissions"]
        ];

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/adminAccounts.json", json_encode($adminAccounts, JSON_PRETTY_PRINT));
        header("Location: ?admin&route=tool&tool=users&user=" . $user["username"]);
    }
    if ($_POST["action"] == "editPerms") {
        $perms = $_POST["perms"];

        $adminAccounts[array_search($user["username"], array_column($adminAccounts, "username"))] = [
            "username" => $user["username"],
            "password" => $user["password"],
            "permissions" => $perms
        ];

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/adminAccounts.json", json_encode($adminAccounts, JSON_PRETTY_PRINT));
        header("Location: ?admin&route=tool&tool=users&user=" . $user["username"]);
    }
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
        <h1><img src='https://img.icons8.com/?size=512&id=Oyh4DCshbhyf&format=png' draggable="false"> Users Manager</h1>
        <div class="tabs">
            <?php
            foreach ($adminAccounts as $account) {
                echo "<a href='?admin&route=tool&tool=users&user=" . $account["username"] . "' class='tab'>" . $account["username"] . "</a>";
            }
            ?>
            <a href="?admin&route=tool&tool=users&user=new" class="tab">+</a>
        </div>
        <div class="tab-content">
            <?php if ($action == "edit") { ?>
                <h1><?= $user["username"] ?></h1>
                <fieldset>
                    <legend>User Info</legend>
                    <form action="" method="post">
                        <input type="hidden" name="action" value="editData">
                        <input type="text" name="username" placeholder="Username" value="<?= $user["username"] ?>">
                        <input type="password" name="password" placeholder="Password" value="<?= $user["password"] ?>">
                        <input type="submit" value="Save">
                    </form>
                </fieldset>
                <fieldset>
                    <legend>Permissions</legend>
                    <form action="" method="post">
                        <input type="hidden" name="action" value="editPerms">
                        <?php 
                        if(in_array("ROOT", $user["permissions"])) {
                            echo "<input type='checkbox' name='perms[]' value='ROOT' checked> ROOT<br>";
                        } else {
                            echo "<input type='checkbox' name='perms[]' value='ROOT'> ROOT<br>";
                        }
                        foreach ($allPerms as $perm) :
                            $checked = "";
                            if (in_array($perm, $user["permissions"])) {
                                $checked = "checked";
                            }
                        ?>
                            <input type="checkbox" name="perms[]" value="<?= $perm ?>" <?= $checked ?>> <?= $perm ?><br>
                        <?php endforeach; ?>
                        <input type="submit" value="Save">
                    </form>
                </fieldset>
            <?php } else if ($action == "new") { ?>
                <h1>New user</h1>
                <form action="" method="post">
                    <input type="hidden" name="action" value="new">
                    <input type="text" name="username" placeholder="Username">
                    <input type="password" name="password" placeholder="Password">
                    <input type="submit" value="Create">
                </form>
            <?php } else { ?>
                <h1>Select an user</h1>
            <?php } ?>
        </div>
    </div>
</body>

</html>