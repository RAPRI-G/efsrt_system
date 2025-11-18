<?php
// index.php - Usar SessionHelper
require_once 'helpers/SessionHelper.php';
SessionHelper::init();

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