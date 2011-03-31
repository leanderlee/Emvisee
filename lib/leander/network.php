<?php

class network {
    
    public static $http = array(
        'continue' => 100,
        'switch' => 101,
        'success' => 200,
        'created' => 201,
        'accepted' => 202,
        'nothing' => 204,
        'reset' => 205,
        'partial' => 206,
        'permanent' => 301,
        'proxy' => 305,
        'temporary' => 307,
        'fail' => 400,
        'unauthorized' => 401,
        'payment' => 402,
        'forbidden' => 403,
        'notfound' => 404,
        'proxyfail' => 407,
        'timeout' => 408,
        'gone' => 410,
        'longuri' => 414,
        'unsupported' => 415,
        'unexpected' => 417,
        'internalfail' => 500,
        'unimplemented' => 501,
        'gatewayfail' => 502,
        'servicefail' => 503,
        'httpfail' => 505,
    );
    
    public static function sent() {
        return headers_sent();
    }
    
    public static function headers() {
        return headers_list();
    }
    
    public static function uri() {
        return arr::get($_SERVER, 'REQUEST_URI');
    }
    
    public static function referrer() {
        return arr::get($_SERVER, 'HTTP_REFERRER');
    }
    
    public static function host() {
        return arr::get($_SERVER, 'SERVER_NAME');
    }
    
    public static function redirect($location, $params = array()) {
        $query = arr::get($params, 'params', array());
        $permanent = arr::get($params, 'permanent', false);
        
        if ($permanent) {
            $http = self::$http['permanent'];
        }
        else {
            $http = self::$http['temporary'];
        }
        
        if (!empty($query)) {
            $first = true;
            foreach ($query as $key => $val) {
                if ($first) {
                    $location .= '?';
                }
                else {
                    $location .= '&';
                }
                $location .= string::url($key) . '=' . string::url($val);
                $first = false;
            }
        }
        
        if (!network::sent()) {
            header('Location: ' . $location, true, $http);
        }
        exit;
    }
    
}


?>