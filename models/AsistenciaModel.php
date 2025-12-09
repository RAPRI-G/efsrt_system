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

                // NUEVA LÓGICA CORREGIDA PARA DETERMINAR ESTADO:

                // 1. Si el estado en BD es 'Finalizado', respetarlo
                if ($modulo_data['estado'] === 'Finalizado') {
                    $estado = 'completado';
                }
                // 2. Si tiene 128+ horas pero no está marcado como Finalizado
                else if ($horas_acumuladas >= 128) {
                    $estado = 'completado';
                    // Opcional: Actualizar estado en BD si no está como Finalizado
                    $this->actualizarEstadoPractica($modulo_data['practica_id'], 'Finalizado');
                }
                // 3. Si tiene al menos 1 hora registrada, está EN CURSO
                else if ($horas_acumuladas > 0) {
                    $estado = 'en_curso';
                    // Si el estado en BD es diferente, actualizarlo
                    if ($modulo_data['estado'] !== 'En curso') {
                        $this->actualizarEstadoPractica($modulo_data['practica_id'], 'En curso');
                    }
                }
                // 4. Si NO tiene horas pero la práctica existe, también está EN CURSO (no pendiente)
                else {
                    // IMPORTANTE: Si la práctica existe pero no tiene horas, está EN CURSO
                    $estado = 'en_curso';
                    // Actualizar estado en BD si es necesario
                    if ($modulo_data['estado'] !== 'En curso') {
                        $this->actualizarEstadoPractica($modulo_data['practica_id'], 'En curso');
                    }
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

                error_log("📊 Módulo $tipo - Estado determinado: $estado (BD: {$modulo_data['estado']}, Horas: $horas_acumuladas)");
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
                    'estado' => 'no_iniciado',  // ← Solo si NO existe la práctica
                    'estado_db' => null,
                    'area_ejecucion' => null
                ];
                error_log("📊 Módulo $tipo - No tiene práctica asignada (no_iniciado)");
            }
        }

        return $modulos;
    }

    // Método auxiliar para actualizar estado de práctica
    private function actualizarEstadoPractica($practica_id, $estado)
    {
        try {
            $sql = "UPDATE practicas SET estado = :estado WHERE id = :practica_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':estado' => $estado,
                ':practica_id' => $practica_id
            ]);
            error_log("✅ Estado de práctica $practica_id actualizado a: $estado");
            return true;
        } catch (Exception $e) {
            error_log("⚠️ Error actualizando estado de práctica $practica_id: " . $e->getMessage());
            return false;
        }
    }

    // Método para inicializar estado de práctica nueva
    public function inicializarEstadoPractica($practica_id)
    {
        try {
            // Verificar si la práctica existe y su estado actual
            $sql = "SELECT estado, horas_acumuladas FROM practicas WHERE id = :practica_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':practica_id' => $practica_id]);
            $practica = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$practica) {
                error_log("⚠️ Práctica $practica_id no encontrada para inicializar estado");
                return false;
            }

            $estado_actual = $practica['estado'];
            $horas_acumuladas = $practica['horas_acumuladas'] ?? 0;

            // Determinar estado correcto
            if ($estado_actual === 'Finalizado') {
                // Ya está finalizado, no cambiar
                return true;
            } elseif ($horas_acumuladas >= 128) {
                // Tiene suficientes horas pero no está marcado como finalizado
                $nuevo_estado = 'Finalizado';
            } elseif ($horas_acumuladas > 0) {
                // Tiene horas registradas
                $nuevo_estado = 'En curso';
            } else {
                // Nueva práctica sin horas
                $nuevo_estado = 'En curso';
            }

            // Actualizar solo si es necesario
            if ($estado_actual !== $nuevo_estado) {
                $sqlUpdate = "UPDATE practicas SET estado = :estado WHERE id = :practica_id";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([
                    ':estado' => $nuevo_estado,
                    ':practica_id' => $practica_id
                ]);
                error_log("✅ Estado de práctica $practica_id inicializado a: $nuevo_estado");
            }

            return true;
        } catch (Exception $e) {
            error_log("❌ Error inicializando estado de práctica $practica_id: " . $e->getMessage());
            return false;
        }
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
            error_log("🎯 ========= MODELO REGISTRAR - VERSIÓN CORREGIDA =========");
            error_log("🎯 Datos recibidos en modelo:");
            error_log(print_r($datos, true));

            // 1. VALIDAR ESTRUCTURA DE DATOS
            if (!isset($datos['practica_id'])) {
                error_log("❌ ERROR: 'practica_id' no encontrado en datos");
                error_log("❌ Claves disponibles: " . implode(', ', array_keys($datos)));
                throw new Exception("Falta el ID de la práctica");
            }

            $practica_id = $datos['practica_id'];

            // 2. VERIFICAR QUE LA PRÁCTICA EXISTA
            $sqlCheck = "SELECT id, estudiante FROM practicas WHERE id = :practica_id";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([':practica_id' => $practica_id]);

            if (!$stmtCheck->fetch()) {
                throw new Exception("La práctica no existe en la base de datos");
            }

            error_log("✅ Práctica ID $practica_id existe");

            // 3. INICIAR TRANSACCIÓN
            $this->db->beginTransaction();

            // 4. INSERTAR ASISTENCIA - VERSIÓN CORREGIDA
            // IMPORTANTE: La columna en la tabla se llama 'practicas' (singular)
            $sql = "INSERT INTO {$this->table} 
                (practicas, fecha, hora_entrada, hora_salida, horas_acumuladas, actividad) 
                VALUES (:practica_id, :fecha, :hora_entrada, :hora_salida, :horas_acumuladas, :actividad)";

            // NOTA: Usamos :practica_id como parámetro (porque eso es lo que recibimos del Controller)
            // pero se insertará en la columna 'practicas'
            $params = [
                ':practica_id' => $practica_id,  // ← CORREGIDO: :practica_id no :practicas
                ':fecha' => $datos['fecha'],
                ':hora_entrada' => $datos['hora_entrada'],
                ':hora_salida' => $datos['hora_salida'],
                ':horas_acumuladas' => $datos['horas_acumuladas'],
                ':actividad' => $datos['actividad']
            ];

            error_log("📝 SQL a ejecutar: " . $sql);
            error_log("📝 Parámetros:");
            error_log(print_r($params, true));

            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute($params);

            if (!$resultado) {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Error SQL: " . ($errorInfo[2] ?? 'Error desconocido'));
            }

            $asistencia_id = $this->db->lastInsertId();
            error_log("✅ Asistencia registrada con ID: $asistencia_id");

            // 5. ACTUALIZAR HORAS EN PRÁCTICA (VERSIÓN SIMPLIFICADA)
            $sqlUpdate = "UPDATE practicas 
                     SET horas_acumuladas = horas_acumuladas + :horas 
                     WHERE id = :practica_id";

            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':horas' => $datos['horas_acumuladas'],
                ':practica_id' => $practica_id
            ]);

            error_log("✅ Horas actualizadas en práctica");

            // 6. VERIFICAR SI SE COMPLETÓ EL MÓDULO
            $sqlCheckHoras = "SELECT horas_acumuladas, estado FROM practicas WHERE id = :practica_id";
            $stmtCheckHoras = $this->db->prepare($sqlCheckHoras);
            $stmtCheckHoras->execute([':practica_id' => $practica_id]);
            $practicaData = $stmtCheckHoras->fetch(PDO::FETCH_ASSOC);

            if ($practicaData && $practicaData['horas_acumuladas'] >= 128 && $practicaData['estado'] != 'Finalizado') {
                $sqlUpdateEstado = "UPDATE practicas SET estado = 'Finalizado' WHERE id = :practica_id";
                $stmtEstado = $this->db->prepare($sqlUpdateEstado);
                $stmtEstado->execute([':practica_id' => $practica_id]);
                error_log("✅ Estado actualizado a 'Finalizado'");
            }

            // 7. CONFIRMAR TRANSACCIÓN
            $this->db->commit();
            error_log("✅ Transacción completada exitosamente");

            return $asistencia_id;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("🔥 ERROR PDO: " . $e->getMessage());
            error_log("🔥 SQLSTATE: " . $e->getCode());
            throw new Exception("Error en base de datos: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("🔥 ERROR GENERAL: " . $e->getMessage());
            throw new Exception("Error al registrar asistencia: " . $e->getMessage());
        }
    }

    // Eliminar asistencia
    public function eliminar($id)
    {
        try {
            error_log("🗑️ ======= ELIMINAR ASISTENCIA =======");
            error_log("🗑️ ID de asistencia a eliminar: $id");

            // 1. Primero obtener TODOS los datos de la asistencia
            $sqlGet = "SELECT a.*, p.estudiante, p.horas_acumuladas as horas_practica 
                  FROM {$this->table} a
                  INNER JOIN practicas p ON a.practicas = p.id
                  WHERE a.id = :id";

            $stmtGet = $this->db->prepare($sqlGet);
            $stmtGet->execute([':id' => $id]);
            $asistencia = $stmtGet->fetch(PDO::FETCH_ASSOC);

            if (!$asistencia) {
                error_log("❌ Asistencia no encontrada con ID: $id");
                return false;
            }

            $practica_id = $asistencia['practicas'];
            $horas_a_eliminar = $asistencia['horas_acumuladas'];
            $horas_actuales_practica = $asistencia['horas_practica'] ?? 0;

            error_log("📊 Datos de la asistencia:");
            error_log("  - Práctica ID: $practica_id");
            error_log("  - Horas a eliminar: $horas_a_eliminar");
            error_log("  - Horas actuales en práctica: $horas_actuales_practica");

            // 2. Iniciar transacción
            $this->db->beginTransaction();
            error_log("✅ Transacción iniciada");

            // 3. Eliminar la asistencia
            $sqlDelete = "DELETE FROM {$this->table} WHERE id = :id";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->execute([':id' => $id]);

            $filas_eliminadas = $stmtDelete->rowCount();

            if ($filas_eliminadas === 0) {
                error_log("❌ No se eliminó ninguna fila (posible error en WHERE)");
                $this->db->rollBack();
                return false;
            }

            error_log("✅ Asistencia eliminada. Filas afectadas: $filas_eliminadas");

            // 4. Actualizar horas en la práctica (RESTAR las horas eliminadas)
            $nuevas_horas = max(0, $horas_actuales_practica - $horas_a_eliminar);

            $sqlUpdateHoras = "UPDATE practicas 
                          SET horas_acumuladas = :nuevas_horas 
                          WHERE id = :practica_id";

            $stmtUpdateHoras = $this->db->prepare($sqlUpdateHoras);
            $stmtUpdateHoras->execute([
                ':nuevas_horas' => $nuevas_horas,
                ':practica_id' => $practica_id
            ]);

            error_log("✅ Horas actualizadas en práctica. Nuevo total: $nuevas_horas");

            // 5. Verificar y actualizar estado de la práctica
            $sqlGetEstado = "SELECT estado FROM practicas WHERE id = :practica_id";
            $stmtGetEstado = $this->db->prepare($sqlGetEstado);
            $stmtGetEstado->execute([':practica_id' => $practica_id]);
            $estado_actual = $stmtGetEstado->fetch(PDO::FETCH_COLUMN);

            error_log("📊 Estado actual de práctica: " . ($estado_actual ?: 'NULL'));

            // Determinar nuevo estado basado en las horas
            if ($nuevas_horas >= 128) {
                $nuevo_estado = 'Finalizado';
            } elseif ($nuevas_horas > 0) {
                $nuevo_estado = 'En curso';
            } else {
                $nuevo_estado = 'En curso'; // Nueva práctica sin horas también está en curso
            }

            // Actualizar estado solo si cambió
            if ($estado_actual !== $nuevo_estado) {
                $sqlUpdateEstado = "UPDATE practicas SET estado = :estado WHERE id = :practica_id";
                $stmtUpdateEstado = $this->db->prepare($sqlUpdateEstado);
                $stmtUpdateEstado->execute([
                    ':estado' => $nuevo_estado,
                    ':practica_id' => $practica_id
                ]);
                error_log("✅ Estado de práctica actualizado de '$estado_actual' a '$nuevo_estado'");
            } else {
                error_log("ℹ️ Estado de práctica no cambia ($estado_actual)");
            }

            // 6. Confirmar transacción
            $this->db->commit();
            error_log("✅ Transacción completada exitosamente");
            error_log("🗑️ ======= ELIMINACIÓN EXITOSA =======");

            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
                error_log("🔙 Transacción revertida por error PDO");
            }

            error_log("🔥 ERROR PDO al eliminar asistencia:");
            error_log("  - Mensaje: " . $e->getMessage());
            error_log("  - Código: " . $e->getCode());
            error_log("  - ErrorInfo: " . print_r($this->db->errorInfo(), true));

            return false;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
                error_log("🔙 Transacción revertida por error general");
            }

            error_log("🔥 ERROR GENERAL al eliminar asistencia: " . $e->getMessage());
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
