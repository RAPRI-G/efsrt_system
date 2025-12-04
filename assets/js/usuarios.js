// assets/js/usuarios.js - ARCHIVO COMPLETO CORREGIDO

// ==============================
// VARIABLES GLOBALES
// ==============================

let personaSeleccionada = null;
let usuarioAEliminar = null;
let usuarioAResetear = null;
let esModoEdicion = false;

// Configuración de URLs
const currentPath = window.location.pathname;
const pathParts = currentPath.split('/');
pathParts.pop(); // Eliminar el archivo actual
const basePath = pathParts.join('/') + '/';
const apiUrl = window.location.origin + basePath + 'index.php?c=Usuario&a=';

console.log('Configuración de URLs:');
console.log('Current Path:', currentPath);
console.log('Base Path:', basePath);
console.log('API URL:', apiUrl);

// ==============================
// SISTEMA DE NOTIFICACIONES
// ==============================

function mostrarNotificacion(titulo, mensaje, tipo = 'info', duracion = 5000) {
    const container = document.getElementById('notificationContainer');
    if (!container) {
        console.warn('No se encontró el contenedor de notificaciones');
        return null;
    }

    const notification = document.createElement('div');
    notification.className = 'notification';
    
    let icono = '';
    let colorBorde = '';
    
    switch(tipo) {
        case 'success':
            icono = 'fa-check-circle';
            colorBorde = '#10b981';
            break;
        case 'error':
            icono = 'fa-exclamation-circle';
            colorBorde = '#ef4444';
            break;
        case 'warning':
            icono = 'fa-exclamation-triangle';
            colorBorde = '#f59e0b';
            break;
        default:
            icono = 'fa-info-circle';
            colorBorde = '#3b82f6';
    }
    
    notification.innerHTML = `
        <div class="notification-icon">
            <i class="fas ${icono}"></i>
        </div>
        <div class="notification-content">
            <div class="notification-title">${titulo}</div>
            <div class="notification-message">${mensaje}</div>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Aplicar estilo de borde
    notification.style.borderLeft = `4px solid ${colorBorde}`;
    
    container.appendChild(notification);
    
    // Animación de entrada
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Cerrar notificación
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        cerrarNotificacion(notification);
    });
    
    // Auto-cerrar después de duración
    if (duracion > 0) {
        setTimeout(() => {
            if (notification.parentNode) {
                cerrarNotificacion(notification);
            }
        }, duracion);
    }
    
    return notification;
}


function cerrarNotificacion(notification) {
    notification.classList.remove('show');
    notification.classList.add('hide');
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 400);
}

// ==============================
// FUNCIONES DE CARGA DE DATOS
// ==============================

async function cargarUsuarios() {
    try {
        mostrarCarga('Cargando usuarios...');
        
        console.log('Solicitando usuarios desde:', apiUrl + 'apiUsuarios');
        const response = await fetch(apiUrl + 'apiUsuarios');
        
        if (!response.ok) {
            throw new Error(`Error HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        console.log('Respuesta de usuarios:', result);
        
        if (result.success) {
            if (result.data && Array.isArray(result.data)) {
                renderizarTablaUsuarios(result.data);
                await actualizarEstadisticas();
                inicializarGraficos(result.data);
                ocultarCarga();
                // REMOVÍ LA NOTIFICACIÓN AUTOMÁTICA AQUÍ
            } else {
                throw new Error('Formato de datos inválido');
            }
        } else {
            throw new Error(result.message || 'Error en la respuesta del servidor');
        }
    } catch (error) {
        console.error('Error al cargar usuarios:', error);
        
        // Mostrar error en la tabla
        const tbody = document.getElementById('tablaUsuariosBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-exclamation-triangle text-3xl mb-3 text-red-500"></i>
                        <p class="text-lg font-medium text-red-600">Error al cargar usuarios</p>
                        <p class="text-sm mt-1">${error.message}</p>
                        <button onclick="cargarUsuarios()" class="mt-4 px-4 py-2 bg-primary-blue text-white rounded-lg hover:bg-blue-800 transition-colors">
                            <i class="fas fa-redo mr-2"></i> Reintentar
                        </button>
                    </td>
                </tr>
            `;
        }
        
        // Actualizar contador
        const contador = document.getElementById('usuarios-mostrados');
        if (contador) {
            contador.textContent = 'Error al cargar';
        }
        
        mostrarNotificacion('Error', error.message, 'error');
        ocultarCarga();
    }
}

async function actualizarEstadisticas() {
    try {
        console.log('Actualizando estadísticas desde:', apiUrl + 'apiEstadisticas');
        const response = await fetch(apiUrl + 'apiEstadisticas');
        
        if (!response.ok) {
            throw new Error(`Error HTTP ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Estadísticas recibidas:', result);
        
        if (result.success && result.data) {
            const stats = result.data;
            
            // Actualizar cards principales
            actualizarCard('total-usuarios', stats.total);
            actualizarCard('usuarios-activos', stats.activos);
            actualizarCard('usuarios-admin', stats.administradores);
            actualizarCard('usuarios-estudiantes', stats.estudiantes);
            
            // Actualizar cards secundarias
            actualizarCard('usuarios-docentes', stats.docentes);
            actualizarCard('usuarios-inactivos', stats.inactivos);
            
            // Último registro con formato especial
            const ultimoRegistroElement = document.getElementById('ultimo-registro');
            if (ultimoRegistroElement) {
                ultimoRegistroElement.innerHTML = `
                    <div class="flex flex-col">
                        <span class="text-sm font-medium">${stats.ultimo_registro}</span>
                        <span class="text-xs text-blue-600 mt-1">${stats.ultimo_usuario || 'N/A'}</span>
                    </div>
                `;
            }
            
            // Agregar animación a los números
            animarNumeros();
            
        } else {
            throw new Error(result.message || 'Error en las estadísticas');
        }
    } catch (error) {
        console.error('Error actualizando estadísticas:', error);
        
        // Mostrar valores por defecto en caso de error
        actualizarCard('total-usuarios', 0);
        actualizarCard('usuarios-activos', 0);
        actualizarCard('usuarios-admin', 0);
        actualizarCard('usuarios-estudiantes', 0);
        actualizarCard('usuarios-docentes', 0);
        actualizarCard('usuarios-inactivos', 0);
        
        const ultimoRegistroElement = document.getElementById('ultimo-registro');
        if (ultimoRegistroElement) {
            ultimoRegistroElement.textContent = '--';
        }
    }
}

// En usuarios.js - después de las funciones principales

function iniciarActualizacionAutomatica() {
    // Actualizar cada 30 segundos
    setInterval(async () => {
        console.log('Actualización automática de estadísticas...');
        await actualizarEstadisticas();
    }, 30000); // 30 segundos
    
    // También actualizar cuando la ventana gana foco
    window.addEventListener('focus', async () => {
        console.log('Ventana activada, actualizando estadísticas...');
        await actualizarEstadisticas();
    });
}

function animarNumeros() {
    // Agregar clase de animación a todos los números
    document.querySelectorAll('[id*="usuarios-"], #total-usuarios').forEach(element => {
        element.classList.add('counter');
    });
}

