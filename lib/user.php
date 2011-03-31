<?php

class user {
    
    private static $id;
    private static $name;
    
    public static function auto() {
        if (client::post('do') == 'login') {
            $username = client::post('username');
            $password = client::post('password');
            $attempt = self::login($username, $password);
        }
        else if (self::logged_in()) {
            self::$id = (int)client::session('user');
        }
    }
    
    public static function id() {
        if (isset(self::$id)) return self::$id;
        self::$id = (int)client::session('user');
        return self::$id;
    }
    
    public static function name() {
        if (isset(self::$name)) return self::$name;
        $id = self::id();
        if ($id === 0) {
            return '';
        }
        self::$name = my::cell('SELECT Name FROM Users WHERE ID=? AND Status=1', array((int)$id));
        return self::$name;
    }
    
    public static function logged_in() {
        return (self::$id !== 0);
    }
    
    public static function verify($user, $pass) {
        
        $filter = string::username($user);
        if ($user != $filter) {
            return array('error' => 'Bad characters in username.');
        }
        
        $user_row = my::row('SELECT ID, Password FROM Users WHERE Name=?', array($user));
        if (empty($user_row)) {
            return array('error' => "User doesn't exist.");
        }
        
        $ret = array();
        if ($user_row['Password'] == '') {
            log::warning("$user needs to change their password.");
            $ret['warnings'][] = 'Please change the password.';
        }
        if ($pass == '') {
            log::warning("No password provided.");
            $ret['warnings'][] = 'No password provided.';
        }
        
        $password = crypt($pass, $user_row['Password']);
        
        if ($password == $user_row['Password']) {
            return array('success' => 'Successfully logged in.', 'login_id' => 'saucy', 'user' => $user_row['ID']);
        }
        else {
            return array('error' => 'Wrong password.');
        }
        
    }
    
    public static function login($user, $pass) {
        $attempt = self::verify($user, $pass);
        if (isset($attempt['success'])) {
            self::$id = (int)$attempt['user'];
            client::session('login_id', $attempt['login_id']);
            client::session('user', $attempt['user']);
        }
        return $attempt;
    }
    
    public static function logout() {
        client::destroy('login_id');
        client::destroy('user');
        return (client::session('user') == '');
    }
    
    public static function register($firstname, $lastname, $email, $password, $password_retyped) {
        
        $user_id = (int)my::cell("SELECT ID FROM Users WHERE Name=?", array($email));
        $email = string::t(string::lower($email));
        $filtered = string::e($email);

        if ($email == '' || strlen($email) < 3) {
            return array('error' => 'Email must be at least 3 characters.');
        }
        if ($filtered != $email) {
            return array('error' => 'Your username contains invalid characters.');
        }
        if ($password == '') {
            return array('error' => 'No password given.');
        }
        if ($id != 0) {
            return array('error' => 'User already exists.');
        }
        if ($password != $password_retyped) {
            return array('error' => 'Passwords do not match.');
        }
        
        $hashed_password = crypt($password);
        
        $result = my::run('INSERT INTO Users (Status, Name, Password, IP, LoginDate, CreateDate, FirstName, LastName) VALUE (?, ?, ?, ?, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), ?, ?)', array(1, $email, $hashed_password, '', $firstname, $lastname));
        if (isset($result['error'])) {
            return $result;
        } else {
            return array('success' => 'Successful registration');
        }
    }
    
}

user::auto();

?>