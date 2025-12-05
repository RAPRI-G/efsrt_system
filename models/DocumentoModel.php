<?php
require_once 'BaseModel.php';

class DocumentoModel extends BaseModel
{
    private $table = 'efsrt_documentos';

    // Obtener documentos con información completa
    public function obtenerDocumentos($filtros = [])
    {
        $sql = "SELECT 
                    d.*,
                    -- Datos de la práctica
                    p.estudiante,
                    p.empresa,
                    p.modulo,
                    p.tipo_efsrt,
                    p.periodo_academico,
                    p.fecha_inicio,
                    p.fecha_fin,
                    p.total_horas,
                    p.area_ejecucion,
                    p.supervisor_empresa,
                    p.cargo_supervisor,
                    p.estado as estado_practica,
                    -- Datos del estudiante
                    CONCAT(e.ap_est, ' ', e.am_est, ', ', e.nom_est) as nombre_estudiante,
                    e.dni_est,
                    e.cel_est,
                    e.mailp_est,
                    -- Datos de la empresa
                    emp.razon_social as nombre_empresa,
                    emp.representante_legal,
                    -- Datos del empleado/administrador que generó
                    em.apnom_emp as generador_nombre
                FROM {$this->table} d
                INNER JOIN practicas p ON d.practica_id = p.id
                INNER JOIN estudiante e ON p.estudiante = e.id
                LEFT JOIN empresa emp ON p.empresa = emp.id
                LEFT JOIN empleado em ON d.generado_por = em.id
                WHERE 1=1";
        
        $params = [];

        // Aplicar filtros
        if (!empty($filtros['tipo_documento']) && $filtros['tipo_documento'] !== 'all') {
            $sql .= " AND d.tipo_documento = :tipo_documento";
            $params[':tipo_documento'] = $filtros['tipo_documento'];
        }

        if (!empty($filtros['estado']) && $filtros['estado'] !== 'all') {
            $sql .= " AND d.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        if (!empty($filtros['estudiante_id']) && $filtros['estudiante_id'] !== 'all') {
            $sql .= " AND p.estudiante = :estudiante_id";
            $params[':estudiante_id'] = $filtros['estudiante_id'];
        }

        if (!empty($filtros['modulo']) && $filtros['modulo'] !== 'all') {
            $sql .= " AND p.tipo_efsrt = :modulo";
            $params[':modulo'] = $filtros['modulo'];
        }

        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (CONCAT(e.ap_est, ' ', e.am_est, ', ', e.nom_est) LIKE :busqueda 
                      OR e.dni_est LIKE :busqueda 
                      OR d.numero_oficio LIKE :busqueda
                      OR emp.razon_social LIKE :busqueda)";
            $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
        }

        $sql .= " ORDER BY d.fecha_generacion DESC, d.id DESC";

