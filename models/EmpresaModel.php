<?php
require_once 'BaseModel.php';

class EmpresaModel extends BaseModel {
    private $table = 'empresa';
    
    public function obtenerEmpresas() {
        $sql = "SELECT * FROM empresa WHERE estado = 'ACTIVO' ORDER BY razon_social";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
    
    public function obtenerEmpresaPorId($id) {
        $sql = "SELECT * FROM empresa WHERE id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }
    
    public function buscarEmpresas($termino) {
        $sql = "SELECT * FROM empresa 
                WHERE razon_social LIKE :termino 
                   OR nombre_comercial LIKE :termino
                   OR ruc LIKE :termino
                LIMIT 10";
        
        $stmt = $this->executeQuery($sql, [':termino' => "%$termino%"]);
        return $stmt->fetchAll();
    }
}
?>