function actualizarCard(elementId, valor) {
    const element = document.getElementById(elementId);
    if (element) {
        // Si ya tiene un valor, animar el cambio
        const valorActual = parseInt(element.textContent) || 0;
        if (valorActual !== valor) {
            animarContador(element, valorActual, valor);
        } else {
            element.textContent = valor;
        }
    }
}

function animarContador(element, inicio, fin) {
    const duracion = 1000; // 1 segundo
    const incremento = (fin - inicio) / (duracion / 16); // 60fps
    let actual = inicio;
    const timer = setInterval(() => {
        actual += incremento;
        if ((incremento > 0 && actual >= fin) || (incremento < 0 && actual <= fin)) {
            actual = fin;
            clearInterval(timer);
        }
        element.textContent = Math.round(actual);
    }, 16);
}

function renderizarTablaUsuarios(usuarios) {
    const tbody = document.getElementById('tablaUsuariosBody');
    if (!tbody) {
        console.error('No se encontró el cuerpo de la tabla');
        return;
    }
    
    tbody.innerHTML = '';
    
    // Aplicar filtros
    const usuariosFiltrados = aplicarFiltros(usuarios);
    
    if (usuariosFiltrados.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-user-slash text-3xl mb-3"></i>
                    <p class="text-lg font-medium">No se encontraron usuarios</p>
                    <p class="text-sm mt-1">Intenta ajustar los filtros de búsqueda</p>
                </td>
            </tr>
        `;
        
        actualizarContador(0);
        return;
    }
    
    usuariosFiltrados.forEach(usuario => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition-colors duration-150';
        
        // Determinar clases para badges
        let tipoBadgeClass = '';
        let tipoTexto = '';
        switch(parseInt(usuario.tipo)) {
            case 1: tipoBadgeClass = 'bg-blue-100 text-blue-800'; tipoTexto = 'Docente'; break;
            case 2: tipoBadgeClass = 'bg-red-100 text-red-800'; tipoTexto = 'Administrador'; break;
            case 3: tipoBadgeClass = 'bg-green-100 text-green-800'; tipoTexto = 'Estudiante'; break;
            default: tipoBadgeClass = 'bg-gray-100 text-gray-800'; tipoTexto = 'Usuario';
        }
        
        const estadoBadgeClass = usuario.estado == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        const estadoTexto = usuario.estado == 1 ? 'Activo' : 'Inactivo';
        const iniciales = usuario.usuario ? usuario.usuario.substring(0, 2).toUpperCase() : '??';
        
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-primary-blue rounded-lg flex items-center justify-center text-white font-bold">
                        ${iniciales}
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${usuario.usuario || 'Sin nombre'}</div>
                        <div class="text-xs text-gray-500">ID: ${usuario.id || 'N/A'}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full ${tipoBadgeClass}">
                    ${tipoTexto}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${usuario.nombre_completo || 'No vinculado'}</div>
                ${usuario.documento ? `<div class="text-xs text-gray-500">DNI: ${usuario.documento}</div>` : ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full ${estadoBadgeClass}">
                    ${estadoTexto}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                <div class="flex flex-col">
                    <span class="${getColorUltimoAcceso(usuario.ultimo_acceso)}">
                        ${usuario.ultimo_acceso || 'Nunca'}
                    </span>
                    ${usuario.ultimo_acceso !== 'Nunca' ? 
                        `<span class="text-xs text-gray-500 mt-1">
                            ${calcularTiempoDesde(usuario.ultimo_acceso)}
                        </span>` : ''
                    }
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button class="btn-ver text-blue-600 hover:text-blue-900 transition-colors" 
                            data-id="${usuario.id}" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-editar text-yellow-600 hover:text-yellow-900 transition-colors" 
                            data-id="${usuario.id}" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-reset text-green-600 hover:text-green-900 transition-colors" 
                            data-id="${usuario.id}" 
                            data-usuario="${usuario.usuario || ''}" 
                            title="Restablecer contraseña">
                        <i class="fas fa-key"></i>
                    </button>
                    <button class="btn-eliminar text-red-600 hover:text-red-900 transition-colors" 
                            data-id="${usuario.id}" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
    
    actualizarContador(usuariosFiltrados.length);
    agregarEventListenersTabla();
}

function getColorUltimoAcceso(ultimoAcceso) {
    if (ultimoAcceso === 'Nunca') return 'text-gray-500';
    
    // Verificar si contiene "Hace" para indicar actividad reciente
    if (ultimoAcceso.includes('Hace')) {
        const tiempo = ultimoAcceso.toLowerCase();
        if (tiempo.includes('segundos') || tiempo.includes('min') || tiempo.includes('h')) {
            return 'text-green-600 font-medium';
        }
    }
    
    return 'text-gray-900';
}

function calcularTiempoDesde(fechaStr) {
    if (fechaStr === 'Nunca') return '';
    
    try {
        // Intentar parsear diferentes formatos
        let fecha;
        
        if (fechaStr.includes('/')) {
            // Formato d/m/Y H:i
            const parts = fechaStr.split(' ');
            const dateParts = parts[0].split('/');
            fecha = new Date(dateParts[2], dateParts[1] - 1, dateParts[0], 
                            parts[1] ? parts[1].split(':')[0] : 0, 
                            parts[1] ? parts[1].split(':')[1] : 0);
        } else if (fechaStr.includes('Hace')) {
            return 'Actividad reciente';
        } else {
            // Formato ISO
            fecha = new Date(fechaStr);
        }
        
        const ahora = new Date();
        const diffMs = ahora - fecha;
        const diffDias = Math.floor(diffMs / (1000 * 60 * 60 * 24));
        
        if (diffDias === 0) {
            const diffHoras = Math.floor(diffMs / (1000 * 60 * 60));
            if (diffHoras > 0) {
                return `Hace ${diffHoras} ${diffHoras === 1 ? 'hora' : 'horas'}`;
            }
            const diffMin = Math.floor(diffMs / (1000 * 60));
            return `Hace ${diffMin} ${diffMin === 1 ? 'minuto' : 'minutos'}`;
        } else if (diffDias === 1) {
            return 'Ayer';
        } else if (diffDias < 7) {
            return `Hace ${diffDias} días`;
        } else if (diffDias < 30) {
            const semanas = Math.floor(diffDias / 7);
            return `Hace ${semanas} ${semanas === 1 ? 'semana' : 'semanas'}`;
        } else {
            const meses = Math.floor(diffDias / 30);
            return `Hace ${meses} ${meses === 1 ? 'mes' : 'meses'}`;
        }
    } catch (e) {
        return '';
    }
}

function aplicarFiltros(usuarios) {
    const filtroTipo = document.getElementById('filtroTipo')?.value || 'all';
    const filtroEstado = document.getElementById('filtroEstado')?.value || 'all';
    const busqueda = document.getElementById('buscarUsuario')?.value.toLowerCase() || '';
    
    let usuariosFiltrados = usuarios;
    
    // Filtrar por tipo
    if (filtroTipo !== 'all') {
        usuariosFiltrados = usuariosFiltrados.filter(u => u.tipo.toString() === filtroTipo);
    }
    
    // Filtrar por estado
    if (filtroEstado !== 'all') {
        usuariosFiltrados = usuariosFiltrados.filter(u => u.estado.toString() === filtroEstado);
    }
    
    // Filtrar por búsqueda
    if (busqueda) {
        usuariosFiltrados = usuariosFiltrados.filter(u => 
            (u.usuario && u.usuario.toLowerCase().includes(busqueda)) ||
            (u.nombre_completo && u.nombre_completo.toLowerCase().includes(busqueda)) ||
            (u.documento && u.documento.toString().includes(busqueda))
        );
    }
    
    return usuariosFiltrados;
}

function actualizarContador(cantidad) {
    const contador = document.getElementById('usuarios-mostrados');
    if (contador) {
        contador.textContent = `${cantidad} usuarios`;
    }
}

function agregarEventListenersTabla() {
    // Botones ver
    document.querySelectorAll('.btn-ver').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            verUsuario(id);
        });
    });
    
    // Botones editar
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            editarUsuario(id);
        });
    });
    
    // Botones reset
    document.querySelectorAll('.btn-reset').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombreUsuario = this.getAttribute('data-usuario');
            restablecerPassword(id, nombreUsuario);
        });
    });
    
    // Botones eliminar
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            confirmarEliminarUsuario(id);
        });
    });
}

function confirmarEliminarUsuario(id) {
    usuarioAEliminar = id;
    
    // Mostrar modal de confirmación
    const modal = document.getElementById('modalConfirmarEliminar');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }
}

// ==============================
// FUNCIONES DE GESTIÓN DE USUARIOS
// ==============================

function abrirModalNuevoUsuario() {
    console.log('Abriendo modal nuevo usuario');
    
    // Resetear formulario
    const form = document.getElementById('formNuevoUsuario');
    if (form) form.reset();
    
    // Resetear selección
    personaSeleccionada = null;
    
    // Ocultar secciones
    const seleccionContainer = document.getElementById('seleccionPersonaContainer');
    const infoAdmin = document.getElementById('infoAdministrador');
    const preview = document.getElementById('personaSeleccionadaPreview');
    
    if (seleccionContainer) seleccionContainer.style.display = 'none';
    if (infoAdmin) infoAdmin.style.display = 'none';
    if (preview) preview.classList.remove('visible');
    
    // Resetear preview
    const avatar = document.getElementById('previewAvatar');
    const nombre = document.getElementById('previewNombre');
    const detalle = document.getElementById('previewDetalle');
    
    if (avatar) avatar.textContent = '?';
    if (nombre) nombre.textContent = 'Sin persona seleccionada';
    if (detalle) detalle.textContent = 'Selecciona una persona';
    
    // Mostrar modal
    const modal = document.getElementById('modalNuevoUsuario');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }
}

function mostrarSeleccionPersona() {
    const tipoSelect = document.getElementById('nuevoTipo');
    if (!tipoSelect) return;
    
    const tipo = tipoSelect.value;
    const seleccionContainer = document.getElementById('seleccionPersonaContainer');
    const infoAdmin = document.getElementById('infoAdministrador');
    
    if (!seleccionContainer || !infoAdmin) return;
    
    if (tipo === '1' || tipo === '3') {
        seleccionContainer.style.display = 'block';
        infoAdmin.style.display = 'none';
        
        // Actualizar título
        const titulo = document.getElementById('tituloListaPersonas');
        if (titulo) {
            titulo.textContent = tipo === '1' ? 'Seleccionar Docente' : 'Seleccionar Estudiante';
        }
    } else if (tipo === '2') {
        seleccionContainer.style.display = 'none';
        infoAdmin.style.display = 'block';
        
        // Limpiar selección
        personaSeleccionada = null;
        const preview = document.getElementById('personaSeleccionadaPreview');
        if (preview) preview.classList.remove('visible');
    } else {
        seleccionContainer.style.display = 'none';
        infoAdmin.style.display = 'none';
    }
}

async function abrirModalSeleccionPersona() {
    const tipoSelect = document.getElementById('nuevoTipo');
    if (!tipoSelect || !tipoSelect.value) {
        mostrarNotificacion('Error', 'Primero selecciona un tipo de usuario', 'error');
        return;
    }
    
    esModoEdicion = false;
    await cargarListaPersonas(tipoSelect.value);
    
    const modal = document.getElementById('modalSeleccionPersona');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }
}

async function cargarListaPersonas(tipo) {
    try {
        let endpoint = '';
        let tipoTexto = '';
        
        if (tipo === '1') {
            endpoint = 'apiEmpleados';
            tipoTexto = 'DOCENTE';
        } else if (tipo === '3') {
            endpoint = 'apiEstudiantes';
            tipoTexto = 'ESTUDIANTE';
        } else {
            return;
        }
        
        console.log('Cargando personas desde:', apiUrl + endpoint);
        const response = await fetch(apiUrl + endpoint);
        const result = await response.json();
        
        const lista = document.getElementById('listaPersonas');
        const contador = document.getElementById('contadorPersonas');
        const sinResultados = document.getElementById('sinResultados');
        
        if (!lista || !contador || !sinResultados) return;
        
        lista.innerHTML = '';
        
        if (!result.success || !result.data || result.data.length === 0) {
            lista.style.display = 'none';
            sinResultados.style.display = 'block';
            contador.textContent = '0 personas encontradas';
            return;
        }
        
        lista.style.display = 'block';
        sinResultados.style.display = 'none';
        
        result.data.forEach(persona => {
            const div = document.createElement('div');
            div.className = 'persona-option';
            div.dataset.id = persona.id;
            div.dataset.tipo = tipo;
            
            const nombre = persona.nombre_completo || persona.apnom_emp || persona.nom_est;
            const detalle = persona.dni_emp || persona.dni_est || '';
            
            div.innerHTML = `
                <div class="persona-info">
                    <div class="persona-nombre">${nombre}</div>
                    <div class="persona-detalle">DNI: ${detalle}</div>
                </div>
                <span class="persona-tipo ${tipo === '1' ? 'tipo-docente' : 'tipo-estudiante'}">
                    ${tipoTexto}
                </span>
            `;
            
            div.addEventListener('click', function() {
                // Deseleccionar otros
                document.querySelectorAll('.persona-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
                
                // Habilitar botón de confirmación
                const confirmBtn = document.getElementById('confirmarSeleccion');
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
                }
            });
            
            lista.appendChild(div);
        });
        
        contador.textContent = `${result.data.length} personas encontradas`;
        
    } catch (error) {
        console.error('Error cargando personas:', error);
        mostrarNotificacion('Error', 'No se pudieron cargar las personas', 'error');
    }
}

function filtrarPersonas() {
    const busquedaInput = document.getElementById('buscarPersona');
    if (!busquedaInput) return;
    
    const busqueda = busquedaInput.value.toLowerCase();
    const opciones = document.querySelectorAll('.persona-option');
    const contador = document.getElementById('contadorPersonas');
    const sinResultados = document.getElementById('sinResultados');
    const lista = document.getElementById('listaPersonas');
    
    if (!contador || !sinResultados || !lista) return;
    
    let visibleCount = 0;
    
    opciones.forEach(opcion => {
        const nombre = opcion.querySelector('.persona-nombre')?.textContent.toLowerCase() || '';
        const detalle = opcion.querySelector('.persona-detalle')?.textContent.toLowerCase() || '';
        
        if (nombre.includes(busqueda) || detalle.includes(busqueda)) {
            opcion.style.display = 'flex';
            visibleCount++;
        } else {
            opcion.style.display = 'none';
        }
    });
    
    if (visibleCount === 0) {
        lista.style.display = 'none';
        sinResultados.style.display = 'block';
    } else {
        lista.style.display = 'block';
        sinResultados.style.display = 'none';
    }
    
    contador.textContent = `${visibleCount} personas encontradas`;
}

function confirmarSeleccionPersona() {
    // Verificar si hay una persona seleccionada en la lista
    const personaOption = document.querySelector('.persona-option.selected');
    if (!personaOption) {
        mostrarNotificacion('Error', 'Debes seleccionar una persona primero', 'error');
        return;
    }
    
    // Obtener datos de la persona seleccionada
    const personaId = personaOption.dataset.id;
    const personaTipo = personaOption.dataset.tipo;
    const personaNombre = personaOption.querySelector('.persona-nombre').textContent;
    const personaDetalle = personaOption.querySelector('.persona-detalle').textContent;
    
    console.log('Persona seleccionada:', { personaId, personaTipo, personaNombre, personaDetalle });
    
    // Actualizar preview
    const preview = document.getElementById('personaSeleccionadaPreview');
    const avatar = document.getElementById('previewAvatar');
    const nombre = document.getElementById('previewNombre');
    const detalle = document.getElementById('previewDetalle');
    
    if (preview) preview.classList.add('visible');
    if (avatar) avatar.textContent = personaNombre.substring(0, 2).toUpperCase();
    if (nombre) nombre.textContent = personaNombre;
    if (detalle) detalle.textContent = personaDetalle;
    
    // Actualizar campos ocultos
    const personaIdInput = document.getElementById('personaSeleccionadaId');
    const personaTipoInput = document.getElementById('personaSeleccionadaTipo');
    
    if (personaIdInput) {
        personaIdInput.value = personaId;
        console.log('Input personaId actualizado:', personaIdInput.value);
    }
    
    if (personaTipoInput) {
        personaTipoInput.value = personaTipo;
        console.log('Input personaTipo actualizado:', personaTipoInput.value);
    }
    
    // Guardar también en variable global para referencia
    personaSeleccionada = {
        id: personaId,
        tipo: personaTipo,
        nombre: personaNombre,
        detalle: personaDetalle
    };
    
    cerrarModalSeleccionPersona();
}

function cerrarModalSeleccionPersona() {
    const modal = document.getElementById('modalSeleccionPersona');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }
    
    const buscarInput = document.getElementById('buscarPersona');
    if (buscarInput) buscarInput.value = '';
    
    const confirmBtn = document.getElementById('confirmarSeleccion');
    if (confirmBtn) confirmBtn.disabled = true;
    
    personaSeleccionada = null;
}

async function crearUsuario(event) {
    if (event) event.preventDefault();
    
    const form = document.getElementById('formNuevoUsuario');
    if (!form) return;
    
    // Validar formulario
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Validar contraseñas
    const password = document.getElementById('nuevaPassword').value;
    const passwordConfirm = document.getElementById('nuevaPasswordConfirm').value;
    const tipo = document.getElementById('nuevoTipo').value;
    
    if (password !== passwordConfirm) {
        mostrarNotificacion('Error', 'Las contraseñas no coinciden', 'error');
        return;
    }
    
    if (password.length < 8) {
        mostrarNotificacion('Error', 'La contraseña debe tener al menos 8 caracteres', 'error');
        return;
    }
    
    // OBTENER VALOR DEL INPUT OCULTO
    const personaIdInput = document.getElementById('personaSeleccionadaId');
    const tienePersonaVinculada = personaIdInput && personaIdInput.value && personaIdInput.value.trim() !== '';
    
    // Solo validar para docentes y estudiantes
    if ((tipo === '1' || tipo === '3') && !tienePersonaVinculada) {
        mostrarNotificacion('Error', 'Debes seleccionar una persona para vincular', 'error');
        return;
    }
    
    try {
        mostrarCarga('Creando usuario...');
        
        // Preparar datos
        const data = {
            usuario: document.getElementById('nuevoUsuario').value,
            password: password,
            tipo: tipo,
            estado: document.getElementById('nuevoEstado').value,
            csrf_token: document.getElementById('csrf_token_nuevo')?.value || 
                        document.querySelector('input[name="csrf_token"]')?.value
        };
        
        // Agregar persona solo si existe
        if (tienePersonaVinculada) {
            data.estuempleado = personaIdInput.value;
        }
        
        console.log('Datos a enviar:', data);
        
        // Enviar petición
        const response = await fetch(apiUrl + 'crear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        });
        
        const result = await response.json();
        console.log('Respuesta del servidor:', result);
        
        if (result.success) {
            mostrarNotificacion('Usuario creado', 'El usuario ha sido creado exitosamente', 'success');
            cerrarModalNuevoUsuario();
            await cargarUsuarios();
        } else {
            throw new Error(result.message || 'Error al crear usuario');
        }
    } catch (error) {
        console.error('Error creando usuario:', error);
        mostrarNotificacion('Error', error.message, 'error');
    } finally {
        ocultarCarga();
    }
}

function cerrarModalNuevoUsuario() {
    const modal = document.getElementById('modalNuevoUsuario');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }
}

async function verUsuario(id) {
    try {
        mostrarCarga('Cargando detalles...');
        
        // En una implementación real, harías una petición al servidor
        // Por ahora usaremos datos de ejemplo
        const usuarios = await obtenerUsuariosDesdeCache();
        const usuario = usuarios.find(u => u.id == id);
        
        if (!usuario) {
            throw new Error('Usuario no encontrado');
        }
        
        mostrarDetallesUsuario(usuario);
        ocultarCarga();
    } catch (error) {
        console.error('Error al ver usuario:', error);
        mostrarNotificacion('Error', error.message, 'error');
        ocultarCarga();
    }
}

async function obtenerUsuariosDesdeCache() {
    try {
        const response = await fetch(apiUrl + 'apiUsuarios');
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error obteniendo usuarios desde cache:', error);
        return [];
    }
}

function filtrarPersonasEditar(termino) {
    const opciones = document.querySelectorAll('#listaPersonasEditar .persona-option');
    const contador = document.getElementById('contadorPersonasEditar');
    const sinResultados = document.getElementById('sinResultadosEditar');
    const lista = document.getElementById('listaPersonasEditar');
    
    if (!contador || !sinResultados || !lista) return;
    
    let visibleCount = 0;
    
    opciones.forEach(opcion => {
        const nombre = opcion.querySelector('.persona-nombre')?.textContent.toLowerCase() || '';
        const detalle = opcion.querySelector('.persona-detalle')?.textContent.toLowerCase() || '';
        
        if (nombre.includes(termino) || detalle.includes(termino)) {
            opcion.style.display = 'flex';
            visibleCount++;
        } else {
            opcion.style.display = 'none';
        }
    });
    
    if (visibleCount === 0) {
        lista.style.display = 'none';
        sinResultados.style.display = 'block';
    } else {
        lista.style.display = 'block';
        sinResultados.style.display = 'none';
    }
    
    contador.textContent = `${visibleCount} personas encontradas`;
}

// Agrega esta función para manejar el cierre del modal de confirmación
window.addEventListener('click', function(event) {
    const modal = document.getElementById('modalConfirmarEliminar');
    if (modal && event.target === modal) {
        cerrarModalConfirmarEliminar();
    }
});


function mostrarDetallesUsuario(usuario) {
    const contenido = document.getElementById('contenidoDetalles');
    if (!contenido) return;
    
    // Determinar tipo y estado
    let tipoTexto = '';
    let tipoClase = '';
    let estadoTexto = usuario.estado == 1 ? 'Activo' : 'Inactivo';
    let estadoClase = usuario.estado == 1 ? 'badge-activo' : 'badge-inactivo';
    
    switch(usuario.tipo) {
        case 1: tipoTexto = 'Docente'; tipoClase = 'badge-docente'; break;
        case 2: tipoTexto = 'Administrador'; tipoClase = 'badge-admin'; break;
        case 3: tipoTexto = 'Estudiante'; tipoClase = 'badge-estudiante'; break;
    }
    
    // Avatar con iniciales
    const iniciales = usuario.usuario ? usuario.usuario.substring(0, 2).toUpperCase() : '??';
    
    contenido.innerHTML = `
        <div class="space-y-6">
            <div class="flex items-center space-x-4">
                <div class="avatar-usuario w-16 h-16 text-lg">
                    ${iniciales}
                </div>
                <div>
                    <h4 class="text-xl font-semibold text-gray-900">${usuario.usuario}</h4>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="badge-tipo ${tipoClase}">${tipoTexto}</span>
                        <span class="badge-estado ${estadoClase}">${estadoTexto}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">ID Usuario</label>
                    <p class="text-gray-900">${usuario.id}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Persona Vinculada</label>
                    <p class="text-gray-900 font-medium">${usuario.nombre_completo || 'No vinculado'}</p>
                    ${usuario.documento ? `<p class="text-sm text-gray-600 mt-1">DNI: ${usuario.documento}</p>` : ''}
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Fecha Creación</label>
                    <p class="text-gray-900">${usuario.fecha_creacion || 'No disponible'}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Último Acceso</label>
                    <p class="text-gray-900">${usuario.ultimo_acceso || 'Nunca'}</p>
                </div>
            </div>
            
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            ${usuario.tipo == 1 ? 
                                'Usuario con permisos para gestionar prácticas' : 
                                usuario.tipo == 2 ? 
                                'Usuario con permisos completos del sistema' : 
                                'Usuario con permisos de consulta de información personal'}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Mostrar modal
    const modal = document.getElementById('modalDetallesUsuario');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }
}

