<?php
require_once 'models/UsuarioModel.php';
require_once 'helpers/SessionHelper.php';

class LoginController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }
    
    public function index() {
        // Si ya está logueado, redirigir al INICIO
        if(SessionHelper::isLoggedIn()) {
            header("Location: index.php?c=Inicio&a=index");
            exit;
        }
        require_once 'views/login/login.php';
    }
    
    public function auth() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->usuarioModel->verificarLogin($usuario, $password);
            
            if($user) {
                // Guardar datos en sesión usando SessionHelper
                SessionHelper::set('usuario', [
                    'id' => $user['id'],
                    'usuario' => $user['usuario'],
                    'nombre_completo' => $user['nombre_completo'],
                    'rol' => $user['rol'],
                    'tipo' => $user['tipo']
                ]);
                
                // REDIRIGIR AL INICIO después del login
                header("Location: index.php?c=Inicio&a=index");
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos";
                require_once 'views/login/login.php';
            }
        } else {
            header("Location: index.php?c=Login&a=index");
        }
    }
    
    public function logout() {
        SessionHelper::destroy();
        header("Location: index.php?c=Login&a=index");
        exit;
    }
}
?>