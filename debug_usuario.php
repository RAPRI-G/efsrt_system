<?php
require_once 'helpers/SessionHelper.php';
SessionHelper::init();

if (SessionHelper::isLoggedIn()) {
    $usuario = SessionHelper::get('usuario');
    echo "<pre>";
    echo "DATOS DEL USUARIO EN SESIÓN:\n";
    print_r($usuario);
    echo "</pre>";
    
    // Verificar en la base de datos
    require_once 'models/UsuarioModel.php';
    $model = new UsuarioModel();
    $userDB = $model->obtenerUsuarioPorId($usuario['id']);
    
    echo "<pre>";
    echo "DATOS DEL USUARIO EN BD:\n";
    print_r($userDB);
    echo "</pre>";
} else {
    echo "No hay usuario logueado";
}
?>