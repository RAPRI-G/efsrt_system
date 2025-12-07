// assets/js/asistencias.js - VERSIÓN COMPLETA

// Sistema de notificaciones
// Sistema de notificaciones - VERSIÓN MEJORADA
class Notificacion {
    static mostrar(titulo, mensaje, tipo = 'info', duracion = 5000) {
        const container = document.getElementById('notificationContainer');
        if (!container) {
            // Crear contenedor si no existe
            const newContainer = document.createElement('div');
            newContainer.id = 'notificationContainer';
            newContainer.className = 'notification-container';
            document.body.appendChild(newContainer);
        }
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${tipo}`;
        
        let icono = 'fa-info-circle';
        let iconClass = 'fas';
        
        switch(tipo) {
            case 'success': 
                icono = 'fa-check-circle';
                break;
            case 'error': 
                icono = 'fa-exclamation-circle';
                break;
            case 'warning': 
                icono = 'fa-exclamation-triangle';
                break;
        }
        
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="${iconClass} ${icono}"></i>
            </div>
            <div class="flex-1">
                <div class="font-medium">${titulo}</div>
                <div class="text-sm opacity-90 mt-1">${mensaje}</div>
            </div>
            <button class="notification-close text-gray-400 hover:text-gray-600 ml-3">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        const actualContainer = document.getElementById('notificationContainer');
        actualContainer.appendChild(notification);
        
        // Mostrar con animación
        setTimeout(() => notification.classList.add('show'), 10);
        
        // Configurar cierre
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => this.cerrar(notification));
        
        // Cierre automático
        if (duracion > 0) {
            setTimeout(() => {
                if (notification.parentNode) this.cerrar(notification);
            }, duracion);
        }
        
        return notification;
    }
    
    static cerrar(notification) {
        notification.classList.remove('show');
        notification.classList.add('hide');
        setTimeout(() => {
            if (notification.parentNode) notification.remove();
        }, 400);
    }
}

// Gestión de estudiantes
class GestorEstudiantes {
    constructor() {
        this.estudiantes = [];
        this.filtros = {
            busqueda: '',
            modulo: 'all',
            estado: 'all'
        };
        this.paginacion = {
            pagina_actual: 1,
            total_paginas: 1,
            total_estudiantes: 0
        };
        this.items_por_pagina = 9;
    }
    
    async cargarEstudiantes() {
        try {
            this.mostrarCarga();
            
            const params = new URLSearchParams({
                c: 'Asistencia',
                a: 'api_estudiantes',
                ...this.filtros
            });
            
            const response = await fetch(`index.php?${params}`);
            const data = await response.json();
            
            if (data.success) {
                this.estudiantes = data.data.estudiantes;
                this.paginacion.total_estudiantes = data.data.total_estudiantes;
                this.actualizarEstadisticas(data.data.estadisticas);
                this.renderizarEstudiantes();
            } else {
                throw new Error(data.error || 'Error al cargar estudiantes');
            }
        } catch (error) {
            console.error('Error:', error);
            Notificacion.mostrar('Error', 'No se pudieron cargar los estudiantes', 'error');
        } finally {
            this.ocultarCarga();
        }
    }
    
    actualizarEstadisticas(estadisticas) {
    if (!estadisticas) return;
    
    // Formatear números
    const formatNumber = (num) => num.toLocaleString('es-ES');
    
    document.getElementById('total-estudiantes').textContent = formatNumber(estadisticas.total_estudiantes || 0);
    document.getElementById('horas-totales').textContent = formatNumber(estadisticas.horas_totales || 0);
    document.getElementById('modulos-completados').textContent = formatNumber(estadisticas.modulos_completados || 0);
    document.getElementById('tasa-cumplimiento').textContent = `${estadisticas.tasa_cumplimiento || 0}%`;
    
    // Textos descriptivos
    document.getElementById('estudiantes-texto').textContent = `${estadisticas.total_estudiantes || 0} estudiantes`;
    document.getElementById('horas-texto').textContent = `${estadisticas.horas_totales || 0} horas registradas`;
    document.getElementById('completas-texto').textContent = `${estadisticas.modulos_completados || 0} de ${estadisticas.total_modulos || 0} módulos`;
    document.getElementById('cumplimiento-texto').textContent = `Tasa de cumplimiento`;
    
    // Si quieres mostrar más detalles, puedes agregar tooltips
    this.agregarTooltipsEstadisticas(estadisticas);
}

agregarTooltipsEstadisticas(estadisticas) {
    // Tooltip para módulos completados
    const modulosElement = document.getElementById('modulos-completados');
    if (modulosElement && estadisticas.total_modulos) {
        modulosElement.title = `${estadisticas.modulos_completados} completados de ${estadisticas.total_modulos} totales`;
    }
    
    // Tooltip para tasa de cumplimiento
    const tasaElement = document.getElementById('tasa-cumplimiento');
    if (tasaElement) {
        tasaElement.title = `Porcentaje de módulos completados`;
    }
}
    
    renderizarEstudiantes() {
        const container = document.getElementById('listaEstudiantes');
        if (!container) return;
        
        // Calcular índices para paginación
        const inicio = (this.paginacion.pagina_actual - 1) * this.items_por_pagina;
        const fin = inicio + this.items_por_pagina;
        const estudiantesPagina = this.estudiantes.slice(inicio, fin);
        
        if (estudiantesPagina.length === 0) {
            container.innerHTML = `
                <div class="col-span-3 text-center py-12 text-gray-500">
                    <i class="fas fa-user-graduate text-4xl mb-4"></i>
                    <p class="text-lg">No se encontraron estudiantes</p>
                    <p class="text-sm mt-2">Intenta ajustar los filtros de búsqueda</p>
                </div>
            `;
            this.actualizarContadores();
            this.actualizarPaginacion();
            return;
        }
        
        container.innerHTML = estudiantesPagina.map(estudiante => this.crearTarjetaEstudiante(estudiante)).join('');
        
        // Agregar event listeners
        document.querySelectorAll('.btn-ver-detalles').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                this.mostrarDetallesEstudiante(id);
            });
        });
        
        this.actualizarContadores();
        this.actualizarPaginacion();
    }
    
    crearTarjetaEstudiante(estudiante) {
        // Calcular estadísticas
        const horasTotales = estudiante.modulos ? 
            estudiante.modulos.reduce((sum, m) => sum + (m.horas_acumuladas || 0), 0) : 0;
        
        // Contar módulos por estado
        let modulosCompletados = 0, modulosEnCurso = 0, modulosPendientes = 0, modulosNoIniciados = 0;
        
        if (estudiante.modulos) {
            estudiante.modulos.forEach(modulo => {
                switch(modulo.estado) {
                    case 'completado': modulosCompletados++; break;
                    case 'en_curso': modulosEnCurso++; break;
                    case 'pendiente': modulosPendientes++; break;
                    case 'no_iniciado': modulosNoIniciados++; break;
                }
            });
        }
        
        // Obtener empresa si existe
        const empresa = estudiante.empresa || null;
        
        return `
            <div class="estudiante-card fade-in">
                <div class="estudiante-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="avatar-estudiante mr-3">
                                ${estudiante.iniciales || '??'}
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">${estudiante.nombre_completo || 'Nombre no disponible'}</h4>
                                <p class="text-blue-200 text-sm">${estudiante.dni_est || ''} • ${estudiante.programa || 'No asignado'}</p>
                            </div>
                        </div>
                        <button class="btn-ver-detalles text-white bg-blue-700 hover:bg-blue-800 px-3 py-1 rounded-lg text-sm" data-id="${estudiante.id}">
                            <i class="fas fa-eye mr-1"></i> Ver Detalles
                        </button>
                    </div>
                    ${empresa && empresa.razon_social ? 
                        `<p class="mt-2 text-blue-200"><i class="fas fa-building mr-1"></i> ${empresa.razon_social}</p>` : 
                        ''}
                </div>
                <div class="estudiante-body">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <span class="text-sm text-gray-600">Horas Totales:</span>
                            <span class="font-bold text-lg ml-2">${horasTotales}h</span>
                        </div>
                        <div class="flex space-x-2">
                            ${modulosCompletados > 0 ? `<span class="modulo-badge badge-completado"><i class="fas fa-check-circle mr-1"></i> ${modulosCompletados}</span>` : ''}
                            ${modulosEnCurso > 0 ? `<span class="modulo-badge badge-en-curso"><i class="fas fa-spinner mr-1"></i> ${modulosEnCurso}</span>` : ''}
                            ${modulosPendientes > 0 ? `<span class="modulo-badge badge-pendiente"><i class="fas fa-clock mr-1"></i> ${modulosPendientes}</span>` : ''}
                        </div>
                    </div>
                    
                    ${estudiante.modulos ? estudiante.modulos.map(modulo => this.crearProgresoModulo(modulo)).join('') : ''}
                </div>
            </div>
        `;
    }
    
    crearProgresoModulo(modulo) {
        const colorProgreso = this.obtenerColorProgreso(modulo.estado);
        const claseBadge = this.obtenerClaseBadgeEstado(modulo.estado);
        const textoEstado = this.obtenerTextoEstado(modulo.estado);
        const horasAcumuladas = modulo.horas_acumuladas || 0;
        const horasRequeridas = modulo.horas_requeridas || 128;
        const porcentaje = modulo.porcentaje || 0;
        
        return `
            <div class="mb-3">
                <div class="flex justify-between items-center mb-1">
                    <div class="text-sm font-medium truncate modulo-nombre">${modulo.nombre || 'Módulo'}</div>
                    <div class="text-xs font-medium">${horasAcumuladas}h / ${horasRequeridas}h</div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill ${colorProgreso}" style="width: ${porcentaje}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span class="badge-estado ${claseBadge}">
                        ${textoEstado}
                    </span>
                    <span>${porcentaje}%</span>
                </div>
            </div>
        `;
    }
    
    obtenerColorProgreso(estado) {
        const colores = {
            'completado': 'progress-completado',
            'en_curso': 'progress-en-curso',
            'pendiente': 'progress-pendiente',
            'no_iniciado': 'progress-no-iniciado'
        };
        return colores[estado] || 'progress-pendiente';
    }
    
    obtenerClaseBadgeEstado(estado) {
        const clases = {
            'completado': 'badge-completado',
            'en_curso': 'badge-en-curso',
            'pendiente': 'badge-pendiente',
            'no_iniciado': 'badge-no-iniciado'
        };
        return clases[estado] || 'badge-pendiente';
    }
    
    obtenerTextoEstado(estado) {
        const textos = {
            'completado': 'Completado',
            'en_curso': 'En curso',
            'pendiente': 'Pendiente',
            'no_iniciado': 'No iniciado'
        };
        return textos[estado] || estado;
    }
    
    async mostrarDetallesEstudiante(id) {
        try {
            const params = new URLSearchParams({
                c: 'Asistencia',
                a: 'api_detalle_estudiante',
                id: id
            });
            
            const response = await fetch(`index.php?${params}`);
            const data = await response.json();
            
            if (data.success) {
                this.mostrarModalDetalles(data.data);
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            console.error('Error:', error);
            Notificacion.mostrar('Error', 'No se pudieron cargar los detalles', 'error');
        }
    }
    
    mostrarModalDetalles(data) {
    const modal = document.getElementById('modalDetallesEstudiante');
    const sidebar = document.getElementById('modulosSidebar');
    const content = document.getElementById('modulosContent');
    const titulo = document.getElementById('modalTitulo');
    
    if (!modal || !sidebar || !content || !titulo) return;
    
    // Actualizar título del modal
    titulo.textContent = `Detalles de Asistencias - ${data.estudiante.nombre_completo}`;
    
    // Limpiar contenido
    sidebar.innerHTML = '';
    content.innerHTML = '<div class="empty-state"><i class="fas fa-folder-open"></i><h4>Selecciona un módulo</h4></div>';
    
    // Crear sidebar con módulos (solo los que tienen práctica)
    if (data.modulos && data.modulos.length > 0) {
        const modulosConPractica = data.modulos.filter(m => m.practica_id);
        
        modulosConPractica.forEach((modulo, index) => {
            const moduloItem = document.createElement('div');
            moduloItem.className = `modulo-item ${index === 0 ? 'active' : ''}`;
            moduloItem.setAttribute('data-practica-id', modulo.practica_id);
            moduloItem.innerHTML = `
                <div class="font-medium mb-1">${modulo.nombre}</div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>${modulo.horas_acumuladas}h / ${modulo.horas_requeridas}h</span>
                    <span class="badge-estado ${this.obtenerClaseBadgeEstado(modulo.estado)}">${this.obtenerTextoEstado(modulo.estado)}</span>
                </div>
                <div class="progress-bar mt-2">
                    <div class="progress-fill ${this.obtenerColorProgreso(modulo.estado)}" style="width: ${modulo.porcentaje > 100 ? 100 : modulo.porcentaje}%"></div>
                </div>
            `;
            
            moduloItem.addEventListener('click', () => {
                // Remover clase active de todos los items
                document.querySelectorAll('.modulo-item').forEach(item => {
                    item.classList.remove('active');
                });
                // Agregar clase active al item clickeado
                moduloItem.classList.add('active');
                // Cargar contenido del módulo
                this.cargarDetallesModulo(modulo.practica_id, data.estudiante);
            });
            
            sidebar.appendChild(moduloItem);
        });
        
        // Cargar el primer módulo por defecto
        if (modulosConPractica.length > 0) {
            this.cargarDetallesModulo(modulosConPractica[0].practica_id, data.estudiante);
        }
    }
    
    // Mostrar modal
    modal.style.display = 'flex';
}
    
    async cargarDetallesModulo(practicaId, estudianteInfo = null) {
    const content = document.getElementById('modulosContent');
    if (!content) return;
    
    try {
        content.innerHTML = `
            <div class="text-center py-12">
                <div class="loading mb-4 mx-auto"></div>
                <p class="text-gray-500">Cargando detalles del módulo...</p>
            </div>
        `;
        
        const params = new URLSearchParams({
            c: 'Asistencia',
            a: 'api_detalle_modulo',
            practica_id: practicaId
        });
        
        const response = await fetch(`index.php?${params}`);
        const data = await response.json();
        
        if (data.success) {
            this.renderizarDetallesModulo(data.data, estudianteInfo);
        } else {
            throw new Error(data.error);
        }
    } catch (error) {
        console.error('Error:', error);
        content.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle"></i>
                <h4 class="text-lg font-medium mb-2">Error al cargar detalles</h4>
                <p class="text-sm">${error.message}</p>
            </div>
        `;
    }
}
    
    renderizarDetallesModulo(data, estudianteInfo = null) {
    const content = document.getElementById('modulosContent');
    if (!content) return;
    
    const { practica, asistencias, horas_acumuladas, porcentaje, estudiante, empresa } = data;
    
    // Usar estudianteInfo si se proporciona (del primer llamado)
    const estudianteData = estudianteInfo || estudiante;
    
    // Formatear fechas
    const fechaInicio = practica.fecha_inicio ? 
        new Date(practica.fecha_inicio).toLocaleDateString('es-ES') : 'No definido';
    
    const fechaFin = practica.fecha_fin ? 
        new Date(practica.fecha_fin).toLocaleDateString('es-ES') : 
        '<span class="text-blue-600">(En curso)</span>';
    
    // Determinar estado para mostrar
    const estadoDisplay = porcentaje >= 100 || practica.estado === 'Finalizado' ? 
        'Completado' : 'En curso';
    
    // Calcular horas restantes
    const horasRestantes = Math.max(0, practica.total_horas - horas_acumuladas);
    
    let html = `
        <div class="modulo-header">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="text-xl font-bold text-primary-blue">${practica.modulo}</h4>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="badge-estado ${porcentaje >= 100 ? 'badge-completado' : 'badge-en-curso'}">
                            ${estadoDisplay}
                        </span>
                        <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">${practica.periodo_academico}</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-primary-blue">${horas_acumuladas}h</div>
                    <div class="text-sm text-gray-600">de ${practica.total_horas}h totales</div>
                </div>
            </div>
        </div>
        
        <div class="resumen-modulo">
            <h5 class="font-semibold text-gray-700 mb-3">Resumen del Módulo</h5>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">${asistencias.length}</div>
                    <div class="stat-label">Asistencias</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${porcentaje}%</div>
                    <div class="stat-label">Progreso</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${horasRestantes}</div>
                    <div class="stat-label">Horas restantes</div>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <p><strong>Estudiante:</strong> ${estudianteData.nombre_completo}</p>
                <p class="mt-1"><strong>Empresa:</strong> ${empresa.razon_social}</p>
                <p class="mt-1"><strong>Supervisor:</strong> ${practica.supervisor_empresa} - ${practica.cargo_supervisor}</p>
                <p class="mt-1"><strong>Área:</strong> ${practica.area_ejecucion}</p>
                <p class="mt-1"><strong>Fecha inicio:</strong> ${fechaInicio}</p>
                <p class="mt-1"><strong>Fecha fin:</strong> ${fechaFin}</p>
            </div>
        </div>
    `;
    
    if (asistencias.length > 0) {
        // Ordenar asistencias por fecha (más recientes primero)
        asistencias.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
        
        html += `
            <div class="px-6 py-4">
                <h5 class="font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-tasks mr-2"></i> Actividades Realizadas (${asistencias.length})
                </h5>
                <div class="actividades-grid">
        `;
        
        asistencias.forEach(asistencia => {
            const fecha = new Date(asistencia.fecha);
            const fechaFormateada = fecha.toLocaleDateString('es-ES', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            html += `
                <div class="actividad-card">
                    <div class="actividad-fecha">
                        <i class="fas fa-calendar-day mr-2"></i> 
                        ${fechaFormateada}
                    </div>
                    <div class="actividad-descripcion">
                        ${asistencia.actividad || 'Sin descripción'}
                    </div>
                    <div class="actividad-horas">
                        <span><i class="fas fa-clock mr-1"></i> ${asistencia.hora_entrada} - ${asistencia.hora_salida}</span>
                        <span class="horas-badge">${asistencia.horas_acumuladas}h</span>
                    </div>
                </div>
            `;
        });
        
        html += `
                </div>
            </div>
        `;
    } else {
        html += `
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h4 class="text-lg font-medium mb-2">No hay asistencias registradas</h4>
                <p class="text-sm">No se han registrado asistencias para este módulo</p>
            </div>
        `;
    }
    
    content.innerHTML = html;
    
    // Aplicar animación de fade-in a las tarjetas
    setTimeout(() => {
        document.querySelectorAll('.actividad-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in');
        });
    }, 100);
}
    
    actualizarContadores() {
        const inicio = (this.paginacion.pagina_actual - 1) * this.items_por_pagina + 1;
        const fin = Math.min(inicio + this.items_por_pagina - 1, this.estudiantes.length);
        
        document.getElementById('estudiantes-mostrados').textContent = `${inicio}-${fin}`;
        document.getElementById('estudiantes-totales').textContent = this.estudiantes.length;
    }
    
    actualizarPaginacion() {
        const paginacion = document.getElementById('paginacion-estudiantes');
        if (!paginacion) return;
        
        const totalPaginas = Math.ceil(this.estudiantes.length / this.items_por_pagina);
        
        if (totalPaginas <= 1) {
            paginacion.innerHTML = '';
            return;
        }
        
        let html = '';
        
        // Botón anterior
        html += `
            <button class="px-3 py-1 rounded-lg border ${this.paginacion.pagina_actual === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}" 
                    onclick="gestorEstudiantes.cambiarPagina(${this.paginacion.pagina_actual - 1})"
                    ${this.paginacion.pagina_actual === 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
        
        // Números de página
        const inicioPagina = Math.max(1, this.paginacion.pagina_actual - 2);
        const finPagina = Math.min(totalPaginas, this.paginacion.pagina_actual + 2);
        
        for (let i = inicioPagina; i <= finPagina; i++) {
            html += `
                <button class="px-3 py-1 rounded-lg border ${i === this.paginacion.pagina_actual ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'}" 
                        onclick="gestorEstudiantes.cambiarPagina(${i})">
                    ${i}
                </button>
            `;
        }
        
        // Botón siguiente
        html += `
            <button class="px-3 py-1 rounded-lg border ${this.paginacion.pagina_actual === totalPaginas ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}" 
                    onclick="gestorEstudiantes.cambiarPagina(${this.paginacion.pagina_actual + 1})"
                    ${this.paginacion.pagina_actual === totalPaginas ? 'disabled' : ''}>
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
        
        paginacion.innerHTML = html;
    }
    
    cambiarPagina(pagina) {
        this.paginacion.pagina_actual = pagina;
        this.renderizarEstudiantes();
    }
    
    aplicarFiltros() {
        this.paginacion.pagina_actual = 1;
        this.filtros.busqueda = document.getElementById('buscarEstudiante').value;
        this.filtros.modulo = document.getElementById('filtroModulo').value;
        this.filtros.estado = document.getElementById('filtroEstado').value;
        this.cargarEstudiantes();
    }
    
    mostrarCarga() {
        const container = document.getElementById('listaEstudiantes');
        if (container) {
            container.innerHTML = `
                <div class="col-span-3 text-center py-12">
                    <div class="loading mb-4 mx-auto"></div>
                    <p class="text-gray-500">Cargando estudiantes...</p>
                </div>
            `;
        }
    }
    
    ocultarCarga() {
        // El renderizado reemplazará el loading
    }
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Crear instancia global
    window.gestorEstudiantes = new GestorEstudiantes();
    
    // Configurar event listeners
    const btnRefrescar = document.getElementById('btnRefrescar');
    const buscarEstudiante = document.getElementById('buscarEstudiante');
    const filtroModulo = document.getElementById('filtroModulo');
    const filtroEstado = document.getElementById('filtroEstado');
    const cerrarModalDetalles = document.getElementById('cerrarModalDetalles');
    const cerrarDetalles = document.getElementById('cerrarDetalles');
    
    // Cargar datos iniciales
    gestorEstudiantes.cargarEstudiantes();
    
    // Event listeners
    if (btnRefrescar) {
        btnRefrescar.addEventListener('click', () => {
            gestorEstudiantes.cargarEstudiantes();
            Notificacion.mostrar('Actualizado', 'Datos actualizados correctamente', 'success', 2000);
        });
    }
    
    if (buscarEstudiante) {
        let timeout;
        buscarEstudiante.addEventListener('input', () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => gestorEstudiantes.aplicarFiltros(), 500);
        });
    }
    
    if (filtroModulo) {
        filtroModulo.addEventListener('change', () => gestorEstudiantes.aplicarFiltros());
    }
    
    if (filtroEstado) {
        filtroEstado.addEventListener('change', () => gestorEstudiantes.aplicarFiltros());
    }
    
    // Cerrar modal de detalles
    if (cerrarModalDetalles) {
        cerrarModalDetalles.addEventListener('click', () => {
            document.getElementById('modalDetallesEstudiante').style.display = 'none';
        });
    }
    
    if (cerrarDetalles) {
        cerrarDetalles.addEventListener('click', () => {
            document.getElementById('modalDetallesEstudiante').style.display = 'none';
        });
    }
    
    // Cerrar modal al hacer clic fuera
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Generar reporte (función básica)
    const btnGenerarReporte = document.getElementById('btnGenerarReporte');
    if (btnGenerarReporte) {
        btnGenerarReporte.addEventListener('click', () => {
            Notificacion.mostrar('Próximamente', 'La generación de reportes estará disponible en la próxima actualización', 'info', 4000);
        });
    }
});