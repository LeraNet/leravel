<?php

$Leravel["settings"] = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/settings.json"), true);
$Leravel["current_lang"] = $_COOKIE["leravel_lang"] ?? $Leravel["settings"]["lang"]["default"];
$Leravel["lang"] = json_decode(file_get_contents("app/localization/" . $Leravel["settings"]["lang"][$Leravel["current_lang"]] . ".json"), true);

if (isset($_POST["lang"])) {
    $Leravel["current_lang"]  = $_POST["lang"];
    if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/output/")) {
        mkdir($_SERVER["DOCUMENT_ROOT"] . "/output/");
    }
    if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/output/css")) {
        mkdir($_SERVER["DOCUMENT_ROOT"] . "/output/css");
    }

    if (!empty(glob($_SERVER["DOCUMENT_ROOT"] . "/output/*"))) {
        foreach (glob($_SERVER["DOCUMENT_ROOT"] . "/output/*") as $file) {
            if (is_dir($file)) {
                continue;
            } else {
                unlink($file);
            }
        }
    }

    if (!empty(glob($_SERVER["DOCUMENT_ROOT"] . "/output/css/*"))) {
        foreach (glob($_SERVER["DOCUMENT_ROOT"] . "/output/css/*") as $file) {
            if (is_dir($file)) {
                continue;
            } else {
                unlink($file);
            }
        }
    }
    function extractUsedCSS($content)
    {
        $pattern = '/class\s*=\s*["\']([^"\']+)["\']|style\s*=\s*["\']([^"\']+)["\']/';
        preg_match_all($pattern, $content, $matches);
        $usedClassesAndStyles = array_merge($matches[1], $matches[2]);
        $usedClassesAndStyles = array_unique(array_filter($usedClassesAndStyles));
        return $usedClassesAndStyles;
    }

    function generateCSSFile($cssFileName, $usedClassesAndStyles)
    {
        $cssContent = implode(' ', $usedClassesAndStyles);
        file_put_contents($cssFileName, $cssContent);
    }

    function convertToStaticHTMLWithCSS($inputFile, $outputFile)
    {
        ob_start();
        template($inputFile);
        $content = ob_get_clean();
        $usedClassesAndStyles = extractUsedCSS($content);
        $cssFileName = $_SERVER["DOCUMENT_ROOT"] . "/output/css/" . basename($outputFile, '.html') . '.css';
        generateCSSFile($cssFileName, $usedClassesAndStyles);
        $content = '<link rel="stylesheet" href="' . $cssFileName . '">' . $content;
        file_put_contents($outputFile, $content);
        echo $inputFile . " done <br>";
    }

    function convertAllPHPToStaticHTMLWithCSS($sourceDir, $outputDir)
    {
        $phpFiles = glob($sourceDir . '/*.php');
        foreach ($phpFiles as $phpFile) {
            $fileName = basename($phpFile);
            $outputFile = $outputDir . '/' . str_replace('.php', '.html', $fileName);
            convertToStaticHTMLWithCSS($fileName, $outputFile);
        }
    }

    $sourceDir = $Leravel["settings"]["router"]["views"];
    $outputDir = $_SERVER["DOCUMENT_ROOT"] . "/output";

    convertAllPHPToStaticHTMLWithCSS($sourceDir, $outputDir);
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
        <h1><img src='https://img.icons8.com/?size=512&id=QwEwqmJDk4Za&format=png' draggable="false"> Static Site Generator</h1>
        <div class="tab-content">
            <h2>Generate Site</h2>
            <p>Warning : This is work in progress so it can give you some errors.</p>
            <p>Warning : You need to make sure that there is no errors in any of the pages or else you will see an error.</p>
            <form action="" method="post">
                <label for="lang">Language Used :</label>
                <input type="text" name="lang" id="" placeholder="Language Used" value="<?= $_COOKIE["leravel_lang"] ?? $Leravel["settings"]["lang"]["default"] ?>">
                <input type="submit" value="Generate">
            </form>
        </div>
    </div>
</body>

</html>