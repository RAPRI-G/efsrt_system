<?php
// Preparar datos para JavaScript
$modulos_js = json_encode($estadisticas_modulos ?? []);
$practicas_js = json_encode($practicas_activas ?? []);
$asistencias_js = json_encode($asistencias_por_practica ?? []);
$estudiante_js = json_encode($estudiante ?? []);
$modulo_activo_js = json_encode($modulo_activo ?? 'modulo1');
?>
<!-- Contenido Principal -->

<!-- En la sección head de la vista -->
<link rel="stylesheet" href="assets/css/asistencias-estudiante.css">
<!-- Área de Bienvenida -->
<div class="mb-8 welcome-area">
    <h1 class="text-3xl font-bold text-primary-blue mb-2">Mis Asistencias</h1>
    <p class="text-gray-600">Registro de horas y actividades realizadas en tus prácticas profesionales</p>
    <div class="mt-2 text-sm text-gray-500">
        <i class="fas fa-id-card mr-1"></i> Estudiante:
        <span class="font-medium"><?php echo htmlspecialchars($estudiante['ap_est'] . ' ' . ($estudiante['am_est'] ?? '') . ', ' . $estudiante['nom_est']); ?></span>
        | <i class="fas fa-graduation-cap ml-3 mr-1"></i> Programa:
        <span class="font-medium"><?php echo htmlspecialchars($estudiante['nom_progest'] ?? 'No asignado'); ?></span>
    </div>
</div>

<!-- Tabs para seleccionar módulo -->
<div class="mb-6 tabs-container">
    <div class="flex space-x-1 border-b">
        <?php foreach ($estadisticas_modulos as $modulo_id => $modulo): 
            $activo = ($modulo_id === $modulo_activo) ? 'active' : '';
            $icono = $modulo['estado'] === 'completado' ? 'fa-check-circle' : 
                    ($modulo['estado'] === 'en_curso' ? 'fa-play-circle' : 'fa-clock');
            $color = $modulo['estado'] === 'completado' ? 'text-green-500' : 
                    ($modulo['estado'] === 'en_curso' ? 'text-blue-500' : 'text-amber-500');
        ?>
            <button class="tab-button <?php echo $activo; ?>" 
                    data-tab="<?php echo $modulo_id; ?>">
                <i class="fas <?php echo $icono; ?> <?php echo $color; ?> mr-2"></i>
                Módulo <?php echo substr($modulo_id, -1); ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<!-- Contenedor dinámico -->
<div id="contenedor-modulos">
    <!-- El contenido se cargará aquí -->
</div>

<!-- Plantilla para módulo (usada por JavaScript) -->
<template id="template-modulo">
    <div class="tab-content fade-in">
        <div class="card">
            <div class="card-header">
                <h3 class="text-xl font-bold text-[#0C1F36] flex items-center">
                    <i class="fas" id="modulo-icon"></i>
                    <span id="modulo-nombre">Módulo</span>
                </h3>
            </div>
            <div class="card-content">
                <!-- Información del Módulo -->
                <div class="info-grid mb-6" id="info-modulo">
                    <!-- Información dinámica -->
                </div>

                <!-- Resumen del Módulo -->
                <div class="resumen-modulo mb-6">
                    <h5 class="font-semibold text-gray-700 mb-3">Resumen del Módulo</h5>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value" id="total-asistencias">0</div>
                            <div class="stat-label">Días Asistidos</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="horas-registradas">0h</div>
                            <div class="stat-label">Horas Totales</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="porcentaje-progreso">0%</div>
                            <div class="stat-label">Progreso</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progreso del Módulo</span>
                            <span><span id="horas-actuales">0h</span> / <span id="horas-requeridas">0h</span> requeridas</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" id="barra-progreso" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="mt-4 text-center" id="boton-nueva-asistencia">
                        <!-- Botón se mostrará solo si el módulo está en curso -->
                    </div>
                </div>

                <!-- Registro de Asistencias -->
                <div class="mb-6">
                    <h4 class="font-bold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        Mis Asistencias Registradas
                    </h4>

                    <div id="contenedor-asistencias">
                        <!-- Asistencias se cargarán dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Plantilla para información de módulo -->
<template id="template-info-modulo">
    <div class="info-item">
        <div class="info-label">{label}</div>
        <div class="info-value">{value}</div>
    </div>
</template>

<!-- Plantilla para asistencia -->
<template id="template-asistencia">
    <div class="dia-row" data-id="{id}">
        <div class="dia-fecha">
            {fecha}
            <span class="dia-dia">({dia})</span>
        </div>
        <div class="dia-horas">
            <div class="hora-entrada">
                <span class="hora-badge">{am_pm_entrada}</span>
                {hora_entrada}
            </div>
            <div class="hora-salida">
                <span class="hora-badge">{am_pm_salida}</span>
                {hora_salida}
            </div>
        </div>
        <div class="dia-actividad">{actividad}</div>
        <div class="dia-total horas-formateadas">{horas}h</div>
        <div class="action-buttons">
            {botones}
        </div>
    </div>
</template>


