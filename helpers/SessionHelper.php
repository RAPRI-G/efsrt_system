<?php
class SessionHelper {
    
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generar token CSRF si no existe
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
    
    // 🔐 MÉTODOS PARA TOKENS CSRF
    public static function getCSRFToken() {
        self::init();
        return $_SESSION['csrf_token'] ?? '';
    }
    
    public static function validateCSRF($token) {
        self::init();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function regenerateCSRF() {
        self::init();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
    
    // 🔐 MÉTODOS PARA TOKENS DE USUARIO (BD)
    public static function generateUserToken() {
        return bin2hex(random_bytes(32));
    }
    
    public static function validateUserToken($token) {
        return preg_match('/^[a-f0-9]{64}$/', $token);
    }
}
?>