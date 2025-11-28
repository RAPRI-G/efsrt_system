// ==============================
// SISTEMA DE NOTIFICACIONES
// ==============================

function mostrarNotificacion(tipo, titulo, mensaje, duracion = 5000) {
    const container = document.getElementById('notificationContainer');
    const notification = document.createElement('div');
    notification.className = `notification ${tipo}`;
    
    const iconos = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    notification.innerHTML = `
        <i class="notification-icon fas ${iconos[tipo]}"></i>
        <div class="notification-content">
            <div class="notification-title">${titulo}</div>
            <div class="notification-message">${mensaje}</div>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(notification);
    
    // Animación de entrada
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Cerrar notificación
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 500);
    });
    
    // Auto-remover después de la duración
    if (duracion > 0) {
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 500);
            }
        }, duracion);
    }
}

// ==============================
// SISTEMA DE CONFIRMACIÓN
// ==============================

function mostrarConfirmacion(titulo, mensaje, tipo = 'warning') {
    return new Promise((resolve) => {
        const modal = document.getElementById('confirmationModal');
        const title = document.getElementById('confirmationTitle');
        const message = document.getElementById('confirmationMessage');
        const icon = document.getElementById('confirmationIcon');
        const confirmBtn = document.getElementById('confirmAction');
        const cancelBtn = document.getElementById('confirmCancel');
        
        // Configurar según el tipo
        const config = {
            warning: { icon: 'fa-exclamation-triangle', btnClass: '' },
            danger: { icon: 'fa-trash', btnClass: '' },
            success: { icon: 'fa-check', btnClass: 'success' }
        }[tipo] || config.warning;
        
        title.textContent = titulo;
        message.textContent = mensaje;
        icon.className = `confirmation-icon fas ${config.icon}`;
        confirmBtn.className = `btn-confirm ${config.btnClass}`;
        confirmBtn.textContent = tipo === 'success' ? 'Aceptar' : 'Confirmar';
        
        // Mostrar modal
        modal.classList.add('show');
        
        // Event listeners
        const handleConfirm = () => {
            cleanup();
            resolve(true);
        };
        
        const handleCancel = () => {
            cleanup();
            resolve(false);
        };
        
        const handleKeydown = (e) => {
            if (e.key === 'Escape') handleCancel();
            if (e.key === 'Enter') handleConfirm();
        };
        
        const cleanup = () => {
            modal.classList.remove('show');
            confirmBtn.removeEventListener('click', handleConfirm);
            cancelBtn.removeEventListener('click', handleCancel);
            document.removeEventListener('keydown', handleKeydown);
        };
        
        confirmBtn.addEventListener('click', handleConfirm);
        cancelBtn.addEventListener('click', handleCancel);
        document.addEventListener('keydown', handleKeydown);
    });
}

// ==============================
// SISTEMA DE CARGA
// ==============================

function mostrarCarga(mensaje = 'Procesando...') {
    const overlay = document.getElementById('loadingOverlay');
    overlay.classList.add('show');
}

function ocultarCarga() {
    const overlay = document.getElementById('loadingOverlay');
    overlay.classList.remove('show');
}

// ==============================
// DATOS Y CONFIGURACIÓN
// ==============================

const datosEstudiantes = {
    estudiantes: [],
    programas: [],
    matriculas: [],
    practicas: []
};

const configPaginacion = {
    paginaActual: 1,
    elementosPorPagina: 10,
    totalElementos: 0
};

// ==============================
// FUNCIONES PRINCIPALES
// ==============================

// Función para cargar datos desde la base de datos
async function cargarDatosEstudiantes() {
    mostrarCarga('Cargando datos de estudiantes...');
    
    try {
        const response = await fetch('index.php?c=Estudiante&a=apiEstudiantes');
        const result = await response.json();
        
        if (result.success) {
            datosEstudiantes.estudiantes = result.data.estudiantes || [];
            datosEstudiantes.programas = result.data.programas || [];
            
            aplicarFiltrosYRenderizar();
            actualizarDashboardEstudiantes();
            mostrarNotificacion('success', '¡Datos cargados!', 'La información de estudiantes se ha cargado correctamente', 3000);
        } else {
            throw new Error(result.error);
        }
    } catch (error) {
        console.error('Error al cargar datos:', error);
        mostrarNotificacion('error', 'Error', 'No se pudieron cargar los datos de estudiantes');
    } finally {
        ocultarCarga();
    }
}

// Función para agregar nuevo estudiante
async function agregarEstudiante(formData) {
    mostrarCarga('Guardando estudiante...');
    
    try {
        const response = await fetch('index.php?c=Estudiante&a=crear', {
            method: 'POST',
            body: formData // 🔥 Ahora enviamos FormData directamente
        });
        
        const result = await response.json();
        
        if (result.success) {
            await cargarDatosEstudiantes();
            mostrarNotificacion('success', '¡Estudiante agregado!', `El estudiante se ha registrado correctamente`);
            return true;
        } else {
            throw new Error(result.error);
        }
    } catch (error) {
        console.error('Error al agregar estudiante:', error);
        mostrarNotificacion('error', 'Error', 'No se pudo agregar el estudiante');
        return false;
    } finally {
        ocultarCarga();
    }
}

// Función para editar estudiante
async function editarEstudiante(id, formData) {
    mostrarCarga('Actualizando estudiante...');
    
    try {
        const response = await fetch(`index.php?c=Estudiante&a=actualizar&id=${id}`, {
            method: 'POST',
            body: formData // 🔥 Ahora enviamos FormData directamente
        });
        
        const result = await response.json();
        
        if (result.success) {
            await cargarDatosEstudiantes();
            mostrarNotificacion('success', '¡Estudiante actualizado!', `El estudiante se ha actualizado correctamente`);
            return true;
        } else {
            throw new Error(result.error);
        }
    } catch (error) {
        console.error('Error al editar estudiante:', error);
        mostrarNotificacion('error', 'Error', 'No se pudo actualizar el estudiante');
        return false;
    } finally {
        ocultarCarga();
    }
}

// Función para eliminar estudiante
async function eliminarEstudiante(id) {
    const estudiante = datosEstudiantes.estudiantes.find(e => e.id == id);
    if (!estudiante) return false;

    const confirmado = await mostrarConfirmacion(
        'Eliminar Estudiante',
        `¿Estás seguro de que deseas eliminar al estudiante ${estudiante.ap_est} ${estudiante.am_est}, ${estudiante.nom_est}? Esta acción no se puede deshacer.`,
        'danger'
    );

    if (!confirmado) {
        mostrarNotificacion('info', 'Acción cancelada', 'El estudiante no fue eliminado');
        return false;
    }

    mostrarCarga('Eliminando estudiante...');
    
    try {
        const response = await fetch(`index.php?c=Estudiante&a=eliminar&id=${id}`, {
            method: 'POST'
        });
        
        const result = await response.json();
        
        if (result.success) {
            await cargarDatosEstudiantes();
            mostrarNotificacion('success', '¡Estudiante eliminado!', `El estudiante se ha eliminado correctamente`);
            return true;
        } else {
            throw new Error(result.error);
        }
    } catch (error) {
        console.error('Error al eliminar estudiante:', error);
        mostrarNotificacion('error', 'Error', 'No se pudo eliminar el estudiante');
        return false;
    } finally {
        ocultarCarga();
    }
}

// ==============================
// FUNCIONES DE INTERFAZ
// ==============================

// Actualizar estadísticas del dashboard
// Actualizar estadísticas del dashboard
function actualizarDashboardEstudiantes() {
    const totalEstudiantes = datosEstudiantes.estudiantes.length;
    
    // 🔥 CORRECCIÓN: Contar estudiantes activos (incluyendo null como activos)
    const estudiantesActivos = datosEstudiantes.estudiantes.filter(e => 
        e.estado === 1 || e.estado === null
    ).length;
    
    const estudiantesPracticas = datosEstudiantes.estudiantes.filter(e => e.en_practicas > 0).length;
    const totalProgramas = new Set(datosEstudiantes.estudiantes.map(e => e.prog_estudios)).size;

    // Actualizar contadores
    document.getElementById('total-estudiantes').textContent = totalEstudiantes;
    document.getElementById('estudiantes-activos').textContent = estudiantesActivos;
    document.getElementById('estudiantes-practicas').textContent = estudiantesPracticas;
    document.getElementById('total-programas').textContent = totalProgramas;

    // Actualizar textos descriptivos
    document.getElementById('estudiantes-texto').textContent = `${totalEstudiantes} registrados`;
    document.getElementById('activos-texto').textContent = `${estudiantesActivos} activos`;
    document.getElementById('practicas-texto').textContent = `${estudiantesPracticas} en prácticas`;
    document.getElementById('programas-texto').textContent = `${totalProgramas} programas`;

    // Actualizar gráficos
    inicializarGraficosEstudiantes();
}

// Aplicar filtros y renderizar la tabla
function aplicarFiltrosYRenderizar() {
    const textoBusqueda = document.getElementById('buscarEstudiante').value.toLowerCase();
    const programaFiltro = document.getElementById('filtroPrograma').value;
    const estadoFiltro = document.getElementById('filtroEstado').value;
    const generoFiltro = document.getElementById('filtroGenero').value;
    
    // Filtrar estudiantes
    let estudiantesFiltrados = datosEstudiantes.estudiantes.filter(estudiante => {
        // Filtro por texto de búsqueda
        const textoCoincide = textoBusqueda === '' || 
            estudiante.dni_est.includes(textoBusqueda) ||
            estudiante.ap_est.toLowerCase().includes(textoBusqueda) ||
            estudiante.am_est.toLowerCase().includes(textoBusqueda) ||
            estudiante.nom_est.toLowerCase().includes(textoBusqueda);
        
        // Filtro por programa
        const programaCoincide = programaFiltro === 'all' || 
            estudiante.prog_estudios == programaFiltro;
        
        // 🔥 CORRECCIÓN: Filtro por estado (maneja valores null)
        const estadoCoincide = estadoFiltro === 'all' || 
            (estadoFiltro === '1' && (estudiante.estado === 1 || estudiante.estado === null)) ||
            (estadoFiltro === '0' && estudiante.estado === 0);
        
        // Filtro por género
        const generoCoincide = generoFiltro === 'all' || 
            estudiante.sex_est === generoFiltro;
        
        return textoCoincide && programaCoincide && estadoCoincide && generoCoincide;
    });
    
    // Actualizar configuración de paginación
    configPaginacion.totalElementos = estudiantesFiltrados.length;
    configPaginacion.paginaActual = 1;
    
    // Renderizar tabla
    renderizarTablaEstudiantes(estudiantesFiltrados);
    actualizarContadores(estudiantesFiltrados.length);
    actualizarPaginacion();
}

// Renderizar la tabla de estudiantes
function renderizarTablaEstudiantes(estudiantes) {
    const tabla = document.getElementById('tabla-estudiantes-body');
    tabla.innerHTML = '';
    
    if (estudiantes.length === 0) {
        tabla.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    <i class="fas fa-search text-2xl text-gray-300 mb-2"></i>
                    <p class="font-medium">No se encontraron estudiantes</p>
                    <p class="text-sm">Intenta con otros términos de búsqueda</p>
                </td>
            </tr>
        `;
        return;
    }
    
    // Calcular índices para la paginación
    const inicio = (configPaginacion.paginaActual - 1) * configPaginacion.elementosPorPagina;
    const fin = inicio + configPaginacion.elementosPorPagina;
    const estudiantesPagina = estudiantes.slice(inicio, fin);
    
    estudiantesPagina.forEach(estudiante => {
        const fila = document.createElement('tr');
        fila.className = 'hover:bg-gray-50 transition-all duration-300 fade-in';
        
        // Determinar badge de estado
let estadoBadge = '';
// 🔥 CORRECCIÓN: Considerar null como activo
if (estudiante.estado === 1 || estudiante.estado === null) {
    estadoBadge = '<span class="badge-estado badge-activo">Activo</span>';
} else {
    estadoBadge = '<span class="badge-estado badge-inactivo">Inactivo</span>';
}
        
        // Determinar badge de prácticas
        let practicasBadge = '';
        if (estudiante.en_practicas > 0) {
            practicasBadge = '<span class="badge-estado badge-activo">En prácticas</span>';
        } else {
            practicasBadge = '<span class="badge-estado badge-inactivo">Sin prácticas</span>';
        }
        
        // En renderizarTablaEstudiantes - CORREGIR esta parte:
fila.innerHTML = `
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-semibold mr-3 ${estudiante.sex_est == 'F' ? 'avatar-estudiante-femenino' : 'avatar-estudiante-masculino'}">
                ${estudiante.nom_est.charAt(0)}${estudiante.ap_est.charAt(0)}
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">
                    ${estudiante.ap_est} ${estudiante.am_est}, ${estudiante.nom_est}
                </div>
                <div class="text-xs text-gray-500">
                    ${practicasBadge}
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${estudiante.dni_est}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${estudiante.nom_progest || 'No asignado'}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        <div class="font-medium">${estudiante.id_matricula || 'N/A'}</div>
        <div class="text-xs">${estudiante.turno || ''}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        <div>${estudiante.cel_est || 'N/A'}</div>
        <div class="text-xs">${estudiante.mailp_est || ''}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        ${estadoBadge}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <div class="flex space-x-2">
            <button class="btn-accion btn-editar editar-estudiante" data-id="${estudiante.id}" title="Editar">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn-accion btn-ver ver-estudiante" data-id="${estudiante.id}" title="Ver detalles">
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn-accion btn-eliminar eliminar-estudiante" data-id="${estudiante.id}" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
`;
        
        tabla.appendChild(fila);
    });
    
    // Agregar event listeners a los botones de acción
    agregarEventListenersAcciones();
}

