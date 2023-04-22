<?php 

function input($key) {
    global $Leravel;
    if($Leravel["request"]["method"] == "PUT" || $Leravel["request"]["method"] == "DELETE") {
        parse_str(file_get_contents("php://input"), $params);
    }else if($Leravel["request"]["method"] == "GET"){
        $params = $Leravel["request"]["query"];
    }else if($Leravel["request"]["method"] == "POST"){
        $params = $Leravel["request"]["body"];
    }

    if(isset($params[$key])) {
        $value = $params[$key];
        return $value;
    }else{
        return null;
    }
}