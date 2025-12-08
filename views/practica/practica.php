
<link rel="stylesheet" href="assets/css/practica.css">
<!-- Contenido Principal -->

<div class="p-6">
    <!-- 🔔 Contenedor de Notificaciones SOLO para prácticas -->
    <div class="notification-container" id="notificationContainerPracticas"></div>

    <!-- Área de Bienvenida -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary-blue mb-2">Gestión de Prácticas EFSRT</h1>
            <p class="text-gray-600">Administra y supervisa las prácticas de los estudiantes en empresas</p>
        </div>
        <div class="flex space-x-3">
            <button id="btnNuevaPractica" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-300 flex items-center">
                <i class="fas fa-plus mr-2"></i> Nueva Práctica
            </button>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="dashboardCards">
        <div class="card-gradient-1 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200 text-sm font-medium">Total Prácticas</p>
                    <h3 class="text-3xl font-bold mt-2" id="total-practicas">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-briefcase text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-200">
                <i class="fas fa-chart-line mr-2"></i>
                <span id="practicas-texto">Cargando...</span>
            </div>
        </div>

        <div class="card-gradient-2 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Prácticas Activas</p>
                    <h3 class="text-3xl font-bold mt-2" id="practicas-activas">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-play-circle text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-100">
                <i class="fas fa-user-check mr-2"></i>
                <span id="activas-texto">Cargando...</span>
            </div>
        </div>

        <div class="card-gradient-3 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Prácticas Finalizadas</p>
                    <h3 class="text-3xl font-bold mt-2" id="practicas-finalizadas">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-100">
                <i class="fas fa-trophy mr-2"></i>
                <span id="finalizadas-texto">Cargando...</span>
            </div>
        </div>

        <div class="card-gradient-4 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-200 text-sm font-medium">Horas Acumuladas</p>
                    <h3 class="text-3xl font-bold mt-2" id="horas-acumuladas">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-200">
                <i class="fas fa-layer-group mr-2"></i>
                <span id="horas-texto">Cargando...</span>
            </div>
        </div>
    </div>

    <!-- Gráficos y Estadísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Distribución por Estado de Práctica -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                    Distribución por Estado
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="estadoPracticaChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución por Tipo de Módulo -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-cubes text-blue-500 mr-3"></i>
                    Distribución por Tipo de Módulo
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="tipoModuloChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="relative">
                    <input type="text" id="buscarPractica" placeholder="Buscar por estudiante o empresa..."
                        class="w-full md:w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>

                <div class="flex flex-wrap gap-2">
                    <select id="filtroEstado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los estados</option>
                        <option value="En curso">En curso</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="Pendiente">Pendiente</option>
                    </select>

                    <select id="filtroModulo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los módulos</option>
                        <option value="modulo1">Módulo 1</option>
                        <option value="modulo2">Módulo 2</option>
                        <option value="modulo3">Módulo 3</option>
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

    <!-- Vista de Prácticas - Tabla -->
    <div id="vistaTabla" class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-bold text-primary-blue flex items-center">
                <i class="fas fa-list-ul text-blue-500 mr-3"></i>
                Lista de Prácticas
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="tabla-practicas">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Empresa</th>
                        <th>Módulo</th>
                        <th>Estado</th>
                        <th>Fecha Inicio</th>
                        <th>Horas</th>
                        <th>Docente Supervisor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaPracticasBody">
                    <!-- Las filas se generarán dinámicamente -->
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                <p>Cargando prácticas...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div class="text-sm text-gray-500" id="contador-practicas">
                Mostrando <span id="practicas-mostradas">0</span> de <span id="practicas-totales">0</span> prácticas
            </div>
            <div class="flex space-x-2" id="paginacion-practicas">
                <!-- Los controles de paginación se generarán dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Modales -->
    <?php include __DIR__ . '/modals/nueva_practica.php'; ?>
    <?php include __DIR__ . '/modals/editar_practica.php'; ?>
    <?php include __DIR__ . '/modals/detalles_practica.php'; ?>
    <?php include __DIR__ . '/modals/confirmar_eliminar.php'; ?>


    <!-- Script específico para prácticas -->
    <script src="assets/js/practicas.js"></script>
    <script>
        // Inicializar la aplicación de prácticas
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.practicasApp === 'undefined') {
                window.practicasApp = new PracticasApp();
            }
        });
    </script>