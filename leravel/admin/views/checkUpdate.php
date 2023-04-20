<?php 

$leravelInfo = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/leravel.json"), true);

if(!isset($leravelInfo["lastUpdateCheck"])){
    $leravelInfo["lastUpdateCheck"] = 0;
}

if ($leravelInfo["lastUpdateCheck"] < time() - 86400) {

    $url = "https://api.github.com/repos/lera2od/leravel/releases/latest";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36",
        "Accept: application/vnd.github+json"
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if(!function_exists("toast")){
        function toast($message, $type = "success") {
            echo "<div class='toast toast-$type'>$message</div>";
            echo "<script>
            setTimeout(() => {
                document.querySelector('.toast').style.top = '20px';
            }, 500);
            </script>";
        }
    }

    $latestVersion = $data["tag_name"];
    $latestVersion = str_replace("v", "", $latestVersion);
    $leravelVersion = $leravelInfo["version"];
    if($latestVersion > $leravelVersion){
        toast("New update available", "success");
    }else{
        toast("You are up to date", "success");
    }
    $leravelInfo["lastUpdateCheck"] = time();
    $leravelInfo["latestVersion"] = $latestVersion;

    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/leravel.json", json_encode($leravelInfo, JSON_PRETTY_PRINT));
}