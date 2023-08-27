<?php

error_reporting(0);

function customError($errno, $errstr, $errfile, $errline)
{
    global $Leravel;

    $commonErrors = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/commonErrors.json");
    $commonErrors = json_decode($commonErrors, true);

    if (!isset($Leravel["settings"]["errors"]["devErrorPage"]) || $Leravel["settings"]["errors"]["devErrorPage"] == true) {
        $solution = null;

        foreach ($commonErrors as $commonError) {
            if (strpos($errstr, $commonError["error"]) !== false) {
                if (strpos($errfile, $commonError["file"]) !== false) {
                    $solution = $commonError["solution"];
                }
            }
        }


        echo "<style>h1,h2{margin:0}.centerbox,.row,fieldset{display:flex}table{border-collapse:collapse;background-color:red}table,td,th{border:1px solid #000;padding:5px}body{background-image:linear-gradient(40deg,#744d4d,#fff);font-size:1.2rem;line-height:1.5;color:#000;background-position:center;background-repeat:no-repeat;background-size:cover;min-height:100vh;font-family:Roboto,sans-serif}h1{font-size:2rem;font-weight:400;padding:0}h2,legend{font-size:1.2rem;font-weight:400;padding:0}.container{width:100%;max-width:1200px;margin:0 auto;padding:0 1rem}.row{flex-wrap:wrap;margin:0 -1rem}.col{flex:1 1 100%;padding:0 1rem}.center{text-align:center}.centerbox{justify-content:center;align-items:center;flex-direction:column}.btn{display:inline-block;padding:.5rem 1rem;border-radius:.25rem;background-color:#333;color:#fff;text-decoration:none;transition:.2s ease-in-out;display:flex;align-items:center}.btn:hover{background-color:#444}.btn img{width:2.5rem}.logo{width:100%;max-width:300px}::-webkit-scrollbar{width:.4rem}::-webkit-scrollbar-track{background:#f1f1f1}::-webkit-scrollbar-thumb{background:#888;border-radius:5px}fieldset{border:1px solid #000;border-radius:5px;padding:20px;justify-content:center;align-items:center;flex-direction:column}legend{margin:0 10px}.solution {background-color: #33e361;padding: 10px;border-radius: 10px;box-shadow: 0px 0px 15px 4px black;font-family: monospace;}.solution table {background-color: #fffdfd5e;color: black;}.solution h2 {font-size: 26px;color: #000000c7;}.sidebar{width: 0px !important; transition: 0.4s all ease-in-out}.content{width: 100%!important; transition: 0.4s all ease-in-out; margin: 30px;}</style>";
        echo '<div class="container">';
        echo "<div class='centerbox'>";
        echo "<h1 style='color: red;'>ERROR</h1>";
        echo "<h2>Something went wrong</h2>";
        echo "</div>";
        echo "<br>";
        echo "<div class='centerbox'>";
        $extra = "";
        if (strpos($errfile, "leravel") !== false) {
            echo "<table>";
            echo "<tr><td>Error Type</td><td>Leravel Error</td></tr>";
            echo "<tr><td>Error</td><td>" . $errno . "</td></tr>";
            echo "<tr><td>Message</td><td>" . $errstr . "</td></tr>";
            echo "<tr><td>File</td><td>" . $errfile . "</td></tr>";
            echo "<tr><td>Line</td><td>" . $errline . "</td></tr>";
            echo "</table>";
            echo "<br>";
            if ($solution != null) {
                echo "<br>";
                echo "<div class='solution'>";
                echo "<h2>There is a solution for this error</h2>";
                echo "<table>";
                echo "<tr><td>Solution</td><td>" . $solution . "</td></tr>";
                echo "</table>";
                echo "</div>";
                echo "<br>";
            }
            echo "<fieldset>";
            echo "<legend>Report The Error</legend>";
            echo '<a href="https://github.com/lera2od/leravel" class="btn" style="width: fit-content"><img src="https://img.icons8.com/fluency/256/github.png"> Report the error using Github</a>';
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
    }else{
        if(!isset($Leravel["settings"]["errors"]["errorFile"])) {
            echo "errorRoute ayarlanmamış.";
        }else{
            foreach ($commonErrors as $commonError) {
                if (strpos($errstr, $commonError["error"]) !== false) {
                    if (strpos($errfile, $commonError["file"]) !== false) {
                        $errorName = $commonError["errorName"];
                    }
                }
            }
    
    
            $Leravel["errorData"]["errno"] = $errno;
            $Leravel["errorData"]["errstr"] = $errstr;
            $Leravel["errorData"]["errfile"] = $errfile;
            $Leravel["errorData"]["errline"] = $errline;
            $Leravel["errorData"]["leravelErroName"] = $errorName;
            require $_SERVER["DOCUMENT_ROOT"] . "/app/views/" . $Leravel["settings"]["errors"]["errorFile"];
        }
    }
    die();
}

function customShutdown()
{
    $error = error_get_last();
    if ($error !== NULL) {
        customError($error["type"], $error["message"], $error["file"], $error["line"]);
    }
    die();
}

set_error_handler("customError");
register_shutdown_function('customShutdown');
