<?php

class utils {
    
    public static function interpret_file_size($value) {
        $value = string::lower($value);
        $number = string::f($value, '/[^0-9][1-9]*.?[0-9]*/S');
        $number = floatval($number);
        
        if (string::ends($value, 'pb') || string::ends($value, 'p')) {
            return $number * pow(2, 50);
        }
        else if (string::ends($value, 'tb') || string::ends($value, 't')) {
            return $number * pow(2, 40);
        }
        else if (string::ends($value, 'gb') || string::ends($value, 'g')) {
            return $number * pow(2, 30);
        }
        else if (string::ends($value, 'mb') || string::ends($value, 'm')) {
            return $number * pow(2, 20);
        }
        else if (string::ends($value, 'kb') || string::ends($value, 'k')) {
            return $number * pow(2, 10);
        }
        else if (string::ends($value, 'bytes') || string::ends($value, 'byte') || string::ends($value, 'b')) {
            return $number;
        }
        return -1;
    }
    
}


?>