async function editarUsuario(id) {
    try {
        mostrarCarga('Cargando datos del usuario...');
        
        // En una implementación real, harías una petición al servidor
        // Por ahora usaremos datos de ejemplo
        const usuarios = await obtenerUsuariosDesdeCache();
        const usuario = usuarios.find(u => u.id == id);
        
        if (!usuario) {
            throw new Error('Usuario no encontrado');
        }
        
        // Llenar formulario
        document.getElementById('editarId').value = usuario.id;
        document.getElementById('editarUsuario').value = usuario.usuario;
        document.getElementById('editarTipo').value = usuario.tipo;
        document.getElementById('editarEstado').value = usuario.estado;
        
        // Configurar persona vinculada si existe
        if (usuario.estuempleado) {
            document.getElementById('editarPersonaSeleccionadaId').value = usuario.estuempleado;
            document.getElementById('editarPersonaSeleccionadaTipo').value = usuario.tipo;
            
            const avatar = document.getElementById('editarPreviewAvatar');
            const nombre = document.getElementById('editarPreviewNombre');
            const detalle = document.getElementById('editarPreviewDetalle');
            
            if (avatar) avatar.textContent = usuario.nombre_completo ? usuario.nombre_completo.substring(0, 2).toUpperCase() : '??';
            if (nombre) nombre.textContent = usuario.nombre_completo || 'Persona vinculada';
            if (detalle) detalle.textContent = usuario.documento ? `DNI: ${usuario.documento}` : 'Información no disponible';
            
            document.getElementById('infoPersonaVinculada').style.display = 'block';
        } else {
            document.getElementById('infoPersonaVinculada').style.display = 'none';
        }
        
        ocultarCarga();
        
        // Mostrar modal
        const modal = document.getElementById('modalEditarUsuario');
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }
    } catch (error) {
        console.error('Error al editar usuario:', error);
        mostrarNotificacion('Error', error.message, 'error');
        ocultarCarga();
    }
}

