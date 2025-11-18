<?php
class SessionHelper {
    
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key) {
        self::init();
        return $_SESSION[$key] ?? null;
    }
    
    public static function destroy() {
        self::init();
        session_destroy();
    }
    
    public static function isLoggedIn() {
        self::init();
        return isset($_SESSION['usuario']);
    }
    
    public static function getUser() {
        self::init();
        return $_SESSION['usuario'] ?? null;
    }
}
?>