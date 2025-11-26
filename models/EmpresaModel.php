<?php
require_once 'BaseModel.php';

class EmpresaModel extends BaseModel
{
    private $table = 'empresa';

    // Método existente - MANTENER
    public function obtenerEmpresas()
    {
        $sql = "SELECT * FROM empresa WHERE estado = 'ACTIVO' ORDER BY razon_social";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    // NUEVO: Método para obtener empresas con filtros y paginación - CORREGIDO
    public function obtenerEmpresasConFiltros($filtros = [], $limit = null, $offset = 0)
    {
        // ✅ AGREGAR fecha_creacion al SELECT
        $sql = "SELECT *, 
                   COALESCE(fecha_creacion, fecha_registro, NOW()) as fecha_creacion 
            FROM empresa WHERE 1=1";
        $params = [];

        $sql = "SELECT * FROM empresa WHERE 1=1";
        $params = [];

        // ✅ CORREGIDO: Usar parámetros separados para cada condición LIKE
        if (!empty($filtros['busqueda'])) {
            $searchTerm = "%{$filtros['busqueda']}%";
            $sql .= " AND (razon_social LIKE :busqueda_razon OR ruc LIKE :busqueda_ruc OR representante_legal LIKE :busqueda_representante)";
            $params[':busqueda_razon'] = $searchTerm;
            $params[':busqueda_ruc'] = $searchTerm;
            $params[':busqueda_representante'] = $searchTerm;
        }

        if (!empty($filtros['departamento']) && $filtros['departamento'] !== 'all') {
            $sql .= " AND departamento = :departamento";
            $params[':departamento'] = $filtros['departamento'];
        }

        if (!empty($filtros['estado']) && $filtros['estado'] !== 'all') {
            $sql .= " AND estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        $sql .= " ORDER BY razon_social";

        // ✅ Aplicar paginación de forma segura
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        try {
            error_log("🔍 EJECUTANDO CONSULTA CON FILTROS:");
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));

            $stmt = $this->executeQuery($sql, $params);
            $resultados = $stmt->fetchAll();

            error_log("🔍 RESULTADOS OBTENIDOS: " . count($resultados));

            return $resultados;
        } catch (Exception $e) {
            error_log("❌ ERROR en obtenerEmpresasConFiltros: " . $e->getMessage());
            throw $e;
        }
    }

    // NUEVO: Contar total de empresas con filtros - CORREGIDO
    public function contarEmpresasConFiltros($filtros = [])
    {
        $sql = "SELECT COUNT(*) as total FROM empresa WHERE 1=1";
        $params = [];

        // ✅ CORREGIDO: Misma corrección aquí
        if (!empty($filtros['busqueda'])) {
            $searchTerm = "%{$filtros['busqueda']}%";
            $sql .= " AND (razon_social LIKE :busqueda_razon OR ruc LIKE :busqueda_ruc OR representante_legal LIKE :busqueda_representante)";
            $params[':busqueda_razon'] = $searchTerm;
            $params[':busqueda_ruc'] = $searchTerm;
            $params[':busqueda_representante'] = $searchTerm;
        }

        if (!empty($filtros['departamento']) && $filtros['departamento'] !== 'all') {
            $sql .= " AND departamento = :departamento";
            $params[':departamento'] = $filtros['departamento'];
        }

        if (!empty($filtros['estado']) && $filtros['estado'] !== 'all') {
            $sql .= " AND estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        try {
            error_log("🔍 CONTANDO EMPRESAS CON FILTROS:");
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));

            $stmt = $this->executeQuery($sql, $params);
            $result = $stmt->fetch();