function mostrarSeleccionPersonaEditar() {
    const tipo = document.getElementById('editarTipo').value;
    const container = document.getElementById('infoPersonaVinculada');
    
    if (tipo === '1' || tipo === '3') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

async function abrirModalSeleccionPersonaEditar() {
    const tipo = document.getElementById('editarTipo').value;
    if (!tipo) {
        mostrarNotificacion('Error', 'Primero selecciona un tipo de usuario', 'error');
        return;
    }
    
    esModoEdicion = true;
    await cargarListaPersonasEditar(tipo);
    
    const modal = document.getElementById('modalSeleccionPersonaEditar');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }
}

async function cargarListaPersonasEditar(tipo) {
    try {
        let endpoint = '';
        let tipoTexto = '';
        
        if (tipo === '1') {
            endpoint = 'apiEmpleados';
            tipoTexto = 'DOCENTE';
        } else if (tipo === '3') {
            endpoint = 'apiEstudiantes';
            tipoTexto = 'ESTUDIANTE';
        } else {
            return;
        }
        
        const response = await fetch(apiUrl + endpoint);
        const result = await response.json();
        
        const lista = document.getElementById('listaPersonasEditar');
        const contador = document.getElementById('contadorPersonasEditar');
        const sinResultados = document.getElementById('sinResultadosEditar');
        const titulo = document.getElementById('tituloListaPersonasEditar');
        
        if (!lista || !contador || !sinResultados || !titulo) return;
        
        lista.innerHTML = '';
        
        if (!result.success || !result.data || result.data.length === 0) {
            lista.style.display = 'none';
            sinResultados.style.display = 'block';
            contador.textContent = '0 personas encontradas';
            return;
        }
        
        lista.style.display = 'block';
        sinResultados.style.display = 'none';
        titulo.textContent = tipo === '1' ? 'Seleccionar Docente' : 'Seleccionar Estudiante';
        
        result.data.forEach(persona => {
            const div = document.createElement('div');
            div.className = 'persona-option';
            div.dataset.id = persona.id;
            div.dataset.tipo = tipo;
            
            const nombre = persona.nombre_completo || persona.apnom_emp || persona.nom_est;
            const detalle = persona.dni_emp || persona.dni_est || '';
            
            div.innerHTML = `
                <div class="persona-info">
                    <div class="persona-nombre">${nombre}</div>
                    <div class="persona-detalle">DNI: ${detalle}</div>
                </div>
                <span class="persona-tipo ${tipo === '1' ? 'tipo-docente' : 'tipo-estudiante'}">
                    ${tipoTexto}
                </span>
            `;
            
            div.addEventListener('click', () => {
                // Deseleccionar otros
                document.querySelectorAll('#listaPersonasEditar .persona-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                div.classList.add('selected');
                
                // Habilitar botón de confirmación
                const confirmBtn = document.getElementById('confirmarSeleccionEditar');
                if (confirmBtn) confirmBtn.disabled = false;
            });
            
            lista.appendChild(div);
        });
        
        contador.textContent = `${result.data.length} personas encontradas`;
        
    } catch (error) {
        console.error('Error cargando personas para editar:', error);
        mostrarNotificacion('Error', 'No se pudieron cargar las personas', 'error');
    }
}

function confirmarSeleccionPersonaEditar() {
    const personaOption = document.querySelector('#listaPersonasEditar .persona-option.selected');
    if (!personaOption) {
        mostrarNotificacion('Error', 'Debes seleccionar una persona primero', 'error');
        return;
    }
    
    const personaId = personaOption.dataset.id;
    const personaTipo = personaOption.dataset.tipo;
    const personaNombre = personaOption.querySelector('.persona-nombre').textContent;
    const personaDetalle = personaOption.querySelector('.persona-detalle').textContent;
    
    // Actualizar preview en edición
    const avatar = document.getElementById('editarPreviewAvatar');
    const nombre = document.getElementById('editarPreviewNombre');
    const detalle = document.getElementById('editarPreviewDetalle');
    
    if (avatar) avatar.textContent = personaNombre.substring(0, 2).toUpperCase();
    if (nombre) nombre.textContent = personaNombre;
    if (detalle) detalle.textContent = personaDetalle;
    
    // Actualizar campos ocultos
    const personaIdInput = document.getElementById('editarPersonaSeleccionadaId');
    const personaTipoInput = document.getElementById('editarPersonaSeleccionadaTipo');
    
    if (personaIdInput) personaIdInput.value = personaId;
    if (personaTipoInput) personaTipoInput.value = personaTipo;
    
    cerrarModalSeleccionPersonaEditar();
}

function cerrarModalSeleccionPersonaEditar() {
    const modal = document.getElementById('modalSeleccionPersonaEditar');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }
    
    const buscarInput = document.getElementById('buscarPersonaEditar');
    if (buscarInput) buscarInput.value = '';
    
    const confirmBtn = document.getElementById('confirmarSeleccionEditar');
    if (confirmBtn) confirmBtn.disabled = true;
}

