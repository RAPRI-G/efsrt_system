<?php
// controllers/PracticaController.php
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../models/EstudianteModel.php';
require_once __DIR__ . '/../models/EmpresaModel.php';
require_once __DIR__ . '/../models/EmpleadoModel.php';

class PracticaController {
    private $model;
    private $estudianteModel;
    private $empresaModel;
    private $empleadoModel;
    private $db;
    
    public function __construct() {
        $this->model = new PracticaModel();
        $this->estudianteModel = new EstudianteModel();
        $this->empresaModel = new EmpresaModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function index() {
        // Verificar si hay datos, si no, insertar datos de prueba
        if (method_exists($this->model, 'hayDatosPrueba') && !$this->model->hayDatosPrueba()) {
            $this->model->insertarDatosPrueba();
        }
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/practica/practica.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
    
    public function api_practicas() {
        header('Content-Type: application/json');
        
        try {
            // Usar método existente
            $practicas = method_exists($this->model, 'obtenerPracticasDashboard') 
                ? $this->model->obtenerPracticasDashboard() 
                : $this->model->obtenerPracticas();
            
            // Calcular estadísticas
            $total_practicas = count($practicas);
            $practicas_en_curso = 0;
            $practicas_finalizadas = 0;
            $practicas_pendientes = 0;
            $horas_acumuladas = 0;
            $distribucion_modulos = [
                'Módulo 1' => 0,
                'Módulo 2' => 0,
                'Módulo 3' => 0
            ];
            
            foreach ($practicas as $practica) {
                // Contar por estado
                switch ($practica['estado']) {
                    case 'En curso':
                        $practicas_en_curso++;
                        break;
                    case 'Finalizado':
                        $practicas_finalizadas++;
                        break;
                    case 'Pendiente':
                        $practicas_pendientes++;
                        break;
                }
                
                // Sumar horas (todos los módulos son 128 horas)
                $horas_acumuladas += $practica['horas_acumuladas'] ?? 0;
                
                // Contar por módulo
                if ($practica['tipo_efsrt'] == 'modulo1') {
                    $distribucion_modulos['Módulo 1']++;
                } elseif ($practica['tipo_efsrt'] == 'modulo2') {
                    $distribucion_modulos['Módulo 2']++;
                } elseif ($practica['tipo_efsrt'] == 'modulo3') {
                    $distribucion_modulos['Módulo 3']++;
                }
            }
            
            $estadisticas = [
                'total_practicas' => $total_practicas,
                'practicas_en_curso' => $practicas_en_curso,
                'practicas_finalizadas' => $practicas_finalizadas,
                'practicas_pendientes' => $practicas_pendientes,
                'horas_acumuladas' => $horas_acumuladas,
                'distribucion_estado' => [
                    'En curso' => $practicas_en_curso,
                    'Finalizado' => $practicas_finalizadas,
                    'Pendiente' => $practicas_pendientes
                ],
                'distribucion_modulos' => $distribucion_modulos
            ];
            
            echo json_encode([
                'success' => true,
                'data' => $practicas,
                'estadisticas' => $estadisticas
            ]);
        } catch (Exception $e) {
            error_log("Error en api_practicas: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function api_estudiantes() {
        header('Content-Type: application/json');
        
        try {
            // Usar método existente obtenerEstudiantes()
            $estudiantes = $this->estudianteModel->obtenerEstudiantes();
            
            // Formatear los datos
            $estudiantesFormateados = [];
            foreach ($estudiantes as $estudiante) {
                $estudiantesFormateados[] = [
                    'id' => $estudiante['id'],
                    'dni_est' => $estudiante['dni_est'],
                    'ap_est' => $estudiante['ap_est'],
                    'am_est' => $estudiante['am_est'] ?? '',
                    'nom_est' => $estudiante['nom_est'],
                    'nombre_completo' => trim($estudiante['ap_est'] . ' ' . ($estudiante['am_est'] ?? '') . ', ' . $estudiante['nom_est'], ', '),
                    'iniciales' => strtoupper(
                        substr($estudiante['ap_est'] ?? '', 0, 1) . 
                        substr($estudiante['am_est'] ?? '', 0, 1)
                    ),
                    'programa' => $estudiante['nom_progest'] ?? 'No asignado',
                    'cel_est' => $estudiante['cel_est'] ?? '',
                    'mailp_est' => $estudiante['mailp_est'] ?? ''
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $estudiantesFormateados
            ]);
        } catch (Exception $e) {
            error_log("Error en api_estudiantes: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function api_empresas() {
        header('Content-Type: application/json');
        
        try {
            // Usar método existente
            $empresas = $this->empresaModel->obtenerEmpresas();
            
            echo json_encode([
                'success' => true,
                'data' => $empresas
            ]);
        } catch (Exception $e) {
            error_log("Error en api_empresas: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function api_empleados() {
        header('Content-Type: application/json');
        
        try {
            // Usar método existente
            $empleados = $this->empleadoModel->obtenerDocentes();
            
            echo json_encode([
                'success' => true,
                'data' => $empleados
            ]);
        } catch (Exception $e) {
            error_log("Error en api_empleados: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function api_practica() {
        header('Content-Type: application/json');
        
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de práctica no proporcionado');
            }
            
            $practica = method_exists($this->model, 'obtenerPracticaPorId') 
                ? $this->model->obtenerPracticaPorId($id) 
                : null;
            
            if ($practica) {
                echo json_encode([
                    'success' => true,
                    'data' => $practica
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Práctica no encontrada'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en api_practica: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function api_guardar() {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Obtener datos directamente del POST o del JSON
            $input = $_POST;
            
            // Si no hay datos en POST, intentar leer del JSON
            if (empty($input)) {
                $jsonInput = file_get_contents('php://input');
                if (!empty($jsonInput)) {
                    $input = json_decode($jsonInput, true);
                }
            }
            
            if (!$input) {
                throw new Exception('Datos no válidos');
            }
            
            error_log("🔍 Datos recibidos en api_guardar: " . print_r($input, true));
            
            $id = $input['id'] ?? null;
            
            // 🔥 OBTENER EL PERIODO ACADÉMICO DEL ESTUDIANTE DESDE MATRÍCULA
            $periodo_academico = '2024-1'; // Valor por defecto
            if (!empty($input['estudiante'])) {
                $periodo_academico = $this->obtenerPeriodoAcademicoEstudiante($input['estudiante']);
            }
            
            $datos = [
                'estudiante' => $input['estudiante'] ?? '',
                'empresa' => $input['empresa'] ?? '',
                'empleado' => $input['empleado'] ?? '', // Docente supervisor
                'tipo_efsrt' => $input['tipo_efsrt'] ?? '',
                'periodo_academico' => $periodo_academico, // 🔥 Usar el periodo obtenido
                'fecha_inicio' => $input['fecha_inicio'] ?? '',
                'total_horas' => 128, // TODOS los módulos son 128 horas
                'horas_acumuladas' => 0, // Inicia en 0
                'area_ejecucion' => $input['area_ejecucion'] ?? '',
                'supervisor_empresa' => $input['supervisor_empresa'] ?? '',
                'cargo_supervisor' => $input['cargo_supervisor'] ?? '',
                'estado' => 'En curso',
                'modulo' => $this->getNombreModulo($input['tipo_efsrt'] ?? '')
            ];
            
            error_log("🔍 Datos para guardar: " . print_r($datos, true));
            
            if ($id) {
                // Verificar si existe método para actualizar
                if (method_exists($this->model, 'actualizarPractica')) {
                    $result = $this->model->actualizarPractica($id, $datos);
                    $mensaje = 'Práctica actualizada correctamente';
                } else {
                    // Si no existe, usar el método registrarPractica
                    $datos['id'] = $id;
                    $result = $this->model->registrarPractica($datos);
                    $mensaje = 'Práctica creada correctamente';
                }
            } else {
                // Crear nueva práctica
                if (method_exists($this->model, 'crearPractica')) {
                    $result = $this->model->crearPractica($datos);
                } elseif (method_exists($this->model, 'registrarPractica')) {
                    $result = $this->model->registrarPractica($datos);
                } else {
                    throw new Exception('No hay método para crear prácticas');
                }
                $mensaje = 'Práctica creada correctamente';
            }
            
            error_log("🔍 Resultado de guardar: " . ($result ? 'true' : 'false'));
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => $mensaje
                ]);
            } else {
                throw new Exception('No se pudo guardar la práctica. Verifique los datos.');
            }
        } catch (Exception $e) {
            error_log("❌ Error en api_guardar: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}

private function obtenerPeriodoAcademicoEstudiante($estudiante_id) {
    try {
        $sql = "SELECT per_acad FROM matricula WHERE estudiante = :estudiante_id ORDER BY fec_matricula DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':estudiante_id' => $estudiante_id]);
        $result = $stmt->fetch();
        
        if ($result && !empty($result['per_acad'])) {
            return $result['per_acad'];
        }
        
        // Si no encuentra, usar valor por defecto
        return '2024-1';
        
    } catch (Exception $e) {
        error_log("Error al obtener periodo académico: " . $e->getMessage());
        return '2024-1';
    }
}
    
    public function api_eliminar() {
        header('Content-Type: application/json');
        
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de práctica no proporcionado');
            }
            
            // Verificar si existe método para eliminar
            if (method_exists($this->model, 'eliminarPractica')) {
                $result = $this->model->eliminarPractica($id);
            } else {
                // Si no existe método específico, hacer una eliminación directa usando la conexión de base de datos
                $sql = "DELETE FROM practicas WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':id' => $id]);
                $result = $stmt->rowCount() > 0;
            }
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Práctica eliminada correctamente'
                ]);
            } else {
                throw new Exception('No se pudo eliminar la práctica');
            }
        } catch (Exception $e) {
            error_log("Error en api_eliminar: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function getNombreModulo($tipoModulo) {
        $modulos = [
            'modulo1' => 'Módulo 1',
            'modulo2' => 'Módulo 2',
            'modulo3' => 'Módulo 3'
        ];
        
        return $modulos[$tipoModulo] ?? 'Módulo 1';
    }
    
    // Métodos existentes (mantener)
    public function registrar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'estudiante' => $_POST['estudiante_id'],
                'empresa' => $_POST['empresa_id'],
                'tipo_efsrt' => $_POST['tipo_efsrt'],
                'docente_supervisor' => $_POST['docente_supervisor'],
                'periodo_academico' => $_POST['periodo_academico'],
                'turno' => $_POST['turno'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'area_ejecucion' => $_POST['area_ejecucion']
            ];
            
            if($this->model->registrarPractica($datos)) {
                header("Location: index.php?c=Practica&a=index&msg=success");
                exit;
            } else {
                $error = "Error al registrar la práctica";
            }
        }
        
        // Obtener datos para el formulario
        $estudiantes = $this->estudianteModel->obtenerEstudiantes();
        $empresas = $this->empresaModel->obtenerEmpresas();
        $docentes = $this->empleadoModel->obtenerDocentes();
        
        require_once __DIR__ . '/../views/practica/registrar.php';
    }
    
    public function generarDocumentos() {
        $practica_id = $_GET['id'] ?? null;
        if($practica_id) {
            $practica = $this->model->obtenerPracticaPorId($practica_id);
            if($practica) {
                require_once __DIR__ . '/../views/documento/generar.php';
            } else {
                header("Location: index.php?c=Practica&a=index&msg=not_found");
            }
        } else {
            header("Location: index.php?c=Practica&a=index&msg=error");
        }
    }
}