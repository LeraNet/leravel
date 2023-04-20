<?php 

class Asset{

    private $id;

    public function __construct($id) {
        $this->id = $id;
    }
    
    public function serve() {
        $file = $_SERVER["DOCUMENT_ROOT"] . "/app/media/" . $this->id;
        if (file_exists($file)) {
            $mime = mime_content_type($file);
            header("Content-Type: " . $mime);
            readfile($file);
        } else {
            header("HTTP/1.0 404 Not Found");
        }
    }
}