<?php
require_once 'BaseModel.php';

class UbigeoModel extends BaseModel {
    
    // Obtener todos los departamentos
    public function obtenerDepartamentos() {
        $sql = "SELECT id, departamento FROM ubdepartamento ORDER BY departamento";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener provincias por departamento
    public function obtenerProvinciasPorDepartamento($departamentoId) {
        $sql = "SELECT id, provincia FROM ubprovincia WHERE ubdepartamento = :departamento_id ORDER BY provincia";
        $stmt = $this->executeQuery($sql, [':departamento_id' => $departamentoId]);
        return $stmt->fetchAll();
    }
    
    // Obtener distritos por provincia
    public function obtenerDistritosPorProvincia($provinciaId) {
        $sql = "SELECT id, distrito FROM ubdistrito WHERE ubprovincia = :provincia_id ORDER BY distrito";
        $stmt = $this->executeQuery($sql, [':provincia_id' => $provinciaId]);
        return $stmt->fetchAll();
    }
    
    // Obtener ubicación completa por IDs
    public function obtenerUbicacionCompleta($departamentoId, $provinciaId, $distritoId) {
        $sql = "SELECT 
                    d.departamento,
                    p.provincia,
                    di.distrito
                FROM ubdepartamento d
                JOIN ubprovincia p ON d.id = p.ubdepartamento
                JOIN ubdistrito di ON p.id = di.ubprovincia
                WHERE d.id = :departamento_id 
                AND p.id = :provincia_id 
                AND di.id = :distrito_id";
        
        $stmt = $this->executeQuery($sql, [
            ':departamento_id' => $departamentoId,
            ':provincia_id' => $provinciaId,
            ':distrito_id' => $distritoId
        ]);
        
        return $stmt->fetch();
    }
}
?>