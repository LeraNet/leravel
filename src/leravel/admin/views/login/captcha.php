<?php

header("Content-Type: image/png");

$browser = $_SERVER['HTTP_USER_AGENT'];

$safe = false;

if(strpos($browser, "Chrome") !== false) {
    $safe = true;
} else if(strpos($browser, "Firefox") !== false) {
    $safe = true;
} else if(strpos($browser, "Safari") !== false) {
    $safe = true;
} else if(strpos($browser, "Opera") !== false) {
    $safe = true;
} else if(strpos($browser, "Edge") !== false) {
    $safe = true;
}

if ($safe == true) {
    $width = 200;
    $height = 50;

    $image = imagecreatetruecolor($width, $height);

    $white = imagecolorallocate($image, 255, 255, 255);

    $black = imagecolorallocate($image, 0, 0, 0);

    $code = rand(1000, 9999);

    $_SESSION['captcha'] = $code;

    imagefill($image, 0, 0, $white);

    $font = "C:\Windows\Fonts\Arial.ttf";

    imagettftext($image, 30, 0, 50, 40, $black, $font, $code);

    for ($i = 0; $i < 2500; $i++) {
        $x = rand(0, $width);
        $y = rand(0, $height);
        imagesetpixel($image, $x, $y, $black);
    }

    for ($i = 0; $i < 50; $i++) {
        $x1 = rand(0, $width);
        $y1 = rand(0, $height);
        $x2 = rand(0, $width);
        $y2 = rand(0, $height);
        imageline($image, $x1, $y1, $x2, $y2, $black);
    }

    imagepng($image);

    imagedestroy($image);
} else {
    $width = 500;
    $height = 50;

    $image = imagecreatetruecolor($width, $height);

    $white = imagecolorallocate($image, 255, 255, 255);

    $black = imagecolorallocate($image, 0, 0, 0);

    $code = "This browser is not safe";

    imagefill($image, 0, 0, $white);

    $font = "C:\Windows\Fonts\Arial.ttf";

    imagettftext($image, 30, 0, 50, 40, $black, $font, $code);


    imagepng($image);

    imagedestroy($image);
}
