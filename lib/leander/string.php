<?php
/******************************************************************
    
    String Class
    For Generic Use
    
    Written and Owned by Leander Lee
    
    Created:  October 03, 2010
    Modified: October 16, 2010 (Version 0.3)
  
 ******************************************************************/

// String Class:
//   starts(string, prefix)
//   ends(string, suffix)
//
//   my(string, max)    = MySQL Real Escape
//   h(string)    = HTML Entities
//   t(string)    = Trim
//
//   f(string, allowed)    = Filter
//   a(string)    = Alphanumeric
//   e(string)    = Email Safe
//   n(string)    = Numeric [Returns float]
//   i(string)    = Integer [Returns int]
//   c(string)    = Currency [Returns string]

class string {
    
    public static function make_slug($str) {
        $str = string::lower(string::a(string::t($str), '-_ '));
        $str = str_replace(' ', '-', $str);
        return $str;
    }
    
    public static function pluralify($str) {
        $use_ies = false;
        $ies_exceptions = array('ay', 'ey', 'iy', 'oy', 'uy');
        $ies_endings = array('y');
        foreach ($ies_endings as $ies) {
            if (string::ends($str, $ies)) {
                $use_ise = true;
                // Drop
                $str = substr($str, 0, -1);
                foreach ($ies_exceptions as $except) {
                    if (string::ends($str, $except)) {
                        $use_ies = false;
                        break;
                    }
                }
                break;
            }
        }
        if ($use_ies) {
            return $str . 'ies';
        }
        $use_es = false;
        $es_endings = array('ch', 's', 'sh', 'o', 'z', 'x');
        foreach ($es_endings as $es) {
            if (string::ends($str, $es)) {
                $use_es = true;
                break;
            }
        }
        if ($use_es) {
            return $str . 'es';
        }
        return $str . 's';
    }
    
    public static function multi_split($s, $delimiters = array(',', ';'), $limit = -1) {
        foreach ($delimiters as &$delimit) {
            $delimit = preg_quote($delimit);
        }
        $pattern = '/' . implode('|', $delimiters) . '/';
        $ret = preg_split($pattern, $s, $limit);
        return $ret;
    }
    
    static function starts($s, $pre = "") {
        if ($pre == '') return true;
        return (preg_match('/^' . preg_quote($pre) . '/', $s) > 0);
    }
    static function ends($s, $suf = "") {
        if ($suf == '') return true;
        return (preg_match('/' . preg_quote($suf) . '$/', $s) > 0);
    }
    static function before($c, $s, $first = true) {
        if ($c == '') return $s;
        $i = ($first) ? stripos($s, $c) : strripos($s, $c);
        if ($i !== false) {
            return substr($s, 0, $i);
        }
        return $s;
    }
    static function after($c, $s, $first = true) {
        if ($c == '') return $s;
        $i = ($first) ? stripos($s, $c) : strripos($s, $c);
        if ($i !== false) {
            return substr($s, $i+strlen($c));
        }
        return "";
    }
    static function my($s, $len = -1) {
        if ($len != -1) {
            $s = substr($s, 0, $len);
        }
        return mysql_real_escape_string($s);
    }
    static function html($s) {
        return htmlspecialchars($s);
    }
    static function t($s, $c = " \t\n\r\0\x0B") {
        return trim($s, $c);
    }
    public static function username($s) {
        $filtered = self::t($s);
        if (strlen($filtered) > 255) {
            $filtered = substr($filtered, 0, 255);
        }
        return $filtered;
    }
    public static function proper($s) {
        $pieces = explode(' ', $s);
        foreach ($pieces as $k => $v) {
            $pieces[$k] = strtoupper(substr($pieces[$k], 0, 1)) . substr($pieces[$k], 1);
        }
        return implode(' ', $pieces);
    }
    static function lower($s) {
        return strtolower($s);
    }
    static function upper($s) {
        return strtoupper($s);
    }
    static function f($s, $regex = "/[^-a-zA-Z0-9_.@]+/S") {
        return preg_replace($regex, "", $s);
    }
    static function e($s) {
        return self::f($s, "/[^-a-zA-Z0-9_.@]+/S");
    }
    static function a($s, $other_chars = "") {
        $other_chars = addslashes($other_chars);
        return self::f($s, "/[^".$other_chars."a-zA-Z0-9]+/S");
    }
    static function n($s) {
        return floatval($s);
    }
    static function i($s) {
        return intval($s);
    }
    static function c($f) {
        return sprintf("%01.2f", self::n(f));
    }
    static function url($s) {
        return urlencode($s);
    }
    static function deurl($s) {
        return urldecode($s);
    }
}

define ( "STRING_CLASS_DEFINED" , 1 );

?>