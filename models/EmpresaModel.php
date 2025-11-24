<?php
require_once 'BaseModel.php';

class EmpresaModel extends BaseModel {
    private $table = 'empresa';
    
    public function obtenerEmpresas() {
        $sql = "SELECT * FROM empresa WHERE estado = 'ACTIVO' ORDER BY razon_social";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
    
    // NUEVO MÉTODO PARA EL DASHBOARD
    public function contarEmpresasActivas() {
        $sql = "SELECT COUNT(*) as total FROM empresa WHERE estado = 'ACTIVO'";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    public function obtenerEmpresaPorId($id) {
        $sql = "SELECT * FROM empresa WHERE id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }
}
?>