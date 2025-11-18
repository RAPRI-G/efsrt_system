<?php
// verificar_tabla_usuarios.php
require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    echo "<h3>🔍 Verificando estructura de la tabla 'usuarios'</h3>";
    
    // Verificar columnas existentes
    $sql_columns = "SHOW COLUMNS FROM usuarios";
    $stmt_columns = $db->query($sql_columns);
    $columns = $stmt_columns->fetchAll();
    
    echo "<h4>📋 Columnas actuales:</h4>";
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>
                <td>{$col['Field']}</td>
                <td>{$col['Type']}</td>
                <td>{$col['Null']}</td>
                <td>{$col['Key']}</td>
                <td>{$col['Default']}</td>
              </tr>";
    }
    echo "</table>";
    
    // Verificar si necesitamos agregar la columna ultimo_acceso
    $tiene_ultimo_acceso = false;
    foreach ($columns as $col) {
        if ($col['Field'] == 'ultimo_acceso') {
            $tiene_ultimo_acceso = true;
            break;
        }
    }
    
    if (!$tiene_ultimo_acceso) {
        echo "<h4>🛠️ Agregando columna 'ultimo_acceso'...</h4>";
        $sql_alter = "ALTER TABLE usuarios ADD COLUMN ultimo_acceso DATETIME NULL AFTER nivel";
        $db->exec($sql_alter);
        echo "<p style='color: green;'>✅ Columna 'ultimo_acceso' agregada correctamente</p>";
    } else {
        echo "<p style='color: green;'>✅ La columna 'ultimo_acceso' ya existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>