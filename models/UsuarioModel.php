<?php
require_once 'BaseModel.php';

class UsuarioModel extends BaseModel
{
    private $table = 'usuarios';

    public function verificarLogin($usuario, $password, $rolSolicitado = null)
    {
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

        // CONSULTA MEJORADA - Asegurar que trae estuempleado
        $sql = "SELECT 
        u.*, 
        CASE 
            WHEN u.tipo = 1 THEN e.apnom_emp
            WHEN u.tipo = 3 THEN CONCAT(es.ap_est, ' ', es.am_est, ', ', es.nom_est)
            ELSE 'Administrador'
        END as nombre_completo,
        CASE 
            WHEN u.tipo = 1 THEN e.dni_emp
            WHEN u.tipo = 3 THEN es.dni_est
            ELSE NULL
        END as dni,
        CASE 
            WHEN u.tipo = 1 THEN 'docente'
            WHEN u.tipo = 2 THEN 'administrador'
            WHEN u.tipo = 3 THEN 'estudiante'
            ELSE 'usuario'
        END as rol,
        u.estuempleado  -- ← ¡IMPORTANTE! Asegurar que trae este campo
    FROM usuarios u
    LEFT JOIN empleado e ON u.estuempleado = e.id AND u.tipo = 1
    LEFT JOIN estudiante es ON u.estuempleado = es.id AND u.tipo = 3
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

            $this->actualizarUltimoAcceso($user['id']);

            // 🔐 GENERAR TOKEN
            $newToken = SessionHelper::generateUserToken();
            $this->actualizarToken($user['id'], $newToken);
            $user['token'] = $newToken;

            // Remover password
            unset($user['password']);

            // DEBUG - Verificar datos
            error_log("✅ Login exitoso - Usuario: {$user['usuario']}, " .
                "Nombre: {$user['nombre_completo']}, " .
                "Rol: {$user['rol']}, " .
                "Tipo: {$user['tipo']}, " .
                "Estuempleado: " . ($user['estuempleado'] ?? 'NULL'));

            return $user;
        }

