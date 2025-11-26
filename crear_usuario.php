<?php
// crear_usuario.php
require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    echo "✅ Conectado a la base de datos<br>";
    
    // Datos del nuevo usuario
    $usuario = "admin_efsrt";
    $password = "123456"; // Contraseña en texto plano
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $tipo = 3; // 1=Docente, 2=Estudiante, 3=Admin
    $estado = 1; // Activo
    
    // Verificar si el usuario ya existe
    $sql_check = "SELECT id FROM usuarios WHERE usuario = :usuario";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bindParam(':usuario', $usuario);
    $stmt_check->execute();
    
    if ($stmt_check->fetch()) {
        echo "❌ El usuario '$usuario' ya existe<br>";
    } else {
        // Insertar nuevo usuario
        $sql_insert = "INSERT INTO usuarios (usuario, password, tipo, estado, nivel) 
                      VALUES (:usuario, :password, :tipo, :estado, 1)";
        
        $stmt_insert = $db->prepare($sql_insert);
        $stmt_insert->bindParam(':usuario', $usuario);
        $stmt_insert->bindParam(':password', $password_hash);
        $stmt_insert->bindParam(':tipo', $tipo);
        $stmt_insert->bindParam(':estado', $estado);
        
        if ($stmt_insert->execute()) {
            echo "✅ Usuario creado exitosamente!<br>";
            echo "📧 Usuario: <strong>$usuario</strong><br>";
            echo "🔑 Password: <strong>$password</strong><br>";
            echo "👤 Tipo: <strong>Administrador</strong><br>";
        } else {
            echo "❌ Error al crear el usuario<br>";
        }
    }
    
    // Mostrar todos los usuarios
    echo "<br><h3>📋 Usuarios existentes:</h3>";
    $sql_users = "SELECT id, usuario, tipo, estado FROM usuarios";
    $stmt_users = $db->query($sql_users);
    $usuarios = $stmt_users->fetchAll();
    
    if ($usuarios) {
        echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Usuario</th><th>Tipo</th><th>Estado</th></tr>";
        foreach ($usuarios as $user) {
            $tipo_text = match($user['tipo']) {
                1 => 'Docente',
                2 => 'Admin', 
                3 => 'estudiante',
                default => 'Desconocido'
            };
            $estado_text = $user['estado'] == 1 ? 'Activo' : 'Inactivo';
            echo "<tr>
                    <td>{$user['id']}</td>
                    <td>{$user['usuario']}</td>
                    <td>$tipo_text</td>
                    <td>$estado_text</td>
                  </tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>