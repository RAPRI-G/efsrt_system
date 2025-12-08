<?php
require_once 'models/UsuarioModel.php';
require_once 'helpers/SessionHelper.php';
require_once 'helpers/RolesHelper.php'; // ← AGREGAR ESTA LÍNEA

class LoginController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        // Si ya está logueado, redirigir al INICIO
        if (SessionHelper::isLoggedIn()) {
            header("Location: index.php?c=Inicio&a=index");
            exit;
        }
        $this->setNoCacheHeaders();
        // 🔐 REGENERAR TOKEN CSRF PARA EL FORMULARIO
        SessionHelper::regenerateCSRF();

        require_once 'views/login/login.php';
    }

    public function auth()
    {
        // 🔐 HEADERS PARA NO CACHEAR
        $this->setNoCacheHeaders();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            // 🔐 VALIDAR TOKEN CSRF
            if (!SessionHelper::validateCSRF($csrf_token)) {
                $error = "Token de seguridad inválido. Por favor, recargue la página.";
                error_log("CSRF Token validation failed for user: $usuario");
                require_once 'views/login/login.php';
                return;
            }

            // Validaciones mejoradas
            if (empty($usuario) || empty($password)) {
                $error = "Por favor, complete todos los campos";
                require_once 'views/login/login.php';
                return;
            }

            if (empty($rol)) {
                $error = "Debe seleccionar un tipo de usuario";
                require_once 'views/login/login.php';
                return;
            }

            $user = $this->usuarioModel->verificarLogin($usuario, $password, $rol);

            if ($user) {
                // 🔐 VALIDAR TOKEN DEL USUARIO
                if (empty($user['token']) || !$this->usuarioModel->verificarToken($user['id'], $user['token'])) {
                    $error = "Error de seguridad. Por favor, intente nuevamente.";
                    error_log("Token validation failed for user: " . $user['usuario']);
                    require_once 'views/login/login.php';
                    return;
                }

                // ✅ VERIFICAR QUE EL NOMBRE COMPLETO EXISTA
                $nombreCompleto = $user['nombre_completo'] ?? $user['usuario'];
                if (empty($nombreCompleto)) {
                    $nombreCompleto = $user['usuario']; // Fallback al nombre de usuario
                }

                // ✅ ASEGURAR QUE EL ROL ESTÉ BIEN DEFINIDO
                if (!isset($user['rol']) || empty($user['rol'])) {
                    // Si no viene del modelo, determinarlo por tipo usando RolesHelper
                    $user['rol'] = RolesHelper::getRolFromTipo($user['tipo']);
                }

                // ✅ VALIDAR QUE EL ROL COINCIDA CON EL SELECCIONADO
                if ($user['rol'] !== $rol) {
                    $error = "El tipo de usuario no coincide con las credenciales";
                    error_log("Rol mismatch: DB={$user['rol']}, Selected={$rol} for user: {$user['usuario']}");
                    require_once 'views/login/login.php';
                    return;
                }

                // Guardar datos en sesión (INCLUYENDO EL TOKEN)
                SessionHelper::set('usuario', [
                    'id' => $user['id'],
                    'usuario' => $user['usuario'],
                    'nombre_completo' => $nombreCompleto, // ✅ NOMBRE COMPLETO ASEGURADO
                    'rol' => $user['rol'],
                    'tipo' => $user['tipo'],
                    'dni' => $user['dni'] ?? null,
                    'token' => $user['token'] // 🔐 GUARDAR TOKEN EN SESIÓN
                ]);

                // Registrar el login en logs con más detalles
                error_log("✅ Login exitoso: " . $user['usuario'] .
                    " - Nombre: " . $nombreCompleto .
                    " - Rol: " . $user['rol'] .
                    " - Tipo: " . $user['tipo']);

                // 🔐 REGENERAR CSRF PARA LA SESIÓN
                SessionHelper::regenerateCSRF();

                // 🔐 REDIRECCIÓN SEGÚN ROL (USANDO EL NUEVO MÉTODO)
                $this->redirectByRole($user['rol']);
                exit;
            } else {
                $error = "Credenciales incorrectas o tipo de usuario no coincide";
                error_log("Intento de login fallido - Usuario: $usuario, Rol solicitado: $rol");
                require_once 'views/login/login.php';
            }
        } else {
            header("Location: index.php?c=Login&a=index");
        }
    }

    public function logout()
    {
        // 🔐 HEADERS PARA NO CACHEAR
        $this->setNoCacheHeaders();

        // 🔐 ELIMINAR TOKEN DE LA BD ANTES DE CERRAR SESIÓN
        if (SessionHelper::isLoggedIn()) {
            $usuario = SessionHelper::get('usuario');
            $this->usuarioModel->eliminarToken($usuario['id']);
            error_log("Logout: " . $usuario['usuario'] . " - Token eliminado");
        }

        SessionHelper::destroy();

        // 🔐 REDIRECCIÓN QUE IMPIDE VOLVER ATRÁS AL DASHBOARD
        header("Location: index.php?c=Login&a=index");
        exit;
    }

    // 🔐 MÉTODO PARA HEADERS ANTI-CACHE
    private function setNoCacheHeaders()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Fecha pasada
    }

    // 🔐 MODIFICAR ESTE MÉTODO PARA REDIRECCIONES ESPECÍFICAS POR ROL
    private function redirectByRole($rol)
    {
        switch ($rol) {
            case 'administrador':
                // Administrador va al inicio (tiene acceso a todo)
                header("Location: index.php?c=Inicio&a=index");
                break;
            case 'docente':
                // Docente podría ir directamente a asistencias o reportes
                //header("Location: index.php?c=Asistencia&a=index");
                header("Location: index.php?c=Inicio&a=index");
                break;
            case 'estudiante':
                // Estudiante va directamente a prácticas (su módulo principal)
                //header("Location: index.php?c=Practica&a=index");
                header("Location: index.php?c=Inicio&a=index");
                break;
            default:
                header("Location: index.php?c=Inicio&a=index");
        }
        exit;
    }
}
?>