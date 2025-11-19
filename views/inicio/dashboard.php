<?php require_once 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto">
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard EFSRT</h1>
        <p class="text-gray-600 mt-2">Bienvenido al sistema de Experiencias Formativas en Situaciones Reales de Trabajo</p>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Prácticas -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Prácticas</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $estadisticas['total_practicas']; ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex space-x-2 text-xs">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">M1: <?php echo $estadisticas['practicas_modulo1']; ?></span>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">M2: <?php echo $estadisticas['practicas_modulo2']; ?></span>
                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full">M3: <?php echo $estadisticas['practicas_modulo3']; ?></span>
                </div>
            </div>
        </div>

        <!-- Total Estudiantes -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Estudiantes</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $estadisticas['total_estudiantes']; ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500">Registrados en el sistema</p>
            </div>
        </div>

        <!-- Total Empresas -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Empresas Convenio</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $estadisticas['total_empresas']; ?></p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-building text-orange-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500">Convenios activos</p>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Acciones Rápidas</p>
                    <p class="text-lg font-bold text-primary-blue">Gestionar</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-bolt text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <a href="index.php?c=Practica&a=registrar" class="block text-sm text-primary-blue hover:text-blue-800 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Nueva Práctica
                </a>
                <a href="index.php?c=Estudiante&a=index" class="block text-sm text-primary-blue hover:text-blue-800 transition-colors">
                    <i class="fas fa-list mr-1"></i> Ver Estudiantes
                </a>
                <a href="index.php?c=Documento&a=index" class="block text-sm text-primary-blue hover:text-blue-800 transition-colors">
                    <i class="fas fa-file-alt mr-1"></i> Generar Documentos
                </a>
            </div>
        </div>
    </div>

    <!-- Últimas Prácticas Registradas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Prácticas Recientes -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-clock text-yellow-500 mr-2"></i>
                    Prácticas Recientes
                </h2>
            </div>
            <div class="p-6">
                <?php if (!empty($practicas)): ?>
                    <div class="space-y-4">
                        <?php 
                        $practicas_recientes = array_slice($practicas, 0, 5);
                        foreach($practicas_recientes as $practica): 
                        ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-semibold">
                                    <?php echo substr($practica['nom_est'], 0, 1); ?>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        <?php echo $practica['ap_est'] . ' ' . $practica['am_est']; ?>
                                    </p>
                                    <p class="text-xs text-gray-500"><?php echo $practica['razon_social']; ?></p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php echo $practica['tipo_efsrt'] == 'modulo1' ? 'bg-blue-100 text-blue-800' : 
                                          ($practica['tipo_efsrt'] == 'modulo2' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800'); ?>">
                                <?php echo strtoupper($practica['tipo_efsrt'] ?? 'N/A'); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">No hay prácticas registradas</p>
                        <a href="index.php?c=Practica&a=registrar" class="text-primary-blue hover:text-blue-800 text-sm mt-2 inline-block">
                            Registrar primera práctica
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Resumen por Módulos -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-chart-pie text-green-500 mr-2"></i>
                    Distribución por Módulos
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Módulo 1 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-700 font-bold">1</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Módulo 1</p>
                                <p class="text-xs text-gray-500">Primer Año</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-blue-700"><?php echo $estadisticas['practicas_modulo1']; ?></span>
                    </div>

                    <!-- Módulo 2 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-700 font-bold">2</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Módulo 2</p>
                                <p class="text-xs text-gray-500">Segundo Año</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-green-700"><?php echo $estadisticas['practicas_modulo2']; ?></span>
                    </div>

                    <!-- Módulo 3 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-purple-700 font-bold">3</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Módulo 3</p>
                                <p class="text-xs text-gray-500">Tercer Año</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-purple-700"><?php echo $estadisticas['practicas_modulo3']; ?></span>
                    </div>
                </div>

                <!-- Gráfico simple -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between text-xs text-gray-600 mb-2">
                        <span>Progreso Total</span>
                        <span><?php echo $estadisticas['total_practicas']; ?> prácticas</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" 
                             style="width: <?php echo min(100, ($estadisticas['total_practicas'] / max(1, $estadisticas['total_estudiantes'])) * 100); ?>%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Principales -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="index.php?c=Practica&a=registrar" 
           class="bg-primary-blue text-white p-6 rounded-2xl hover:bg-blue-800 transition-colors duration-300 text-center group">
            <i class="fas fa-plus-circle text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
            <h3 class="font-semibold text-lg mb-2">Nueva Práctica</h3>
            <p class="text-blue-100 text-sm">Registrar nueva experiencia formativa</p>
        </a>

        <a href="index.php?c=Documento&a=index" 
           class="bg-green-600 text-white p-6 rounded-2xl hover:bg-green-700 transition-colors duration-300 text-center group">
            <i class="fas fa-file-alt text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
            <h3 class="font-semibold text-lg mb-2">Generar Documentos</h3>
            <p class="text-green-100 text-sm">Oficios, cartas y formatos</p>
        </a>

        <a href="index.php?c=Reporte&a=index" 
           class="bg-purple-600 text-white p-6 rounded-2xl hover:bg-purple-700 transition-colors duration-300 text-center group">
            <i class="fas fa-chart-bar text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
            <h3 class="font-semibold text-lg mb-2">Ver Reportes</h3>
            <p class="text-purple-100 text-sm">Estadísticas e informes</p>
        </a>
    </div>
</div>
<script>
// 🔐 BLOQUEO COMPLETO DEL BOTÓN ATRÁS EN DASHBOARD
history.pushState(null, null, location.href);
window.onpopstate = function(event) {
    history.go(1);
    // Opcional: Redirigir al dashboard si intenta volver
    window.location.href = 'index.php?c=Inicio&a=index';
};

// Prevenir que se cargue desde cache
window.onpageshow = function(event) {
    if (event.persisted) {
        window.location.reload();
    }
};
</script>

<?php require_once 'views/layouts/footer.php'; ?>