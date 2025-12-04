<?php
// views/usuario/index.php
$usuarioSesion = SessionHelper::get('usuario');
$csrf_token = SessionHelper::getCSRFToken();
?>

<!-- Incluir CSS específico si es necesario -->

<link rel="stylesheet" href="assets/css/usuarios.css">

<!-- Sistema de Notificaciones -->
<div class="notification-container" id="notificationContainer"></div>

<!-- Overlay de Carga -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<div class="p-6">
    <!-- Main Content -->
    <!-- Área de Bienvenida -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-primary-blue mb-2">Gestión de Usuarios</h1>
            <p class="text-gray-600">Administra los usuarios del sistema de Experiencias Formativas</p>
        </div>
        <button id="btnNuevoUsuario" class="bg-primary-blue text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition-colors duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105">
            <i class="fas fa-user-plus mr-2"></i> Nuevo Usuario
        </button>
    </div>

    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Usuarios -->
        <div class="stat-card total bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Usuarios</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2" id="total-usuarios">0</h3>
                        <div class="flex items-center mt-3">
                            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full font-medium">
                                <i class="fas fa-users mr-1"></i> Sistema
                            </span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Registrados en el sistema</p>
                </div>
            </div>
        </div>

        <!-- Usuarios Activos -->
        <div class="stat-card active bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Usuarios Activos</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2" id="usuarios-activos">0</h3>
                        <div class="flex items-center mt-3">
                            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full font-medium">
                                <i class="fas fa-user-check mr-1"></i> Acceso activo
                            </span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-3 rounded-xl shadow-lg">
                        <i class="fas fa-user-check text-white text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Con acceso al sistema</p>
                </div>
            </div>
        </div>

        <!-- Administradores -->
        <div class="stat-card admin bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Administradores</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2" id="usuarios-admin">0</h3>
                        <div class="flex items-center mt-3">
                            <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full font-medium">
                                <i class="fas fa-crown mr-1"></i> Privilegios
                            </span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-xl shadow-lg">
                        <i class="fas fa-user-shield text-white text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Con privilegios completos</p>
                </div>
            </div>
        </div>

        <!-- Estudiantes con Usuario -->
        <div class="stat-card estudiantes bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Estudiantes</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2" id="usuarios-estudiantes">0</h3>
                        <div class="flex items-center mt-3">
                            <span class="text-xs text-cyan-600 bg-cyan-100 px-2 py-1 rounded-full font-medium">
                                <i class="fas fa-graduation-cap mr-1"></i> Credenciales
                            </span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 p-3 rounded-xl shadow-lg">
                        <i class="fas fa-user-graduate text-white text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Con credenciales asignadas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards secundarias -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Docentes con Usuario -->
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-800 text-sm font-medium">Docentes</p>
                    <h3 class="text-2xl font-bold text-yellow-900 mt-1" id="usuarios-docentes">0</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-chalkboard-teacher text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-yellow-700">Con acceso al sistema</p>
            </div>
        </div>

        <!-- Usuarios Inactivos -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium">Inactivos</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1" id="usuarios-inactivos">0</h3>
                </div>
                <div class="bg-gray-100 p-3 rounded-lg">
                    <i class="fas fa-user-slash text-gray-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-600">Cuentas deshabilitadas</p>
            </div>
        </div>

        <!-- Último Registro -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-800 text-sm font-medium">Último Registro</p>
                    <h3 class="text-xl font-bold text-blue-900 mt-1" id="ultimo-registro">--</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-history text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-blue-700">Usuario más reciente</p>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Distribución por Tipo -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                    Distribución por Tipo
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="tipoUsuarioChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Estado de Usuarios -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-primary-blue flex items-center">
                    <i class="fas fa-chart-bar text-blue-500 mr-3"></i>
                    Estado de Usuarios
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="estadoUsuarioChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" id="buscarUsuario" placeholder="Buscar por nombre de usuario o persona..."
                        class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <select id="filtroTipo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Todos los tipos</option>
                    <option value="1">Docente</option>
                    <option value="2">Administrador</option>
                    <option value="3">Estudiante</option>
                </select>

                <select id="filtroEstado" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Todos los estados</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>

                <button id="btnRefrescar" class="text-gray-600 hover:text-gray-800 transition-colors duration-300">
                    <i class="fas fa-sync-alt text-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-primary-blue flex items-center">
                <i class="fas fa-users text-blue-500 mr-3"></i>
                Usuarios del Sistema
            </h3>
            <div class="text-sm text-gray-500" id="contador-usuarios">
                <span id="usuarios-mostrados">Cargando...</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 tabla-usuarios">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Vinculado con</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Último Acceso</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-blue uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="tablaUsuariosBody">
                    <!-- Los datos se cargarán dinámicamente vía JavaScript -->
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin text-3xl mb-3"></i>
                            <p class="text-lg font-medium">Cargando usuarios...</p>
                            <p class="text-sm mt-1">Por favor espere</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Usuario -->
