<?php
class Database {
    private static $instance = null;
    private $con;

    private function __construct() {
        // Configuración para tu base de datos local de XAMPP
        $host = 'localhost';
        $dbname = 'wxwdrnht_wxwdrnht_integrado_db';
        $username = 'root';   // Usuario por defecto de XAMPP
        $password = '';       // Password por defecto de XAMPP (vacío)
        
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        
        try {
            $this->con = new PDO($dsn, $username, $password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            // Verificar que las tablas EFSRT existan
            $this->verificarTablasEFSRT();
            
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    private function verificarTablasEFSRT() {
        // Verificar si la tabla practicas tiene los campos EFSRT
        try {
            $stmt = $this->con->query("SHOW COLUMNS FROM practicas LIKE 'tipo_efsrt'");
            $result = $stmt->fetch();
            
            if (!$result) {
                // Agregar los campos EFSRT si no existen
                $this->agregarCamposEFSRT();
            }
        } catch (Exception $e) {
            error_log("Error verificando tablas EFSRT: " . $e->getMessage());
        }
    }

    private function agregarCamposEFSRT() {
        $alterQueries = [
            "ALTER TABLE practicas ADD COLUMN tipo_efsrt ENUM('modulo1','modulo2','modulo3') NULL AFTER modulo",
            "ALTER TABLE practicas ADD COLUMN docente_supervisor INT NULL AFTER empleado",
            "ALTER TABLE practicas ADD COLUMN horas_acumuladas INT DEFAULT 0 AFTER total_horas",
            "ALTER TABLE practicas ADD COLUMN area_ejecucion VARCHAR(255) NULL AFTER horas_acumuladas",
            "ALTER TABLE practicas ADD COLUMN supervisor_empresa VARCHAR(255) NULL AFTER area_ejecucion",
            "ALTER TABLE practicas ADD COLUMN cargo_supervisor VARCHAR(150) NULL AFTER supervisor_empresa",
            "ALTER TABLE practicas ADD COLUMN periodo_academico_efsrt VARCHAR(50) NULL AFTER cargo_supervisor",
            "ALTER TABLE practicas ADD COLUMN turno_efsrt VARCHAR(20) NULL AFTER periodo_academico_efsrt"
        ];

        foreach ($alterQueries as $query) {
            try {
                $this->con->exec($query);
            } catch (PDOException $e) {
                error_log("Error ejecutando query: $query - " . $e->getMessage());
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->con;
    }

    public static function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    public function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query error: " . $e->getMessage());
            throw new Exception("Error al procesar la solicitud: " . $e->getMessage());
        }
    }
        // Método para verificar credenciales de usuario
    public function verificarUsuario($usuario, $password) {
        try {
            $sql = "SELECT u.*, 
                           CASE 
                               WHEN u.tipo = 1 THEN e.apnom_emp 
                               WHEN u.tipo = 2 THEN est.ap_est 
                           END as nombre_completo
                    FROM usuarios u
                    LEFT JOIN empleado e ON u.estuempleado = e.id AND u.tipo = 1
                    LEFT JOIN estudiante est ON u.estuempleado = est.id AND u.tipo = 2
                    WHERE u.usuario = :usuario AND u.estado = 1";
            
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
}
?>