<?php

require_once 'config/database.php';
require_once 'models/PracticaModel.php';
require_once 'models/EstudianteModel.php';
require_once 'models/EmpresaModel.php';
require_once 'models/EmpleadoModel.php';


class ModulosController {
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
        require_once 'views/modulos/modulos.php';
    }
    
    public function getModulosData() {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'estadisticas' => $this->getEstadisticasModulos(),
                'estudiantes' => $this->getEstudiantesConModulos(),
                'modulos' => $this->getTodosModulos(),
                'empresas' => $this->getEmpresas(),
                'graficos' => $this->getDatosGraficosModulos()
            ];
            
            echo json_encode(['success' => true, 'data' => $data]);
            
        } catch (Exception $e) {
            error_log("Error en getModulosData: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    private function getEstadisticasModulos() {
        return [
            'total_estudiantes' => $this->contarEstudiantesConModulos(),
            'modulos_activos' => $this->practicaModel->contarPracticasPorEstado('En curso'),
            'modulos_finalizados' => $this->practicaModel->contarPracticasPorEstado('Finalizado'),
            'progreso_promedio' => $this->calcularProgresoPromedio()
        ];
    }
    
    private function contarEstudiantesConModulos() {
        try {
            // Contar estudiantes que tienen al menos una práctica
            $sql = "SELECT COUNT(DISTINCT e.id) as total 
                    FROM estudiante e 
                    INNER JOIN practicas p ON e.id = p.estudiante 
                    WHERE e.estado = 1";
            
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            
            return $result['total'] ?? 0;
            
        } catch (Exception $e) {
            error_log("Error en contarEstudiantesConModulos: " . $e->getMessage());
            return 0;
        }
    }
    
    private function calcularProgresoPromedio() {
        try {
            $modulos = $this->practicaModel->obtenerPracticasDashboard();
            $totalProgreso = 0;
            $modulosConProgreso = 0;
            
            foreach ($modulos as $modulo) {
                if ($modulo['estado'] !== 'Pendiente' && $modulo['total_horas'] > 0) {
                    $horasAcumuladas = $modulo['horas_acumuladas'] ?? 0;
                    $progreso = ($horasAcumuladas / $modulo['total_horas']) * 100;
                    $totalProgreso += $progreso;
                    $modulosConProgreso++;
                }
            }
            
            return $modulosConProgreso > 0 ? round($totalProgreso / $modulosConProgreso, 0) : 0;
            
        } catch (Exception $e) {
            error_log("Error en calcularProgresoPromedio: " . $e->getMessage());
            return 0;
        }
    }
    
    private function getEstudiantesConModulos() {
        try {
            // Obtener estudiantes que tienen prácticas
            $sql = "SELECT DISTINCT 
                    e.id, 
                    e.dni_est, 
                    CONCAT(e.ap_est, ' ', e.am_est, ', ', e.nom_est) as nombre_completo,
                    e.ap_est, 
                    e.am_est, 
                    e.nom_est, 
                    p.nom_progest as programa,
                    UPPER(CONCAT(SUBSTRING(e.ap_est, 1, 1), SUBSTRING(e.am_est, 1, 1))) as iniciales
                FROM estudiante e
                INNER JOIN matricula m ON e.id = m.estudiante
                INNER JOIN prog_estudios p ON m.prog_estudios = p.id
                INNER JOIN practicas pr ON e.id = pr.estudiante
                WHERE e.estado = 1 
                ORDER BY e.ap_est, e.am_est, e.nom_est";
            
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $estudiantes = $stmt->fetchAll();
            
            return $estudiantes ?: [];
            
        } catch (Exception $e) {
            error_log("Error en getEstudiantesConModulos: " . $e->getMessage());
            return [];
        }
    }
    
    private function getTodosModulos() {
        try {
            return $this->practicaModel->obtenerPracticasDashboard();
        } catch (Exception $e) {
            error_log("Error en getTodosModulos: " . $e->getMessage());
            return [];
        }
    }
    
    private function getEmpresas() {
        try {
            return $this->empresaModel->obtenerEmpresas();
        } catch (Exception $e) {
            error_log("Error en getEmpresas: " . $e->getMessage());
            return [];
        }
    }
    
    private function getDatosGraficosModulos() {
        try {
            return [
                'tipo_modulos' => $this->getDistribucionTipoModulos(),
                'estado_modulos' => $this->practicaModel->obtenerDistribucionEstadoPracticas()
            ];
        } catch (Exception $e) {
            error_log("Error en getDatosGraficosModulos: " . $e->getMessage());
            return [
                'tipo_modulos' => ['Módulo 1' => 0, 'Módulo 2' => 0, 'Módulo 3' => 0],
                'estado_modulos' => ['En curso' => 0, 'Finalizado' => 0, 'Pendiente' => 0]
            ];
        }
    }
    
    private function getDistribucionTipoModulos() {
        try {
            $sql = "SELECT 
                    CASE 
                        WHEN tipo_efsrt = 'modulo1' THEN 'Módulo 1'
                        WHEN tipo_efsrt = 'modulo2' THEN 'Módulo 2' 
                        WHEN tipo_efsrt = 'modulo3' THEN 'Módulo 3'
                        ELSE 'Sin especificar'
                    END as modulo,
                    COUNT(*) as cantidad
                FROM practicas 
                WHERE tipo_efsrt IS NOT NULL
                GROUP BY tipo_efsrt";
            
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll();
            
            $distribucion = [
                'Módulo 1' => 0,
                'Módulo 2' => 0,
                'Módulo 3' => 0
            ];
            
            foreach ($resultados as $row) {
                $distribucion[$row['modulo']] = $row['cantidad'];
            }
            
            return $distribucion;
            
        } catch (Exception $e) {
            error_log("Error en getDistribucionTipoModulos: " . $e->getMessage());
            return ['Módulo 1' => 0, 'Módulo 2' => 0, 'Módulo 3' => 0];
        }
    }
}
?>