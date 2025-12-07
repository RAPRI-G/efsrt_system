<?php
require_once 'BaseModel.php';

class AsistenciaModel extends BaseModel
{
    private $table = 'asistencias';

    // Obtener asistencias por práctica
    public function obtenerPorPractica($practica_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE practicas = :practica_id ORDER BY fecha ASC";
        $stmt = $this->executeQuery($sql, [':practica_id' => $practica_id]);
        return $stmt->fetchAll();
    }

    // Obtener horas acumuladas por práctica
    public function obtenerHorasAcumuladas($practica_id)
    {
        $sql = "SELECT SUM(horas_acumuladas) as total FROM {$this->table} WHERE practicas = :practica_id";
        $stmt = $this->executeQuery($sql, [':practica_id' => $practica_id]);
        $result = $stmt->fetch();
        return $result['total'] ?: 0;
    }

    // Obtener todas las asistencias de un estudiante
    public function obtenerPorEstudiante($estudiante_id)
    {
        $sql = "SELECT a.*, p.tipo_efsrt, p.modulo 
                FROM {$this->table} a
                INNER JOIN practicas p ON a.practicas = p.id
                WHERE p.estudiante = :estudiante_id
                ORDER BY a.fecha DESC";
        $stmt = $this->executeQuery($sql, [':estudiante_id' => $estudiante_id]);
        return $stmt->fetchAll();
    }

    // Obtener estadísticas generales para dashboard

    public function obtenerEstadisticasDashboard()
    {
        try {
            // 1. Total estudiantes con prácticas activas
            $sqlEstudiantes = "SELECT COUNT(DISTINCT p.estudiante) as total 
                          FROM practicas p 
                          WHERE p.estado IN ('En curso', 'Finalizado')";
            $stmt = $this->executeQuery($sqlEstudiantes);
            $estudiantes = $stmt->fetch();

            // 2. Horas totales registradas (de asistencias)
            $sqlHoras = "SELECT COALESCE(SUM(a.horas_acumuladas), 0) as total 
                    FROM asistencias a
                    INNER JOIN practicas p ON a.practicas = p.id
                    WHERE p.estado IN ('En curso', 'Finalizado')";
            $stmt = $this->executeQuery($sqlHoras);
            $horas = $stmt->fetch();

            // 3. Módulos completados CORREGIDO (128 horas alcanzadas O estado = 'Finalizado')
            $sqlCompletados = "SELECT COUNT(*) as total 
                          FROM practicas p
                          WHERE p.estado = 'Finalizado' 
                          OR p.horas_acumuladas >= 128";
            $stmt = $this->executeQuery($sqlCompletados);
            $completados = $stmt->fetch();

            // 4. Total módulos (solo los que tienen estado)
            $sqlTotalModulos = "SELECT COUNT(*) as total FROM practicas 
                           WHERE estado IN ('En curso', 'Finalizado')";
            $stmt = $this->executeQuery($sqlTotalModulos);
            $totalModulos = $stmt->fetch();

            // 5. Calcular tasa de cumplimiento (módulos completados / total módulos)
            $tasa_cumplimiento = 0;
            if ($totalModulos['total'] > 0) {
                $tasa_cumplimiento = round(($completados['total'] / $totalModulos['total']) * 100);
            }

            // 6. Estudiantes en curso (que tienen al menos un módulo en curso)
            $sqlEnCurso = "SELECT COUNT(DISTINCT estudiante) as total 
                      FROM practicas 
                      WHERE estado = 'En curso'";
            $stmt = $this->executeQuery($sqlEnCurso);
            $en_curso = $stmt->fetch();

            // 7. Empresas activas
            $sqlEmpresas = "SELECT COUNT(DISTINCT empresa) as total 
                       FROM practicas 
                       WHERE estado IN ('En curso', 'Finalizado')";
            $stmt = $this->executeQuery($sqlEmpresas);
            $empresas = $stmt->fetch();

            return [
                'total_estudiantes' => $estudiantes['total'] ?? 0,
                'horas_totales' => $horas['total'] ?? 0,
                'modulos_completados' => $completados['total'] ?? 0,
                'total_modulos' => $totalModulos['total'] ?? 0,
                'tasa_cumplimiento' => $tasa_cumplimiento,
                'estudiantes_en_curso' => $en_curso['total'] ?? 0,
                'empresas_activas' => $empresas['total'] ?? 0
            ];
        } catch (Exception $e) {
            error_log("Error en obtenerEstadisticasDashboard: " . $e->getMessage());
            return [
                'total_estudiantes' => 0,
                'horas_totales' => 0,
                'modulos_completados' => 0,
                'total_modulos' => 0,
                'tasa_cumplimiento' => 0,
                'estudiantes_en_curso' => 0,
                'empresas_activas' => 0
            ];
        }
    }

