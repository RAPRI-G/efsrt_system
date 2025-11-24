<?php
require_once 'BaseModel.php';

class EstudianteModel extends BaseModel {
    private $table = 'estudiante';
    
    public function obtenerEstudiantes() {
        $sql = "SELECT e.*, m.per_acad, m.turno, p.nom_progest 
                FROM estudiante e
                LEFT JOIN matricula m ON e.id = m.estudiante
                LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
                WHERE e.estado IS NULL OR e.estado = 1";
        
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
    
    public function contarEstudiantesActivos() {
        $sql = "SELECT COUNT(*) as total FROM estudiante WHERE estado = 1"; // estado IS NULL OR estado = 1 -> contar todos los activos y nulos
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
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