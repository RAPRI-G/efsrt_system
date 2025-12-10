<?php
// Cargar datos del controlador
$estudiante = $data['estudiante'] ?? [];
$modulos = $data['modulos'] ?? [];

// Mapeo de estados a clases
$estadoClases = [
    'completado' => 'estado-completado',
    'en_curso' => 'estado-en-curso',
    'pendiente' => 'estado-pendiente',
    'no_iniciado' => 'estado-pendiente'
];

$estadoIconos = [
    'completado' => 'fa-check-circle',
    'en_curso' => 'fa-play-circle',
    'pendiente' => 'fa-clock',
    'no_iniciado' => 'fa-clock'
];

$estadoColores = [
    'completado' => 'text-green-500',
    'en_curso' => 'text-blue-500',
    'pendiente' => 'text-amber-500',
    'no_iniciado' => 'text-gray-500'
];

$estadoTextos = [
    'completado' => 'Completado',
    'en_curso' => 'En Curso',
    'pendiente' => 'Pendiente',
    'no_iniciado' => 'No Iniciado'
];

// Obtener nombre completo del estudiante
$nombreCompleto = $estudiante['ap_est'] . ' ' . $estudiante['am_est'] . ', ' . $estudiante['nom_est'];
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Mis Documentos</h1>
    <p class="text-gray-600">Gestión y descarga de documentos de prácticas profesionales</p>
</div>

<!-- Información del estudiante -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="bg-blue-600 h-12 w-12 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                <?php echo strtoupper(substr($estudiante['ap_est'] ?? 'E', 0, 1)); ?>
            </div>
            <div class="flex flex-col">
                <span class="text-lg font-semibold text-gray-800"><?php echo $nombreCompleto; ?></span>
                <div class="flex items-center space-x-4 mt-1">
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-id-card mr-1"></i>
                        DNI: <?php echo $estudiante['dni_est']; ?>
                    </span>
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        <?php echo $estudiante['nom_progest'] ?? 'Programa no asignado'; ?>
                    </span>
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-clock mr-1"></i>
                        Período: <?php echo $estudiante['per_acad'] ?? 'VI'; ?> -
                        <?php echo $estudiante['turno'] ?? 'Vespertino'; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="text-right">
            <span class="text-sm font-medium text-gray-600">Estudiante Activo</span>
            <div class="mt-1">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-user-graduate mr-1"></i>
                    ESFRH
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="mb-6 bg-white rounded-xl shadow-sm p-1">
    <div class="flex space-x-1">
        <?php for ($i = 1; $i <= 3; $i++): ?>
            <?php
            $moduloData = $modulos[$i] ?? null;
            $estado = $moduloData['estado'] ?? 'no_iniciado';
            $activeClass = $i == 1 ? 'active' : '';
            ?>
            <button class="tab-button <?php echo $activeClass; ?>"
                data-tab="modulo<?php echo $i; ?>">
                <i class="fas <?php echo $estadoIconos[$estado]; ?> mr-2 <?php echo $estadoColores[$estado]; ?>"></i>
                Módulo <?php echo $i; ?>
            </button>
        <?php endfor; ?>
    </div>
</div>

