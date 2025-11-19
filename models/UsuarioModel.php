<?php
require_once 'BaseModel.php';

class UsuarioModel extends BaseModel {
    private $table = 'usuarios';
    
    public function verificarLogin($usuario, $password, $rolSolicitado = null) {
        // 🔥 MAPEO CORREGIDO - Según tu base de datos
        $rolToTipo = [
            'administrador' => 2,  // ← 2 es administrador
            'docente' => 1, 
            'estudiante' => 3      // ← Ajusta según tu realidad
        ];
        
        // 🔥 CORREGIR: Inicializar correctamente el filtro
        $params = [':usuario' => $this->sanitize($usuario)];
        $tipoFiltro = '';
        
        if ($rolSolicitado && isset($rolToTipo[$rolSolicitado])) {
            $tipoFiltro = " AND u.tipo = :tipo";
            $params[':tipo'] = $rolToTipo[$rolSolicitado];
        }
        
        // CONSULTA SIMPLIFICADA - SOLO JALAMOS DE EMPLEADO
        $sql = "SELECT u.*, 
                       e.apnom_emp as nombre_completo,
                       e.dni_emp as dni,
                       CASE 
                           WHEN u.tipo = 1 THEN 'docente'
                           WHEN u.tipo = 2 THEN 'administrador'
                           WHEN u.tipo = 3 THEN 'estudiante'
                           ELSE 'usuario'
                       END as rol
                FROM usuarios u
                LEFT JOIN empleado e ON u.estuempleado = e.id
                WHERE u.usuario = :usuario 
                  AND u.estado = 1
                  $tipoFiltro";

        $stmt = $this->executeQuery($sql, $params);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Verificar que el rol coincida
            if ($rolSolicitado && $user['rol'] !== $rolSolicitado) {
                error_log("Error: Rol no coincide. Esperado: $rolSolicitado, Obtenido: {$user['rol']}");
                return false;
            }
            
            // 🔐 GENERAR TOKEN
            $newToken = SessionHelper::generateUserToken();
            $this->actualizarToken($user['id'], $newToken);
            $user['token'] = $newToken;
            
            // Remover password
            unset($user['password']);
            
            // DEBUG
            error_log("✅ Login exitoso - Usuario: {$user['usuario']}, Nombre: {$user['nombre_completo']}, Rol: {$user['rol']}, Tipo: {$user['tipo']}");
            
            return $user;
        }
        
        error_log("❌ Login fallido - Usuario: $usuario");
        return false;
    }
    
    public function actualizarToken($userId, $token) {
        $sql = "UPDATE usuarios SET token = :token WHERE id = :id";
        $this->executeQuery($sql, [
            ':token' => $token,
            ':id' => $userId
        ]);
    }
    
    public function verificarToken($userId, $token) {
        if (!SessionHelper::validateUserToken($token)) {
            return false;
        }
        
        $sql = "SELECT id FROM usuarios WHERE id = :id AND token = :token AND estado = 1";
        $stmt = $this->executeQuery($sql, [
            ':id' => $userId,
            ':token' => $token
        ]);
        
        return $stmt->fetch() !== false;
    }
    
    public function eliminarToken($userId) {
        $sql = "UPDATE usuarios SET token = NULL WHERE id = :id";
        $this->executeQuery($sql, [':id' => $userId]);
    }
    
    public function obtenerUsuarioPorId($id) {
        $sql = "SELECT u.*, e.apnom_emp as nombre_completo
                FROM usuarios u 
                LEFT JOIN empleado e ON u.estuempleado = e.id
                WHERE u.id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }
    
    public function obtenerDocentesParaUsuarios() {
        $sql = "SELECT e.id, e.dni_emp, e.apnom_emp 
                FROM empleado e 
                WHERE e.estado = 1 
                  AND e.cargo_emp = 'D'
                  AND e.id NOT IN (SELECT estuempleado FROM usuarios WHERE tipo = 1)";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }
}
?>