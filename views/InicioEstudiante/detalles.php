<?php
// Extraer datos
$practica = $data['practica'] ?? [];
$estudiante = $data['estudiante'] ?? [];
$empresa = $data['empresa'] ?? [];
$asistencias = $data['asistencias'] ?? [];
$estadisticas = $data['estadisticas'] ?? [];
$modulo_nombre = $data['modulo_nombre'] ?? 'Módulo';
$usuario = SessionHelper::getUser();

// Incluir layout
require_once 'views/layouts/header.php';
?>

<style>
    .detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .detail-card:hover {
        transform: translateY(-2px);
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .stat-badge.success {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-badge.info {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .stat-badge.warning {
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .progress-circle {
        width: 120px;
        height: 120px;
        position: relative;
    }

    .progress-circle svg {
        transform: rotate(-90deg);
    }

    .progress-circle-bg {
        fill: none;
        stroke: #e5e7eb;
        stroke-width: 8;
    }

    .progress-circle-progress {
        fill: none;
        stroke: #3b82f6;
        stroke-width: 8;
        stroke-linecap: round;
        stroke-dasharray: 314;
        stroke-dashoffset: calc(314 - (314 * <?php echo $practica['progreso_porcentaje'] ?? 0; ?>) / 100);
        transition: stroke-dashoffset 1s ease;
    }

    .attendance-row:hover {
        background-color: #f8fafc;
        transform: translateX(4px);
        transition: all 0.3s ease;
    }

    .back-btn {
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        transform: translateX(-5px);
    }
</style>


<!-- Encabezado con botón de regreso -->
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center">
        <a href="index.php?c=DashboardEstudiante&a=index"
            class="back-btn text-primary-blue hover:text-blue-700 mr-4 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver al Dashboard
        </a>
        <div>
            <h1 class="text-2xl font-bold text-primary-blue">
                Detalles del <?php echo $modulo_nombre; ?>
            </h1>
            <p class="text-gray-600">Información completa de tu módulo de prácticas</p>
        </div>
    </div>
    <div class="flex items-center space-x-3">
        <span class="stat-badge <?php
                                echo ($practica['estado'] == 'En curso') ? 'success' : (($practica['estado'] == 'Finalizado') ? 'info' : 'warning');
                                ?>">
            <i class="fas fa-circle mr-2" style="font-size: 8px;"></i>
            <?php echo htmlspecialchars($practica['estado'] ?? 'Pendiente'); ?>
        </span>
        <span class="text-sm text-gray-500">
            ID: <?php echo $practica['id'] ?? '--'; ?>
        </span>
    </div>
</div>

<!-- Sección 1: Información General -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Tarjeta de Progreso -->
    <div class="detail-card p-6">
        <h3 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
            <i class="fas fa-chart-line text-blue-500 mr-3"></i>
            Progreso del Módulo
        </h3>
        <div class="flex flex-col items-center justify-center">
            <div class="progress-circle mb-4">
                <svg viewBox="0 0 120 120">
                    <circle class="progress-circle-bg" cx="60" cy="60" r="50"></circle>
                    <circle class="progress-circle-progress" cx="60" cy="60" r="50"></circle>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                    <span class="text-3xl font-bold text-primary-blue">
                        <?php echo $practica['progreso_porcentaje'] ?? 0; ?>%
                    </span>
                    <span class="text-sm text-gray-500">Completado</span>
                </div>
            </div>
            <div class="text-center">
                <p class="text-gray-600 mb-2">
                    <?php echo $practica['horas_acumuladas'] ?? 0; ?> / <?php echo $practica['total_horas'] ?? 128; ?> horas
                </p>
                <p class="text-sm text-gray-500">
                    <?php echo $estadisticas['horas_totales'] ?? 0; ?> horas registradas
                </p>
            </div>
        </div>
    </div>

    <!-- Información del Período -->
    <div class="detail-card p-6">
        <h3 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
            <i class="fas fa-calendar-alt text-blue-500 mr-3"></i>
            Información del Período
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-2 border-b">
                <span class="text-gray-600">Fecha de Inicio:</span>
                <span class="font-medium text-gray-800">
                    <?php echo $practica['fecha_inicio_formateada'] ?? 'Por definir'; ?>
                </span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b">
                <span class="text-gray-600">Fecha de Fin:</span>
                <span class="font-medium text-gray-800">
                    <?php echo $practica['fecha_fin_formateada'] ?? 'Por definir'; ?>
                </span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b">
                <span class="text-gray-600">Días Transcurridos:</span>
                <span class="font-medium text-gray-800">
                    <?php echo $practica['dias_transcurridos'] ?? 0; ?> días
                </span>
            </div>
            <?php if ($practica['dias_restantes']): ?>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span class="text-gray-600">Días Restantes:</span>
                    <span class="font-bold text-blue-600">
                        <?php echo $practica['dias_restantes']; ?> días
                    </span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Periodo Académico:</span>
                <span class="font-medium text-primary-blue">
                    <?php echo htmlspecialchars($practica['periodo_academico'] ?? '--'); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Asistencia -->
    <div class="detail-card p-6">
        <h3 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
            <i class="fas fa-clipboard-check text-blue-500 mr-3"></i>
            Estadísticas de Asistencia
        </h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-gray-600">Total de Asistencias:</span>
                    <span class="text-xl font-bold text-primary-blue">
                        <?php echo $estadisticas['total_asistencias']; ?>
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full"
                        style="width: <?php echo min(100, ($estadisticas['total_asistencias'] / 30) * 100); ?>%">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Jornadas Completas</p>
                    <p class="text-2xl font-bold text-green-600"><?php echo $estadisticas['jornadas_completas']; ?></p>
                </div>
                <div class="text-center p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Jornadas Medias</p>
                    <p class="text-2xl font-bold text-yellow-600"><?php echo $estadisticas['jornadas_medias']; ?></p>
                </div>
            </div>

            <div class="text-center p-3 bg-blue-100 rounded-lg">
                <p class="text-sm text-gray-700 mb-1">Porcentaje de Asistencia</p>
                <p class="text-3xl font-bold text-primary-blue">
                    <?php echo $estadisticas['porcentaje_asistencia']; ?>%
                </p>
                <p class="text-xs text-gray-600 mt-1">
                    <?php echo $estadisticas['horas_promedio']; ?> horas en promedio por día
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Sección 2: Información de Empresa y Supervisor -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Información de la Empresa -->
    <div class="detail-card p-6">
        <h3 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
            <i class="fas fa-building text-blue-500 mr-3"></i>
            Información de la Empresa
        </h3>
        <?php if ($empresa): ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Razón Social</label>
                    <p class="font-semibold text-primary-blue"><?php echo htmlspecialchars($empresa['razon_social']); ?></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">RUC</label>
                        <p class="font-medium text-gray-800"><?php echo htmlspecialchars($empresa['ruc'] ?? '--'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Representante Legal</label>
                        <p class="font-medium text-gray-800"><?php echo htmlspecialchars($empresa['representante_legal'] ?? '--'); ?></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-500 mb-1">Dirección</label>
                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($empresa['direccion_fiscal'] ?? '--'); ?></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Teléfono</label>
                        <p class="font-medium text-gray-800"><?php echo htmlspecialchars($empresa['telefono'] ?? '--'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Email</label>
                        <p class="font-medium text-gray-800"><?php echo htmlspecialchars($empresa['email'] ?? '--'); ?></p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-building text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">No hay empresa asignada</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Información del Supervisor -->
    <div class="detail-card p-6">
        <h3 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
            <i class="fas fa-user-tie text-blue-500 mr-3"></i>
            Información del Supervisor
        </h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-gray-500 mb-1">Supervisor de Empresa</label>
                <p class="font-semibold text-primary-blue">
                    <?php echo htmlspecialchars($practica['supervisor_empresa'] ?? 'Por asignar'); ?>
                </p>
                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($practica['cargo_supervisor'] ?? '--'); ?></p>
            </div>

            <div>
                <label class="block text-sm text-gray-500 mb-1">Docente Supervisor</label>
                <?php if ($practica['nombre_docente']): ?>
                    <p class="font-semibold text-primary-blue"><?php echo htmlspecialchars($practica['nombre_docente']); ?></p>
                    <p class="text-sm text-gray-600">DNI: <?php echo htmlspecialchars($practica['dni_docente'] ?? '--'); ?></p>
                <?php else: ?>
                    <p class="text-gray-500">Por asignar</p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm text-gray-500 mb-1">Área de Ejecución</label>
                <p class="font-medium text-gray-800">
                    <?php echo htmlspecialchars($practica['area_ejecucion'] ?? 'Por definir'); ?>
                </p>
            </div>

            <div>
                <label class="block text-sm text-gray-500 mb-1">Actividades Principales</label>
                <p class="text-gray-700">
                    <?php if (!empty($practica['actividad'])): ?>
                        <?php echo nl2br(htmlspecialchars($practica['actividad'])); ?>
                    <?php else: ?>
                        <span class="text-gray-500">No especificadas</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Sección 3: Historial de Asistencias -->
<div class="detail-card p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-primary-blue flex items-center">
            <i class="fas fa-history text-blue-500 mr-3"></i>
            Historial de Asistencias
        </h3>
        <span class="text-sm text-gray-500">
            Total: <?php echo count($asistencias); ?> registros
        </span>
    </div>

    <?php if (!empty($asistencias)): ?>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividad</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($asistencias as $asistencia): ?>
                        <tr class="attendance-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo $asistencia['fecha_formateada']; ?></div>
                                <div class="text-xs text-gray-500">
                                    <?php echo date('l', strtotime($asistencia['fecha'])); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo $asistencia['hora_entrada_formateada']; ?> - <?php echo $asistencia['hora_salida_formateada']; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">
                                    <?php echo $asistencia['duracion_formateada']; ?>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <?php echo $asistencia['horas_acumuladas']; ?> horas
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo $asistencia['horas_acumuladas'] >= 8 ? 'bg-green-100 text-green-800' : ($asistencia['horas_acumuladas'] >= 4 ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'); ?>">
                                    <?php echo $asistencia['tipo_jornada']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="<?php echo htmlspecialchars($asistencia['actividad'] ?? ''); ?>">
                                    <?php echo !empty($asistencia['actividad']) ? htmlspecialchars(substr($asistencia['actividad'], 0, 80)) . (strlen($asistencia['actividad']) > 80 ? '...' : '') : 'Sin actividad registrada'; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
            <h4 class="text-lg font-medium text-gray-700 mb-2">No hay asistencias registradas</h4>
            <p class="text-gray-500">Aún no se han registrado asistencias para este módulo.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Botones de acción -->
<div class="flex justify-between items-center pt-6 border-t border-gray-200">
    <div class="text-sm text-gray-500">
        <i class="fas fa-info-circle mr-2"></i>
        Registrado el: <?php echo $practica['fecha_registro_formateada'] ?? '--'; ?>
    </div>
    <div class="flex space-x-3">
        <a href="index.php?c=DashboardEstudiante&a=index"
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
        </a>
    </div>
</div>

<script>

    // Agregar funcionalidad para expandir actividades
    document.addEventListener('DOMContentLoaded', function() {
        const activityCells = document.querySelectorAll('td:nth-child(5)');

        activityCells.forEach(cell => {
            cell.addEventListener('click', function() {
                const text = this.querySelector('div').getAttribute('title');
                if (text && text.length > 80) {
                    alert('Actividad completa:\n\n' + text);
                }
            });

            // Cambiar cursor si hay texto largo
            const title = cell.querySelector('div').getAttribute('title');
            if (title && title.length > 80) {
                cell.style.cursor = 'pointer';
                cell.title = 'Click para ver actividad completa';
            }
        });
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>