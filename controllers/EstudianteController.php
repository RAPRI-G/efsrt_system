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
    public function verificarDNI()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

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

        exit;
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
        ini_set('display_errors', 0);
        error_reporting(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                error_log("🎯 CREANDO NUEVO ESTUDIANTE - CON verificación de DNI duplicado");

                // 🔥 EN CREACIÓN SÍ verificamos DNI duplicado
                if (isset($_POST['dni_est'])) {
                    $dni = $_POST['dni_est'];

                    // Validar formato
                    if (!preg_match('/^\d{8}$/', $dni)) {
                        throw new Exception('El DNI debe tener 8 dígitos numéricos');
                    }

                    // 🔥 VERIFICAR SI EL DNI YA EXISTE (solo en creación)
                    $dniExistente = $this->estudianteModel->verificarDniExistente($dni);
                    if ($dniExistente) {
                        throw new Exception('El DNI ya está registrado en el sistema. No se puede crear el estudiante.');
                    }

                    error_log("✅ DNI disponible para nuevo estudiante: {$dni}");
                }

                // Resto de validaciones...
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

                // Preparar datos del estudiante
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
                    'ubigeodir_est' => $_POST['ubigeodir_est'] ?? null,
                    'ubigeonac_est' => $_POST['ubigeonac_est'] ?? null
                ];

                $datosMatricula = [
                    'prog_estudios' => $_POST['prog_estudios'] ?? null,
                    'id_matricula' => $_POST['id_matricula'] ?? null,
                    'per_acad' => $_POST['per_acad'] ?? null,
                    'turno' => $_POST['turno'] ?? null
                ];

                // Validar email
                if (!empty($datosEstudiante['mailp_est']) && !filter_var($datosEstudiante['mailp_est'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('El email personal no tiene un formato válido');
                }

                // Crear estudiante
                $estudianteId = $this->estudianteModel->crearEstudiante($datosEstudiante);

                // Crear matrícula si hay datos
                if (!empty($datosMatricula['prog_estudios']) || !empty($datosMatricula['id_matricula'])) {
                    $this->estudianteModel->crearMatricula($estudianteId, $datosMatricula);
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Estudiante creado correctamente',
                    'id' => $estudianteId
                ]);
            } catch (Exception $e) {
                error_log("💥 Error en creación: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        }
    }

    public function actualizar()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_GET['id'] ?? null;

                if (!$id) {
                    throw new Exception('ID de estudiante no proporcionado');
                }

                error_log("🎯 EDITANDO estudiante ID: {$id} - SIN VERIFICACIÓN DE DNI");

                // 🔥 SOLO validaciones básicas - SIN NINGUNA verificación de DNI duplicado
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

                // 🔥 SOLO validar FORMATO del DNI - NADA MÁS
                if (!preg_match('/^\d{8}$/', $_POST['dni_est'])) {
                    throw new Exception('El DNI debe tener 8 dígitos numéricos');
                }

                // Preparar datos del estudiante
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
                    'ubigeodir_est' => $_POST['ubigeodir_est'] ?? null,
                    'ubigeonac_est' => $_POST['ubigeonac_est'] ?? null
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

                // Actualizar estudiante
                $resultado = $this->estudianteModel->actualizarEstudiante($id, $datosEstudiante);

                // Actualizar/Crear matrícula
                $this->estudianteModel->actualizarMatricula($id, $datosMatricula);

                if ($resultado) {
                    error_log("✅ Estudiante ID {$id} editado CORRECTAMENTE");
                    echo json_encode([
                        'success' => true,
                        'message' => 'Estudiante actualizado correctamente'
                    ]);
                } else {
                    throw new Exception('Error al actualizar estudiante en la base de datos');
                }
            } catch (Exception $e) {
                error_log("💥 ERROR en edición: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
        }

        exit;
    }

    public function eliminar()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

        try {
            // Obtener el ID desde GET
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception('ID de estudiante no proporcionado');
            }

            // Validar token CSRF
            $csrf_token = $_POST['csrf_token'] ?? '';
            if (!SessionHelper::validateCSRF($csrf_token)) {
                throw new Exception('Token de seguridad inválido');
            }

            // 🔥 DEBUG: Log de inicio
            error_log("🚀 Iniciando ELIMINACIÓN FÍSICA del estudiante ID: {$id}");

            // Validar que el estudiante exista
            $estudianteExistente = $this->estudianteModel->obtenerEstudianteCompleto($id);
            if (!$estudianteExistente) {
                error_log("❌ Estudiante ID {$id} no encontrado");
                throw new Exception('Estudiante no encontrado');
            }

            error_log("✅ Estudiante encontrado: " . $estudianteExistente['nom_est']);

            // 🔥 ELIMINACIÓN FÍSICA
            $resultado = $this->estudianteModel->eliminarEstudiante($id);

            if ($resultado) {
                error_log("🎉 Estudiante ID {$id} ELIMINADO FÍSICAMENTE de la base de datos");
                echo json_encode([
                    'success' => true,
                    'message' => 'Estudiante eliminado permanentemente del sistema'
                ]);
            } else {
                error_log("❌ Falló la ELIMINACIÓN FÍSICA del estudiante ID {$id}");
                throw new Exception('No se pudo eliminar el estudiante. Puede que tenga registros relacionados que impidan la eliminación.');
            }
        } catch (Exception $e) {
            error_log("💥 Error en ELIMINACIÓN FÍSICA estudiante ID {$id}: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }

    public function detalle()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

        try {
            // 🔥 Obtener el ID desde GET
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception('ID de estudiante no proporcionado');
            }

            // Validar que el ID sea numérico
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception('ID de estudiante inválido');
            }

            $estudiante = $this->estudianteModel->obtenerEstudianteCompleto($id);

            if ($estudiante && !empty($estudiante['id'])) {
                echo json_encode([
                    'success' => true,
                    'data' => $estudiante
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Estudiante no encontrado'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en detalle estudiante ID {$id}: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }

    // 🔥 NUEVO MÉTODO: Exportar estudiantes a CSV
    public function exportarCSV()
    {
        try {
            // Obtener estudiantes con los mismos filtros
            $filtros = [
                'busqueda' => $_GET['busqueda'] ?? '',
                'programa' => $_GET['programa'] ?? 'all',
                'estado' => $_GET['estado'] ?? 'all',
                'genero' => $_GET['genero'] ?? 'all'
            ];

            $estudiantes = $this->estudianteModel->obtenerEstudiantes($filtros);

            if (empty($estudiantes)) {
                throw new Exception('No hay datos para exportar');
            }

            // Configurar headers para descarga CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="estudiantes_' . date('Y-m-d_H-i-s') . '.csv"');

            // Crear output stream
            $output = fopen('php://output', 'w');

            // 🔥 BOM para Excel (caracteres especiales)
            fwrite($output, "\xEF\xBB\xBF");

            // Encabezados CSV
            $encabezados = [
                'DNI',
                'Apellido Paterno',
                'Apellido Materno',
                'Nombres',
                'Género',
                'Celular',
                'Email Personal',
                'Fecha Nacimiento',
                'Dirección',
                'Lugar Nacimiento',
                'Lugar Actual',
                'Programa Estudios',
                'ID Matrícula',
                'Periodo Académico',
                'Turno',
                'Estado'
            ];

            fputcsv($output, $encabezados, ';');

            // Datos de estudiantes
            foreach ($estudiantes as $estudiante) {
                $fila = [
                    $estudiante['dni_est'] ?? '',
                    $estudiante['ap_est'] ?? '',
                    $estudiante['am_est'] ?? '',
                    $estudiante['nom_est'] ?? '',
                    $estudiante['sex_est'] == 'M' ? 'Masculino' : ($estudiante['sex_est'] == 'F' ? 'Femenino' : ''),
                    $estudiante['cel_est'] ?? '',
                    $estudiante['mailp_est'] ?? '',
                    $estudiante['fecnac_est'] ?? '',
                    $estudiante['dir_est'] ?? '',
                    $estudiante['ubigeonac_est'] ?? '',
                    $estudiante['ubigeodir_est'] ?? '',
                    $estudiante['nom_progest'] ?? 'No asignado',
                    $estudiante['id_matricula'] ?? '',
                    $estudiante['per_acad'] ?? '',
                    $estudiante['turno'] ?? '',
                    ($estudiante['estado'] === 1) ? 'ACTIVO' : 'INACTIVO'
                ];

                fputcsv($output, $fila, ';');
            }

            fclose($output);
            exit;
        } catch (Exception $e) {
            // Si hay error, redirigir con mensaje
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
