<?php
require_once 'BaseModel.php';

class PracticaModel extends BaseModel {
    private $table = 'practicas';
    
    public function obtenerPracticas() {
        // Consulta más simple para probar primero
        $sql = "SELECT p.*, 
                       e.dni_est, e.ap_est, e.am_est, e.nom_est,
                       emp.razon_social
                FROM practicas p
                INNER JOIN estudiante e ON p.estudiante = e.id
                INNER JOIN empresa emp ON p.empresa = emp.id
                ORDER BY p.fecha_inicio DESC";
        
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
    
    public function registrarPractica($datos) {
        $sql = "INSERT INTO practicas 
                (estudiante, empresa, tipo_efsrt, docente_supervisor, periodo_academico_efsrt, 
                 turno_efsrt, fecha_inicio, fecha_fin, area_ejecucion, estado) 
                VALUES (:estudiante, :empresa, :tipo_efsrt, :docente_supervisor, :periodo_academico, 
                        :turno, :fecha_inicio, :fecha_fin, :area_ejecucion, 'Pendiente')";
        
        $params = [
            ':estudiante' => $this->sanitize($datos['estudiante']),
            ':empresa' => $this->sanitize($datos['empresa']),
            ':tipo_efsrt' => $this->sanitize($datos['tipo_efsrt']),
            ':docente_supervisor' => $this->sanitize($datos['docente_supervisor']),
            ':periodo_academico' => $this->sanitize($datos['periodo_academico']),
            ':turno' => $this->sanitize($datos['turno']),
            ':fecha_inicio' => $this->sanitize($datos['fecha_inicio']),
            ':fecha_fin' => $this->sanitize($datos['fecha_fin']),
            ':area_ejecucion' => $this->sanitize($datos['area_ejecucion'])
        ];
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount() > 0;
    }
    
    public function obtenerPracticaPorId($id) {
        $sql = "SELECT p.*, 
                       e.dni_est, e.ap_est, e.am_est, e.nom_est, e.cel_est, e.mailp_est, e.dir_est,
                       emp.razon_social, emp.direccion_fiscal, emp.telefono as emp_telefono, emp.email as emp_email
                FROM practicas p
                INNER JOIN estudiante e ON p.estudiante = e.id
                INNER JOIN empresa emp ON p.empresa = emp.id
                WHERE p.id = :id";
        
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }
    
    // Método para verificar si hay datos de prueba
    public function hayDatosPrueba() {
        $sql = "SELECT COUNT(*) as count FROM practicas WHERE tipo_efsrt IS NOT NULL";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
?>