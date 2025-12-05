<?php require_once 'views/layouts/header.php'; ?>

<div class="p-6">
    <!-- Área de Bienvenida -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary-blue mb-2">Gestión de Documentos</h1>
            <p class="text-gray-600">Administra y envía documentos oficiales a los estudiantes</p>
        </div>
        <a href="index.php?c=Documento&a=crear" class="btn-nuevo-doc">
            <i class="fas fa-plus-circle"></i> Nuevo Documento
        </a>
    </div>
    
    <!-- Mostrar mensajes -->
    <?php if (isset($_GET['success'])): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <?php echo htmlspecialchars(urldecode($_GET['success'])); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg flex items-center">
            <i class="fas fa-exclamation-triangle mr-3"></i>
            <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
        </div>
    <?php endif; ?>
    
    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card-gradient-1 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200 text-sm font-medium">Total Documentos</p>
                    <h3 class="text-3xl font-bold mt-2"><?php echo $estadisticas['total'] ?? 0; ?></h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-200">
                <i class="fas fa-chart-bar mr-2"></i>
                <span>Documentos registrados</span>
            </div>
        </div>
        
        <div class="card-gradient-2 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Pendientes</p>
                    <h3 class="text-3xl font-bold mt-2"><?php echo $estadisticas['pendientes'] ?? 0; ?></h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-100">
                <i class="fas fa-hourglass-half mr-2"></i>
                <span>Por enviar</span>
            </div>
        </div>
        
        <div class="card-gradient-3 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Enviados</p>
                    <h3 class="text-3xl font-bold mt-2"><?php echo $estadisticas['generados'] ?? 0; ?></h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-paper-plane text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-100">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Documentos enviados</span>
            </div>
        </div>
        
        <div class="card-gradient-4 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Asistencias Completadas</p>
                    <h3 class="text-3xl font-bold mt-2"><?php echo $estadisticas['asistencias_completas'] ?? 0; ?></h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-clipboard-check text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-yellow-100">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Estudiantes con horas completas</span>
            </div>
        </div>
    </div>
    
    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <form method="GET" action="index.php" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <input type="hidden" name="c" value="Documento">
            <input type="hidden" name="a" value="index">
            
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="relative">
                    <input type="text" name="busqueda" value="<?php echo htmlspecialchars($filtros['busqueda']); ?>" 
                           placeholder="Buscar por estudiante o empresa..." 
                           class="w-full md:w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <select name="tipo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los tipos</option>
                        <option value="carta_presentacion" <?php echo $filtros['tipo_documento'] == 'carta_presentacion' ? 'selected' : ''; ?>>Carta de Presentación</option>
                        <option value="oficio_multiple" <?php echo $filtros['tipo_documento'] == 'oficio_multiple' ? 'selected' : ''; ?>>Oficio Múltiple</option>
                        <option value="ficha_identidad" <?php echo $filtros['tipo_documento'] == 'ficha_identidad' ? 'selected' : ''; ?>>Ficha de Identidad</option>
                    </select>
                    
                    <select name="estado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" <?php echo $filtros['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="generado" <?php echo $filtros['estado'] == 'generado' ? 'selected' : ''; ?>>Generado/Enviado</option>
                    </select>
                    
                    <select name="modulo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los módulos</option>
                        <option value="modulo1" <?php echo $filtros['modulo'] == 'modulo1' ? 'selected' : ''; ?>>Módulo 1</option>
                        <option value="modulo2" <?php echo $filtros['modulo'] == 'modulo2' ? 'selected' : ''; ?>>Módulo 2</option>
                        <option value="modulo3" <?php echo $filtros['modulo'] == 'modulo3' ? 'selected' : ''; ?>>Módulo 3</option>
                    </select>
                    
                    <select name="estudiante_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los estudiantes</option>
                        <?php foreach ($estudiantes as $est): ?>
                            <option value="<?php echo $est['id']; ?>" 
                                <?php echo $filtros['estudiante_id'] == $est['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($est['nombre_completo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300 flex items-center">
                    <i class="fas fa-search mr-2"></i> Buscar
                </button>
                <a href="index.php?c=Documento&a=exportarCSV<?php echo !empty($filtros) ? '&' . http_build_query($filtros) : ''; ?>" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-300 flex items-center">
                    <i class="fas fa-download mr-2"></i> Exportar CSV
                </a>
                <a href="index.php?c=Documento&a=index" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-300 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
    
    <!-- Tabla de Documentos -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <?php if (empty($documentos)): ?>
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-file-alt text-4xl mb-4"></i>
                <p class="text-lg">No se encontraron documentos</p>
                <p class="text-sm mt-2">Crea un nuevo documento o ajusta los filtros de búsqueda</p>
                <a href="index.php?c=Documento&a=crear" class="mt-4 inline-block btn-nuevo-doc">
                    <i class="fas fa-plus-circle"></i> Crear Primer Documento
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Módulo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($documentos as $doc): ?>
                            <?php 
                            $tipoInfo = getTipoDocumentoInfo($doc['tipo_documento']);
                            $estadoInfo = getEstadoDocumentoInfo($doc['estado']);
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="icon-documento <?php echo $tipoInfo['class']; ?>">
                                            <i class="fas <?php echo $tipoInfo['icon']; ?>"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-medium text-gray-900"><?php echo $tipoInfo['nombre']; ?></div>
                                            <?php if ($doc['numero_oficio']): ?>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($doc['numero_oficio']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($doc['nombre_estudiante']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo $doc['dni_est']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge-modulo"><?php echo strtoupper($doc['modulo']); ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($doc['nombre_empresa'] ?? 'No asignada'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge-estado <?php echo $estadoInfo['class']; ?>">
                                        <i class="fas <?php echo $estadoInfo['icon']; ?> mr-1"></i><?php echo $estadoInfo['texto']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('d/m/Y', strtotime($doc['fecha_generacion'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="index.php?c=Documento&a=vistaPrevia&id=<?php echo $doc['id']; ?>" 
                                           class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50" 
                                           title="Ver documento">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($doc['estado'] == 'pendiente'): ?>
                                            <a href="#" onclick="enviarDocumento(<?php echo $doc['id']; ?>)" 
                                               class="text-green-600 hover:text-green-900 p-2 rounded hover:bg-green-50" 
                                               title="Enviar al estudiante">
                                                <i class="fas fa-paper-plane"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?c=Documento&a=descargarPDF&id=<?php echo $doc['id']; ?>" 
                                           class="text-purple-600 hover:text-purple-900 p-2 rounded hover:bg-purple-50" 
                                           title="Descargar PDF">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="#" onclick="eliminarDocumento(<?php echo $doc['id']; ?>)" 
                                           class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50" 
                                           title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
// Funciones auxiliares
function getTipoDocumentoInfo($tipo) {
    switch($tipo) {
        case 'carta_presentacion':
            return ['nombre' => 'Carta de Presentación', 'icon' => 'fa-envelope', 'class' => 'icon-carta'];
        case 'oficio_multiple':
            return ['nombre' => 'Oficio Múltiple', 'icon' => 'fa-file-contract', 'class' => 'icon-solicitud'];
        case 'ficha_identidad':
            return ['nombre' => 'Ficha de Identidad', 'icon' => 'fa-clipboard-check', 'class' => 'icon-asistencias'];
        default:
            return ['nombre' => 'Documento', 'icon' => 'fa-file-alt', 'class' => 'icon-carta'];
    }
}

function getEstadoDocumentoInfo($estado) {
    switch($estado) {
        case 'generado':
            return ['texto' => 'Enviado', 'icon' => 'fa-paper-plane', 'class' => 'badge-enviado'];
        default:
            return ['texto' => 'Pendiente', 'icon' => 'fa-clock', 'class' => 'badge-pendiente'];
    }
}
?>

<script>
function enviarDocumento(id) {
    if (!confirm('¿Estás seguro de enviar este documento al estudiante?')) {
        return;
    }
    
    fetch(`index.php?c=Documento&a=enviar&id=${id}&csrf_token=<?php echo SessionHelper::getCSRFToken(); ?>`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar documento');
        });
}

function eliminarDocumento(id) {
    if (!confirm('¿Estás seguro de eliminar este documento?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('csrf_token', '<?php echo SessionHelper::getCSRFToken(); ?>');
    
    fetch(`index.php?c=Documento&a=eliminar&id=${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar documento');
    });
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>