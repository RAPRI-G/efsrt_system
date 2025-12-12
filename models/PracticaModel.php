<?php
require_once 'BaseModel.php';

class PracticaModel extends BaseModel
{
    private $table = 'practicas';

    public function obtenerPracticas()
    {
        $sql = "SELECT p.*, 
                       e.dni_est, e.ap_est, e.am_est, e.nom_est,
                       emp.razon_social
                FROM practicas p
                INNER JOIN estudiante e ON p.estudiante = e.id
                INNER JOIN empresa emp ON p.empresa = emp.id
                ORDER BY p.fecha_inicio DESC";

        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    // NUEVOS MÉTODOS PARA EL DASHBOARD
    public function obtenerPracticasDashboard()
    {
        try {
            $sql = "SELECT 
                    p.id,
                    p.estudiante,
                    p.empresa,
                    p.tipo_efsrt,
                    p.periodo_academico,
                    p.fecha_inicio,
                    p.fecha_fin,
                    p.total_horas,
                    p.horas_acumuladas,
                    p.area_ejecucion,
                    p.supervisor_empresa,
                    p.cargo_supervisor,
                    p.estado,
                    p.empleado,  -- 🔥 ID del docente supervisor
                    COALESCE(p.horas_acumuladas, 0) as horas_acumuladas,
                    -- Datos del estudiante
                    e.dni_est,
                    e.ap_est,
                    e.am_est,
                    e.nom_est,
                     -- Datos del programa de estudios (CORREGIDO: nom_progest)
                m.prog_estudios,
                pe.nom_progest as programa,  -- 🔥 CAMBIADO: 'programa' en lugar de 'nombre_programa'
                    -- Datos de la empresa
                    emp.razon_social as nombre_empresa,
                    -- Datos del docente supervisor (empleado)
                    em.apnom_emp as nombre_docente  -- 🔥 ESTA ES LA COLUMNA CORRECTA
                FROM practicas p
                LEFT JOIN estudiante e ON p.estudiante = e.id

                -- JOINS para obtener el programa
            LEFT JOIN matricula m ON e.id = m.estudiante
            LEFT JOIN prog_estudios pe ON m.prog_estudios = pe.id

                LEFT JOIN empresa emp ON p.empresa = emp.id
                LEFT JOIN empleado em ON p.empleado = em.id  -- 🔥 UNIR CON TABLA EMPLEADO
                WHERE p.estado IS NOT NULL
                ORDER BY p.fecha_inicio DESC, p.id DESC";

            $stmt = $this->executeQuery($sql);
            $resultados = $stmt->fetchAll();

            error_log("📊 Total prácticas obtenidas: " . count($resultados));
            if (count($resultados) > 0) {
                error_log("📋 Primer práctica - Docente: " . ($resultados[0]['nombre_docente'] ?? 'NO HAY DOCENTE'));
            }

            return $resultados;
        } catch (Exception $e) {
            error_log("❌ Error en obtenerPracticasDashboard: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPracticasPorEmpresa($empresaId)
    {
        try {
            $sql = "SELECT 
                    p.id,
                    p.estudiante,
                    p.modulo,
                    p.estado,
                    p.tipo_efsrt,
                    p.fecha_inicio,
                    p.fecha_fin,
                    p.total_horas,
                    CONCAT(e.ap_est, ' ', e.am_est, ', ', e.nom_est) as estudiante_nombre,
                    e.dni_est,
                    pe.nom_progest as programa_estudios
                FROM practicas p
                LEFT JOIN estudiante e ON p.estudiante = e.id
                LEFT JOIN matricula m ON e.id = m.estudiante
                LEFT JOIN prog_estudios pe ON m.prog_estudios = pe.id
                WHERE p.empresa = :empresa_id 
                AND p.estado != 'Eliminada'
                ORDER BY p.estado, p.fecha_inicio DESC";

            $stmt = $this->executeQuery($sql, [':empresa_id' => $empresaId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function contarPracticasPorEstado($estado)
    {
        $sql = "SELECT COUNT(*) as total FROM practicas WHERE estado = :estado";
        $stmt = $this->executeQuery($sql, [':estado' => $estado]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function contarTotalPracticas()
    {
        $sql = "SELECT COUNT(*) as total FROM practicas";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // Agregar este método a models/PracticaModel.php
    public function obtenerDistribucionEstadoPracticas()
    {
        try {
            $sql = "SELECT estado, COUNT(*) as cantidad FROM practicas GROUP BY estado";
            $stmt = $this->executeQuery($sql);
            $resultados = $stmt->fetchAll();

            $distribucion = [
                'En curso' => 0,
                'Finalizado' => 0,
                'Pendiente' => 0
            ];

            foreach ($resultados as $row) {
                $estado = $row['estado'] ?? 'Pendiente';
                $distribucion[$estado] = $row['cantidad'];
            }

            return $distribucion;
        } catch (Exception $e) {
            error_log("Error en obtenerDistribucionEstadoPracticas: " . $e->getMessage());
            return ['En curso' => 0, 'Finalizado' => 0, 'Pendiente' => 0];
        }
    }

    public function obtenerDistribucionModulos()
    {
        $sql = "SELECT tipo_efsrt, COUNT(*) as cantidad FROM practicas GROUP BY tipo_efsrt";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetchAll();

        $distribucion = [
            'Módulo 1' => 0,
            'Módulo 2' => 0,
            'Módulo 3' => 0
        ];

        foreach ($result as $row) {
            $modulo = $this->getNombreModulo($row['tipo_efsrt']);
            $distribucion[$modulo] = $row['cantidad'];
        }

        return $distribucion;
    }

    public function obtenerPracticasEnCurso()
    {
        $sql = "SELECT 
                    p.id,
                    p.tipo_efsrt,
                    p.horas_acumuladas,
                    p.total_horas,
                    e.nom_est,
                    e.ap_est,
                    emp.razon_social
                FROM practicas p
                LEFT JOIN estudiante e ON p.estudiante = e.id
                LEFT JOIN empresa emp ON p.empresa = emp.id
                WHERE p.estado = 'En curso'
                ORDER BY p.fecha_inicio DESC
                LIMIT 5";

        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    public function obtenerActividadReciente()
    {
        $sql = "SELECT 
                    'practica' as tipo,
                    CONCAT('Nueva práctica: ', e.nom_est, ' ', e.ap_est) as descripcion,
                    p.fecha_inicio as fecha
                FROM practicas p
                LEFT JOIN estudiante e ON p.estudiante = e.id
                WHERE p.fecha_inicio >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                UNION ALL
                SELECT 
                    'asistencia' as tipo,
                    CONCAT('Asistencia registrada: ', e.nom_est, ' ', e.ap_est) as descripcion,
                    a.fecha as fecha
                FROM asistencias a
                LEFT JOIN practicas p ON a.practicas = p.id
                LEFT JOIN estudiante e ON p.estudiante = e.id
                WHERE a.fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                ORDER BY fecha DESC
                LIMIT 5";

        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    public function hayDatosPrueba()
    {
        $sql = "SELECT COUNT(*) as count FROM practicas WHERE tipo_efsrt IS NOT NULL";
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function insertarDatosPrueba()
    {
        // Solo insertar datos de prueba si no hay datos existentes
        if ($this->hayDatosPrueba()) {
            return;
        }

        // Obtener algunos estudiantes, empresas y docentes existentes
        $estudiantes = $this->obtenerEstudiantesParaPrueba();
        $empresas = $this->obtenerEmpresasParaPrueba();
        $docentes = $this->obtenerDocentesParaPrueba();

        if (empty($estudiantes) || empty($empresas) || empty($docentes)) {
            return;
        }

        // Insertar algunas prácticas de prueba
        $practicasPrueba = [
            [
                'estudiante' => $estudiantes[0]['id'],
                'empresa' => $empresas[0]['id'],
                'tipo_efsrt' => 'modulo1',
                'docente_supervisor' => $docentes[0]['id'],
                'periodo_academico' => '2025-I',
                'fecha_inicio' => '2025-04-16',
                'fecha_fin' => '2025-07-01',
                'total_horas' => 128,
                'horas_acumuladas' => 45,
                'area_ejecucion' => 'Desarrollo Web Frontend',
                'estado' => 'En curso'
            ],
            [
                'estudiante' => $estudiantes[1]['id'],
                'empresa' => $empresas[1]['id'],
                'tipo_efsrt' => 'modulo2',
                'docente_supervisor' => $docentes[0]['id'],
                'periodo_academico' => '2025-I',
                'fecha_inicio' => '2025-04-16',
                'fecha_fin' => '2025-07-01',
                'total_horas' => 128,
                'horas_acumuladas' => 78,
                'area_ejecucion' => 'Desarrollo Backend',
                'estado' => 'En curso'
            ]
        ];

        foreach ($practicasPrueba as $practica) {
            $this->insertarPracticaPrueba($practica);
        }
    }

    private function obtenerEstudiantesParaPrueba()
    {
        $sql = "SELECT id, dni_est, ap_est, nom_est FROM estudiante WHERE estado IS NULL OR estado = 1 LIMIT 3";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    private function obtenerEmpresasParaPrueba()
    {
        $sql = "SELECT id, razon_social FROM empresa WHERE estado = 'activa' LIMIT 3";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    private function obtenerDocentesParaPrueba()
    {
        $sql = "SELECT id, apnom_emp FROM empleado WHERE (estado IS NULL OR estado = 1) AND cargo_emp = 'D' LIMIT 2";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    private function insertarPracticaPrueba($datos)
    {
        $sql = "INSERT INTO practicas 
                (estudiante, empresa, tipo_efsrt, docente_supervisor, periodo_academico, 
                 fecha_inicio, fecha_fin, total_horas, horas_acumuladas, area_ejecucion, estado) 
                VALUES (:estudiante, :empresa, :tipo_efsrt, :docente_supervisor, :periodo_academico,
                        :fecha_inicio, :fecha_fin, :total_horas, :horas_acumuladas, :area_ejecucion, :estado)";

        $params = [
            ':estudiante' => $datos['estudiante'],
            ':empresa' => $datos['empresa'],
            ':tipo_efsrt' => $datos['tipo_efsrt'],
            ':docente_supervisor' => $datos['docente_supervisor'],
            ':periodo_academico' => $datos['periodo_academico'],
            ':fecha_inicio' => $datos['fecha_inicio'],
            ':fecha_fin' => $datos['fecha_fin'],
            ':total_horas' => $datos['total_horas'],
            ':horas_acumuladas' => $datos['horas_acumuladas'],
            ':area_ejecucion' => $datos['area_ejecucion'],
            ':estado' => $datos['estado']
        ];

        $this->executeQuery($sql, $params);
    }

    private function getNombreModulo($tipo_efsrt)
    {
        $modulos = [
            'modulo1' => 'Módulo 1',
            'modulo2' => 'Módulo 2',
            'modulo3' => 'Módulo 3'
        ];

        return $modulos[$tipo_efsrt] ?? 'Módulo 1';
    }

    // Métodos existentes...
    public function registrarPractica($datos)
    {
        try {
            error_log("📝 Ejecutando registrarPractica con datos: " . print_r($datos, true));

            $sql = "INSERT INTO practicas 
                (estudiante, empleado, empresa, modulo, tipo_efsrt, periodo_academico, 
                 fecha_inicio, total_horas, horas_acumuladas, area_ejecucion, 
                 supervisor_empresa, cargo_supervisor, estado, fecha_registro) 
                VALUES (:estudiante, :empleado, :empresa, :modulo, :tipo_efsrt, :periodo_academico,
                        :fecha_inicio, :total_horas, :horas_acumuladas, :area_ejecucion, 
                        :supervisor_empresa, :cargo_supervisor, :estado, NOW())";

            $params = [
                ':estudiante' => $datos['estudiante'] ?? $datos['estudiante_id'] ?? null,
                ':empleado' => $datos['empleado'] ?? $datos['docente_supervisor'] ?? null,
                ':empresa' => $datos['empresa'] ?? $datos['empresa_id'] ?? null,
                ':modulo' => $datos['modulo'] ?? $this->getNombreModulo($datos['tipo_efsrt'] ?? ''),
                ':tipo_efsrt' => $datos['tipo_efsrt'] ?? '',
                ':periodo_academico' => $datos['periodo_academico'] ?? '2024-1',
                ':fecha_inicio' => $datos['fecha_inicio'] ?? '',
                ':total_horas' => $datos['total_horas'] ?? 128,
                ':horas_acumuladas' => $datos['horas_acumuladas'] ?? 0,
                ':area_ejecucion' => $datos['area_ejecucion'] ?? '',
                ':supervisor_empresa' => $datos['supervisor_empresa'] ?? '',
                ':cargo_supervisor' => $datos['cargo_supervisor'] ?? '',
                ':estado' => $datos['estado'] ?? 'En curso'
            ];

            // Limpiar parámetros nulos
            foreach ($params as $key => $value) {
                if ($value === null) {
                    unset($params[$key]);
                    $sql = str_replace($key, 'NULL', $sql);
                }
            }

            error_log("🔧 SQL: $sql");
            error_log("🔧 Parámetros: " . print_r($params, true));

            $stmt = $this->executeQuery($sql, $params);
            $result = $stmt->rowCount() > 0;

            error_log("✅ registrarPractica resultado: " . ($result ? "éxito" : "fallo"));
            return $result;
        } catch (Exception $e) {
            error_log("💥 Error en registrarPractica: " . $e->getMessage());
            return false;
        }
    }


    public function obtenerPracticaPorId($id)
    {
        $sql = "SELECT p.*, 
                       e.dni_est, e.ap_est, e.am_est, e.nom_est, e.cel_est, e.mailp_est, e.dir_est,
                       emp.razon_social, emp.direccion_fiscal, emp.telefono as emp_telefono, emp.email as emp_email
                FROM practicas p
                INNER JOIN estudiante e ON p.estudiante = e.id
                INNER JOIN empresa emp ON p.empresa = emp.id
                WHERE p.id = :id";

        $stmt = $this->executeQuery($sql, [':id' => $id]);
        return $stmt->fetch();
    }

    // Agregar este método al final de models/PracticaModel.php
    // Agrega este método a tu EstudianteModel.php
    public function obtenerEstudiantesConModulos()
    {
        try {
            $sql = "SELECT 
                e.id, 
                e.dni_est,
                e.ap_est,
                e.am_est,
                e.nom_est,
                e.cel_est,
                e.mailp_est,
                p.nom_progest as programa,
                CONCAT(e.ap_est, ' ', COALESCE(e.am_est, ''), ', ', e.nom_est) as nombre_completo,
                UPPER(
                    CONCAT(
                        SUBSTRING(e.ap_est, 1, 1), 
                        COALESCE(SUBSTRING(e.am_est, 1, 1), '')
                    )
                ) as iniciales
            FROM estudiante e
            LEFT JOIN matricula m ON e.id = m.estudiante
            LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
            WHERE e.estado = 1 
            ORDER BY e.ap_est, e.am_est, e.nom_est";

            $stmt = $this->executeQuery($sql);
            $resultados = $stmt->fetchAll();

            return $resultados;
        } catch (Exception $e) {
            error_log("Error en obtenerEstudiantesConModulos: " . $e->getMessage());
            return [];
        }
    }

    // Agregar al final del archivo PracticaModel.php

    // En tu PracticaModel.php
    public function crearPractica($datos)
    {
        try {
            error_log("🔍 Intentando crear práctica con datos: " . print_r($datos, true));

            $sql = "INSERT INTO practicas 
                (estudiante, empleado, empresa, modulo, tipo_efsrt, periodo_academico, 
                 fecha_inicio, total_horas, horas_acumuladas, area_ejecucion, 
                 supervisor_empresa, cargo_supervisor, estado, fecha_registro) 
                VALUES (:estudiante, :empleado, :empresa, :modulo, :tipo_efsrt, :periodo_academico,
                        :fecha_inicio, :total_horas, :horas_acumuladas, :area_ejecucion, 
                        :supervisor_empresa, :cargo_supervisor, :estado, NOW())";

            $params = [
                ':estudiante' => $datos['estudiante'],
                ':empleado' => $datos['empleado'] ?? null,
                ':empresa' => $datos['empresa'],
                ':modulo' => $datos['modulo'],
                ':tipo_efsrt' => $datos['tipo_efsrt'],
                ':periodo_academico' => $datos['periodo_academico'],
                ':fecha_inicio' => $datos['fecha_inicio'],
                ':total_horas' => $datos['total_horas'],
                ':horas_acumuladas' => $datos['horas_acumuladas'] ?? 0,
                ':area_ejecucion' => $datos['area_ejecucion'],
                ':supervisor_empresa' => $datos['supervisor_empresa'],
                ':cargo_supervisor' => $datos['cargo_supervisor'],
                ':estado' => $datos['estado']
            ];

            error_log("🔍 Parámetros SQL: " . print_r($params, true));

            $stmt = $this->executeQuery($sql, $params);
            $lastInsertId = $this->db->lastInsertId();

            error_log("✅ Práctica creada exitosamente. ID: " . $lastInsertId);

            return $lastInsertId;
        } catch (Exception $e) {
            error_log("❌ Error en crearPractica: " . $e->getMessage());
            error_log("❌ SQL: " . $sql);
            error_log("❌ Parámetros: " . print_r($params, true));
            return false;
        }
    }

    public function actualizarPractica($id, $datos)
    {
        try {
            $sql = "UPDATE practicas SET 
                estudiante = :estudiante,
                empleado = :empleado,
                empresa = :empresa,
                modulo = :modulo,
                tipo_efsrt = :tipo_efsrt,
                periodo_academico = :periodo_academico,
                fecha_inicio = :fecha_inicio,
                total_horas = :total_horas,
                area_ejecucion = :area_ejecucion,
                supervisor_empresa = :supervisor_empresa,
                cargo_supervisor = :cargo_supervisor
                WHERE id = :id";

            $params = [
                ':estudiante' => $datos['estudiante'],
                ':empleado' => $datos['empleado'] ?? null,
                ':empresa' => $datos['empresa'],
                ':modulo' => $datos['modulo'],
                ':tipo_efsrt' => $datos['tipo_efsrt'],
                ':periodo_academico' => $datos['periodo_academico'],
                ':fecha_inicio' => $datos['fecha_inicio'],
                ':total_horas' => $datos['total_horas'],
                ':area_ejecucion' => $datos['area_ejecucion'],
                ':supervisor_empresa' => $datos['supervisor_empresa'],
                ':cargo_supervisor' => $datos['cargo_supervisor'],
                ':id' => $id
            ];

            $stmt = $this->executeQuery($sql, $params);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error en actualizarPractica: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarPractica($id)
    {
        try {
            // Primero eliminar asistencias relacionadas si existen
            if ($this->tableExists('asistencias')) {
                $sqlAsistencias = "DELETE FROM asistencias WHERE practicas = :id";
                $this->executeQuery($sqlAsistencias, [':id' => $id]);
            }

            // Luego eliminar la práctica
            $sql = "DELETE FROM practicas WHERE id = :id";
            $stmt = $this->executeQuery($sql, [':id' => $id]);

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error en eliminarPractica: " . $e->getMessage());
            return false;
        }
    }

    private function tableExists($tableName)
    {
        try {
            $sql = "SHOW TABLES LIKE :table";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':table' => $tableName]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    // Método para obtener estudiantes para prácticas
    public function obtenerEstudiantesParaPracticas()
    {
        try {
            $sql = "SELECT e.id, 
                       e.dni_est,
                       e.ap_est,
                       e.am_est,
                       e.nom_est,
                       CONCAT(e.ap_est, ' ', e.am_est, ', ', e.nom_est) as nombre_completo,
                       p.nom_progest as programa
                FROM estudiante e
                LEFT JOIN matricula m ON e.id = m.estudiante
                LEFT JOIN prog_estudios p ON m.prog_estudios = p.id
                WHERE e.estado = 1
                ORDER BY e.ap_est, e.am_est, e.nom_est";

            $stmt = $this->executeQuery($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error en obtenerEstudiantesParaPracticas: " . $e->getMessage());
            return [];
        }
    }

    // En models/PracticaModel.php - Agregar estos métodos si no existen

    public function obtenerPracticasPorEstudiante($estudiante_id)
    {
        $sql = "SELECT p.*, e.razon_social as empresa_nombre
            FROM practicas p
            LEFT JOIN empresa e ON p.empresa = e.id
            WHERE p.estudiante = :estudiante_id
            ORDER BY 
                CASE p.tipo_efsrt 
                    WHEN 'modulo1' THEN 1 
                    WHEN 'modulo2' THEN 2 
                    WHEN 'modulo3' THEN 3 
                    ELSE 4 
                END";

        $stmt = $this->executeQuery($sql, [':estudiante_id' => $estudiante_id]);
        return $stmt->fetchAll();
    }

    public function obtenerPracticaByModulo($estudiante_id, $modulo)
    {
        $sql = "SELECT p.*, e.razon_social as empresa_nombre
            FROM practicas p
            LEFT JOIN empresa e ON p.empresa = e.id
            WHERE p.estudiante = :estudiante_id 
            AND p.tipo_efsrt = :modulo
            LIMIT 1";

        $stmt = $this->executeQuery($sql, [
            ':estudiante_id' => $estudiante_id,
            ':modulo' => $modulo
        ]);

        return $stmt->fetch();
    }
}
