<?php
require_once 'helpers/SessionHelper.php';

class DocumentoController
{
    private $estudianteModel;
    private $practicaModel;
    private $asistenciaModel;
    private $empresaModel;
    private $documentoModel;
    private $empleadoModel;

    public function __construct()
    {
        // Cargar modelos
        require_once 'models/EstudianteModel.php';
        require_once 'models/PracticaModel.php';
        require_once 'models/AsistenciaModel.php';
        require_once 'models/EmpresaModel.php';
        require_once 'models/DocumentoModel.php';
        require_once 'models/EmpleadoModel.php';

        $this->estudianteModel = new EstudianteModel();
        $this->practicaModel = new PracticaModel();
        $this->asistenciaModel = new AsistenciaModel();
        $this->empresaModel = new EmpresaModel();
        $this->documentoModel = new DocumentoModel();
        $this->empleadoModel = new EmpleadoModel();
    }

    /**
     * Página principal de documentos del estudiante
     */
    public function index()
    {
        // Solo estudiantes pueden acceder
        if (!SessionHelper::esEstudiante()) {
            header("Location: index.php?c=Inicio&a=index&error=acceso_denegado");
            exit;
        }

        $usuario = SessionHelper::getUser();
        $estudiante_id = $usuario['estuempleado'] ?? null;

        if (!$estudiante_id) {
            header("Location: index.php?c=DashboardEstudiante&a=index&error=no_estudiante");
            exit;
        }

        // Obtener datos del estudiante
        $estudiante = $this->estudianteModel->obtenerEstudianteCompleto($estudiante_id);

        if (!$estudiante) {
            error_log("❌ No se encontró estudiante con ID: $estudiante_id");
            header("Location: index.php?c=DashboardEstudiante&a=index&error=estudiante_no_encontrado");
            exit;
        }

        // Obtener prácticas del estudiante
        $practicas = $this->practicaModel->obtenerPracticasPorEstudiante($estudiante_id);

        // Organizar por módulos
        $modulos = [];
        foreach ($practicas as $practica) {
            $modulo_num = str_replace('modulo', '', $practica['tipo_efsrt']);

            // Obtener asistencias para esta práctica
            $asistencias = $this->asistenciaModel->obtenerPorPractica($practica['id']);
            $totalHoras = $this->asistenciaModel->obtenerHorasAcumuladas($practica['id']);

            // Obtener empresa
            $empresa = $this->empresaModel->obtenerEmpresaPorId($practica['empresa']);

            // Obtener docente supervisor
            $docente = null;
            if ($practica['empleado']) {
                $docente = $this->empleadoModel->obtenerEmpleadoPorId($practica['empleado']);
            }

            // Obtener documentos existentes
            $documentos = $this->documentoModel->obtenerDocumentosPorPractica($practica['id']);

            // Determinar estado de la práctica
            $estado = $this->determinarEstadoPractica($practica, $totalHoras);

            $modulos[$modulo_num] = [
                'practica' => $practica,
                'empresa' => $empresa,
                'docente' => $docente,
                'asistencias' => $asistencias,
                'total_horas' => $totalHoras,
                'porcentaje' => $practica['total_horas'] > 0 ?
                    min(100, round(($totalHoras / $practica['total_horas']) * 100)) : 0,
                'documentos' => $documentos,
                'estado' => $estado
            ];
        }

        // Preparar datos para la vista
        $data = [
            'estudiante' => $estudiante,
            'modulos' => $modulos
        ];

        // Cargar vista
        require_once 'views/layouts/header.php';
        require_once 'views/documento/index.php';
        require_once 'views/layouts/footer.php';
    }

    /**
     * Generar vista previa de documento (AJAX)
     */
    public function preview()
    {
        // Solo estudiantes pueden acceder
        if (!SessionHelper::esEstudiante()) {
            echo json_encode(['error' => 'Acceso denegado']);
            exit;
        }

        $tipo = $_GET['tipo'] ?? '';
        $modulo = $_GET['modulo'] ?? 1;

        if (empty($tipo)) {
            echo json_encode(['error' => 'Tipo de documento no especificado']);
            exit;
        }

        $usuario = SessionHelper::getUser();
        $estudiante_id = $usuario['estuempleado'] ?? null;

        if (!$estudiante_id) {
            echo json_encode(['error' => 'Estudiante no identificado']);
            exit;
        }

        // Obtener datos del estudiante
        $estudiante = $this->estudianteModel->obtenerEstudianteCompleto($estudiante_id);

        if (!$estudiante) {
            echo json_encode(['error' => 'Estudiante no encontrado']);
            exit;
        }

        // Obtener práctica del módulo
        $practica = $this->practicaModel->obtenerPracticaByModulo(
            $estudiante_id,
            "modulo{$modulo}"
        );

        if (!$practica) {
            echo json_encode(['error' => 'No se encontró la práctica para este módulo']);
            exit;
        }

        // Obtener empresa
        $empresa = $this->empresaModel->obtenerEmpresaPorId($practica['empresa']);

        // Obtener docente supervisor
        $docente = null;
        if ($practica['empleado']) {
            $docente = $this->empleadoModel->obtenerEmpleadoPorId($practica['empleado']);
        }

        // Generar contenido según tipo
        $contenido = $this->generarContenidoDocumento($tipo, $estudiante, $practica, $empresa, $docente);

        echo json_encode([
            'titulo' => $this->getTituloDocumento($tipo, $modulo),
            'contenido' => $contenido,
            'tipo' => $tipo,
            'modulo' => $modulo
        ]);
    }

