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
    require_once __DIR__ . '/../config/Database.php'; // Añadir esto
    $this->model = new PracticaModel();
    $this->estudianteModel = new EstudianteModel();
    $this->empresaModel = new EmpresaModel();
    $this->empleadoModel = new EmpleadoModel();
    
    // Obtener conexión a la base de datos
    $database = Database::getInstance();
    $this->db = $database->getConnection();
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
            $input = $_POST;
            
            // 🔥 Validar permisos para CREAR
            $id = $input['id'] ?? null;
            if (!$id) {
                // Creación de nueva práctica - permitir a administradores y docentes
                if (!SessionHelper::esAdministrador() && !SessionHelper::esDocente()) {
                    throw new Exception('No tiene permisos para crear nuevas prácticas. Solo administradores y docentes pueden realizar esta acción.');
                }
            } else {
                // 🔥 Validar permisos para EDITAR - solo administradores
                if (!SessionHelper::esAdministrador()) {
                    throw new Exception('No tiene permisos para editar prácticas. Solo administradores pueden realizar esta acción.');
                }
            } 
            
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
            $estudiante_id = $input['estudiante'] ?? '';
            $tipo_efsrt = $input['tipo_efsrt'] ?? '';
            
            // 🔥 VALIDACIÓN DE MÓDULO - SOLO PARA NUEVAS PRÁCTICAS
            if (!$id && $estudiante_id && $tipo_efsrt) {
                // Verificar si el estudiante ya tiene este módulo registrado
                if ($this->model->validarModuloEstudiante($estudiante_id, $tipo_efsrt)) {
                    // Obtener nombre del módulo
                    $nombreModulo = $this->getNombreModulo($tipo_efsrt);
                    
                    // Obtener información del estudiante para mensaje más descriptivo
                    $estudianteInfo = $this->obtenerInfoEstudiante($estudiante_id);
                    $nombreEstudiante = $estudianteInfo['nombre'] ?? 'el estudiante';
                    
                    throw new Exception("{$nombreEstudiante} ya tiene una práctica registrada para {$nombreModulo}. 
                    Solo se permite una práctica por módulo. Si necesita modificar, edite la práctica existente.");
                }
                
                // 🔥 VALIDACIÓN DE ORDEN DE MÓDULOS (OPCIONAL)
                // Si quieres forzar que se completen en orden: Módulo 1 -> Módulo 2 -> Módulo 3
                $modulosEstudiante = $this->model->obtenerModulosEstudiante($estudiante_id);
                
                // Validar si el estudiante ha completado módulos anteriores
                $this->validarOrdenModulos($tipo_efsrt, $modulosEstudiante);
            }
            
            // Obtener periodo académico
            $periodo_academico = '2024-1';
            if ($estudiante_id) {
                $periodo_academico = $this->obtenerPeriodoAcademicoEstudiante($estudiante_id);
            }
            
            $datos = [
                'estudiante' => $estudiante_id,
                'empresa' => $input['empresa'] ?? '',
                'empleado' => $input['empleado'] ?? '',
                'tipo_efsrt' => $tipo_efsrt,
                'periodo_academico' => $periodo_academico,
                'fecha_inicio' => $input['fecha_inicio'] ?? '',
                'total_horas' => 128,
                'horas_acumuladas' => 0,
                'area_ejecucion' => $input['area_ejecucion'] ?? '',
                'supervisor_empresa' => $input['supervisor_empresa'] ?? '',
                'cargo_supervisor' => $input['cargo_supervisor'] ?? '',
                'estado' => 'En curso',
                'modulo' => $this->getNombreModulo($tipo_efsrt)
            ];
            
            error_log("🔍 Datos para guardar: " . print_r($datos, true));
            
            if ($id) {
                // Actualizar práctica existente
                if (method_exists($this->model, 'actualizarPractica')) {
                    $result = $this->model->actualizarPractica($id, $datos);
                    $mensaje = 'Práctica actualizada correctamente';
                } else {
                    $datos['id'] = $id;
                    $result = $this->model->registrarPractica($datos);
                    $mensaje = 'Práctica actualizada correctamente';
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

private function validarOrdenModulos($moduloNuevo, $modulosExistentes) {
    $ordenModulos = ['modulo1', 'modulo2', 'modulo3'];
    $indiceNuevo = array_search($moduloNuevo, $ordenModulos);
    
    // Verificar módulos previos necesarios
    for ($i = 0; $i < $indiceNuevo; $i++) {
        $moduloRequerido = $ordenModulos[$i];
        $moduloEncontrado = false;
        
        foreach ($modulosExistentes as $modulo) {
            if ($modulo['tipo_efsrt'] == $moduloRequerido) {
                $moduloEncontrado = true;
                
                // Verificar si el módulo requerido está finalizado
                if ($modulo['estado'] != 'Finalizado') {
                    $nombreModuloRequerido = $this->getNombreModulo($moduloRequerido);
                    throw new Exception("Debe finalizar {$nombreModuloRequerido} antes de registrar " . 
                                       $this->getNombreModulo($moduloNuevo));
                }
                break;
            }
        }
        
        if (!$moduloEncontrado && $i > 0) {
            $nombreModuloRequerido = $this->getNombreModulo($moduloRequerido);
            throw new Exception("Debe completar {$nombreModuloRequerido} antes de registrar " . 
                               $this->getNombreModulo($moduloNuevo));
        }
    }
}

private function obtenerInfoEstudiante($estudiante_id) {
    try {
        $sql = "SELECT CONCAT(nom_est, ' ', ap_est) as nombre 
                FROM estudiante 
                WHERE id = :estudiante_id";
        
        if (!$this->db) {
            $database = Database::getInstance();
            $this->db = $database->getConnection();
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':estudiante_id' => $estudiante_id]);
        $result = $stmt->fetch();
        
        return $result ?: ['nombre' => 'El estudiante'];
        
    } catch (Exception $e) {
        error_log("Error en obtenerInfoEstudiante: " . $e->getMessage());
        return ['nombre' => 'El estudiante'];
    }
}

public function api_modulos_estudiante() {
    header('Content-Type: application/json');
    
    try {
        $estudiante_id = $_GET['estudiante_id'] ?? null;
        
        if (!$estudiante_id) {
            throw new Exception('ID de estudiante no proporcionado');
        }
        
        // Usar el nuevo método del modelo
        $modulos = method_exists($this->model, 'obtenerModulosEstudiante') 
            ? $this->model->obtenerModulosEstudiante($estudiante_id) 
            : [];
        
        echo json_encode([
            'success' => true,
            'modulos' => $modulos
        ]);
        
    } catch (Exception $e) {
        error_log("Error en api_modulos_estudiante: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'modulos' => []
        ]);
    }
}

private function obtenerPeriodoAcademicoEstudiante($estudiante_id) {
    try {
        // Verificar que la conexión existe
        if (!$this->db) {
            $database = Database::getInstance();
            $this->db = $database->getConnection();
        }
        
        $sql = "SELECT per_acad FROM matricula WHERE estudiante = :estudiante_id ORDER BY fec_matricula DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':estudiante_id' => $estudiante_id]);
        $result = $stmt->fetch();
        
        if ($result && !empty($result['per_acad'])) {
            return $result['per_acad'];
        }
        
        return '2024-1';
        
    } catch (Exception $e) {
        error_log("Error al obtener periodo académico: " . $e->getMessage());
        return '2024-1';
    }
}
    
    public function api_eliminar() {
    header('Content-Type: application/json');
    
    try {
        // 🔥 Validar que sea SOLO administrador
        if (!SessionHelper::esAdministrador()) {
            throw new Exception('No tiene permisos para eliminar prácticas. Solo administradores pueden realizar esta acción.');
        }
        
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