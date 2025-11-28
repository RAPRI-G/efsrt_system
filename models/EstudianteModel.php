<?php
require_once 'BaseModel.php';

class EstudianteModel extends BaseModel
{
    private $table = 'estudiante';

    public function obtenerEstudiantes($filtros = [])
    {
        // 🔥 CORRECCIÓN: Mostrar solo estudiantes activos (estado = 1 o null)
    $sql = "SELECT e.*, 
                   p.nom_progest, 
                   m.prog_estudios,
                   m.id_matricula,
                   m.per_acad,
                   m.turno,
                   (SELECT COUNT(*) FROM practicas pr WHERE pr.estudiante = e.id AND pr.estado = 'En curso') as en_practicas
            FROM estudiante e
            LEFT JOIN matricula m ON e.id = m.estudiante
            LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
            WHERE (e.estado IS NULL OR e.estado = 1)"; // 🔥 Solo activos

    $params = [];

        // Aplicar filtros
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (e.dni_est LIKE :busqueda OR e.ap_est LIKE :busqueda OR e.nom_est LIKE :busqueda)";
            $params[':busqueda'] = '%' . $filtros['busqueda'] . '%';
        }

        if (!empty($filtros['programa']) && $filtros['programa'] != 'all') {
            $sql .= " AND m.prog_estudios = :programa";
            $params[':programa'] = $filtros['programa'];
        }

        if (!empty($filtros['estado']) && $filtros['estado'] != 'all') {
            $sql .= " AND e.estado = :estado";
            $params[':estado'] = $filtros['estado'];
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
        $sql = "SELECT COUNT(*) as count FROM estudiante WHERE dni_est = :dni AND (estado IS NULL OR estado = 1)";
        $params = [':dni' => $dni];

        if ($excluirId) {
            $sql .= " AND id != :excluir_id";
            $params[':excluir_id'] = $excluirId;
        }

        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->fetch();
        return $result['count'] > 0;
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
        $sql = "UPDATE estudiante SET estado = 0 WHERE id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function obtenerEstudianteCompleto($id)
    {
        $sql = "SELECT e.*, p.nom_progest, m.prog_estudios, 
                       pr.estado as estado_practica, pr.modulo,
                       emp.razon_social as empresa_practica
                FROM estudiante e
                LEFT JOIN matricula m ON e.id = m.estudiante
                LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
                LEFT JOIN practicas pr ON e.id = pr.estudiante AND pr.estado = 'En curso'
                LEFT JOIN empresa emp ON pr.empresa = emp.id
                WHERE e.id = :id";

        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }

    public function obtenerEstadisticasEstudiantes()
    {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as activos,
                SUM(CASE WHEN sex_est = 'M' THEN 1 ELSE 0 END) as masculinos,
                SUM(CASE WHEN sex_est = 'F' THEN 1 ELSE 0 END) as femeninos,
                (SELECT COUNT(DISTINCT estudiante) FROM practicas WHERE estado = 'En curso') as en_practicas
                FROM estudiante 
                WHERE estado IS NULL OR estado = 1";

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
}
