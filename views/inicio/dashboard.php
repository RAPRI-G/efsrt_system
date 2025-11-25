<?php
// Incluir el header
include 'views/layouts/header.php';
?>

<!-- Main Content -->

    <div class="p-6">
        <!-- Área de Bienvenida -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-primary-blue mb-2">Dashboard de Experiencias Formativas</h1>
            <p class="text-gray-600">Bienvenido al sistema de gestión de prácticas EFSRT - ESFRH</p>
        </div>
        
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card-gradient-1 text-white p-6 rounded-2xl stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-200 text-sm font-medium">Prácticas Activas</p>
                        <h3 class="text-3xl font-bold mt-2" id="practicas-activas">0</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-briefcase text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-blue-200">
                    <i class="fas fa-chart-line mr-2 text-green-400"></i>
                    <span id="practicas-texto">Cargando...</span>
                </div>
            </div>
            
            <div class="card-gradient-2 text-white p-6 rounded-2xl stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Estudiantes</p>
                        <h3 class="text-3xl font-bold mt-2" id="total-estudiantes">0</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-blue-100">
                    <i class="fas fa-users mr-2"></i>
                    <span id="estudiantes-texto">Cargando...</span>
                </div>
            </div>
            
            <div class="card-gradient-3 text-white p-6 rounded-2xl stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Empresas</p>
                        <h3 class="text-3xl font-bold mt-2" id="total-empresas">0</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-green-100">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span id="empresas-texto">Cargando...</span>
                </div>
            </div>
            
            <div class="card-gradient-4 text-white p-6 rounded-2xl stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-200 text-sm font-medium">Docentes</p>
                        <h3 class="text-3xl font-bold mt-2" id="total-docentes">0</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-gray-200">
                    <i class="fas fa-user-check mr-2"></i>
                    <span id="docentes-texto">Cargando...</span>
                </div>
            </div>
        </div>
        
        <!-- Gráficos y Tablas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Distribución de Prácticas por Estado -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-primary-blue flex items-center">
                        <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                        Estado de Prácticas
                    </h3>
                </div>
                <div class="p-6">
                    <div class="chart-container">
                        <canvas id="estadoPracticasChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Prácticas en Curso -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-primary-blue flex items-center">
                        <i class="fas fa-tasks text-blue-500 mr-3"></i>
                        Prácticas en Curso
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4" id="practicas-en-curso">
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>Cargando prácticas...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Segunda fila de gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Distribución por Módulo -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-primary-blue flex items-center">
                        <i class="fas fa-chart-bar text-blue-500 mr-3"></i>
                        Distribución por Módulo
                    </h3>
                </div>
                <div class="p-6">
                    <div class="chart-container">
                        <canvas id="modulosChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Actividad Reciente -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-primary-blue flex items-center">
                        <i class="fas fa-clock text-blue-500 mr-3"></i>
                        Actividad Reciente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4" id="actividad-reciente">
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-database text-blue-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-primary-blue">Sistema Iniciado</p>
                                <p class="text-sm text-gray-600">Dashboard de experiencias formativas cargado</p>
                                <p class="text-xs text-gray-400 mt-1">Hace unos momentos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla Completa de Prácticas -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-list-alt text-blue-500 mr-3"></i>
                    Resumen de Prácticas EFSRT
                </h3>
                <div class="flex space-x-2">
                    <div class="relative">
                        <select id="filterEstado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all">Todos los estados</option>
                            <option value="En curso">En curso</option>
                            <option value="Finalizado">Finalizado</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>
                    </div>
                    <button id="btnExportar" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-300">
                        <i class="fas fa-download mr-2"></i>Exportar
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Programa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Módulo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Empresa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Horas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tabla-practicas">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-spinner fa-spin text-lg mb-2"></i>
                                <p>Cargando datos de prácticas...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<script src="assets/js/dashboard.js"></script>
<?php
// Incluir el footer
include 'views/layouts/footer.php';
?>