    /**
     * Generar y descargar documento
     */
    public function generar()
    {
        // Solo estudiantes pueden acceder
        if (!SessionHelper::esEstudiante()) {
            die('Acceso denegado');
        }

        $tipo = $_GET['tipo'] ?? '';
        $modulo = $_GET['modulo'] ?? 1;

        if (empty($tipo)) {
            die('Tipo de documento no especificado');
        }

        $usuario = SessionHelper::getUser();
        $estudiante_id = $usuario['estuempleado'] ?? null;

        if (!$estudiante_id) {
            die('Estudiante no identificado');
        }

        // Obtener datos del estudiante
        $estudiante = $this->estudianteModel->obtenerEstudianteCompleto($estudiante_id);

        if (!$estudiante) {
            die('Estudiante no encontrado');
        }

        // Obtener práctica del módulo
        $practica = $this->practicaModel->obtenerPracticaByModulo(
            $estudiante_id,
            "modulo{$modulo}"
        );

        if (!$practica) {
            die('No se encontró la práctica para este módulo');
        }

        // Generar el documento
        $this->generarDocumento($tipo, $estudiante, $practica, $modulo);
    }

    /**
     * Generar contenido HTML para documento
     */
    private function generarContenidoDocumento($tipo, $estudiante, $practica, $empresa, $docente)
    {
        switch ($tipo) {
            case 'solicitud':
                return $this->generarHTMLSolicitud($estudiante, $practica, $empresa);
            case 'carta':
                return $this->generarHTMLCartaPresentacion($estudiante, $practica, $empresa);
            case 'asistencias':
                $asistencias = $this->asistenciaModel->obtenerPorPractica($practica['id']);
                $totalHoras = $this->asistenciaModel->obtenerHorasAcumuladas($practica['id']);
                return $this->generarHTMLFichaAsistencias($estudiante, $practica, $empresa, $asistencias, $totalHoras);
            case 'evaluacion':
                return $this->generarHTMLEvaluacion($estudiante, $practica, $empresa, $docente);
            default:
                return '<p class="text-red-600">Tipo de documento no válido</p>';
        }
    }

    /**
     * Generar HTML para Solicitud de Prácticas
     */
    private function generarHTMLSolicitud($estudiante, $practica, $empresa)
    {
        $fechaActual = date('d/m/Y');
        $periodo = $estudiante['per_acad'] ?? 'VI';
        $nombreCompleto = $estudiante['ap_est'] . ' ' . $estudiante['am_est'] . ', ' . $estudiante['nom_est'];

        return '
            <div class="document-header">
                <h2 style="color: #1e40af; margin-bottom: 0.5rem; text-align: center;">SOLICITUD DE PRÁCTICAS PROFESIONALES</h2>
                <p style="color: #6b7280; margin: 0; text-align: center;">Módulo: ' . $practica['modulo'] . '</p>
                <p style="color: #6b7280; margin: 0; text-align: center;">Fecha: ' . $fechaActual . '</p>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem;">
                    <tr>
                        <td style="padding: 0.5rem 0; width: 30%;"><strong>INSTITUCIÓN:</strong></td>
                        <td style="padding: 0.5rem 0;">I.E.S.T.P. "Andrés Avelino Cáceres Dorregaray"</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0;"><strong>DIRIGIDO A:</strong></td>
                        <td style="padding: 0.5rem 0;">' . ($practica['supervisor_empresa'] ?? 'Supervisor de Empresa') . '<br>' .
            ($empresa['razon_social'] ?? 'Empresa') . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0;"><strong>ESTUDIANTE:</strong></td>
                        <td style="padding: 0.5rem 0;">' . $nombreCompleto . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0;"><strong>DNI:</strong></td>
                        <td style="padding: 0.5rem 0;">' . $estudiante['dni_est'] . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0;"><strong>PROGRAMA:</strong></td>
                        <td style="padding: 0.5rem 0;">' . ($estudiante['nom_progest'] ?? 'Diseño y Programación Web') . ' - ' . $periodo . '° Ciclo</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0;"><strong>PERÍODO:</strong></td>
                        <td style="padding: 0.5rem 0;">' . date('d/m/Y', strtotime($practica['fecha_inicio'])) . ' - ' .
            ($practica['fecha_fin'] ? date('d/m/Y', strtotime($practica['fecha_fin'])) : 'Actual') . '</td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <p style="text-align: justify; line-height: 1.8; margin-bottom: 1rem;">
                    Por medio de la presente, me dirijo a usted para solicitar formalmente la autorización correspondiente 
                    para que el estudiante <strong>' . $nombreCompleto . '</strong> pueda realizar sus prácticas 
                    pre-profesionales en las instalaciones de su prestigiosa empresa <strong>' .
            ($empresa['razon_social'] ?? '') . '</strong>.
                </p>
                
                <p style="text-align: justify; line-height: 1.8; margin-bottom: 1rem;">
                    El estudiante ha sido capacitado en las competencias necesarias para desempeñarse en el área de 
                    ' . strtolower($estudiante['nom_progest'] ?? 'Diseño y Programación Web') . ' y cuenta con el perfil adecuado 
                    para aportar valor a su organización durante el período de prácticas.
                </p>
                
                <p style="text-align: justify; line-height: 1.8;">
                    Agradezco de antemano su valiosa colaboración en la formación profesional de nuestros estudiantes, 
                    quedando a su disposición para cualquier consulta o coordinación adicional.
                </p>
            </div>
            
            <div class="signature-section">
                <div style="text-align: center; margin-top: 3rem;">
                    <div style="height: 1px; background: #000; width: 250px; margin: 0 auto;"></div>
                    <p style="margin: 0.5rem 0;"><strong>DIRECTOR</strong></p>
                    <p style="color: #6b7280; margin: 0;">I.E.S.T.P. "Andrés Avelino Cáceres Dorregaray"</p>
                </div>
            </div>
            
            <div style="margin-top: 2rem; padding: 1rem; background: #f8fafc; border-radius: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                <p style="margin: 0;"><strong>Nota:</strong> Este documento debe ser presentado en la empresa para formalizar el inicio de las prácticas profesionales.</p>
            </div>';
    }

