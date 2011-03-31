<?php


class arr {
    
    public static function get($arr, $key, $default = '') {
        if (!is_array($arr) || !array_key_exists($key, $arr)) {
            return $default;
        }
        return $arr[$key];
    }
    
    public static function has($arr, $b) {
        if (!is_array($arr)) return false;
        return in_array($b, $arr);
    }
    
    public static function has_key($arr, $k) {
        if (!is_array($arr)) return false;
        return array_key_exists($k, $arr);
    }
    
    public static function union() {
        $args = func_get_args();
        if (empty($args)) {
            return array();
        }
        else {
            $result = array_shift($args);
            if (!is_array($result)) $next = array($result);
        }
        foreach ($args as $next) {
            if (!is_array($next)) $next = array($next);
            $result = array_merge($result, $next);
        }
        return $result;
    }
    
    public static function same() {
        $args = func_get_args();
        if (empty($args)) {
            return true;
        }
        else {
            $reference = array_shift($args);
        }
        foreach ($args as $next) {
            if (count($reference) != count($next)) return false;
            foreach ($reference as $key => $val) {
                if ($next[$key] != $val) {
                    return false;
                }
            }
        }
        return true;
    }
    
}

?>