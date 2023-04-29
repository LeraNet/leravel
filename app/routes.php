<?php
$Router = new Router();

$Router->get("/", function () {
    template("index.php");
});

$Router->get("/test", function () {
    view("test.php");
});

$Router->get("/dynamic/{id}/{xd}", function ($args) {
    echo $args["id"] . " " . $args["xd"];
});

$Router->get("/media/{id}", function ($args) {
    $media = new Asset($args["id"]);
    $media->serve();
});

$Router->run();