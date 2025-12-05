<?php
// controllers/AsistenciaController.php - VERSIÓN FUNCIONAL
require_once __DIR__ . '/../models/EstudianteModel.php';
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../models/AsistenciaModel.php';
require_once __DIR__ . '/../models/EmpresaModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class AsistenciaController
{
    private $estudianteModel;
    private $practicaModel;
    private $asistenciaModel;
    private $empresaModel;

    public function __construct()
    {
        // Verificar login
        SessionHelper::init();
        if (!SessionHelper::isLoggedIn()) {
            header("Location: index.php?c=Login&a=index");
            exit;
        }

        $this->estudianteModel = new EstudianteModel();
        $this->practicaModel = new PracticaModel();
        $this->asistenciaModel = new AsistenciaModel();
        $this->empresaModel = new EmpresaModel();
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        // Cargar vista del dashboard
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/asistencia/dashboard.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // API: Obtener estudiantes con sus módulos
    public function api_estudiantes()
    {
        header('Content-Type: application/json');

        try {
            $filtros = [
                'busqueda' => $_GET['busqueda'] ?? '',
                'modulo' => $_GET['modulo'] ?? 'all',
                'estado' => $_GET['estado'] ?? 'all'
            ];

            // Obtener estudiantes con sus módulos
            $estudiantes = $this->asistenciaModel->obtenerEstudiantesConModulos();

            // Aplicar filtros
            $estudiantes_filtrados = $this->aplicarFiltrosEstudiantes($estudiantes, $filtros);

            // Obtener estadísticas
            $estadisticas = $this->asistenciaModel->obtenerEstadisticasDashboard();

            echo json_encode([
                'success' => true,
                'data' => [
                    'estudiantes' => $estudiantes_filtrados,
                    'estadisticas' => $estadisticas,
                    'total_estudiantes' => count($estudiantes_filtrados)
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error en api_estudiantes: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function aplicarFiltrosEstudiantes($estudiantes, $filtros)
    {
        if (empty($estudiantes)) return [];

        $resultados = $estudiantes;

        if (!empty($filtros['busqueda'])) {
            $busqueda = strtolower($filtros['busqueda']);
            $resultados = array_filter($resultados, function ($estudiante) use ($busqueda) {
                $nombreCompleto = strtolower($estudiante['nombre_completo'] ?? '');
                $dni = strtolower($estudiante['dni_est'] ?? '');
                return strpos($nombreCompleto, $busqueda) !== false ||
                    strpos($dni, $busqueda) !== false;
            });
        }

        if ($filtros['modulo'] !== 'all') {
            $modulo_buscado = $filtros['modulo'];
            $resultados = array_filter($resultados, function ($estudiante) use ($modulo_buscado) {
                if (empty($estudiante['modulos'])) return false;
                foreach ($estudiante['modulos'] as $modulo) {
                    if ($modulo['id'] === $modulo_buscado) {
                        return true;
                    }
                }
                return false;
            });
        }

        if ($filtros['estado'] !== 'all') {
            $estado_buscado = $filtros['estado'];
            $resultados = array_filter($resultados, function ($estudiante) use ($estado_buscado) {
                if (empty($estudiante['modulos'])) return false;
                foreach ($estudiante['modulos'] as $modulo) {
                    if ($modulo['estado'] === $estado_buscado) {
                        return true;
                    }
                }
                return false;
            });
        }

        return array_values($resultados);
    }

    // API: Obtener detalles de un estudiante
    public function api_detalle_estudiante()
    {
        header('Content-Type: application/json');

        try {
            $estudiante_id = $_GET['id'] ?? null;

            if (!$estudiante_id) {
                throw new Exception('ID de estudiante no proporcionado');
            }

            // Obtener estudiante
            $estudiante = $this->estudianteModel->obtenerEstudiantePorId($estudiante_id);

            if (!$estudiante) {
                throw new Exception('Estudiante no encontrado');
            }

            // Obtener módulos
            $modulos = $this->asistenciaModel->obtenerModulosDetallados($estudiante_id);

            // Formatear nombre completo
            $estudiante['nombre_completo'] = $estudiante['ap_est'] . ' ' .
                ($estudiante['am_est'] ?? '') .
                ', ' . $estudiante['nom_est'];

            echo json_encode([
                'success' => true,
                'data' => [
                    'estudiante' => $estudiante,
                    'modulos' => $modulos
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error en api_detalle_estudiante: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // API: Obtener detalles de un módulo
    public function api_detalle_modulo()
    {
        header('Content-Type: application/json');

        try {
            $practica_id = $_GET['practica_id'] ?? null;

            if (!$practica_id) {
                throw new Exception('ID de práctica no proporcionado');
            }

            $practica = $this->practicaModel->obtenerPracticaPorId($practica_id);

            if (!$practica) {
                throw new Exception('Práctica no encontrada');
            }

            $asistencias = $this->asistenciaModel->obtenerPorPractica($practica_id);
            $horas_acumuladas = $this->asistenciaModel->obtenerHorasAcumuladas($practica_id);

            // Obtener información del estudiante
            $estudiante = $this->estudianteModel->obtenerEstudiantePorId($practica['estudiante']);
            $estudiante['nombre_completo'] = $estudiante['ap_est'] . ' ' .
                ($estudiante['am_est'] ?? '') .
                ', ' . $estudiante['nom_est'];

            // Obtener información de la empresa
            $empresa = $this->empresaModel->obtenerEmpresaPorId($practica['empresa']);

            echo json_encode([
                'success' => true,
                'data' => [
                    'practica' => $practica,
                    'asistencias' => $asistencias,
                    'horas_acumuladas' => $horas_acumuladas,
                    'porcentaje' => $practica['total_horas'] > 0 ?
                        min(100, round(($horas_acumuladas / $practica['total_horas']) * 100)) : 0,
                    'estudiante' => $estudiante,
                    'empresa' => $empresa
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error en api_detalle_modulo: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // API: Registrar nueva asistencia
    public function api_registrar_asistencia()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        try {
            $datos = [
                'practica_id' => $_POST['practica_id'] ?? null,
                'fecha' => $_POST['fecha'] ?? null,
                'hora_entrada' => $_POST['hora_entrada'] ?? null,
                'hora_salida' => $_POST['hora_salida'] ?? null,
                'actividad' => $_POST['actividad'] ?? ''
            ];

            // Validar campos requeridos
            $camposRequeridos = ['practica_id', 'fecha', 'hora_entrada', 'hora_salida'];
            foreach ($camposRequeridos as $campo) {
                if (empty($datos[$campo])) {
                    throw new Exception("El campo {$campo} es requerido");
                }
            }

            // Calcular horas acumuladas
            $horas_acumuladas = $this->calcularHoras($datos['hora_entrada'], $datos['hora_salida']);
            if ($horas_acumuladas <= 0) {
                throw new Exception('La hora de salida debe ser mayor a la hora de entrada');
            }

            $datos['horas_acumuladas'] = $horas_acumuladas;

            // Registrar la asistencia
            $resultado = $this->asistenciaModel->registrar($datos);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Asistencia registrada correctamente',
                    'horas_acumuladas' => $horas_acumuladas
                ]);
            } else {
                throw new Exception('Error al registrar la asistencia en la base de datos');
            }
        } catch (Exception $e) {
            error_log("Error en api_registrar_asistencia: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function calcularHoras($entrada, $salida)
    {
        $entrada_timestamp = strtotime($entrada);
        $salida_timestamp = strtotime($salida);

        if ($entrada_timestamp === false || $salida_timestamp === false) {
            return 0;
        }

        $diferencia = $salida_timestamp - $entrada_timestamp;
        return round($diferencia / 3600, 1);
    }

    // API: Generar reporte
    public function api_generar_reporte()
    {
        header('Content-Type: application/json');

        try {
            $filtros = [
                'fecha_inicio' => $_POST['fecha_inicio'] ?? date('Y-m-01'),
                'fecha_fin' => $_POST['fecha_fin'] ?? date('Y-m-t'),
                'estudiante_id' => $_POST['estudiante_id'] ?? null,
                'modulo' => $_POST['modulo'] ?? 'all'
            ];

            // Obtener reporte
            $reporte = $this->asistenciaModel->obtenerReporte(
                $filtros['fecha_inicio'],
                $filtros['fecha_fin'],
                $filtros['estudiante_id']
            );

            echo json_encode([
                'success' => true,
                'data' => [
                    'reporte' => $reporte,
                    'filtros' => $filtros,
                    'total_registros' => count($reporte),
                    'total_horas' => array_sum(array_column($reporte, 'horas_acumuladas'))
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error en api_generar_reporte: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // API: Obtener estudiantes para select
    public function api_estudiantes_select()
    {
        header('Content-Type: application/json');

        try {
            $estudiantes = $this->estudianteModel->obtenerEstudiantes();

            $estudiantes_select = array_map(function ($estudiante) {
                return [
                    'id' => $estudiante['id'],
                    'text' => trim($estudiante['ap_est'] . ' ' .
                        ($estudiante['am_est'] ?? '') . ', ' .
                        $estudiante['nom_est'] . ' - ' . $estudiante['dni_est'])
                ];
            }, $estudiantes);

            echo json_encode([
                'success' => true,
                'data' => $estudiantes_select
            ]);
        } catch (Exception $e) {
            error_log("Error en api_estudiantes_select: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