    // Obtener datos de estudiantes con sus módulos para el dashboard
    public function obtenerEstudiantesConModulos()
    {
        try {
            // MODIFICADO: Solo estudiantes con prácticas activas o en curso
            $sqlEstudiantes = "SELECT 
            e.id,
            e.dni_est,
            e.ap_est,
            e.am_est,
            e.nom_est,
            e.cel_est,
            e.mailp_est,
            p.nom_progest as programa,
            CONCAT(e.ap_est, ' ', COALESCE(e.am_est, ''), ', ', e.nom_est) as nombre_completo,
            UPPER(CONCAT(
                SUBSTRING(e.ap_est, 1, 1), 
                COALESCE(SUBSTRING(e.am_est, 1, 1), '')
            )) as iniciales
        FROM estudiante e
        LEFT JOIN matricula m ON e.id = m.estudiante
        LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
        
        -- NUEVA CONDICIÓN: Solo estudiantes que tienen al menos una práctica
        WHERE e.estado = 1 
        AND EXISTS (
            SELECT 1 FROM practicas pr 
            WHERE pr.estudiante = e.id 
            AND pr.estado IN ('En curso', 'Finalizado', 'Pendiente')
            -- Si quieres solo prácticas activas, usa: AND pr.estado IN ('En curso')
        )
        
        GROUP BY e.id, e.dni_est, e.ap_est, e.am_est, e.nom_est
        ORDER BY e.ap_est, e.am_est, e.nom_est";

            $stmt = $this->executeQuery($sqlEstudiantes);
            $estudiantes = $stmt->fetchAll();

            // Para cada estudiante, obtener sus módulos
            foreach ($estudiantes as &$estudiante) {
                $estudiante['modulos'] = $this->obtenerModulosDetallados($estudiante['id']);
                $estudiante['empresa'] = $this->obtenerEmpresaEstudiante($estudiante['id']);
            }

            error_log("✅ Estudiantes con prácticas obtenidos: " . count($estudiantes));
            return $estudiantes;
        } catch (Exception $e) {
            error_log("Error en obtenerEstudiantesConModulos: " . $e->getMessage());
            return [];
        }
    }

    // Obtener datos detallados de módulos de un estudiante (PÚBLICO para acceso desde controlador)
    public function obtenerModulosDetallados($estudiante_id)
    {
        $modulos = [];
        $tipos_modulos = ['modulo1', 'modulo2', 'modulo3'];
        $horas_requeridas = [
            'modulo1' => 128,
            'modulo2' => 128,
            'modulo3' => 128
        ];

        foreach ($tipos_modulos as $tipo) {
            $sql = "SELECT 
                p.id as practica_id,
                p.tipo_efsrt,
                p.modulo,
                p.fecha_inicio,
                p.fecha_fin,
                p.total_horas,
                p.estado,
                p.area_ejecucion,
                COALESCE(SUM(a.horas_acumuladas), 0) as horas_acumuladas
            FROM practicas p
            LEFT JOIN asistencias a ON p.id = a.practicas
            WHERE p.estudiante = :estudiante_id 
            AND p.tipo_efsrt = :tipo
            GROUP BY p.id
            ORDER BY p.fecha_inicio DESC
            LIMIT 1";

            $stmt = $this->executeQuery($sql, [
                ':estudiante_id' => $estudiante_id,
                ':tipo' => $tipo
            ]);
            $modulo_data = $stmt->fetch();

            if ($modulo_data && !empty($modulo_data['practica_id'])) {
                $horas_acumuladas = $modulo_data['horas_acumuladas'];
                $horas_requeridas_modulo = $horas_requeridas[$tipo];

                // Determinar estado PRIMERO por horas_acumuladas de la tabla practicas
                $estado_modulo = $modulo_data['estado'];

                // Si en la BD dice que está finalizado, respetar eso
                if ($estado_modulo === 'Finalizado') {
                    $estado = 'completado';
                }
                // Si no está marcado como finalizado pero tiene 128+ horas, está completado
                else if ($modulo_data['horas_acumuladas'] >= 128) {
                    $estado = 'completado';
                }
                // Si tiene horas pero menos de 128, está en curso
                else if ($modulo_data['horas_acumuladas'] > 0) {
                    $estado = 'en_curso';
                }
                // Si no tiene horas registradas
                else {
                    $estado = 'pendiente';
                }

                $porcentaje = $horas_requeridas_modulo > 0 ?
                    min(100, round(($horas_acumuladas / $horas_requeridas_modulo) * 100)) : 0;

                $modulos[] = [
                    'id' => $modulo_data['tipo_efsrt'],
                    'nombre' => $modulo_data['modulo'],
                    'practica_id' => $modulo_data['practica_id'],
                    'fecha_inicio' => $modulo_data['fecha_inicio'],
                    'fecha_fin' => $modulo_data['fecha_fin'],
                    'horas_acumuladas' => $horas_acumuladas,
                    'horas_requeridas' => $horas_requeridas_modulo,
                    'porcentaje' => $porcentaje,
                    'estado' => $estado,
                    'estado_db' => $modulo_data['estado'],
                    'area_ejecucion' => $modulo_data['area_ejecucion']
                ];
            } else {
                // Si no tiene práctica para este módulo
                $modulos[] = [
                    'id' => $tipo,
                    'nombre' => 'Módulo ' . substr($tipo, -1),
                    'practica_id' => null,
                    'fecha_inicio' => null,
                    'fecha_fin' => null,
                    'horas_acumuladas' => 0,
                    'horas_requeridas' => $horas_requeridas[$tipo],
                    'porcentaje' => 0,
                    'estado' => 'no_iniciado',
                    'estado_db' => null,
                    'area_ejecucion' => null
                ];
            }
        }

        return $modulos;
    }