        try {
            error_log("🔍 Consultando documentos con filtros: " . print_r($filtros, true));
            $stmt = $this->executeQuery($sql, $params);
            $resultados = $stmt->fetchAll();
            error_log("✅ Documentos obtenidos: " . count($resultados));
            return $resultados;
        } catch (Exception $e) {
            error_log("❌ Error en obtenerDocumentos: " . $e->getMessage());
            return [];
        }
    }

    // Obtener estadísticas para el dashboard
    public function obtenerEstadisticasDocumentos()
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pendientes,
                        COUNT(CASE WHEN estado = 'generado' THEN 1 END) as generados,
                        COUNT(CASE WHEN tipo_documento = 'carta_presentacion' THEN 1 END) as cartas,
                        COUNT(CASE WHEN tipo_documento = 'oficio_multiple' THEN 1 END) as oficios,
                        COUNT(CASE WHEN tipo_documento = 'ficha_identidad' THEN 1 END) as fichas
                    FROM {$this->table}";
            
            $stmt = $this->executeQuery($sql);
            $resultado = $stmt->fetch();
            
            // Calcular asistencias completadas (horas completas)
            $sqlHoras = "SELECT COUNT(DISTINCT p.id) as completas
                        FROM practicas p
                        LEFT JOIN asistencias a ON p.id = a.practicas
                        WHERE p.estado = 'Finalizado' 
                        OR (SELECT COALESCE(SUM(horas_acumuladas), 0) 
                            FROM asistencias a2 
                            WHERE a2.practicas = p.id) >= p.total_horas";
            
            $stmtHoras = $this->executeQuery($sqlHoras);
            $horasResult = $stmtHoras->fetch();
            
            $resultado['asistencias_completas'] = $horasResult['completas'] ?? 0;
            
            return $resultado;
        } catch (Exception $e) {
            error_log("❌ Error en obtenerEstadisticasDocumentos: " . $e->getMessage());
            return [
                'total' => 0,
                'pendientes' => 0,
                'generados' => 0,
                'cartas' => 0,
                'oficios' => 0,
                'fichas' => 0,
                'asistencias_completas' => 0
            ];
        }
    }

    // Obtener documento por ID
    public function obtenerDocumentoPorId($id)
    {
        $sql = "SELECT d.*, 
                       CONCAT(e.ap_est, ' ', e.am_est, ', ', e.nom_est) as nombre_estudiante,
                       e.dni_est,
                       p.modulo,
                       p.tipo_efsrt,
                       emp.razon_social as nombre_empresa
                FROM {$this->table} d
                INNER JOIN practicas p ON d.practica_id = p.id
                INNER JOIN estudiante e ON p.estudiante = e.id
                LEFT JOIN empresa emp ON p.empresa = emp.id
                WHERE d.id = :id";
        
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }

    // Crear nuevo documento
    public function crearDocumento($datos)
    {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (practica_id, tipo_documento, numero_oficio, contenido, 
                     fecha_documento, generado_por, estado, fecha_generacion) 
                    VALUES (:practica_id, :tipo_documento, :numero_oficio, :contenido,
                            :fecha_documento, :generado_por, :estado, NOW())";
            
            $params = [
                ':practica_id' => $datos['practica_id'],
                ':tipo_documento' => $datos['tipo_documento'],
                ':numero_oficio' => $datos['numero_oficio'] ?? null,
                ':contenido' => $datos['contenido'] ?? '',
                ':fecha_documento' => $datos['fecha_documento'] ?? date('Y-m-d'),
                ':generado_por' => $datos['generado_por'] ?? null,
                ':estado' => $datos['estado'] ?? 'pendiente'
            ];
            
            error_log("📝 Creando documento: " . print_r($params, true));
            
            $stmt = $this->executeQuery($sql, $params);
            $documentoId = $this->db->lastInsertId();
            
            error_log("✅ Documento creado con ID: " . $documentoId);
            return $documentoId;
            
        } catch (Exception $e) {
            error_log("❌ Error en crearDocumento: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar estado del documento
    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE {$this->table} SET estado = :estado WHERE id = :id";
        $stmt = $this->executeQuery($sql, [':estado' => $estado, ':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Eliminar documento
    public function eliminarDocumento($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->executeQuery($sql, [':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("❌ Error en eliminarDocumento: " . $e->getMessage());
            return false;
        }
    }

    // Generar número de oficio automático
    public function generarNumeroOficio()
    {
        try {
            $anio = date('Y');
            $sql = "SELECT COUNT(*) + 1 as consecutivo 
                    FROM {$this->table} 
                    WHERE YEAR(fecha_generacion) = :anio 
                    AND tipo_documento = 'oficio_multiple'";
            
            $stmt = $this->executeQuery($sql, [':anio' => $anio]);
            $result = $stmt->fetch();
            
            $consecutivo = $result['consecutivo'] ?? 1;
            return "{$consecutivo}-IESTP-AACD-DG/{$anio}";
            
        } catch (Exception $e) {
            error_log("❌ Error en generarNumeroOficio: " . $e->getMessage());
            return "1-IESTP-AACD-DG/" . date('Y');
        }
    }

    // Verificar horas completadas de una práctica
    public function verificarHorasCompletadas($practicaId)
    {
        try {
            $sql = "SELECT 
                        p.total_horas,
                        COALESCE(SUM(a.horas_acumuladas), 0) as horas_acumuladas,
                        CASE 
                            WHEN COALESCE(SUM(a.horas_acumuladas), 0) >= p.total_horas THEN 1
                            ELSE 0
                        END as completado
                    FROM practicas p
                    LEFT JOIN asistencias a ON p.id = a.practicas
                    WHERE p.id = :practica_id
                    GROUP BY p.id";
            
            $stmt = $this->executeQuery($sql, [':practica_id' => $practicaId]);
            $resultado = $stmt->fetch();
            
            if (!$resultado) {
                return [
                    'total_horas' => 0,
                    'horas_acumuladas' => 0,
                    'completado' => false,
                    'porcentaje' => 0
                ];
            }
            
            $porcentaje = $resultado['total_horas'] > 0 
                ? min(100, round(($resultado['horas_acumuladas'] / $resultado['total_horas']) * 100))
                : 0;
            
            return [
                'total_horas' => $resultado['total_horas'] ?? 128,
                'horas_acumuladas' => $resultado['horas_acumuladas'] ?? 0,
                'completado' => (bool)($resultado['completado'] ?? 0),
                'porcentaje' => $porcentaje
            ];
            
        } catch (Exception $e) {
            error_log("❌ Error en verificarHorasCompletadas: " . $e->getMessage());
            return [
                'total_horas' => 128,
                'horas_acumuladas' => 0,
                'completado' => false,
                'porcentaje' => 0
            ];
        }
    }

    // Obtener prácticas por estudiante (para select en formulario)
    public function obtenerPracticasPorEstudiante($estudianteId)
    {
        try {
            $sql = "SELECT p.*, 
                           emp.razon_social as nombre_empresa,
                           p.modulo as nombre_modulo
                    FROM practicas p
                    LEFT JOIN empresa emp ON p.empresa = emp.id
                    WHERE p.estudiante = :estudiante_id
                    ORDER BY p.fecha_inicio DESC";
            
            $stmt = $this->executeQuery($sql, [':estudiante_id' => $estudianteId]);
            $practicas = $stmt->fetchAll();
            
            // Agregar información de horas a cada práctica
            foreach ($practicas as &$practica) {
                $horasInfo = $this->verificarHorasCompletadas($practica['id']);
                $practica['horas_info'] = $horasInfo;
            }
            
            return $practicas;
            
        } catch (Exception $e) {
            error_log("❌ Error en obtenerPracticasPorEstudiante: " . $e->getMessage());
            return [];
        }
    }
}
?>