// Agregar event listeners a los botones de acción
function agregarEventListenersAcciones() {
    document.querySelectorAll('.editar-estudiante').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            abrirModalEditar(id);
        });
    });
    
    document.querySelectorAll('.ver-estudiante').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            verEstudiante(id);
        });
    });
    
    document.querySelectorAll('.eliminar-estudiante').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.getAttribute('data-id');
            await eliminarEstudiante(id);
        });
    });
}

// Actualizar contadores de estudiantes
function actualizarContadores(totalFiltrados) {
    const inicio = (configPaginacion.paginaActual - 1) * configPaginacion.elementosPorPagina + 1;
    const fin = Math.min(inicio + configPaginacion.elementosPorPagina - 1, totalFiltrados);
    
    document.getElementById('estudiantes-mostrados').textContent = `${inicio}-${fin}`;
    document.getElementById('estudiantes-totales').textContent = totalFiltrados;
    
    document.getElementById('info-paginacion').textContent = 
        `Página ${configPaginacion.paginaActual} de ${Math.ceil(totalFiltrados / configPaginacion.elementosPorPagina)}`;
}

// Actualizar controles de paginación
function actualizarPaginacion() {
    const totalPaginas = Math.ceil(configPaginacion.totalElementos / configPaginacion.elementosPorPagina);
    const paginacion = document.getElementById('paginacion');
    paginacion.innerHTML = '';
    
    if (totalPaginas <= 1) return;
    
    // Botón anterior
    const btnAnterior = document.createElement('button');
    btnAnterior.className = `px-3 py-1 rounded-lg border ${configPaginacion.paginaActual === 1 ? 
        'bg-gray-100 text-gray-400 cursor-not-allowed' : 
        'bg-white text-gray-700 hover:bg-gray-50'}`;
    btnAnterior.innerHTML = '<i class="fas fa-chevron-left"></i>';
    btnAnterior.disabled = configPaginacion.paginaActual === 1;
    btnAnterior.addEventListener('click', function() {
        if (configPaginacion.paginaActual > 1) {
            configPaginacion.paginaActual--;
            aplicarFiltrosYRenderizar();
        }
    });
    paginacion.appendChild(btnAnterior);
    
    // Números de página
    const inicioPagina = Math.max(1, configPaginacion.paginaActual - 2);
    const finPagina = Math.min(totalPaginas, configPaginacion.paginaActual + 2);
    
    for (let i = inicioPagina; i <= finPagina; i++) {
        const btnPagina = document.createElement('button');
        btnPagina.className = `px-3 py-1 rounded-lg border ${i === configPaginacion.paginaActual ? 
            'bg-primary-blue text-white' : 
            'bg-white text-gray-700 hover:bg-gray-50'}`;
        btnPagina.textContent = i;
        btnPagina.addEventListener('click', function() {
            configPaginacion.paginaActual = i;
            aplicarFiltrosYRenderizar();
        });
        paginacion.appendChild(btnPagina);
    }
    
    // Botón siguiente
    const btnSiguiente = document.createElement('button');
    btnSiguiente.className = `px-3 py-1 rounded-lg border ${configPaginacion.paginaActual === totalPaginas ? 
        'bg-gray-100 text-gray-400 cursor-not-allowed' : 
        'bg-white text-gray-700 hover:bg-gray-50'}`;
    btnSiguiente.innerHTML = '<i class="fas fa-chevron-right"></i>';
    btnSiguiente.disabled = configPaginacion.paginaActual === totalPaginas;
    btnSiguiente.addEventListener('click', function() {
        if (configPaginacion.paginaActual < totalPaginas) {
            configPaginacion.paginaActual++;
            aplicarFiltrosYRenderizar();
        }
    });
    paginacion.appendChild(btnSiguiente);
}

