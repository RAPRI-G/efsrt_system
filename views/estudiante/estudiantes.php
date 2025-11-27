<?php
// views/estudiante/estudiantes.php
require_once 'views/layouts/header.php';
?>

<!-- Incluir CSS específico -->
<link rel="stylesheet" href="assets/css/estudiantes.css">

<!-- Sistema de Notificaciones -->
<div id="notificationContainer"></div>

<!-- Overlay de Carga -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<!-- Modal de Confirmación -->
<div id="confirmationModal" class="confirmation-modal">
    <div class="confirmation-content">
        <div class="confirmation-header">
            <i id="confirmationIcon" class="confirmation-icon fas fa-exclamation-triangle"></i>
            <h3 id="confirmationTitle">Confirmar acción</h3>
        </div>
        <div class="confirmation-body">
            <p id="confirmationMessage">¿Estás seguro de que deseas realizar esta acción?</p>
            <div class="confirmation-actions">
                <button id="confirmCancel" class="btn-cancel">Cancelar</button>
                <button id="confirmAction" class="btn-confirm">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<div class="p-6">
<!-- Main Content -->
    <!-- Área de Bienvenida -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary-blue mb-2">Gestión de Estudiantes</h1>
            <p class="text-gray-600">Administra la información de los estudiantes registrados en el sistema</p>
        </div>
        <button id="btnNuevoEstudiante" class="bg-primary-blue text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition-colors duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i> Nuevo Estudiante
        </button>
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
                <i class="fas fa-users mr-2"></i>
                <span id="estudiantes-texto">Cargando...</span>
            </div>
        </div>
        
        <div class="card-gradient-2 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Estudiantes Activos</p>
                    <h3 class="text-3xl font-bold mt-2" id="estudiantes-activos">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-100">
                <i class="fas fa-user-check mr-2"></i>
                <span id="activos-texto">Cargando...</span>
            </div>
        </div>
        
        <div class="card-gradient-3 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Estudiantes en Prácticas</p>
                    <h3 class="text-3xl font-bold mt-2" id="estudiantes-practicas">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-briefcase text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-100">
                <i class="fas fa-chart-line mr-2"></i>
                <span id="practicas-texto">Cargando...</span>
            </div>
        </div>
        
        <div class="card-gradient-4 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-200 text-sm font-medium">Estudiantes por Programa</p>
                    <h3 class="text-3xl font-bold mt-2" id="total-programas">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-book text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-200">
                <i class="fas fa-graduation-cap mr-2"></i>
                <span id="programas-texto">Cargando...</span>
            </div>
        </div>
    </div>
    
    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="relative">
                    <input type="text" id="buscarEstudiante" placeholder="Buscar por nombre, DNI..." 
                           class="w-full md:w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    <div id="resultadosBusqueda" class="resultados-busqueda"></div>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <select id="filtroPrograma" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los programas</option>
                        <?php foreach ($programas as $programa): ?>
                            <option value="<?php echo $programa['id']; ?>" <?php echo ($filtros['programa'] ?? '') == $programa['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($programa['nom_progest']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <select id="filtroEstado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los estados</option>
                        <option value="1" <?php echo ($filtros['estado'] ?? '') == '1' ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($filtros['estado'] ?? '') == '0' ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                    
                    <select id="filtroGenero" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los géneros</option>
                        <option value="M" <?php echo ($filtros['genero'] ?? '') == 'M' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="F" <?php echo ($filtros['genero'] ?? '') == 'F' ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button id="btnExportar" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-300 flex items-center">
                    <i class="fas fa-download mr-2"></i> Exportar
                </button>
                <button id="btnRefrescar" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors duration-300 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Actualizar
                </button>
            </div>
        </div>
    </div>
    
    <!-- Gráficos y Estadísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Distribución por Programa -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                    Distribución por Programa
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="programasChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Estado de Prácticas -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-briefcase text-blue-500 mr-3"></i>
                    Estado de Prácticas
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="practicasChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de Estudiantes -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-primary-blue flex items-center">
                <i class="fas fa-list-alt text-blue-500 mr-3"></i>
                Lista de Estudiantes
            </h3>
            <div class="text-sm text-gray-500" id="contador-estudiantes">
                Mostrando <span id="estudiantes-mostrados">0</span> de <span id="estudiantes-totales">0</span> estudiantes
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 tabla-estudiantes">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Estudiante</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">DNI</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Programa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Matrícula</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="tabla-estudiantes-body">
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin text-lg mb-2"></i>
                            <p>Cargando datos de estudiantes...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div class="text-sm text-gray-500" id="info-paginacion">
                Página 1 de 1
            </div>
            <div class="flex space-x-2" id="paginacion">
                <!-- Los controles de paginación se generarán dinámicamente -->
            </div>
        </div>
    </div>
</main>

<!-- Modal para Agregar/Editar Estudiante -->
<div id="estudianteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-4xl mx-4 modal-content">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-primary-blue" id="modalTitulo">Nuevo Estudiante</h3>
            <button id="cerrarModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="formEstudiante">
                <input type="hidden" id="estudianteId">
                <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo SessionHelper::getCSRFToken(); ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="dni_est" class="block text-sm font-medium text-gray-700 mb-1">DNI *</label>
                        <input type="text" id="dni_est" name="dni_est" maxlength="8" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="prog_estudios" class="block text-sm font-medium text-gray-700 mb-1">Programa de Estudios</label>
                        <select id="prog_estudios" name="prog_estudios"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar programa</option>
                            <?php foreach ($programas as $programa): ?>
                                <option value="<?php echo $programa['id']; ?>">
                                    <?php echo htmlspecialchars($programa['nom_progest']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="ap_est" class="block text-sm font-medium text-gray-700 mb-1">Apellido Paterno *</label>
                        <input type="text" id="ap_est" name="ap_est" maxlength="40" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="am_est" class="block text-sm font-medium text-gray-700 mb-1">Apellido Materno</label>
                        <input type="text" id="am_est" name="am_est" maxlength="40"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="nom_est" class="block text-sm font-medium text-gray-700 mb-1">Nombres *</label>
                        <input type="text" id="nom_est" name="nom_est" maxlength="40" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="cel_est" class="block text-sm font-medium text-gray-700 mb-1">Celular *</label>
                        <input type="text" id="cel_est" name="cel_est" maxlength="9" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="fecnac_est" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento *</label>
                        <input type="date" id="fecnac_est" name="fecnac_est" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="mailp_est" class="block text-sm font-medium text-gray-700 mb-1">Email Personal</label>
                        <input type="email" id="mailp_est" name="mailp_est" maxlength="40"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="sex_est" class="block text-sm font-medium text-gray-700 mb-1">Género *</label>
                        <select id="sex_est" name="sex_est" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar género</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="dir_est" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" id="dir_est" name="dir_est" maxlength="40"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Campos de matrícula -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h4 class="text-lg font-semibold text-primary-blue mb-4">Información de Matrícula</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="id_matricula" class="block text-sm font-medium text-gray-700 mb-1">ID Matrícula</label>
                            <input type="text" id="id_matricula" name="id_matricula" maxlength="9"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="per_acad" class="block text-sm font-medium text-gray-700 mb-1">Periodo Académico</label>
                            <input type="text" id="per_acad" name="per_acad" maxlength="3"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="turno" class="block text-sm font-medium text-gray-700 mb-1">Turno</label>
                            <select id="turno" name="turno"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccionar turno</option>
                                <option value="Mañana">Mañana</option>
                                <option value="Tarde">Tarde</option>
                                <option value="Noche">Noche</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center mb-6">
                    <input type="checkbox" id="estado" name="estado" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="estado" class="ml-2 block text-sm text-gray-700">Estudiante Activo</label>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" id="cancelarForm" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors duration-300">
                        Guardar Estudiante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles del Estudiante -->
<div id="detalleEstudianteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-3xl mx-4 modal-content">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-primary-blue to-blue-800 text-white rounded-t-2xl">
            <div class="flex items-center">
                <i class="fas fa-user-graduate text-xl mr-3"></i>
                <h3 class="text-xl font-bold" id="detalleModalTitulo">Detalles del Estudiante</h3>
            </div>
            <button id="cerrarDetalleModal" class="text-white hover:text-blue-200 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 max-h-[70vh] overflow-y-auto">
            <!-- Encabezado con avatar y datos principales -->
            <div class="flex flex-col md:flex-row items-start md:items-center mb-8 pb-6 border-b border-gray-200">
                <div id="detalleAvatar" class="h-20 w-20 rounded-full flex items-center justify-center text-white font-bold text-2xl mr-0 md:mr-6 mb-4 md:mb-0 shadow-lg avatar-estudiante">
                </div>
                <div class="flex-1">
                    <h2 id="detalleNombre" class="text-2xl font-bold text-primary-blue mb-1"></h2>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <span id="detallePrograma" class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full"></span>
                        <span id="detalleEstado" class="text-sm font-medium px-3 py-1 rounded-full"></span>
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-id-card mr-2 text-primary-blue"></i>
                            <span id="detalleDni"></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-birthday-cake mr-2 text-primary-blue"></i>
                            <span id="detalleNacimiento"></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-primary-blue"></i>
                            <span id="detalleMatricula"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Secciones organizadas con tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información de Contacto -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <h4 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
                        <i class="fas fa-address-book mr-2"></i>
                        Información de Contacto
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-mobile-alt mr-2 text-blue-500"></i>
                                Celular:
                            </span>
                            <span id="detalleCelular" class="text-sm text-gray-600 text-right"></span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                Email Personal:
                            </span>
                            <span id="detalleEmailPersonal" class="text-sm text-gray-600 text-right break-all"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Información de Ubicación -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <h4 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Información de Ubicación
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-home mr-2 text-blue-500"></i>
                                Dirección:
                            </span>
                            <span id="detalleDireccion" class="text-sm text-gray-600 text-right"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Información Académica -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 md:col-span-2">
                    <h4 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Información Académica
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Programa:</span>
                            <span id="detalleProgramaNombre" class="text-sm text-gray-600"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Periodo:</span>
                            <span id="detallePeriodo" class="text-sm text-gray-600"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Turno:</span>
                            <span id="detalleTurno" class="text-sm text-gray-600"></span>
                        </div>
                    </div>
                </div>

                <!-- Información de Prácticas -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 md:col-span-2">
                    <h4 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
                        <i class="fas fa-briefcase mr-2"></i>
                        Información de Prácticas
                    </h4>
                    <div id="detallePracticasInfo">
                        <!-- Se llenará dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-between items-center">
            <button id="cerrarDetalleBtn" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300 flex items-center">
                <i class="fas fa-times mr-2"></i> Cerrar
            </button>
            <div class="flex space-x-3">
                <button id="imprimirDetalle" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-300 flex items-center">
                    <i class="fas fa-print mr-2"></i> Imprimir
                </button>
                <button id="editarDesdeDetalle" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors duration-300 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Editar Estudiante
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Incluir JavaScript específico -->
<script src="assets/js/estudiantes.js"></script>

<?php require_once 'views/layouts/footer.php'; ?>