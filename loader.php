<?php
/*
    
    Ideally, this should be loading from a lookup table
    of some sort, since disk searching is expensive.
    
*/

function class_loader($class_name) {
    include_once 'lib/' . $class_name . '.php';
}

function leander_loader($class_name) {
    include_once 'lib/leander/' . $class_name . '.php';
}

spl_autoload_register('class_loader');
spl_autoload_register('leander_loader');

?>