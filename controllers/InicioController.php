<?php
require_once 'models/PracticaModel.php';
require_once 'models/EstudianteModel.php';
require_once 'models/EmpresaModel.php';

class InicioController {
    private $practicaModel;
    private $estudianteModel;
    private $empresaModel;
    
    public function __construct() {
        // 🔐 HEADERS PARA NO CACHEAR EN TODAS LAS VISTAS
        $this->setNoCacheHeaders();
        $this->practicaModel = new PracticaModel();
        $this->estudianteModel = new EstudianteModel();
        $this->empresaModel = new EmpresaModel();
    }
    
    public function index() {
        // 🔐 SI NO ESTÁ LOGUEADO, REDIRIGIR AL LOGIN
        if (!SessionHelper::isLoggedIn()) {
            header("Location: index.php?c=Login&a=index");
            exit;
        }
        // 🔐 HEADERS ADICIONALES POR SI ACASO
        $this->setNoCacheHeaders();
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

     // 🔐 MÉTODO PARA HEADERS ANTI-CACHE
    private function setNoCacheHeaders() {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }
}
?>