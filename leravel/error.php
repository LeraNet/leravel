<?php

function customError($errno, $errstr, $errfile, $errline)
{
    $solution = null;

    $commonErrors = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/commonErrors.json");
    $commonErrors = json_decode($commonErrors, true);
    
    foreach ($commonErrors as $commonError) {
        if (strpos($errstr, $commonError["error"]) !== false) {
            if (strpos($errfile, $commonError["file"]) !== false) {
                $solution = $commonError["solution"];
            }
        }
    }
    

    echo "
        <style>
        table {
            border-collapse: collapse;
            background-color: red;
            padding: 10px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }

        body {
            background-image: linear-gradient(40deg, #744d4d, #ffffff);
            font-size: 1.2rem;
            line-height: 1.5;
            color: black;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
        }

        h1 {
            font-size: 2rem;
            font-weight: 400;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -1rem;
        }

        .col {
            flex: 1 1 100%;
            padding: 0 1rem;
        }

        .center {
            text-align: center;
        }

        .centerbox {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }

        .btn:hover {
            background-color: #444;
        }

        .btn img {
            width: 2.5rem;
        }

        .logo {
            width: 100%;
            max-width: 300px;
        }

        ::-webkit-scrollbar {
            width: 0.4rem;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        h2 {
            font-size: 1.2rem;
            font-weight: 400;
            margin: 0;
            padding: 0;
        }
        
        fieldset {
            border: 1px solid black;
            border-radius: 5px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        legend {
            font-size: 1.2rem;
            font-weight: 400;
            margin: 0;
            padding: 0;
            margin-left: 10px;
            margin-right: 10px;
        }
    </style>
        ";
        echo '<div class="container">';
        echo "<div class='centerbox'>";
        echo "<h1 style='color: red;'>ERROR</h1>";
    echo '<img src="https://cdn.discordapp.com/attachments/989920686065725490/1097604721369423953/leravellogo.png" alt="" class="logo" draggable="false">';
    echo "<h1>Leravel</h1>";
    echo "<h2>Something went wrong</h2>";
    echo "</div>";
    echo "<br>";
    echo "<div class='centerbox'>";
    $extra = "";
    if (strpos($errfile, "leravel") !== false) {
        if($solution != null) {
            echo "<br>";
            echo "<h2>There is a solution for this error</h2>";
            echo "<table>";
            echo "<tr><td>Solution</td><td>" . $solution . "</td></tr>";
            echo "</table>";
            echo "<br>";
            $extra = "<span>If the solution didn't help.</span>";
        }
        echo "<table>";
        echo "<tr><td>Error Type</td><td>Leravel Error</td></tr>";
        echo "<tr><td>Error</td><td>" . $errno . "</td></tr>";
        echo "<tr><td>Message</td><td>" . $errstr . "</td></tr>";
        echo "<tr><td>File</td><td>" . $errfile . "</td></tr>";
        echo "<tr><td>Line</td><td>" . $errline . "</td></tr>";
        echo "</table>";
        echo "<br>";
        echo "<fieldset>";
        echo "<legend>Report The Error</legend>";
        echo $extra . '<span>Please use</span><a href="https://github.com/lera2od/leravel" class="btn" style="width: fit-content"><img src="https://img.icons8.com/fluency/256/github.png">Github</a> to report this error';
        echo "</fieldset>";
    } else {
        echo "<table>";
        echo "<tr><td>Error</td><td>" . $errno . "</td></tr>";
        echo "<tr><td>Message</td><td>" . $errstr . "</td></tr>";
        echo "<tr><td>File</td><td>" . $errfile . "</td></tr>";
        echo "<tr><td>Line</td><td>" . $errline . "</td></tr>";
        echo "</table>";
    }
    echo "<br>";
    echo "</div>";
    echo "</div>";

    die();
}

function customShutdown() {
    $error = error_get_last();
    if ($error !== NULL) {
        customError($error["type"], $error["message"], $error["file"], $error["line"]);
    }
    die();
}

set_error_handler("customError");
register_shutdown_function('customShutdown');

?>
