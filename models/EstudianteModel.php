<?php
require_once 'BaseModel.php';

class EstudianteModel extends BaseModel {
    private $table = 'estudiante';
    
    public function obtenerEstudiantes() {
        $sql = "SELECT e.*, m.per_acad, m.turno, p.nom_progest 
                FROM estudiante e
                LEFT JOIN matricula m ON e.id = m.estudiante
                LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
                WHERE e.estado = 1";
        
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
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
        $sql = "SELECT e.*, p.nom_progest 
                FROM estudiante e
                LEFT JOIN matricula m ON e.id = m.estudiante
                LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
                WHERE e.dni_est LIKE :termino 
                   OR CONCAT(e.ap_est, ' ', e.am_est, ' ', e.nom_est) LIKE :termino
                LIMIT 10";
        
        $stmt = $this->executeQuery($sql, [':termino' => "%$termino%"]);
        return $stmt->fetchAll();
    }
}
?>