async function guardarEditarUsuario() {
    const form = document.getElementById('formEditarUsuario');
    if (!form) return;
    
    // Validar formulario
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const id = document.getElementById('editarId').value;
    const tipo = document.getElementById('editarTipo').value;
    const password = document.getElementById('editarPassword').value;
    const personaIdInput = document.getElementById('editarPersonaSeleccionadaId');
    
    // Validar contraseña si se proporciona
    if (password && password.length < 8) {
        mostrarNotificacion('Error', 'La contraseña debe tener al menos 8 caracteres', 'error');
        return;
    }
    
    // Validar selección de persona para docentes y estudiantes
    if ((tipo === '1' || tipo === '3') && (!personaIdInput || !personaIdInput.value)) {
        mostrarNotificacion('Error', 'Debes seleccionar una persona para vincular', 'error');
        return;
    }
    
    try {
        mostrarCarga('Actualizando usuario...');
        
        // Preparar datos
        const data = {
            id: id,
            usuario: document.getElementById('editarUsuario').value,
            tipo: tipo,
            estado: document.getElementById('editarEstado').value,
            csrf_token: document.getElementById('csrf_token_editar').value
        };
        
        // Agregar contraseña si se proporciona
        if (password) {
            data.password = password;
        }
        
        // Agregar persona si existe
        if (personaIdInput && personaIdInput.value) {
            data.estuempleado = personaIdInput.value;
        }
        
        console.log('Datos a actualizar:', data);
        
        // Enviar petición
        const response = await fetch(apiUrl + 'editar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        });
        
        const result = await response.json();
        console.log('Respuesta del servidor:', result);
        
        if (result.success) {
            mostrarNotificacion('Éxito', 'Usuario actualizado exitosamente', 'success');
            cerrarModalEditarUsuario();
            await cargarUsuarios();
        } else {
            throw new Error(result.message || 'Error al actualizar usuario');
        }
    } catch (error) {
        console.error('Error actualizando usuario:', error);
        mostrarNotificacion('Error', error.message, 'error');
    } finally {
        ocultarCarga();
    }
}

function cerrarModalEditarUsuario() {
    const modal = document.getElementById('modalEditarUsuario');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }
}


