<?php
// Incluir el header
include 'views/layouts/header.php';
?>

<!-- Main Content -->
<link rel="stylesheet" href="assets/css/modulos.css">

<div class="p-6">
    <!-- Área de Bienvenida -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary-blue mb-2">Gestión de Módulos EFSRT</h1>
            <p class="text-gray-600">Administra el progreso de los estudiantes en sus módulos de prácticas</p>
        </div>
        <div class="flex space-x-3">
            <button id="btnVistaProgreso" class="btn-vista-progreso flex items-center">
                <i class="fas fa-chart-line mr-2"></i> Vista Progreso
            </button>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card-gradient-1 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200 text-sm font-medium">Estudiantes con Módulos</p>
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
                    <p class="text-blue-100 text-sm font-medium">Módulos Activos</p>
                    <h3 class="text-3xl font-bold mt-2" id="modulos-activos">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-play-circle text-2xl"></i>
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
                    <p class="text-green-100 text-sm font-medium">Módulos Finalizados</p>
                    <h3 class="text-3xl font-bold mt-2" id="modulos-finalizados">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-100">
                <i class="fas fa-trophy mr-2"></i>
                <span id="finalizados-texto">Cargando...</span>
            </div>
        </div>

        <div class="card-gradient-4 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-200 text-sm font-medium">Progreso Promedio</p>
                    <h3 class="text-3xl font-bold mt-2" id="progreso-promedio">0%</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-200">
                <i class="fas fa-layer-group mr-2"></i>
                <span id="promedio-texto">Cargando...</span>
            </div>
        </div>
    </div>

    <!-- Gráficos y Estadísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Distribución por Tipo de Módulo -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                    Distribución por Tipo de Módulo
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="tipoModuloChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución por Estado -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-check-circle text-blue-500 mr-3"></i>
                    Estado de Módulos
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="estadoModuloChart"></canvas>
                </div>
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
                    <select id="filtroPrograma" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los programas</option>
                    </select>

                    <select id="filtroEstado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los estados</option>
                        <option value="completado">Completado</option>
                        <option value="en-progreso">En progreso</option>
                        <option value="pendiente">Pendiente</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Botón Exportar ya existe -->
                <button id="btnExportar" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-300 flex items-center btn-exportar">
                    <i class="fas fa-download mr-2"></i> Exportar CSV
                </button>

                <button id="btnRefrescar" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors duration-300 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Actualizar
                </button>
            </div>
        </div>
    </div>

    <!-- Vista de Progreso por Estudiante -->
    <div id="vistaProgreso">
        <div class="grid grid-cols-1 gap-6" id="listaEstudiantes">
            <!-- Las tarjetas de estudiantes se generarán dinámicamente -->
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                <p>Cargando datos de estudiantes...</p>
            </div>
        </div>

        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-500" id="contador-estudiantes">
                Mostrando <span id="estudiantes-mostrados">0</span> de <span id="estudiantes-totales">0</span> estudiantes
            </div>
            <div class="flex space-x-2" id="paginacion-estudiantes">
                <!-- Los controles de paginación se generarán dinámicamente -->
            </div>
        </div>
    </div>
</div>
</main>

<script src="assets/js/modulos.js"></script>

<?php
// Incluir el footer
include 'views/layouts/footer.php';
?>