<?php


class log {
    
    public static $MAX_DEPTH = 10;
    public static $logs;
    
    public static function get($subject = 'Fetch message', $stack = 0) {
        return self::debug(self::$logs, $subject, $stack+1);
    }
    
    public static function render($subject = 'Render message', $stack = 0) {
        echo self::debug(self::$logs, $subject, $stack+1);
    }
    
    public function critical($message, $stop = true) {
        self::$logs['critical'][] = $message;
        if ($stop) code::halt('Critical message', 1);
    }
    
    public function error($message, $stop = false) {
        self::$logs['error'][] = $message;
        if ($stop) code::halt('Error message', 1);
    }
    
    public function warning($message, $stop = false) {
        self::$logs['warning'][] = $message;
        if ($stop) code::halt('Warning message', 1);
    }
    
    public function found($message, $stop = false) {
        self::$logs['found'][] = $message;
        if ($stop) code::halt('Found message', 1);
    }
    
    public function ok($message, $stop = false) {
        self::$logs['ok'][] = $message;
        if ($stop) code::halt('OK received', 1);
    }
    
    private static function format_array($array, $depth = 0) {
        $spacing = str_repeat(' ', $depth*4);
        $format = $spacing;
        $nl = "\n" . $spacing;
        foreach ($array as $key => $val) {
            if (is_array($val) && count($val) >= 2 && $depth < self::$MAX_DEPTH) {
                $format .= self::format_variable($key);
                $format .= ": [\n";
                $format .= rtrim(self::format_array($val, $depth+1), ',');
                $format .= $nl . "]," . $nl;
            }
            else if (is_array($val)) {
                $format .= self::format_variable($key);
                $format .= ": [";
                $format .= rtrim(ltrim(self::format_array($val, $depth)), ',');
                $format .= "]," . $nl;
            }
            else {
                $format .= self::format_variable($key);
                $format .= ": ";
                $format .= self::format_variable($val);
                $format .= "," . $nl;
            }
        }
        $format = substr(rtrim($format), 0, strlen($format)-2);
        return $format;
    }
    
    private static function format_variable($variable = null) {
        if (is_array($variable)) {
            return  "[\n" . self::format_array($variable, 1) . "\n]";
        }
        else if (is_string($variable)) {
            return '&quot;' . $variable . '&quot;';
        }
        else if (is_numeric($variable)) {
            return '' . $variable . '';
        }
        else if (is_callable($variable)) {
            return 'func ' . $variable;
        }
        else {
            return var_export($variable, true);
        }
    }
    
    public static function debug($variable = null, $subject = 'Trace message', $line = 0) {
        $stack = code::trace($line);
        $variable = self::format_variable($variable);
        $html = "<pre style=\"padding: 5px; font: 11px/12px Courier New, mono; color: black; background: white;\">\n";
        $html .= "<strong>$subject from {$stack['file']}\t\t\tline {$stack['line']}</strong>\n";
        $html .= $variable;
        $html .= "\n</pre>";
        error_log(
            "$subject from {$stack['file']}        line {$stack['line']}" . "\n" .
            html_entity_decode($variable) . "\n"
        );
        return $html;
    }
    
    // Special alias for debug
    public static function w($variable = null, $subject = 'Trace message', $line = 0) {
        $line += 1;
        echo log::debug($variable, $subject, $line);
    }
    
}


?>