            error_log("🔍 TOTAL ENCONTRADO: " . ($result['total'] ?? 0));

            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("❌ ERROR en contarEmpresasConFiltros: " . $e->getMessage());
            return 0;
        }
    }

    // Métodos existentes (los mantienes)
    public function obtenerEmpresaPorId($id)
    {
        $sql = "SELECT e.*, 
                   d.id as departamento_id,
                   p.id as provincia_id, 
                   di.id as distrito_id
            FROM empresa e
            LEFT JOIN ubdepartamento d ON e.departamento = d.departamento
            LEFT JOIN ubprovincia p ON e.provincia = p.provincia AND p.ubdepartamento = d.id
            LEFT JOIN ubdistrito di ON e.distrito = di.distrito AND di.ubprovincia = p.id
            WHERE e.id = :id";

        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }

    public function crearEmpresa($datos)
    {
        $sql = "INSERT INTO empresa (ruc, razon_social, representante_legal, direccion_fiscal, telefono, email, departamento, provincia, distrito, estado) 
                VALUES (:ruc, :razon_social, :representante_legal, :direccion_fiscal, :telefono, :email, :departamento, :provincia, :distrito, :estado)";

        $stmt = $this->executeQuery($sql, $datos);
        return $stmt->rowCount() > 0;
    }

    public function actualizarEmpresa($id, $datos)
    {
        $sql = "UPDATE empresa SET 
                ruc = :ruc, 
                razon_social = :razon_social, 
                representante_legal = :representante_legal, 
                direccion_fiscal = :direccion_fiscal, 
                telefono = :telefono, 
                email = :email, 
                departamento = :departamento, 
                provincia = :provincia, 
                distrito = :distrito, 
                estado = :estado 
                WHERE id = :id";

        $datos[':id'] = $id;
        $stmt = $this->executeQuery($sql, $datos);
        return $stmt->rowCount() > 0;
    }

   public function eliminarEmpresa($id) {
    try {
        // ✅ VERIFICAR SI HAY PRÁCTICAS RELACIONADAS
        $sqlCheck = "SELECT COUNT(*) as total FROM practicas WHERE empresa = :id";
        $stmtCheck = $this->executeQuery($sqlCheck, [':id' => $id]);
        $result = $stmtCheck->fetch();
        
        if ($result['total'] > 0) {
            throw new Exception("No se puede eliminar la empresa porque tiene prácticas asociadas.");
        }
        
        // ✅ ELIMINACIÓN DIRECTA
        $sql = "DELETE FROM empresa WHERE id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        
        return $stmt->rowCount() > 0;
        
    } catch (Exception $e) {
        error_log("❌ Error eliminando empresa ID {$id}: " . $e->getMessage());
        throw $e;
    }
}

    // 🔥 MÉTODOS PARA ESTADÍSTICAS - CORREGIDOS PARA INCLUIR INACTIVAS
    public function contarEmpresasActivas()
    {
        $sql = "SELECT COUNT(*) as total FROM empresa WHERE estado = 'ACTIVO'";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // 🔍 MÉTODO PARA CONTAR TOTAL DE EMPRESAS
    public function contarTotalEmpresas()
    {
        $sql = "SELECT COUNT(*) as total FROM empresa";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function contarEmpresasPorSector()
    {
        // Como no tienes campo sector, usamos departamento
        $sql = "SELECT departamento as sector, COUNT(*) as cantidad FROM empresa GROUP BY departamento";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    public function contarEmpresasValidadas()
    {
        // Como no tienes campo validado, contamos todas las activas
        $sql = "SELECT COUNT(*) as total FROM empresa WHERE estado = 'ACTIVO'";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // 🔍 MÉTODO PARA CONTAR EMPRESAS CON PRÁCTICAS
    public function contarEmpresasConPracticas()
    {
        $sql = "SELECT COUNT(DISTINCT empresa) as total FROM practicas WHERE estado = 'En curso'";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // 🔍 MÉTODO PARA CONTAR EMPRESAS POR ESTADO
    public function contarEmpresasPorEstado()
    {
        $sql = "SELECT estado, COUNT(*) as cantidad FROM empresa GROUP BY estado";
        $stmt = $this->executeQuery($sql);
        $resultados = $stmt->fetchAll();

        // ✅ DEBUG: Log de resultados
        error_log("📊 DISTRIBUCIÓN POR ESTADO:");
        foreach ($resultados as $estado) {
            error_log(" - " . $estado['estado'] . ": " . $estado['cantidad']);
        }

        return $resultados;
    }
}
