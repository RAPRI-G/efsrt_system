<?php
require_once 'BaseModel.php';

class UsuarioModel extends BaseModel {
    private $table = 'usuarios';
    
    public function verificarLogin($usuario, $password) {
        $sql = "SELECT u.*, 
                       CASE 
                           WHEN u.tipo = 1 THEN CONCAT('Docente: ', e.apnom_emp)
                           WHEN u.tipo = 2 THEN CONCAT('Estudiante: ', est.ap_est, ' ', est.am_est, ' ', est.nom_est)
                           ELSE u.usuario
                       END as nombre_completo,
                       CASE 
                           WHEN u.tipo = 1 THEN 'docente'
                           WHEN u.tipo = 2 THEN 'estudiante' 
                           WHEN u.tipo = 3 THEN 'admin'
                           ELSE 'usuario'
                       END as rol
                FROM usuarios u
                LEFT JOIN empleado e ON u.estuempleado = e.id AND u.tipo = 1
                LEFT JOIN estudiante est ON u.estuempleado = est.id AND u.tipo = 2
                WHERE u.usuario = :usuario AND u.estado = 1";
        
        $stmt = $this->executeQuery($sql, [':usuario' => $this->sanitize($usuario)]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Verificar contraseña hasheada
            if (password_verify($password, $user['password'])) {
                // Remover password por seguridad
                unset($user['password']);
                return $user;
            }
            // Si no coincide con hash, verificar si es texto plano (para desarrollo)
            elseif ($password === 'admin' && $user['usuario'] === 'admin') {
                // Para el usuario admin, permitir login con "admin" en texto plano
                unset($user['password']);
                return $user;
            }
        }
        return false;
    }
    
    // Método alternativo usando el código del profesor
    public function verificarLoginProfesor($usuario, $password) {
        $sql = "SELECT id, password, usuario, tipo, estuempleado, estado, nivel 
                FROM usuarios 
                WHERE usuario = :usuario";
        
        $stmt = $this->executeQuery($sql, [':usuario' => $this->sanitize($usuario)]);
        
        if($stmt->rowCount() > 0){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $row['password'])) {
                    return $row;
                }
                // Fallback para desarrollo - permitir "admin" en texto plano
                elseif ($password === 'admin' && $row['usuario'] === 'admin') {
                    return $row;
                }
            }
        }
        return false;
    }
    
    public function obtenerUsuarioPorId($id) {
        $sql = "SELECT id, usuario, tipo, estuempleado, estado, nivel 
                FROM usuarios WHERE id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }
}
?>