<?php 
function cleanVariables() {
    $allVariables = get_defined_vars();
    
    $currentFunctionVariables = array_keys($allVariables);

    $unsetPoint = array_search('point_variable', $currentFunctionVariables);

    if ($unsetPoint !== false) {
        for ($i = 0; $i < $unsetPoint; $i++) {
            $varName = $currentFunctionVariables[$i];
            if ($varName !== 'Laravel') {
                unset(${$varName});
            }
        }
    }
}