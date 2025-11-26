<?php
date_default_timezone_set('America/Lima');
// index.php - Validación de tokens en cada request
require_once 'helpers/SessionHelper.php';
SessionHelper::init();

// 🔐 HEADERS GLOBALES PARA NO CACHEAR - BLOQUEA BOTÓN ATRÁS
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

// Cargar configuración
require_once 'config/database.php';

// Función de autocarga mejorada
spl_autoload_register(function($class) {
    $paths = [
        'controllers/' . $class . '.php',
        'models/' . $class . '.php',
        'helpers/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Controladores que no requieren login
$publicControllers = ['Login'];

// Obtener controlador y acción
$controller = $_GET['c'] ?? 'Inicio';
$action = $_GET['a'] ?? 'index';

// 🔐 VALIDAR TOKEN DE USUARIO PARA RUTAS PROTEGIDAS
if (!in_array($controller, $publicControllers) && SessionHelper::isLoggedIn()) {
    $usuario = SessionHelper::getUser();
    $usuarioModel = new UsuarioModel();
    
    if (!$usuarioModel->verificarToken($usuario['id'], $usuario['token'])) {
        // Token inválido - forzar logout
        SessionHelper::destroy();
        header("Location: index.php?c=Login&a=index&error=token_invalido");
        exit;
    }
}

// Verificar autenticación (excepto para Login)
if (!in_array($controller, $publicControllers) && !SessionHelper::isLoggedIn()) {
    header("Location: index.php?c=Login&a=index");
    exit;
}

// Validar y ejecutar
$controller_class = $controller . 'Controller';

if (class_exists($controller_class)) {
    $controller_instance = new $controller_class();
    
    if (method_exists($controller_instance, $action)) {
        $controller_instance->$action();
    } else {
        http_response_code(404);
        echo "Acción no encontrada: $action";
    }
} else {
    http_response_code(404);
    echo "Controlador no encontrado: $controller_class";
}
?>