    // Método específico para contar módulos completados
    public function contarModulosCompletados()
    {
        try {
            // Contar módulos que están completados (128+ horas O estado Finalizado)
            $sql = "SELECT COUNT(*) as total 
                FROM practicas 
                WHERE (estado = 'Finalizado' OR horas_acumuladas >= 128)
                AND estado IS NOT NULL";

            $stmt = $this->executeQuery($sql);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error contando módulos completados: " . $e->getMessage());
            return 0;
        }
    }
    // Obtener empresa del estudiante
    private function obtenerEmpresaEstudiante($estudiante_id)
    {
        try {
            $sql = "SELECT e.* FROM empresa e 
                    INNER JOIN practicas p ON e.id = p.empresa 
                    WHERE p.estudiante = :estudiante_id 
                    ORDER BY p.fecha_inicio DESC 
                    LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':estudiante_id' => $estudiante_id]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (Exception $e) {
            error_log("Error obteniendo empresa: " . $e->getMessage());
            return null;
        }
    }

    // En models/AsistenciaModel.php - MODIFICAR EL MÉTODO registrar()
    public function registrar($datos)
    {
        try {
            // Iniciar transacción
            $this->db->beginTransaction();

            // 1. Insertar la asistencia
            $sql = "INSERT INTO {$this->table} 
                (practicas, fecha, hora_entrada, hora_salida, horas_acumuladas, actividad) 
                VALUES (:practicas, :fecha, :hora_entrada, :hora_salida, :horas_acumuladas, :actividad)";

            $params = [
                ':practicas' => $datos['practica_id'],
                ':fecha' => $datos['fecha'],
                ':hora_entrada' => $datos['hora_entrada'],
                ':hora_salida' => $datos['hora_salida'],
                ':horas_acumuladas' => $datos['horas_acumuladas'],
                ':actividad' => $datos['actividad']
            ];

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            // 2. Actualizar horas acumuladas en la tabla practicas
            $sqlUpdate = "UPDATE practicas 
                     SET horas_acumuladas = (
                         SELECT COALESCE(SUM(horas_acumuladas), 0) 
                         FROM asistencias 
                         WHERE practicas = :practica_id
                     )
                     WHERE id = :practica_id";

            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->execute([':practica_id' => $datos['practica_id']]);

            // 3. Verificar si se completó el módulo (128 horas)
            $sqlCheckComplete = "SELECT horas_acumuladas FROM practicas WHERE id = :practica_id";
            $stmtCheck = $this->db->prepare($sqlCheckComplete);
            $stmtCheck->execute([':practica_id' => $datos['practica_id']]);
            $result = $stmtCheck->fetch();

            if ($result && $result['horas_acumuladas'] >= 128) {
                // Actualizar estado a 'Finalizado' si alcanza o supera 128 horas
                $sqlUpdateEstado = "UPDATE practicas SET estado = 'Finalizado' WHERE id = :practica_id AND estado != 'Finalizado'";
                $stmtEstado = $this->db->prepare($sqlUpdateEstado);
                $stmtEstado->execute([':practica_id' => $datos['practica_id']]);
            }

            // Confirmar transacción
            $this->db->commit();

            return true;
        } catch (Exception $e) {
            // Revertir en caso de error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error al registrar asistencia: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar asistencia
    // En models/AsistenciaModel.php - MODIFICAR EL MÉTODO eliminar()
    public function eliminar($id)
    {
        try {
            // 1. Primero obtener el ID de la práctica para actualizarla después
            $sqlGetPractica = "SELECT practicas FROM {$this->table} WHERE id = :id";
            $stmtGet = $this->db->prepare($sqlGetPractica);
            $stmtGet->execute([':id' => $id]);
            $result = $stmtGet->fetch();

            if (!$result) {
                return false;
            }

            $practica_id = $result['practicas'];

            // 2. Iniciar transacción
            $this->db->beginTransaction();

            // 3. Eliminar la asistencia
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $deleted = $stmt->rowCount() > 0;

            if ($deleted) {
                // 4. Actualizar horas acumuladas en practicas
                $sqlUpdate = "UPDATE practicas 
                         SET horas_acumuladas = (
                             SELECT COALESCE(SUM(horas_acumuladas), 0) 
                             FROM asistencias 
                             WHERE practicas = :practica_id
                         )
                         WHERE id = :practica_id";

                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([':practica_id' => $practica_id]);

                // 5. Verificar si hay que cambiar el estado (si baja de 128 horas)
                $sqlCheckEstado = "SELECT horas_acumuladas FROM practicas WHERE id = :practica_id";
                $stmtCheck = $this->db->prepare($sqlCheckEstado);
                $stmtCheck->execute([':practica_id' => $practica_id]);
                $horasResult = $stmtCheck->fetch();

                if ($horasResult && $horasResult['horas_acumuladas'] < 128) {
                    $sqlUpdateEstado = "UPDATE practicas SET estado = 'En curso' WHERE id = :practica_id AND estado = 'Finalizado'";
                    $stmtEstado = $this->db->prepare($sqlUpdateEstado);
                    $stmtEstado->execute([':practica_id' => $practica_id]);
                }

                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                return false;
            }
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error al eliminar asistencia: " . $e->getMessage());
            return false;
        }
    }

    // Obtener asistencia por ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT a.*, p.estudiante, p.modulo 
                FROM {$this->table} a
                INNER JOIN practicas p ON a.practicas = p.id
                WHERE a.id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }

    // Obtener reporte de asistencias por período
    public function obtenerReporte($fecha_inicio, $fecha_fin, $estudiante_id = null)
    {
        $sql = "SELECT 
                a.*,
                p.modulo,
                p.tipo_efsrt,
                e.dni_est,
                e.ap_est,
                e.am_est,
                e.nom_est,
                emp.razon_social as empresa
            FROM {$this->table} a
            INNER JOIN practicas p ON a.practicas = p.id
            INNER JOIN estudiante e ON p.estudiante = e.id
            INNER JOIN empresa emp ON p.empresa = emp.id
            WHERE a.fecha BETWEEN :fecha_inicio AND :fecha_fin";

        $params = [
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin
        ];

        if ($estudiante_id) {
            $sql .= " AND p.estudiante = :estudiante_id";
            $params[':estudiante_id'] = $estudiante_id;
        }

        $sql .= " ORDER BY a.fecha DESC, a.hora_entrada DESC";

        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetchAll();
    }

    // Obtener módulos con más horas registradas
    public function obtenerModulosTop()
    {
        $sql = "SELECT 
                p.modulo,
                p.tipo_efsrt,
                COUNT(DISTINCT p.estudiante) as estudiantes,
                SUM(a.horas_acumuladas) as horas_totales
            FROM practicas p
            LEFT JOIN asistencias a ON p.id = a.practicas
            WHERE p.estado IN ('En curso', 'Finalizado')
            GROUP BY p.modulo, p.tipo_efsrt
            ORDER BY horas_totales DESC
            LIMIT 5";

        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
}
