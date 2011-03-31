<?php
/* 
   
    Router Class
   
    Leander Lee
   
    Usage
        self::load(method, params)
        self::render(page, method, params)
     
     
    /                   => loads php/index.php  runs index          displays tml/index/index.tml
    info/               => loads php/info.php   runs index          displays tml/info/index.tml
    info/company        => loads php/info.php   runs company        displays tml/info/company.tml
    about/              => loads php/about.php  runs index          displays tml/about/index.tml
    about/cars          => loads php/about.php  runs cars           displays tml/about/cars.tml
    about/cars/red      => loads php/about.php  runs cars(red)      displays tml/about/cars.tml
    about/cars/red/1    => loads php/about.php  runs cars(red, 1)   displays tml/about/cars.tml
    
   
*/


class router {
    
    const pre_control = 'setup';
    const post_control = 'teardown';
    const controller_suffix = '_controller';
    const controller_dir = 'controllers';
    const controller_ext = '.php';
    const services_suffix = '_service';
    const services_dir = 'services';
    const services_ext = '.php';
    
    const template_dir = 'tml';
    const template_ext = '.tml';
    const cache_reload = true;
    const cache_dir = 'cache';
    
    public static $rendered = false;
    public static $template = '';
    public static $params = '';
    public static $method = '';
    public static $controller = '';
    public static $service = false;
    
    public static function template($tmpl) {
        router::$template = router::$controller . '/' . $tmpl;
    }
    
    public static function load($chosen) {
        if (!is_array($chosen)) {
            $chosen = array('controller' => self::$controller, 'method' => $chosen, 'params' => self::$params);
        }
        if (!self::$rendered) {
            self::$service = arr::get($chosen, 'service', false);
            self::$controller = arr::get($chosen, 'controller', 'index');
            self::$method = arr::get($chosen, 'method', 'index');
            self::$params = arr::get($chosen, 'params', array());
            self::$template = $chosen['controller'] . '/' . self::$method;
            if (self::$service) {
                if (method_exists(self::$controller . self::services_suffix, self::pre_control)) {
                    call_user_func_array(array(self::$controller . self::services_suffix, self::pre_control), array());
                }
                if (method_exists(self::$controller . self::services_suffix, self::$method)) {
                    $service_response = call_user_func_array(array(self::$controller . self::services_suffix, self::$method), self::$params);
                }
                if (method_exists(self::$controller . self::services_suffix, self::post_control)) {
                    call_user_func_array(array(self::$controller . self::services_suffix, self::post_control), array());
                }
                echo json_encode($service_response);
                self::$rendered = true;
            }
            else {
                $defaults = array('base' => code::root_dir(), 'sandbox' => code::sandbox());
                $to_template = array();
                if (method_exists(self::$controller . self::controller_suffix, self::pre_control)) {
                    call_user_func_array(array($chosen['controller'] . self::controller_suffix, self::pre_control), array());
                }
                if (method_exists(self::$controller . self::controller_suffix, self::$method)) {
                    $result = call_user_func_array(array(self::$controller . self::controller_suffix, self::$method), self::$params);
                    $to_template = arr::union($defaults, $result);
                }
                if (method_exists(self::$controller . self::controller_suffix, self::post_control)) {
                    call_user_func_array(array(self::$controller . self::controller_suffix, self::post_control), array());
                }
                self::draw($to_template);
            }
        }
    }
    
    private static function draw($data) {
        if (!self::$rendered) {
            require_once 'twig/lib/Twig/Autoloader.php';
            Twig_Autoloader::register();
            $twig_loader = new Twig_Loader_Filesystem(self::template_dir);
            $twig_settings = array('cache' => self::cache_dir, 'auto_reload' => self::cache_reload);
            $twig = new Twig_Environment($twig_loader, $twig_settings);
            $twig_template = self::template_dir . '/' . self::$template . self::template_ext;
            if (file_exists($twig_template)) {
                $twig_load = $twig->loadTemplate(self::$template . self::template_ext);
                $twig_load->display($data);
                self::$rendered = true;
            }
            else {
                log::critical("Template file not found at $twig_template");
            }
        }
    }
    
    public static function handle($request = '', $root_dir = '') {
        $interpretations = self::interpretations($request, $root_dir);
        $chosen = self::choose($interpretations);
        if ($chosen === false) {
            // 404
            echo '404!';
            exit;
        }
        else {
            self::load($chosen);
        }
    }
    
    public static function choose($interpretations) {
        while (!empty($interpretations)) {
            $interpretation = array_shift($interpretations);
            $controller = $interpretation['controller'] . self::controller_suffix;
            $method = $interpretation['method'];
            $params = $interpretation['params'];
            $controller_file = self::controller_dir . '/' . $interpretation['controller'] . self::controller_ext;
            if (file_exists($controller_file)) {
                require_once $controller_file;
                if (method_exists($controller, $method)) {
                    $func_info = code::method($controller, $method);
                    $func_req_args = $func_info['required'];
                    $func_args = count($params);
                    if ($func_req_args <= $func_args) {
                        $interpretation['service'] = false;
                        return $interpretation;
                    }
                }
            }
            $controller = $interpretation['controller'] . self::services_suffix;
            $services_file = self::services_dir . '/' . $interpretation['controller'] . self::services_ext;
            if (file_exists($services_file)) {
                require_once $services_file;
                if (method_exists($controller, $method)) {
                    $func_info = code::method($controller, $method);
                    $func_req_args = $func_info['required'];
                    $func_args = count($params);
                    if ($func_req_args <= $func_args) {
                        $interpretation['service'] = true;
                        return $interpretation;
                    }
                }
            }
        }
        return false;
    }
    
    public static function interpretations($request, $root_dir = '') {
        $request = string::after($root_dir, $request);
        $request = trim(trim($request), '/');
        $request = string::before('?', $request);
        $parts = explode('/', $request);
        foreach ($parts as $key => $part) {
            if (trim($part) == '' && count($parts) > 1) {
                unset($parts[$key]);
            }
        }
        $parts = array_values($parts);
        $num_parts = count($parts);
        $options = array();
        if ($num_parts == 1) {
            if ($parts[0] == '') {
                $options[] = array('controller' => 'index', 'method' => 'index', 'params' => array());
            }
            else {
                $options[] = array('controller' => $parts[0], 'method' => 'index', 'params' => array());
                $options[] = array('controller' => 'index', 'method' => $parts[0], 'params' => array());
                $options[] = array('controller' => 'index', 'method' => 'index', 'params' => array($parts[0]));
            }
        }
        else {
            $item1 = '';
            while (trim($item1) == '' && !empty($parts)) {
                $item1 = array_shift($parts);
            }
            $item2 = '';
            while (trim($item2) == '' && !empty($parts)) {
                $item2 = array_shift($parts);
            }
            $options[] = array('controller' => $item1, 'method' => $item2, 'params' => $parts);
            array_unshift($parts, $item2);
            $options[] = array('controller' => $item1, 'method' => 'index', 'params' => $parts);
            $options[] = array('controller' => 'index', 'method' => $item1, 'params' => $parts);
            array_unshift($parts, $item1);
            $options[] = array('controller' => 'index', 'method' => 'index', 'params' => $parts);
        }
        return $options;
    }
    
}

?>