<?php
require_once 'models/EstudianteModel.php';
require_once 'models/PracticaModel.php';

class EstudianteController {
    private $estudianteModel;
    private $practicaModel;
    
    public function __construct() {
        $this->estudianteModel = new EstudianteModel();
        $this->practicaModel = new PracticaModel();
    }
    
    public function index() {
        $filtros = [
            'busqueda' => $_GET['busqueda'] ?? '',
            'programa' => $_GET['programa'] ?? 'all',
            'estado' => $_GET['estado'] ?? 'all',
            'genero' => $_GET['genero'] ?? 'all'
        ];
        
        $estudiantes = $this->estudianteModel->obtenerEstudiantes($filtros);
        $programas = $this->estudianteModel->obtenerProgramas();
        $estadisticas = $this->estudianteModel->obtenerEstadisticasEstudiantes();
        
        // Pasar datos a la vista
        $data = [
            'estudiantes' => $estudiantes,
            'programas' => $programas,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros
        ];
        
        require_once 'views/estudiante/estudiantes.php';
    }
    
    public function apiEstudiantes() {
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
    
    public function crear() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
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
                
                // Preparar datos
                $datos = [
                    'ubdistrito' => $_POST['ubdistrito'] ?? null,
                    'dni_est' => $_POST['dni_est'],
                    'ap_est' => $_POST['ap_est'],
                    'am_est' => $_POST['am_est'] ?? null,
                    'nom_est' => $_POST['nom_est'],
                    'sex_est' => $_POST['sex_est'],
                    'cel_est' => $_POST['cel_est'] ?? null,
                    'ubigeodir_est' => $_POST['ubigeodir_est'] ?? null,
                    'ubigeonac_est' => $_POST['ubigeonac_est'] ?? null,
                    'dir_est' => $_POST['dir_est'] ?? null,
                    'mailp_est' => $_POST['mailp_est'] ?? null,
                    'maili_est' => $_POST['maili_est'] ?? null,
                    'fecnac_est' => $_POST['fecnac_est'] ?? null,
                    'estado' => isset($_POST['estado']) ? 1 : 0
                ];
                
                // Validar email si se proporciona
                if (!empty($datos['mailp_est']) && !filter_var($datos['mailp_est'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('El email personal no tiene un formato válido');
                }
                
                if (!empty($datos['maili_est']) && !filter_var($datos['maili_est'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('El email institucional no tiene un formato válido');
                }
                
                $resultado = $this->estudianteModel->crearEstudiante($datos);
                
                if ($resultado) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Estudiante creado correctamente',
                        'id' => $resultado
                    ]);
                } else {
                    throw new Exception('Error al crear estudiante en la base de datos');
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        }
    }
    
    public function actualizar($id) {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
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
                
                // Preparar datos
                $datos = [
                    'ubdistrito' => $_POST['ubdistrito'] ?? null,
                    'dni_est' => $_POST['dni_est'],
                    'ap_est' => $_POST['ap_est'],
                    'am_est' => $_POST['am_est'] ?? null,
                    'nom_est' => $_POST['nom_est'],
                    'sex_est' => $_POST['sex_est'],
                    'cel_est' => $_POST['cel_est'] ?? null,
                    'ubigeodir_est' => $_POST['ubigeodir_est'] ?? null,
                    'ubigeonac_est' => $_POST['ubigeonac_est'] ?? null,
                    'dir_est' => $_POST['dir_est'] ?? null,
                    'mailp_est' => $_POST['mailp_est'] ?? null,
                    'maili_est' => $_POST['maili_est'] ?? null,
                    'fecnac_est' => $_POST['fecnac_est'] ?? null,
                    'estado' => isset($_POST['estado']) ? 1 : 0
                ];
                
                // Validar email si se proporciona
                if (!empty($datos['mailp_est']) && !filter_var($datos['mailp_est'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('El email personal no tiene un formato válido');
                }
                
                if (!empty($datos['maili_est']) && !filter_var($datos['maili_est'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('El email institucional no tiene un formato válido');
                }
                
                $resultado = $this->estudianteModel->actualizarEstudiante($id, $datos);
                
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
    
    public function eliminar($id) {
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
    
    public function detalle($id) {
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
?>