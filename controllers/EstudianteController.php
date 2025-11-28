<?php
require_once 'models/EstudianteModel.php';
require_once 'models/PracticaModel.php';
require_once 'models/UbigeoModel.php';

class EstudianteController
{
    private $estudianteModel;
    private $practicaModel;
    private $ubigeoModel;

    public function __construct()
    {
        $this->estudianteModel = new EstudianteModel();
        $this->practicaModel = new PracticaModel();
        $this->ubigeoModel = new UbigeoModel(); // 🔥 NUEVO
    }

    public function index()
    {
        $filtros = [
            'busqueda' => $_GET['busqueda'] ?? '',
            'programa' => $_GET['programa'] ?? 'all',
            'estado' => $_GET['estado'] ?? 'all',
            'genero' => $_GET['genero'] ?? 'all'
        ];

        $estudiantes = $this->estudianteModel->obtenerEstudiantes($filtros);
        $programas = $this->estudianteModel->obtenerProgramas();
        $estadisticas = $this->estudianteModel->obtenerEstadisticasEstudiantes();
        $departamentos = $this->ubigeoModel->obtenerDepartamentos(); // 🔥 NUEVO

        // Pasar datos a la vista
        $data = [
            'estudiantes' => $estudiantes,
            'programas' => $programas,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros,
            'departamentos' => $departamentos // 🔥 NUEVO
        ];

        require_once 'views/estudiante/estudiantes.php';
    }