    /**
     * Generar HTML para Carta de Presentación
     */
    private function generarHTMLCartaPresentacion($estudiante, $practica, $empresa)
    {
        $nombreCompleto = $estudiante['ap_est'] . ' ' . $estudiante['am_est'] . ', ' . $estudiante['nom_est'];
        $periodo = $estudiante['per_acad'] ?? 'VI';

        return '
            <div class="document-header">
                <h2 style="color: #1e40af; margin-bottom: 0.5rem; text-align: center;">CARTA DE PRESENTACIÓN</h2>
                <p style="color: #6b7280; margin: 0; text-align: center;">Módulo: ' . $practica['modulo'] . '</p>
                <p style="color: #6b7280; margin: 0; text-align: center;">Fecha: ' . date('d/m/Y') . '</p>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem;">
                    <tr>
                        <td style="padding: 0.5rem 0; width: 20%;"><strong>PARA:</strong></td>
                        <td style="padding: 0.5rem 0;">' . ($practica['supervisor_empresa'] ?? 'Supervisor de Empresa') . '<br>' .
            ($empresa['razon_social'] ?? 'Empresa') . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0;"><strong>DE:</strong></td>
                        <td style="padding: 0.5rem 0;">' . $nombreCompleto . '<br>Estudiante - ' .
            ($estudiante['nom_progest'] ?? 'Diseño y Programación Web') . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0;"><strong>ASUNTO:</strong></td>
                        <td style="padding: 0.5rem 0;">Presentación para prácticas profesionales</td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <p style="text-align: justify; line-height: 1.8; margin-bottom: 1rem;">
                    Me es grato dirigirme a usted para presentarme como estudiante del <strong>' .
            ($estudiante['nom_progest'] ?? 'Diseño y Programación Web') . '</strong> del <strong>' .
            $periodo . '° ciclo</strong> en el I.E.S.T.P. "Andrés Avelino Cáceres Dorregaray", 
                    y expresar mi interés en realizar mis prácticas pre-profesionales en su distinguida empresa.
                </p>
                
                <p style="text-align: justify; line-height: 1.8; margin-bottom: 1rem;">
                    Durante mi formación académica he adquirido competencias en:
                </p>
                
                <ul style="margin-left: 1.5rem; margin-bottom: 1rem;">
                    <li>Desarrollo de aplicaciones web y móviles</li>
                    <li>Diseño de interfaces de usuario</li>
                    <li>Gestión de bases de datos</li>
                    <li>Metodologías ágiles de desarrollo</li>
                    <li>Control de versiones con Git</li>
                </ul>
                
                <p style="text-align: justify; line-height: 1.8; margin-bottom: 1rem;">
                    Me considero una persona responsable, con capacidad de trabajo en equipo y gran motivación por aprender 
                    y contribuir al éxito de los proyectos en los que participe. Estoy seguro de que esta experiencia 
                    en su empresa será fundamental para mi desarrollo profesional.
                </p>
                
                <p style="text-align: justify; line-height: 1.8;">
                    Aprovecho la oportunidad para agradecerle su atención y quedo a su disposición para cualquier 
                    información adicional que pueda requerir.
                </p>
            </div>
            
            <div class="signature-section">
                <div style="text-align: right; margin-top: 3rem;">
                    <div style="height: 1px; background: #000; width: 250px; margin-left: auto;"></div>
                    <p style="margin: 0.5rem 0;"><strong>' . strtoupper($nombreCompleto) . '</strong></p>
                    <p style="color: #6b7280; margin: 0;">DNI: ' . $estudiante['dni_est'] . '</p>
                    <p style="color: #6b7280; margin: 0;">Estudiante - ' . ($estudiante['nom_progest'] ?? 'Diseño y Programación Web') . '</p>
                </div>
            </div>';
    }

