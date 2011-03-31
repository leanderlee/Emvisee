<?php

class tests_controller {
    
    public static function setup() {
        protect::sandbox();
    }
    
    public static function index($file = '') {
        if ($file == '') {
            router::load('all');
            return;
        }
        test::init();
        $results = test::run($file);
        $total_failed = 0;
        $total_passed = 0;
        $total_tests = 0;
        foreach ($results as $module_name => $module_tests) {
            foreach ($module_tests as $test) {
                $total_tests++;
                if ($test['passed']) {
                    $total_passed++;
                }
                else {
                    $total_failed++;
                }
            }
        }
        $ret['test_name'] = $file;
        $ret['total_passed'] = $total_passed;
        $ret['total_failed'] = $total_failed;
        $ret['total_tests'] = $total_tests;
        $ret['modules'] = $results;
        return $ret;
    }
    
    public static function all() {
        $ret['tests'] = test::all();
        $ret['total_tests'] = count($ret['tests']);
        return $ret;
    }
    
}

?>