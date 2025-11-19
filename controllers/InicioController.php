<?php
require_once 'models/PracticaModel.php';
require_once 'models/EstudianteModel.php';
require_once 'models/EmpresaModel.php';
require_once 'models/EmpleadoModel.php';

class InicioController {
    private $practicaModel;
    private $estudianteModel;
    private $empresaModel;
    private $empleadoModel;
    
    public function __construct() {
        $this->practicaModel = new PracticaModel();
        $this->estudianteModel = new EstudianteModel();
        $this->empresaModel = new EmpresaModel();
        $this->empleadoModel = new EmpleadoModel();
    }
    
    public function index() {
        // Verificar si hay datos, si no, cargar datos de prueba
        if (!$this->practicaModel->hayDatosPrueba()) {
            $this->cargarDatosPrueba();
        }
        
        // Cargar la vista del dashboard
        require_once 'views/inicio/dashboard.php';
    }
    
    // API para obtener datos del dashboard
    public function getDashboardData() {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'estadisticas' => $this->getEstadisticas(),
                'practicas' => $this->getPracticas(),
                'graficos' => $this->getDatosGraficos(),
                'actividad_reciente' => $this->getActividadReciente()
            ];
            
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    private function getEstadisticas() {
        return [
            'total_estudiantes' => $this->estudianteModel->contarEstudiantesActivos(),
            'total_empresas' => $this->empresaModel->contarEmpresasActivas(),
            'total_docentes' => $this->empleadoModel->contarDocentesActivos(),
            'practicas_activas' => $this->practicaModel->contarPracticasPorEstado('En curso'),
            'practicas_finalizadas' => $this->practicaModel->contarPracticasPorEstado('Finalizado'),
            'practicas_pendientes' => $this->practicaModel->contarPracticasPorEstado('Pendiente'),
            'total_practicas' => $this->practicaModel->contarTotalPracticas()
        ];
    }
    
    private function getPracticas() {
        return $this->practicaModel->obtenerPracticasDashboard();
    }
    
    private function getDatosGraficos() {
        return [
            'estado_practicas' => $this->practicaModel->obtenerDistribucionEstadoPracticas(),
            'distribucion_modulos' => $this->practicaModel->obtenerDistribucionModulos(),
            'practicas_en_curso' => $this->practicaModel->obtenerPracticasEnCurso()
        ];
    }
    
    private function getActividadReciente() {
        return $this->practicaModel->obtenerActividadReciente();
    }
    
    // Método para cargar datos de prueba si no hay datos
    private function cargarDatosPrueba() {
        $this->practicaModel->insertarDatosPrueba();
    }
}
?>