<!-- Modal para Nueva/Editar Asistencia -->
<div id="modalAsistencia" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-white flex items-center" id="modal-titulo">
                <i class="fas fa-calendar-plus mr-3"></i>
                Registrar Nueva Asistencia
            </h3>
            <button id="cerrarModal" class="text-white hover:text-blue-200 transition-colors duration-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="formAsistencia">
                <input type="hidden" id="asistenciaId" name="id">
                <input type="hidden" id="practicaId" name="practica_id">
                <input type="hidden" name="csrf_token" value="<?php echo SessionHelper::getCSRFToken(); ?>">

                <div class="form-grid">
                    <div class="input-group">
                        <label for="fecha" class="input-label">Fecha *</label>
                        <input type="date" id="fecha" name="fecha" class="input-field" required>
                    </div>

                    <div class="input-group">
                        <label for="horaEntrada" class="input-label">Hora de Entrada *</label>
                        <div class="hora-input-container">
                            <input type="text" id="horaEntrada" name="hora_entrada" class="input-field hora-input-field"
                                placeholder="Ej: 8:15, 2.25pm, 14:30" required>
                            <button type="button" class="toggle-format-btn" data-target="horaEntrada" title="Cambiar formato">
                                <i class="fas fa-clock"></i>
                            </button>
                        </div>
                        <div class="format-info">Formato: HH:MM (24h) o 8.30am / 2.25pm</div>
                    </div>

                    <div class="input-group">
                        <label for="horaSalida" class="input-label">Hora de Salida *</label>
                        <div class="hora-input-container">
                            <input type="text" id="horaSalida" name="hora_salida" class="input-field hora-input-field"
                                placeholder="Ej: 16:45, 5.30pm, 17:00" required>
                            <button type="button" class="toggle-format-btn" data-target="horaSalida" title="Cambiar formato">
                                <i class="fas fa-clock"></i>
                            </button>
                        </div>
                        <div class="format-info">Formato: HH:MM (24h) o 4.45pm / 17:30</div>
                    </div>

                    <div class="input-group">
                        <label for="horasCalculadas" class="input-label">Horas Totales</label>
                        <div class="flex items-center">
                            <input type="text" id="horasCalculadas" class="input-field" readonly>
                            <span class="ml-2 text-gray-600">horas</span>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label for="actividad" class="input-label">Actividad Realizada *</label>
                    <textarea id="actividad" name="actividad" class="textarea-field"
                        placeholder="Describe las actividades realizadas durante la jornada..." required></textarea>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-blue-700 text-sm">
                                <strong>Nota:</strong> Las horas se calcularán automáticamente según la diferencia entre hora de entrada y salida.
                                <br><strong>Formatos aceptados:</strong> 8:15, 08:15, 8.15am, 2.30pm, 14:30, 2.25pm
                                <br><strong>Horario sugerido:</strong> 8:00 AM - 4:00 PM (8 horas)
                            </p>
                        </div>
                    </div>
                </div>

                <div id="previsualizacionHoras" class="bg-gray-50 border border-gray-200 rounded-lg p-3 hidden">
                    <h5 class="font-bold text-gray-700 mb-2">Previsualización de Horario</h5>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-600">Entrada:</span>
                            <span id="prevEntrada" class="font-bold ml-2">--:--</span>
                            <span id="prevEntradaAMPM" class="text-sm text-gray-600"></span>
                        </div>
                        <div>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Salida:</span>
                            <span id="prevSalida" class="font-bold ml-2">--:--</span>
                            <span id="prevSalidaAMPM" class="text-sm text-gray-600"></span>
                        </div>
                        <div class="border-l pl-4">
                            <span class="text-sm text-gray-600">Total:</span>
                            <span id="prevTotal" class="font-bold ml-2 text-blue-600">0h</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" id="cancelarAsistencia" class="btn-secondary">
                Cancelar
            </button>
            <button type="button" id="guardarAsistencia" class="btn-primary">
                Guardar Asistencia
            </button>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div id="modalConfirmacion" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                Confirmar Acción
            </h3>
        </div>
        <div class="modal-body">
            <p id="mensajeConfirmacion" class="text-gray-700">¿Estás seguro de que deseas eliminar esta asistencia?</p>
        </div>
        <div class="modal-footer">
            <button id="cancelarAccion" class="btn-secondary">
                Cancelar
            </button>
            <button id="confirmarAccion" class="btn-danger">
                Confirmar
            </button>
        </div>
    </div>
</div>

<!-- Notificaciones -->
<div id="notificaciones-container"></div>

<!-- JavaScript con datos de PHP -->
<script>
// En tu index.php, verifica que los datos se pasen bien
console.log('Datos desde PHP:');
console.log('Módulos:', <?php echo json_encode(array_keys($estadisticas_modulos ?? [])); ?>);
console.log('Módulo activo:', <?php echo json_encode($modulo_activo ?? 'modulo1'); ?>);

window.datosEstudiante = {
    modulos: <?php echo json_encode($estadisticas_modulos ?? []); ?>,
    practicas: <?php echo json_encode($practicas_activas ?? []); ?>,
    asistencias: <?php echo json_encode($asistencias_por_practica ?? []); ?>,
    estudiante: <?php echo json_encode($estudiante ?? []); ?>,
    moduloActivo: <?php echo json_encode($modulo_activo ?? 'modulo1'); ?>,
    baseUrl: 'index.php?c=AsistenciaEstudiante&a=',
    csrfToken: '<?php echo SessionHelper::getCSRFToken(); ?>'
};
</script>

<!-- Incluir JavaScript -->
<script src="assets/js/asistencias-estudiante.js"></script>