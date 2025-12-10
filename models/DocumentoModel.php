<?php
require_once 'BaseModel.php';

class DocumentoModel extends BaseModel
{
    private $table = 'efsrt_documentos';

    /**
     * Obtener todos los documentos de una práctica
     */
    public function obtenerDocumentosPorPractica($practica_id)
    {
        $sql = "SELECT d.*, 
                       u.usuario as generado_por_usuario
                FROM {$this->table} d
                LEFT JOIN usuarios u ON d.generado_por = u.id
                WHERE d.practica_id = :practica_id 
                ORDER BY d.fecha_generacion DESC";

        $stmt = $this->executeQuery($sql, [':practica_id' => $practica_id]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener documento específico por tipo
     */
    public function obtenerDocumentoPorTipo($practica_id, $tipo_documento)
    {
        $sql = "SELECT d.*, 
                       u.usuario as generado_por_usuario
                FROM {$this->table} d
                LEFT JOIN usuarios u ON d.generado_por = u.id
                WHERE d.practica_id = :practica_id 
                AND d.tipo_documento = :tipo_documento
                ORDER BY d.fecha_generacion DESC 
                LIMIT 1";

        $stmt = $this->executeQuery($sql, [
            ':practica_id' => $practica_id,
            ':tipo_documento' => $tipo_documento
        ]);

        return $stmt->fetch();
    }

    /**
     * Crear nuevo documento
     */
    public function crearDocumento($datos)
    {
        $sql = "INSERT INTO {$this->table} 
                (practica_id, tipo_documento, numero_oficio, contenido, 
                 fecha_documento, fecha_generacion, generado_por, estado) 
                VALUES (:practica_id, :tipo_documento, :numero_oficio, :contenido,
                        :fecha_documento, NOW(), :generado_por, :estado)";

        $params = [
            ':practica_id' => $datos['practica_id'],
            ':tipo_documento' => $datos['tipo_documento'],
            ':numero_oficio' => $datos['numero_oficio'] ?? null,
            ':contenido' => $datos['contenido'] ?? '',
            ':fecha_documento' => $datos['fecha_documento'] ?? date('Y-m-d'),
            ':generado_por' => $datos['generado_por'] ?? ($_SESSION['usuario']['id'] ?? null),
            ':estado' => $datos['estado'] ?? 'generado'
        ];

        $stmt = $this->executeQuery($sql, $params);
        $documento_id = $this->db->lastInsertId();

        error_log("✅ Documento creado - ID: $documento_id, Tipo: " . $datos['tipo_documento']);
        return $documento_id;
    }

    /**
     * Actualizar documento existente
     */
    public function actualizarDocumento($id, $datos)
    {
        $sql = "UPDATE {$this->table} SET 
                contenido = :contenido,
                fecha_documento = :fecha_documento,
                estado = :estado,
                fecha_generacion = NOW()
                WHERE id = :id";

        $params = [
            ':contenido' => $datos['contenido'] ?? '',
            ':fecha_documento' => $datos['fecha_documento'] ?? date('Y-m-d'),
            ':estado' => $datos['estado'] ?? 'generado',
            ':id' => $id
        ];

        $stmt = $this->executeQuery($sql, $params);
        $actualizado = $stmt->rowCount() > 0;

        if ($actualizado) {
            error_log("✅ Documento actualizado - ID: $id");
        }

        return $actualizado;
    }

    /**
     * Obtener estadísticas de documentos por estudiante
     */
    public function obtenerEstadisticasPorEstudiante($estudiante_id)
    {
        $sql = "SELECT 
                d.tipo_documento,
                COUNT(*) as total,
                SUM(CASE WHEN d.estado = 'generado' THEN 1 ELSE 0 END) as generados,
                SUM(CASE WHEN d.estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes
            FROM {$this->table} d
            INNER JOIN practicas p ON d.practica_id = p.id
            WHERE p.estudiante = :estudiante_id
            GROUP BY d.tipo_documento";

        $stmt = $this->executeQuery($sql, [':estudiante_id' => $estudiante_id]);
        return $stmt->fetchAll();
    }

    /**
     * Verificar si existe documento para una práctica
     */
    public function existeDocumento($practica_id, $tipo_documento)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE practica_id = :practica_id 
                AND tipo_documento = :tipo_documento";

        $stmt = $this->executeQuery($sql, [
            ':practica_id' => $practica_id,
            ':tipo_documento' => $tipo_documento
        ]);

        $result = $stmt->fetch();
        return ($result['total'] ?? 0) > 0;
    }

    /**
     * Obtener documento por ID
     */
    public function obtenerDocumentoPorId($documento_id)
    {
        $sql = "SELECT d.*, 
                       p.estudiante,
                       p.tipo_efsrt,
                       u.usuario as generado_por_usuario
                FROM {$this->table} d
                INNER JOIN practicas p ON d.practica_id = p.id
                LEFT JOIN usuarios u ON d.generado_por = u.id
                WHERE d.id = :documento_id";

        $stmt = $this->executeQuery($sql, [':documento_id' => $documento_id]);
        return $stmt->fetch();
    }

    /**
     * Eliminar documento
     */
    public function eliminarDocumento($documento_id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :documento_id";
            $stmt = $this->executeQuery($sql, [':documento_id' => $documento_id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("❌ Error eliminando documento ID $documento_id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener documentos recientes de un estudiante
     */
    public function obtenerDocumentosRecientes($estudiante_id, $limite = 5)
    {
        $sql = "SELECT 
                d.*,
                p.tipo_efsrt,
                p.modulo,
                u.usuario as generado_por_usuario,
                DATE_FORMAT(d.fecha_generacion, '%d/%m/%Y %H:%i') as fecha_formateada
            FROM {$this->table} d
            INNER JOIN practicas p ON d.practica_id = p.id
            LEFT JOIN usuarios u ON d.generado_por = u.id
            WHERE p.estudiante = :estudiante_id
            ORDER BY d.fecha_generacion DESC
            LIMIT :limite";

        $stmt = $this->executeQuery($sql, [
            ':estudiante_id' => $estudiante_id,
            ':limite' => (int)$limite
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Contar documentos por estado
     */
    public function contarDocumentosPorEstado($estudiante_id)
    {
        $sql = "SELECT 
                d.estado,
                COUNT(*) as cantidad
            FROM {$this->table} d
            INNER JOIN practicas p ON d.practica_id = p.id
            WHERE p.estudiante = :estudiante_id
            GROUP BY d.estado";

        $stmt = $this->executeQuery($sql, [':estudiante_id' => $estudiante_id]);
        $resultados = $stmt->fetchAll();

        $conteo = [
            'generado' => 0,
            'pendiente' => 0
        ];

        foreach ($resultados as $row) {
            $estado = $row['estado'] ?? 'pendiente';
            $conteo[$estado] = $row['cantidad'];
        }

        return $conteo;
    }

    /**
     * Generar número de oficio automático
     */
    public function generarNumeroOficio($tipo_documento)
    {
        $prefijos = [
            'oficio_multiple' => 'OF-MUL',
            'carta_presentacion' => 'CAR-PRE',
            'ficha_identidad' => 'FIC-ID',
            'solicitud_practicas' => 'SOL-PRA',
            'evaluacion_efsrt' => 'EVAL-EFSRT',
            'ficha_asistencias' => 'FIC-ASIS'
        ];

        $prefijo = $prefijos[$tipo_documento] ?? 'DOC';
        $anio = date('Y');
        $mes = date('m');

        // Obtener último número del mes
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE numero_oficio LIKE :prefijo 
                AND YEAR(fecha_generacion) = :anio 
                AND MONTH(fecha_generacion) = :mes";

        $stmt = $this->executeQuery($sql, [
            ':prefijo' => $prefijo . '-%',
            ':anio' => $anio,
            ':mes' => $mes
        ]);

        $result = $stmt->fetch();
        $numero = ($result['total'] ?? 0) + 1;

        return sprintf("%s-%s-%s-%03d", $prefijo, $anio, $mes, $numero);
    }

    /**
     * Obtener documentos por tipo para un estudiante
     */
    public function obtenerDocumentosPorTipoEstudiante($estudiante_id, $tipo_documento)
    {
        $sql = "SELECT 
                d.*,
                p.tipo_efsrt,
                p.modulo,
                p.fecha_inicio,
                p.fecha_fin,
                DATE_FORMAT(d.fecha_generacion, '%d/%m/%Y') as fecha_generacion_formateada
            FROM {$this->table} d
            INNER JOIN practicas p ON d.practica_id = p.id
            WHERE p.estudiante = :estudiante_id
            AND d.tipo_documento = :tipo_documento
            ORDER BY d.fecha_generacion DESC";

        $stmt = $this->executeQuery($sql, [
            ':estudiante_id' => $estudiante_id,
            ':tipo_documento' => $tipo_documento
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Buscar documentos por término
     */
    public function buscarDocumentos($estudiante_id, $termino)
    {
        $sql = "SELECT 
                d.*,
                p.tipo_efsrt,
                p.modulo,
                CASE d.tipo_documento
                    WHEN 'solicitud_practicas' THEN 'Solicitud de Prácticas'
                    WHEN 'carta_presentacion' THEN 'Carta de Presentación'
                    WHEN 'ficha_asistencias' THEN 'Ficha de Asistencias'
                    WHEN 'evaluacion_efsrt' THEN 'Evaluación EFSRT'
                    WHEN 'oficio_multiple' THEN 'Oficio Múltiple'
                    WHEN 'ficha_identidad' THEN 'Ficha de Identidad'
                    ELSE d.tipo_documento
                END as tipo_documento_nombre,
                DATE_FORMAT(d.fecha_generacion, '%d/%m/%Y') as fecha_formateada
            FROM {$this->table} d
            INNER JOIN practicas p ON d.practica_id = p.id
            WHERE p.estudiante = :estudiante_id
            AND (
                d.contenido LIKE :termino OR
                d.numero_oficio LIKE :termino OR
                p.modulo LIKE :termino
            )
            ORDER BY d.fecha_generacion DESC";

        $terminoBusqueda = '%' . $this->sanitize($termino) . '%';

        $stmt = $this->executeQuery($sql, [
            ':estudiante_id' => $estudiante_id,
            ':termino' => $terminoBusqueda
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Sanitizar input
     */
    public function sanitize($input)
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}
