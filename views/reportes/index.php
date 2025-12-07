<link rel="stylesheet" href="assets/css/reportes.css">
<!-- Main Content -->
<div class="p-6">
    <!-- Área de Bienvenida -->
    <div class="mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard de Reportes</h1>
            <p class="text-gray-600">Resumen estadístico del sistema de prácticas ESFRH</p>
            <p class="text-sm text-gray-500 mt-1" id="fecha-actualizacion">
                Actualizado: <?php echo date('d/m/Y H:i'); ?>
            </p>
        </div>
    </div>

    <!-- Solo Dashboard - Eliminadas las tabs y filtros -->
    <div id="tabContent">
        <!-- Dashboard Simplificado -->
        <div id="tab-dashboard" class="tab-content active">
            <!-- Estadísticas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="card-gradient-1 text-white p-6 rounded-2xl stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-200 text-sm font-medium">Total Estudiantes</p>
                            <h3 class="text-3xl font-bold mt-2" id="total-estudiantes">
                                <?php echo isset($estadisticas['total_estudiantes']) ? $estadisticas['total_estudiantes'] : '0'; ?>
                            </h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <i class="fas fa-user-graduate text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-blue-200">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span id="tendencia-estudiantes">Actualizando...</span>
                    </div>
                </div>

                <div class="card-gradient-2 text-white p-6 rounded-2xl stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Prácticas Activas</p>
                            <h3 class="text-3xl font-bold mt-2" id="practicas-activas">
                                <?php echo isset($estadisticas['total_practicas']) ? $estadisticas['total_practicas'] : '0'; ?>
                            </h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <i class="fas fa-briefcase text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-blue-100">
                        <i class="fas fa-user-check mr-2"></i>
                        <span id="tendencia-practicas">Actualizando...</span>
                    </div>
                </div>

                <div class="card-gradient-3 text-white p-6 rounded-2xl stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Horas Cumplidas</p>
                            <h3 class="text-3xl font-bold mt-2" id="horas-cumplidas">
                                <?php echo isset($estadisticas['horas_cumplidas']) ? $estadisticas['horas_cumplidas'] : '0'; ?>
                            </h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-green-100">
                        <i class="fas fa-trophy mr-2"></i>
                        <span id="tendencia-horas">Actualizando...</span>
                    </div>
                </div>

                <div class="card-gradient-4 text-white p-6 rounded-2xl stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-200 text-sm font-medium">Tasa Finalización</p>
                            <h3 class="text-3xl font-bold mt-2" id="tasa-finalizacion">
                                <?php echo isset($estadisticas['tasa_finalizacion']) ? $estadisticas['tasa_finalizacion'] : '0'; ?>%
                            </h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <i class="fas fa-chart-bar text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-200">
                        <i class="fas fa-percentage mr-2"></i>
                        <span id="tendencia-finalizacion">Actualizando...</span>
                    </div>
                </div>
            </div>

            <!-- Gráficos Principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico 1: Distribución por Estado -->
                <div class="summary-card">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                        Distribución de Prácticas por Estado
                    </h3>
                    <div class="chart-container">
                        <canvas id="chartEstadoPracticas"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-2">
                        <div class="text-center">
                            <div class="inline-block w-3 h-3 rounded-full bg-blue-500 mr-1"></div>
                            <span class="text-sm">En curso</span>
                            <div class="font-bold" id="count-en-curso">
                                <?php echo isset($estadisticas['practicas_en_curso']) ? $estadisticas['practicas_en_curso'] : '0'; ?>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1"></div>
                            <span class="text-sm">Finalizado</span>
                            <div class="font-bold" id="count-finalizado">
                                <?php echo isset($estadisticas['practicas_finalizadas']) ? $estadisticas['practicas_finalizadas'] : '0'; ?>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-1"></div>
                            <span class="text-sm">Pendiente</span>
                            <div class="font-bold" id="count-pendiente">
                                <?php echo isset($estadisticas['practicas_pendientes']) ? $estadisticas['practicas_pendientes'] : '0'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico 2: Distribución por Módulo -->
                <div class="summary-card">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                        Distribución por Módulo
                    </h3>
                    <div class="chart-container">
                        <canvas id="chartModulos"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-2">
                        <div class="text-center">
                            <div class="inline-block w-3 h-3 rounded-full bg-blue-500 mr-1"></div>
                            <span class="text-sm">Módulo 1</span>
                            <div class="font-bold" id="count-modulo1">0</div>
                        </div>
                        <div class="text-center">
                            <div class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1"></div>
                            <span class="text-sm">Módulo 2</span>
                            <div class="font-bold" id="count-modulo2">0</div>
                        </div>
                        <div class="text-center">
                            <div class="inline-block w-3 h-3 rounded-full bg-purple-500 mr-1"></div>
                            <span class="text-sm">Módulo 3</span>
                            <div class="font-bold" id="count-modulo3">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos Secundarios -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico 3: Evolución Mensual -->
                <div class="summary-card">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-purple-600"></i>
                        Evolución Mensual de Prácticas
                    </h3>
                    <div class="chart-container">
                        <canvas id="chartEvolucionMensual"></canvas>
                    </div>
                </div>

                <!-- Gráfico 4: Top Empresas -->
                <div class="summary-card">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-building mr-2 text-red-600"></i>
                        Top 5 Empresas con más Prácticas
                    </h3>
                    <div class="chart-container">
                        <canvas id="chartTopEmpresas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/reportes.js"></script>