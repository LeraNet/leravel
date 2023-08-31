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
    redirect("/");
    exit();
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
        <h1><img src='https://img.icons8.com/?size=512&id=kDoeg22e5jUY&format=png' draggable="false"> Sidebar Editor</h1>
        <div class="tab-content">
            <h2>Edit Your Sidebar</h2>
            <p>Here you can edit your sidebar. You can add new items, remove items, change the order of items and more.</p>

            <fieldset>
                <legend>Current Items</legend>
                <div class="items">
                    <?php
                    $sidebarStuff = $currentUser["sidebar"] ?? [];
                    foreach ($sidebarStuff as $item) :
                    ?>
                        <div class="sidebarEdit-item" style="display: flex; align-items: center">
                            <button onclick="up(this)">⬆️</button> 
                            <button onclick="down(this)">⬇️</button> 
                            <div id="content">
                                <?= $item ?>
                            </div> 
                            <button onclick="remove(this)">❌</button>
                        </div>
                    <?php
                    endforeach;
                    ?>
                </div>
            </fieldset>
            <br>
            <fieldset>
                <legend>Add Item</legend>
                <select name="" id="selectItem">
                    <option value="">Select Item</option>
                    <?php
                    sort($allTools);
                    array_reverse($allTools);
                    foreach ($allTools as $category) :
                        sort($category["tools"]);
                        foreach ($category["tools"] as $tool) :
                            if (in_array($tool["perm"], $sidebarStuff )) {
                                continue;
                            }
                            if(checkPerm($tool["perm"]) == false){
                                continue;
                            }

                    ?>
                        <option value="<?= $tool["id"] ?>"><?= $tool["title"] ?></option>
                    <?php
                        endforeach;
                    endforeach;
                    ?>
                </select>
                <button id="add">add</button>
            </fieldset>
            <br>
            <fieldset>
                <legend>Save</legend>
                <button onclick="save()">save</button>
            </fieldset>
        </div>
    </div>

    <script>
        let selectItem = document.querySelector("#selectItem");
        let add = document.querySelector("#add");
        let items = document.querySelector(".items");

        add.addEventListener("click", () => {
            if (selectItem.value == "") {
                return;
            }
            let item = document.createElement("div");
            item.classList.add("sidebarEdit-item");
            item.style.display = "flex";
            item.style.alignItems = "center";
            item.innerHTML = `
                <button onclick="up(this)">⬆️</button> 
                <button onclick="down(this)">⬇️</button> 
                <div id="content">
                    ${selectItem.options[selectItem.selectedIndex].value}
                </div> 
                <button onclick="remove(this)">❌</button>
            `;
            items.appendChild(item);
        });

        function up(element) {
            let item = element.parentElement;
            let prev = item.previousElementSibling;
            if (prev == null) {
                return;
            }
            items.insertBefore(item, prev);
        }

        function down(element) {
            let item = element.parentElement;
            let next = item.nextElementSibling;
            if (next == null) {
                return;
            }
            items.insertBefore(next, item);
        }

        function remove(element) {
            let item = element.parentElement;
            item.remove();
        }

        function save() {
            let items = document.querySelectorAll(".items .sidebarEdit-item #content");
            let sidebar = [];
            items.forEach(item => {
                sidebar.push(item.innerText);
            });
            let data = {
                "sidebar": sidebar
            };
            //put all of these in a form element and submit
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
    </script>
</body>

</html>