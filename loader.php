<?php
/*
    
    Ideally, this should be loading from a lookup table
    of some sort, since disk searching is expensive.
    
*/

class Loader {
    
    private static $prefixes = [];
    
    public static function addPrefix($prefix) {
        array_push(Loader::$prefixes, $prefix);
    }
    
    public static function load($class_name) {
        
        foreach (Loader::$prefixes as $prefix) {
        
            $suggestedFileName = $prefix . DIRECTORY_SEPARATOR . $class_name . '.php';
            if (file_exists($suggestedFileName)) {
                include_once($suggestedFileName);
                return;
            }
        }
        
        return false;
        
    }
    
}

Loader::addPrefix('lib');
Loader::addPrefix('lib/leander');

spl_autoload_register('Loader::load');

?>