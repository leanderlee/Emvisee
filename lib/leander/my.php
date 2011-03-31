<?php
/******************************************************************
    
    Database Class
    
    Written and Owned by Leander Lee
    December 14, 2010
    
    Modified February 1, 2010 (Version 0.5)
  
 ******************************************************************/

// Database Class:
//   my::connect([usr, [pwd, [svr, [dbn]]]])
//   my::ok()
//   my::cell(sql, params)   => smart result (useful for single value)
//   my::row(sql, params)    => smart result (useful for one row)
//   my::col(sql, params)    => smart result (useful for one col)
//   my::grid(sql, params)   => array result
//   my::run(sql, params)    => raw result
//   n()      => num rows
//   t()      => time taken
//   i()      => info
//   e()      => error

class my {
    
    const cache_compress = MEMCACHE_COMPRESSED;
    private static $cache;
    private static $conn;
    private static $rows = 0;
    
    public static function load() {}
    
    public static function auto() {
        $mysql = code::mysql();
        self::connect($mysql['user'], $mysql['pass'], $mysql['host'], $mysql['db']);
    }
    
    public static function connect($usr = "root", $pwd = "", $svr = "localhost", $dbn = "") {
        if (my::$conn) {
            mysqli_close(my::$conn);
        }
        if (class_exists('Memcache')) {
            my::$cache = new Memcache;
            my::$cache->connect($svr, 11211);
        }
        my::$conn = new mysqli($svr, $usr, $pwd, $dbn);
    }
    
    public static function set($key, $value, $expiry = 0) {
        // NB: 0 means never expires
        if (my::$cache) {
            my::$cache->set($key, $value, my::cache_compress, $expiry);
        }
    }
    
    public static function get($key) {
        if (my::$cache) {
            my::$cache->get($key);
        }
    }
    
    public static function begin() {
        mysqli_autocommit(my::$conn, false);
    }
    
    public static function rollback() {
        mysqli_rollback(my::$conn);
        mysqli_autocommit(my::$conn, true);
    }
    
    public static function commit() {
        mysqli_autocommit(my::$conn, true);
    }
    
    public static function ok() {
        if (!my::$conn) {
            // Attempt to revive the connection if it died
            self::auto();
        }
        return (!!my::$conn && my::$conn->connect_error == NULL);
    }
    
    private static function fetch_value($value) {
        // We can do true escaping here.
        return $value;
    }
    
    private static function fetch_assoc(&$statement, &$out) {
        $metadata = $statement->result_metadata();
        if (empty($metadata)) return;
        
        $fields = array();
        $out = array();
        while($field = mysqli_fetch_field($metadata)) {
            $fields[] = &$out[$field->name];
        }
        call_user_func_array(array($statement, 'bind_result'), $fields);
    }
    
    private static function prepare($sql, $args = array()) {
        my::$rows = 0;
        $statement = my::$conn->prepare($sql);
        if (!$statement || my::$conn->error != "") {
            return array('error' => my::$conn->error);
        }
        $types = '';
        $params = array();
        foreach ($args as $arg) {
            if (is_int($arg)) {
                $types .= 'i';
                $params[] = $arg;
            }
            else if (is_float($arg)) {
                $types .= 'd';
                $params[] = $arg;
                $statement->bind_param('d', $arg);
            }
            else if (is_array($arg)) {
                $types .= 's';
                $json = json_encode($arg);
                $params[] = $json;
            }
            else if (is_string($arg)) {
                $types .= 's';
                $params[] = $arg;
            }
            else {
                $types .= 's';
                $cast = (string)$arg;
                $params[] = $cast;
            }
        }
        array_unshift($params, $types);
        if ($types != '') {
            call_user_func_array(array($statement, 'bind_param'), self::refValues($params));
        }
        return $statement;
    }
     
    private function refValues($arr){
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }
    
    public static function num() {
        return (my::$rows == 0) ? mysqli_affected_rows(my::$conn) : my::$rows;
    }
    
    public static function id() {
        return mysqli_insert_id(my::$conn);
    }
    
    public static function run($sql, $args = array()) {
        $statement = self::prepare($sql, $args);
        if (is_array($statement)) return $statement;
        $statement->execute();
        $statement->free_result();
        $statement->close();
        if ($statement && my::$conn->error != "") {
            return array('error' => my::$conn->error);
        }
        return array('success' => true);
    }
    
