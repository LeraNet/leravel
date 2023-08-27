<?php 

class LeravelJsonDatabase {
    private $data = [];
    private $db = "db";

    public function __construct($db = "db") {
        if(!is_dir($_SERVER["DOCUMENT_ROOT"] . "/app/db/")) {
            mkdir($_SERVER["DOCUMENT_ROOT"] . "/app/db/");
        }
        if(!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/db/" . $db . ".json")) {
            file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/db/" . $db . ".json", "{}");
        }
        $this->data = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app//db/" . $db . ".json"), true);
        $this->db = $db;
    }

    public function set($key, $value) {
        $keys = explode('.', $key);
        $current =& $this->data;

        foreach ($keys as $keyPart) {
            if (!isset($current[$keyPart])) {
                $current[$keyPart] = [];
            }
            $current =& $current[$keyPart];
        }

        $current = $value;
    }

    public function get($key) {
        $keys = explode('.', $key);
        $current = $this->data;

        foreach ($keys as $keyPart) {
            if (!isset($current[$keyPart])) {
                return null;
            }
            $current = $current[$keyPart];
        }

        return $current;
    }

    public function save($db = "db") {
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/db/" . $db . ".json", json_encode($this->data));
    }

    public function __destruct() {
        $this->save($this->db);
    }
}

