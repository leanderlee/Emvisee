<?php

class service {
    
    public static $params = array();
    
    public static function setup() {
        if (!user::logged_in()) {
            return array('error' => 'Not logged in.');
        }
        self::$params = arr::union($_GET, $_POST);
    }
    
}

?>