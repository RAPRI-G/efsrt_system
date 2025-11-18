<?php
require_once 'models/PracticaModel.php';
require_once 'models/EstudianteModel.php';
require_once 'models/EmpresaModel.php';

class InicioController {
    private $practicaModel;
    private $estudianteModel;
    private $empresaModel;
    
    public function __construct() {
        $this->practicaModel = new PracticaModel();
        $this->estudianteModel = new EstudianteModel();
        $this->empresaModel = new EmpresaModel();
    }
    
    public function index() {
        // Obtener datos para el dashboard
        $practicas = $this->practicaModel->obtenerPracticas();
        $estudiantes = $this->estudianteModel->obtenerEstudiantes();
        $empresas = $this->empresaModel->obtenerEmpresas();
        
        // Estadísticas
        $estadisticas = [
            'total_practicas' => count($practicas),
            'total_estudiantes' => count($estudiantes),
            'total_empresas' => count($empresas),
            'practicas_modulo1' => count(array_filter($practicas, function($p) { return $p['tipo_efsrt'] == 'modulo1'; })),
            'practicas_modulo2' => count(array_filter($practicas, function($p) { return $p['tipo_efsrt'] == 'modulo2'; })),
            'practicas_modulo3' => count(array_filter($practicas, function($p) { return $p['tipo_efsrt'] == 'modulo3'; }))
        ];
        
        require_once 'views/inicio/dashboard.php';
    }
}
?>