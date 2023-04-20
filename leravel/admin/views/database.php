<?php
$conn = $Leravel["conn"];

include "include/toast.php";

$tables = $conn->query("SHOW TABLES");

$currenTable = $_GET["table"] ?? null;

if (isset($_POST["action"])) {
    if ($_POST["action"] == "create") {
        $columns = $conn->query("SHOW COLUMNS FROM " . $currenTable);
        $values = [];
        while ($column = $columns->fetch_array()) {
            $values[] = $_POST[$column[0]];
        }
        $stmt = $conn->prepare("INSERT INTO " . $currenTable . " VALUES (" . implode(",", array_fill(0, count($values), "?")) . ")");
        $stmt->bind_param(str_repeat("s", count($values)), ...$values);
        $stmt->execute();

        if ($stmt->error) {
            echo $stmt->error;
        } else {
            header("Location: ?admin&route=database&table=" . $currenTable . "&success=created");
            exit;
        }
    }
    if ($_POST["action"] == "createTable") {
        $columns = explode("\n", $_POST["table_columns"]);
        $columns = array_map(function ($column) {
            return explode(":", $column);
        }, $columns);
        $columns = array_map(function ($column) {
            return $column[0] . " " . $column[1];
        }, $columns);
        $columns = implode(",", $columns);
        $stmt = $conn->prepare("CREATE TABLE " . $_POST["table_name"] . " (" . $columns . ")");
        $stmt->execute();

        if ($stmt->error) {
            echo $stmt->error;
        } else {
            header("Location: ?admin&route=database&table=" . $_POST["table_name"] . "&success=tablecreated");
            exit;
        }
    }
}

if (isset($_GET["tablecreate"])) {
    $currenTable = null;
}

if (isset($_GET["delete"])) {
    $stmt = $conn->prepare("DELETE FROM " . $currenTable . " WHERE id = ?");
    $stmt->bind_param("s", $_GET["delete"]);
    $stmt->execute();

    if ($stmt->error) {
        echo $stmt->error;
    } else {
        header("Location: ?admin&route=database&table=" . $currenTable . "&success=deleted");
        exit;
    }
}

if (isset($_GET["deleteTable"])) {
    $stmt = $conn->prepare("DROP TABLE " . $_GET["table"]);
    $stmt->execute();

    if ($stmt->error) {
        echo $stmt->error;
    } else {
        header("Location: ?admin&route=database" . "&success=tabledeleted");
        exit;
    }
}

if (isset($_GET["success"])) {
    if ($_GET["success"] == "created") {
        toast("Successfully created!", "success");
    }
    if ($_GET["success"] == "deleted") {
        toast("Successfully deleted!", "success");
    }
    if ($_GET["success"] == "tablecreated") {
        toast("Successfully created table!", "success");
    }
    if ($_GET["success"] == "tabledeleted") {
        toast("Successfully deleted table!", "success");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Database Manager</title>
    <link rel="stylesheet" href="/?admin&route=css">
</head>

<body>
    <?php include "include/header.php" ?>
    <?php include "include/sidebar.php" ?>
    <div class="content">
        <h1>Database Manager!</h1>
        <div class="tabs">
            <?php
            while ($table = $tables->fetch_array()) {
                if ($table[0] == $currenTable) {
                    echo "<a href='?admin&route=database&table=" . $table[0] . "' class='tab tab-active'>" . $table[0] . "</a><br>";
                } else
                    echo "<a href='?admin&route=database&table=" . $table[0] . "' class='tab'>" . $table[0] . "</a><br>";
            }
            ?>
            <a href="?admin&route=database&tablecreate=1" class="tab">+</a>
        </div>
        <?php if ($currenTable != null) : ?>
            <div class="tab-content">
                <h2><?php echo $currenTable; ?></h2>
                <form action="" method="post" class="db_form" autocomplete="off">
                    <input type="hidden" value="create" name="action">
                    <?php
                    $columns = $conn->query("SHOW COLUMNS FROM " . $currenTable);
                    $types = [
                        "int" => "number",
                        "varchar" => "text",
                        "text" => "textarea",
                        "date" => "date",
                        "datetime" => "datetime-local"
                    ];

                    while ($column = $columns->fetch_array()) {
                        $type = $types[explode("(", $column[1])[0]] ?? "text";
                        echo "<label for='" . $column[0] . "'>" . $column[0] . "</label><br>";
                        echo "<input type='$type' name='" . $column[0] . "' id='" . $column[0] . "'><br>";
                    }
                    ?>
                    <input type="submit" value="Ekle">
                </form>
                <br>
                <div class="hr"></div>
                <br>
                <table>
                    <tr>
                        <?php
                        $thereIsAnId = false;
                        $columns = $conn->query("SHOW COLUMNS FROM " . $currenTable);
                        while ($column = $columns->fetch_array()) {
                            echo "<th>" . $column[0] . "</th>";
                            if($column[0] == "id"){
                                $thereIsAnId = true;
                            }
                        }
                        ?>
                        <th></th>
                    </tr>
                    <?php
                    $offset = ($_GET["page"] ?? 1) * 50 - 50;
                    $rows = $conn->query("SELECT * FROM " . $currenTable . " LIMIT 50 OFFSET $offset");
                    while ($row = $rows->fetch_array()) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            if (is_numeric($key)) {
                                continue;
                            }
                            echo "<td>" . $value . "</td>";
                        }
                        if($thereIsAnId == true){
                            echo "<td><a class='btn' href='?admin&route=database&table=" . $currenTable . "&delete=" . $row["id"] . "'>Delete</a></td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </table>
                <br>
                <select name="" id="pagination">
                    <?php
                    $rows = $conn->query("SELECT COUNT(*) FROM " . $currenTable);
                    $row = $rows->fetch_array();
                    $count = $row[0];
                    $pages = ceil($count / 50);
                    for ($i = 1; $i <= $pages; $i++) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
                <script>
                    document.getElementById("pagination").addEventListener("change", function() {
                        window.location.href = "?admin&route=database&table=<?php echo $currenTable; ?>&page=" + this.value;
                    })
                </script>
                <br>
                <br>
                <div class="hr"></div>
                <br>
                <a id="openscary" class="btn">Scary Buttons</a>
                <b></b>
                <div id="scary" style="display: none;">
                    <h1>Danger!</h1>
                    <h2>Ultra scary buttons!</h2>
                    <a href="?admin&route=database&table=<?php echo $currenTable; ?>&deleteTable=1" class="btn">Delete the table.</a>
                </div>
                <script>
                    let scary = 0
                    document.getElementById("openscary").addEventListener("click", function() {
                        if (scary == 0) {
                            document.getElementById("scary").style.display = "block";
                            scary = 1;
                        } else {
                            document.getElementById("scary").style.display = "none";
                            scary = 0;
                        }
                    })
                </script>
            </div>
        <?php elseif (isset($_GET["tablecreate"])) : ?>
            <div class="tab-content">
                <h2>Create a table!</h2>
                <form action="" method="post" class="db_form-veritical" autocomplete="off">
                    <input type="hidden" value="createTable" name="action">
                    <label for="table_name">Name</label><br>
                    <input type="text" name="table_name" id="table_name"><br>
                    <label for="table_columns">Rows</label><br>
                    <textarea name="table_columns" id="table_columns" cols="30" rows="10" placeholder="id int 
name varchar(255) 
description text"></textarea><br>
                    <input type="submit" value="Create">
                </form>
            </div>
        <?php else : ?>
            <div class="tab-content">
                <h2>Select a table!</h2>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>