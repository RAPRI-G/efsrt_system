<?php
require_once 'models/DocumentoModel.php';
require_once 'models/EstudianteModel.php';
require_once 'models/PracticaModel.php';
require_once 'models/EmpresaModel.php';

class DocumentoController
{
    private $documentoModel;
    private $estudianteModel;
    private $practicaModel;
    private $empresaModel;

    public function __construct()
    {
        $this->documentoModel = new DocumentoModel();
        $this->estudianteModel = new EstudianteModel();
        $this->practicaModel = new PracticaModel();
        $this->empresaModel = new EmpresaModel();
    }

    public function index()
    {
        // Obtener filtros de la URL
        $filtros = [
            'tipo_documento' => $_GET['tipo'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'estudiante_id' => $_GET['estudiante_id'] ?? '',
            'modulo' => $_GET['modulo'] ?? '',
            'busqueda' => $_GET['busqueda'] ?? ''
        ];

        // Obtener datos
        $documentos = $this->documentoModel->obtenerDocumentos($filtros);
        $estadisticas = $this->documentoModel->obtenerEstadisticasDocumentos();
        $estudiantes = $this->estudianteModel->obtenerEstudiantesConModulos();

        // Pasar datos a la vista
        $data = [
            'titulo' => 'Gestión de Documentos',
            'documentos' => $documentos,
            'estadisticas' => $estadisticas,
            'estudiantes' => $estudiantes,
            'filtros' => $filtros
        ];

        require_once 'views/documento/index.php';
    }

    public function ver()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de documento no especificado');
            }

            $documento = $this->documentoModel->obtenerDocumentoPorId($id);
            
            if (!$documento) {
                throw new Exception('Documento no encontrado');
            }

            // Obtener datos adicionales
            $practicasEstudiante = $this->documentoModel->obtenerPracticasPorEstudiante($documento['estudiante']);
            $horasInfo = $this->documentoModel->verificarHorasCompletadas($documento['practica_id']);

