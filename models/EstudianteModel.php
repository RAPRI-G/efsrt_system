<?php
require_once 'BaseModel.php';

class EstudianteModel extends BaseModel {
    private $table = 'estudiante';
    
     public function obtenerEstudiantes($filtros = []) {
        $sql = "SELECT e.*, p.nom_progest, m.prog_estudios,
                       (SELECT COUNT(*) FROM practicas pr WHERE pr.estudiante = e.id AND pr.estado = 'En curso') as en_practicas
                FROM estudiante e
                LEFT JOIN matricula m ON e.id = m.estudiante
                LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
                WHERE (e.estado IS NULL OR e.estado = 1)";
        
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

    private function verificarDniExistente($dni, $excluirId = null) {
        $sql = "SELECT COUNT(*) as count FROM estudiante WHERE dni_est = :dni";
        $params = [':dni' => $dni];
        
        if ($excluirId) {
            $sql .= " AND id != :excluir_id";
            $params[':excluir_id'] = $excluirId;
        }
        
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function obtenerUbigeos() {
        // Método para obtener datos de ubigeo si los necesitas
        return [
            'departamentos' => [],
            'provincias' => [],
            'distritos' => []
        ];
    }

    public function crearEstudiante($datos) {
        try {
            // Validar que el DNI no exista
            $dniExistente = $this->verificarDniExistente($datos['dni_est']);
            if ($dniExistente) {
                throw new Exception('El DNI ya está registrado en el sistema');
            }
            
            // Insertar estudiante
            $sql = "INSERT INTO estudiante (
                ubdistrito, dni_est, ap_est, am_est, nom_est, sex_est, 
                cel_est, ubigeodir_est, ubigeonac_est, dir_est, 
                mailp_est, maili_est, fecnac_est, estado
            ) VALUES (
                :ubdistrito, :dni_est, :ap_est, :am_est, :nom_est, :sex_est,
                :cel_est, :ubigeodir_est, :ubigeonac_est, :dir_est,
                :mailp_est, :maili_est, :fecnac_est, :estado
            )";
            
            $params = [
                ':ubdistrito' => $datos['ubdistrito'] ?? null,
                ':dni_est' => $datos['dni_est'],
                ':ap_est' => $datos['ap_est'],
                ':am_est' => $datos['am_est'] ?? null,
                ':nom_est' => $datos['nom_est'],
                ':sex_est' => $datos['sex_est'],
                ':cel_est' => $datos['cel_est'] ?? null,
                ':ubigeodir_est' => $datos['ubigeodir_est'] ?? null,
                ':ubigeonac_est' => $datos['ubigeonac_est'] ?? null,
                ':dir_est' => $datos['dir_est'] ?? null,
                ':mailp_est' => $datos['mailp_est'] ?? null,
                ':maili_est' => $datos['maili_est'] ?? null,
                ':fecnac_est' => $datos['fecnac_est'] ?? null,
                ':estado' => $datos['estado'] ?? 1
            ];
            
            $stmt = $this->executeQuery($sql, $params);
            return $this->db->getConnection()->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error al crear estudiante: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function actualizarEstudiante($id, $datos) {
        try {
            // Validar que el DNI no exista en otros estudiantes
            if (isset($datos['dni_est'])) {
                $dniExistente = $this->verificarDniExistente($datos['dni_est'], $id);
                if ($dniExistente) {
                    throw new Exception('El DNI ya está registrado en otro estudiante');
                }
            }
            
            $sql = "UPDATE estudiante SET 
                    ubdistrito = :ubdistrito,
                    dni_est = :dni_est,
                    ap_est = :ap_est,
                    am_est = :am_est,
                    nom_est = :nom_est,
                    sex_est = :sex_est,
                    cel_est = :cel_est,
                    ubigeodir_est = :ubigeodir_est,
                    ubigeonac_est = :ubigeonac_est,
                    dir_est = :dir_est,
                    mailp_est = :mailp_est,
                    maili_est = :maili_est,
                    fecnac_est = :fecnac_est,
                    estado = :estado
                WHERE id = :id";
            
            $params = [
                ':ubdistrito' => $datos['ubdistrito'] ?? null,
                ':dni_est' => $datos['dni_est'],
                ':ap_est' => $datos['ap_est'],
                ':am_est' => $datos['am_est'] ?? null,
                ':nom_est' => $datos['nom_est'],
                ':sex_est' => $datos['sex_est'],
                ':cel_est' => $datos['cel_est'] ?? null,
                ':ubigeodir_est' => $datos['ubigeodir_est'] ?? null,
                ':ubigeonac_est' => $datos['ubigeonac_est'] ?? null,
                ':dir_est' => $datos['dir_est'] ?? null,
                ':mailp_est' => $datos['mailp_est'] ?? null,
                ':maili_est' => $datos['maili_est'] ?? null,
                ':fecnac_est' => $datos['fecnac_est'] ?? null,
                ':estado' => $datos['estado'] ?? 1,
                ':id' => $id
            ];
            
            $stmt = $this->executeQuery($sql, $params);
            return $stmt->rowCount() > 0;
            
        } catch (Exception $e) {
            error_log("Error al actualizar estudiante: " . $e->getMessage());
            throw $e;
        }
    }
    
      public function eliminarEstudiante($id) {
        $sql = "UPDATE estudiante SET estado = 0 WHERE id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->rowCount() > 0;
    }
    
     public function obtenerEstudianteCompleto($id) {
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

     public function obtenerEstadisticasEstudiantes() {
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
    
    public function obtenerProgramas() {
        $sql = "SELECT id, nom_progest FROM prog_estudios ORDER BY nom_progest";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
    
    public function contarEstudiantesActivos() {
        $sql = "SELECT COUNT(*) as total FROM estudiante WHERE estado = 1"; // estado IS NULL OR estado = 1 -> contar todos los activos y nulos
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

     // Métodos adicionales que ya tenías
    public function obtenerEstudiantePorId($id) {
        $sql = "SELECT e.*, m.per_acad, m.turno, p.nom_progest 
                FROM estudiante e
                LEFT JOIN matricula m ON e.id = m.estudiante
                LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
                WHERE e.id = :id";
        
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }
    
    public function buscarEstudiantes($termino) {
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
    
    // Método para obtener estudiantes para datos de prueba
    public function obtenerEstudiantesParaPrueba() {
        $sql = "SELECT id, dni_est, ap_est, nom_est FROM estudiante WHERE estado IS NULL OR estado = 1 LIMIT 3";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
}
?>