<div id="modalNuevoUsuario" class="modal">
    <!-- Dentro del modal nuevo usuario, en el formulario -->
    <input type="hidden" id="personaSeleccionadaId" name="estuempleado" value="">
    <input type="hidden" id="personaSeleccionadaTipo" name="persona_tipo" value="">
    <input type="hidden" name="csrf_token" id="csrf_token_nuevo" value="<?php echo $csrf_token; ?>">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-user-plus mr-2"></i>
                Nuevo Usuario
            </h3>
            <button id="cerrarModalNuevo" class="text-white hover:text-blue-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <form id="formNuevoUsuario">
                <div class="space-y-6">
                    <div class="form-group">
                        <label class="form-label">Nombre de Usuario *</label>
                        <input type="text" class="form-input" id="nuevoUsuario"
                            placeholder="Ej: admin, docente01, estudiante123" required>
                        <p class="text-xs text-gray-500 mt-1">Identificador único para acceder al sistema</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Contraseña *</label>
                            <div class="password-input-container relative">
                                <input type="password" class="form-input pr-10" id="nuevaPassword"
                                    placeholder="••••••••" required>
                                <button type="button" class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirmar Contraseña *</label>
                            <div class="password-input-container relative">
                                <input type="password" class="form-input pr-10" id="nuevaPasswordConfirm"
                                    placeholder="••••••••" required>
                                <button type="button" class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Tipo de Usuario *</label>
                            <select class="form-select" id="nuevoTipo" required onchange="mostrarSeleccionPersona()">
                                <option value="">Seleccionar tipo</option>
                                <option value="1">Docente</option>
                                <option value="2">Administrador</option>
                                <option value="3">Estudiante</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Estado *</label>
                            <select class="form-select" id="nuevoEstado" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Selección de Persona (se muestra dinámicamente) -->
                    <div id="seleccionPersonaContainer" class="hidden">
                        <div class="form-group">
                            <label class="form-label">Vincular con Persona *</label>
                            <p class="text-xs text-gray-500 mb-3">Selecciona a qué persona se le asignarán estas credenciales</p>

                            <div class="persona-seleccionada bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-4 mb-3" id="personaSeleccionadaPreview">
                                <div class="persona-seleccionada-content flex items-center justify-between">
                                    <div class="persona-seleccionada-info flex items-center">
                                        <div class="avatar-usuario w-10 h-10 rounded-lg bg-primary-blue text-white flex items-center justify-center font-bold mr-3" id="previewAvatar">
                                            ?
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900" id="previewNombre">
                                                Sin persona seleccionada
                                            </div>
                                            <div class="text-xs text-gray-500" id="previewDetalle">
                                                Selecciona una persona
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-cambiar-persona text-blue-600 hover:text-blue-800 text-sm font-medium" onclick="abrirModalSeleccionPersona()">
                                        <i class="fas fa-edit mr-1"></i>
                                        Cambiar
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" id="personaSeleccionadaId" name="estuempleado">
                            <input type="hidden" id="personaSeleccionadaTipo" name="persona_tipo">

                            <button type="button" class="btn btn-secondary w-full" onclick="abrirModalSeleccionPersona()">
                                <i class="fas fa-search mr-2"></i>
                                Buscar y Seleccionar Persona
                            </button>
                        </div>
                    </div>

                    <!-- Solo para Administradores -->
                    <div id="infoAdministrador" class="hidden">
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Los usuarios administradores no requieren estar vinculados a una persona específica.
                                        Tienen acceso completo al sistema.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="csrf_token" id="csrf_token_nuevo" value="<?php echo $csrf_token; ?>">
            </form>
        </div>

        <div class="modal-footer">
            <button id="cancelarNuevoUsuario" class="btn btn-secondary">
                Cancelar
            </button>
            <button id="guardarNuevoUsuario" class="btn btn-success">
                <i class="fas fa-save mr-1"></i>
                Crear Usuario
            </button>
        </div>
    </div>
