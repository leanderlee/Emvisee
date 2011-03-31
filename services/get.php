<?php

class get_service extends service {
    
    public static function index($slug = "") {
        return array('hello' => 'This is an example web service!');
    }
    
}

?>