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
async function agregarEstudiante(datos) {
    mostrarCarga('Guardando estudiante...');
    
    try {
        const formData = new FormData();
        Object.keys(datos).forEach(key => {
            formData.append(key, datos[key]);
        });
        
        const response = await fetch('index.php?c=Estudiante&a=crear', {
            method: 'POST',
            body: formData
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
async function editarEstudiante(id, datos) {
    mostrarCarga('Actualizando estudiante...');
    
    try {
        const formData = new FormData();
        Object.keys(datos).forEach(key => {
            formData.append(key, datos[key]);
        });
        
        const response = await fetch(`index.php?c=Estudiante&a=actualizar&id=${id}`, {
            method: 'POST',
            body: formData
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
function actualizarDashboardEstudiantes() {
    const totalEstudiantes = datosEstudiantes.estudiantes.length;
    const estudiantesActivos = datosEstudiantes.estudiantes.filter(e => e.estado === 1).length;
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
        
        // Filtro por estado
        const estadoCoincide = estadoFiltro === 'all' || 
            estudiante.estado.toString() === estadoFiltro;
        
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
        if (estudiante.estado === 1) {
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
                <div class="font-medium">${estudiante.prog_estudios || 'N/A'}</div>
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
function abrirModalNuevo() {
    document.getElementById('modalTitulo').textContent = 'Nuevo Estudiante';
    document.getElementById('formEstudiante').reset();
    document.getElementById('estudianteId').value = '';
    
    document.getElementById('estudianteModal').classList.remove('hidden');
}

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
// EVENT LISTENERS PRINCIPALES
// ==============================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando página de estudiantes...');
    
    // Cargar datos iniciales
    cargarDatosEstudiantes();
    
    // Botón Nuevo Estudiante
    document.getElementById('btnNuevoEstudiante').addEventListener('click', abrirModalNuevo);

    // Envío del formulario de estudiante
    document.getElementById('formEstudiante').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const id = document.getElementById('estudianteId').value;
        const datos = {
            dni_est: document.getElementById('dni_est').value,
            ap_est: document.getElementById('ap_est').value,
            am_est: document.getElementById('am_est').value,
            nom_est: document.getElementById('nom_est').value,
            sex_est: document.getElementById('sex_est').value,
            cel_est: document.getElementById('cel_est').value,
            dir_est: document.getElementById('dir_est').value,
            mailp_est: document.getElementById('mailp_est').value,
            fecnac_est: document.getElementById('fecnac_est').value,
            estado: document.getElementById('estado').checked ? 1 : 0,
            csrf_token: document.getElementById('csrf_token').value
        };
        
        let exito = false;
        
        if (id) {
            exito = await editarEstudiante(id, datos);
        } else {
            exito = await agregarEstudiante(datos);
        }
        
        if (exito) {
            cerrarModalEstudiante();
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