</div>

<div id="modalSeleccionPersona" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-user-friends mr-2"></i>
                <span id="tituloListaPersonas">Seleccionar Persona</span>
            </h3>
            <button id="cerrarModalSeleccion" class="text-white hover:text-blue-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="space-y-4">
                <div class="buscador-persona relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="buscarPersona" placeholder="Buscar por nombre, DNI o código..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between">
                    <h4 class="font-medium text-gray-900" id="tituloListaPersonas">
                        Selecciona una persona
                    </h4>
                    <div class="text-sm text-gray-500" id="contadorPersonas">
                        0 personas encontradas
                    </div>
                </div>

                <div class="lista-personas max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-2" id="listaPersonas">
                    <!-- Las opciones se generarán dinámicamente -->
                </div>

                <div class="text-center text-gray-500 text-sm py-4 hidden" id="sinResultados">
                    <i class="fas fa-user-slash text-2xl mb-2"></i>
                    <p>No se encontraron personas</p>
                    <p class="text-xs mt-1">Intenta con otro término de búsqueda</p>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" id="cancelarSeleccion" class="btn btn-secondary">
                Cancelar
            </button>
            <button type="button" id="confirmarSeleccion" class="btn btn-primary" disabled>
                <i class="fas fa-check mr-1"></i>
                Seleccionar Persona
            </button>
        </div>
    </div>
</div>

<!-- Modal Confirmación Eliminar -->
<div id="modalConfirmarEliminar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-96 max-w-md mx-4 p-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Eliminar Usuario</h3>
            <p class="text-sm text-gray-500 mb-6">
                Esta acción no se puede deshacer. El usuario perderá acceso al sistema.
            </p>
            <div class="flex justify-center space-x-3">
                <button id="cancelarEliminar" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300">
                    Cancelar
                </button>
                <button id="confirmarEliminar" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-300">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles Usuario -->
<div id="modalDetallesUsuario" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-user-circle mr-2"></i>
                Detalles del Usuario
            </h3>
            <button id="cerrarModalDetalles" class="text-white hover:text-blue-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div id="contenidoDetalles">
                <!-- Contenido dinámico -->
            </div>
        </div>
        
        <div class="modal-footer">
            <button id="cerrarDetalles" class="btn btn-secondary">
                Cerrar
            </button>
        </div>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div id="modalEditarUsuario" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-user-edit mr-2"></i>
                Editar Usuario
            </h3>
            <button id="cerrarModalEditar" class="text-white hover:text-blue-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form id="formEditarUsuario">
                <input type="hidden" id="editarId">
                <input type="hidden" id="csrf_token_editar" value="<?php echo $csrf_token; ?>">
                
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Nombre de Usuario *</label>
                        <input type="text" class="form-input" id="editarUsuario" required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Tipo de Usuario *</label>
                            <select class="form-select" id="editarTipo" required onchange="mostrarSeleccionPersonaEditar()">
                                <option value="">Seleccionar tipo</option>
                                <option value="1">Docente</option>
                                <option value="2">Administrador</option>
                                <option value="3">Estudiante</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Estado *</label>
                            <select class="form-select" id="editarEstado" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Información de Persona Vinculada -->
                    <div id="infoPersonaVinculada" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Persona Vinculada</label>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="avatar-usuario mr-3" id="editarPreviewAvatar">
                                            ?
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900" id="editarPreviewNombre">
                                                Sin persona vinculada
                                            </div>
                                            <div class="text-xs text-gray-500" id="editarPreviewDetalle">
                                                No hay persona asociada
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-cambiar-persona" onclick="abrirModalSeleccionPersonaEditar()">
                                        <i class="fas fa-exchange-alt mr-1"></i>
                                        Cambiar
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" id="editarPersonaSeleccionadaId">
                            <input type="hidden" id="editarPersonaSeleccionadaTipo">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nueva Contraseña</label>
                        <div class="password-input-container">
                            <input type="password" class="form-input" id="editarPassword" 
                                   placeholder="Dejar en blanco para mantener actual">
                            <button type="button" class="toggle-password" id="toggleEditPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Opcional: completar solo si desea cambiar</p>
                    </div>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Los cambios se aplicarán inmediatamente después de guardar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="modal-footer">
            <button id="cancelarEditarUsuario" class="btn btn-secondary">
                Cancelar
            </button>
            <button id="guardarEditarUsuario" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i>
                Guardar Cambios
            </button>
        </div>
    </div>
