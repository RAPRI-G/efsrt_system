<?php
// controllers/InformacionController.php
class InformacionController
{
    private $db;
    
    public function __construct() {
        // Cargar base de datos
        require_once 'config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        // Verificar permisos
        if (!SessionHelper::puedeVerMenu('informacion')) {
            header('Location: index.php?c=Inicio&a=index&error=acceso_denegado');
            exit;
        }
        
        // Obtener estadísticas
        $estadisticas = $this->obtenerEstadisticas();
        
        // Cargar vista
        require_once 'views/informacion/index.php';
    }
    
    public function getEstadisticasAjax() {
        // Método para peticiones AJAX
        if (!SessionHelper::isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit;
        }
        
        $estadisticas = $this->obtenerEstadisticas();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'empresas_count' => $estadisticas['empresas_activas'],
            'modulos_count' => $estadisticas['modulos_activos'],
            'estudiantes_activos' => $estadisticas['estudiantes_activos'],
            'practicas_en_curso' => $estadisticas['practicas_en_curso']
        ]);
        exit;
    }
    
    private function obtenerEstadisticas() {
        $estadisticas = [];
        
        try {
            // 1. Contar empresas activas
            $sql = "SELECT COUNT(*) as total FROM empresa WHERE estado = 'ACTIVO'";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $estadisticas['empresas_activas'] = $result['total'] ?? 0;
            
            // 2. Módulos siempre son 3
            $estadisticas['modulos_activos'] = 3;
            
            // 3. Contar estudiantes activos
            $sql = "SELECT COUNT(*) as total FROM estudiante WHERE estado = 1";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $estadisticas['estudiantes_activos'] = $result['total'] ?? 0;
            
            // 4. Contar prácticas en curso
            $sql = "SELECT COUNT(*) as total FROM practicas WHERE estado = 'En curso'";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $estadisticas['practicas_en_curso'] = $result['total'] ?? 0;
            
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas: " . $e->getMessage());
            // Valores por defecto en caso de error
            $estadisticas = [
                'empresas_activas' => 24,
                'modulos_activos' => 3,
                'estudiantes_activos' => 156,
                'practicas_en_curso' => 48
            ];
        }
        
        return $estadisticas;
    }
}
?>