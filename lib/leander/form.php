<?php

class form {
    
    public static function get($key, $def = '') {
        return arr::get($_GET, $key, $def);
    }
    
    public static function post($key, $def = '') {
        return arr::get($_POST, $key, $def);
    }
    
    public static function in($key, $def = '') {
        return arr::get(arr::union($_GET, $_POST), $key, $def);
    }
    
    public static function file($key, $def = '') {
        return arr::get($_FILES, $key, $def);
    }
    
}


?>