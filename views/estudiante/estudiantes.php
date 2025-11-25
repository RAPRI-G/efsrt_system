<?php
// Incluir el header
include 'views/layouts/header.php';

// Obtener datos del controlador
$estudiantes = $data['estudiantes'] ?? [];
$programas = $data['programas'] ?? [];
$estadisticas = $data['estadisticas'] ?? [];
$filtros = $data['filtros'] ?? [];
?>


<!-- Área de Bienvenida -->
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-primary-blue mb-2">Gestión de Estudiantes</h1>
        <p class="text-gray-600">Administra la información de los estudiantes registrados en el sistema</p>
    </div>
    <button id="btnNuevoEstudiante" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors duration-300 flex items-center">
        <i class="fas fa-plus mr-2"></i> Nuevo Estudiante
    </button>
</div>

<!-- Dashboard Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card-gradient-1 text-white p-6 rounded-2xl stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-200 text-sm font-medium">Total Estudiantes</p>
                <h3 class="text-3xl font-bold mt-2" id="total-estudiantes"><?= $estadisticas['total'] ?? 0 ?></h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-blue-200">
            <i class="fas fa-users mr-2"></i>
            <span id="estudiantes-texto"><?= $estadisticas['total'] ?? 0 ?> registrados</span>
        </div>
    </div>

    <div class="card-gradient-2 text-white p-6 rounded-2xl stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Estudiantes Activos</p>
                <h3 class="text-3xl font-bold mt-2" id="estudiantes-activos"><?= $estadisticas['activos'] ?? 0 ?></h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-blue-100">
            <i class="fas fa-user-check mr-2"></i>
            <span id="activos-texto"><?= $estadisticas['activos'] ?? 0 ?> activos</span>
        </div>
    </div>

    <div class="card-gradient-3 text-white p-6 rounded-2xl stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Estudiantes en Prácticas</p>
                <h3 class="text-3xl font-bold mt-2" id="estudiantes-practicas"><?= $estadisticas['en_practicas'] ?? 0 ?></h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-briefcase text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-green-100">
            <i class="fas fa-chart-line mr-2"></i>
            <span id="practicas-texto"><?= $estadisticas['en_practicas'] ?? 0 ?> en prácticas</span>
        </div>
    </div>

    <div class="card-gradient-4 text-white p-6 rounded-2xl stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-200 text-sm font-medium">Por Género</p>
                <h3 class="text-3xl font-bold mt-2" id="ratio-genero"><?= ($estadisticas['masculinos'] ?? 0) . ':' . ($estadisticas['femeninos'] ?? 0) ?></h3>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-venus-mars text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-gray-200">
            <i class="fas fa-balance-scale mr-2"></i>
            <span id="genero-texto"><?= $estadisticas['masculinos'] ?? 0 ?>M / <?= $estadisticas['femeninos'] ?? 0 ?>F</span>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
    <form id="formFiltros" method="GET" action="index.php">
        <input type="hidden" name="c" value="Estudiante">
        <input type="hidden" name="a" value="index">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="relative">
                    <input type="text" name="busqueda" id="buscarEstudiante" placeholder="Buscar por nombre, DNI..."
                        value="<?= htmlspecialchars($filtros['busqueda'] ?? '') ?>"
                        class="w-full md:w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    <div id="resultadosBusqueda" class="resultados-busqueda"></div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <select name="programa" id="filtroPrograma" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los programas</option>
                        <?php foreach ($programas as $programa): ?>
                            <option value="<?= $programa['id'] ?>" <?= ($filtros['programa'] ?? '') == $programa['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($programa['nom_progest']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="estado" id="filtroEstado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los estados</option>
                        <option value="1" <?= ($filtros['estado'] ?? '') == '1' ? 'selected' : '' ?>>Activo</option>
                        <option value="0" <?= ($filtros['estado'] ?? '') == '0' ? 'selected' : '' ?>>Inactivo</option>
                    </select>

                    <select name="genero" id="filtroGenero" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los géneros</option>
                        <option value="M" <?= ($filtros['genero'] ?? '') == 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= ($filtros['genero'] ?? '') == 'F' ? 'selected' : '' ?>>Femenino</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" id="btnExportar" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-300 flex items-center">
                    <i class="fas fa-download mr-2"></i> Exportar
                </button>
                <button type="submit" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors duration-300 flex items-center">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
                <a href="index.php?c=Estudiante&a=index" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-300 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Limpiar
                </a>
            </div>
        </div>
    </form>
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

    <!-- Distribución por Género -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-bold text-primary-blue flex items-center">
                <i class="fas fa-venus-mars text-blue-500 mr-3"></i>
                Distribución por Género
            </h3>
        </div>
        <div class="p-6">
            <div class="chart-container">
                <canvas id="generoChart"></canvas>
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
            Mostrando <span id="estudiantes-mostrados"><?= count($estudiantes) ?></span> de <span id="estudiantes-totales"><?= $estadisticas['total'] ?? 0 ?></span> estudiantes
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 tabla-estudiantes">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Estudiante</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">DNI</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Programa</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Género</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Contacto</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="tabla-estudiantes">
                <?php if (empty($estudiantes)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-search text-lg mb-2"></i>
                            <p>No se encontraron estudiantes que coincidan con los filtros</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($estudiantes as $estudiante): ?>
                        <tr class="hover:bg-gray-50 transition-all duration-300 fade-in">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-semibold mr-3 <?= $estudiante['sex_est'] === 'F' ? 'avatar-estudiante-femenino' : 'avatar-estudiante-masculino' ?>">
                                        <?= substr($estudiante['nom_est'], 0, 1) . substr($estudiante['ap_est'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">
                                            <?= htmlspecialchars($estudiante['ap_est'] . ' ' . $estudiante['am_est'] . ', ' . $estudiante['nom_est']) ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php if ($estudiante['en_practicas'] > 0): ?>
                                                <i class="fas fa-briefcase mr-1 text-green-500"></i> En prácticas
                                            <?php else: ?>
                                                <i class="fas fa-clock mr-1 text-gray-400"></i> Sin prácticas
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($estudiante['dni_est']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($estudiante['nom_progest'] ?? 'No asignado') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if ($estudiante['sex_est'] === 'M'): ?>
                                    <i class="fas fa-mars text-blue-500"></i> Masculino
                                <?php else: ?>
                                    <i class="fas fa-venus text-pink-500"></i> Femenino
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div><?= htmlspecialchars($estudiante['cel_est']) ?></div>
                                <div class="text-xs"><?= htmlspecialchars($estudiante['mailp_est']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($estudiante['estado'] == 1): ?>
                                    <span class="badge-estado badge-activo">Activo</span>
                                <?php else: ?>
                                    <span class="badge-estado badge-inactivo">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="btn-accion btn-editar editar-estudiante" data-id="<?= $estudiante['id'] ?>" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-accion btn-ver ver-estudiante" data-id="<?= $estudiante['id'] ?>" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-accion btn-eliminar eliminar-estudiante" data-id="<?= $estudiante['id'] ?>" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
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
                <input type="hidden" id="estudianteId" name="id">
                <input type="hidden" name="csrf_token" value="<?= SessionHelper::getCSRFToken() ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="dni_est" class="block text-sm font-medium text-gray-700 mb-1">DNI *</label>
                        <input type="text" id="dni_est" name="dni_est" maxlength="8" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="ubdistrito" class="block text-sm font-medium text-gray-700 mb-1">Distrito</label>
                        <input type="number" id="ubdistrito" name="ubdistrito"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <label for="sex_est" class="block text-sm font-medium text-gray-700 mb-1">Género *</label>
                        <select id="sex_est" name="sex_est" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>

                    <div>
                        <label for="fecnac_est" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento</label>
                        <input type="date" id="fecnac_est" name="fecnac_est"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="cel_est" class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                        <input type="text" id="cel_est" name="cel_est" maxlength="9"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="mailp_est" class="block text-sm font-medium text-gray-700 mb-1">Email Personal</label>
                        <input type="email" id="mailp_est" name="mailp_est" maxlength="40"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="dir_est" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" id="dir_est" name="dir_est" maxlength="40"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="ubigeodir_est" class="block text-sm font-medium text-gray-700 mb-1">Ubigeo Dirección</label>
                        <input type="text" id="ubigeodir_est" name="ubigeodir_est" maxlength="6"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="ubigeonac_est" class="block text-sm font-medium text-gray-700 mb-1">Ubigeo Nacimiento</label>
                        <input type="text" id="ubigeonac_est" name="ubigeonac_est" maxlength="6"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="maili_est" class="block text-sm font-medium text-gray-700 mb-1">Email Institucional</label>
                    <input type="email" id="maili_est" name="maili_est" maxlength="40"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <span id="btnGuardarTexto">Guardar Estudiante</span>
                        <i class="fas fa-spinner fa-spin hidden ml-2" id="loadingIcon"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Detalles del Estudiante -->
<div id="detalleEstudianteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <!-- El contenido del modal de detalles se cargará dinámicamente -->
</div>

<!-- Incluir el JavaScript -->
<script src="assets/js/estudiantes.js"></script>
<script>
    // Pasar datos PHP a JavaScript
    const estudiantesData = {
        programas: <?= json_encode($programas) ?>,
        estudiantes: <?= json_encode($estudiantes) ?>,
        estadisticas: <?= json_encode($estadisticas) ?>
    };
</script>

<?php
// Incluir el footer
include 'views/layouts/footer.php';
?>