            echo json_encode([
                'success' => true,
                'data' => [
                    'documento' => $documento,
                    'practicas' => $practicasEstudiante,
                    'horas_info' => $horasInfo
                ]
            ]);
            
        } catch (Exception $e) {
            error_log("❌ Error en DocumentoController::ver: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Mostrar formulario de creación
            $estudiantes = $this->estudianteModel->obtenerEstudiantesConModulos();
            
            $data = [
                'titulo' => 'Crear Nuevo Documento',
                'estudiantes' => $estudiantes
            ];

            require_once 'views/documento/crear.php';
            return;
        }

        // POST - Procesar creación
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

        try {
            // Validar token CSRF
            if (!SessionHelper::validateCSRF($_POST['csrf_token'] ?? '')) {
                throw new Exception('Token de seguridad inválido');
            }

            // Validar datos
            $requiredFields = ['practica_id', 'tipo_documento'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo {$field} es requerido");
                }
            }

            // Obtener datos de la práctica
            $practica = $this->practicaModel->obtenerPracticaPorId($_POST['practica_id']);
            if (!$practica) {
                throw new Exception("Práctica no encontrada");
            }

            // Verificar horas para fichas de identidad
            if ($_POST['tipo_documento'] === 'ficha_identidad') {
                $horasInfo = $this->documentoModel->verificarHorasCompletadas($_POST['practica_id']);
                if (!$horasInfo['completado']) {
                    throw new Exception("No se puede crear ficha de identidad: horas incompletas ({$horasInfo['horas_acumuladas']}/{$horasInfo['total_horas']})");
                }
            }

            // Generar contenido del documento
            $estudiante = $this->estudianteModel->obtenerEstudianteCompleto($practica['estudiante']);
            $empresa = $this->empresaModel->obtenerEmpresaPorId($practica['empresa']);
            $contenido = $this->generarContenidoDocumento($_POST['tipo_documento'], $estudiante, $empresa, $practica);

            // Preparar datos del documento
            $datosDocumento = [
                'practica_id' => $_POST['practica_id'],
                'tipo_documento' => $_POST['tipo_documento'],
                'contenido' => $contenido,
                'fecha_documento' => $_POST['fecha_documento'] ?? date('Y-m-d'),
                'generado_por' => $_SESSION['usuario']['id'] ?? 1,
                'estado' => 'pendiente'
            ];

            // Generar número de oficio si es necesario
            if ($_POST['tipo_documento'] === 'oficio_multiple') {
                $datosDocumento['numero_oficio'] = $this->documentoModel->generarNumeroOficio();
            }

            // Crear documento
            $documentoId = $this->documentoModel->crearDocumento($datosDocumento);

            if ($documentoId) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Documento creado exitosamente',
                    'id' => $documentoId,
                    'redirect' => "index.php?c=Documento&a=vistaPrevia&id={$documentoId}"
                ]);
            } else {
                throw new Exception("Error al crear el documento");
            }

        } catch (Exception $e) {
            error_log("❌ Error en DocumentoController::crear: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function vistaPrevia()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header("Location: index.php?c=Documento&a=index&error=ID no especificado");
            exit;
        }

        $documento = $this->documentoModel->obtenerDocumentoPorId($id);
        
        if (!$documento) {
            header("Location: index.php?c=Documento&a=index&error=Documento no encontrado");
            exit;
        }

        // Obtener datos adicionales
        $practicasEstudiante = $this->documentoModel->obtenerPracticasPorEstudiante($documento['estudiante']);
        $horasInfo = $this->documentoModel->verificarHorasCompletadas($documento['practica_id']);

        $data = [
            'titulo' => 'Vista Previa del Documento',
            'documento' => $documento,
            'practicas' => $practicasEstudiante,
            'horas_info' => $horasInfo
        ];

        require_once 'views/documento/vista_previa.php';
    }

    private function generarContenidoDocumento($tipo, $estudiante, $empresa, $practica)
    {
        $fechaActual = date('d/m/Y');
        $nombreCompleto = $estudiante['nombre_completo'] ?? "{$estudiante['ap_est']} {$estudiante['am_est']}, {$estudiante['nom_est']}";
        
        switch ($tipo) {
            case 'carta_presentacion':
                return $this->generarCartaPresentacion($nombreCompleto, $estudiante, $empresa, $practica, $fechaActual);
            
            case 'oficio_multiple':
                return $this->generarOficioMultiple($nombreCompleto, $estudiante, $empresa, $practica, $fechaActual);
            
            case 'ficha_identidad':
                return $this->generarFichaIdentidad($nombreCompleto, $estudiante, $empresa, $practica, $fechaActual);
            
            default:
                return "<h2>Documento</h2><p>Generado el {$fechaActual}</p>";
        }
    }

    private function generarCartaPresentacion($nombreCompleto, $estudiante, $empresa, $practica, $fecha)
    {
        return "<div style='font-family: Times New Roman; font-size: 12pt; line-height: 1.5;'>
            <h2 style='text-align: center; font-weight: bold;'>CARTA DE PRESENTACIÓN</h2>
            <p style='text-align: center;'>San Agustín de Cajas, {$fecha}</p>
            <p style='margin-top: 20px;'><strong>Señor: {$empresa['representante_legal']}</strong></p>
            <p><strong>{$empresa['razon_social']}</strong></p>
            <p>Ciudad. --</p>
            <p style='margin-top: 20px; text-align: justify;'>
                La presente es para presentar al estudiante:
            </p>
            <div style='margin: 20px 0; padding: 15px; border-left: 3px solid #0C1F36; background: #f8f9fa;'>
                <p><strong>Nombre:</strong> {$nombreCompleto}</p>
                <p><strong>DNI:</strong> {$estudiante['dni_est']}</p>
                <p><strong>Programa de Estudios:</strong> {$estudiante['programa']}</p>
                <p><strong>Módulo:</strong> {$practica['modulo']}</p>
            </div>
            <p style='text-align: justify;'>
                Para realizar sus experiencias formativas en situaciones reales de trabajo en su prestigiosa institución.
            </p>
            <div style='margin-top: 100px; text-align: center;'>
                <p>_________________________</p>
                <p><strong>Firma y Sello del Director</strong></p>
                <p>IESTP \"Andrés Avelino Cáceres Dorregaray\"</p>
            </div>
        </div>";
    }

    private function generarOficioMultiple($nombreCompleto, $estudiante, $empresa, $practica, $fecha)
    {
        return "<div style='font-family: Times New Roman; font-size: 12pt; line-height: 1.5;'>
            <h2 style='text-align: center; font-weight: bold;'>OFICIO MÚLTIPLE</h2>
            <p style='text-align: center;'>San Agustín de Cajas, {$fecha}</p>
            <p style='margin-top: 20px;'><strong>Señor: {$empresa['representante_legal']}</strong></p>
            <p><strong>{$empresa['razon_social']}</strong></p>
            <p style='margin-top: 20px; text-align: center; font-weight: bold;'>
                ASUNTO: Solicita realizar experiencias formativas en situaciones reales de trabajo.
            </p>
            <p style='margin-top: 20px; text-align: justify;'>
                Es grato dirigirme a Ud. para solicitarle se digne aceptar al estudiante:
            </p>
            <div style='margin: 20px 0; padding: 15px; border-left: 3px solid #0C1F36; background: #f8f9fa;'>
                <p><strong>Nombre:</strong> {$nombreCompleto}</p>
                <p><strong>DNI:</strong> {$estudiante['dni_est']}</p>
                <p><strong>Programa de Estudios:</strong> {$estudiante['programa']}</p>
                <p><strong>Módulo:</strong> {$practica['modulo']}</p>
                <p><strong>Periodo Académico:</strong> {$practica['periodo_academico']}</p>
            </div>
            <p style='text-align: justify;'>
                Agradeciéndole anticipadamente su colaboración en bien de la Educación Superior.
            </p>
            <div style='margin-top: 100px; text-align: center;'>
                <p>_________________________</p>
                <p><strong>Firma y Sello del Director</strong></p>
                <p>IESTP \"Andrés Avelino Cáceres Dorregaray\"</p>
            </div>
        </div>";
    }

    private function generarFichaIdentidad($nombreCompleto, $estudiante, $empresa, $practica, $fecha)
    {
        $horasInfo = $this->documentoModel->verificarHorasCompletadas($practica['id']);
        $estadoHoras = $horasInfo['completado'] ? 'COMPLETADO' : 'EN CURSO';
        $colorEstado = $horasInfo['completado'] ? '#10b981' : '#f59e0b';
        
        return "<div style='font-family: Times New Roman; font-size: 12pt; line-height: 1.5;'>
            <h2 style='text-align: center; font-weight: bold;'>FICHA DE IDENTIDAD - ASISTENCIAS COMPLETADAS</h2>
            <h3 style='text-align: center;'>EXPERIENCIAS FORMATIVAS EN SITUACIONES REALES DE TRABAJO</h3>
            
            <div style='margin: 20px 0; padding: 15px; border: 1px solid #ccc;'>
                <p><strong>Estudiante:</strong> {$nombreCompleto}</p>
                <p><strong>DNI:</strong> {$estudiante['dni_est']}</p>
                <p><strong>Programa:</strong> {$estudiante['programa']}</p>
                <p><strong>Empresa:</strong> {$empresa['razon_social']}</p>
                <p><strong>Módulo:</strong> {$practica['modulo']}</p>
                <p><strong>Supervisor Empresa:</strong> {$practica['supervisor_empresa']}</p>
                <p><strong>Cargo Supervisor:</strong> {$practica['cargo_supervisor']}</p>
            </div>
            
            <div style='margin: 20px 0; padding: 15px; border-left: 4px solid {$colorEstado}; background: " . ($horasInfo['completado'] ? '#d1fae5' : '#fef3c7') . ";'>
                <p><strong>Horas Acumuladas:</strong> {$horasInfo['horas_acumuladas']}</p>
                <p><strong>Horas Requeridas:</strong> {$horasInfo['total_horas']}</p>
                <p><strong>Porcentaje:</strong> {$horasInfo['porcentaje']}%</p>
                <p><strong>Estado:</strong> {$estadoHoras}</p>
            </div>
            
            <p style='text-align: justify;'>
                Se certifica que el estudiante <strong>{$nombreCompleto}</strong> 
                ha realizado sus experiencias formativas en <strong>{$empresa['razon_social']}</strong> 
                durante el módulo de <strong>{$practica['modulo']}</strong>, 
                cumpliendo con <strong>{$horasInfo['horas_acumuladas']}</strong> horas de las 
                <strong>{$horasInfo['total_horas']}</strong> horas requeridas.
            </p>
            
            <div style='margin-top: 100px; display: flex; justify-content: space-between;'>
                <div style='text-align: center; width: 200px;'>
                    <p>_________________________</p>
                    <p><strong>Supervisor de Empresa</strong></p>
                    <p>{$practica['supervisor_empresa']}</p>
                </div>
                
                <div style='text-align: center; width: 200px;'>
                    <p>_________________________</p>
                    <p><strong>Docente Supervisor</strong></p>
                </div>
                
                <div style='text-align: center; width: 200px;'>
                    <p>_________________________</p>
                    <p><strong>Coordinador del Programa</strong></p>
                    <p>{$estudiante['programa']}</p>
                </div>
            </div>
        </div>";
    }

    public function enviar()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de documento no especificado');
            }

            // Verificar que el documento exista
            $documento = $this->documentoModel->obtenerDocumentoPorId($id);
            if (!$documento) {
                throw new Exception("Documento no encontrado");
            }

            // Para documentos de asistencias, verificar horas completadas
            if ($documento['tipo_documento'] === 'ficha_identidad') {
                $horasInfo = $this->documentoModel->verificarHorasCompletadas($documento['practica_id']);
                if (!$horasInfo['completado']) {
                    throw new Exception("No se puede enviar: El estudiante no ha completado las horas requeridas ({$horasInfo['horas_acumuladas']}/{$horasInfo['total_horas']} horas)");
                }
            }

            // Actualizar estado
            if ($this->documentoModel->actualizarEstado($id, 'generado')) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Documento enviado al estudiante exitosamente'
                ]);
            } else {
                throw new Exception("Error al actualizar el estado del documento");
            }

        } catch (Exception $e) {
            error_log("❌ Error en DocumentoController::enviar: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function eliminar()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de documento no especificado');
            }

            // Validar token CSRF
            $csrf_token = $_POST['csrf_token'] ?? '';
            if (!SessionHelper::validateCSRF($csrf_token)) {
                throw new Exception('Token de seguridad inválido');
            }

            if ($this->documentoModel->eliminarDocumento($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Documento eliminado exitosamente'
                ]);
            } else {
                throw new Exception("Error al eliminar el documento");
            }
        } catch (Exception $e) {
            error_log("❌ Error en DocumentoController::eliminar: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function obtenerPracticasAjax()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

        try {
            $estudianteId = $_GET['estudiante_id'] ?? null;
            
            if (!$estudianteId) {
                echo json_encode([]);
                return;
            }

            $practicas = $this->documentoModel->obtenerPracticasPorEstudiante($estudianteId);
            echo json_encode([
                'success' => true,
                'data' => $practicas
            ]);
            
        } catch (Exception $e) {
            error_log("❌ Error en obtenerPracticasAjax: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function verificarHorasAjax()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);
        error_reporting(0);

        try {
            $practicaId = $_GET['practica_id'] ?? null;
            
            if (!$practicaId) {
                echo json_encode(['completado' => false]);
                return;
            }

            $horasInfo = $this->documentoModel->verificarHorasCompletadas($practicaId);
            echo json_encode([
                'success' => true,
                'data' => $horasInfo
            ]);
            
        } catch (Exception $e) {
            error_log("❌ Error en verificarHorasAjax: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }

    public function descargarPDF()
    {
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID de documento no especificado');
            }

            $documento = $this->documentoModel->obtenerDocumentoPorId($id);
            
            if (!$documento) {
                throw new Exception('Documento no encontrado');
            }

            // Para implementación real, aquí generarías el PDF
            // Por ahora redirigimos a la vista previa con opción de imprimir
            header("Location: index.php?c=Documento&a=vistaPrevia&id={$id}&imprimir=1");
            exit;
            
        } catch (Exception $e) {
            error_log("❌ Error en DocumentoController::descargarPDF: " . $e->getMessage());
            header("Location: index.php?c=Documento&a=index&error=" . urlencode($e->getMessage()));
            exit;
        }
    }

    public function exportarCSV()
    {
        try {
            // Obtener filtros
            $filtros = [
                'tipo_documento' => $_GET['tipo'] ?? '',
                'estado' => $_GET['estado'] ?? '',
                'estudiante_id' => $_GET['estudiante_id'] ?? '',
                'modulo' => $_GET['modulo'] ?? '',
                'busqueda' => $_GET['busqueda'] ?? ''
            ];

            $documentos = $this->documentoModel->obtenerDocumentos($filtros);

            if (empty($documentos)) {
                throw new Exception('No hay documentos para exportar');
            }

            // Configurar headers para descarga CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="documentos_' . date('Y-m-d_H-i-s') . '.csv"');

            // Crear output stream
            $output = fopen('php://output', 'w');

            // 🔥 BOM para Excel
            fwrite($output, "\xEF\xBB\xBF");

            // Encabezados CSV
            $encabezados = [
                'ID',
                'Tipo Documento',
                'N° Oficio',
                'Estudiante',
                'DNI',
                'Módulo',
                'Empresa',
                'Fecha Documento',
                'Fecha Generación',
                'Estado'
            ];

            fputcsv($output, $encabezados, ';');

            // Datos de documentos
            foreach ($documentos as $doc) {
                $tipo = $this->getTipoTexto($doc['tipo_documento']);
                $estado = $doc['estado'] == 'generado' ? 'Enviado' : 'Pendiente';
                
                $fila = [
                    $doc['id'],
                    $tipo,
                    $doc['numero_oficio'] ?? '',
                    $doc['nombre_estudiante'] ?? '',
                    $doc['dni_est'] ?? '',
                    $doc['modulo'] ?? '',
                    $doc['nombre_empresa'] ?? 'No asignada',
                    $doc['fecha_documento'] ?? '',
                    $doc['fecha_generacion'] ?? '',
                    $estado
                ];

                fputcsv($output, $fila, ';');
            }

            fclose($output);
            exit;
        } catch (Exception $e) {
            // Si hay error, redirigir con mensaje
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function getTipoTexto($tipo)
    {
        $tipos = [
            'carta_presentacion' => 'Carta de Presentación',
            'oficio_multiple' => 'Oficio Múltiple',
            'ficha_identidad' => 'Ficha de Identidad'
        ];
        
        return $tipos[$tipo] ?? $tipo;
    }
}
?>