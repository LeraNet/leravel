<?php 

if(!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/stats.json")) {
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/stats.json", json_encode(array()));
}

$stats = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/stats.json"), true);
$leraveljson = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/json/leravel.json"), true);

if(isset($leraveljson["statWeek"]) && $leraveljson["statWeek"] == date("W")) {
    $week = date("W");
    $year = date("Y");
    $file = $_SERVER["DOCUMENT_ROOT"] . "/app/stats/" . $week . "-" . $year . ".json";
    if(!is_dir($_SERVER["DOCUMENT_ROOT"] . "/app/stats/")) {
        mkdir($_SERVER["DOCUMENT_ROOT"] . "/app/stats/");
    }
    fopen($file,"w");
    file_put_contents($file, json_encode($stats));
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/stats.json", json_encode(array()));
    $leraveljson["statWeek"] = date("W") + 1;
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/json/leravel.json", json_encode($leraveljson));
}

if(!isset($leraveljson["statWeek"])) {
    $leraveljson["statWeek"] = date("W") + 1;
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/leravel/json/leravel.json", json_encode($leraveljson));
}

$os = [
    "Windows" => "Windows",
    "Linux" => "Linux",
    "Mac" => "Mac",
    "Android" => "Android",
    "iOS" => "iOS"
];

$device = [
    "Windows" => "PC",
    "Linux" => "PC",
    "Mac" => "PC",
    "Android" => "Mobile",
    "iOS" => "Mobile"
];

function getOs($agent) {
    global $os;
    if (preg_match('/linux/i', $agent)) {
        return "Linux";
    } elseif (preg_match('/macintosh|mac os x/i', $agent)) {
        return "Mac";
    } elseif (preg_match('/windows|win32/i', $agent)) {
        return "Windows";
    } elseif (preg_match('/android/i', $agent)) {
        return "Android";
    } elseif (preg_match('/iphone/i', $agent)) {
        return "iOS";
    } else {
        return "Unknown";
    }
}
$uri = $_SERVER["REQUEST_URI"];
if (strpos($uri, "?admin") == false && strpos($uri, "?update") == false) {

if($uri == "/favicon.ico") return;

if($_SERVER["REMOTE_ADDR"] == "127.0.0.1" || $_SERVER["REMOTE_ADDR"] == "::1") return;

$stats[] = [
    "url" => $_SERVER["REQUEST_URI"],
    "ip" => $_SERVER["REMOTE_ADDR"],
    "date" => date("d/m/Y"),
    "time" => date("H:i:s"),
    "browser" => $_SERVER["HTTP_USER_AGENT"],
    "os" => $os[getOS($_SERVER["HTTP_USER_AGENT"])],
    "device" => $device[getOS($_SERVER["HTTP_USER_AGENT"])]
];
file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/stats.json", json_encode($stats));
}

?>