<?php
require_once 'models/EstudianteModel.php';
require_once 'models/PracticaModel.php';
require_once 'models/AsistenciaModel.php';
require_once 'models/EmpresaModel.php';
require_once 'helpers/SessionHelper.php';

class AsistenciaEstudianteController
{
    private $estudianteModel;
    private $practicaModel;
    private $asistenciaModel;
    private $empresaModel;
    private $db;

    public function __construct()
    {
        // Verificar login y que sea estudiante
        SessionHelper::init();
        if (!SessionHelper::isLoggedIn()) {
            header("Location: index.php?c=Login&a=index");
            exit;
        }

        $usuario = SessionHelper::get('usuario');
        if ($usuario['rol'] !== 'estudiante') {
            header("Location: index.php?c=DashboardEstudiante&a=index&error=acceso_denegado");
            exit;
        }

        $this->estudianteModel = new EstudianteModel();
        $this->practicaModel = new PracticaModel();
        $this->asistenciaModel = new AsistenciaModel();
        $this->empresaModel = new EmpresaModel();

        // Obtener conexión a la base de datos
        require_once 'config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    // En AsistenciaEstudianteController.php, corregir el método index()
    public function index()
    {
        try {
            // Obtener datos del estudiante desde sesión
            $usuario = SessionHelper::get('usuario');
            $estudiante_id = $usuario['estuempleado'] ?? null;

            if (!$estudiante_id) {
                throw new Exception("No se encontró el ID del estudiante en sesión");
            }

            // Obtener datos completos del estudiante
            $estudiante = $this->estudianteModel->obtenerEstudianteCompleto($estudiante_id);

            if (!$estudiante) {
                throw new Exception("Estudiante no encontrado en la base de datos");
            }

            // Obtener módulos del estudiante - VERIFICAR QUE TRAIGA LOS CORRECTOS
            $modulos_raw = $this->asistenciaModel->obtenerModulosDetallados($estudiante_id);

            error_log("📊 Módulos obtenidos para estudiante $estudiante_id:");
            error_log(print_r($modulos_raw, true));

            // Reorganizar módulos para la vista
            $estadisticas_modulos = [];
            foreach ($modulos_raw as $modulo) {
                // Asegurar que el estado sea correcto
                if ($modulo['practica_id'] && $modulo['estado'] === 'pendiente') {
                    // Si tiene práctica_id pero está como pendiente, cambiarlo a en_curso
                    $modulo['estado'] = 'en_curso';
                    error_log("🔄 Corrigiendo estado de módulo {$modulo['id']} de 'pendiente' a 'en_curso'");
                }

                $estadisticas_modulos[$modulo['id']] = [
                    'nombre' => $modulo['nombre'],
                    'horas_acumuladas' => $modulo['horas_acumuladas'],
                    'horas_requeridas' => $modulo['horas_requeridas'],
                    'porcentaje' => $modulo['porcentaje'],
                    'estado' => $modulo['estado'],
                    'practica_id' => $modulo['practica_id'],
                    'fecha_inicio' => $modulo['fecha_inicio'],
                    'fecha_fin' => $modulo['fecha_fin'],
                    'area_ejecucion' => $modulo['area_ejecucion']
                ];
            }

            // Obtener prácticas activas
            $practicas_activas = [];
            $sql_practicas = "SELECT p.*, e.razon_social, emp.apnom_emp as docente_nombre 
                     FROM practicas p 
                     LEFT JOIN empresa e ON p.empresa = e.id 
                     LEFT JOIN empleado emp ON p.empleado = emp.id 
                     WHERE p.estudiante = :estudiante_id 
                     ORDER BY p.tipo_efsrt";

            $stmt_practicas = $this->db->prepare($sql_practicas);
            $stmt_practicas->execute([':estudiante_id' => $estudiante_id]);
            $practicas_raw = $stmt_practicas->fetchAll(PDO::FETCH_ASSOC);

            error_log("📊 Prácticas encontradas para estudiante $estudiante_id: " . count($practicas_raw));

            // Organizar prácticas por módulo
            foreach ($practicas_raw as $practica) {
                $practicas_activas[$practica['tipo_efsrt']] = $practica;

                // DEBUG: Mostrar estado de cada práctica
                error_log("🏢 Práctica ID {$practica['id']} - Módulo: {$practica['tipo_efsrt']} - Estado BD: {$practica['estado']}");

                // Si la práctica está en NULL o 'Pendiente', actualizarla a 'En curso'
                if (empty($practica['estado']) || $practica['estado'] === 'Pendiente') {
                    $sqlUpdate = "UPDATE practicas SET estado = 'En curso' WHERE id = :practica_id";
                    $stmtUpdate = $this->db->prepare($sqlUpdate);
                    $stmtUpdate->execute([':practica_id' => $practica['id']]);
                    error_log("✅ Actualizada práctica {$practica['id']} a estado 'En curso'");
                }
            }

            // Obtener asistencias por práctica
            $asistencias_por_practica = [];
            foreach ($practicas_raw as $practica) {
                $asistencias = $this->asistenciaModel->obtenerPorPractica($practica['id']);
                $asistencias_por_practica[$practica['tipo_efsrt']] = $asistencias;
            }

            // Determinar módulo activo (priorizar módulos en curso)
            $modulo_activo = $this->determinarModuloActivo($estadisticas_modulos);

            error_log("🎯 Módulo activo determinado: $modulo_activo");

            // Cargar vista
            require_once 'views/layouts/header.php';
            require_once 'views/asistencia_estudiante/index.php';
            require_once 'views/layouts/footer.php';
        } catch (Exception $e) {
            error_log("Error en AsistenciaEstudianteController::index: " . $e->getMessage());

            require_once 'views/layouts/header.php';
            echo '<div class="p-6 ml-64 mt-16">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-800">Error al cargar asistencias</h3>
                        <p class="text-red-700">' . htmlspecialchars($e->getMessage()) . '</p>
                        <a href="index.php?c=DashboardEstudiante&a=index" class="inline-block mt-3 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>';
            require_once 'views/layouts/footer.php';
        }
    }

    // Agregar este método a AsistenciaEstudianteController.php
    public function guardar()
    {
        header('Content-Type: application/json');

        try {
            // Verificar que sea POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }

            // Obtener datos del estudiante
            $usuario = SessionHelper::get('usuario');
            $estudiante_id = $usuario['estuempleado'] ?? null;

            if (!$estudiante_id) {
                throw new Exception('No se encontró el ID del estudiante');
            }

            // Obtener y validar datos del formulario
            $datos = $this->validarDatosAsistencia($_POST, $estudiante_id);

            // Registrar la asistencia usando el modelo
            $resultado = $this->asistenciaModel->registrar($datos);

            if ($resultado) {
                // Obtener horas acumuladas actualizadas
                $practica_id = $datos['practica_id'];
                $horas_acumuladas = $this->asistenciaModel->obtenerHorasAcumuladas($practica_id);

                echo json_encode([
                    'success' => true,
                    'message' => 'Asistencia registrada correctamente',
                    'data' => [
                        'horas_acumuladas' => $horas_acumuladas
                    ]
                ]);
            } else {
                throw new Exception('Error al registrar la asistencia');
            }
        } catch (Exception $e) {
            error_log("Error en AsistenciaEstudianteController::guardar: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function registrar()
    {
        header('Content-Type: application/json');

        try {
            // DEBUG detallado
            error_log("📥 ======= REGISTRO INICIADO =======");
            error_log("📥 Método: " . $_SERVER['REQUEST_METHOD']);
            error_log("📥 POST recibido:");
            error_log(print_r($_POST, true));

            // 1. Verificar que sea POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }

            // 2. Obtener estudiante de SESIÓN
            $usuario = SessionHelper::get('usuario');
            if (!$usuario) {
                throw new Exception('No hay sesión activa');
            }

            $estudiante_id = $usuario['estuempleado'] ?? null;
            if (!$estudiante_id) {
                throw new Exception('No se encontró el ID del estudiante en sesión');
            }

            error_log("👤 Estudiante en sesión ID: $estudiante_id");

            // 3. Validar CSRF token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== SessionHelper::getCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }

            // 4. Validar datos (esto ya verifica que la práctica sea del estudiante)
            $datos = $this->validarDatosAsistencia($_POST, $estudiante_id);

            error_log("✅ Datos validados correctamente");
            error_log(print_r($datos, true));

            // 5. Registrar usando el modelo
            $asistencia_id = $this->asistenciaModel->registrar($datos);

            if ($asistencia_id) {
                // 6. Obtener horas actualizadas
                $horas_acumuladas = $this->asistenciaModel->obtenerHorasAcumuladas($datos['practica_id']);

                error_log("🎉 Asistencia registrada exitosamente. ID: $asistencia_id");
                error_log("📊 Horas acumuladas totales: $horas_acumuladas");

                echo json_encode([
                    'success' => true,
                    'message' => 'Asistencia registrada correctamente',
                    'data' => [
                        'asistencia_id' => $asistencia_id,
                        'horas_acumuladas' => $horas_acumuladas,
                        'practica_id' => $datos['practica_id']
                    ]
                ]);
            } else {
                throw new Exception('Error al guardar en la base de datos');
            }
        } catch (Exception $e) {
            error_log("🔥 ERROR en registrar(): " . $e->getMessage());
            error_log("🔥 Stack: " . $e->getTraceAsString());

            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'debug' => [
                    'estudiante_id' => $estudiante_id ?? null,
                    'post_data' => $_POST
                ]
            ]);
        }
    }

    public function obtener()
    {
        header('Content-Type: application/json');

        try {
            $asistencia_id = $_GET['id'] ?? null;

            if (!$asistencia_id) {
                throw new Exception('ID de asistencia no proporcionado');
            }

            // Obtener asistencia usando el modelo
            $asistencia = $this->asistenciaModel->obtenerPorId($asistencia_id);

            if (!$asistencia) {
                throw new Exception('Asistencia no encontrada');
            }

            // Verificar que pertenezca al estudiante actual
            $usuario = SessionHelper::get('usuario');
            $estudiante_id = $usuario['estuempleado'] ?? null;

            if ($asistencia['estudiante'] != $estudiante_id) {
                throw new Exception('No tienes permiso para acceder a esta asistencia');
            }

            echo json_encode([
                'success' => true,
                'data' => $asistencia
            ]);
        } catch (Exception $e) {
            error_log("Error en AsistenciaEstudianteController::obtener: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function actualizar()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }

            $asistencia_id = $_POST['id'] ?? null;

            if (!$asistencia_id) {
                throw new Exception('ID de asistencia no proporcionado');
            }

            // Verificar que la asistencia exista y pertenezca al estudiante
            $asistencia = $this->asistenciaModel->obtenerPorId($asistencia_id);
            $usuario = SessionHelper::get('usuario');
            $estudiante_id = $usuario['estuempleado'] ?? null;

            if (!$asistencia || $asistencia['estudiante'] != $estudiante_id) {
                throw new Exception('No tienes permiso para editar esta asistencia');
            }

            // Obtener y validar datos
            $datos = $this->validarDatosAsistencia($_POST, $estudiante_id);
            $datos['id'] = $asistencia_id;

            // Actualizar en base de datos usando PDO directamente
            $sql = "UPDATE asistencias SET 
                    fecha = :fecha,
                    hora_entrada = :hora_entrada,
                    hora_salida = :hora_salida,
                    horas_acumuladas = :horas_acumuladas,
                    actividad = :actividad
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $params = [
                ':fecha' => $datos['fecha'],
                ':hora_entrada' => $datos['hora_entrada'],
                ':hora_salida' => $datos['hora_salida'],
                ':horas_acumuladas' => $datos['horas_acumuladas'],
                ':actividad' => $datos['actividad'],
                ':id' => $asistencia_id
            ];

            $resultado = $stmt->execute($params);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Asistencia actualizada correctamente'
                ]);
            } else {
                throw new Exception('Error al actualizar la asistencia');
            }
        } catch (Exception $e) {
            error_log("Error en AsistenciaEstudianteController::actualizar: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function eliminar()
    {
        header('Content-Type: application/json');

        try {
            $asistencia_id = $_POST['id'] ?? null;

            if (!$asistencia_id) {
                throw new Exception('ID de asistencia no proporcionado');
            }

            // Verificar que la asistencia exista y pertenezca al estudiante
            $asistencia = $this->asistenciaModel->obtenerPorId($asistencia_id);
            $usuario = SessionHelper::get('usuario');
            $estudiante_id = $usuario['estuempleado'] ?? null;

            if (!$asistencia || $asistencia['estudiante'] != $estudiante_id) {
                throw new Exception('No tienes permiso para eliminar esta asistencia');
            }

            // Eliminar asistencia usando el modelo
            $resultado = $this->asistenciaModel->eliminar($asistencia_id);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Asistencia eliminada correctamente'
                ]);
            } else {
                throw new Exception('Error al eliminar la asistencia');
            }
        } catch (Exception $e) {
            error_log("Error en AsistenciaEstudianteController::eliminar: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function obtenerPorMes()
    {
        header('Content-Type: application/json');

        try {
            $practica_id = $_GET['practica_id'] ?? null;
            $mes = $_GET['mes'] ?? date('m');
            $anio = $_GET['anio'] ?? date('Y');

            if (!$practica_id) {
                throw new Exception('ID de práctica no proporcionado');
            }

            // Verificar que la práctica pertenezca al estudiante
            $sql = "SELECT estudiante FROM practicas WHERE id = :practica_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':practica_id' => $practica_id]);
            $practica = $stmt->fetch(PDO::FETCH_ASSOC);

            $usuario = SessionHelper::get('usuario');
            $estudiante_id = $usuario['estuempleado'] ?? null;

            if (!$practica || $practica['estudiante'] != $estudiante_id) {
                throw new Exception('No tienes permiso para acceder a estas asistencias');
            }

            // Obtener asistencias del mes
            $fecha_inicio = "$anio-$mes-01";
            $fecha_fin = date('Y-m-t', strtotime($fecha_inicio));

            $sql = "SELECT * FROM asistencias 
                    WHERE practicas = :practica_id 
                    AND fecha BETWEEN :fecha_inicio AND :fecha_fin
                    ORDER BY fecha ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':practica_id' => $practica_id,
                ':fecha_inicio' => $fecha_inicio,
                ':fecha_fin' => $fecha_fin
            ]);

            $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $asistencias
            ]);
        } catch (Exception $e) {
            error_log("Error en AsistenciaEstudianteController::obtenerPorMes: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // ==============================
    // MÉTODOS PRIVADOS AUXILIARES
    // ==============================

    private function determinarModuloActivo($modulos)
    {
        error_log("🔍 Determinando módulo activo entre: " . implode(', ', array_keys($modulos)));

        // 1. Prioridad: Módulos en curso
        foreach ($modulos as $modulo_id => $modulo) {
            if ($modulo['estado'] === 'en_curso') {
                error_log("✅ Módulo activo determinado: $modulo_id (en_curso)");
                return $modulo_id;
            }
        }

        // 2. Si no hay en curso, buscar completados
        foreach ($modulos as $modulo_id => $modulo) {
            if ($modulo['estado'] === 'completado') {
                error_log("✅ Módulo activo determinado: $modulo_id (completado)");
                return $modulo_id;
            }
        }

        // 3. Si no hay completados, buscar cualquier práctica existente
        foreach ($modulos as $modulo_id => $modulo) {
            if ($modulo['practica_id']) {
                error_log("✅ Módulo activo determinado: $modulo_id (tiene práctica)");
                return $modulo_id;
            }
        }

        // 4. Por defecto, módulo 1
        error_log("✅ Módulo activo por defecto: modulo1");
        return 'modulo1';
    }

    private function validarDatosAsistencia($postData, $estudiante_id)
    {
        error_log("🔍 Validando datos para estudiante ID: $estudiante_id");

        // Validar campos requeridos
        $camposRequeridos = ['practica_id', 'fecha', 'hora_entrada', 'hora_salida', 'actividad'];
        foreach ($camposRequeridos as $campo) {
            if (empty($postData[$campo])) {
                error_log("❌ Campo requerido faltante: $campo");
                throw new Exception("El campo {$campo} es requerido");
            }
        }

        $practica_id = $postData['practica_id'];
        error_log("🔍 Validando práctica ID: $practica_id");

        // CRÍTICO: Verificar que la práctica PERTENEZCA al estudiante
        $sql = "SELECT id, estudiante, estado, tipo_efsrt, modulo 
            FROM practicas 
            WHERE id = :practica_id AND estudiante = :estudiante_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':practica_id' => $practica_id,
            ':estudiante_id' => $estudiante_id
        ]);

        $practica = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$practica) {
            throw new Exception('La práctica no existe o no tienes permiso para acceder a ella');
        }

