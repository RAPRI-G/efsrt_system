<?php
require_once 'BaseModel.php';

class EstudianteModel extends BaseModel
{
    private $table = 'estudiante';

    public function obtenerEstudiantes($filtros = [])
    {
        // 🔥 CONSULTA MEJORADA: Obtiene la ÚLTIMA práctica activa de cada estudiante
        $sql = "SELECT 
                e.*, 
                p.nom_progest, 
                m.prog_estudios,
                m.id_matricula,
                m.per_acad,
                m.turno,
                -- Última práctica activa (si existe)
                pr.estado as estado_practica,
                pr.modulo as modulo_practica,
                pr.empresa as empresa_practica,
                pr.fecha_inicio as fecha_inicio_practica,
                -- Contador de prácticas en curso
                (SELECT COUNT(*) FROM practicas pr2 
                 WHERE pr2.estudiante = e.id 
                 AND pr2.estado = 'En curso') as total_practicas_curso,
                -- Contador total de prácticas
                (SELECT COUNT(*) FROM practicas pr3 
                 WHERE pr3.estudiante = e.id) as total_practicas
            FROM estudiante e
            LEFT JOIN matricula m ON e.id = m.estudiante
            LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
            -- 🔥 IMPORTANTE: Obtener solo la ÚLTIMA práctica (la más reciente)
            LEFT JOIN (
                SELECT estudiante, estado, modulo, empresa, fecha_inicio
                FROM practicas
                WHERE (estudiante, fecha_inicio) IN (
                    SELECT estudiante, MAX(fecha_inicio)
                    FROM practicas
                    GROUP BY estudiante
                )
            ) pr ON e.id = pr.estudiante
            WHERE 1=1";

        $params = [];

        // Aplicar filtros (mantener tu código actual)
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (e.dni_est LIKE :busqueda OR e.ap_est LIKE :busqueda OR e.nom_est LIKE :busqueda)";
            $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
        }

        if (!empty($filtros['programa']) && $filtros['programa'] != 'all') {
            $sql .= " AND m.prog_estudios = :programa";
            $params[':programa'] = $filtros['programa'];
        }

        if (!empty($filtros['estado']) && $filtros['estado'] != 'all') {
            if ($filtros['estado'] == '1') {
                $sql .= " AND e.estado = 1";
            } else if ($filtros['estado'] == '0') {
                $sql .= " AND (e.estado = 0 OR e.estado IS NULL)";
            }
        }

        if (!empty($filtros['genero']) && $filtros['genero'] != 'all') {
            $sql .= " AND e.sex_est = :genero";
            $params[':genero'] = $filtros['genero'];
        }