        error_log("❌ Login fallido - Usuario: $usuario");
        return false;
    }

    public function actualizarUltimoAcceso($userId)
    {
        try {
            $sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id";
            $this->executeQuery($sql, [':id' => $userId]);
            return true;
        } catch (Exception $e) {
            error_log("Error al actualizar último acceso: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarToken($userId, $token)
    {
        $sql = "UPDATE usuarios SET token = :token WHERE id = :id";
        $this->executeQuery($sql, [
            ':token' => $token,
            ':id' => $userId
        ]);
    }

    public function verificarToken($userId, $token)
    {
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

    public function eliminarToken($userId)
    {
        $sql = "UPDATE usuarios SET token = NULL WHERE id = :id";
        $this->executeQuery($sql, [':id' => $userId]);
    }

    public function obtenerUsuarioPorId($id)
    {
        $sql = "SELECT u.*, e.apnom_emp as nombre_completo
                FROM usuarios u 
                LEFT JOIN empleado e ON u.estuempleado = e.id
                WHERE u.id = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }

    public function obtenerDocentesParaUsuarios()
    {
        $sql = "SELECT e.id, e.dni_emp, e.apnom_emp 
                FROM empleado e 
                WHERE e.estado = 1 
                  AND e.cargo_emp = 'D'
                  AND e.id NOT IN (SELECT estuempleado FROM usuarios WHERE tipo = 1)";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    // models/UsuarioModel.php (agregar al final)

    // ====================================
    // NUEVOS MÉTODOS PARA GESTIÓN COMPLETA
    // ====================================

    public function obtenerTodos()
    {
        try {
            $sql = "SELECT 
                    u.*,
                    CASE 
                        WHEN u.tipo = 1 THEN e.apnom_emp
                        WHEN u.tipo = 3 THEN CONCAT(es.ap_est, ' ', es.am_est, ', ', es.nom_est)
                        ELSE 'Administrador'
                    END as nombre_completo,
                    CASE 
                        WHEN u.tipo = 1 THEN e.dni_emp
                        WHEN u.tipo = 3 THEN es.dni_est
                        ELSE NULL
                    END as documento,
                    CASE 
                        WHEN u.tipo = 1 THEN 'Docente'
                        WHEN u.tipo = 2 THEN 'Administrador'
                        WHEN u.tipo = 3 THEN 'Estudiante'
                        ELSE 'Usuario'
                    END as tipo_nombre,
                    -- Agregar último acceso si existe en la tabla
                    COALESCE(
                        DATE_FORMAT(u.ultimo_acceso, '%d/%m/%Y %H:%i'),
                        'Nunca'
                    ) as ultimo_acceso_formateado,
                    -- También mantener el original para otras operaciones
                    u.ultimo_acceso
                FROM usuarios u
                LEFT JOIN empleado e ON u.estuempleado = e.id AND u.tipo = 1
                LEFT JOIN estudiante es ON u.estuempleado = es.id AND u.tipo = 3
                ORDER BY u.id DESC";

            $stmt = $this->executeQuery($sql);
            $usuarios = $stmt->fetchAll();

            // Formatear fechas para mejor presentación
            foreach ($usuarios as &$usuario) {
                $usuario['ultimo_acceso'] = $this->formatearUltimoAcceso($usuario['ultimo_acceso']);
            }

            return $usuarios;
        } catch (Exception $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }

    private function formatearUltimoAcceso($fecha)
    {
        if (!$fecha || $fecha == '0000-00-00 00:00:00') {
            return 'Nunca';
        }

        try {
            $dateTime = new DateTime($fecha);
            $now = new DateTime();
            $diff = $now->diff($dateTime);

            // Si fue hoy, mostrar hace cuánto tiempo
            if ($diff->days == 0) {
                if ($diff->h > 0) {
                    return "Hace {$diff->h}h {$diff->i}min";
                } elseif ($diff->i > 0) {
                    return "Hace {$diff->i}min";
                } else {
                    return "Hace unos segundos";
                }
            }

            // Si fue ayer
            if ($diff->days == 1) {
                return "Ayer a las " . $dateTime->format('H:i');
            }

            // Si fue esta semana
            if ($diff->days < 7) {
                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                return $dias[$dateTime->format('w')] . " " . $dateTime->format('H:i');
            }

            // Para fechas más antiguas
            return $dateTime->format('d/m/Y H:i');
        } catch (Exception $e) {
            return 'Fecha inválida';
        }
    }

    public function obtenerEstadisticas()
    {
        try {
            $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as activos,
                    SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as inactivos,
                    SUM(CASE WHEN tipo = 1 THEN 1 ELSE 0 END) as docentes,
                    SUM(CASE WHEN tipo = 2 THEN 1 ELSE 0 END) as administradores,
                    SUM(CASE WHEN tipo = 3 THEN 1 ELSE 0 END) as estudiantes,
                    MAX(fecha_creacion) as ultimo_registro,
                    (SELECT usuario FROM usuarios ORDER BY fecha_creacion DESC LIMIT 1) as ultimo_usuario
                FROM usuarios";

            $stmt = $this->executeQuery($sql);
            $result = $stmt->fetch();

            // Formatear fecha del último registro
            $ultimoRegistro = 'Nunca';
            $ultimoUsuario = 'Ninguno';

            if ($result['ultimo_registro']) {
                $fecha = new DateTime($result['ultimo_registro']);
                $ultimoRegistro = $fecha->format('d/m/Y');
                $ultimoUsuario = $result['ultimo_usuario'] ?? 'N/A';
            }

            return [
                'total' => (int)($result['total'] ?? 0),
                'activos' => (int)($result['activos'] ?? 0),
                'inactivos' => (int)($result['inactivos'] ?? 0),
                'docentes' => (int)($result['docentes'] ?? 0),
                'administradores' => (int)($result['administradores'] ?? 0),
                'estudiantes' => (int)($result['estudiantes'] ?? 0),
                'ultimo_registro' => $ultimoRegistro,
                'ultimo_usuario' => $ultimoUsuario
            ];
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return $this->getEstadisticasDefault();
        }
    }

    private function getEstadisticasDefault()
    {
        return [
            'total' => 0,
            'activos' => 0,
            'inactivos' => 0,
            'docentes' => 0,
            'administradores' => 0,
            'estudiantes' => 0,
            'ultimo_registro' => '--',
            'ultimo_usuario' => 'Ninguno'
        ];
    }

    public function crear($data)
    {
        try {
            // Verificar si el usuario ya existe
            $checkSql = "SELECT id FROM usuarios WHERE usuario = ?";
            $checkStmt = $this->executeQuery($checkSql, [$data['usuario']]);

            if ($checkStmt->fetch()) {
                return ['success' => false, 'message' => 'El nombre de usuario ya existe'];
            }

            // Hash de la contraseña
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (usuario, password, tipo, estuempleado, estado, fecha_creacion) 
                    VALUES (?, ?, ?, ?, ?, NOW())";

            $params = [
                $data['usuario'],
                $hashedPassword,
                $data['tipo'],
                $data['estuempleado'] ?: null,
                $data['estado']
            ];

            $stmt = $this->executeQuery($sql, $params);
            $id = $this->db->lastInsertId();

            return [
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'id' => $id
            ];
        } catch (Exception $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al crear usuario: ' . $e->getMessage()];
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $updates = [];
            $params = [];

            if (isset($data['usuario'])) {
                // Verificar que el nuevo usuario no exista (excepto el actual)
                $checkSql = "SELECT id FROM usuarios WHERE usuario = ? AND id != ?";
                $checkStmt = $this->executeQuery($checkSql, [$data['usuario'], $id]);

                if ($checkStmt->fetch()) {
                    return ['success' => false, 'message' => 'El nombre de usuario ya existe'];
                }

                $updates[] = "usuario = ?";
                $params[] = $data['usuario'];
            }

            if (isset($data['tipo'])) {
                $updates[] = "tipo = ?";
                $params[] = $data['tipo'];
            }

            if (isset($data['estuempleado'])) {
                $updates[] = "estuempleado = ?";
                $params[] = $data['estuempleado'] ?: null;
            }

            if (isset($data['estado'])) {
                $updates[] = "estado = ?";
                $params[] = $data['estado'];
            }

            if (!empty($data['password'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                $updates[] = "password = ?";
                $params[] = $hashedPassword;
            }

            if (empty($updates)) {
                return ['success' => false, 'message' => 'No hay datos para actualizar'];
            }

            $params[] = $id;

            $sql = "UPDATE usuarios SET " . implode(', ', $updates) . " WHERE id = ?";
            $this->executeQuery($sql, $params);

            return ['success' => true, 'message' => 'Usuario actualizado exitosamente'];
        } catch (Exception $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al actualizar usuario: ' . $e->getMessage()];
        }
    }

    public function eliminar($id)
    {
        try {
            // Verificar si el usuario existe
            $checkSql = "SELECT id FROM usuarios WHERE id = ?";
            $checkStmt = $this->executeQuery($checkSql, [$id]);

            if (!$checkStmt->fetch()) {
                return ['success' => false, 'message' => 'Usuario no encontrado'];
            }

            // Eliminar usuario
            $sql = "DELETE FROM usuarios WHERE id = ?";
            $this->executeQuery($sql, [$id]);

            return ['success' => true, 'message' => 'Usuario eliminado exitosamente'];
        } catch (Exception $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al eliminar usuario: ' . $e->getMessage()];
        }
    }

    public function resetPassword($id)
    {
        try {
            // Generar contraseña temporal
            $tempPassword = $this->generarPasswordTemporal();
            $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios SET password = ? WHERE id = ?";
            $this->executeQuery($sql, [$hashedPassword, $id]);

            return [
                'success' => true,
                'message' => 'Contraseña restablecida',
                'tempPassword' => $tempPassword
            ];
        } catch (Exception $e) {
            error_log("Error al restablecer contraseña: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al restablecer contraseña: ' . $e->getMessage()];
        }
    }

    private function generarPasswordTemporal()
    {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%';
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $password;
    }

    public function obtenerParaUsuarios()
    {
        try {
            $sql = "SELECT id, dni_est, CONCAT(ap_est, ' ', am_est, ', ', nom_est) as nombre_completo 
                    FROM estudiante 
                    WHERE estado = 1 
                    ORDER BY ap_est, am_est, nom_est";

            $stmt = $this->executeQuery($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error al obtener estudiantes para usuarios: " . $e->getMessage());
            return [];
        }
    }
}
