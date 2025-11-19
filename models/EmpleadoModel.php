<?php
require_once 'BaseModel.php';

class EmpleadoModel extends BaseModel {
    private $table = 'empleado';
    
    public function obtenerDocentes() {
        $sql = "SELECT * FROM empleado WHERE (estado IS NULL OR estado = 1) AND cargo_emp = 'D' ORDER BY apnom_emp";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
    
    // NUEVO MÉTODO PARA EL DASHBOARD
    public function contarDocentesActivos() {
        $sql = "SELECT COUNT(*) as total FROM empleado WHERE (estado IS NULL OR estado = 1) AND cargo_emp = 'D'";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    public function obtenerEmpleadoPorId($id) {
        $sql = "SELECT * FROM empleado WHERE id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }
    
    // Método para obtener docentes para datos de prueba
    public function obtenerDocentesParaPrueba() {
        $sql = "SELECT id, apnom_emp FROM empleado WHERE (estado IS NULL OR estado = 1) AND cargo_emp = 'D' LIMIT 2";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
}
?>