        // ==============================================
        // CORRECCIÓN CRÍTICA PARA LA FECHA
        // ==============================================

        $fecha_input = trim($postData['fecha']);
        error_log("📅 Fecha recibida del formulario: '$fecha_input'");

        // DEPURACIÓN: Ver formato exacto
        error_log("📅 Longitud de fecha: " . strlen($fecha_input));
        error_log("📅 Caracteres fecha: " . bin2hex($fecha_input));

        // Opción 1: Si viene en formato con slashes (DD/MM/YYYY)
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $fecha_input, $matches)) {
            $dia = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $mes = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $anio = $matches[3];
            $fecha = "$anio-$mes-$dia";
            error_log("🔄 Fecha convertida de DD/MM/YYYY: $fecha_input -> $fecha");
        }
        // Opción 2: Si viene en formato con guiones (YYYY-MM-DD)
        elseif (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $fecha_input, $matches)) {
            $anio = $matches[1];
            $mes = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $dia = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
            $fecha = "$anio-$mes-$dia";
            error_log("🔄 Fecha ya en formato YYYY-MM-DD: $fecha");
        }
        // Opción 3: Formato no reconocido
        else {
            error_log("❌ Formato de fecha no reconocido: $fecha_input");
            throw new Exception('Formato de fecha no válido. Use DD/MM/YYYY o YYYY-MM-DD');
        }

        // Validar que la fecha sea real
        if (!checkdate((int)$mes, (int)$dia, (int)$anio)) {
            throw new Exception('Fecha no válida. Verifica día, mes y año');
        }

        // Crear fecha usando DateTime (evita problemas de timezone)
        $dateTime = DateTime::createFromFormat('Y-m-d', $fecha, new DateTimeZone('UTC'));
        if (!$dateTime) {
            throw new Exception('Error al procesar la fecha');
        }

        $fecha_final = $dateTime->format('Y-m-d');
        error_log("📅 Fecha final para guardar (UTC): $fecha_final");

        // Validar que no sea fecha futura (usar fecha actual en UTC)
        $hoy_utc = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d');
        error_log("📅 Hoy (UTC): $hoy_utc");

        if ($fecha_final > $hoy_utc) {
            throw new Exception('No puedes registrar asistencias con fecha futura');
        }

        // ==============================================
        // VALIDACIÓN DE HORAS
        // ==============================================

        $hora_entrada = trim($postData['hora_entrada']);
        $hora_salida = trim($postData['hora_salida']);

        // Convertir horas a formato 24h con segundos
        $hora_entrada_24 = $this->convertirHoraFormato24($hora_entrada);
        $hora_salida_24 = $this->convertirHoraFormato24($hora_salida);

        if (!$hora_entrada_24 || !$hora_salida_24) {
            throw new Exception('Formato de hora no válido');
        }

        // Calcular diferencia de horas
        $entrada_ts = strtotime("2000-01-01 $hora_entrada_24");
        $salida_ts = strtotime("2000-01-01 $hora_salida_24");

        if ($salida_ts <= $entrada_ts) {
            throw new Exception('La hora de salida debe ser mayor a la hora de entrada');
        }

        $diferencia = $salida_ts - $entrada_ts;
        $horas_acumuladas = round($diferencia / 3600, 2);

        if ($horas_acumuladas > 12) {
            throw new Exception('La jornada no puede exceder las 12 horas diarias');
        }

        error_log("✅ Validación completada:");
        error_log("  - Práctica ID: $practica_id");
        error_log("  - Fecha input: $fecha_input");
        error_log("  - Fecha a guardar: $fecha_final");
        error_log("  - Hora entrada: $hora_entrada -> $hora_entrada_24");
        error_log("  - Hora salida: $hora_salida -> $hora_salida_24");
        error_log("  - Horas calculadas: $horas_acumuladas");

        return [
            'practica_id' => $practica_id,
            'fecha' => $fecha_final,  // Fecha en formato YYYY-MM-DD (UTC)
            'hora_entrada' => $hora_entrada_24,
            'hora_salida' => $hora_salida_24,
            'horas_acumuladas' => $horas_acumuladas,
            'actividad' => htmlspecialchars($postData['actividad'])
        ];
    }

    // Agregar este método auxiliar a la clase
    private function convertirHoraFormato24($hora_str)
    {
        $hora_str = trim(strtolower($hora_str));

        // Si ya tiene formato HH:MM:SS
        if (preg_match('/^(\d{1,2}):(\d{2}):(\d{2})$/', $hora_str, $matches)) {
            $hora = (int)$matches[1];
            $min = $matches[2];
            $seg = $matches[3];
            return sprintf('%02d:%02d:%02d', $hora, $min, $seg);
        }

        // Si tiene formato HH:MM
        if (preg_match('/^(\d{1,2}):(\d{2})$/', $hora_str, $matches)) {
            $hora = (int)$matches[1];
            $min = $matches[2];
            return sprintf('%02d:%02d:00', $hora, $min);
        }

        // Si tiene formato con AM/PM (ej: 8.15am, 2.25pm)
        if (preg_match('/^(\d{1,2})(?:\.(\d{1,2}))?\s*(am|pm)$/', $hora_str, $matches)) {
            $hora = (int)$matches[1];
            $min = isset($matches[2]) ? str_pad($matches[2], 2, '0', STR_PAD_LEFT) : '00';
            $ampm = $matches[3];

            // Convertir a 24h
            if ($ampm === 'pm' && $hora < 12) {
                $hora += 12;
            } elseif ($ampm === 'am' && $hora === 12) {
                $hora = 0;
            }

            return sprintf('%02d:%s:00', $hora, $min);
        }

        return null;
    }
}