    public function obtenerProvincias()
    {
        header('Content-Type: application/json');

        try {
            $departamentoId = $_GET['departamento_id'] ?? null;
            if (!$departamentoId) {
                throw new Exception('ID de departamento no proporcionado');
            }

            $provincias = $this->ubigeoModel->obtenerProvinciasPorDepartamento($departamentoId);
            echo json_encode(['success' => true, 'data' => $provincias]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function obtenerDistritos()
    {
        header('Content-Type: application/json');

        try {
            $provinciaId = $_GET['provincia_id'] ?? null;
            if (!$provinciaId) {
                throw new Exception('ID de provincia no proporcionado');
            }

            $distritos = $this->ubigeoModel->obtenerDistritosPorProvincia($provinciaId);
            echo json_encode(['success' => true, 'data' => $distritos]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function apiEstudiantes()
    {
        header('Content-Type: application/json');

        try {
            $filtros = [
                'busqueda' => $_GET['busqueda'] ?? '',
                'programa' => $_GET['programa'] ?? 'all',
                'estado' => $_GET['estado'] ?? 'all',
                'genero' => $_GET['genero'] ?? 'all'
            ];

            $estudiantes = $this->estudianteModel->obtenerEstudiantes($filtros);
            $estadisticas = $this->estudianteModel->obtenerEstadisticasEstudiantes();
            $programas = $this->estudianteModel->obtenerProgramas();

            echo json_encode([
                'success' => true,
                'data' => [
                    'estudiantes' => $estudiantes,
                    'estadisticas' => $estadisticas,
                    'programas' => $programas
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // 🔥 NUEVO MÉTODO: Verificar si DNI existe
    // 🔥 NUEVO MÉTODO: Verificar si DNI existe
    // 🔥 MÉTODO MEJORADO: Verificar si DNI existe
    public function verificarDNI()
    {
        header('Content-Type: application/json');

        try {
            $dni = $_GET['dni'] ?? '';
            $excluirId = $_GET['excluir_id'] ?? null;

            if (empty($dni)) {
                echo json_encode(['success' => false, 'existe' => false, 'error' => 'DNI no proporcionado']);
                return;
            }

            // Validar formato de DNI
            if (!preg_match('/^\d{8}$/', $dni)) {
                echo json_encode(['success' => false, 'existe' => false, 'error' => 'Formato de DNI inválido']);
                return;
            }

            // Verificar si el DNI existe usando el método del modelo
            $existe = $this->estudianteModel->verificarDniExistente($dni, $excluirId);

            echo json_encode([
                'success' => true,
                'existe' => $existe,
                'mensaje' => $existe ? 'DNI ya existe' : 'DNI disponible'
            ]);
        } catch (Exception $e) {
            error_log("Error en verificarDNI: " . $e->getMessage());
            echo json_encode(['success' => false, 'existe' => false, 'error' => $e->getMessage()]);
        }
    }

    // En tu EstudianteController.php - AGREGAR ESTE MÉTODO
    public function actualizarCSRF()
    {
        header('Content-Type: application/json');

        try {
            // Regenerar token CSRF
            $nuevoToken = SessionHelper::regenerateCSRF();

            echo json_encode([
                'success' => true,
                'token' => $nuevoToken
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function crear()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                // 🔥 VALIDACIÓN EXTRA: Verificar DNI nuevamente por seguridad
                if (isset($_POST['dni_est'])) {
                    $dniExistente = $this->estudianteModel->verificarDniExistente($_POST['dni_est']);
                    if ($dniExistente) {
                        throw new Exception('El DNI ya está registrado en el sistema. No se puede crear el estudiante.');
                    }
                }

                // 🔥 DEBUG TEMPORAL: Ver qué datos llegan al servidor
                error_log("Datos recibidos en crear estudiante:");
                foreach ($_POST as $key => $value) {
                    error_log(" - $key: $value");
                }

                // Validar token CSRF
                if (!SessionHelper::validateCSRF($_POST['csrf_token'] ?? '')) {
                    throw new Exception('Token de seguridad inválido');
                }

                // Validar campos requeridos
                $camposRequeridos = ['dni_est', 'ap_est', 'nom_est', 'sex_est'];
                foreach ($camposRequeridos as $campo) {
                    if (empty($_POST[$campo])) {
                        throw new Exception("El campo " . str_replace('_', ' ', $campo) . " es requerido");
                    }
                }

                // Validar formato DNI
                if (!preg_match('/^\d{8}$/', $_POST['dni_est'])) {
                    throw new Exception('El DNI debe tener 8 dígitos numéricos');
                }

                // 🔥 SEPARAR DATOS: estudiante vs matrícula
                $datosEstudiante = [
                    'dni_est' => $_POST['dni_est'],
                    'ap_est' => $_POST['ap_est'],
                    'am_est' => $_POST['am_est'] ?? null,
                    'nom_est' => $_POST['nom_est'],
                    'sex_est' => $_POST['sex_est'],
                    'cel_est' => $_POST['cel_est'] ?? null,
                    'dir_est' => $_POST['dir_est'] ?? null,
                    'mailp_est' => $_POST['mailp_est'] ?? null,
                    'fecnac_est' => $_POST['fecnac_est'] ?? null,
                    'estado' => isset($_POST['estado']) ? 1 : 0,
                    'ubigeodir_est' => $_POST['ubigeodir_est'] ?? null, // 🔥 NUEVO
                    'ubigeonac_est' => $_POST['ubigeonac_est'] ?? null  // 🔥 NUEVO
                ];

                $datosMatricula = [
                    'prog_estudios' => $_POST['prog_estudios'] ?? null,
                    'id_matricula' => $_POST['id_matricula'] ?? null,
                    'per_acad' => $_POST['per_acad'] ?? null,
                    'turno' => $_POST['turno'] ?? null
                ];

                // Validar email si se proporciona
                if (!empty($datosEstudiante['mailp_est']) && !filter_var($datosEstudiante['mailp_est'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('El email personal no tiene un formato válido');
                }

                // 🔥 PRIMERO: Crear estudiante
                $estudianteId = $this->estudianteModel->crearEstudiante($datosEstudiante);

                // 🔥 SEGUNDO: Crear matrícula si hay datos
                if (!empty($datosMatricula['prog_estudios']) || !empty($datosMatricula['id_matricula'])) {
                    $this->estudianteModel->crearMatricula($estudianteId, $datosMatricula);
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Estudiante creado correctamente',
                    'id' => $estudianteId
                ]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        }
    }

    public function actualizar($id)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                // 🔥 VALIDACIÓN EXTRA: Verificar DNI excluyendo el actual
                if (isset($_POST['dni_est'])) {
                    $dniExistente = $this->estudianteModel->verificarDniExistente($_POST['dni_est'], $id);
                    if ($dniExistente) {
                        throw new Exception('El DNI ya está registrado en otro estudiante. No se puede actualizar.');
                    }
                }
                // Validar token CSRF
                if (!SessionHelper::validateCSRF($_POST['csrf_token'] ?? '')) {
                    throw new Exception('Token de seguridad inválido');
                }

                // Validar que el estudiante exista
                $estudianteExistente = $this->estudianteModel->obtenerEstudianteCompleto($id);
                if (!$estudianteExistente) {
                    throw new Exception('Estudiante no encontrado');
                }

                // Validar campos requeridos
                $camposRequeridos = ['dni_est', 'ap_est', 'nom_est', 'sex_est'];
                foreach ($camposRequeridos as $campo) {
                    if (empty($_POST[$campo])) {
                        throw new Exception("El campo " . str_replace('_', ' ', $campo) . " es requerido");
                    }
                }

                // Validar formato DNI
                if (!preg_match('/^\d{8}$/', $_POST['dni_est'])) {
                    throw new Exception('El DNI debe tener 8 dígitos numéricos');
                }

                // 🔥 SEPARAR DATOS: estudiante vs matrícula
                $datosEstudiante = [
                    'dni_est' => $_POST['dni_est'],
                    'ap_est' => $_POST['ap_est'],
                    'am_est' => $_POST['am_est'] ?? null,
                    'nom_est' => $_POST['nom_est'],
                    'sex_est' => $_POST['sex_est'],
                    'cel_est' => $_POST['cel_est'] ?? null,
                    'dir_est' => $_POST['dir_est'] ?? null,
                    'mailp_est' => $_POST['mailp_est'] ?? null,
                    'fecnac_est' => $_POST['fecnac_est'] ?? null,
                    'estado' => isset($_POST['estado']) ? 1 : 0
                ];

                $datosMatricula = [
                    'prog_estudios' => $_POST['prog_estudios'] ?? null,
                    'id_matricula' => $_POST['id_matricula'] ?? null,
                    'per_acad' => $_POST['per_acad'] ?? null,
                    'turno' => $_POST['turno'] ?? null
                ];

                // Validar email si se proporciona
                if (!empty($datosEstudiante['mailp_est']) && !filter_var($datosEstudiante['mailp_est'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('El email personal no tiene un formato válido');
                }

                // 🔥 PRIMERO: Actualizar estudiante
                $resultado = $this->estudianteModel->actualizarEstudiante($id, $datosEstudiante);

                // 🔥 SEGUNDO: Actualizar/Crear matrícula
                $this->estudianteModel->actualizarMatricula($id, $datosMatricula);

                if ($resultado) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Estudiante actualizado correctamente'
                    ]);
                } else {
                    throw new Exception('Error al actualizar estudiante en la base de datos');
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        }
    }

    public function eliminar($id)
    {
        header('Content-Type: application/json');

        try {
            // Validar que el estudiante exista
            $estudianteExistente = $this->estudianteModel->obtenerEstudianteCompleto($id);
            if (!$estudianteExistente) {
                throw new Exception('Estudiante no encontrado');
            }

            $resultado = $this->estudianteModel->eliminarEstudiante($id);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Estudiante eliminado correctamente']);
            } else {
                throw new Exception('Error al eliminar estudiante');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function detalle($id)
    {
        header('Content-Type: application/json');

        try {
            $estudiante = $this->estudianteModel->obtenerEstudianteCompleto($id);

            if ($estudiante) {
                echo json_encode(['success' => true, 'data' => $estudiante]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