</div>

<!-- Modal Restablecer Contraseña -->
<div id="modalResetPassword" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-key mr-2"></i>
                Restablecer Contraseña
            </h3>
            <button id="cerrarModalReset" class="text-white hover:text-blue-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div id="contenidoReset">
                <p class="text-gray-700 mb-4">
                    Se generará una nueva contraseña para el usuario 
                    <span id="resetUsuarioNombre" class="font-semibold text-gray-900"></span>.
                </p>
                
                <input type="hidden" id="resetUsuarioId">
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                El usuario deberá cambiar su contraseña en el próximo inicio de sesión.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nueva Contraseña Temporal</label>
                    <div class="password-input-container">
                        <input type="text" class="form-input" id="nuevaPasswordTemp" value="Temp123456" readonly>
                        <button type="button" class="toggle-password" id="copyPasswordTemp">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button id="cancelarReset" class="btn btn-secondary">
                Cancelar
            </button>
            <button id="confirmarReset" class="btn btn-primary">
                <i class="fas fa-redo mr-1"></i>
                Restablecer
            </button>
        </div>
    </div>
</div>

<!-- Modal para Seleccionar Persona en Edición -->
<div id="modalSeleccionPersonaEditar" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-user-friends mr-2"></i>
                <span id="tituloListaPersonasEditar">Seleccionar Persona</span>
            </h3>
            <button id="cerrarModalSeleccionEditar" class="text-white hover:text-blue-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="space-y-4">
                <div class="buscador-persona relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="buscarPersonaEditar" placeholder="Buscar por nombre, DNI o código..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex items-center justify-between">
                    <h4 class="font-medium text-gray-900" id="tituloListaPersonasEditar">
                        Selecciona una persona
                    </h4>
                    <div class="text-sm text-gray-500" id="contadorPersonasEditar">
                        0 personas encontradas
                    </div>
                </div>
                
                <div class="lista-personas max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-2" id="listaPersonasEditar">
                    <!-- Las opciones se generarán dinámicamente -->
                </div>
                
                <div class="text-center text-gray-500 text-sm py-4 hidden" id="sinResultadosEditar">
                    <i class="fas fa-user-slash text-2xl mb-2"></i>
                    <p>No se encontraron personas</p>
                    <p class="text-xs mt-1">Intenta con otro término de búsqueda</p>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" id="cancelarSeleccionEditar" class="btn btn-secondary">
                Cancelar
            </button>
            <button type="button" id="confirmarSeleccionEditar" class="btn btn-primary" disabled>
                <i class="fas fa-check mr-1"></i>
                Seleccionar Persona
            </button>
        </div>
    </div>
</div>

<!-- Incluir JavaScript específico -->
<script src="assets/js/usuarios.js"></script>

<?php require_once 'views/layouts/footer.php'; ?>