// Inicializar gráficos de estudiantes
function inicializarGraficosEstudiantes() {
    // Gráfico de distribución por programa
    const programasCount = {};
    datosEstudiantes.estudiantes.forEach(estudiante => {
        const programa = estudiante.nom_progest || 'No asignado';
        programasCount[programa] = (programasCount[programa] || 0) + 1;
    });
    
    const ctxProgramas = document.getElementById('programasChart');
    if (ctxProgramas) {
        // Destruir gráfico existente si existe
        if (window.programasChartInstance) {
            window.programasChartInstance.destroy();
        }
        
        window.programasChartInstance = new Chart(ctxProgramas, {
            type: 'doughnut',
            data: {
                labels: Object.keys(programasCount),
                datasets: [{
                    data: Object.values(programasCount),
                    backgroundColor: [
                        '#0C1F36',
                        '#0dcaf0',
                        '#198754',
                        '#ffc107',
                        '#6c757d'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
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
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de estado de prácticas
    const practicasCount = {
        'En prácticas': datosEstudiantes.estudiantes.filter(e => e.en_practicas > 0).length,
        'Sin prácticas': datosEstudiantes.estudiantes.filter(e => e.en_practicas === 0).length
    };
    
    const ctxPracticas = document.getElementById('practicasChart');
    if (ctxPracticas) {
        // Destruir gráfico existente si existe
        if (window.practicasChartInstance) {
            window.practicasChartInstance.destroy();
        }
        
        window.practicasChartInstance = new Chart(ctxPracticas, {
            type: 'pie',
            data: {
                labels: Object.keys(practicasCount),
                datasets: [{
                    data: Object.values(practicasCount),
                    backgroundColor: [
                        '#0dcaf0',
                        '#6c757d'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
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
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }
}

// ==============================
// FUNCIONES DE MODALES
// ==============================

// Función para abrir modal de nuevo estudiante
// Función para abrir modal de nuevo estudiante
// Función para abrir modal de nuevo estudiante
function abrirModalNuevo() {
    document.getElementById('modalTitulo').textContent = 'Nuevo Estudiante';
    document.getElementById('formEstudiante').reset();
    document.getElementById('estudianteId').value = '';
    
    // 🔥 CORRECCIÓN: Actualizar el token CSRF
    actualizarTokenCSRF();
    
    // 🔥 NUEVO: Configurar validación de DNI
    setTimeout(() => {
        configurarValidacionDNI();
    }, 100);
    
    // Resetear selects de ubigeo
    ['nac', 'dir'].forEach(tipo => {
        document.getElementById(`departamento_${tipo}`).value = '';
        document.getElementById(`provincia_${tipo}`).innerHTML = '<option value="">Provincia</option>';
        document.getElementById(`provincia_${tipo}`).disabled = true;
        document.getElementById(`distrito_${tipo}`).innerHTML = '<option value="">Distrito</option>';
        document.getElementById(`distrito_${tipo}`).disabled = true;
    });
    
    // Ocultar cualquier advertencia previa
    ocultarAdvertenciaDNI();
    
    document.getElementById('estudianteModal').classList.remove('hidden');
}

// 🔥 NUEVA FUNCIÓN: Actualizar token CSRF
async function actualizarTokenCSRF() {
    try {
        const response = await fetch('index.php?c=Estudiante&a=actualizarCSRF');
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('csrf_token').value = result.token;
            console.log('Token CSRF actualizado:', result.token);
        }
    } catch (error) {
        console.error('Error al actualizar token CSRF:', error);
        // Si falla, intentamos regenerar localmente
        generarTokenCSRFLocal();
    }
}

function generarTokenCSRFLocal() {
    const token = generateRandomToken(32);
    document.getElementById('csrf_token').value = token;
    console.log('Token CSRF generado localmente:', token);
}

function generateRandomToken(length) {
    const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let token = '';
    for (let i = 0; i < length; i++) {
        token += chars[Math.floor(Math.random() * chars.length)];
    }
    return token;
}

// Función para abrir modal de edición
// Función para abrir modal de edición
async function abrirModalEditar(id) {
    mostrarCarga('Cargando datos del estudiante...');
    
    try {
        const response = await fetch(`index.php?c=Estudiante&a=detalle&id=${id}`);
        const result = await response.json();
        
        if (result.success) {
            const estudiante = result.data;
            
            document.getElementById('modalTitulo').textContent = 'Editar Estudiante';
            document.getElementById('estudianteId').value = estudiante.id;
            document.getElementById('dni_est').value = estudiante.dni_est;
            document.getElementById('ap_est').value = estudiante.ap_est;
            document.getElementById('am_est').value = estudiante.am_est || '';
            document.getElementById('nom_est').value = estudiante.nom_est;
            document.getElementById('cel_est').value = estudiante.cel_est || '';
            document.getElementById('dir_est').value = estudiante.dir_est || '';
            document.getElementById('mailp_est').value = estudiante.mailp_est || '';
            document.getElementById('fecnac_est').value = estudiante.fecnac_est || '';
            document.getElementById('estado').checked = estudiante.estado === 1;
            
            // Seleccionar género
            const sexEst = document.getElementById('sex_est');
            if (sexEst && estudiante.sex_est) {
                sexEst.value = estudiante.sex_est;
            }
            
            // Seleccionar programa
            const progEstudios = document.getElementById('prog_estudios');
            if (progEstudios && estudiante.prog_estudios) {
                progEstudios.value = estudiante.prog_estudios;
            }
            
            // Seleccionar turno
            const turno = document.getElementById('turno');
            if (turno && estudiante.turno) {
                turno.value = estudiante.turno;
            }
            
            // 🔥 NUEVO: Configurar validación de DNI excluyendo el ID actual
            setTimeout(() => {
                configurarValidacionDNIEdicion(estudiante.id);
            }, 100);
            
            document.getElementById('estudianteModal').classList.remove('hidden');
        } else {
            throw new Error(result.error);
        }
    } catch (error) {
        mostrarNotificacion('error', 'Error', error.message);
    } finally {
        ocultarCarga();
    }
}

// 🔥 NUEVA FUNCIÓN: Configurar validación de DNI para edición
function configurarValidacionDNIEdicion(estudianteId) {
    const inputDNI = document.getElementById('dni_est');
    let timeout = null;

    inputDNI.addEventListener('input', function() {
        const dni = this.value.trim();
        
        if (timeout) {
            clearTimeout(timeout);
        }
        
        ocultarAdvertenciaDNI();
        
        if (dni.length === 8) {
            if (!validarFormatoDNI(dni)) {
                mostrarAdvertenciaDNI('El DNI debe contener solo 8 dígitos numéricos.');
                return;
            }
            
            timeout = setTimeout(async () => {
                const existe = await verificarDNIExistenteEdicion(dni, estudianteId);
                if (existe) {
                    mostrarAdvertenciaDNI('Este DNI ya está registrado en otro estudiante.');
                }
            }, 500);
        }
    });
}

// Función para verificar si el DNI existe (MEJORADA)
async function verificarDNIExistente(dni, excluirId = null) {
    if (!dni || dni.length !== 8) return false;
    
    try {
        let url = `index.php?c=Estudiante&a=verificarDNI&dni=${dni}`;
        if (excluirId) {
            url += `&excluir_id=${excluirId}`;
        }
        
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success !== undefined) {
            return result.existe;
        } else {
            // Para compatibilidad con la versión anterior
            return result.existe || false;
        }
    } catch (error) {
        console.error('Error al verificar DNI:', error);
        return false;
    }
}

// 🔥 NUEVA FUNCIÓN: Verificar DNI excluyendo el ID actual (más robusta)
async function verificarDNIExistenteEdicion(dni, excluirId) {
    if (!dni || dni.length !== 8) return false;
    
    try {
        const response = await fetch(`index.php?c=Estudiante&a=verificarDNI&dni=${dni}&excluir_id=${excluirId}`);
        
        // Verificar si la respuesta es JSON válido
        const text = await response.text();
        let result;
        
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('Respuesta no es JSON válido:', text);
            return false;
        }
        
        return result.existe || false;
        
    } catch (error) {
        console.error('Error al verificar DNI:', error);
        return false;
    }
}

function cerrarModalEstudiante() {
    document.getElementById('estudianteModal').classList.add('hidden');
}

// Función para ver detalles de estudiante
async function verEstudiante(id) {
    mostrarCarga('Cargando detalles...');
    
    try {
        const response = await fetch(`index.php?c=Estudiante&a=detalle&id=${id}`);
        const result = await response.json();
        
        if (result.success) {
            const estudiante = result.data;
            mostrarDetallesEstudiante(estudiante);
        } else {
            throw new Error(result.error);
        }
    } catch (error) {
        mostrarNotificacion('error', 'Error', error.message);
    } finally {
        ocultarCarga();
    }
}

// Función auxiliar para formatear fechas
function formatearFecha(fecha) {
    if (!fecha) return 'No especificada';
    const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(fecha).toLocaleDateString('es-ES', opciones);
}

function mostrarDetallesEstudiante(estudiante) {
    // Llenar los detalles del estudiante
    document.getElementById('detalleModalTitulo').textContent = `Detalles de ${estudiante.ap_est} ${estudiante.am_est}`;
    
    // Configurar avatar
    const detalleAvatar = document.getElementById('detalleAvatar');
    detalleAvatar.textContent = `${estudiante.nom_est.charAt(0)}${estudiante.ap_est.charAt(0)}`;
    detalleAvatar.className = `h-20 w-20 rounded-full flex items-center justify-center text-white font-bold text-2xl mr-0 md:mr-6 mb-4 md:mb-0 shadow-lg ${estudiante.sex_est == 'F' ? 'avatar-estudiante-femenino' : 'avatar-estudiante-masculino'}`;
    
    // Información principal
    document.getElementById('detalleNombre').textContent = `${estudiante.ap_est} ${estudiante.am_est}, ${estudiante.nom_est}`;
    document.getElementById('detallePrograma').textContent = estudiante.nom_progest || 'No asignado';
    document.getElementById('detalleProgramaNombre').textContent = estudiante.nom_progest || 'No asignado';
    document.getElementById('detalleDni').textContent = estudiante.dni_est;
    document.getElementById('detalleNacimiento').textContent = formatearFecha(estudiante.fecnac_est);
    document.getElementById('detalleCelular').textContent = estudiante.cel_est || 'N/A';
    document.getElementById('detalleEmailPersonal').textContent = estudiante.mailp_est || 'N/A';
    document.getElementById('detalleDireccion').textContent = estudiante.dir_est || 'N/A';
    document.getElementById('detallePeriodo').textContent = estudiante.per_acad || 'N/A';
    document.getElementById('detalleTurno').textContent = estudiante.turno || 'N/A';
    document.getElementById('detalleMatricula').textContent = estudiante.id_matricula || 'N/A';

    // 🔥 NUEVO: Información de ubicación
    document.getElementById('detalleLugarNacimiento').textContent = estudiante.ubigeonac_est || 'No especificado';
    document.getElementById('detalleLugarActual').textContent = estudiante.ubigeodir_est || 'No especificado';
    
    // Estado
    const estadoElement = document.getElementById('detalleEstado');
    if (estudiante.estado === 1) {
        estadoElement.textContent = 'Activo';
        estadoElement.className = 'bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full';
    } else {
        estadoElement.textContent = 'Inactivo';
        estadoElement.className = 'bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full';
    }
    
    // Información de prácticas
    const practicasInfo = document.getElementById('detallePracticasInfo');
    if (estudiante.estado_practica) {
        let estadoClass = '';
        if (estudiante.estado_practica === 'En curso') {
            estadoClass = 'bg-blue-100 text-blue-800';
        } else if (estudiante.estado_practica === 'Finalizado') {
            estadoClass = 'bg-green-100 text-green-800';
        } else {
            estadoClass = 'bg-yellow-100 text-yellow-800';
        }
        
        practicasInfo.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Estado:</span>
                    <span class="text-sm px-3 py-1 rounded-full ${estadoClass}">${estudiante.estado_practica}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Módulo:</span>
                    <span class="text-sm text-gray-600">${estudiante.modulo || 'N/A'}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Empresa:</span>
                    <span class="text-sm text-gray-600">${estudiante.empresa_practica || 'N/A'}</span>
                </div>
            </div>
        `;
    } else {
        practicasInfo.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-briefcase text-gray-400 text-3xl mb-2"></i>
                <p class="text-gray-500">El estudiante no tiene prácticas registradas</p>
            </div>
        `;
    }
    
    // Configurar el botón de editar desde el modal de detalles
    const editarBtn = document.getElementById('editarDesdeDetalle');
    editarBtn.onclick = function() {
        cerrarDetalleModalEstudiante();
        abrirModalEditar(estudiante.id);
    };
    
    document.getElementById('detalleEstudianteModal').classList.remove('hidden');
}

function cerrarDetalleModalEstudiante() {
    document.getElementById('detalleEstudianteModal').classList.add('hidden');
}

// ==============================
// VALIDACIÓN DE DNI EN TIEMPO REAL
// ==============================

// Función para verificar si el DNI existe
async function verificarDNIExistente(dni) {
    if (!dni || dni.length !== 8) return false;
    
    try {
        const response = await fetch(`index.php?c=Estudiante&a=verificarDNI&dni=${dni}`);
        const result = await response.json();
        return result.existe;
    } catch (error) {
        console.error('Error al verificar DNI:', error);
        return false;
    }
}

// Función para mostrar advertencia de DNI existente
function mostrarAdvertenciaDNI(mensaje) {
    let advertencia = document.getElementById('advertenciaDNI');
    
    if (!advertencia) {
        advertencia = document.createElement('div');
        advertencia.id = 'advertenciaDNI';
        advertencia.className = 'mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start';
        
        const inputDNI = document.getElementById('dni_est');
        inputDNI.parentNode.appendChild(advertencia);
    }
    
    advertencia.innerHTML = `
        <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-3"></i>
        <div class="flex-1">
            <p class="text-sm font-medium text-yellow-800">¡Advertencia!</p>
            <p class="text-sm text-yellow-700">${mensaje}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-yellow-500 hover:text-yellow-700">
            <i class="fas fa-times"></i>
        </button>
    `;
}

// Función para ocultar advertencia de DNI
function ocultarAdvertenciaDNI() {
    const advertencia = document.getElementById('advertenciaDNI');
    if (advertencia) {
        advertencia.remove();
    }
}

// Función para validar formato de DNI
function validarFormatoDNI(dni) {
    return /^\d{8}$/.test(dni);
}

// Event listener para validación de DNI en tiempo real
// Event listener para validación de DNI en tiempo real (SOLO ADVERTENCIA)
function configurarValidacionDNI() {
    const inputDNI = document.getElementById('dni_est');
    let timeout = null;

    inputDNI.addEventListener('input', function() {
        const dni = this.value.trim();
        
        // Limpiar timeout anterior
        if (timeout) {
            clearTimeout(timeout);
        }
        
        // Ocultar advertencia anterior
        ocultarAdvertenciaDNI();
        
        // Validar formato
        if (dni.length === 8) {
            if (!/^\d{8}$/.test(dni)) {
                mostrarAdvertenciaDNI('El DNI debe contener solo 8 dígitos numéricos.');
                return;
            }
            
            // Esperar 500ms después de que el usuario deje de escribir
            timeout = setTimeout(async () => {
                const estudianteId = document.getElementById('estudianteId').value;
                const existe = await verificarDNIExistente(dni, estudianteId);
                if (existe) {
                    if (estudianteId) {
                        mostrarAdvertenciaDNI('Este DNI ya está registrado en OTRO estudiante. No podrás guardar los cambios.');
                    } else {
                        mostrarAdvertenciaDNI('Este DNI ya está registrado en el sistema. No podrás guardar el estudiante.');
                    }
                }
            }, 500);
        }
    });
}

// ==============================
// MANEJO DE UBIGEO
// ==============================

// Función para cargar provincias
async function cargarProvincias(departamentoId, tipo) {
    if (!departamentoId) return;
    
    try {
        const response = await fetch(`index.php?c=Estudiante&a=obtenerProvincias&departamento_id=${departamentoId}`);
        const result = await response.json();
        
        if (result.success) {
            const selectProvincia = document.getElementById(`provincia_${tipo}`);
            const selectDistrito = document.getElementById(`distrito_${tipo}`);
            
            // Limpiar y habilitar provincia
            selectProvincia.innerHTML = '<option value="">Provincia</option>';
            selectProvincia.disabled = false;
            
            // Limpiar y deshabilitar distrito
            selectDistrito.innerHTML = '<option value="">Distrito</option>';
            selectDistrito.disabled = true;
            
            // Llenar provincias
            result.data.forEach(provincia => {
                const option = document.createElement('option');
                option.value = provincia.id;
                option.textContent = provincia.provincia;
                selectProvincia.appendChild(option);
            });
            
            // 🔥 CORRECCIÓN: Limpiar hidden correctamente
            document.getElementById(`ubigeo${tipo}_est`).value = '';
        }
    } catch (error) {
        console.error('Error al cargar provincias:', error);
        mostrarNotificacion('error', 'Error', 'No se pudieron cargar las provincias');
    }
}

/// Función para cargar distritos
async function cargarDistritos(provinciaId, tipo) {
    if (!provinciaId) return;
    
    try {
        const response = await fetch(`index.php?c=Estudiante&a=obtenerDistritos&provincia_id=${provinciaId}`);
        const result = await response.json();
        
        if (result.success) {
            const selectDistrito = document.getElementById(`distrito_${tipo}`);
            
            // Limpiar y habilitar distrito
            selectDistrito.innerHTML = '<option value="">Distrito</option>';
            selectDistrito.disabled = false;
            
            // Llenar distritos
            result.data.forEach(distrito => {
                const option = new Option(distrito.distrito, distrito.id);
                selectDistrito.add(option);
            });
            
            // 🔥 CORRECCIÓN: Limpiar hidden
            document.getElementById(`ubigeo${tipo}_est`).value = '';
        }
    } catch (error) {
        console.error('Error al cargar distritos:', error);
        mostrarNotificacion('error', 'Error', 'No se pudieron cargar los distritos');
    }
}

// 🔥 CORRECCIÓN: Función mejorada para actualizar el ubigeo hidden
function actualizarUbigeoHidden(tipo) {
    const distritoId = document.getElementById(`distrito_${tipo}`).value;
    console.log(`Ubigeo ${tipo} seleccionado:`, distritoId);
}

// Función para cargar ubigeo en edición
async function cargarUbigeoEnEdicion(estudianteId) {
    // Esta función necesitaría obtener los datos del estudiante y cargar los selects
    // Se implementaría cuando cargues los datos para edición
}

// ==============================
// EVENT LISTENERS PRINCIPALES
// ==============================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando página de estudiantes...');
    
    // Cargar datos iniciales
    cargarDatosEstudiantes();
    
    // Botón Nuevo Estudiante
    document.getElementById('btnNuevoEstudiante').addEventListener('click', abrirModalNuevo);

    // Event listeners para ubigeo - Nacimiento
document.getElementById('departamento_nac').addEventListener('change', function() {
    const departamentoId = this.value;
    cargarProvincias(departamentoId, 'nac');
});

document.getElementById('provincia_nac').addEventListener('change', function() {
    const provinciaId = this.value;
    cargarDistritos(provinciaId, 'nac');
});

document.getElementById('distrito_nac').addEventListener('change', function() {
    actualizarUbigeoHidden('nac');
});

// Event listeners para ubigeo - Dirección
document.getElementById('departamento_dir').addEventListener('change', function() {
    const departamentoId = this.value;
    cargarProvincias(departamentoId, 'dir');
});

document.getElementById('provincia_dir').addEventListener('change', function() {
    const provinciaId = this.value;
    cargarDistritos(provinciaId, 'dir');
});

document.getElementById('distrito_dir').addEventListener('change', function() {
    actualizarUbigeoHidden('dir');
});

// Envío del formulario de estudiante - VERSIÓN CON VALIDACIÓN DE DNI
document.getElementById('formEstudiante').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('estudianteId').value;
    const dni = document.getElementById('dni_est').value.trim();

    // 🔥 VALIDACIÓN CRÍTICA: Verificar formato de DNI
    if (!/^\d{8}$/.test(dni)) {
        mostrarNotificacion('error', 'Error', 'El DNI debe tener exactamente 8 dígitos numéricos.');
        return;
    }

    // 🔥 VALIDACIÓN CRÍTICA: Verificar si el DNI existe
    mostrarCarga('Verificando DNI...');
    try {
        const existe = await verificarDNIExistente(dni, id);
        
        if (existe && !id) {
            // Si es nuevo estudiante y el DNI existe
            ocultarCarga();
            mostrarNotificacion('error', 'DNI Duplicado', 'Este DNI ya está registrado en el sistema. No se puede guardar.');
            return;
        } else if (existe && id) {
            // Si está editando y el DNI existe en OTRO estudiante
            ocultarCarga();
            mostrarNotificacion('error', 'DNI Duplicado', 'Este DNI ya está registrado en otro estudiante. No se puede guardar.');
            return;
        }

        // 🔥 Si pasa todas las validaciones, proceder con el guardado
        const formData = new FormData(this);
        
        // 🔥 CORRECCIÓN: Obtener los NOMBRES completos en lugar de IDs
        const departamentoNac = document.getElementById('departamento_nac');
        const provinciaNac = document.getElementById('provincia_nac');
        const distritoNac = document.getElementById('distrito_nac');
        
        const departamentoDir = document.getElementById('departamento_dir');
        const provinciaDir = document.getElementById('provincia_dir');
        const distritoDir = document.getElementById('distrito_dir');

        // Obtener nombres completos para lugar de nacimiento
        if (departamentoNac.value && provinciaNac.value && distritoNac.value) {
            const lugarNacimiento = `${distritoNac.options[distritoNac.selectedIndex].text}, ${provinciaNac.options[provinciaNac.selectedIndex].text}, ${departamentoNac.options[departamentoNac.selectedIndex].text}`;
            formData.set('ubigeonac_est', lugarNacimiento);
        }

        // Obtener nombres completos para lugar actual
        if (departamentoDir.value && provinciaDir.value && distritoDir.value) {
            const lugarActual = `${distritoDir.options[distritoDir.selectedIndex].text}, ${provinciaDir.options[provinciaDir.selectedIndex].text}, ${departamentoDir.options[departamentoDir.selectedIndex].text}`;
            formData.set('ubigeodir_est', lugarActual);
        }

        let exito = false;
        
        if (id) {
            exito = await editarEstudiante(id, formData);
        } else {
            exito = await agregarEstudiante(formData);
        }
        
        if (exito) {
            cerrarModalEstudiante();
        }

    } catch (error) {
        console.error('Error en validación:', error);
        mostrarNotificacion('error', 'Error', 'Ocurrió un error al verificar el DNI.');
    } finally {
        ocultarCarga();
    }
});

    // Botón Refrescar
    document.getElementById('btnRefrescar').addEventListener('click', function() {
        cargarDatosEstudiantes();
    });

    // Botón Exportar
    document.getElementById('btnExportar').addEventListener('click', function() {
        mostrarCarga('Generando archivo de exportación...');
        
        setTimeout(() => {
            ocultarCarga();
            mostrarNotificacion('success', '¡Exportación completada!', 'El archivo Excel se ha generado correctamente');
        }, 2000);
    });

    // Event listeners para cerrar modales
    document.getElementById('cerrarModal').addEventListener('click', cerrarModalEstudiante);
    document.getElementById('cancelarForm').addEventListener('click', cerrarModalEstudiante);
    document.getElementById('cerrarDetalleModal').addEventListener('click', cerrarDetalleModalEstudiante);
    document.getElementById('cerrarDetalleBtn').addEventListener('click', cerrarDetalleModalEstudiante);

    // Cerrar modal al hacer clic fuera
    document.getElementById('estudianteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalEstudiante();
        }
    });

    document.getElementById('detalleEstudianteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarDetalleModalEstudiante();
        }
    });

    // Event listeners para filtros
    document.getElementById('filtroPrograma').addEventListener('change', aplicarFiltrosYRenderizar);
    document.getElementById('filtroEstado').addEventListener('change', aplicarFiltrosYRenderizar);
    document.getElementById('filtroGenero').addEventListener('change', aplicarFiltrosYRenderizar);
    
    // Event listener para búsqueda
    document.getElementById('buscarEstudiante').addEventListener('input', function() {
        aplicarFiltrosYRenderizar();
    });
});

// Hacer funciones disponibles globalmente para debugging
window.abrirModalNuevo = abrirModalNuevo;
window.cerrarModalEstudiante = cerrarModalEstudiante;
window.cargarDatosEstudiantes = cargarDatosEstudiantes;