<?php
// views/empresa/empresa.php
require_once 'views/layouts/header.php';
?>

<!-- Solo el contenido específico de empresas -->
<div class="p-6">
    <!-- Área de Bienvenida -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary-blue mb-2">Gestión de Empresas</h1>
            <p class="text-gray-600">Administra la información de las empresas registradas para prácticas EFSRT</p>
        </div>
        <button id="btnNuevaEmpresa" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors duration-300 flex items-center">
            <i class="fas fa-plus mr-2"></i> Nueva Empresa
        </button>
    </div>
    
    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card-gradient-1 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200 text-sm font-medium">Total Empresas</p>
                    <h3 class="text-3xl font-bold mt-2" id="total-empresas">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-building text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-200">
                <i class="fas fa-industry mr-2"></i>
                <span id="empresas-texto">Cargando...</span>
            </div>
        </div>
        
        <div class="card-gradient-2 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Empresas Validadas</p>
                    <h3 class="text-3xl font-bold mt-2" id="empresas-validadas">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-100">
                <i class="fas fa-user-check mr-2"></i>
                <span id="validadas-texto">Cargando...</span>
            </div>
        </div>
        
        <div class="card-gradient-3 text-white p-6 rounded-2xl stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Con Prácticas Activas</p>
                    <h3 class="text-3xl font-bold mt-2" id="empresas-practicas">0</h3>
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
                    <p class="text-gray-200 text-sm font-medium">Por Sector</p>
                    <h3 class="text-3xl font-bold mt-2" id="sectores-count">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-200">
                <i class="fas fa-tags mr-2"></i>
                <span id="sectores-texto">Cargando...</span>
            </div>
        </div>
    </div>
    
    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="relative">
                    <input type="text" id="buscarEmpresa" placeholder="Buscar por RUC, razón social..." 
                           class="w-full md:w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    <div id="resultadosBusqueda" class="resultados-busqueda"></div>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <select id="filtroSector" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los sectores</option>
                        <option value="TECNOLOGÍA">Tecnología</option>
                        <option value="CONSTRUCCIÓN">Construcción</option>
                        <option value="SERVICIOS">Servicios</option>
                        <option value="COMERCIO">Comercio</option>
                        <option value="INFORMÁTICA">Informática</option>
                        <option value="DESARROLLO SOFTWARE">Desarrollo Software</option>
                        <option value="Otros">Otros</option>
                    </select>
                    
                    <select id="filtroValidado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todas las validaciones</option>
                        <option value="1">Validadas</option>
                        <option value="0">No validadas</option>
                    </select>
                    
                    <select id="filtroEstado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los estados</option>
                        <option value="ACTIVO">Activo</option>
                        <option value="INACTIVO">Inactivo</option>
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
        <!-- Distribución por Sector -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                    Distribución por Sector
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="sectoresChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Distribución por Estado de Validación -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-check-circle text-blue-500 mr-3"></i>
                    Estado de Validación
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="validacionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Vista de Tarjetas (Alternativa a la tabla) -->
    <div class="mb-6 flex justify-end">
        <div class="flex bg-gray-100 rounded-lg p-1">
            <button id="btnVistaTabla" class="px-3 py-2 rounded-md bg-white shadow-sm text-primary-blue font-medium flex items-center">
                <i class="fas fa-table mr-2"></i> Vista Tabla
            </button>
            <button id="btnVistaTarjetas" class="px-3 py-2 rounded-md text-gray-600 hover:text-primary-blue transition-colors flex items-center">
                <i class="fas fa-th-large mr-2"></i> Vista Tarjetas
            </button>
        </div>
    </div>
    
    <!-- Tabla de Empresas -->
    <div id="vistaTabla" class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-primary-blue flex items-center">
                <i class="fas fa-list-alt text-blue-500 mr-3"></i>
                Lista de Empresas
            </h3>
            <div class="text-sm text-gray-500" id="contador-empresas">
                Mostrando <span id="empresas-mostradas">0</span> de <span id="empresas-totales">0</span> empresas
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 tabla-empresas">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">RUC</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Sector</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="tabla-empresas">
                    <!-- Los datos se cargarán dinámicamente -->
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin text-lg mb-2"></i>
                            <p>Cargando datos de empresas...</p>
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
    
    <!-- Vista de Tarjetas -->
    <div id="vistaTarjetas" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="contenedor-tarjetas">
            <!-- Las tarjetas se generarán dinámicamente -->
            <div class="bg-white rounded-2xl shadow-lg p-6 card-empresa">
                <div class="flex justify-between items-start mb-4">
                    <div class="avatar-empresa h-14 w-14 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="flex space-x-2">
                        <button class="btn-accion btn-ver" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-accion btn-editar" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-primary-blue mb-2">Cargando...</h3>
                <div class="flex items-center text-sm text-gray-500 mb-3">
                    <i class="fas fa-id-card mr-2"></i>
                    <span>RUC: ...</span>
                </div>
                <div class="mb-4">
                    <span class="sector-badge sector-otros">Cargando...</span>
                </div>
                <div class="text-sm text-gray-600 mb-4">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                        <span>Cargando...</span>
                    </div>
                    <div class="flex items-center mb-1">
                        <i class="fas fa-phone mr-2 text-blue-500"></i>
                        <span>Cargando...</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-2 text-blue-500"></i>
                        <span class="truncate">Cargando...</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="badge-estado badge-activo">Cargando...</span>
                    <span class="badge-estado badge-validado">Cargando...</span>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-500" id="contador-tarjetas">
                Mostrando <span id="tarjetas-mostradas">0</span> de <span id="tarjetas-totales">0</span> empresas
            </div>
            <div class="flex space-x-2" id="paginacion-tarjetas">
                <!-- Los controles de paginación se generarán dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Empresa -->
<div id="empresaModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-4xl mx-4 modal-content">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-primary-blue" id="modalTitulo">Nueva Empresa</h3>
            <button id="cerrarModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="formEmpresa">
                <input type="hidden" id="empresaId">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="ruc" class="block text-sm font-medium text-gray-700 mb-1">RUC *</label>
                        <input type="text" id="ruc" name="ruc" maxlength="11" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label for="sector" class="block text-sm font-medium text-gray-700 mb-1">Sector *</label>
                        <select id="sector" name="sector" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Seleccionar sector</option>
                            <option value="TECNOLOGÍA">Tecnología</option>
                            <option value="CONSTRUCCIÓN">Construcción</option>
                            <option value="SERVICIOS">Servicios</option>
                            <option value="COMERCIO">Comercio</option>
                            <option value="INFORMÁTICA">Informática</option>
                            <option value="DESARROLLO SOFTWARE">Desarrollo Software</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                </div>
                
                <!-- ... (resto del formulario igual) ... -->
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" id="cancelarForm" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors duration-300">
                        Guardar Empresa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles de la Empresa -->
<div id="detalleEmpresaModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-3xl mx-4 modal-content">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-primary-blue to-blue-800 text-white rounded-t-2xl">
            <div class="flex items-center">
                <i class="fas fa-building text-xl mr-3"></i>
                <h3 class="text-xl font-bold" id="detalleModalTitulo">Detalles de la Empresa</h3>
            </div>
            <button id="cerrarDetalleModal" class="text-white hover:text-blue-200 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 max-h-[70vh] overflow-y-auto">
            <!-- ... (contenido del modal de detalles igual) ... -->
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
                    <i class="fas fa-edit mr-2"></i> Editar Empresa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript específico de empresas -->
<script src="assets/js/empresas.js"></script>

<?php
require_once 'views/layouts/footer.php';
?>