async function restablecerPassword(id, nombreUsuario) {
    usuarioAResetear = { id, nombreUsuario };
    
    // Actualizar información en el modal
    document.getElementById('resetUsuarioNombre').textContent = nombreUsuario;
    document.getElementById('resetUsuarioId').value = id;
    
    // Generar contraseña temporal
    const tempPassword = generarPasswordTemporal();
    document.getElementById('nuevaPasswordTemp').value = tempPassword;
    
    // Mostrar modal
    const modal = document.getElementById('modalResetPassword');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }
}

function generarPasswordTemporal() {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%';
    let password = '';
    for (let i = 0; i < 10; i++) {
        password += caracteres[Math.floor(Math.random() * caracteres.length)];
    }
    return password;
}

async function confirmarRestablecerPassword() {
    if (!usuarioAResetear) return;
    
    try {
        mostrarCarga('Restableciendo contraseña...');
        
        const data = {
            id: usuarioAResetear.id,
            csrf_token: document.querySelector('input[name="csrf_token"]').value
        };
        
        console.log('Restableciendo contraseña para:', usuarioAResetear.id);
        
        const response = await fetch(apiUrl + 'resetPassword', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        });
        
        const result = await response.json();
        console.log('Respuesta del servidor:', result);
        
        if (result.success) {
            mostrarNotificacion('Contraseña restablecida', `Se ha generado una nueva contraseña para ${usuarioAResetear.nombreUsuario}`, 'success');
            
            // Mostrar contraseña temporal (opcional)
            if (result.tempPassword) {
                console.log('Contraseña temporal generada:', result.tempPassword);
            }
            
            cerrarModalResetPassword();
            await cargarUsuarios();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error restableciendo contraseña:', error);
        mostrarNotificacion('Error', error.message, 'error');
    } finally {
        ocultarCarga();
    }
}

function cerrarModalResetPassword() {
    const modal = document.getElementById('modalResetPassword');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }
    usuarioAResetear = null;
}

