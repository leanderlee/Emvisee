<?php

class code {
    
    const storage_dir = "data/";
    const settings_file = '../../settings.conf';
    private static $conf = array();
    private static $conf_loaded = false;
    
    public static function sandbox() {
        if (self::load_config(code::settings_file)) {
            return !arr::get(self::$conf, 'production', false);
        }
        else {
            log::critical('Could not load settings file.');
        }
    }
    
    public static function load_config($filename) {
        if (!self::$conf_loaded) {
            self::$conf_loaded = true;
            $filename = dirname(__FILE__) . '/' . $filename;
            $fh = fopen($filename, "r");
            if ($fh) {
                $json_settings = fread($fh, filesize($filename));
                $json = json_decode($json_settings, true);
                if ($json !== null) {
                    self::$conf = $json;
                }
                else return false;
            }
            else return false;
        }
        return true;
    }
    
    public static function root_dir() {
        if (self::load_config(code::settings_file)) {
            return arr::get(self::$conf, 'root', 'http://localhost/');
        }
        else {
            log::critical('Could not load settings file.');
        }
    }
    
    public static function mysql() {
        if (self::load_config(code::settings_file)) {
            $mysql = array();
            $mysql['db'] = arr::get(self::$conf, 'db', '');
            $mysql['user'] = arr::get(self::$conf, 'user', 'root');
            $mysql['pass'] = arr::get(self::$conf, 'pass', '');
            $mysql['host'] = arr::get(self::$conf, 'host', 'localhost');
            return $mysql;
        }
        else {
            log::critical('Could not load settings file.');
        }
    }
    
    public static function storage_dir() {
        return code::storage_dir;
    }
    
    public static function halt($subject = 'Halt message', $stack = 0) {
        log::render($subject, $stack+1);
        exit;
    }
    
    public static function assert($expression, $die_on_fail = true) {
        $trace = self::trace();
        $line = $trace['line'];
        $file = $trace['file'];
        $info = " in $file on line $line";
        if ($expression) {
            log::ok('Passed assertion' . $info);
        }
        else if ($die_on_fail) {
            log::critical('Failed assertion' . $info, false);
            code::halt('Assertion failed');
        }
        else {
            log::error('Failed assertion' . $info);
        }
    }
    
    private static function raw_stack() {
        $stack = debug_backtrace();
        array_shift($stack);
        array_shift($stack);
        return $stack;
    }
    
    private static function analyse($raw_array) {
        $file = $raw_array['file'];
        $line = $raw_array['line'];
        $class = $raw_array['class'];
        $method = $raw_array['function'];
        $type = $raw_array['type'];
        $call = 'function';
        if ($type == '->') {
            $call = 'method';
        }
        else if ($type == '::') {
            $call = 'static';
        }
        $func = $class . $type . $method;
        return array(
            'file' => $file,
            'line' => $line,
            'class' => $class,
            'method' => $method,
            'type' => $call,
            'function' => $func
        );
    }
    
    public static function trace($line = 0) {
        $stack = code::raw_stack();
        if (isset($stack[$line])) {
            return self::analyse($stack[$line]);
        }
        else {
            return array();
        }
    }
    
    public static function stack() {
        $result = array();
        $stack = self::raw_stack();
        foreach ($stack as $line => $array) {
            $result[] = self::analyze($array[$line]);
        }
        return $result;
    }
    
    public static function func($function) {
        $func_refl = new ReflectionFunction($function);
        return array(
            'name' => $function,
            'file' => $func_refl->getFileName(),
            'line' => $func_refl->getStartLine(),
            'required' => $func_refl->getNumberOfRequiredParameters(),
            'params' => $func_refl->getNumberOfParameters(),
            'deprecated' => $func_refl->isDeprecated(),
            'internal' => $func_refl->isInternal(),
            'user' => $func_refl->isUserDefined(),
            'reference' => $func_refl->returnsReference(),
        );
    }
    
    public static function method($class, $method) {
        $func_refl = new ReflectionMethod($class, $method);
        return array(
            'name' => $class,
            'file' => $func_refl->getFileName(),
            'line' => $func_refl->getStartLine(),
            'required' => $func_refl->getNumberOfRequiredParameters(),
            'params' => $func_refl->getNumberOfParameters(),
            'deprecated' => $func_refl->isDeprecated(),
            'internal' => $func_refl->isInternal(),
            'user' => $func_refl->isUserDefined(),
            'reference' => $func_refl->returnsReference(),
            'private' => $func_refl->isPrivate(),
            'protected' => $func_refl->isProtected(),
            'public' => $func_refl->isPublic(),
            'static' => $func_refl->isStatic(),
            'abstract' => $func_refl->isAbstract(),
        );
    }
    
}


?>