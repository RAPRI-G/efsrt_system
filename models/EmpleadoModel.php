<?php
require_once 'BaseModel.php';

class EmpleadoModel extends BaseModel {
    private $table = 'empleado';
    
    public function obtenerDocentes() {
        $sql = "SELECT * FROM empleado WHERE cargo_emp = 'D' AND estado = 1";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
    
    public function obtenerEmpleadoPorId($id) {
        $sql = "SELECT * FROM empleado WHERE id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }
}
?>