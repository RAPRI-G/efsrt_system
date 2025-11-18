<?php require_once 'views/layouts/header.php'; ?>

<!-- Contenido específico de la página -->
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestión de Prácticas EFSRT</h1>
            <p class="text-gray-600 mt-2">Administra las experiencias formativas de los estudiantes</p>
        </div>
        <a href="index.php?c=Practica&a=registrar" 
           class="bg-primary-blue text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition-colors duration-300 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Nueva Práctica</span>
        </a>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $_GET['msg'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
            <?php echo $_GET['msg'] === 'success' ? '✅ Práctica registrada correctamente!' : '❌ Error al procesar la solicitud'; ?>
        </div>
    <?php endif; ?>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Prácticas</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo count($practicas); ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">En Proceso</p>
                    <p class="text-2xl font-bold text-yellow-600">2</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-sync-alt text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completadas</p>
                    <p class="text-2xl font-bold text-green-600">0</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pendientes</p>
                    <p class="text-2xl font-bold text-gray-600"><?php echo count($practicas); ?></p>
                </div>
                <div class="bg-gray-100 p-3 rounded-full">
                    <i class="fas fa-clock text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de prácticas -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Lista de Prácticas Registradas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Módulo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periodo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach($practicas as $practica): ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-semibold mr-3">
                                    <?php echo substr($practica['nom_est'], 0, 1); ?>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        <?php echo $practica['ap_est'] . ' ' . $practica['am_est'] . ' ' . $practica['nom_est']; ?>
                                    </div>
                                    <div class="text-xs text-gray-500">DNI: <?php echo $practica['dni_est']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo $practica['nom_progest'] ?? 'No asignado'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo $practica['razon_social']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo $practica['tipo_efsrt'] == 'modulo1' ? 'bg-blue-100 text-blue-800' : 
                                          ($practica['tipo_efsrt'] == 'modulo2' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800'); ?>">
                                <?php echo strtoupper($practica['tipo_efsrt'] ?? 'No asignado'); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $practica['periodo_academico_efsrt'] ?? 'N/A'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="index.php?c=Practica&a=generarDocumentos&id=<?php echo $practica['id']; ?>" 
                               class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300 mr-2">
                                <i class="fas fa-file-alt mr-1"></i> Documentos
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>