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
    
    public function __construct() {
        $this->model = new PracticaModel();
        $this->estudianteModel = new EstudianteModel();
        $this->empresaModel = new EmpresaModel();
        $this->empleadoModel = new EmpleadoModel();
    }
    
    public function index() {
        $practicas = $this->model->obtenerPracticas();
        require_once __DIR__ . '/../views/practica/listar.php';
    }
    
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
?>