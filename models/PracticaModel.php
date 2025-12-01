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
                    COALESCE(p.horas_acumuladas, 0) as horas_acumuladas, -- 🔥 Asegurar que siempre tenga valor
                    e.dni_est,
                    e.ap_est,
                    e.am_est,
                    e.nom_est,
                    emp.razon_social
                FROM practicas p
                LEFT JOIN estudiante e ON p.estudiante = e.id
                LEFT JOIN empresa emp ON p.empresa = emp.id
                WHERE p.estado IS NOT NULL
                ORDER BY p.fecha_inicio DESC, p.id DESC";
        
        $stmt = $this->executeQuery($sql);
        $resultados = $stmt->fetchAll();
        
        // 🔥 DEBUG: Ver qué datos se están obteniendo
        error_log("📊 Total módulos obtenidos: " . count($resultados));
        if (count($resultados) > 0) {
            error_log("📋 Primer módulo: " . print_r($resultados[0], true));
        }
        
        return $resultados;
        
    } catch (Exception $e) {
        error_log("❌ Error en obtenerPracticasDashboard: " . $e->getMessage());
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

        return $modulos[$tipo_efsrt] ?? $tipo_efsrt;
    }

    // Métodos existentes...
    public function registrarPractica($datos)
    {
        $sql = "INSERT INTO practicas 
                (estudiante, empresa, tipo_efsrt, docente_supervisor, periodo_academico_efsrt, 
                 turno_efsrt, fecha_inicio, fecha_fin, area_ejecucion, estado) 
                VALUES (:estudiante, :empresa, :tipo_efsrt, :docente_supervisor, :periodo_academico, 
                        :turno, :fecha_inicio, :fecha_fin, :area_ejecucion, 'Pendiente')";

        $params = [
            ':estudiante' => $this->sanitize($datos['estudiante']),
            ':empresa' => $this->sanitize($datos['empresa']),
            ':tipo_efsrt' => $this->sanitize($datos['tipo_efsrt']),
            ':docente_supervisor' => $this->sanitize($datos['docente_supervisor']),
            ':periodo_academico' => $this->sanitize($datos['periodo_academico']),
            ':turno' => $this->sanitize($datos['turno']),
            ':fecha_inicio' => $this->sanitize($datos['fecha_inicio']),
            ':fecha_fin' => $this->sanitize($datos['fecha_fin']),
            ':area_ejecucion' => $this->sanitize($datos['area_ejecucion'])
        ];

        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount() > 0;
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
    public function obtenerEstudiantesConModulos()
    {
        try {
            $sql = "SELECT DISTINCT 
                    e.id, 
                    e.dni_est, 
                    e.ap_est, 
                    e.am_est, 
                    e.nom_est,
                    p.nom_progest as programa,
                    CONCAT(e.ap_est, ' ', e.am_est, ', ', e.nom_est) as nombre_completo,
                    UPPER(CONCAT(SUBSTRING(e.ap_est, 1, 1), SUBSTRING(e.am_est, 1, 1))) as iniciales
                FROM estudiante e
                INNER JOIN matricula m ON e.id = m.estudiante
                INNER JOIN prog_estudios p ON m.prog_estudios = p.id
                INNER JOIN practicas pr ON e.id = pr.estudiante
                WHERE e.estado = 1 
                AND (m.est_matricula = '1' OR m.est_matricula IS NULL)
                ORDER BY e.ap_est, e.am_est, e.nom_est";

            $stmt = $this->executeQuery($sql);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error en obtenerEstudiantesConModulos: " . $e->getMessage());
            return [];
        }
    }
}
