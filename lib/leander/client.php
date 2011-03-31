<?php

class client {
    
    public static function destroy($name) {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION[$name])) {
            $SESSION[$name] = '';
            unset($_SESSION[$name]);
        }
    }
    
    public static function session($name, $val = '') {
        if (!isset($_SESSION)) {
            session_start();
        }
        if ($val == '') {
            if (isset($_SESSION[$name])) {
                return $_SESSION[$name];
            }
            else {
                return '';
            }
        }
        else {
            $_SESSION[$name] = $val;
        }
    }
    
    public static function post($name) {
        // TODO: Verify sauce
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        else {
            return '';
        }
    }
    
    public static function get($name) {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        else {
            return '';
        }
    }
    
}


?>