        $sql .= " ORDER BY e.ap_est, e.am_est, e.nom_est";

        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetchAll();
    }

    public function crearMatricula($estudianteId, $datosMatricula)
    {
        $sql = "INSERT INTO matricula (estudiante, prog_estudios, id_matricula, per_acad, turno, fec_matricula) 
                VALUES (:estudiante, :prog_estudios, :id_matricula, :per_acad, :turno, CURDATE())";

        $params = [
            ':estudiante' => $estudianteId,
            ':prog_estudios' => $datosMatricula['prog_estudios'] ?? null,
            ':id_matricula' => $datosMatricula['id_matricula'] ?? null,
            ':per_acad' => $datosMatricula['per_acad'] ?? null,
            ':turno' => $datosMatricula['turno'] ?? null
        ];

        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount() > 0;
    }

    public function actualizarMatricula($estudianteId, $datosMatricula)
    {
        // Primero verificar si existe matrícula
        $sqlCheck = "SELECT id FROM matricula WHERE estudiante = :estudiante";
        $stmtCheck = $this->executeQuery($sqlCheck, [':estudiante' => $estudianteId]);
        $matriculaExistente = $stmtCheck->fetch();

        if ($matriculaExistente) {
            // Actualizar matrícula existente
            $sql = "UPDATE matricula SET 
                    prog_estudios = :prog_estudios,
                    id_matricula = :id_matricula, 
                    per_acad = :per_acad,
                    turno = :turno
                    WHERE estudiante = :estudiante";
        } else {
            // Crear nueva matrícula
            $sql = "INSERT INTO matricula (estudiante, prog_estudios, id_matricula, per_acad, turno, fec_matricula) 
                    VALUES (:estudiante, :prog_estudios, :id_matricula, :per_acad, :turno, CURDATE())";
        }

        $params = [
            ':prog_estudios' => $datosMatricula['prog_estudios'] ?? null,
            ':id_matricula' => $datosMatricula['id_matricula'] ?? null,
            ':per_acad' => $datosMatricula['per_acad'] ?? null,
            ':turno' => $datosMatricula['turno'] ?? null,
            ':estudiante' => $estudianteId
        ];

        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount() > 0;
    }


    // 🔥 CAMBIAR DE private A public
    public function verificarDniExistente($dni, $excluirId = null)
    {
        try {
            error_log("🔍 VERIFICANDO DNI: {$dni}, Excluir ID: " . ($excluirId ?? 'Ninguno'));

            // 🔥 CONSULTA DIRECTA CON PDO - SIN executeQuery
            $sql = "SELECT COUNT(*) as count FROM estudiante WHERE dni_est = :dni AND (estado = 1 OR estado IS NULL)";
            $params = [':dni' => $dni];

            if ($excluirId !== null && $excluirId !== '') {
                $sql .= " AND id != :excluir_id";
                $params[':excluir_id'] = $excluirId;
            }

            // Ejecutar directamente con PDO
            $stmt = $this->db->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch();

            $count = $result['count'] ?? 0;
            error_log("🔍 RESULTADO: {$count} estudiantes encontrados con DNI: {$dni}");

            return $count > 0;
        } catch (Exception $e) {
            error_log("💥 ERROR en verificarDniExistente: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerUbigeos()
    {
        // Método para obtener datos de ubigeo si los necesitas
        return [
            'departamentos' => [],
            'provincias' => [],
            'distritos' => []
        ];
    }

    public function crearEstudiante($datos)
    {
        try {
            // Validar que el DNI no exista
            $dniExistente = $this->verificarDniExistente($datos['dni_est']);
            if ($dniExistente) {
                throw new Exception('El DNI ya está registrado en el sistema');
            }

            // 🔥 CORRECCIÓN: Incluir campos de ubigeo
            $sql = "INSERT INTO estudiante (
            dni_est, ap_est, am_est, nom_est, sex_est, 
            cel_est, dir_est, mailp_est, fecnac_est, estado,
            ubigeodir_est, ubigeonac_est
        ) VALUES (
            :dni_est, :ap_est, :am_est, :nom_est, :sex_est,
            :cel_est, :dir_est, :mailp_est, :fecnac_est, :estado,
            :ubigeodir_est, :ubigeonac_est
        )";

            $params = [
                ':dni_est' => $datos['dni_est'],
                ':ap_est' => $datos['ap_est'],
                ':am_est' => $datos['am_est'] ?? null,
                ':nom_est' => $datos['nom_est'],
                ':sex_est' => $datos['sex_est'],
                ':cel_est' => $datos['cel_est'] ?? null,
                ':dir_est' => $datos['dir_est'] ?? null,
                ':mailp_est' => $datos['mailp_est'] ?? null,
                ':fecnac_est' => $datos['fecnac_est'] ?? null,
                ':estado' => $datos['estado'] ?? 1,
                ':ubigeodir_est' => $datos['ubigeodir_est'] ?? null, // 🔥 NUEVO
                ':ubigeonac_est' => $datos['ubigeonac_est'] ?? null  // 🔥 NUEVO
            ];

            // 🔥 DEBUG: Ver parámetros
            error_log("Parámetros para insertar estudiante:");
            foreach ($params as $key => $value) {
                error_log("$key: " . ($value ?? 'NULL'));
            }

            $stmt = $this->executeQuery($sql, $params);
            $estudianteId = $this->db->lastInsertId();

            return $estudianteId;
        } catch (Exception $e) {
            error_log("Error al crear estudiante: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizarEstudiante($id, $datos)
    {
        try {
            // Validar que el DNI no exista en otros estudiantes
            if (isset($datos['dni_est'])) {
                $dniExistente = $this->verificarDniExistente($datos['dni_est'], $id);
                if ($dniExistente) {
                    throw new Exception('El DNI ya está registrado en otro estudiante');
                }
            }

            // 🔥 CORRECCIÓN: Incluir campos de ubigeo
            $sql = "UPDATE estudiante SET 
                dni_est = :dni_est,
                ap_est = :ap_est,
                am_est = :am_est,
                nom_est = :nom_est,
                sex_est = :sex_est,
                cel_est = :cel_est,
                dir_est = :dir_est,
                mailp_est = :mailp_est,
                fecnac_est = :fecnac_est,
                estado = :estado,
                ubigeodir_est = :ubigeodir_est,
                ubigeonac_est = :ubigeonac_est
            WHERE id = :id";

            $params = [
                ':dni_est' => $datos['dni_est'],
                ':ap_est' => $datos['ap_est'],
                ':am_est' => $datos['am_est'] ?? null,
                ':nom_est' => $datos['nom_est'],
                ':sex_est' => $datos['sex_est'],
                ':cel_est' => $datos['cel_est'] ?? null,
                ':dir_est' => $datos['dir_est'] ?? null,
                ':mailp_est' => $datos['mailp_est'] ?? null,
                ':fecnac_est' => $datos['fecnac_est'] ?? null,
                ':estado' => $datos['estado'] ?? 1,
                ':ubigeodir_est' => $datos['ubigeodir_est'] ?? null, // 🔥 NUEVO
                ':ubigeonac_est' => $datos['ubigeonac_est'] ?? null, // 🔥 NUEVO
                ':id' => $id
            ];

            $stmt = $this->executeQuery($sql, $params);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error al actualizar estudiante: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminarEstudiante($id)
    {
        try {
            // 🔥 CORRECCIÓN: Verificar que el estudiante existe antes de eliminar
            $sqlCheck = "SELECT id FROM estudiante WHERE id = :id";
            $stmtCheck = $this->executeQuery($sqlCheck, [':id' => $id]);
            $estudiante = $stmtCheck->fetch();

            if (!$estudiante) {
                error_log("❌ Estudiante con ID {$id} no encontrado para eliminar");
                return false;
            }

            error_log("🔍 Estudiante encontrado - ID: {$id}, procediendo a eliminación física");

            // 🔥 PRIMERO: Eliminar registros relacionados en otras tablas (si existen)

            // 1. Eliminar matrículas del estudiante
            try {
                $sqlMatricula = "DELETE FROM matricula WHERE estudiante = :id";
                $stmtMatricula = $this->executeQuery($sqlMatricula, [':id' => $id]);
                error_log("📚 Matrículas eliminadas: " . $stmtMatricula->rowCount());
            } catch (Exception $e) {
                error_log("⚠️ Error eliminando matrículas: " . $e->getMessage());
                // Continuar aunque falle esta parte
            }

            // 2. Eliminar prácticas del estudiante  
            try {
                $sqlPracticas = "DELETE FROM practicas WHERE estudiante = :id";
                $stmtPracticas = $this->executeQuery($sqlPracticas, [':id' => $id]);
                error_log("💼 Prácticas eliminadas: " . $stmtPracticas->rowCount());
            } catch (Exception $e) {
                error_log("⚠️ Error eliminando prácticas: " . $e->getMessage());
                // Continuar aunque falle esta parte
            }

            // 🔥 FINALMENTE: Eliminar el estudiante
            $sql = "DELETE FROM estudiante WHERE id = :id";
            $stmt = $this->executeQuery($sql, [':id' => $id]);
            $rowCount = $stmt->rowCount();

            error_log("📊 Eliminación física - Filas afectadas: " . $rowCount);

            if ($rowCount > 0) {
                error_log("🎉 Estudiante ID {$id} eliminado físicamente de la base de datos");
                return true;
            } else {
                error_log("❌ No se pudo eliminar el estudiante ID {$id}");
                return false;
            }
        } catch (Exception $e) {
            error_log("💥 Error en eliminarEstudiante ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstudianteCompleto($id)
    {
        // 🔥 CONSULTA MEJORADA para obtener TODOS los datos necesarios
        $sql = "SELECT 
                e.*, 
                m.prog_estudios,
                m.id_matricula,
                m.per_acad,
                m.turno,
                p.nom_progest
            FROM estudiante e
            LEFT JOIN matricula m ON e.id = m.estudiante
            LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
            WHERE e.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();

        // 🔥 DEBUG: Ver qué datos se obtienen
        error_log("📊 Datos obtenidos para estudiante ID {$id}: " . print_r($result, true));

        return $result;
    }

    public function obtenerEstadisticasEstudiantes()
    {
        $sql = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as activos,
            SUM(CASE WHEN estado = 0 OR estado IS NULL THEN 1 ELSE 0 END) as inactivos,
            SUM(CASE WHEN sex_est = 'M' THEN 1 ELSE 0 END) as masculinos,
            SUM(CASE WHEN sex_est = 'F' THEN 1 ELSE 0 END) as femeninos,
            (SELECT COUNT(DISTINCT estudiante) FROM practicas WHERE estado = 'En curso') as en_practicas
            FROM estudiante";

        $stmt = $this->executeQuery($sql);
        return $stmt->fetch();
    }

    public function obtenerProgramas()
    {
        $sql = "SELECT id, nom_progest FROM prog_estudios ORDER BY nom_progest";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    public function contarEstudiantesActivos()
    {
        $sql = "SELECT COUNT(*) as total FROM estudiante WHERE estado = 1"; // estado IS NULL OR estado = 1 -> contar todos los activos y nulos
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // Métodos adicionales que ya tenías
    public function obtenerEstudiantePorId($id)
    {
        $sql = "SELECT e.*, m.per_acad, m.turno, p.nom_progest 
                FROM estudiante e
                LEFT JOIN matricula m ON e.id = m.estudiante
                LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
                WHERE e.id = :id";

        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }

    public function buscarEstudiantes($termino)
    {
        $sql = "SELECT id, dni_est, ap_est, am_est, nom_est, cel_est, mailp_est
                FROM estudiante 
                WHERE CONCAT(dni_est, ' ', ap_est, ' ', am_est, ' ', nom_est) LIKE :termino
                AND (estado IS NULL OR estado = 1)
                ORDER BY ap_est, am_est, nom_est
                LIMIT 10";

        $terminoBusqueda = '%' . $this->sanitize($termino) . '%';
        $stmt = $this->executeQuery($sql, [':termino' => $terminoBusqueda]);
        return $stmt->fetchAll();
    }

    // 🔥 AGREGA ESTE MÉTODO SI NO EXISTE
    protected function executeQuery($sql, $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);

            foreach ($params as $key => $value) {
                if ($value === null) {
                    $stmt->bindValue($key, $value, PDO::PARAM_NULL);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }

            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            error_log("💥 Error en executeQuery: " . $e->getMessage());
            error_log("💥 SQL: " . $sql);
            error_log("💥 Parámetros: " . print_r($params, true));
            throw $e;
        }
    }
}
