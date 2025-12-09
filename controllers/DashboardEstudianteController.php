<?php
require_once 'helpers/SessionHelper.php';
require_once 'models/EstudianteModel.php';
require_once 'models/PracticaModel.php';
require_once 'models/AsistenciaModel.php';
require_once 'models/EmpresaModel.php';

class DashboardEstudianteController
{

    public function index()
    {
        // 1. Verificar login
        if (!SessionHelper::isLoggedIn()) {
            header("Location: index.php?c=Login&a=index");
            exit;
        }

        $usuario = SessionHelper::getUser();

        // 2. Verificar que sea estudiante
        if (!SessionHelper::esEstudiante()) {
            error_log("⚠️ Acceso denegado: Usuario {$usuario['usuario']} (Rol: {$usuario['rol']}) intentó acceder al DashboardEstudiante");
            header("Location: index.php?c=Inicio&a=index&error=acceso_denegado");
            exit;
        }

        // 3. Obtener ID del estudiante
        $estudiante_id = $usuario['estuempleado'] ?? null;

        if (!$estudiante_id) {
            // Intentar buscar el estudiante por usuario
            $estudiante_id = $this->buscarEstudiantePorUsuario($usuario['id']);

            if ($estudiante_id) {
                // Actualizar sesión
                $usuario['estuempleado'] = $estudiante_id;
                SessionHelper::set('usuario', $usuario);
                error_log("✅ Estuempleado actualizado en sesión: " . $estudiante_id);
            } else {
                error_log("❌ ERROR: No se pudo encontrar estudiante para usuario ID: " . $usuario['id']);
                echo "<div style='padding: 20px; text-align: center;'>";
                echo "<h1>Error: Cuenta no vinculada</h1>";
                echo "<p>Tu cuenta de usuario no está vinculada a un estudiante.</p>";
                echo "<p>Por favor, contacta al administrador.</p>";
                echo "<p><a href='index.php?c=Login&a=logout'>Cerrar sesión</a></p>";
                echo "</div>";
                exit;
            }
        }

        // 4. Cargar modelos y obtener datos
        $estudianteModel = new EstudianteModel();
        $practicaModel = new PracticaModel();

        $estudiante = $estudianteModel->obtenerEstudianteCompleto($estudiante_id);

        if (!$estudiante) {
            error_log("❌ ERROR: Estudiante no encontrado en BD con ID: " . $estudiante_id);
            echo "<div style='padding: 20px; text-align: center;'>";
            echo "<h1>Error: Estudiante no encontrado</h1>";
            echo "<p>No se encontró información del estudiante en la base de datos.</p>";
            echo "<p>Contacta al administrador.</p>";
            echo "</div>";
            exit;
        }

        // 5. Obtener prácticas del estudiante ORGANIZADAS POR MÓDULO
        $practicas_por_modulo = $this->obtenerPracticasEstudiante($estudiante_id);

        // 6. Determinar módulo activo (el primero que encuentre en curso)
        $modulo_activo = null;
        $modulos_activos = [];

        foreach ($practicas_por_modulo as $tipo => $practica) {
            if ($practica && $practica['estado'] == 'En curso') {
                if (!$modulo_activo) {
                    $modulo_activo = $practica;
                }
                $modulos_activos[$tipo] = $practica;
            }
        }

        // Si no hay módulo activo, usar el primer módulo que tenga datos
        if (!$modulo_activo) {
            foreach ($practicas_por_modulo as $tipo => $practica) {
                if ($practica) {
                    $modulo_activo = $practica;
                    break;
                }
            }
        }

        // 7. Obtener asistencias recientes del módulo activo
        $asistencias_recientes = [];
        if ($modulo_activo && isset($modulo_activo['id'])) {
            $asistencias_recientes = $this->obtenerAsistenciasPractica($modulo_activo['id'], 5);
        }

        // 8. Calcular estadísticas GENERALES (no solo del módulo activo)
        $estadisticas = $this->calcularEstadisticasCompletas($practicas_por_modulo, $modulo_activo);

        // 9. Obtener progreso semanal
        $semana_actual = $_GET['semana'] ?? 3;
        $progreso_semanal = $this->obtenerProgresoSemanal($modulo_activo, $semana_actual);

        // 10. Preparar datos para la vista
        $data = [
            'estudiante' => $estudiante,
            'practicas_por_modulo' => $practicas_por_modulo, // ← TODOS LOS MÓDULOS
            'modulo_activo' => $modulo_activo,
            'modulos_activos' => $modulos_activos,
            'asistencias_recientes' => $asistencias_recientes,
            'estadisticas' => $estadisticas,
            'progreso_semanal' => $progreso_semanal,
            'semana_actual' => $semana_actual
        ];

        require_once 'views/InicioEstudiante/dashboard.php';
    }