async function eliminarUsuario() {
    if (!usuarioAEliminar) return;
    
    try {
        mostrarCarga('Eliminando usuario...');
        
        const data = {
            id: usuarioAEliminar,
            csrf_token: document.querySelector('input[name="csrf_token"]').value
        };
        
        console.log('Eliminando usuario:', usuarioAEliminar);
        
        const response = await fetch(apiUrl + 'eliminar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        });
        
        const result = await response.json();
        console.log('Respuesta del servidor:', result);
        
        if (result.success) {
            mostrarNotificacion('Usuario eliminado', 'El usuario ha sido eliminado del sistema', 'success');
            cerrarModalConfirmarEliminar();
            await cargarUsuarios();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error eliminando usuario:', error);
        mostrarNotificacion('Error', error.message, 'error');
    } finally {
        ocultarCarga();
        usuarioAEliminar = null;
    }
}

async function eliminarUsuario() {
    if (!usuarioAEliminar) return;
    
    try {
        mostrarCarga('Eliminando usuario...');
        
        const formData = new FormData();
        formData.append('id', usuarioAEliminar);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
        
        const response = await fetch(apiUrl + 'eliminar', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            mostrarNotificacion('Éxito', 'Usuario eliminado exitosamente', 'success');
            cerrarModalConfirmarEliminar();
            await cargarUsuarios();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        mostrarNotificacion('Error', error.message, 'error');
    } finally {
        ocultarCarga();
        usuarioAEliminar = null;
    }
}

function cerrarModalConfirmarEliminar() {
    const modal = document.getElementById('modalConfirmarEliminar');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }
    usuarioAEliminar = null;
}

// ==============================
// GRÁFICOS
// ==============================

function inicializarGraficos(usuarios) {
    if (!window.Chart || usuarios.length === 0) return;
    
    // Destruir gráficos anteriores si existen
    if (window.tipoChart) window.tipoChart.destroy();
    if (window.estadoChart) window.estadoChart.destroy();
    
    // Gráfico de distribución por tipo (Doughnut mejorado)
    const tipoCount = {
        'Docente': usuarios.filter(u => u.tipo == 1).length,
        'Administrador': usuarios.filter(u => u.tipo == 2).length,
        'Estudiante': usuarios.filter(u => u.tipo == 3).length
    };
    
    const ctxTipo = document.getElementById('tipoUsuarioChart');
    if (ctxTipo) {
        window.tipoChart = new Chart(ctxTipo, {
            type: 'doughnut',
            data: {
                labels: ['Docentes', 'Administradores', 'Estudiantes'],
                datasets: [{
                    data: [tipoCount['Docente'], tipoCount['Administrador'], tipoCount['Estudiante']],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',    // Azul para docentes
                        'rgba(139, 92, 246, 0.8)',    // Púrpura para administradores
                        'rgba(6, 182, 212, 0.8)'      // Cyan para estudiantes
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(6, 182, 212, 1)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }
    
    // Gráfico de distribución por estado (Bar mejorado)
    const estadoCount = {
        'Activo': usuarios.filter(u => u.estado == 1).length,
        'Inactivo': usuarios.filter(u => u.estado == 0).length
    };
    
    const ctxEstado = document.getElementById('estadoUsuarioChart');
    if (ctxEstado) {
        window.estadoChart = new Chart(ctxEstado, {
            type: 'bar',
            data: {
                labels: ['Activos', 'Inactivos'],
                datasets: [{
                    label: 'Usuarios',
                    data: [estadoCount['Activo'], estadoCount['Inactivo']],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
}

// ==============================
// UTILIDADES
// ==============================

function mostrarCarga(mensaje = 'Procesando...') {
    // Si tienes un spinner, muéstralo aquí
    console.log('Cargando:', mensaje);
    
    // Ejemplo básico de spinner
    let spinner = document.getElementById('globalSpinner');
    if (!spinner) {
        spinner = document.createElement('div');
        spinner.id = 'globalSpinner';
        spinner.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden';
        spinner.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mb-4"></div>
                <div class="text-gray-700 font-medium">${mensaje}</div>
            </div>
        `;
        document.body.appendChild(spinner);
    }
    
    spinner.classList.remove('hidden');
    const texto = spinner.querySelector('.text-gray-700');
    if (texto) texto.textContent = mensaje;
}

function ocultarCarga() {
    const spinner = document.getElementById('globalSpinner');
    if (spinner) {
        spinner.classList.add('hidden');
    }
}

// ==============================
// INICIALIZACIÓN DE EVENTOS
// ==============================

function inicializarEventListeners() {
    console.log('Inicializando event listeners...');
    
    // ========== BOTÓN NUEVO USUARIO ==========
    const btnNuevo = document.getElementById('btnNuevoUsuario');
    if (btnNuevo) {
        btnNuevo.addEventListener('click', abrirModalNuevoUsuario);
        console.log('Botón nuevo usuario configurado');
    }
    
    // ========== MODAL NUEVO USUARIO ==========
    const cerrarBtn = document.getElementById('cerrarModalNuevo');
    const cancelarBtn = document.getElementById('cancelarNuevoUsuario');
    if (cerrarBtn) cerrarBtn.addEventListener('click', cerrarModalNuevoUsuario);
    if (cancelarBtn) cancelarBtn.addEventListener('click', cerrarModalNuevoUsuario);
    
    // Guardar nuevo usuario
    const guardarBtn = document.getElementById('guardarNuevoUsuario');
    if (guardarBtn) guardarBtn.addEventListener('click', crearUsuario);
    
    // ========== MODAL SELECCIÓN PERSONA ==========
    const cerrarSeleccion = document.getElementById('cerrarModalSeleccion');
    const cancelarSeleccion = document.getElementById('cancelarSeleccion');
    const confirmarSeleccion = document.getElementById('confirmarSeleccion');
    
    if (cerrarSeleccion) cerrarSeleccion.addEventListener('click', cerrarModalSeleccionPersona);
    if (cancelarSeleccion) cancelarSeleccion.addEventListener('click', cerrarModalSeleccionPersona);
    if (confirmarSeleccion) confirmarSeleccion.addEventListener('click', confirmarSeleccionPersona);
    
    // Búsqueda en modal personas
    const buscarPersona = document.getElementById('buscarPersona');
    if (buscarPersona) buscarPersona.addEventListener('input', filtrarPersonas);
    
    // ========== MODAL ELIMINAR ==========
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    
    if (cancelarEliminar) cancelarEliminar.addEventListener('click', cerrarModalConfirmarEliminar);
    if (confirmarEliminar) confirmarEliminar.addEventListener('click', eliminarUsuario);
    
    // ========== MODAL DETALLES ==========
    const cerrarDetalles = document.getElementById('cerrarModalDetalles');
    const cerrarDetallesBtn = document.getElementById('cerrarDetalles');
    
    if (cerrarDetalles) cerrarDetalles.addEventListener('click', cerrarModalDetalles);
    if (cerrarDetallesBtn) cerrarDetallesBtn.addEventListener('click', cerrarModalDetalles);
    
    // ========== MODAL EDITAR ==========
    const cerrarEditar = document.getElementById('cerrarModalEditar');
    const cancelarEditar = document.getElementById('cancelarEditarUsuario');
    const guardarEditar = document.getElementById('guardarEditarUsuario');
    
    if (cerrarEditar) cerrarEditar.addEventListener('click', cerrarModalEditarUsuario);
    if (cancelarEditar) cancelarEditar.addEventListener('click', cerrarModalEditarUsuario);
    if (guardarEditar) guardarEditar.addEventListener('click', guardarEditarUsuario);
    
    // ========== MODAL RESET PASSWORD ==========
    const cerrarReset = document.getElementById('cerrarModalReset');
    const cancelarReset = document.getElementById('cancelarReset');
    const confirmarReset = document.getElementById('confirmarReset');
    
    if (cerrarReset) cerrarReset.addEventListener('click', cerrarModalResetPassword);
    if (cancelarReset) cancelarReset.addEventListener('click', cerrarModalResetPassword);
    if (confirmarReset) confirmarReset.addEventListener('click', confirmarRestablecerPassword);
    
    // Botón copiar contraseña temporal
    const copyPasswordTemp = document.getElementById('copyPasswordTemp');
    if (copyPasswordTemp) {
        copyPasswordTemp.addEventListener('click', function() {
            const tempPassword = document.getElementById('nuevaPasswordTemp');
            if (tempPassword) {
                tempPassword.select();
                document.execCommand('copy');
                mostrarNotificacion('Copiado', 'Contraseña copiada al portapapeles', 'success');
            }
        });
    }
    
    // ========== MODAL SELECCIÓN PERSONA EDITAR ==========
    const cerrarSeleccionEditar = document.getElementById('cerrarModalSeleccionEditar');
    const cancelarSeleccionEditar = document.getElementById('cancelarSeleccionEditar');
    const confirmarSeleccionEditar = document.getElementById('confirmarSeleccionEditar');
    
    if (cerrarSeleccionEditar) cerrarSeleccionEditar.addEventListener('click', cerrarModalSeleccionPersonaEditar);
    if (cancelarSeleccionEditar) cancelarSeleccionEditar.addEventListener('click', cerrarModalSeleccionPersonaEditar);
    if (confirmarSeleccionEditar) confirmarSeleccionEditar.addEventListener('click', confirmarSeleccionPersonaEditar);
    
    // Búsqueda en modal editar
    const buscarPersonaEditar = document.getElementById('buscarPersonaEditar');
    if (buscarPersonaEditar) {
        buscarPersonaEditar.addEventListener('input', function() {
            filtrarPersonasEditar(this.value.toLowerCase());
        });
    }
    
    // ========== FILTROS Y BÚSQUEDA ==========
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroEstado = document.getElementById('filtroEstado');
    const buscarUsuario = document.getElementById('buscarUsuario');
    const btnRefrescar = document.getElementById('btnRefrescar');
    
    if (filtroTipo) filtroTipo.addEventListener('change', cargarUsuarios);
    if (filtroEstado) filtroEstado.addEventListener('change', cargarUsuarios);
    if (buscarUsuario) buscarUsuario.addEventListener('input', cargarUsuarios);
    if (btnRefrescar) btnRefrescar.addEventListener('click', cargarUsuarios);
    
    // ========== MOSTRAR/OCULTAR CONTRASEÑAS ==========
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // ========== CERRAR MODALES AL HACER CLIC FUERA ==========
    const modales = [
        'modalNuevoUsuario', 
        'modalSeleccionPersona',
        'modalConfirmarEliminar',
        'modalDetallesUsuario', 
        'modalEditarUsuario', 
        'modalResetPassword', 
        'modalSeleccionPersonaEditar'
    ];
    
    modales.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    switch(modalId) {
                        case 'modalNuevoUsuario':
                            cerrarModalNuevoUsuario();
                            break;
                        case 'modalSeleccionPersona':
                            cerrarModalSeleccionPersona();
                            break;
                        case 'modalConfirmarEliminar':
                            cerrarModalConfirmarEliminar();
                            break;
                        case 'modalDetallesUsuario':
                            cerrarModalDetalles();
                            break;
                        case 'modalEditarUsuario':
                            cerrarModalEditarUsuario();
                            break;
                        case 'modalResetPassword':
                            cerrarModalResetPassword();
                            break;
                        case 'modalSeleccionPersonaEditar':
                            cerrarModalSeleccionPersonaEditar();
                            break;
                    }
                }
            });
        }
    });
    
    // ========== ESC PARA CERRAR MODALES ==========
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            // Cerrar cualquier modal abierto
            const modalesAbiertos = document.querySelectorAll('.modal.show');
            modalesAbiertos.forEach(modal => {
                const modalId = modal.id;
                switch(modalId) {
                    case 'modalNuevoUsuario':
                        cerrarModalNuevoUsuario();
                        break;
                    case 'modalSeleccionPersona':
                        cerrarModalSeleccionPersona();
                        break;
                    case 'modalConfirmarEliminar':
                        cerrarModalConfirmarEliminar();
                        break;
                    case 'modalDetallesUsuario':
                        cerrarModalDetalles();
                        break;
                    case 'modalEditarUsuario':
                        cerrarModalEditarUsuario();
                        break;
                    case 'modalResetPassword':
                        cerrarModalResetPassword();
                        break;
                    case 'modalSeleccionPersonaEditar':
                        cerrarModalSeleccionPersonaEditar();
                        break;
                }
            });
        }
    });
}

function cerrarModalDetalles() {
    const modal = document.getElementById('modalDetallesUsuario');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }
}



// ==============================
// INICIALIZACIÓN AL CARGAR LA PÁGINA
// ==============================

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM completamente cargado - Inicializando dashboard...');
    
    // Inicializar event listeners
    inicializarEventListeners();
    
    // Cargar datos iniciales
    cargarUsuarios();
    
    // Actualizar estadísticas inmediatamente
    actualizarEstadisticas();
    
    // Iniciar actualización automática
    iniciarActualizacionAutomatica();
    
    // Hacer funciones disponibles globalmente
    window.mostrarSeleccionPersona = mostrarSeleccionPersona;
    window.abrirModalSeleccionPersona = abrirModalSeleccionPersona;
    window.filtrarPersonas = filtrarPersonas;
    window.cargarUsuarios = cargarUsuarios;
});

// ==============================
// EXPORTAR FUNCIONES NECESARIAS
// ==============================

// Hacer las funciones principales disponibles globalmente
window.abrirModalNuevoUsuario = abrirModalNuevoUsuario;
window.mostrarSeleccionPersona = mostrarSeleccionPersona;
window.abrirModalSeleccionPersona = abrirModalSeleccionPersona;
window.filtrarPersonas = filtrarPersonas;
window.confirmarSeleccionPersona = confirmarSeleccionPersona;
window.cerrarModalSeleccionPersona = cerrarModalSeleccionPersona;
window.crearUsuario = crearUsuario;
window.verUsuario = verUsuario;
window.editarUsuario = editarUsuario;
window.restablecerPassword = restablecerPassword;
window.confirmarEliminarUsuario = confirmarEliminarUsuario;
window.eliminarUsuario = eliminarUsuario;
window.cargarUsuarios = cargarUsuarios;
window.cerrarModalDetalles = cerrarModalDetalles;
window.cerrarModalEditarUsuario = cerrarModalEditarUsuario;
window.cerrarModalResetPassword = cerrarModalResetPassword;
window.cerrarModalSeleccionPersonaEditar = cerrarModalSeleccionPersonaEditar;
window.confirmarSeleccionPersonaEditar = confirmarSeleccionPersonaEditar;
window.filtrarPersonasEditar = filtrarPersonasEditar;
window.mostrarSeleccionPersonaEditar = mostrarSeleccionPersonaEditar;
window.abrirModalSeleccionPersonaEditar = abrirModalSeleccionPersonaEditar;
window.guardarEditarUsuario = guardarEditarUsuario;
window.confirmarRestablecerPassword = confirmarRestablecerPassword;

console.log('usuarios.js cargado correctamente');