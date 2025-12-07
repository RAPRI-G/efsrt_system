<?php
require_once 'BaseModel.php';

class ReportesModel extends BaseModel
{
    public function obtenerEstadisticasDashboard()
    {
        try {
            $estadisticas = [];
            
            // 1. Total Estudiantes
            $sql = "SELECT COUNT(*) as total FROM estudiante WHERE estado = 1";
            $stmt = $this->executeQuery($sql);
            $estadisticas['total_estudiantes'] = $stmt->fetchColumn() ?? 0;
            
            // 2. Total Prácticas
            $sql = "SELECT COUNT(*) as total FROM practicas";
            $stmt = $this->executeQuery($sql);
            $estadisticas['total_practicas'] = $stmt->fetchColumn() ?? 0;
            
            // 3. Prácticas por estado
            $sql = "SELECT estado, COUNT(*) as cantidad FROM practicas GROUP BY estado";
            $stmt = $this->executeQuery($sql);
            $practicasPorEstado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $estadisticas['practicas_en_curso'] = 0;
            $estadisticas['practicas_finalizadas'] = 0;
            $estadisticas['practicas_pendientes'] = 0;
            
            foreach ($practicasPorEstado as $row) {
                if ($row['estado'] == 'En curso') {
                    $estadisticas['practicas_en_curso'] = $row['cantidad'];
                } elseif ($row['estado'] == 'Finalizado') {
                    $estadisticas['practicas_finalizadas'] = $row['cantidad'];
                } elseif ($row['estado'] == 'Pendiente') {
                    $estadisticas['practicas_pendientes'] = $row['cantidad'];
                }
            }
            
            // 4. Total horas acumuladas
            $sql = "SELECT SUM(COALESCE(horas_acumuladas, 0)) as total FROM practicas";
            $stmt = $this->executeQuery($sql);
            $estadisticas['horas_cumplidas'] = $stmt->fetchColumn() ?? 0;
            
            // 5. Total Empresas activas
            $sql = "SELECT COUNT(*) as total FROM empresa WHERE estado = 'ACTIVO'";
            $stmt = $this->executeQuery($sql);
            $estadisticas['total_empresas'] = $stmt->fetchColumn() ?? 0;
            
            // 6. Tasa de finalización
            if ($estadisticas['total_practicas'] > 0) {
                $estadisticas['tasa_finalizacion'] = round(
                    ($estadisticas['practicas_finalizadas'] / $estadisticas['total_practicas']) * 100, 
                    1
                );
            } else {
                $estadisticas['tasa_finalizacion'] = 0;
            }
            
            return $estadisticas;
            
        } catch (Exception $e) {
            error_log("Error en obtenerEstadisticasDashboard: " . $e->getMessage());
            return [
                'total_estudiantes' => 0,
                'total_practicas' => 0,
                'practicas_en_curso' => 0,
                'practicas_finalizadas' => 0,
                'practicas_pendientes' => 0,
                'horas_cumplidas' => 0,
                'total_empresas' => 0,
                'tasa_finalizacion' => 0
            ];
        }
    }
    
    public function obtenerDatosGraficoEstadoPracticas()
    {
        try {
            $sql = "SELECT estado, COUNT(*) as cantidad FROM practicas GROUP BY estado";
            $stmt = $this->executeQuery($sql);
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Formatear datos para Chart.js
            $resultado = [
                'labels' => [],
                'data' => [],
                'colors' => []
            ];
            
            $colores = [
                'En curso' => '#0ea5e9',    // Azul
                'Finalizado' => '#10b981',  // Verde
                'Pendiente' => '#f59e0b'    // Amarillo
            ];
            
            foreach ($datos as $row) {
                $resultado['labels'][] = $row['estado'];
                $resultado['data'][] = $row['cantidad'];
                $resultado['colors'][] = $colores[$row['estado']] ?? '#6b7280';
            }
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en obtenerDatosGraficoEstadoPracticas: " . $e->getMessage());
            return ['labels' => [], 'data' => [], 'colors' => []];
        }
    }
    
    public function obtenerDatosGraficoModulos()
    {
        try {
            $sql = "SELECT tipo_efsrt, COUNT(*) as cantidad FROM practicas GROUP BY tipo_efsrt";
            $stmt = $this->executeQuery($sql);
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $resultado = [
                'labels' => [],
                'data' => [],
                'colors' => []
            ];
            
            $nombresModulos = [
                'modulo1' => 'Módulo 1',
                'modulo2' => 'Módulo 2',
                'modulo3' => 'Módulo 3'
            ];
            
            $colores = ['#3b82f6', '#10b981', '#8b5cf6']; // Azul, Verde, Púrpura
            
            foreach ($datos as $index => $row) {
                $modulo = $nombresModulos[$row['tipo_efsrt']] ?? $row['tipo_efsrt'];
                $resultado['labels'][] = $modulo;
                $resultado['data'][] = $row['cantidad'];
                $resultado['colors'][] = $colores[$index] ?? '#6b7280';
            }
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en obtenerDatosGraficoModulos: " . $e->getMessage());
            return ['labels' => [], 'data' => [], 'colors' => []];
        }
    }
    
    public function obtenerTopEmpresas($limite = 5)
    {
        try {
            $sql = "SELECT 
                        e.razon_social,
                        COUNT(p.id) as cantidad_practicas,
                        SUM(COALESCE(p.horas_acumuladas, 0)) as total_horas
                    FROM empresa e
                    LEFT JOIN practicas p ON e.id = p.empresa
                    WHERE e.estado = 'ACTIVO'
                    GROUP BY e.id
                    ORDER BY cantidad_practicas DESC, total_horas DESC
                    LIMIT :limite";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en obtenerTopEmpresas: " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerEvolucionMensual()
    {
        try {
            $sql = "SELECT 
                        DATE_FORMAT(fecha_registro, '%Y-%m') as mes,
                        DATE_FORMAT(fecha_registro, '%b %Y') as mes_texto,
                        COUNT(*) as cantidad
                    FROM practicas
                    WHERE fecha_registro >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                    GROUP BY DATE_FORMAT(fecha_registro, '%Y-%m')
                    ORDER BY mes";
            
            $stmt = $this->executeQuery($sql);
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $resultado = [
                'labels' => [],
                'data' => []
            ];
            
            foreach ($datos as $row) {
                $resultado['labels'][] = $row['mes_texto'];
                $resultado['data'][] = $row['cantidad'];
            }
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en obtenerEvolucionMensual: " . $e->getMessage());
            return ['labels' => [], 'data' => []];
        }
    }
    
    // Método adicional para obtener datos de estudiantes con prácticas
    public function obtenerEstudiantesConPracticas()
    {
        try {
            $sql = "SELECT 
                        e.id,
                        e.dni_est,
                        CONCAT(e.ap_est, ' ', e.am_est, ', ', e.nom_est) as nombre_completo,
                        p.nom_progest as programa,
                        COUNT(pr.id) as total_practicas,
                        SUM(CASE WHEN pr.estado = 'En curso' THEN 1 ELSE 0 END) as practicas_curso,
                        SUM(COALESCE(pr.horas_acumuladas, 0)) as horas_acumuladas
                    FROM estudiante e
                    LEFT JOIN matricula m ON e.id = m.estudiante
                    LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
                    LEFT JOIN practicas pr ON e.id = pr.estudiante
                    WHERE e.estado = 1
                    GROUP BY e.id
                    ORDER BY total_practicas DESC
                    LIMIT 10";
            
            $stmt = $this->executeQuery($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en obtenerEstudiantesConPracticas: " . $e->getMessage());
            return [];
        }
    }
}
?>