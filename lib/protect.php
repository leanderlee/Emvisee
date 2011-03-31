<?php

class protect {
    
    public static function login() {
        if (!user::logged_in()) {
            router::load(array('controller' => 'login'));
        }
    }
    
    public static function sandbox() {
        if (!code::sandbox()) {
            router::load(array('controller' => 'index'));
        }
    }
    
}


?>