    private function calcularEstadisticasCompletas($practicas_por_modulo, $modulo_activo)
    {
        $estadisticas = [
            'progreso_modulo' => 0,
            'asistencias_count' => 0,
            'horas_totales' => 0,
            'dias_restantes' => 0,
            'porcentaje_asistencia' => 0,
            'modulos_completados' => 0,
            'modulos_en_curso' => 0,
            'modulos_pendientes' => 0,
            'horas_totales_acumuladas' => 0
        ];

        // Contar módulos por estado
        foreach ($practicas_por_modulo as $tipo => $practica) {
            if ($practica) {
                $estado = $practica['estado'] ?? 'Pendiente';

                if ($estado == 'Finalizado') {
                    $estadisticas['modulos_completados']++;
                } elseif ($estado == 'En curso') {
                    $estadisticas['modulos_en_curso']++;
                } elseif ($estado == 'Pendiente') {
                    $estadisticas['modulos_pendientes']++;
                }

                // Sumar horas acumuladas
                $estadisticas['horas_totales_acumuladas'] += ($practica['horas_acumuladas'] ?? 0);

                // Sumar asistencias
                $estadisticas['asistencias_count'] += ($practica['total_asistencias'] ?? 0);
            } else {
                $estadisticas['modulos_pendientes']++;
            }
        }

        // Estadísticas del módulo activo
        if ($modulo_activo) {
            // Progreso del módulo activo
            $total_horas = $modulo_activo['total_horas'] ?: 128;
            $horas_acumuladas = $modulo_activo['horas_acumuladas'] ?: 0;

            if ($total_horas > 0) {
                $estadisticas['progreso_modulo'] = min(100, round(($horas_acumuladas / $total_horas) * 100));
            }

            $estadisticas['horas_totales'] = $horas_acumuladas;

            // Días restantes del módulo activo
            if ($modulo_activo['fecha_fin']) {
                $hoy = new DateTime();
                $fin = new DateTime($modulo_activo['fecha_fin']);
                if ($fin > $hoy) {
                    $diferencia = $hoy->diff($fin);
                    $estadisticas['dias_restantes'] = $diferencia->days;
                }
            }

            // Calcular porcentaje de asistencia del módulo activo
            $estadisticas['porcentaje_asistencia'] = $this->calcularPorcentajeAsistencia($modulo_activo);
        }

        return $estadisticas;
    }

    private function calcularPorcentajeAsistencia($modulo_activo)
    {
        if (!$modulo_activo || !isset($modulo_activo['id'])) {
            return 0;
        }

        require_once 'config/database.php';
        $db = Database::getInstance()->getConnection();

        try {
            // 1. Verificar si hay fecha de inicio y fin
            if (!$modulo_activo['fecha_inicio'] || !$modulo_activo['fecha_fin']) {
                // Si no hay fechas, calcular porcentaje basado en días desde el registro
                return $this->calcularPorcentajeSimple($modulo_activo['id']);
            }

            $fecha_inicio = new DateTime($modulo_activo['fecha_inicio']);
            $fecha_fin = new DateTime($modulo_activo['fecha_fin']);
            $hoy = new DateTime();

            // Si el módulo aún no ha empezado
            if ($hoy < $fecha_inicio) {
                return 0;
            }

            // Si el módulo ya terminó, usar la fecha de fin
            if ($hoy > $fecha_fin) {
                $fecha_hasta = $fecha_fin;
            } else {
                $fecha_hasta = $hoy;
            }

            // 2. Calcular días hábiles totales (lunes a viernes)
            $dias_habiles_totales = $this->calcularDiasHabiles($fecha_inicio, $fecha_fin);

            // 3. Calcular días hábiles hasta hoy
            $dias_habiles_hasta_hoy = $this->calcularDiasHabiles($fecha_inicio, $fecha_hasta);

            if ($dias_habiles_hasta_hoy <= 0) {
                return 0;
            }

            // 4. Obtener días con asistencia registrada
            $sql = "SELECT COUNT(DISTINCT DATE(fecha)) as dias_asistidos 
                FROM asistencias 
                WHERE practicas = :practica_id 
                  AND fecha BETWEEN :fecha_inicio AND :fecha_hasta";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':practica_id' => $modulo_activo['id'],
                ':fecha_inicio' => $fecha_inicio->format('Y-m-d'),
                ':fecha_hasta' => $fecha_hasta->format('Y-m-d')
            ]);