    /**
     * Generar HTML para Ficha de Asistencias
     */
    private function generarHTMLFichaAsistencias($estudiante, $practica, $empresa, $asistencias, $totalHoras)
    {
        $nombreCompleto = $estudiante['ap_est'] . ' ' . $estudiante['am_est'] . ', ' . $estudiante['nom_est'];
        $periodo = $estudiante['per_acad'] ?? 'VI';
        $turno = $estudiante['turno'] ?? 'Vespertino';
        $estadoDocumento = $practica['estado'] === 'Finalizado' ? 'COMPLETO' : 'PARCIAL';

        // Generar tabla de asistencias
        $tablaHTML = '';
        $contador = 1;

        foreach ($asistencias as $asistencia) {
            $fecha = date('d/m/Y', strtotime($asistencia['fecha']));
            $entrada = date('H:i', strtotime($asistencia['hora_entrada']));
            $salida = date('H:i', strtotime($asistencia['hora_salida']));

            $tablaHTML .= '
                <tr>
                    <td style="text-align: center;">' . $contador . '</td>
                    <td>' . $fecha . '</td>
                    <td style="text-align: center;">' . $entrada . '</td>
                    <td style="text-align: center;">' . $salida . '</td>
                    <td style="text-align: center;">' . $asistencia['horas_acumuladas'] . 'h</td>
                    <td>' . ($asistencia['actividad'] ?? '') . '</td>
                    <td style="text-align: center; min-width: 100px;">________________</td>
                </tr>';
            $contador++;
        }

        return '
            <div class="document-header">
                <h2 style="color: #1e40af; margin-bottom: 0.5rem; text-align: center;">FICHA DE CONTROL DE ASISTENCIA Y ACTIVIDADES</h2>
                <h3 style="color: #4b5563; margin: 0; font-weight: 600; text-align: center;">EXPERIENCIAS FORMATIVAS EN SITUACIONES REALES DE TRABAJO</h3>
                <p style="color: #6b7280; margin: 0.5rem 0; text-align: center;">Módulo: ' . $practica['modulo'] . '</p>
                <p style="color: #6b7280; margin: 0; text-align: center; font-size: 0.875rem;">Estado: ' . $estadoDocumento . ' - ' .
            ($practica['estado'] === 'Finalizado' ? 'Completado' : 'En Curso') . '</p>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <tr>
                        <td style="padding: 0.5rem; width: 25%;"><strong>NOMBRES Y APELLIDOS:</strong></td>
                        <td style="padding: 0.5rem;">' . $nombreCompleto . '</td>
                        <td style="padding: 0.5rem; width: 20%;"><strong>PERIODO ACADÉMICO:</strong></td>
                        <td style="padding: 0.5rem;">' . $periodo . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem;"><strong>PROGRAMA DE ESTUDIOS:</strong></td>
                        <td style="padding: 0.5rem;">' . ($estudiante['nom_progest'] ?? 'Diseño y Programación Web') . '</td>
                        <td style="padding: 0.5rem;"><strong>TURNO:</strong></td>
                        <td style="padding: 0.5rem;">' . $turno . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem;"><strong>MÓDULO TÉCNICO PROFESIONAL:</strong></td>
                        <td style="padding: 0.5rem;">' . $practica['modulo'] . '</td>
                        <td style="padding: 0.5rem;"><strong>EMPRESA:</strong></td>
                        <td style="padding: 0.5rem;">' . ($empresa['razon_social'] ?? '') . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem;"><strong>SUPERVISOR EMPRESA:</strong></td>
                        <td style="padding: 0.5rem;" colspan="3">' . ($practica['supervisor_empresa'] ?? '') . '</td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #d1d5db; font-size: 0.8rem;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="border: 1px solid #d1d5db; padding: 6px; width: 5%; text-align: center;">ITEM</th>
                            <th style="border: 1px solid #d1d5db; padding: 6px; width: 12%; text-align: left;">FECHA</th>
                            <th style="border: 1px solid #d1d5db; padding: 6px; width: 10%; text-align: center;">HORA ENTRADA</th>
                            <th style="border: 1px solid #d1d5db; padding: 6px; width: 10%; text-align: center;">HORA SALIDA</th>
                            <th style="border: 1px solid #d1d5db; padding: 6px; width: 8%; text-align: center;">HORAS</th>
                            <th style="border: 1px solid #d1d5db; padding: 6px; width: 45%; text-align: left;">ACTIVIDADES REALIZADAS</th>
                            <th style="border: 1px solid #d1d5db; padding: 6px; width: 10%; text-align: center;">V°B° DOCENTE</th>
                        </tr>
                    </thead>
                    <tbody>' . $tablaHTML . '</tbody>
                    <tfoot>
                        <tr style="background: #f8fafc; font-weight: bold;">
                            <td colspan="4" style="border: 1px solid #d1d5db; padding: 6px; text-align: right;">TOTAL DE HORAS:</td>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">' . $totalHoras . 'h</td>
                            <td colspan="2" style="border: 1px solid #d1d5db; padding: 6px;"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="signature-section">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-top: 3rem;">
                    <div style="text-align: center;">
                        <div style="height: 1px; background: #000; width: 200px; margin: 0 auto;"></div>
                        <p style="margin: 0.5rem 0;"><strong>' . (isset($practica['supervisor_empresa']) ?
                strtoupper(explode(' ', $practica['supervisor_empresa'])[0]) : 'SUPERVISOR') . '</strong></p>
                        <p style="color: #6b7280; margin: 0; font-size: 0.875rem;">V°B° EMPRESA</p>
                    </div>
                    
