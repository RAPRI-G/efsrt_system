<?php
// controllers/UsuarioController.php

require_once 'models/UsuarioModel.php';
require_once 'models/EstudianteModel.php';
require_once 'models/EmpleadoModel.php';
require_once 'helpers/SessionHelper.php';

class UsuarioController
{
    private $usuarioModel;
    private $estudianteModel;
    private $empleadoModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->estudianteModel = new EstudianteModel();
        $this->empleadoModel = new EmpleadoModel();
    }

    public function index()
    {
        // Verificar autenticación
        if (!SessionHelper::isLoggedIn()) {
            header("Location: index.php?c=Login&a=index");
            exit;
        }

        // Solo administradores pueden ver esta página
        $usuario = SessionHelper::get('usuario');
        if ($usuario['rol'] !== 'administrador') {
            header("Location: index.php?c=Inicio&a=index&error=permisos");
            exit;
        }

        // Obtener solo estadísticas iniciales
        $data = [
            'estadisticas' => $this->usuarioModel->obtenerEstadisticas(),
            'csrf_token' => SessionHelper::getCSRFToken()
        ];

        // Cargar vista (los datos se cargarán vía AJAX)
        require_once 'views/layouts/header.php';
        require_once 'views/usuario/usuarios.php';
        require_once 'views/layouts/footer.php';
    }

    public function crear()
    {
        $this->validarPermisos();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Método no permitido');
        }

        // Validar CSRF
        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!SessionHelper::validateCSRF($csrf_token)) {
            $this->jsonResponse(false, 'Token de seguridad inválido');
        }

        // Obtener y validar datos
        $data = [
            'usuario' => trim($_POST['usuario'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'tipo' => $_POST['tipo'] ?? '',
            'estuempleado' => !empty($_POST['estuempleado']) ? $_POST['estuempleado'] : null,
            'estado' => $_POST['estado'] ?? 1
        ];

        // Validaciones
        if (empty($data['usuario']) || empty($data['password']) || empty($data['tipo'])) {
            $this->jsonResponse(false, 'Todos los campos son requeridos');
        }

        if (strlen($data['password']) < 8) {
            $this->jsonResponse(false, 'La contraseña debe tener al menos 8 caracteres');
        }

        // Crear usuario
        $result = $this->usuarioModel->crear($data);
        echo json_encode($result);
    }

    public function editar()
    {
        $this->validarPermisos();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Método no permitido');
        }

        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!SessionHelper::validateCSRF($csrf_token)) {
            $this->jsonResponse(false, 'Token de seguridad inválido');
        }

        $id = $_POST['id'] ?? 0;
        $data = [
            'usuario' => trim($_POST['usuario'] ?? ''),
            'tipo' => $_POST['tipo'] ?? '',
            'estuempleado' => !empty($_POST['estuempleado']) ? $_POST['estuempleado'] : null,
            'estado' => $_POST['estado'] ?? 1,
            'password' => !empty($_POST['password']) ? $_POST['password'] : null
        ];

        $result = $this->usuarioModel->actualizar($id, $data);
        echo json_encode($result);
    }

    public function eliminar()
    {
        $this->validarPermisos();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Método no permitido');
        }

        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!SessionHelper::validateCSRF($csrf_token)) {
            $this->jsonResponse(false, 'Token de seguridad inválido');
        }

        $id = $_POST['id'] ?? 0;
        $result = $this->usuarioModel->eliminar($id);
        echo json_encode($result);
    }

    public function resetPassword()
    {
        $this->validarPermisos();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Método no permitido');
        }

        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!SessionHelper::validateCSRF($csrf_token)) {
            $this->jsonResponse(false, 'Token de seguridad inválido');
        }

        $id = $_POST['id'] ?? 0;
        $result = $this->usuarioModel->resetPassword($id);
        echo json_encode($result);
    }

    // API endpoints para AJAX
    // En controllers/UsuarioController.php - Método apiEstadisticas

    public function apiEstadisticas()
    {
        if (!SessionHelper::isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit;
        }

        $estadisticas = $this->usuarioModel->obtenerEstadisticas();
        echo json_encode([
            'success' => true,
            'data' => $estadisticas,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }

    public function apiUsuarios()
    {
        if (!SessionHelper::isLoggedIn()) {
            $this->jsonResponse(false, 'No autenticado');
        }

        $usuarios = $this->usuarioModel->obtenerTodos();
        $this->jsonResponse(true, '', $usuarios);
    }

    public function apiEstudiantes()
    {
        if (!SessionHelper::isLoggedIn()) {
            $this->jsonResponse(false, 'No autenticado');
        }

        $estudiantes = $this->estudianteModel->obtenerParaUsuarios();
        $this->jsonResponse(true, '', $estudiantes);
    }

    public function apiEmpleados()
    {
        if (!SessionHelper::isLoggedIn()) {
            $this->jsonResponse(false, 'No autenticado');
        }

        $empleados = $this->empleadoModel->obtenerParaUsuarios();
        $this->jsonResponse(true, '', $empleados);
    }

    // Métodos privados auxiliares
    private function validarPermisos()
    {
        if (!SessionHelper::isLoggedIn()) {
            $this->jsonResponse(false, 'No autenticado');
        }

        $usuario = SessionHelper::get('usuario');
        if ($usuario['rol'] !== 'administrador') {
            $this->jsonResponse(false, 'Sin permisos');
        }
    }

    private function jsonResponse($success, $message = '', $data = [])
    {
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}
