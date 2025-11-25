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

    // Agregar estos métodos a tu EmpresaModel existente
public function obtenerTodasEmpresas($filtros = []) {
    $sql = "SELECT * FROM empresa WHERE 1=1";
    $params = [];
    
    if (!empty($filtros['busqueda'])) {
        $sql .= " AND (razon_social LIKE :busqueda OR ruc LIKE :busqueda OR nombre_comercial LIKE :busqueda)";
        $params[':busqueda'] = "%{$filtros['busqueda']}%";
    }
    
    if (!empty($filtros['sector']) && $filtros['sector'] !== 'all') {
        $sql .= " AND sector = :sector";
        $params[':sector'] = $filtros['sector'];
    }
    
    if (!empty($filtros['validado']) && $filtros['validado'] !== 'all') {
        $sql .= " AND validado = :validado";
        $params[':validado'] = $filtros['validado'];
    }
    
    if (!empty($filtros['estado']) && $filtros['estado'] !== 'all') {
        $sql .= " AND estado = :estado";
        $params[':estado'] = $filtros['estado'];
    }
    
    $sql .= " ORDER BY razon_social";
    
    $stmt = $this->executeQuery($sql, $params);
    return $stmt->fetchAll();
}

public function crearEmpresa($datos) {
    $sql = "INSERT INTO empresa (ruc, razon_social, nombre_comercial, direccion_fiscal, telefono, email, sector, validado, registro_manual, estado, condicion_sunat, ubigeo, departamento, provincia, distrito, fecha_creacion, fecha_actualizacion) 
            VALUES (:ruc, :razon_social, :nombre_comercial, :direccion_fiscal, :telefono, :email, :sector, :validado, :registro_manual, :estado, :condicion_sunat, :ubigeo, :departamento, :provincia, :distrito, NOW(), NOW())";
    
    $stmt = $this->executeQuery($sql, $datos);
    return $stmt->rowCount() > 0;
}

public function actualizarEmpresa($id, $datos) {
    $sql = "UPDATE empresa SET 
            ruc = :ruc, razon_social = :razon_social, nombre_comercial = :nombre_comercial, 
            direccion_fiscal = :direccion_fiscal, telefono = :telefono, email = :email, 
            sector = :sector, validado = :validado, registro_manual = :registro_manual, 
            estado = :estado, condicion_sunat = :condicion_sunat, ubigeo = :ubigeo, 
            departamento = :departamento, provincia = :provincia, distrito = :distrito, 
            fecha_actualizacion = NOW() 
            WHERE id = :id";
    
    $datos[':id'] = $id;
    $stmt = $this->executeQuery($sql, $datos);
    return $stmt->rowCount() > 0;
}

public function eliminarEmpresa($id) {
    $sql = "UPDATE empresa SET estado = 'INACTIVO' WHERE id = :id";
    $stmt = $this->executeQuery($sql, [':id' => $id]);
    return $stmt->rowCount() > 0;
}

// Métodos para estadísticas
public function contarEmpresasPorSector() {
    $sql = "SELECT sector, COUNT(*) as cantidad FROM empresa WHERE estado = 'ACTIVO' GROUP BY sector";
    $stmt = $this->executeQuery($sql);
    return $stmt->fetchAll();
}

public function contarEmpresasValidadas() {
    $sql = "SELECT COUNT(*) as total FROM empresa WHERE validado = 1 AND estado = 'ACTIVO'";
    $stmt = $this->executeQuery($sql);
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

public function contarEmpresasConPracticas() {
    $sql = "SELECT COUNT(DISTINCT empresa) as total FROM practicas WHERE estado = 'En curso'";
    $stmt = $this->executeQuery($sql);
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}
}
?>