                    <div style="text-align: center;">
                        <div style="height: 1px; background: #000; width: 200px; margin: 0 auto;"></div>
                        <p style="margin: 0.5rem 0;"><strong>DOCENTE SUPERVISOR</strong></p>
                        <p style="color: #6b7280; margin: 0; font-size: 0.875rem;">V°B° SUPERVISOR DE PRÁCTICAS</p>
                    </div>
                    
                    <div style="text-align: center;">
                        <div style="height: 1px; background: #000; width: 200px; margin: 0 auto;"></div>
                        <p style="margin: 0.5rem 0;"><strong>COORDINADOR</strong></p>
                        <p style="color: #6b7280; margin: 0; font-size: 0.875rem;">V°B° COORDINADOR DEL PROGRAMA</p>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 2rem; padding: 1rem; background: #f8fafc; border-radius: 0.5rem; font-size: 0.75rem; color: #6b7280;">
                <p style="margin: 0; font-weight: bold;">Instrucciones:</p>
                <p style="margin: 0.25rem 0 0 0;">1. Esta ficha debe ser presentada en cada jornada de prácticas para el registro de asistencia.</p>
                <p style="margin: 0.25rem 0 0 0;">2. El docente supervisor debe firmar (V°B°) cada actividad realizada.</p>
                <p style="margin: 0.25rem 0 0 0;">3. Al completar el módulo, esta ficha debe ser entregada en la institución educativa.</p>
                <p style="margin: 0.25rem 0 0 0; font-weight: bold;">Fecha de generación:</strong> ' . date('d/m/Y') . '</p>
            </div>';
    }

    /**
     * Generar HTML para Evaluación EFSRT
     */
    /**
     * Generar HTML para Evaluación EFSRT (COMPLETO)
     */
    private function generarHTMLEvaluacion($estudiante, $practica, $empresa, $docente)
    {
        $nombreCompleto = $estudiante['ap_est'] . ' ' . $estudiante['am_est'] . ', ' . $estudiante['nom_est'];
        $fechaActual = date('d/m/Y');
        $fechaInicio = date('d/m/Y', strtotime($practica['fecha_inicio']));
        $fechaFin = $practica['fecha_fin'] ? date('d/m/Y', strtotime($practica['fecha_fin'])) : 'Actual';

        // Obtener horas acumuladas
        $horasAcumuladas = $this->asistenciaModel->obtenerHorasAcumuladas($practica['id']) ?? 0;

        // Información del docente
        $docenteNombre = $docente['apnom_emp'] ?? 'Docente Supervisor';

        // Supervisor de empresa
        $supervisorEmpresa = $practica['supervisor_empresa'] ?? 'Supervisor de Empresa';
        $cargoSupervisor = $practica['cargo_supervisor'] ?? 'Supervisor';

        return '
    <div class="document-preview">
        <div class="document-header">
            <h2 style="color: #1e40af; margin-bottom: 0.5rem; text-align: center; font-size: 1.5rem;">
                FICHA DE EVALUACIÓN DE LAS EXPERIENCIAS FORMATIVAS
            </h2>
            <h3 style="color: #4b5563; margin: 0; font-weight: 600; text-align: center; font-size: 1.2rem;">
                EN SITUACIONES REALES DE TRABAJO
            </h3>
            <p style="color: #6b7280; margin: 0.5rem 0; text-align: center;">
                Módulo: ' . $practica['modulo'] . ' | Período: ' . $fechaInicio . ' - ' . $fechaFin . '
            </p>
            <p style="color: #6b7280; margin: 0; text-align: center; font-size: 0.875rem;">
                Código: EVAL-EFSRT-' . date('Y') . '-' . str_pad($practica['id'], 4, '0', STR_PAD_LEFT) . '
            </p>
        </div>
        
        <!-- Información general -->
        <div style="margin-bottom: 2rem; padding: 1.5rem; background: #f8fafc; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <tr>
                    <td style="padding: 0.5rem; width: 25%;"><strong>ESTUDIANTE:</strong></td>
                    <td style="padding: 0.5rem; width: 35%;">' . $nombreCompleto . '</td>
                    <td style="padding: 0.5rem; width: 15%;"><strong>DNI:</strong></td>
                    <td style="padding: 0.5rem; width: 25%;">' . $estudiante['dni_est'] . '</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem;"><strong>PROGRAMA:</strong></td>
                    <td style="padding: 0.5rem;">' . ($estudiante['nom_progest'] ?? 'Diseño y Programación Web') . '</td>
                    <td style="padding: 0.5rem;"><strong>CICLO:</strong></td>
                    <td style="padding: 0.5rem;">' . ($estudiante['per_acad'] ?? 'VI') . '° Ciclo</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem;"><strong>EMPRESA:</strong></td>
                    <td style="padding: 0.5rem;" colspan="3">' . ($empresa['razon_social'] ?? '') . '</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem;"><strong>ÁREA:</strong></td>
                    <td style="padding: 0.5rem;">' . ($practica['area_ejecucion'] ?? 'No especificada') . '</td>
                    <td style="padding: 0.5rem;"><strong>HORAS:</strong></td>
                    <td style="padding: 0.5rem;">' . $horasAcumuladas . ' horas</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem;"><strong>SUPERVISOR EMPRESA:</strong></td>
                    <td style="padding: 0.5rem;">' . $supervisorEmpresa . '</td>
                    <td style="padding: 0.5rem;"><strong>CARGO:</strong></td>
                    <td style="padding: 0.5rem;">' . $cargoSupervisor . '</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem;"><strong>DOCENTE SUPERVISOR:</strong></td>
                    <td style="padding: 0.5rem;" colspan="3">' . $docenteNombre . '</td>
                </tr>
            </table>
        </div>
        
        <!-- Título de la evaluación -->
        <div style="margin-bottom: 1.5rem; text-align: center;">
            <h4 style="color: #374151; margin: 0; font-size: 1.1rem; font-weight: bold; background: #e5e7eb; padding: 0.75rem; border-radius: 0.25rem;">
                III. EVALUACIÓN DE LAS EXPERIENCIAS FORMATIVAS EN SITUACIONES REALES DE TRABAJO
            </h4>
        </div>
        
        <!-- Tabla de evaluación -->
        <div style="margin-bottom: 2rem;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; border: 1px solid #d1d5db;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #d1d5db; padding: 8px; width: 5%; text-align: center; background: #f1f5f9;">N°</th>
                        <th style="border: 1px solid #d1d5db; padding: 8px; width: 65%; text-align: center; background: #f1f5f9;">CRITERIOS DE EVALUACIÓN</th>
                        <th style="border: 1px solid #d1d5db; padding: 8px; width: 30%; text-align: center; background: #f1f5f9;">PUNTAJE OBTENIDO (1-4)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 1. ORGANIZACIÓN Y EJECUCIÓN DEL TRABAJO -->
                    <tr>
                        <td colspan="3" style="border: 1px solid #d1d5db; padding: 8px; background: #e5e7eb; font-weight: bold; text-align: center;">
                            1. ORGANIZACIÓN Y EJECUCIÓN DEL TRABAJO
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">a</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Se identifica con la misión y la visión de la empresa</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">b</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Planifica y ejecuta organizadamente las actividades a realizar</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">c</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Demuestra habilidad y seguridad en el trabajo</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">d</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Organiza los materiales, herramientas y equipos a utilizar</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    
                    <!-- 2. CAPACIDAD TÉCNICA Y EMPRESARIAL -->
                    <tr>
                        <td colspan="3" style="border: 1px solid #d1d5db; padding: 8px; background: #e5e7eb; font-weight: bold; text-align: center;">
                            2. CAPACIDAD TÉCNICA Y EMPRESARIAL
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">a</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Demuestra criterios técnicos en el desarrollo de la actividad</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">b</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Toma decisiones acertadas y oportunas sobre problemas que se presentan</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">c</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Emplea equipos, instrumentos y herramientas correctamente</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">d</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Coopera en la conservación y mantenimiento de equipos</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">e</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Practica normas de seguridad y salud en el trabajo</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">f</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Se comunica con propiedad y fluidez</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">g</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Tiene dominio de los procedimientos empleados en el desarrollo de las actividades</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">h</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Propone proyectos de producción y/o prestación de servicios</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    
                    <!-- 3. CUMPLIMIENTO EN EL TRABAJO -->
                    <tr>
                        <td colspan="3" style="border: 1px solid #d1d5db; padding: 8px; background: #e5e7eb; font-weight: bold; text-align: center;">
                            3. CUMPLIMIENTO EN EL TRABAJO
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">a</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Es puntual</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">b</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Cumple con los plazos establecidos para cada tarea</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">c</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Utiliza indumentaria adecuada</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">d</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Evita distracciones en el horario de trabajo</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    
                    <!-- 4. CALIDAD EN LA EJECUCIÓN -->
                    <tr>
                        <td colspan="3" style="border: 1px solid #d1d5db; padding: 8px; background: #e5e7eb; font-weight: bold; text-align: center;">
                            4. CALIDAD EN LA EJECUCIÓN
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">a</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Aplica normas técnicas en la ejecución de las actividades</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">b</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Identifica rápidamente las dificultades</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">c</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Propone mejoras en los procedimientos utilizados</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    
                    <!-- 5. TRABAJO EN EQUIPO -->
                    <tr>
                        <td colspan="3" style="border: 1px solid #d1d5db; padding: 8px; background: #e5e7eb; font-weight: bold; text-align: center;">
                            5. TRABAJO EN EQUIPO
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">a</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Es capaz de enfrentar cualquier cambio de roles</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">b</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Reconoce y aprovecha la experiencia de los demás</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">c</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Fomenta la colaboración oportuna y solidaria</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">d</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Ejecuta acciones de adiestramiento espontáneo a sus compañeros de trabajo</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    
                    <!-- 6. INICIATIVA -->
                    <tr>
                        <td colspan="3" style="border: 1px solid #d1d5db; padding: 8px; background: #e5e7eb; font-weight: bold; text-align: center;">
                            6. INICIATIVA
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">a</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Respeta las iniciativas de los otros miembros del equipo</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">b</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px;">Admite sus errores y es capaz de aprender de ellos</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                    
                    <!-- TOTAL -->
                    <tr style="background: #f1f5f9; font-weight: bold;">
                        <td colspan="2" style="border: 1px solid #d1d5db; padding: 8px; text-align: right;">PUNTAJE TOTAL (Máximo 100 puntos)</td>
                        <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">_____</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Tablas de referencia -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Escala de calificación -->
            <div>
                <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem; border: 1px solid #d1d5db;">
                    <thead>
                        <tr>
                            <th colspan="2" style="border: 1px solid #d1d5db; padding: 8px; text-align: center; background: #f1f5f9;">
                                ESCALA DE CALIFICACIÓN DE LOS INDICADORES
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; width: 50%;">MUY BUENA</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; width: 50%;"><strong>4</strong></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">BUENA</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;"><strong>3</strong></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">ACEPTABLE</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;"><strong>2</strong></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">DEFICIENTE</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;"><strong>1</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Calificación final -->
            <div>
                <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem; border: 1px solid #d1d5db;">
                    <thead>
                        <tr>
                            <th colspan="3" style="border: 1px solid #d1d5db; padding: 8px; text-align: center; background: #f1f5f9;">
                                CALIFICACIÓN DE LAS EXPERIENCIAS FORMATIVAS
                            </th>
                        </tr>
                        <tr>
                            <th style="border: 1px solid #d1d5db; padding: 6px; text-align: center; width: 30%;">PUNTAJE</th>
                            <th style="border: 1px solid #d1d5db; padding: 6px; text-align: center; width: 30%;">ESCALA</th>
                            <th style="border: 1px solid #d1d5db; padding: 6px; text-align: center; width: 40%;">APRECIACIÓN FINAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">90 - 100</td>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">A</td>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">Muy Buena</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">75 - 89</td>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">B</td>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">Buena</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">63 - 74</td>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">C</td>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">Aceptable</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">25 - 62</td>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">D</td>
                            <td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">Deficiente</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Observaciones del supervisor -->
        <div style="margin-bottom: 2rem; padding: 1.5rem; background: #f8fafc; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <h4 style="color: #374151; margin: 0 0 1rem 0; font-weight: bold; font-size: 1rem;">
                Apreciación del Supervisor de la Empresa
            </h4>
            <p style="margin: 0.5rem 0; font-style: italic; color: #6b7280; font-size: 0.875rem;">
                Según R.D. N° 0865 - 2025- IESTP "AACD"
            </p>
            
            <div style="margin: 1rem 0;">
                <!-- Espacio para observaciones escritas a mano -->
                <div style="height: 80px; border-bottom: 1px dashed #9ca3af; margin-bottom: 0.5rem;"></div>
                <div style="height: 80px; border-bottom: 1px dashed #9ca3af; margin-bottom: 0.5rem;"></div>
                
                <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.875rem;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Recomendaciones:</p>
                        <div style="margin-top: 0.25rem;">
                            <label style="display: inline-flex; align-items: center; margin-right: 1rem;">
                                <input type="checkbox" style="margin-right: 0.25rem;"> Contratación
                            </label>
                            <label style="display: inline-flex; align-items: center; margin-right: 1rem;">
                                <input type="checkbox" style="margin-right: 0.25rem;"> Prácticas posteriores
                            </label>
                            <label style="display: inline-flex; align-items: center;">
                                <input type="checkbox" style="margin-right: 0.25rem;"> Referencia laboral
                            </label>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0; font-weight: bold;">Fecha de evaluación:</p>
                        <div style="margin-top: 0.25rem; width: 150px; border-bottom: 1px solid #9ca3af; display: inline-block;"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Firmas -->
        <div class="signature-section">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 3rem;">
                <!-- Docente Supervisor -->
                <div style="text-align: center;">
                    <div style="height: 1px; background: #000; width: 250px; margin: 0 auto;"></div>
                    <p style="margin: 0.5rem 0; font-weight: bold; font-size: 0.9rem;">
                        ' . strtoupper($docenteNombre) . '
                    </p>
                    <p style="color: #6b7280; margin: 0; font-size: 0.8rem;">Docente Supervisor</p>
                    <p style="color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.75rem;">
                        I.E.S.T.P. "Andrés Avelino Cáceres Dorregaray"
                    </p>
                </div>
                
                <!-- Supervisor Empresa -->
                <div style="text-align: center;">
                    <div style="height: 1px; background: #000; width: 250px; margin: 0 auto;"></div>
                    <p style="margin: 0.5rem 0; font-weight: bold; font-size: 0.9rem;">
                        ' . strtoupper($supervisorEmpresa) . '
                    </p>
                    <p style="color: #6b7280; margin: 0; font-size: 0.8rem;">Supervisor de la Empresa</p>
                    <p style="color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.75rem;">
                        ' . ($empresa['razon_social'] ?? 'Empresa') . '
                    </p>
                </div>
            </div>
            
            <!-- Sello de la empresa -->
            <div style="text-align: center; margin-top: 3rem;">
                <div style="display: inline-block; padding: 1rem; border: 2px dashed #9ca3af; border-radius: 0.5rem;">
                    <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">
                        <i class="fas fa-stamp" style="margin-right: 0.5rem;"></i>
                        ESPACIO PARA SELLO DE LA EMPRESA
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Instrucciones -->
        <div style="margin-top: 2rem; padding: 1rem; background: #f8fafc; border-radius: 0.5rem; border: 1px solid #e5e7eb; font-size: 0.75rem; color: #6b7280;">
            <p style="margin: 0; font-weight: bold;">INSTRUCCIONES:</p>
            <ol style="margin: 0.5rem 0 0 1rem; padding: 0;">
                <li>Este documento debe ser llenado a mano por el supervisor de empresa.</li>
                <li>Calificar cada criterio con la escala correspondiente (1-4).</li>
                <li>Sumar el puntaje total de todos los criterios.</li>
                <li>Determinar la calificación final según la tabla de referencia.</li>
                <li>Firmar y sellar en los espacios correspondientes.</li>
                <li>Entregar al estudiante para su presentación en la institución.</li>
            </ol>
            <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.7rem;">
                <div>
                    <p style="margin: 0; font-weight: bold;">Fecha de emisión:</p>
                    <p style="margin: 0;">' . $fechaActual . '</p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-weight: bold;">Código de verificación:</p>
                    <p style="margin: 0; font-family: monospace;">EVAL-' . strtoupper(substr(md5($practica['id'] . $estudiante['dni_est']), 0, 8)) . '</p>
                </div>
            </div>
        </div>
    </div>';
    }

    /**
     * Generar documento para descarga
     */
    private function generarDocumento($tipo, $estudiante, $practica, $modulo)
    {
        // Por ahora, generamos un archivo HTML simple para descargar
        // Más adelante puedes integrar TCPDF o DomPDF

        $titulo = $this->getTituloDocumento($tipo, $modulo);
        $contenido = $this->generarContenidoDocumento(
            $tipo,
            $estudiante,
            $practica,
            $this->empresaModel->obtenerEmpresaPorId($practica['empresa']),
            $practica['empleado'] ? $this->empleadoModel->obtenerEmpleadoPorId($practica['empleado']) : null
        );

        // Generar archivo HTML para descargar
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $titulo . '</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6; 
                    padding: 20px; 
                    color: #333;
                    max-width: 1000px;
                    margin: 0 auto;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 1rem 0;
                    font-size: 11px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 6px;
                    text-align: left;
                }
                th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                }
                .signature-line {
                    height: 1px;
                    background: #000;
                    width: 200px;
                    margin: 20px 0 5px 0;
                }
                .document-header {
                    text-align: center;
                    margin-bottom: 20px;
                    padding-bottom: 15px;
                    border-bottom: 2px solid #000;
                }
                @media print {
                    body { padding: 0; }
                    @page { 
                        margin: 1cm;
                        size: A4;
                    }
                }
            </style>
        </head>
        <body>
            ' . $contenido . '
        </body>
        </html>';

        // Configurar headers para descarga
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . $titulo . '.html"');
        echo $html;
        exit;
    }

    /**
     * Obtener título del documento
     */
    private function getTituloDocumento($tipo, $modulo)
    {
        $titulos = [
            'solicitud' => 'Solicitud de Prácticas',
            'carta' => 'Carta de Presentación',
            'asistencias' => 'Ficha de Control de Asistencias',
            'evaluacion' => 'Evaluación EFSRT'
        ];

        return ($titulos[$tipo] ?? 'Documento') . ' - Módulo ' . $modulo;
    }

    /**
     * Determinar estado de la práctica
     */
    private function determinarEstadoPractica($practica, $totalHoras)
    {
        if ($practica['estado'] === 'Finalizado') {
            return 'completado';
        }

        if ($totalHoras >= $practica['total_horas']) {
            return 'completado';
        }

        if ($totalHoras > 0) {
            return 'en_curso';
        }

        return 'pendiente';
    }

    /**
     * Obtener prácticas por estudiante (si no existe en PracticaModel)
     */
    private function obtenerPracticasPorEstudiante($estudiante_id)
    {
        // Si tu PracticaModel ya tiene este método, deberías usarlo
        // Esta es una implementación de respaldo
        $sql = "SELECT p.*, e.razon_social as empresa_nombre
                FROM practicas p
                LEFT JOIN empresa e ON p.empresa = e.id
                WHERE p.estudiante = ?
                ORDER BY 
                    CASE p.tipo_efsrt 
                        WHEN 'modulo1' THEN 1 
                        WHEN 'modulo2' THEN 2 
                        WHEN 'modulo3' THEN 3 
                        ELSE 4 
                    END";

        // Necesitarías ejecutar esta consulta directamente
        // Pero es mejor agregar el método a PracticaModel
        return [];
    }
}
