<?php 


function lang($key) {
    global $Leravel;
    return $Leravel["lang"][$key];
}

function changeLang($key) {
    setcookie("leravel_lang", $key, 0, "/");
}

function template($view, $data = []) {
    global $Leravel;
    extract($data);

    if (is_array($view)) {
        foreach ($view as $v) {
            $content .= file_get_contents($Leravel["settings"]["router"]["views"] . "/$v");
        }
    } else {
        $content = file_get_contents($Leravel["settings"]["router"]["views"] . "/$view");
    }

    $content = str_replace(array(
        "{{","}}","{!","!}","{[","]}"
    ), array(
        "<?php echo ",
        "; ?>"
        ,"<?= htmlspecialchars(",") ?>"
        ,'<?= lang("','"); ?>'
    ), $content);

    $content = preg_replace(array(
        '/@(\w+\s*\(.*\))/',
        '/@\s*end(\w+)/s',
        '/@\s*(\w+)/s',
    ),
    array(
        '<?php $1: ?>',
        '<?php end$1; ?>',
        '<?php $1: ?>',
    ), $content);

    eval("?>$content<?php ");
}