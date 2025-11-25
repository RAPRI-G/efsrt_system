<?php
require_once 'models/EmpresaModel.php';
require_once 'models/PracticaModel.php';

class EmpresaController {
    private $empresaModel;
    private $practicaModel;
    
    public function __construct() {
        $this->empresaModel = new EmpresaModel();
        $this->practicaModel = new PracticaModel();
    }

    // En EmpresaController.php - agregar este método si no existe
public function index() {
    // Cargar la vista de empresas
    require_once 'views/empresa/empresa.php';
}
    
    // API para obtener empresas con filtros
    public function api_empresas() {
        header('Content-Type: application/json');
        
        try {
            $filtros = [
                'busqueda' => $_GET['busqueda'] ?? '',
                'sector' => $_GET['sector'] ?? 'all',
                'validado' => $_GET['validado'] ?? 'all',
                'estado' => $_GET['estado'] ?? 'all'
            ];
            
            $empresas = $this->empresaModel->obtenerTodasEmpresas($filtros);
            
            echo json_encode([
                'success' => true,
                'data' => $empresas,
                'total' => count($empresas)
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    // API para obtener una empresa específica
    public function api_empresa() {
        header('Content-Type: application/json');
        
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de empresa no proporcionado');
            }
            
            $empresa = $this->empresaModel->obtenerEmpresaPorId($id);
            
            if ($empresa) {
                echo json_encode([
                    'success' => true,
                    'data' => $empresa
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Empresa no encontrada'
                ]);
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    // API para crear/editar empresa
    public function api_guardar() {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? null;
            
            $datos = [
                ':ruc' => $this->sanitize($input['ruc']),
                ':razon_social' => $this->sanitize($input['razon_social']),
                ':nombre_comercial' => $this->sanitize($input['nombre_comercial']),
                ':direccion_fiscal' => $this->sanitize($input['direccion_fiscal']),
                ':telefono' => $this->sanitize($input['telefono']),
                ':email' => $this->sanitize($input['email']),
                ':sector' => $this->sanitize($input['sector']),
                ':validado' => isset($input['validado']) ? 1 : 0,
                ':registro_manual' => isset($input['registro_manual']) ? 1 : 0,
                ':estado' => $this->sanitize($input['estado']),
                ':condicion_sunat' => $this->sanitize($input['condicion_sunat']),
                ':ubigeo' => $this->sanitize($input['ubigeo']),
                ':departamento' => $this->sanitize($input['departamento']),
                ':provincia' => $this->sanitize($input['provincia']),
                ':distrito' => $this->sanitize($input['distrito'])
            ];
            
            if ($id) {
                // Actualizar empresa existente
                $result = $this->empresaModel->actualizarEmpresa($id, $datos);
                $mensaje = 'Empresa actualizada correctamente';
            } else {
                // Crear nueva empresa
                $result = $this->empresaModel->crearEmpresa($datos);
                $mensaje = 'Empresa creada correctamente';
            }
            
            echo json_encode([
                'success' => true,
                'message' => $mensaje
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    // API para eliminar empresa
    public function api_eliminar() {
        header('Content-Type: application/json');
        
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de empresa no proporcionado');
            }
            
            $result = $this->empresaModel->eliminarEmpresa($id);
            
            echo json_encode([
                'success' => true,
                'message' => 'Empresa eliminada correctamente'
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    // En EmpresaController.php - método api_estadisticas - AGREGAR DEBUG
public function api_estadisticas() {
    header('Content-Type: application/json');
    
    try {
        error_log("DEBUG: Entrando a api_estadisticas");
        
        $total_empresas = $this->empresaModel->contarEmpresasActivas();
        error_log("DEBUG: total_empresas = " . $total_empresas);
        
        $empresas_validadas = $this->empresaModel->contarEmpresasValidadas();
        error_log("DEBUG: empresas_validadas = " . $empresas_validadas);
        
        $distribucion_sectores = $this->empresaModel->contarEmpresasPorSector();
        error_log("DEBUG: distribucion_sectores = " . print_r($distribucion_sectores, true));
        
        $empresas_con_practicas = $this->empresaModel->contarEmpresasConPracticas();
        error_log("DEBUG: empresas_con_practicas = " . $empresas_con_practicas);
        
        $data = [
            'total_empresas' => $total_empresas,
            'empresas_validadas' => $empresas_validadas,
            'distribucion_sectores' => $distribucion_sectores,
            'empresas_con_practicas' => $empresas_con_practicas
        ];
        
        error_log("DEBUG: Enviando respuesta: " . json_encode($data));
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        
    } catch (Exception $e) {
        error_log("ERROR en api_estadisticas: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
}
?>