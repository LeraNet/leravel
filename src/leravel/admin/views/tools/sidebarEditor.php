<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["loggedIn"])) {
        http_response_code(403);
        exit();
    }
    $currentUser = $adminAccounts[array_search($_SESSION['username'], array_column($adminAccounts, 'username'))];
    $currentUser["sidebar"] = json_decode($_POST["sidebar"], true)["sidebar"];
    $adminAccounts[array_search($_SESSION['username'], array_column($adminAccounts, 'username'))] = $currentUser;
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/app/adminAccounts.json", json_encode($adminAccounts, JSON_PRETTY_PRINT));
    header("Location: /?admin&route=tool&tool=sidebarEditor&success");
    exit();
}

if (isset($_GET["success"])) {
    require $_SERVER['DOCUMENT_ROOT'] . "/leravel/admin/views/include/toast.php";
    toast("Sidebar updated successfully", "success");
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
        <h1><img src='https://img.icons8.com/?size=512&id=X78gBGIrZrTl&format=png' draggable="false"> Sidebar Editor</h1>
        <div class="tab-content">
            <h2>Edit Your Sidebar</h2>
            <p>Here you can edit your sidebar. You can add new items, remove items, change the order of items and more.</p>
            <fieldset>
                <legend>Current Items</legend>
                <div class="items">
                    <input type="hidden" name="selectItem" id="selectItem">
                    <button onclick="document.querySelector('dialog').showModal()" id="addMoreStuffidk">➕</button>
                    <div class="sidebarEdit-item">
                        <div id="content">
                            <img src="<?= $icons["home"] ?>" draggable="false">
                            Home
                        </div>
                        <button onclick="remove(this)" id="remove" disabled>❌</button>
                        <button onclick="down(this)" id="down" disabled>⬇️</button>
                        <button onclick="up(this)" id="up" disabled>⬆️</button>
                    </div>
                    <?php
                    $sidebarStuff = $currentUser["sidebar"] ?? [];
                    foreach ($sidebarStuff as $item) :
                        if(checkPerm($allToolsList[array_search($item, array_column($allToolsList, 'id'))]["perm"]) == false) {
                            continue;
                        }
                        if($allToolsList[array_search($item, array_column($allToolsList, 'id'))]["type"] == "experimentInt" || $allToolsList[array_search($item, array_column($allToolsList, 'id'))]["type"] == "experimentExt") {
                            if(isExperimentActive($allToolsList[array_search($item, array_column($allToolsList, 'id'))]["experiment"], $activeExperiments) == false) {
                                continue;
                            }
                        }
                    ?>
                        <div class="sidebarEdit-item" style="display: flex; align-items: center">
                            <div id="content">
                                <img src="<?php
                                            foreach ($allTools as $category) :
                                                foreach ($category["tools"] as $tool) :
                                                    if ($tool["id"] == $item) {
                                                        echo $tool["icon"];
                                                    }
                                                endforeach;
                                            endforeach;
                                            ?>" draggable="false">
                                <?php
                                foreach ($allTools as $category) :
                                    foreach ($category["tools"] as $tool) :
                                        if ($tool["id"] == $item) {
                                            echo $tool["title"];
                                        }
                                    endforeach;
                                endforeach;
                                ?>
                            </div>
                            <button onclick="remove(this)" id="remove">❌</button>
                            <button onclick="down(this)" id="down">⬇️</button>
                            <button onclick="up(this)" id="up">⬆️</button>
                            <div id="value" style="display: none;"><?= $item ?></div>
                        </div>
                    <?php
                    endforeach;
                    ?>
                    <div class="sidebarEdit-item">
                        <div id="content">
                            <img src="<?= $icons["tools"] ?>" draggable="false">
                            Tools
                        </div>
                        <button onclick="remove(this)" id="remove" disabled>❌</button>
                        <button onclick="down(this)" id="down" disabled>⬇️</button>
                        <button onclick="up(this)" id="up" disabled>⬆️</button>
                    </div>
                </div>
            </fieldset>
            <br>
            <fieldset>
                <legend>Save</legend>
                <button onclick="save()">Save</button>
            </fieldset>
        </div>
    </div>

    <dialog>
        <div class="sidebaritemsx">
            <h1>Choose Item</h1>
            <?php
            sort($allTools);
            array_reverse($allTools);
            foreach ($allTools as $category) :
                sort($category["tools"]);
                foreach ($category["tools"] as $tool) :
                    if (in_array($tool["perm"], $sidebarStuff)) {
                        continue;
                    }
                    if (checkPerm($tool["perm"]) == false) {
                        continue;
                    }

            ?>
                    <div class="sidebarEdit-item-content">
                        <img src="<?= $tool["icon"] ?>" draggable="false">
                        <p><?= $tool["title"] ?></p>
                        <button onclick="select(this)" id="select" value="<?= $tool["id"] ?>">Select</button>
                    </div>
            <?php
                endforeach;
            endforeach;
            ?>
        </div>
    </dialog>

    <script>
        let selectItem = document.querySelector("#selectItem");
        let add = document.querySelector("#add");
        let items = document.querySelector(".items");
        let allTools = <?= json_encode($allToolsList) ?>;

        function addItem() {
            if (selectItem.value == "") {
                return;
            }
            let item = document.createElement("div");
            item.classList.add("sidebarEdit-item");
            item.style.display = "flex";
            item.style.alignItems = "center";
            item.innerHTML = `
            <div id="content">
                <img src="${allTools.find(tool => tool.id == selectItem.value).icon}" draggable="false">
                ${allTools.find(tool => tool.id == selectItem.value).title}
            </div>
            <button onclick="remove(this)" id="remove">❌</button>
            <button onclick="down(this)" id="down">⬇️</button> 
            <button onclick="up(this)" id="up">⬆️</button> 
            <div id="value" style="display: none;">${selectItem.value}</div>
            `;
            let sidebarItems = document.querySelectorAll(".sidebaritemsx .sidebarEdit-item-content #select");
            sidebarItems.forEach(sidebarItem => {
                if (sidebarItem.value == selectItem.value) {
                    sidebarItem.parentElement.style.display = "none";
                }
            });
            selectItem.value = "";
            items.insertBefore(item, items.children[items.children.length - 1]);
        };

        function select(element) {
            selectItem.value = element.value;
            document.querySelector("dialog").close();
            addItem();
        }

        function up(element) {
            if (Array.from(element.parentElement.parentElement.children).indexOf(element.parentElement) == 1) {
                return;
            }
            let item = element.parentElement;
            let prev = item.previousElementSibling;
            if (prev == null) {
                return;
            }
            items.insertBefore(item, prev);
        }

        function down(element) {
            if (Array.from(element.parentElement.parentElement.children).indexOf(element.parentElement) == Array.from(element.parentElement.parentElement.children).length - 2) {
                return;
            }
            let item = element.parentElement;
            let next = item.nextElementSibling;
            if (next == null) {
                return;
            }
            items.insertBefore(next, item);
        }

        function remove(element) {
            let item = element.parentElement;
            let value = item.querySelector("#value").innerText;
            let sidebarItems = document.querySelectorAll(".sidebaritemsx .sidebarEdit-item-content #select");
            sidebarItems.forEach(sidebarItem => {
                if (sidebarItem.value == value) {
                    sidebarItem.parentElement.style.display = "flex";
                }
            });
            item.remove();
        }

        function save() {
            let items = document.querySelectorAll(".items .sidebarEdit-item #value");
            let sidebar = [];
            items.forEach(item => {
                sidebar.push(item.innerText);
            });
            let data = {
                "sidebar": sidebar
            };
            let form = document.createElement("form");
            form.method = "POST";
            form.action = "";
            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "sidebar";
            input.value = JSON.stringify(data);
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        window.onclick = function(event) {
            if (event.target == document.querySelector("dialog")) {
                document.querySelector("dialog").close();
            }
        }
    </script>
</body>

</html>