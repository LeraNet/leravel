<?php

$tables = glob($_SERVER["DOCUMENT_ROOT"] . "/app/db/*.json");
$currenTable = $_GET["table"] ?? null;
$page = $_GET["page"] ?? 1;

if (isset($_GET["load_more"])) {
    //todo : pagination later
    header('Content-Type: application/json');

    function extractNodes($array, $page, $itemsPerPage, &$counter)
    {
        $result = [];

        foreach ($array as $key => $value) {
            if ($counter >= $page * $itemsPerPage) {
                break;
            }

            if (is_array($value)) {
                $subArray = extractNodes($value, $page, $itemsPerPage, $counter);
                if (!empty($subArray)) {
                    $result[$key] = $subArray;
                }
            } else {
                $result[$key] = $value;
                $counter++;
            }
        }

        return $result;
    }

    function loadNodes($page, $itemsPerPage)
    {
        $table = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/db/" . $_GET["table"]), true);
        $counter = 0;

        return extractNodes($table, $page, $itemsPerPage, $counter);
    }

    $page = $_GET['page'] ?? 1;
    $itemsPerPage = 10;

    $moreNodes = loadNodes($page, $itemsPerPage);

    echo json_encode($moreNodes);
    exit();
}

$debug = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateTable') {
    $table_name = $_POST['table_name'];
    $table = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/db/" . $table_name), true);

    function updateValue(&$data, $keys, $value)
    {
        $currentKey = array_shift($keys);
        if (empty($keys)) {
            $data[$currentKey] = $value;
        } else {
            updateValue($data[$currentKey], $keys, $value);
        }
    }

    foreach ($_POST as $key => $value) {
        if ($key !== 'action' && $key !== 'table_name') {
            $keys = explode('>', $key);
            if (count($keys) > 1) {
                $targetKey = array_pop($keys); // Get the target key (e.g., "username")
                $parentArray = &$table;

                foreach ($keys as $parentKey) {
                    if (isset($parentArray[$parentKey]) && is_array($parentArray[$parentKey])) {
                        $parentArray = &$parentArray[$parentKey];
                    } else {
                        // Invalid key path, skip this update
                        continue 2;
                    }
                }

                if (isset($parentArray[$targetKey]) && $parentArray[$targetKey] == $value) {
                    continue;
                }

                $parentArray[$targetKey] = $value;
                $debug .= "Editing $key to $value\n";
            }
        }
    }

    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/db/" . $table_name, json_encode($table, JSON_PRETTY_PRINT));
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "createTable") {
    $table_name = $_POST["table_name"];
    $table = [];

    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/db/" . $table_name . ".json", json_encode($table, JSON_PRETTY_PRINT));
    header("Location: /?admin&route=tool&tool=leraveljsondb&table=" . $table_nam . ".json");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel JSON Database Manager</title>
    <link rel="stylesheet" href="/?admin&route=css&<?= time() ?>">
</head>

<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/header.php" ?>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/include/sidebar.php" ?>

    <?php if (!isModuleActive("leravelJsonDatabase", $activeModules)) :
    ?>
        <div class="content">
            <h1><img src='<?= $icons["database"] ?>' draggable="false">LeravelJsonDatabase Manager</h1>
            <div class="tab-content">
                <h3>Database is disabled!</h3>
                <p>Enable the leraveljsondb in the <a href="/?admin&route=tool&tool=modules">modules manager</a> to use the leraveljsondb manager.</p>
                <details>
                    <summary>+</summary>
                    This page doesn't need the class but still it is not practical to use here if you are not gonna use the class. And also in the future it may will???
                </details>
            </div>
        <?php
        exit();
    endif;
        ?>
        <div class="content">
            <h1><img src='<?= $icons["database"] ?>' draggable="false">LeravelJsonDatabase Manager</h1>
            <div class="tabs">
                <?php
                foreach ($tables as $table) {
                    if ($table[0] == $currenTable) {
                        echo "<a href='/?admin&route=tool&tool=leraveljsondb&table=" . basename($table) . "' class='tab tab-active'><img src='$icons[table]' >" . basename($table) . "</a><br>";
                    } else
                        echo "<a href='/?admin&route=tool&tool=leraveljsondb&table=" . basename($table) . "' class='tab'><img src='$icons[table]' >" . basename($table) . "</a><br>";
                }
                ?>
                <a href="/?admin&route=tool&tool=leraveljsondb&tablecreate=1" class="tab">+</a>
            </div>
            <?php if ($currenTable != null) : ?>
                <div class="tab-content">
                    <h2><img src="<?= $icons["table"] ?>" alt=""><?php echo $currenTable; ?></h2>
                    <div class="alert alert-warning">
                        <div>
                            <h2>Warning</h2>
                            <p>This is early work in progress. You can't generate new data using this. Also there is no pagination which is always a bad thing.</p>
                        </div>
                    </div>
                    <br>
                    <form action="" method="post">
                        <input type="hidden" value="updateTable" name="action">
                        <input type="hidden" value="<?= $currenTable ?>" name="table_name">
                        <div id="data-container">
                            <div class="data-row">
                                <input type="text" id="new" placeholder="new">
                                <button type="button" id="add" onclick="addNewRoot(this)">Add</button>
                            </div>
                            <?php

                            $table = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/db/" . $currenTable), true);

                            function paginateTree($data, $parents = "")
                            {
                                $html = "";

                                foreach ($data as $key => $value) {
                                    if (is_array($value)) {
                                        $html .= "<details><summary>$key</summary>";
                                        $html .= "<div class='data-row'>";
                                        $html .= "<input type='text' id='new' placeholder='new'>";
                                        $html .= "<button type='button' id='add' onclick='addNew(this)' parents='" . $parents . "$key>" . "'>Add</button>";
                                        $html .= "</div>";
                                        $html .= paginateTree($value, $parents . "$key>");
                                        $html .= "</details>";
                                    } else {
                                        $html .= "<div class='data-row'>";
                                        $html .= "<label for='$parents$key'>$key</label>";
                                        $html .= "<input type='text' name='$parents$key' id='$parents$key' value='$value'>";
                                        $html .= "</div>";
                                    }
                                }

                                return $html;
                            }

                            $html = paginateTree($table);

                            echo $html;
                            ?>
                        </div>
                        <br>
                        <input type="submit" value="Save">
                    </form>
                    <script>
                        document.querySelectorAll("#data-container input").forEach(function(input) {
                            input.addEventListener("change", function() {
                                input.classList.add("changed");
                                input.parentElement.parentElement.classList.add("changed");
                            })
                        })

                        function addNew(button) {
                            var input = button.parentElement.querySelector("input");
                            var parent = button.parentElement.parentElement.parentElement;
                            var parents = button.getAttribute("parents");
                            var key = input.value;
                            var value = "";

                            if (key == "") {
                                alert("Please fill the fields");
                                return;
                            }

                            var html = "<div class='data-row'>";
                            html += "<label for='" + parents + key + "'>" + key + "</label>";
                            html += "<input type='text' name='" + parents + key + "' id='" + parents + key + "' value='" + value + "'>";
                            html += "</div>";
                            parent.innerHTML += html;
                        }

                        function addNewRoot(button) {
                            var input = button.parentElement.querySelector("input");
                            var parent = button.parentElement;
                            var key = input.value;
                            var value = "";

                            if (key == "") {
                                alert("Please fill the fields");
                                return;
                            }

                            var html = "<details><summary>" + key + "</summary>";
                            html += "<div class='data-row'>";
                            html += "<input type='text' id='new' placeholder='new'>";
                            html += "<button type='button' id='add' onclick='addNew(this)' parents='" + key + "'>Add</button>";
                            html += "</div>";
                            html += "</details>";
                            parent.innerHTML += html;
                        }
                    </script>
                    <?php
                    if ($debug != "") {
                        echo "<details><summary>Operation Details</summary><pre>$debug</pre></details>";
                    }
                    ?>
                </div>
            <?php elseif (isset($_GET["tablecreate"])) : ?>
                <div class="tab-content">
                    <h2>Create a database!</h2>
                    <form action="" method="post" class="db_form-veritical" autocomplete="off">
                        <input type="hidden" value="createTable" name="action">
                        <label for="table_name">Name</label><br>
                        <input required type="text" name="table_name" id="table_name"><br>
                        <br>
                        <input type="submit" value="Create">
                    </form>
                </div>
                <script>
                    document.querySelector("#typesadd").addEventListener("click", function() {
                        document.querySelector("#table_columns").value += "\n" + "test:" + document.querySelector("#types").value;
                    })
                </script>
            <?php else : ?>
                <div class="tab-content">
                    <h2>Select a database!</h2>
                </div>
            <?php endif; ?>
        </div>
</body>

</html>