<!-- Contenido de los módulos -->
<?php for ($i = 1; $i <= 3; $i++): ?>
    <?php
    $moduloData = $modulos[$i] ?? null;
    $displayStyle = $i == 1 ? 'block' : 'none';
    $activeClass = $i == 1 ? 'active' : '';
    ?>

    <div id="modulo<?php echo $i; ?>" class="tab-content <?php echo $activeClass; ?> fade-in"
        style="display: <?php echo $displayStyle; ?>;">

        <?php if ($moduloData): ?>
            <?php
            $practica = $moduloData['practica'];
            $empresa = $moduloData['empresa'];
            $docente = $moduloData['docente'];
            $asistencias = $moduloData['asistencias'];
            $totalHoras = $moduloData['total_horas'];
            $porcentaje = $moduloData['porcentaje'];
            $estado = $moduloData['estado'];
            $documentos = $moduloData['documentos'];

            // Fechas formateadas
            $fechaInicio = $practica['fecha_inicio'] ? date('d/m/Y', strtotime($practica['fecha_inicio'])) : 'No definida';
            $fechaFin = $practica['fecha_fin'] ? date('d/m/Y', strtotime($practica['fecha_fin'])) : 'Actual';

            // Información de la empresa
            $empresaNombre = $empresa['razon_social'] ?? 'No asignada';
            $supervisorEmpresa = $practica['supervisor_empresa'] ?? 'No asignado';

            // Información del docente
            $docenteNombre = $docente['apnom_emp'] ?? 'No asignado';
            ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas <?php echo $estadoIconos[$estado]; ?> mr-2 <?php echo $estadoColores[$estado]; ?>"></i>
                        Módulo <?php echo $i; ?> - <?php echo $estadoTextos[$estado]; ?>
                    </h3>
                </div>

                <div class="card-content p-6">
                    <h4 class="text-lg font-bold text-blue-700 mb-4"><?php echo $practica['modulo']; ?></h4>

                    <!-- Información del módulo -->
                    <div class="module-info mb-6">
                        <table class="module-info-table">
                            <tbody>
                                <tr>
                                    <td class="info-label">Período</td>
                                    <td class="info-value">
                                        <?php echo $fechaInicio; ?> - <?php echo $fechaFin; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-label">Horas Completadas</td>
                                    <td class="info-value">
                                        <span class="font-bold"><?php echo $totalHoras; ?> horas</span>
                                        (<?php echo round($porcentaje, 0); ?>%)
                                        <?php if ($totalHoras > $practica['total_horas']): ?>
                                            <span class="percentage-badge">+<?php echo $totalHoras - $practica['total_horas']; ?> horas</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-label">Empresa</td>
                                    <td class="info-value"><?php echo $empresaNombre; ?></td>
                                </tr>
                                <tr>
                                    <td class="info-label">Supervisor Empresa</td>
                                    <td class="info-value"><?php echo $supervisorEmpresa; ?></td>
                                </tr>
                                <tr>
                                    <td class="info-label">Docente Supervisor</td>
                                    <td class="info-value"><?php echo $docenteNombre; ?></td>
                                </tr>
                                <tr>
                                    <td class="info-label">Área de Ejecución</td>
                                    <td class="info-value"><?php echo $practica['area_ejecucion'] ?? 'No especificada'; ?></td>
                                </tr>
                                <tr>
                                    <td class="info-label">Estado</td>
                                    <td class="info-value">
                                        <span class="estado-badge <?php echo $estadoClases[$estado]; ?>">
                                            <i class="fas <?php echo $estadoIconos[$estado]; ?> mr-1"></i>
                                            <?php echo $estadoTextos[$estado]; ?>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Barra de progreso -->
                        <?php if ($estado == 'en_curso' || $estado == 'completado'): ?>
                            <div class="progress-container mt-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span>Progreso del Módulo</span>
                                    <span><?php echo $totalHoras; ?> / <?php echo $practica['total_horas']; ?> horas</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill <?php echo $estado == 'completado' ? 'completed' : ''; ?>"
                                        style="width: <?php echo $porcentaje; ?>%"></div>
                                </div>
                                <?php if ($estado == 'en_curso'): ?>
                                    <div class="mt-2 text-sm text-blue-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Faltan <?php echo max(0, $practica['total_horas'] - $totalHoras); ?> horas para completar
                                    </div>
                                <?php elseif ($estado == 'completado'): ?>
                                    <div class="mt-2 text-sm text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        ¡Módulo completado!
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Sección de documentos -->
                    <h4 class="font-bold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                        Documentos Disponibles
                    </h4>

                    <div class="space-y-4">
                        <!-- Documento: Solicitud de Prácticas -->
                        <div class="document-card">
                            <div class="flex justify-between items-start mb-4">
                                <div class="document-icon solicitud">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    <?php echo $fechaInicio; ?>
                                </div>
                            </div>
                            <h5 class="document-title font-bold text-lg mb-2">Solicitud de Prácticas - Módulo <?php echo $i; ?></h5>
                            <p class="document-description text-gray-600 mb-4">
                                Documento oficial dirigido a la empresa solicitando la realización de experiencias formativas en situaciones reales de trabajo.
                            </p>
                            <div class="document-actions flex gap-2">
                                <a href="index.php?c=Documento&a=generar&tipo=solicitud&modulo=<?php echo $i; ?>"
                                    class="btn-download" target="_blank">
                                    <i class="fas fa-download"></i>
                                    Descargar PDF
                                </a>
                                <button class="btn-preview" onclick="verDocumento('solicitud', <?php echo $i; ?>)">
                                    <i class="fas fa-eye"></i>
                                    Vista Previa
                                </button>
                            </div>
                        </div>

                        <!-- Documento: Carta de Presentación -->
                        <div class="document-card">
                            <div class="flex justify-between items-start mb-4">
                                <div class="document-icon carta">
                                    <i class="fas fa-envelope-open-text"></i>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    <?php echo $fechaInicio; ?>
                                </div>
                            </div>
                            <h5 class="document-title font-bold text-lg mb-2">Carta de Presentación - Módulo <?php echo $i; ?></h5>
                            <p class="document-description text-gray-600 mb-4">
                                Carta de presentación oficial del estudiante para la empresa como parte del proceso de prácticas.
                            </p>
                            <div class="document-actions flex gap-2">
                                <a href="index.php?c=Documento&a=generar&tipo=carta&modulo=<?php echo $i; ?>"
                                    class="btn-download" target="_blank">
                                    <i class="fas fa-download"></i>
                                    Descargar PDF
                                </a>
                                <button class="btn-preview" onclick="verDocumento('carta', <?php echo $i; ?>)">
                                    <i class="fas fa-eye"></i>
                                    Vista Previa
                                </button>
                            </div>
                        </div>

                        <!-- Documento: Ficha de Asistencias -->
                        <div class="document-card">
                            <div class="flex justify-between items-start mb-4">
                                <div class="document-icon asistencia">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php if ($estado == 'completado'): ?>
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <?php echo $fechaFin; ?>
                                    <?php else: ?>
                                        <i class="fas fa-sync-alt mr-1"></i>
                                        Actualizado: <?php echo date('d/m/Y'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <h5 class="document-title font-bold text-lg mb-2">Ficha de Control de Asistencias - Módulo <?php echo $i; ?></h5>
                            <p class="document-description text-gray-600 mb-4">
                                Documento oficial con todas las asistencias, horas y actividades realizadas.
                                Incluye <?php echo count($asistencias); ?> días con <?php echo $totalHoras; ?> horas.
                            </p>
                            <div class="document-actions flex gap-2">
                                <a href="index.php?c=Documento&a=generar&tipo=asistencias&modulo=<?php echo $i; ?>"
                                    class="btn-download" target="_blank">
                                    <i class="fas fa-download"></i>
                                    <?php echo $estado == 'completado' ? 'Descargar PDF' : 'Descargar PDF (Parcial)'; ?>
                                </a>
                                <button class="btn-preview" onclick="verFichaAsistencias(<?php echo $i; ?>)">
                                    <i class="fas fa-eye"></i>
                                    Vista Previa
                                </button>
                            </div>
                            <?php if ($estado == 'en_curso'): ?>
                                <div class="mt-3 text-sm text-blue-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Este documento se actualizará automáticamente al completar las <?php echo $practica['total_horas']; ?> horas requeridas
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Documento: Evaluación EFSRT -->
                        <div class="document-card">
                            <div class="flex justify-between items-start mb-4">
                                <div class="document-icon evaluacion">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php if ($estado == 'completado'): ?>
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <?php echo $fechaFin; ?>
                                    <?php else: ?>
                                        <i class="fas fa-clock mr-1"></i>
                                        Pendiente
                                    <?php endif; ?>
                                </div>
                            </div>
                            <h5 class="document-title font-bold text-lg mb-2">Evaluación EFSRT - Módulo <?php echo $i; ?></h5>
                            <p class="document-description text-gray-600 mb-4">
                                Ficha de Evaluación de las Experiencias Formativas en Situaciones Reales de Trabajo. El supervisor debe rellenarla a mano.
                            </p>
                            <div class="document-actions flex gap-2">
                                <?php if ($estado == 'completado'): ?>
                                    <a href="index.php?c=Documento&a=generar&tipo=evaluacion&modulo=<?php echo $i; ?>"
                                        class="btn-download" target="_blank">
                                        <i class="fas fa-download"></i>
                                        Descargar PDF
                                    </a>
                                    <button class="btn-preview" onclick="verDocumento('evaluacion', <?php echo $i; ?>)">
                                        <i class="fas fa-eye"></i>
                                        Vista Previa
                                    </button>
                                <?php else: ?>
                                    <button class="btn-download" disabled>
                                        <i class="fas fa-clock"></i>
                                        Pendiente
                                    </button>
                                    <button class="btn-preview" disabled>
                                        <i class="fas fa-eye"></i>
                                        Vista Previa
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php if ($estado != 'completado'): ?>
                                <div class="mt-3 text-sm text-amber-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Disponible al completar las <?php echo $practica['total_horas']; ?> horas requeridas
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Historial de documentos generados -->
                    <?php if (!empty($documentos)): ?>
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="font-bold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-history text-gray-500 mr-2"></i>
                                Historial de Documentos Generados
                            </h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="space-y-3">
                                    <?php foreach ($documentos as $documento): ?>
                                        <?php
                                        $tipoNombres = [
                                            'solicitud_practicas' => 'Solicitud de Prácticas',
                                            'carta_presentacion' => 'Carta de Presentación',
                                            'ficha_asistencias' => 'Ficha de Asistencias',
                                            'evaluacion_efsrt' => 'Evaluación EFSRT',
                                            'oficio_multiple' => 'Oficio Múltiple',
                                            'ficha_identidad' => 'Ficha de Identidad'
                                        ];

                                        $tipoNombre = $tipoNombres[$documento['tipo_documento']] ?? $documento['tipo_documento'];
                                        $fechaGeneracion = date('d/m/Y H:i', strtotime($documento['fecha_generacion']));
                                        ?>
                                        <div class="flex items-center justify-between p-3 bg-white rounded border border-gray-200">
                                            <div class="flex items-center">
                                                <div class="mr-3">
                                                    <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800"><?php echo $tipoNombre; ?></p>
                                                    <p class="text-sm text-gray-600">
                                                        Generado: <?php echo $fechaGeneracion; ?>
                                                        <?php if ($documento['numero_oficio']): ?>
                                                            | N°: <?php echo $documento['numero_oficio']; ?>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full 
                                                        <?php echo $documento['estado'] == 'generado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                    <?php echo $documento['estado'] == 'generado' ? 'Generado' : 'Pendiente'; ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <!-- Si no tiene práctica en este módulo -->
            <div class="card">
                <div class="card-content p-6">
                    <div class="empty-state">
                        <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-file-alt text-gray-400 text-4xl"></i>
                        </div>
                        <h4 class="font-bold text-gray-700 mb-2">No hay prácticas registradas para el Módulo <?php echo $i; ?></h4>
                        <p class="text-gray-600 text-center mb-6">
                            Las prácticas para este módulo aún no han sido registradas en el sistema.
                        </p>

                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 max-w-md mx-auto">
                            <div class="flex items-start">
                                <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-info-circle text-amber-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-amber-800 mb-1">Información del Módulo</h4>
                                    <div class="text-amber-700 text-sm space-y-2">
                                        <p><strong>Horas Requeridas:</strong>
                                            <?php
                                            $horasRequeridas = [
                                                1 => 160,
                                                2 => 180,
                                                3 => 200
                                            ];
                                            echo $horasRequeridas[$i] ?? 'No definido';
                                            ?> horas
                                        </p>
                                        <p><strong>Estado:</strong> Pendiente de asignación</p>
                                        <p><strong>Documentos disponibles:</strong> Al iniciar el módulo</p>
                                        <p><strong>Requisito:</strong>
                                            <?php echo $i > 1 ? 'Completar el Módulo ' . ($i - 1) : 'Ninguno'; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endfor; ?>


<!-- Modal para Vista Previa -->
<div id="modalDocumento" class="modal">
    <div class="modal-content">
        <div class="modal-header bg-gradient-to-r from-blue-800 to-blue-900 text-white p-4 rounded-t-xl">
            <div class="flex justify-between items-center">
                <h3 id="modalTitulo" class="text-xl font-bold flex items-center">
                    <i class="fas fa-file-alt mr-3"></i>
                    <span id="modalTituloTexto">Vista Previa del Documento</span>
                </h3>
                <button id="cerrarModal" class="text-white hover:text-blue-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="modal-body p-0">
            <div id="documentoPreview" class="document-preview p-6">
                <!-- Contenido generado dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- En views/documento/index.php -->
<link rel="stylesheet" href="assets/css/documentos.css">
<script src="assets/js/documentos.js"></script>