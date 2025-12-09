<?php
// Extraer datos
$estudiante = $data['estudiante'] ?? [];
$practicas = $data['practicas'] ?? [];
$modulo_activo = $data['modulo_activo'] ?? null;
$asistencias_recientes = $data['asistencias_recientes'] ?? [];
$estadisticas = $data['estadisticas'] ?? [];
$usuario = SessionHelper::getUser();
?>

<!-- Incluir tu layout general -->
<?php require_once 'views/layouts/header.php'; ?>

<!-- Aquí va el contenido específico del dashboard del estudiante -->
<style>
    /* Solo los estilos específicos para esta página */
    .card-gradient-1 {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        box-shadow: 0 8px 25px rgba(30, 58, 138, 0.2);
    }

    .card-gradient-2 {
        background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
        box-shadow: 0 8px 25px rgba(13, 202, 240, 0.2);
    }

    .card-gradient-3 {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        box-shadow: 0 8px 25px rgba(25, 135, 84, 0.2);
    }

    .card-gradient-4 {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.2);
    }

    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .progress-bar {
        height: 0.75rem;
        border-radius: 9999px;
        background-color: #e5e7eb;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.5s ease;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success {
        background-color: rgba(21, 128, 61, 0.1);
        color: #16a34a;
    }

    .badge-warning {
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .badge-info {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
</style>

<!-- Contenido del Dashboard del Estudiante -->

<div class="mb-8">
    <h1 class="text-3xl font-bold text-primary-blue mb-2">
        Bienvenido, <?php echo htmlspecialchars($estudiante['nom_est'] ?? 'Estudiante'); ?>
    </h1>
    <?php if ($modulo_activo && ($modulo_activo['estado'] ?? '') == 'En curso'): ?>
        <p class="text-gray-600">
            Dashboard del estudiante - 
            <span class="font-semibold text-blue-600">
                <?php echo $this->getNombreModuloCompleto($modulo_activo['tipo_efsrt'] ?? 'modulo1'); ?>
            </span>
        </p>
    <?php else: ?>
        <p class="text-gray-600">Panel de control del estudiante - ESFRH</p>
        <p class="text-amber-600 text-sm mt-1">
            <i class="fas fa-info-circle mr-1"></i>
            No tienes un módulo en curso actualmente.
        </p>
    <?php endif; ?>
</div>

<!-- Dashboard Cards - SOLO MÓDULO EN CURSO -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <?php if ($modulo_activo && ($modulo_activo['estado'] ?? '') == 'En curso'): ?>
        <!-- Progreso del Módulo -->
        <div class="card-gradient-1 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200 text-sm font-medium">Progreso del Módulo</p>
                    <h3 class="text-3xl font-bold mt-2">
                        <?php echo $estadisticas['progreso_modulo']; ?>%
                    </h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-200">
                <i class="fas fa-clock mr-2"></i>
                <span>
                    <?php echo $estadisticas['horas_totales']; ?>/<?php echo $modulo_activo['total_horas'] ?? 128; ?> horas completadas
                </span>
            </div>
        </div>

        <!-- Asistencias Registradas -->
        <div class="card-gradient-2 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Asistencias</p>
                    <h3 class="text-3xl font-bold mt-2">
                        <?php echo $estadisticas['asistencias_count']; ?>
                    </h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-clipboard-check text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-100">
                <i class="fas fa-check-circle mr-2 text-green-300"></i>
                <span><?php echo $estadisticas['porcentaje_asistencia']; ?>% de asistencia</span>
            </div>
        </div>

        <!-- Días Restantes -->
        <div class="card-gradient-3 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Días Restantes - Modulo II</p>
                    <h3 class="text-3xl font-bold mt-2">
                        <?php echo $estadisticas['dias_restantes']; ?>
                    </h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-100">
                <i class="fas fa-hourglass-half mr-2"></i>
                <span>Para finalizar el módulo</span>
            </div>
        </div>

        <!-- Horas Totales -->
        <div class="card-gradient-4 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Horas Totales</p>
                    <h3 class="text-3xl font-bold mt-2">
                        <?php echo $estadisticas['horas_totales']; ?>
                    </h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-purple-100">
                <i class="fas fa-chart-bar mr-2"></i>
                <span>Horas trabajadas acumuladas</span>
            </div>
        </div>
    <?php else: ?>
        <!-- Mensaje cuando no hay módulo en curso -->
        <div class="col-span-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
                <i class="fas fa-info-circle text-yellow-500 text-4xl mb-4"></i>
                <h3 class="text-xl font-bold text-yellow-700 mb-2">No hay módulo en curso</h3>
                <p class="text-yellow-600 mb-4">
                    Actualmente no tienes ningún módulo activo. 
                    <?php if (count(array_filter($practicas_por_modulo)) > 0): ?>
                        Revisa tus módulos pendientes o finalizados.
                    <?php else: ?>
                        Tu matrícula está pendiente.
                    <?php endif; ?>
                </p>
                <div class="inline-flex items-center gap-4">
                    <span class="badge badge-warning"><?php echo $estadisticas['modulos_pendientes'] ?? 0; ?> Pendientes</span>
                    <span class="badge badge-info"><?php echo $estadisticas['modulos_completados'] ?? 0; ?> Completados</span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Información de los 3 Módulos -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-primary-blue mb-4">Progreso por Módulo</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php
        // Configuración de los módulos
        $modulos_config = [
            'modulo1' => [
                'titulo' => 'Módulo 1 - EFSRT',
                'color' => 'blue',
                'icon' => 'fa-1',
                'color_class' => 'blue',
                'badge_default' => 'Pendiente'
            ],
            'modulo2' => [
                'titulo' => 'Módulo 2 - Prácticas',
                'color' => 'purple',
                'icon' => 'fa-2',
                'color_class' => 'purple',
                'badge_default' => 'Pendiente'
            ],
            'modulo3' => [
                'titulo' => 'Módulo 3 - Prácticas',
                'color' => 'green',
                'icon' => 'fa-3',
                'color_class' => 'green',
                'badge_default' => 'Pendiente'
            ]
        ];
        
        foreach ($modulos_config as $modulo_key => $config):
            $practica = $practicas_por_modulo[$modulo_key] ?? null;
            
            // Determinar estado y clase del badge
            $estado = 'Pendiente';
            $badge_class = 'badge-warning';
            
            if ($practica) {
                $estado = $practica['estado'] ?? 'Pendiente';
                
                switch ($estado) {
                    case 'En curso':
                        $badge_class = 'badge-success';
                        break;
                    case 'Finalizado':
                        $badge_class = 'badge-info';
                        break;
                    case 'Pendiente':
                        $badge_class = 'badge-warning';
                        break;
                    default:
                        $badge_class = 'badge-warning';
                }
            }
            
            // Calcular progreso
            $horas_acumuladas = $practica['horas_acumuladas'] ?? 0;
            $total_horas = $practica['total_horas'] ?? 128;
            $progreso_porcentaje = $practica['progreso_porcentaje'] ?? 0;
            
            if ($practica && $total_horas > 0) {
                $progreso_porcentaje = min(100, round(($horas_acumuladas / $total_horas) * 100));
            }
        ?>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-l-4 border-<?php echo $config['color_class']; ?>-500 hover:shadow-xl transition-shadow duration-300">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-primary-blue"><?php echo $config['titulo']; ?></h3>
                <span class="<?php echo $badge_class; ?>">
                    <?php echo htmlspecialchars($estado); ?>
                </span>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Progreso</span>
                        <span class="font-semibold">
                            <?php echo $horas_acumuladas; ?>/<?php echo $total_horas; ?> horas
                        </span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill bg-<?php echo $config['color_class']; ?>-500" 
                             style="width: <?php echo $progreso_porcentaje; ?>%">
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 text-right mt-1">
                        <?php echo $progreso_porcentaje; ?>% completado
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Empresa:</span>
                        <span class="text-sm font-semibold text-primary-blue max-w-[60%] text-right">
                            <?php if ($practica && !empty($practica['empresa_nombre'])): ?>
                                <?php echo htmlspecialchars($practica['empresa_nombre']); ?>
                            <?php else: ?>
                                <span class="text-gray-400 italic">Por asignar</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Periodo:</span>
                        <span class="text-sm font-semibold text-primary-blue">
                            <?php if ($practica && !empty($practica['periodo_academico'])): ?>
                                <?php echo htmlspecialchars($practica['periodo_academico']); ?>
                            <?php else: ?>
                                <span class="text-gray-400">--</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Inicio:</span>
                        <span class="text-sm <?php echo ($practica && !empty($practica['fecha_inicio'])) ? 'text-gray-800' : 'text-gray-400'; ?>">
                            <?php if ($practica && !empty($practica['fecha_inicio'])): ?>
                                <?php 
                                if (isset($practica['fecha_inicio_formateada'])) {
                                    echo $practica['fecha_inicio_formateada'];
                                } else {
                                    echo date('d M Y', strtotime($practica['fecha_inicio']));
                                }
                                ?>
                            <?php else: ?>
                                Por definir
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Fin:</span>
                        <span class="text-sm <?php echo ($practica && !empty($practica['fecha_fin'])) ? 'text-gray-800' : 'text-gray-400'; ?>">
                            <?php if ($practica && !empty($practica['fecha_fin'])): ?>
                                <?php 
                                if (isset($practica['fecha_fin_formateada'])) {
                                    echo $practica['fecha_fin_formateada'];
                                } else {
                                    echo date('d M Y', strtotime($practica['fecha_fin']));
                                }
                                ?>
                            <?php else: ?>
                                Por definir
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <?php if ($practica && !empty($practica['supervisor_empresa'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Supervisor:</span>
                        <span class="text-sm font-medium text-gray-700">
                            <?php echo htmlspecialchars($practica['supervisor_empresa']); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($practica && !empty($practica['nombre_docente'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Docente:</span>
                        <span class="text-sm font-medium text-gray-700">
                            <?php echo htmlspecialchars($practica['nombre_docente']); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Mostrar días restantes si está en curso -->
                    <?php if ($estado == 'En curso' && isset($practica['dias_restantes_calc'])): ?>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Días restantes:</span>
                            <span class="text-sm font-bold text-<?php echo $config['color_class']; ?>-600">
                                <?php echo $practica['dias_restantes_calc']; ?> días
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Botón para ver detalles si existe la práctica -->
                <?php if ($practica): ?>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="index.php?c=DashboardEstudiante&a=ver&id=<?php echo $practica['id']; ?>" 
                       class="text-sm text-<?php echo $config['color_class']; ?>-500 hover:text-<?php echo $config['color_class']; ?>-700 font-medium flex items-center justify-center">
                        <i class="fas fa-eye mr-2"></i>
                        Ver detalles completos
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Resumen de módulos -->
    <div class="mt-6 bg-blue-50 rounded-xl p-4 border border-blue-100">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div>
                <span class="text-sm text-gray-600">Módulos completados:</span>
                <span class="block text-2xl font-bold text-green-600">
                    <?php echo $estadisticas['modulos_completados']; ?>
                </span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Módulos en curso:</span>
                <span class="block text-2xl font-bold text-blue-600">
                    <?php echo $estadisticas['modulos_en_curso']; ?>
                </span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Módulos pendientes:</span>
                <span class="block text-2xl font-bold text-yellow-600">
                    <?php echo $estadisticas['modulos_pendientes']; ?>
                </span>
            </div>
        </div>
        <div class="mt-3 text-center">
            <span class="text-sm text-gray-600">Horas totales acumuladas:</span>
            <span class="ml-2 text-lg font-bold text-primary-blue">
                <?php echo $estadisticas['horas_totales_acumuladas']; ?> horas
            </span>
        </div>
    </div>
</div>

<!-- Gráfico de Progreso Semanal -->
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-primary-blue flex items-center">
                <i class="fas fa-chart-bar text-blue-500 mr-3"></i>
                Progreso Semanal de Horas - Módulo 1
            </h3>
            <form method="GET" action="" class="flex items-center">
                <input type="hidden" name="c" value="DashboardEstudiante">
                <input type="hidden" name="a" value="index">
                <select name="semana" onchange="this.form.submit()"
                    class="border rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1" <?php echo ($semana_actual == 1) ? 'selected' : ''; ?>>Semana 1 (<?php echo date('d M', strtotime('-' . (($semana_actual - 1) * 7 + 35) . ' days')); ?>)</option>
                    <option value="2" <?php echo ($semana_actual == 2) ? 'selected' : ''; ?>>Semana 2 (<?php echo date('d M', strtotime('-' . (($semana_actual - 2) * 7 + 28) . ' days')); ?>)</option>
                    <option value="3" <?php echo ($semana_actual == 3) ? 'selected' : ''; ?>>Semana 3 (<?php echo $progreso_semanal['fechas']['rango'] ?? '16-22 Oct'; ?>)</option>
                    <option value="4" <?php echo ($semana_actual == 4) ? 'selected' : ''; ?>>Semana 4 (<?php echo date('d M', strtotime('+' . (($semana_actual - 3) * 7 + 7) . ' days')); ?>)</option>
                    <option value="5" <?php echo ($semana_actual == 5) ? 'selected' : ''; ?>>Semana 5 (<?php echo date('d M', strtotime('+' . (($semana_actual - 3) * 7 + 14) . ' days')); ?>)</option>
                    <option value="6" <?php echo ($semana_actual == 6) ? 'selected' : ''; ?>>Semana 6 (<?php echo date('d M', strtotime('+' . (($semana_actual - 3) * 7 + 21) . ' days')); ?>)</option>
                </select>
            </form>
        </div>
        <div class="p-6">
            <div class="mb-4 flex justify-between items-center">
                <div>
                    <span class="text-sm text-gray-600">Total semana: </span>
                    <span class="font-semibold text-primary-blue">
                        <?php echo $progreso_semanal['total_semana'] ?? 0; ?> horas
                    </span>
                </div>
                <div>
                    <span class="text-sm text-gray-600">Promedio diario: </span>
                    <span class="font-semibold text-primary-blue">
                        <?php echo $progreso_semanal['promedio_diario'] ?? 0; ?> horas/día
                    </span>
                </div>
            </div>
            <div class="chart-container" style="height: 300px;">
                <canvas id="progresoSemanalChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Últimas Asistencias -->
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-primary-blue flex items-center">
                <i class="fas fa-history text-blue-500 mr-3"></i>
                Últimas Asistencias
            </h3>
        </div>
        <div class="p-6">
            <?php if (!empty($asistencias_recientes)): ?>
                <div class="space-y-3">
                    <?php foreach ($asistencias_recientes as $asistencia):
                        $horas = $asistencia['horas_acumuladas'] ?? 0;
                        $hora_entrada = $asistencia['hora_entrada'] ?? '--:--';
                        $hora_salida = $asistencia['hora_salida'] ?? '--:--';
                    ?>
                        <div class="flex justify-between items-center p-3 <?php echo $horas >= 8 ? 'bg-green-50' : ($horas >= 4 ? 'bg-yellow-50' : 'bg-blue-50'); ?> rounded-lg">
                            <div>
                                <span class="font-medium text-gray-900">
                                    <?php echo date('d M Y', strtotime($asistencia['fecha'])); ?>
                                </span>
                                <p class="text-sm text-gray-600">
                                    <?php echo $horas; ?> horas | <?php echo $hora_entrada; ?> - <?php echo $hora_salida; ?>
                                </p>
                            </div>
                            <span class="badge <?php echo $horas >= 8 ? 'badge-success' : ($horas >= 4 ? 'badge-warning' : 'badge-info'); ?>">
                                <?php echo $horas >= 8 ? 'Completo' : ($horas >= 4 ? 'Medio día' : 'Incompleto'); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No hay asistencias registradas.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Incluir el footer de tu layout -->
<?php require_once 'views/layouts/footer.php'; ?>

<script>
    // Datos para el gráfico desde PHP
    const datosGrafico = {
        labels: <?php echo json_encode($progreso_semanal['labels'] ?? ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']); ?>,
        datos: <?php echo json_encode($progreso_semanal['datos'] ?? [8.75, 7.75, 8.75, 3.5, 0, 0]); ?>,
        total: <?php echo $progreso_semanal['total_semana'] ?? 0; ?>,
        promedio: <?php echo $progreso_semanal['promedio_diario'] ?? 0; ?>
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar gráfico de progreso semanal
        inicializarGraficoProgresoSemanal();

        // También puedes inicializar el gráfico de semanas anteriores si lo quieres
        inicializarGraficoSemanasAnteriores();
    });

    function inicializarGraficoProgresoSemanal() {
        const ctx = document.getElementById('progresoSemanalChart');
        if (!ctx) return;

        // Destruir gráfico existente si hay
        if (window.progresoChart instanceof Chart) {
            window.progresoChart.destroy();
        }

        window.progresoChart = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: datosGrafico.labels,
                datasets: [{
                    label: 'Horas trabajadas',
                    data: datosGrafico.datos,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(59, 130, 246)',
                        'rgb(59, 130, 246)',
                        'rgb(59, 130, 246)',
                        'rgb(59, 130, 246)',
                        'rgb(59, 130, 246)'
                    ],
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.raw} horas`;
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: {
                            size: 12
                        },
                        bodyFont: {
                            size: 14
                        },
                        padding: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Horas',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            stepSize: 2,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Días de la semana',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Agregar línea de promedio
        agregarLineaPromedio(window.progresoChart, datosGrafico.promedio);
    }

    function agregarLineaPromedio(chart, promedio) {
        chart.data.datasets.push({
            type: 'line',
            label: 'Promedio',
            data: [promedio, promedio, promedio, promedio, promedio, promedio],
            borderColor: 'rgba(239, 68, 68, 0.8)',
            borderWidth: 2,
            borderDash: [5, 5],
            pointRadius: 0,
            fill: false,
            tension: 0
        });

        chart.update();
    }

    // Opcional: Gráfico de semanas anteriores (comparativa)
    function inicializarGraficoSemanasAnteriores() {
        const ctx = document.getElementById('semanasAnterioresChart');
        if (!ctx) return;

        // Datos de ejemplo para semanas anteriores
        const semanasData = {
            labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6'],
            datos: [45, 48, 28.75, 48, 43.5, 40]
        };

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: semanasData.labels,
                datasets: [{
                    label: 'Horas por semana',
                    data: semanasData.datos,
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderColor: 'rgb(139, 92, 246)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Horas totales'
                        }
                    }
                }
            }
        });
    }
</script>