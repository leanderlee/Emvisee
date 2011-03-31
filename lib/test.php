<?php

function ok($condition, $test = '') {
    if ($condition) {
        return array('passed' => true, 'name' => $test);
    }
    return array('passed' => false, 'name' => $test, 'condition' => $condition);
}

function test($var1, $var2, $test = '') {
    if ($var1 === $var2) {
        return array('passed' => true, 'name' => $test);
    }
    return array('passed' => false, 'name' => $test, 'var1' => $var1, 'var2' => $var2);
}

class test {
    
    const tests_dir = 'tests/';
    const test_file_ext = '.test';
    
    const test_stage = '[stage]';
    const test_module = '[module]';
    const default_module_name = 'Test Module ';
    const default_test_name = 'Assertion ';
    
    public static $unnamed_modules = 0;
    public static $unnamed_tests = 0;
    public static $shared_vars = array('results', 'module', 'module_name');
    public static $saved_state;
    
    
    public static function init() {
        self::save_state();
    }
    
    public static function all() {
        $handle = opendir(test::tests_dir);
        $result = array();
        while (false !== ($file = readdir($handle))) {
            if (is_file(test::tests_dir . $file) &&
                string::ends($file, test::test_file_ext)) {
                $result[] = string::before(test::test_file_ext, $file);
            }
        }
        return $result;
    }
    
    public static function run($file) {
        if ($handle = fopen(test::tests_dir . $file . test::test_file_ext, 'r')) {
            $result = self::run_file($handle);
            return $result;
        }
        else {
            log::critical('Could not open file for testing.');
        }
    }
    
    public static function run_file($handle) {
        $results = array();
        $staging = false;
        $section_code = '';
        $module = array();
        while ($line = fgets($handle)) {
            $line = string::t($line);
            if ($line == '') {}
            else if (string::starts($line, test::test_stage)) {
                eval($section_code);
                $section_code = '';
                // Reset Variables
                foreach (self::$saved_state as $var_name => $value) {
                    $$var_name = $value;
                }
                $staging = true;
            }
            else if (string::starts($line, test::test_module)) {
                eval($section_code);
                $section_code = '';
                if ($staging) {
                    $staging = false;
                    self::save_state(get_defined_vars());
                }
                // New module
                if (!empty($module)) {
                    $results[$module_name] = $module;
                }
                $module = array();
                $module_name = string::after(test::test_module, $line);
                $module_name = string::t($module_name);
                if ($module_name == '') {
                    self::$unnamed_modules++;
                    $module_name = test::default_module_name . self::$unnamed_modules;
                }
                $fails = 0;
                $passes = 0;
                // Reset Variables
                foreach (self::$saved_state as $var_name => $value) {
                    $$var_name = $value;
                }
            }
            else if (string::starts($line, 'ok(') ||
                     string::starts($line, 'test(')) {
                eval($section_code);
                $section_code = '';
                eval('$result = '.$line);
                if ($result['passed'] === true) {
                    $passes++;
                }
                else {
                    $fails++;
                }
                $test_name = $result['name'];
                if ($test_name == '') {
                    self::$unnamed_tests++;
                    $test_name = test::default_test_name . self::$unnamed_tests;
                }
                unset($result['name']);
                $module[$test_name] = $result;
            }
            else {
                $section_code .= $line."\n";
            }
        }
        if (!empty($module)) {
            $results[$module_name] = $module;
        }
        return $results;
    }
    
    public static function save_state($vars = array()) {
        $result = array();
        foreach ($vars as $var_name => $value) {
            if (!arr::has(self::$shared_vars, $var_name)) {
                $result[$var_name] = $value;
            }
        }
        self::$saved_state = $result;
    }
    
}

?>