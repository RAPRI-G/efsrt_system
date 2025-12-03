<?php
// views/asistencia/dashboard.php
?>
<link rel="stylesheet" href="assets/css/asistencia.css">
<!-- Área de Bienvenida -->
<div class="mb-8">
    <div>
        <h1 class="text-3xl font-bold text-primary-blue mb-2">Dashboard de Asistencias - Administrador</h1>
        <p class="text-gray-600">Seguimiento de horas y actividades por módulo de cada estudiante</p>
    </div>
</div>

<!-- Dashboard Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card-gradient-1 text-white p-6 rounded-2xl stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-200 text-sm font-medium">Total Estudiantes</p>
                <h3 class="text-3xl font-bold mt-2" id="total-estudiantes">0</h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-blue-200">
            <i class="fas fa-chart-line mr-2"></i>
            <span id="estudiantes-texto">Cargando...</span>
        </div>
    </div>
    
    <div class="card-gradient-2 text-white p-6 rounded-2xl stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Horas Totales</p>
                <h3 class="text-3xl font-bold mt-2" id="horas-totales">0</h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-blue-100">
            <i class="fas fa-user-check mr-2"></i>
            <span id="horas-texto">Cargando...</span>
        </div>
    </div>
    
    <div class="card-gradient-3 text-white p-6 rounded-2xl stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Módulos Completados</p>
                <h3 class="text-3xl font-bold mt-2" id="modulos-completados">0</h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-green-100">
            <i class="fas fa-trophy mr-2"></i>
            <span id="completas-texto">Cargando...</span>
        </div>
    </div>
    
    <div class="card-gradient-4 text-white p-6 rounded-2xl stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-200 text-sm font-medium">Tasa de Cumplimiento</p>
                <h3 class="text-3xl font-bold mt-2" id="tasa-cumplimiento">0%</h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-chart-bar text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-gray-200">
            <i class="fas fa-percentage mr-2"></i>
            <span id="cumplimiento-texto">Cargando...</span>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="relative">
                <input type="text" id="buscarEstudiante" placeholder="Buscar por estudiante..." 
                       class="w-full md:w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <select id="filtroModulo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Todos los módulos</option>
                    <option value="modulo1">Módulo 1</option>
                    <option value="modulo2">Módulo 2</option>
                    <option value="modulo3">Módulo 3</option>
                </select>
                
                <select id="filtroEstado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Todos los estados</option>
                    <option value="completado">Completado</option>
                    <option value="en_curso">En curso</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="no_iniciado">No iniciado</option>
                </select>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <button id="btnRefrescar" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors duration-300 flex items-center">
                <i class="fas fa-sync-alt mr-2"></i> Actualizar
            </button>
        </div>
    </div>
</div>

<!-- Vista de Estudiantes con sus Módulos -->
<div id="vistaEstudiantes" class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-xl font-bold text-primary-blue flex items-center">
            <i class="fas fa-user-graduate text-blue-500 mr-3"></i>
            Progreso de Estudiantes por Módulo
        </h3>
    </div>
    <div class="p-6">
        <div id="listaEstudiantes" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Las tarjetas de estudiantes se generarán dinámicamente -->
            <div class="col-span-3 text-center py-12 text-gray-500" id="cargandoEstudiantes">
                <div class="loading mb-4 mx-auto"></div>
                <p class="text-lg">Cargando estudiantes...</p>
            </div>
        </div>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
        <div class="text-sm text-gray-500" id="contador-estudiantes">
            Mostrando <span id="estudiantes-mostrados">0</span> de <span id="estudiantes-totales">0</span> estudiantes
        </div>
        <div class="flex space-x-2" id="paginacion-estudiantes">
            <!-- Los controles de paginación se generarán dinámicamente -->
        </div>
    </div>
</div>

<!-- Modal de Detalles de Estudiante -->
<!-- Modal de Detalles de Estudiante - VERSIÓN MEJORADA (COMO TU HTML ESTÁTICO) -->
<div id="modalDetallesEstudiante" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-info-circle mr-3"></i>
                <span id="modalTitulo">Detalles de Asistencias por Módulo</span>
            </h3>
            <button id="cerrarModalDetalles" class="text-white hover:text-blue-200 transition-colors duration-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="detalle-container">
                <!-- Sidebar de módulos -->
                <div class="modulos-sidebar" id="modulosSidebar">
                    <!-- Los módulos se cargarán dinámicamente -->
                </div>
                
                <!-- Contenido principal -->
                <div class="modulos-content" id="modulosContent">
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h4 class="text-lg font-medium mb-2">Selecciona un módulo</h4>
                        <p class="text-sm">Elige un módulo de la lista para ver las actividades realizadas</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="cerrarDetalles" class="btn-secondary flex items-center">
                <i class="fas fa-times mr-2"></i> Cerrar
            </button>
        </div>
    </div>
</div>

<!-- Modal para generar reporte -->
<div id="modalReporte" class="modal">
    <div class="modal-content max-w-md">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-file-pdf mr-3"></i>
                Generar Reporte de Asistencias
            </h3>
            <button class="cerrarModal text-white hover:text-blue-200 transition-colors duration-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="modal-body p-6">
            <form id="formReporte">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rango de Fechas</label>
                        <div class="flex gap-2">
                            <input type="date" id="fechaInicio" name="fecha_inicio" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="date" id="fechaFin" name="fecha_fin" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estudiante (opcional)</label>
                        <select id="estudianteReporte" name="estudiante_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos los estudiantes</option>
                            <!-- Opciones se cargarán dinámicamente -->
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Módulo</label>
                        <select id="moduloReporte" name="modulo" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all">Todos los módulos</option>
                            <option value="modulo1">Módulo 1</option>
                            <option value="modulo2">Módulo 2</option>
                            <option value="modulo3">Módulo 3</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Formato</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="formato" value="pdf" checked class="form-radio text-blue-600">
                                <span class="ml-2">PDF</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="formato" value="excel" class="form-radio text-green-600">
                                <span class="ml-2">Excel</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary cerrarModal flex items-center">
                <i class="fas fa-times mr-2"></i> Cancelar
            </button>
            <button id="btnGenerarReporteConfirmar" class="btn-primary flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> Generar Reporte
            </button>
        </div>
    </div>
</div>

<!-- Script para funcionalidad de asistencias -->
<script src="assets/js/asistencias.js"></script>