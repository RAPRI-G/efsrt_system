<?php
// models/BaseModel.php
require_once __DIR__ . '/../config/database.php';

class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function executeQuery($sql, $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);

            // ✅ DEBUG: Log de la consulta
            error_log("SQL Executed: " . $sql);
            if (!empty($params)) {
                error_log("SQL Params: " . print_r($params, true));
            }

            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query error: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            throw new Exception("Error al procesar la solicitud: " . $e->getMessage());
        }
    }

    protected function sanitize($data)
    {
        return Database::sanitizeInput($data);
    }

    protected function sanitizeArray($data)
    {
        return array_map([$this, 'sanitize'], $data);
    }
}