            $result = $stmt->fetch();
            $dias_asistidos = $result['dias_asistidos'] ?? 0;

            // 5. Calcular porcentaje
            if ($dias_habiles_hasta_hoy > 0) {
                $porcentaje = ($dias_asistidos / $dias_habiles_hasta_hoy) * 100;
                return min(100, round($porcentaje, 1)); // Máximo 100%
            }

            return 0;
        } catch (Exception $e) {
            error_log("Error calculando porcentaje asistencia: " . $e->getMessage());
            // Fallback: calcular porcentaje simple
            return $this->calcularPorcentajeSimple($modulo_activo['id'] ?? 0);
        }
    }

    private function calcularPorcentajeSimple($practica_id)
    {
        if (!$practica_id) return 0;

        require_once 'config/database.php';
        $db = Database::getInstance()->getConnection();

        try {
            // 1. Contar total de asistencias
            $sql_asistencias = "SELECT COUNT(*) as total_asistencias 
                           FROM asistencias 
                           WHERE practicas = :practica_id";
            $stmt_asistencias = $db->prepare($sql_asistencias);
            $stmt_asistencias->execute([':practica_id' => $practica_id]);
            $result_asistencias = $stmt_asistencias->fetch();
            $total_asistencias = $result_asistencias['total_asistencias'] ?? 0;

            // 2. Obtener fecha de registro de la práctica
            $sql_practica = "SELECT fecha_registro FROM practicas WHERE id = :practica_id";
            $stmt_practica = $db->prepare($sql_practica);
            $stmt_practica->execute([':practica_id' => $practica_id]);
            $result_practica = $stmt_practica->fetch();

            $fecha_registro = $result_practica['fecha_registro'] ?? null;

            if (!$fecha_registro) {
                // Si no hay fecha de registro, asumir 20 días hábiles
                $dias_esperados = 20;
            } else {
                // Calcular días hábiles desde el registro hasta hoy
                $fecha_inicio = new DateTime($fecha_registro);
                $hoy = new DateTime();
                $dias_esperados = $this->calcularDiasHabiles($fecha_inicio, $hoy);

                // Mínimo 10 días, máximo 30 días
                $dias_esperados = max(10, min(30, $dias_esperados));
            }

            // 3. Calcular porcentaje
            if ($dias_esperados > 0) {
                $porcentaje = ($total_asistencias / $dias_esperados) * 100;
                return min(100, round($porcentaje, 1));
            }

            return 0;
        } catch (Exception $e) {
            error_log("Error en calcularPorcentajeSimple: " . $e->getMessage());
            return 0;
        }
    }

    private function calcularDiasHabiles($inicio, $fin)
    {
        if (!$inicio || !$fin) return 0;

        try {
            $dias_habiles = 0;
            $intervalo = new DateInterval('P1D');

            // Asegurar que fin sea mayor que inicio
            if ($fin < $inicio) {
                $temp = $inicio;
                $inicio = $fin;
                $fin = $temp;
            }

            $periodo = new DatePeriod($inicio, $intervalo, $fin->modify('+1 day'));

            foreach ($periodo as $fecha) {
                $dia_semana = $fecha->format('N'); // 1 (lunes) a 7 (domingo)
                if ($dia_semana <= 5) { // Lunes a Viernes
                    $dias_habiles++;
                }
            }

            return $dias_habiles;
        } catch (Exception $e) {
            error_log("Error calculando días hábiles: " . $e->getMessage());
            return 0;
        }
    }

    private function buscarEstudiantePorUsuario($usuario_id)
    {
        require_once 'config/database.php';
        $db = Database::getInstance()->getConnection();

        try {
            $sql = "SELECT estuempleado FROM usuarios WHERE id = :id AND tipo = 3";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $usuario_id]);
            $result = $stmt->fetch();

            return $result['estuempleado'] ?? null;
        } catch (Exception $e) {
            error_log("Error buscando estudiante por usuario: " . $e->getMessage());
            return null;
        }
    }

    private function obtenerPracticasEstudiante($estudiante_id)
    {
        require_once 'config/database.php';
        $db = Database::getInstance()->getConnection();

        try {
            $sql = "SELECT 
                    p.*,
                    e.razon_social as empresa_nombre,
                    emp.direccion_fiscal,
                    emp.telefono as telefono_empresa,
                    emp.email as email_empresa,
                    emp.representante_legal,
                    em.apnom_emp as nombre_docente,
                    -- Calcular progreso porcentual
                    CASE 
                        WHEN p.total_horas > 0 THEN 
                            ROUND((COALESCE(p.horas_acumuladas, 0) / p.total_horas) * 100)
                        ELSE 0 
                    END as progreso_porcentaje,
                    -- Formatear fechas
                    DATE_FORMAT(p.fecha_inicio, '%d %b %Y') as fecha_inicio_formateada,
                    DATE_FORMAT(p.fecha_fin, '%d %b %Y') as fecha_fin_formateada,
                    -- Obtener días transcurridos
                    DATEDIFF(CURDATE(), p.fecha_inicio) as dias_transcurridos,
                    -- Obtener días restantes
                    CASE 
                        WHEN p.fecha_fin IS NOT NULL THEN 
                            GREATEST(0, DATEDIFF(p.fecha_fin, CURDATE()))
                        ELSE NULL 
                    END as dias_restantes_calc,
                    -- Contar asistencias de esta práctica
                    (SELECT COUNT(*) FROM asistencias a WHERE a.practicas = p.id) as total_asistencias
                FROM practicas p
                LEFT JOIN empresa e ON p.empresa = e.id
                LEFT JOIN empresa emp ON p.empresa = emp.id
                LEFT JOIN empleado em ON p.empleado = em.id
                WHERE p.estudiante = :estudiante_id
                ORDER BY 
                    CASE p.tipo_efsrt
                        WHEN 'modulo1' THEN 1
                        WHEN 'modulo2' THEN 2
                        WHEN 'modulo3' THEN 3
                        ELSE 4
                    END";

            $stmt = $db->prepare($sql);
            $stmt->execute([':estudiante_id' => $estudiante_id]);
            $practicas = $stmt->fetchAll();

            // 🎯 ORGANIZAR PRÁCTICAS POR MÓDULO
            $practicas_organizadas = [
                'modulo1' => null,
                'modulo2' => null,
                'modulo3' => null
            ];

            foreach ($practicas as $practica) {
                $tipo = $practica['tipo_efsrt'] ?? 'modulo1';
                $practicas_organizadas[$tipo] = $practica;
            }

            return $practicas_organizadas;
        } catch (Exception $e) {
            error_log("Error obteniendo prácticas: " . $e->getMessage());
            return [
                'modulo1' => null,
                'modulo2' => null,
                'modulo3' => null
            ];
        }
    }

    private function obtenerAsistenciasPractica($practica_id, $limit = 5)
    {
        require_once 'config/database.php';
        $db = Database::getInstance()->getConnection();

        try {
            $sql = "SELECT * FROM asistencias 
                    WHERE practicas = :practica_id 
                    ORDER BY fecha DESC 
                    LIMIT " . intval($limit);

            $stmt = $db->prepare($sql);
            $stmt->execute([':practica_id' => $practica_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo asistencias: " . $e->getMessage());
            return [];
        }
    }

    private function obtenerProgresoSemanal($modulo_activo, $semana = 3)
    {
        if (!$modulo_activo || !isset($modulo_activo['id'])) {
            return $this->getDatosSemanalesEjemplo($semana);
        }

        require_once 'config/database.php';
        $db = Database::getInstance()->getConnection();

        try {
            // Calcular fecha de inicio y fin de la semana solicitada
            $fechas_semana = $this->calcularFechasSemana($semana, $modulo_activo['fecha_inicio']);

            $sql = "SELECT 
                    DAYOFWEEK(fecha) as dia_semana,
                    SUM(horas_acumuladas) as horas_totales
                FROM asistencias 
                WHERE practicas = :practica_id 
                  AND fecha BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY DATE(fecha)
                ORDER BY fecha";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':practica_id' => $modulo_activo['id'],
                ':fecha_inicio' => $fechas_semana['inicio'],
                ':fecha_fin' => $fechas_semana['fin']
            ]);

            $resultados = $stmt->fetchAll();

            // Si hay datos reales, procesarlos
            if (!empty($resultados)) {
                return $this->procesarDatosSemanales($resultados, $fechas_semana);
            }

            // Si no hay datos, usar ejemplo
            return $this->getDatosSemanalesEjemplo($semana);
        } catch (Exception $e) {
            error_log("Error obteniendo progreso semanal: " . $e->getMessage());
            return $this->getDatosSemanalesEjemplo($semana);
        }
    }

    private function calcularFechasSemana($numero_semana, $fecha_inicio_modulo)
    {
        // Si no hay fecha de inicio, usar la semana actual
        if (!$fecha_inicio_modulo) {
            $hoy = new DateTime();
            $inicio_semana = clone $hoy;
            $inicio_semana->modify('monday this week');
            $fin_semana = clone $inicio_semana;
            $fin_semana->modify('+6 days');

            return [
                'inicio' => $inicio_semana->format('Y-m-d'),
                'fin' => $fin_semana->format('Y-m-d'),
                'numero' => $numero_semana,
                'rango' => $inicio_semana->format('d M') . ' - ' . $fin_semana->format('d M')
            ];
        }

        // Calcular semanas desde el inicio del módulo
        $inicio_modulo = new DateTime($fecha_inicio_modulo);
        $inicio_semana = clone $inicio_modulo;
        $inicio_semana->modify('+' . (($numero_semana - 1) * 7) . ' days');

        // Asegurar que sea lunes
        $dia_semana = $inicio_semana->format('N'); // 1=lunes, 7=domingo
        if ($dia_semana != 1) {
            $inicio_semana->modify('last monday');
        }

        $fin_semana = clone $inicio_semana;
        $fin_semana->modify('+6 days');

        return [
            'inicio' => $inicio_semana->format('Y-m-d'),
            'fin' => $fin_semana->format('Y-m-d'),
            'numero' => $numero_semana,
            'rango' => $inicio_semana->format('d M') . ' - ' . $fin_semana->format('d M')
        ];
    }

    private function procesarDatosSemanales($datos, $fechas_semana)
    {
        // Inicializar array con 6 días (lunes a sábado)
        $horas_por_dia = array_fill(1, 6, 0);

        foreach ($datos as $dato) {
            $dia = $dato['dia_semana']; // 1=domingo, 2=lunes, ..., 7=sábado

            // Convertir: domingo=1 -> lunes=0, martes=1, ..., sábado=5
            if ($dia == 1) continue; // Saltar domingo
            $indice = $dia - 2; // lunes=0, martes=1, ..., sábado=5

            if ($indice >= 0 && $indice <= 5) {
                $horas_por_dia[$indice + 1] = floatval($dato['horas_totales']);
            }
        }

        return [
            'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            'datos' => array_values($horas_por_dia),
            'total_semana' => array_sum($horas_por_dia),
            'promedio_diario' => round(array_sum($horas_por_dia) / 6, 1),
            'fechas' => $fechas_semana
        ];
    }

    private function getDatosSemanalesEjemplo($semana = 3)
    {
        // Datos de ejemplo basados en tu HTML estático
        $datos_ejemplo = [
            1 => [8.5, 7.5, 8.0, 6.5, 8.0, 4.0],  // Semana 1
            2 => [8.0, 8.5, 7.0, 8.0, 7.5, 3.5],  // Semana 2
            3 => [8.75, 7.75, 8.75, 3.5, 0, 0],   // Semana 3 (la que tenías)
            4 => [8.0, 8.0, 8.0, 8.0, 8.0, 0],    // Semana 4
            5 => [7.5, 8.0, 8.5, 7.0, 8.0, 4.5],  // Semana 5
            6 => [8.0, 8.0, 8.0, 8.0, 8.0, 0]     // Semana 6
        ];

        $datos = $datos_ejemplo[$semana] ?? $datos_ejemplo[3];

        // Calcular fechas de ejemplo
        $hoy = new DateTime();
        $inicio_semana = clone $hoy;
        $inicio_semana->modify('monday this week')->modify('-' . (($semana - 1) * 7) . ' days');
        $fin_semana = clone $inicio_semana;
        $fin_semana->modify('+6 days');

        return [
            'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            'datos' => $datos,
            'total_semana' => array_sum($datos),
            'promedio_diario' => round(array_sum($datos) / 6, 1),
            'fechas' => [
                'inicio' => $inicio_semana->format('Y-m-d'),
                'fin' => $fin_semana->format('Y-m-d'),
                'numero' => $semana,
                'rango' => $inicio_semana->format('d M') . ' - ' . $fin_semana->format('d M')
            ]
        ];
    }

    public function ver()
    {
        // 1. Verificar que sea estudiante
        if (!SessionHelper::isLoggedIn() || !SessionHelper::esEstudiante()) {
            header("Location: index.php?c=Login&a=index");
            exit;
        }

        // 2. Obtener ID de la práctica desde GET
        $practica_id = $_GET['id'] ?? null;

        if (!$practica_id) {
            header("Location: index.php?c=DashboardEstudiante&a=index&error=no_practica");
            exit;
        }

        // 3. Verificar que la práctica pertenezca al estudiante
        $usuario = SessionHelper::getUser();
        $estudiante_id = $usuario['estuempleado'] ?? null;

        if (!$this->verificarPropiedadPractica($practica_id, $estudiante_id)) {
            header("Location: index.php?c=DashboardEstudiante&a=index&error=acceso_denegado");
            exit;
        }

        // 4. Cargar modelos
        $practicaModel = new PracticaModel();
        $estudianteModel = new EstudianteModel();
        $empresaModel = new EmpresaModel();
        $asistenciaModel = new AsistenciaModel();

        // 5. Obtener datos completos
        $practica = $this->obtenerPracticaCompleta($practica_id);

        if (!$practica) {
            header("Location: index.php?c=DashboardEstudiante&a=index&error=practica_no_encontrada");
            exit;
        }

        // 6. Obtener datos del estudiante
        $estudiante = $estudianteModel->obtenerEstudianteCompleto($estudiante_id);

        // 7. Obtener datos de la empresa si existe
        $empresa = null;
        if ($practica['empresa']) {
            $empresa = $empresaModel->obtenerEmpresaPorId($practica['empresa']);
        }

        // 8. Obtener todas las asistencias de esta práctica
        $asistencias = $this->obtenerTodasAsistenciasPractica($practica_id);

        // 9. Calcular estadísticas detalladas
        $estadisticas_detalladas = $this->calcularEstadisticasDetalladas($practica, $asistencias);

        // 10. Preparar datos para la vista
        $data = [
            'practica' => $practica,
            'estudiante' => $estudiante,
            'empresa' => $empresa,
            'asistencias' => $asistencias,
            'estadisticas' => $estadisticas_detalladas,
            'modulo_nombre' => $this->getNombreModuloCompleto($practica['tipo_efsrt'] ?? 'modulo1')
        ];

        // 11. Cargar vista de detalles
        require_once 'views/InicioEstudiante/detalles.php';
    }

    private function verificarPropiedadPractica($practica_id, $estudiante_id)
    {
        require_once 'config/database.php';
        $db = Database::getInstance()->getConnection();

        try {
            $sql = "SELECT id FROM practicas WHERE id = :practica_id AND estudiante = :estudiante_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':practica_id' => $practica_id,
                ':estudiante_id' => $estudiante_id
            ]);

            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            error_log("Error verificando propiedad práctica: " . $e->getMessage());
            return false;
        }
    }

    private function obtenerPracticaCompleta($practica_id)
    {
        require_once 'config/database.php';
        $db = Database::getInstance()->getConnection();

        try {
            $sql = "SELECT 
                    p.*,
                    e.razon_social as empresa_nombre,
                    e.direccion_fiscal,
                    e.telefono as telefono_empresa,
                    e.email as email_empresa,
                    e.representante_legal,
                    emp.apnom_emp as nombre_docente,
                    emp.dni_emp as dni_docente,
                    -- Calcular progreso
                    CASE 
                        WHEN p.total_horas > 0 THEN 
                            ROUND((COALESCE(p.horas_acumuladas, 0) / p.total_horas) * 100)
                        ELSE 0 
                    END as progreso_porcentaje,
                    -- Formatear fechas
                    DATE_FORMAT(p.fecha_inicio, '%d de %M de %Y') as fecha_inicio_formateada,
                    DATE_FORMAT(p.fecha_fin, '%d de %M de %Y') as fecha_fin_formateada,
                    DATE_FORMAT(p.fecha_registro, '%d/%m/%Y %H:%i') as fecha_registro_formateada,
                    -- Calcular días
                    DATEDIFF(CURDATE(), p.fecha_inicio) as dias_transcurridos,
                    CASE 
                        WHEN p.fecha_fin IS NOT NULL THEN 
                            GREATEST(0, DATEDIFF(p.fecha_fin, CURDATE()))
                        ELSE NULL 
                    END as dias_restantes,
                    -- Información adicional
                    (SELECT COUNT(*) FROM asistencias a WHERE a.practicas = p.id) as total_asistencias,
                    (SELECT SUM(horas_acumuladas) FROM asistencias a WHERE a.practicas = p.id) as horas_totales_asistidas
                FROM practicas p
                LEFT JOIN empresa e ON p.empresa = e.id
                LEFT JOIN empleado emp ON p.empleado = emp.id
                WHERE p.id = :practica_id";

            $stmt = $db->prepare($sql);
            $stmt->execute([':practica_id' => $practica_id]);

            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Error obteniendo práctica completa: " . $e->getMessage());
            return null;
        }
    }

    private function obtenerTodasAsistenciasPractica($practica_id)
    {
        require_once 'config/database.php';
        $db = Database::getInstance()->getConnection();

        try {
            $sql = "SELECT 
                    a.*,
                    DATE_FORMAT(a.fecha, '%d/%m/%Y') as fecha_formateada,
                    DATE_FORMAT(a.hora_entrada, '%H:%i') as hora_entrada_formateada,
                    DATE_FORMAT(a.hora_salida, '%H:%i') as hora_salida_formateada,
                    -- Calcular duración en formato legible
                    CONCAT(
                        FLOOR(a.horas_acumuladas), 'h ',
                        ROUND((a.horas_acumuladas - FLOOR(a.horas_acumuladas)) * 60), 'min'
                    ) as duracion_formateada,
                    -- Determinar tipo de jornada
                    CASE 
                        WHEN a.horas_acumuladas >= 8 THEN 'Jornada completa'
                        WHEN a.horas_acumuladas >= 4 THEN 'Media jornada'
                        ELSE 'Jornada reducida'
                    END as tipo_jornada
                FROM asistencias a
                WHERE a.practicas = :practica_id
                ORDER BY a.fecha DESC, a.hora_entrada DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute([':practica_id' => $practica_id]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error obteniendo asistencias: " . $e->getMessage());
            return [];
        }
    }

    private function calcularEstadisticasDetalladas($practica, $asistencias)
    {
        $estadisticas = [
            'total_asistencias' => count($asistencias),
            'horas_totales' => 0,
            'horas_promedio' => 0,
            'dias_asistidos' => 0,
            'porcentaje_asistencia' => 0,
            'jornadas_completas' => 0,
            'jornadas_medias' => 0,
            'jornadas_reducidas' => 0
        ];

        // Calcular horas totales y tipos de jornada
        foreach ($asistencias as $asistencia) {
            $horas = $asistencia['horas_acumuladas'] ?? 0;
            $estadisticas['horas_totales'] += $horas;

            if ($horas >= 8) {
                $estadisticas['jornadas_completas']++;
            } elseif ($horas >= 4) {
                $estadisticas['jornadas_medias']++;
            } else {
                $estadisticas['jornadas_reducidas']++;
            }
        }

        // Calcular promedio
        if ($estadisticas['total_asistencias'] > 0) {
            $estadisticas['horas_promedio'] = round($estadisticas['horas_totales'] / $estadisticas['total_asistencias'], 1);
        }

        // Calcular porcentaje de asistencia
        if ($practica['fecha_inicio']) {
            $fecha_inicio = new DateTime($practica['fecha_inicio']);
            $hoy = new DateTime();

            // Días hábiles hasta hoy
            $dias_habiles = $this->calcularDiasHabiles($fecha_inicio, $hoy);

            if ($dias_habiles > 0) {
                $estadisticas['porcentaje_asistencia'] = round(($estadisticas['total_asistencias'] / $dias_habiles) * 100, 1);
            }
        }

        return $estadisticas;
    }

    private function getNombreModuloCompleto($tipo_efsrt)
    {
        $modulos = [
            'modulo1' => 'Módulo 1 - Experiencias Formativas en Situaciones Reales de Trabajo (EFSRT)',
            'modulo2' => 'Módulo 2 - Prácticas Pre-Profesionales',
            'modulo3' => 'Módulo 3 - Prácticas Pre-Profesionales'
        ];
        return $modulos[$tipo_efsrt] ?? 'Módulo de Prácticas';
    }
}