    public static function cell($sql, $args = array(), $cache = -1) {
        if (!self::ok()) {
            return "";
        }
        if ($cache != -1 && empty(my::$cache)) {
            $cache_key = serialize(array($sql, $args));
            $cached = my::$cache->get($cache_key);
            if ($cached) {
                my::$rows = 1;
                return $cached;
            }
        }
        
        $statement = self::prepare($sql, $args);
        if (is_array($statement)) return $statement;
        $statement->execute();
        
        $binded = array();
        $result = "";
        self::fetch_assoc($statement, $binded);
        if ($statement->fetch()) {
            if (!empty($binded)) {
                $column = array_shift(array_keys($binded));
                $val = $binded[$column];
                $result = self::fetch_value($val);
            }
        }
        
        $statement->free_result();
        $statement->close();
        
        if ($cache != -1 && empty(my::$cache)) {
            my::$cache->set($cache_key, $result, my::cache_compress, $cache);
        }
        my::$rows = 1;
        return $result;
    }
    
    public static function col($sql, $args = array(), $cache = -1) {
        if (!self::ok()) {
            return array();
        }
        if ($cache != -1 && empty(my::$cache)) {
            $cache_key = serialize(array($sql, $args));
            $cached = my::$cache->get($cache_key);
            if ($cached) {
                my::$rows = count($cached);
                return $cached;
            }
        }
        
        $statement = self::prepare($sql, $args);
        if (is_array($statement)) return $statement;
        $statement->execute();
        
        $binded = array();
        $result = array();
        $count = 0;
        self::fetch_assoc($statement, $binded);
        while ($statement->fetch()) {
            if (!empty($binded)) {
                $column = array_shift(array_keys($binded));
                $val = $binded[$column];
                $result[] = self::fetch_value($val);
            }
            $count ++;
        }
        
        $statement->free_result();
        $statement->close();
        
        if ($cache != -1 && empty(my::$cache)) {
            my::$cache->set($cache_key, $result, MEMCACHE_COMPRESSED, $cache);
        }
        my::$rows = $count;
        return $result;
    }
    
    public static function row($sql, $args = array(), $cache = -1) {
        if (!self::ok()) {
            return array();
        }
        if ($cache != -1 && empty(my::$cache)) {
            $cache_key = serialize(array($sql, $args));
            $cached = my::$cache->get($cache_key);
            if ($cached) {
                my::$rows = 1;
                return $cached;
            }
        }
        
        $statement = self::prepare($sql, $args);
        if (is_array($statement)) return $statement;
        $statement->execute();
        
        $binded = array();
        $result = array();
        self::fetch_assoc($statement, $binded);
        if ($statement->fetch()) {
            foreach ($binded as $key => $val) {
                $result[$key] = self::fetch_value($val);
            }
        }
        
        $statement->free_result();
        $statement->close();
        
        if ($cache != -1 && empty(my::$cache)) {
            my::$cache->set($cache_key, $result, MEMCACHE_COMPRESSED, $cache);
        }
        my::$rows = 1;
        return $result;
    }
    
    public static function grid($sql, $args = array(), $cache = -1) {
        if (!self::ok()) {
            return array();
        }
        if ($cache != -1 && empty(my::$cache)) {
            $cache_key = serialize(array($sql, $args));
            $cached = my::$cache->get($cache_key);
            if ($cached) {
                my::$rows = count($cached);
                return $cached;
            }
        }
        
        $statement = self::prepare($sql, $args);
        if (is_array($statement)) return $statement;
        $statement->execute();
        
        $binded = array();
        $result = array();
        $count = 0;
        self::fetch_assoc($statement, $binded);
        while ($statement->fetch()) {
            $row = array();
            foreach ($binded as $key => $val) {
                $row[$key] = self::fetch_value($val);
            }
            $result[] = $row;
            $count ++;
        }
        
        $statement->free_result();
        $statement->close();
        
        if ($cache != -1 && empty(my::$cache)) {
            my::$cache->set($cache_key, $result, MEMCACHE_COMPRESSED, $cache);
        }
        my::$rows = $count;
        return $result;
    }
    
}

my::auto();

define ( "DATABASE_CLASS_DEFINED" , 1 );
 
?>
