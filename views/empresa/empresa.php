<?php
// views/empresa/empresa.php
require_once 'views/layouts/header.php';
?>
<div class="p-6">
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
                    <p class="text-blue-100 text-sm font-medium">Empresas Activas</p>
                    <h3 class="text-3xl font-bold mt-2" id="empresas-activas">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-2xl"></i>
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
                    <p class="text-gray-200 text-sm font-medium">Por Departamento</p>
                    <h3 class="text-3xl font-bold mt-2" id="departamentos-count">0</h3>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-200">
                <i class="fas fa-tags mr-2"></i>
                <span id="departamentos-texto">Cargando...</span>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="relative">
                    <input type="text"
                        id="buscarEmpresa"
                        placeholder="Buscar por RUC, razón social, representante..."
                        class="w-full md:w-80 py-2 pl-10 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm buscar-empresa"
                        autocomplete="off">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    <!-- El indicador de carga aparecerá aquí -->
                </div>

                <div class="flex flex-wrap gap-2">
                    <select id="filtroDepartamento" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los departamentos</option>
                        <?php foreach ($departamentos as $depto): ?>
                            <option value="<?php echo htmlspecialchars($depto['departamento']); ?>">
                                <?php echo htmlspecialchars($depto['departamento']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select id="filtroEstado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Todos los estados</option>
                        <option value="ACTIVO">Activo</option>
                        <option value="INACTIVO">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- En views/empresa/empresa.php - mejora el botón exportar -->
                <div class="relative inline-block">
                    <button id="btnExportar" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-300 flex items-center">
                        <i class="fas fa-download mr-2"></i> Exportar
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>

                    <!-- Dropdown menu mejorado -->
                    <div id="exportarDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl z-50 border border-gray-200 py-1">
                        <button onclick="empresaManager.exportarDatos()" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                            <i class="fas fa-file-excel mr-3 text-green-600 text-lg"></i>
                            <div>
                                <div class="font-medium">Exportar a Excel</div>
                                <div class="text-xs text-gray-500">Lista completa de empresas</div>
                            </div>
                        </button>

                        <div class="border-t border-gray-100 my-1"></div>

                        <button onclick="empresaManager.exportarEstadisticas()" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                            <i class="fas fa-chart-bar mr-3 text-blue-600 text-lg"></i>
                            <div>
                                <div class="font-medium">Reporte Estadístico</div>
                                <div class="text-xs text-gray-500">Gráficos y análisis</div>
                            </div>
                        </button>

                        <div class="border-t border-gray-100 my-1"></div>

                        <div class="px-4 py-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Los reportes incluyen gráficos en formato de datos
                        </div>
                    </div>
                </div>
                <button id="btnRefrescar" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors duration-300 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Actualizar
                </button>
            </div>
        </div>
    </div>

    <!-- Gráficos y Estadísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Distribución por Departamento -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                    Distribución por Departamento
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="departamentosChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución por Estado -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-check-circle text-blue-500 mr-3"></i>
                    Estado de Empresas
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="estadoChart"></canvas>
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Representante Legal</th>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                <h3 class="text-lg font-bold text-primary-blue mb-2">Nombre de Empresa</h3>
                <div class="flex items-center text-sm text-gray-500 mb-3">
                    <i class="fas fa-id-card mr-2"></i>
                    <span>RUC: 20123456789</span>
                </div>
                <div class="text-sm text-gray-600 mb-4">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                        <span>Lima, Perú</span>
                    </div>
                    <div class="flex items-center mb-1">
                        <i class="fas fa-phone mr-2 text-blue-500"></i>
                        <span>+51 987 654 321</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-2 text-blue-500"></i>
                        <span class="truncate">contacto@empresa.com</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="badge-estado badge-activo">Activo</span>
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
    </main>

    <!-- Modal de Confirmación de Cierre de Sesión -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 w-96 max-w-md mx-4">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-4">
                    <i class="fas fa-sign-out-alt text-red-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary-blue">Cerrar Sesión</h3>
            </div>
            <p class="text-gray-600 mb-6">¿Estás seguro de que deseas cerrar sesión? Serás redirigido a la página de inicio de sesión.</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelLogout" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300">
                    Cancelar
                </button>
                <button id="confirmLogout" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-300">
                    Cerrar Sesión
                </button>
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
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                            <select id="estado" name="estado"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="razon_social" class="block text-sm font-medium text-gray-700 mb-1">Razón Social *</label>
                        <input type="text" id="razon_social" name="razon_social" maxlength="255"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-6">
                        <label for="representante_legal" class="block text-sm font-medium text-gray-700 mb-1">Representante Legal</label>
                        <input type="text" id="representante_legal" name="representante_legal" maxlength="255"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-6">
                        <label for="direccion_fiscal" class="block text-sm font-medium text-gray-700 mb-1">Dirección Fiscal *</label>
                        <textarea id="direccion_fiscal" name="direccion_fiscal" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" maxlength="20"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="email" name="email" maxlength="100"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <input type="hidden" id="departamento_nombre" name="departamento">
                    <input type="hidden" id="provincia_nombre" name="provincia">
                    <input type="hidden" id="distrito_nombre" name="distrito">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="departamento_id" class="block text-sm font-medium text-gray-700 mb-1">Departamento *</label>
                            <select id="departamento_id" name="departamento_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Seleccionar departamento</option>
                                <!-- Los departamentos se cargarán dinámicamente -->
                            </select>
                        </div>

                        <div>
                            <label for="provincia_id" class="block text-sm font-medium text-gray-700 mb-1">Provincia *</label>
                            <select id="provincia_id" name="provincia_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled>
                                <option value="">Primero seleccione departamento</option>
                            </select>
                        </div>

                        <div>
                            <label for="distrito_id" class="block text-sm font-medium text-gray-700 mb-1">Distrito *</label>
                            <select id="distrito_id" name="distrito_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required disabled>
                                <option value="">Primero seleccione provincia</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelarForm" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors duration-300 flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            <span id="btnGuardarTexto">Guardar Empresa</span>
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
                <!-- Encabezado con logo y datos principales -->
                <div class="flex flex-col md:flex-row items-start md:items-center mb-8 pb-6 border-b border-gray-200">
                    <div id="detalleAvatar" class="h-20 w-20 rounded-xl flex items-center justify-center text-white font-bold text-2xl mr-0 md:mr-6 mb-4 md:mb-0 shadow-lg avatar-empresa">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="flex-1">
                        <h2 id="detalleNombre" class="text-2xl font-bold text-primary-blue mb-1"></h2>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <span id="detalleEstado" class="badge-estado"></span>
                        </div>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-id-card mr-2 text-primary-blue"></i>
                                <span id="detalleRuc"></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-primary-blue"></i>
                                <span id="detalleUbicacion"></span>
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
                                    <i class="fas fa-phone mr-2 text-blue-500"></i>
                                    Teléfono:
                                </span>
                                <span id="detalleTelefono" class="text-sm text-gray-600 text-right"></span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                    Email:
                                </span>
                                <span id="detalleEmail" class="text-sm text-gray-600 text-right break-all"></span>
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
                                    Dirección Fiscal:
                                </span>
                                <span id="detalleDireccion" class="text-sm text-gray-600 text-right"></span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-map-pin mr-2 text-blue-500"></i>
                                    Ubicación:
                                </span>
                                <span id="detalleUbicacionCompleta" class="text-sm text-gray-600 text-right"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Información Legal -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 md:col-span-2">
                        <h4 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
                            <i class="fas fa-gavel mr-2"></i>
                            Información Legal
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Razón Social:</span>
                                <span id="detalleRazonSocial" class="text-sm text-gray-600"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Representante Legal:</span>
                                <span id="detalleRepresentanteLegal" class="text-sm text-gray-600"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Prácticas -->
                <div class="mt-6 bg-blue-50 rounded-xl p-5 border border-blue-200">
                    <h4 class="text-lg font-semibold text-primary-blue mb-3 flex items-center">
                        <i class="fas fa-briefcase mr-2"></i>
                        Prácticas Asociadas
                    </h4>
                    <div id="detallePracticas" class="text-sm text-gray-600">
                        <p>Cargando información de prácticas...</p>
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
                        <i class="fas fa-edit mr-2"></i> Editar Empresa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sistema de Notificaciones -->
    <div id="notificationContainer"></div>

    <!-- Overlay de Carga -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- JavaScript específico de empresas -->
    <script src="assets/js/empresas.js"></script>

    <?php
    require_once 'views/layouts/footer.php';
    ?>