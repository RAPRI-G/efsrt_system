// assets/js/practicas.js
class PracticasApp {
    constructor() {
        this.practicas = [];
        this.estudiantes = [];
        this.empresas = [];
        this.empleados = [];
        this.configPaginacion = {
            paginaActual: 1,
            elementosPorPagina: 10,
            totalElementos: 0
        };
        this.practicaAEliminar = null;
        
        this.init();
    }
    
    init() {
        this.cargarDatosPracticas();
        this.setupEventListeners();
        this.setupModalListeners();
    }
    
    setupEventListeners() {
    const userInfo = window.userInfo || {};
    const esDocente = userInfo.esDocente || false;
    
    // Botón Nueva Práctica - visible para admin y docente
    document.getElementById('btnNuevaPractica')?.addEventListener('click', () => this.abrirModalNuevaPractica());
    
    // Botón Refrescar
    document.getElementById('btnRefrescar')?.addEventListener('click', () => this.cargarDatosPracticas());
    
    // Filtros
    document.getElementById('filtroEstado')?.addEventListener('change', () => this.filtrarPracticas());
    document.getElementById('filtroModulo')?.addEventListener('change', () => this.filtrarPracticas());
    document.getElementById('buscarPractica')?.addEventListener('input', () => this.filtrarPracticas());
    
    // Botones de guardar
    document.getElementById('guardarNuevaPractica')?.addEventListener('click', (e) => this.guardarNuevaPractica(e));
    
    // 🔥 Botón de editar SOLO si es administrador
    if (!esDocente) {
        document.getElementById('guardarEditarPractica')?.addEventListener('click', (e) => this.guardarEditarPractica(e));
    } else {
        // Ocultar botón de editar si existe
        const btnEditar = document.getElementById('guardarEditarPractica');
        if (btnEditar) {
            btnEditar.style.display = 'none';
        }
    }
    
    // Botones de eliminar
    document.getElementById('cancelarEliminar')?.addEventListener('click', () => this.cerrarModal('modalConfirmarEliminar'));
    document.getElementById('confirmarEliminar')?.addEventListener('click', () => this.eliminarPractica());
}
    
    setupModalListeners() {
        // Cerrar modales al hacer clic en el botón cerrar
        document.querySelectorAll('.cerrar-modal').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modalId = e.currentTarget.getAttribute('data-modal');
                this.cerrarModal(modalId);
            });
        });
        
        // Cerrar modales al hacer clic fuera
        document.querySelectorAll('.fixed.inset-0').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    }
    
    async cargarDatosPracticas() {
        try {
            this.mostrarCarga('Cargando datos de prácticas...');
            
            // Cargar prácticas con estadísticas
            const response = await fetch('index.php?c=Practica&a=api_practicas');
            const data = await response.json();
            
            if (data.success) {
                this.practicas = data.data || [];
                
                // Cargar estudiantes
                const estudiantesResponse = await fetch('index.php?c=Practica&a=api_estudiantes');
                const estudiantesData = await estudiantesResponse.json();
                this.estudiantes = estudiantesData.success ? estudiantesData.data : [];
                
                // Cargar empresas
                const empresasResponse = await fetch('index.php?c=Practica&a=api_empresas');
                const empresasData = await empresasResponse.json();
                this.empresas = empresasData.success ? empresasData.data : [];
                
                // Cargar empleados (docentes)
                const empleadosResponse = await fetch('index.php?c=Practica&a=api_empleados');
                const empleadosData = await empleadosResponse.json();
                this.empleados = empleadosData.success ? empleadosData.data : [];
                
                // Actualizar dashboard
                this.actualizarDashboard(data.estadisticas);
                
                // Renderizar tabla
                this.renderizarTablaPracticas();
                
                // Inicializar gráficos
                this.inicializarGraficosPracticas(data.estadisticas);
                
                this.ocultarCarga();
                // NO mostrar notificación de éxito al cargar datos
                
            } else {
                throw new Error(data.error || 'Error al cargar datos');
            }
        } catch (error) {
            console.error('Error al cargar datos:', error);
            this.mostrarNotificacion('Error', 'Error al cargar los datos de prácticas', 'error');
            this.ocultarCarga();
        }
    }
    
     actualizarDashboard(estadisticas, user_info = null) {
    if (!estadisticas) return;
    
    // Actualizar contadores
    document.getElementById('total-practicas').textContent = estadisticas.total_practicas || 0;
    document.getElementById('practicas-activas').textContent = estadisticas.practicas_en_curso || 0;
    document.getElementById('practicas-finalizadas').textContent = estadisticas.practicas_finalizadas || 0;
    document.getElementById('horas-acumuladas').textContent = estadisticas.horas_acumuladas || 0;
    
    // Actualizar textos descriptivos
    document.getElementById('practicas-texto').textContent = `${estadisticas.total_practicas || 0} registradas`;
    document.getElementById('activas-texto').textContent = `${estadisticas.practicas_en_curso || 0} en ejecución`;
    document.getElementById('finalizadas-texto').textContent = `${estadisticas.practicas_finalizadas || 0} completadas`;
    document.getElementById('horas-texto').textContent = `Horas acumuladas`;
    
    // 🔥 Si es docente, mostrar badge informativo (opcional)
    const userInfo = window.userInfo || user_info;
    if (userInfo && userInfo.esDocente) {
        console.log(`👨‍🏫 Docente ${userInfo.nombre} está viendo sus prácticas`);
        
        // Agregar badge al header si quieres
        const existingBadge = document.querySelector('.badge-docente');
        if (!existingBadge) {
            const badge = document.createElement('div');
            badge.className = 'badge-docente bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm inline-flex items-center ml-4';
            badge.innerHTML = `<i class="fas fa-user-tie mr-1"></i> Vista docente`;
            
            const headerDiv = document.querySelector('.mb-8 > div:first-child');
            if (headerDiv) {
                headerDiv.appendChild(badge);
            }
        }
    }
}
    
    inicializarGraficosPracticas(estadisticas) {
        if (!estadisticas) return;
        
        // Gráfico de distribución por estado
        const estadoData = estadisticas.distribucion_estado || {
            'En curso': estadisticas.practicas_en_curso || 0,
            'Finalizado': estadisticas.practicas_finalizadas || 0,
            'Pendiente': estadisticas.practicas_pendientes || 0
        };
        
        const ctxEstado = document.getElementById('estadoPracticaChart');
        if (ctxEstado) {
            if (window.estadoPracticaChartInstance) {
                window.estadoPracticaChartInstance.destroy();
            }
            
            window.estadoPracticaChartInstance = new Chart(ctxEstado, {
                type: 'doughnut',
                data: {
                    labels: ['En curso', 'Finalizado', 'Pendiente'],
                    datasets: [{
                        data: [
                            estadoData['En curso'] || 0,
                            estadoData['Finalizado'] || 0,
                            estadoData['Pendiente'] || 0
                        ],
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
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
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });
        }
        
        // Gráfico de distribución por módulo
        const moduloData = estadisticas.distribucion_modulos || {};
        const ctxModulo = document.getElementById('tipoModuloChart');
        if (ctxModulo) {
            if (window.tipoModuloChartInstance) {
                window.tipoModuloChartInstance.destroy();
            }
            
            window.tipoModuloChartInstance = new Chart(ctxModulo, {
                type: 'pie',
                data: {
                    labels: ['Módulo 1', 'Módulo 2', 'Módulo 3'],
                    datasets: [{
                        data: [
                            moduloData['Módulo 1'] || 0,
                            moduloData['Módulo 2'] || 0,
                            moduloData['Módulo 3'] || 0
                        ],
                        backgroundColor: ['#0ea5e9', '#10b981', '#f59e0b'],
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
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });
        }
    }
    
    renderizarTablaPracticas() {
    const tbody = document.getElementById('tablaPracticasBody');
    if (!tbody) return;
    
    // Obtener información del usuario
    const userInfo = window.userInfo || {};
    const esDocente = userInfo.esDocente || false;
    const esAdministrador = userInfo.esAdministrador || false;
    
    console.log("👤 Permisos - Es docente:", esDocente, "Es admin:", esAdministrador);
    
    // Aplicar filtros
    let practicasFiltradas = this.filtrarPracticasLista(this.practicas);
    
    const inicio = (this.configPaginacion.paginaActual - 1) * this.configPaginacion.elementosPorPagina;
    const fin = inicio + this.configPaginacion.elementosPorPagina;
    const practicasPagina = practicasFiltradas.slice(inicio, fin);
    
    if (practicasPagina.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-500">
                    <i class="fas fa-briefcase text-2xl mb-2"></i>
                    <p>No se encontraron prácticas</p>
                </td>
            </tr>
        `;
        this.actualizarContadoresPracticas(practicasFiltradas.length);
        return;
    }
    
    tbody.innerHTML = '';
    
    practicasPagina.forEach(practica => {
        const estudiante = this.estudiantes.find(e => e.id == practica.estudiante);
        const empresa = this.empresas.find(e => e.id == practica.empresa);
        const empleado = this.empleados.find(e => e.id == practica.empleado);
        
        let estadoClase = '';
        let estadoTexto = practica.estado || 'Pendiente';
        
        if (estadoTexto === 'Finalizado') {
            estadoClase = 'badge-finalizado';
        } else if (estadoTexto === 'En curso') {
            estadoClase = 'badge-en-curso';
        } else {
            estadoClase = 'badge-pendiente';
        }
        
        let moduloClase = '';
        let moduloTexto = practica.tipo_efsrt === 'modulo1' ? 'Módulo 1' : 
                        practica.tipo_efsrt === 'modulo2' ? 'Módulo 2' : 'Módulo 3';
        
        if (practica.tipo_efsrt === 'modulo1') {
            moduloClase = 'badge-modulo1';
        } else if (practica.tipo_efsrt === 'modulo2') {
            moduloClase = 'badge-modulo2';
        } else {
            moduloClase = 'badge-modulo3';
        }
        
        const fechaInicio = practica.fecha_inicio ? new Date(practica.fecha_inicio).toLocaleDateString('es-ES') : 'No definida';
        const horasAcumuladas = practica.horas_acumuladas || 0;
        const totalHoras = practica.total_horas || 0;
        const porcentajeCompletado = totalHoras > 0 ? Math.round((horasAcumuladas / totalHoras) * 100) : 0;
        
        // 🔥 Determinar qué botones mostrar según el rol
        let botonesAccion = '';
        
        if (esAdministrador) {
            // Administrador ve todos los botones
            botonesAccion = `
                <button class="btn-accion btn-ver" data-id="${practica.id}" title="Ver detalles">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn-accion btn-editar" data-id="${practica.id}" title="Editar práctica">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-accion btn-eliminar" data-id="${practica.id}" title="Eliminar práctica">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        } else if (esDocente) {
            // 🔥 Docente solo ve el botón VER
            botonesAccion = `
                <button class="btn-accion btn-ver" data-id="${practica.id}" title="Ver detalles">
                    <i class="fas fa-eye"></i>
                </button>
                <!-- Botones editar y eliminar OCULTOS para docente -->
            `;
        } else {
            // Para otros roles o sin sesión, solo ver
            botonesAccion = `
                <button class="btn-accion btn-ver" data-id="${practica.id}" title="Ver detalles">
                    <i class="fas fa-eye"></i>
                </button>
            `;
        }
        
        const fila = document.createElement('tr');
        fila.className = 'fade-in';
        fila.innerHTML = `
            <td>
                <div class="flex items-center">
                    <div class="avatar-estudiante mr-3">
                        ${estudiante ? (estudiante.iniciales || 
                          (estudiante.ap_est ? estudiante.ap_est.charAt(0) : '') + 
                          (estudiante.am_est ? estudiante.am_est.charAt(0) : '')) : 'ND'}
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">
                            ${estudiante ? (estudiante.nombre_completo || 
                              `${estudiante.nom_est || ''} ${estudiante.ap_est || ''}`) : 'No encontrado'}
                        </div>
                        <div class="text-xs text-gray-500">${estudiante ? estudiante.dni_est : ''}</div>
                    </div>
                </div>
            </td>
            <td class="font-medium">${empresa ? empresa.razon_social : 'No encontrada'}</td>
            <td>
                <span class="badge-estado ${moduloClase}">
                    ${moduloTexto}
                </span>
            </td>
            <td>
                <span class="badge-estado ${estadoClase}">${estadoTexto}</span>
            </td>
            <td>${fechaInicio}</td>
            <td>
                <div class="flex items-center">
                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: ${porcentajeCompletado}%"></div>
                    </div>
                    <span class="text-sm">${horasAcumuladas}/${totalHoras}</span>
                </div>
            </td>
            <td class="text-sm">${empleado ? empleado.apnom_emp : 'No asignado'}</td>
            <td>
                <div class="acciones">
                    ${botonesAccion}
                </div>
            </td>
        `;
        
        tbody.appendChild(fila);
    });
    
    // Agregar event listeners SOLO a los botones visibles
    this.setupAccionesButtons();
    
    this.actualizarContadoresPracticas(practicasFiltradas.length);
    this.actualizarPaginacionPracticas(practicasFiltradas.length);
}
    
    setupAccionesButtons() {
        // Ver
        document.querySelectorAll('.btn-ver').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                this.verPractica(id);
            });
        });
        
        // Editar
        document.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                this.editarPractica(id);
            });
        });
        
        // Eliminar
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                this.confirmarEliminarPractica(id);
            });
        });
    }

    async cargarModulosEstudiante(estudianteId, selectId) {
    try {
        if (!estudianteId) return;
        
        const response = await fetch(`index.php?c=Practica&a=api_modulos_estudiante&estudiante_id=${estudianteId}`);
        const data = await response.json();
        
        if (data.success) {
            this.actualizarSelectModulos(data.modulos, selectId);
        }
    } catch (error) {
        console.error('Error al cargar módulos del estudiante:', error);
    }
}

actualizarSelectModulos(modulosEstudiante, selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;
    
    // Guardar valor seleccionado actual
    const currentValue = select.value;
    
    // Crear opciones de módulo
    const modulosDisponibles = [
        { value: 'modulo1', text: 'Módulo 1', disponible: true },
        { value: 'modulo2', text: 'Módulo 2', disponible: true },
        { value: 'modulo3', text: 'Módulo 3', disponible: true }
    ];
    
    // Marcar módulos ya registrados como no disponibles
    modulosEstudiante.forEach(modulo => {
        const index = modulosDisponibles.findIndex(m => m.value === modulo.tipo_efsrt);
        if (index !== -1) {
            modulosDisponibles[index].disponible = false;
            modulosDisponibles[index].estado = modulo.estado;
        }
    });
    
    // Actualizar select
    select.innerHTML = '<option value="">Seleccionar módulo</option>';
    
    modulosDisponibles.forEach(modulo => {
        const option = document.createElement('option');
        option.value = modulo.value;
        
        if (!modulo.disponible) {
            option.textContent = `${modulo.text} (${modulo.estado})`;
            option.disabled = true;
            option.style.color = '#999';
            option.style.fontStyle = 'italic';
        } else {
            option.textContent = modulo.text;
        }
        
        option.selected = (modulo.value === currentValue);
        select.appendChild(option);
    });
    
    // Si hay módulos no disponibles, mostrar tooltip o mensaje
    const modulosNoDisponibles = modulosDisponibles.filter(m => !m.disponible);
    if (modulosNoDisponibles.length > 0) {
        this.mostrarInfoModulos(modulosNoDisponibles);
    }
}

mostrarInfoModulos(modulosNoDisponibles) {
    // Puedes mostrar un mensaje informativo
    const mensaje = `Módulos ya registrados: ${modulosNoDisponibles.map(m => m.text).join(', ')}`;
    console.log(mensaje);
    // O mostrar un toast/notificación
    // this.mostrarNotificacion('Información', mensaje, 'info', 3000);
}
    
    filtrarPracticasLista(practicas) {
        const filtroEstado = document.getElementById('filtroEstado')?.value || 'all';
        const filtroModulo = document.getElementById('filtroModulo')?.value || 'all';
        const busqueda = document.getElementById('buscarPractica')?.value.toLowerCase() || '';
        
        let practicasFiltradas = practicas;
        
        // Filtrar por estado
        if (filtroEstado !== 'all') {
            practicasFiltradas = practicasFiltradas.filter(p => p.estado === filtroEstado);
        }
        
        // Filtrar por módulo
        if (filtroModulo !== 'all') {
            practicasFiltradas = practicasFiltradas.filter(p => p.tipo_efsrt === filtroModulo);
        }
        
        // Filtrar por búsqueda
        if (busqueda) {
            practicasFiltradas = practicasFiltradas.filter(p => {
                const estudiante = this.estudiantes.find(e => e.id == p.estudiante);
                const empresa = this.empresas.find(e => e.id == p.empresa);
                const empleado = this.empleados.find(e => e.id == p.empleado);
                
                return (
                    (estudiante && estudiante.nombre_completo?.toLowerCase().includes(busqueda)) ||
                    (estudiante && estudiante.dni_est?.includes(busqueda)) ||
                    (empresa && empresa.razon_social?.toLowerCase().includes(busqueda)) ||
                    (empleado && empleado.apnom_emp?.toLowerCase().includes(busqueda))
                );
            });
        }
        
        return practicasFiltradas;
    }
    
    filtrarPracticas() {
        this.configPaginacion.paginaActual = 1;
        this.renderizarTablaPracticas();
    }
    
    actualizarContadoresPracticas(totalFiltrados) {
        const inicio = (this.configPaginacion.paginaActual - 1) * this.configPaginacion.elementosPorPagina + 1;
        const fin = Math.min(inicio + this.configPaginacion.elementosPorPagina - 1, totalFiltrados);
        
        const mostradasEl = document.getElementById('practicas-mostradas');
        const totalesEl = document.getElementById('practicas-totales');
        
        if (mostradasEl) mostradasEl.textContent = `${inicio}-${fin}`;
        if (totalesEl) totalesEl.textContent = totalFiltrados;
    }
    
    actualizarPaginacionPracticas(totalFiltrados) {
        const totalPaginas = Math.ceil(totalFiltrados / this.configPaginacion.elementosPorPagina);
        const paginacion = document.getElementById('paginacion-practicas');
        if (!paginacion) return;
        
        paginacion.innerHTML = '';
        
        if (totalPaginas <= 1) return;
        
        // Botón anterior
        const btnAnterior = document.createElement('button');
        btnAnterior.className = `px-3 py-1 rounded-lg border ${this.configPaginacion.paginaActual === 1 ? 
            'bg-gray-100 text-gray-400 cursor-not-allowed' : 
            'bg-white text-gray-700 hover:bg-gray-50'}`;
        btnAnterior.innerHTML = '<i class="fas fa-chevron-left"></i>';
        btnAnterior.disabled = this.configPaginacion.paginaActual === 1;
        btnAnterior.addEventListener('click', () => {
            if (this.configPaginacion.paginaActual > 1) {
                this.configPaginacion.paginaActual--;
                this.renderizarTablaPracticas();
            }
        });
        paginacion.appendChild(btnAnterior);
        
        // Números de página
        const inicioPagina = Math.max(1, this.configPaginacion.paginaActual - 2);
        const finPagina = Math.min(totalPaginas, this.configPaginacion.paginaActual + 2);
        
        for (let i = inicioPagina; i <= finPagina; i++) {
            const btnPagina = document.createElement('button');
            btnPagina.className = `px-3 py-1 rounded-lg border ${i === this.configPaginacion.paginaActual ? 
                'bg-blue-600 text-white' : 
                'bg-white text-gray-700 hover:bg-gray-50'}`;
            btnPagina.textContent = i;
            btnPagina.addEventListener('click', () => {
                this.configPaginacion.paginaActual = i;
                this.renderizarTablaPracticas();
            });
            paginacion.appendChild(btnPagina);
        }
        
        // Botón siguiente
        const btnSiguiente = document.createElement('button');
        btnSiguiente.className = `px-3 py-1 rounded-lg border ${this.configPaginacion.paginaActual === totalPaginas ? 
            'bg-gray-100 text-gray-400 cursor-not-allowed' : 
            'bg-white text-gray-700 hover:bg-gray-50'}`;
        btnSiguiente.innerHTML = '<i class="fas fa-chevron-right"></i>';
        btnSiguiente.disabled = this.configPaginacion.paginaActual === totalPaginas;
        btnSiguiente.addEventListener('click', () => {
            if (this.configPaginacion.paginaActual < totalPaginas) {
                this.configPaginacion.paginaActual++;
                this.renderizarTablaPracticas();
            }
        });
        paginacion.appendChild(btnSiguiente);
    }
    
    async verPractica(id) {
        try {
            const response = await fetch(`index.php?c=Practica&a=api_practica&id=${id}`);
            const data = await response.json();
            
            if (data.success) {
                this.mostrarDetallesPractica(data.data);
            } else {
                this.mostrarNotificacion('Error', data.error || 'No se pudo cargar la práctica', 'error');
            }
        } catch (error) {
            console.error('Error al ver práctica:', error);
            this.mostrarNotificacion('Error', 'Error al cargar los detalles', 'error');
        }
    }
    
    async editarPractica(id) {
    const userInfo = window.userInfo || {};
    
    // 🔥 Si es docente, mostrar mensaje de no permitido
    if (userInfo.esDocente) {
        this.mostrarNotificacion(
            'Permiso denegado', 
            'No tiene permisos para editar prácticas. Solo administradores pueden realizar esta acción.', 
            'warning'
        );
        return;
    }
    
    try {
        const response = await fetch(`index.php?c=Practica&a=api_practica&id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            this.abrirModalEditarPractica(data.data);
        } else {
            this.mostrarNotificacion('Error', data.error || 'No se pudo cargar la práctica', 'error');
        }
    } catch (error) {
        console.error('Error al editar práctica:', error);
        this.mostrarNotificacion('Error', 'Error al cargar los datos para editar', 'error');
    }
}
    
    confirmarEliminarPractica(id) {
    const userInfo = window.userInfo || {};
    
    // 🔥 Si es docente, mostrar mensaje de no permitido
    if (userInfo.esDocente) {
        this.mostrarNotificacion(
            'Permiso denegado', 
            'No tiene permisos para eliminar prácticas. Solo administradores pueden realizar esta acción.', 
            'warning'
        );
        return;
    }
    
    this.practicaAEliminar = this.practicas.find(p => p.id == id);
    if (this.practicaAEliminar) {
        this.abrirModal('modalConfirmarEliminar');
    }
}
    
    async eliminarPractica() {
        if (!this.practicaAEliminar) return;
        
        try {
            const response = await fetch(`index.php?c=Practica&a=api_eliminar&id=${this.practicaAEliminar.id}`);
            const data = await response.json();
            
            if (data.success) {
                // 🔔 Mostrar notificación de éxito (igual que tu página estática)
                this.mostrarNotificacion('Éxito', 'Práctica eliminada correctamente', 'success');
                this.cerrarModal('modalConfirmarEliminar');
                this.cargarDatosPracticas();
                this.practicaAEliminar = null;
            } else {
                throw new Error(data.error || 'Error al eliminar');
            }
        } catch (error) {
            console.error('Error al eliminar práctica:', error);
            this.mostrarNotificacion('Error', 'Error al eliminar la práctica', 'error');
        }
    }
    
    abrirModalNuevaPractica() {
    // Limpiar formulario
    const form = document.getElementById('formNuevaPractica');
    if (form) form.reset();
    
    // Cargar estudiantes
    this.cargarSelectEstudiantes('nuevoEstudiante');
    
    // Cargar empresas
    this.cargarSelectEmpresas('nuevaEmpresa');
    
    // Cargar empleados
    this.cargarSelectEmpleados('nuevoEmpleado');
    
    // Fecha por defecto
    const fechaInput = document.getElementById('nuevaFechaInicio');
    if (fechaInput) {
        fechaInput.valueAsDate = new Date();
    }
    
    // 🔥 Agregar evento para cargar módulos cuando cambie el estudiante
    const selectEstudiante = document.getElementById('nuevoEstudiante');
    if (selectEstudiante) {
        selectEstudiante.addEventListener('change', (e) => {
            const estudianteId = e.target.value;
            if (estudianteId) {
                this.cargarModulosEstudiante(estudianteId, 'nuevoTipoModulo');
            } else {
                // Resetear select de módulos
                const selectModulo = document.getElementById('nuevoTipoModulo');
                if (selectModulo) {
                    selectModulo.innerHTML = `
                        <option value="">Seleccionar tipo</option>
                        <option value="modulo1">Módulo 1</option>
                        <option value="modulo2">Módulo 2</option>
                        <option value="modulo3">Módulo 3</option>
                    `;
                }
            }
        });
    }
    
    this.abrirModal('modalNuevaPractica');
}
    
    async guardarNuevaPractica(e) {
    e.preventDefault();
    
    const form = document.getElementById('formNuevaPractica');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Recolectar datos
    const datos = {
        estudiante: document.getElementById('nuevoEstudiante').value,
        empresa: document.getElementById('nuevaEmpresa').value,
        empleado: document.getElementById('nuevoEmpleado').value,
        tipo_efsrt: document.getElementById('nuevoTipoModulo').value,
        fecha_inicio: document.getElementById('nuevaFechaInicio').value,
        area_ejecucion: document.getElementById('nuevaArea').value,
        supervisor_empresa: document.getElementById('nuevoSupervisor').value,
        cargo_supervisor: document.getElementById('nuevoCargo').value
    };
    
    console.log("📤 Enviando datos:", datos);
    
    try {
        // 🔥 USAR FormData para enviar como POST normal (no JSON)
        const formData = new FormData();
        Object.keys(datos).forEach(key => {
            formData.append(key, datos[key]);
        });
        
        const response = await fetch('index.php?c=Practica&a=api_guardar', {
            method: 'POST',
            body: formData // 🔥 Enviar como FormData
        });
        
        const result = await response.json();
        console.log("📥 Respuesta del servidor:", result);
        
        if (result.success) {
            this.cerrarModal('modalNuevaPractica');
            this.mostrarNotificacion('Éxito', 'Práctica creada correctamente', 'success');
            this.cargarDatosPracticas();
        } else {
            throw new Error(result.error || 'Error al guardar');
        }
    } catch (error) {
        console.error('❌ Error al guardar práctica:', error);
        this.mostrarNotificacion('Error', error.message || 'Error al guardar la práctica', 'error');
    }
}
    
    abrirModalEditarPractica(practica) {
    // Llenar formulario con datos
    document.getElementById('editarId').value = practica.id;
    document.getElementById('editarEstudiante').value = practica.estudiante;
    document.getElementById('editarEmpresa').value = practica.empresa;
    document.getElementById('editarEmpleado').value = practica.empleado;
    document.getElementById('editarTipoModulo').value = practica.tipo_efsrt;
    document.getElementById('editarFechaInicio').value = practica.fecha_inicio;
    document.getElementById('editarArea').value = practica.area_ejecucion || '';
    document.getElementById('editarSupervisor').value = practica.supervisor_empresa || '';
    document.getElementById('editarCargo').value = practica.cargo_supervisor || '';
    
    // Cargar opciones en los selects
    this.cargarSelectEstudiantes('editarEstudiante');
    this.cargarSelectEmpresas('editarEmpresa');
    this.cargarSelectEmpleados('editarEmpleado');
    
    // 🔥 Si es docente, deshabilitar algunos campos (opcional)
    const userInfo = window.userInfo || {};
    if (userInfo.esDocente) {
        // Los docentes solo pueden editar información específica
        document.getElementById('editarEstudiante').disabled = true;
        document.getElementById('editarEmpresa').disabled = true;
        document.getElementById('editarTipoModulo').disabled = true;
        // Pueden editar supervisor, área, cargo, etc.
    }
    
    this.abrirModal('modalEditarPractica');
}
    
    async guardarEditarPractica(e) {
    e.preventDefault();
    
    const form = document.getElementById('formEditarPractica');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Recolectar datos
    const datos = {
        id: document.getElementById('editarId').value,
        estudiante: document.getElementById('editarEstudiante').value,
        empresa: document.getElementById('editarEmpresa').value,
        empleado: document.getElementById('editarEmpleado').value,
        tipo_efsrt: document.getElementById('editarTipoModulo').value,
        fecha_inicio: document.getElementById('editarFechaInicio').value,
        area_ejecucion: document.getElementById('editarArea').value,
        supervisor_empresa: document.getElementById('editarSupervisor').value,
        cargo_supervisor: document.getElementById('editarCargo').value
    };
    
    console.log("📤 Enviando datos para editar:", datos);
    
    try {
        // 🔥 USAR FormData para enviar como POST normal (no JSON)
        const formData = new FormData();
        Object.keys(datos).forEach(key => {
            formData.append(key, datos[key]);
        });
        
        const response = await fetch('index.php?c=Practica&a=api_guardar', {
            method: 'POST',
            body: formData // 🔥 Enviar como FormData
        });
        
        const result = await response.json();
        console.log("📥 Respuesta del servidor:", result);
        
        if (result.success) {
            this.cerrarModal('modalEditarPractica');
            this.mostrarNotificacion('Éxito', 'Práctica actualizada correctamente', 'success');
            this.cargarDatosPracticas();
        } else {
            throw new Error(result.error || 'Error al actualizar');
        }
    } catch (error) {
        console.error('❌ Error al actualizar práctica:', error);
        this.mostrarNotificacion('Error', error.message || 'Error al actualizar la práctica', 'error');
    }
}
    
    cargarSelectEstudiantes(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;
        
        // Guardar valor actual
        const currentValue = select.value;
        
        select.innerHTML = '<option value="">Seleccionar estudiante</option>';
        
        this.estudiantes.forEach(estudiante => {
            const option = document.createElement('option');
            option.value = estudiante.id;
            option.textContent = estudiante.nombre_completo || `${estudiante.nom_est} ${estudiante.ap_est}`;
            option.selected = (estudiante.id == currentValue);
            select.appendChild(option);
        });
    }
    
    cargarSelectEmpresas(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;
        
        // Guardar valor actual
        const currentValue = select.value;
        
        select.innerHTML = '<option value="">Seleccionar empresa</option>';
        
        this.empresas.forEach(empresa => {
            const option = document.createElement('option');
            option.value = empresa.id;
            option.textContent = empresa.razon_social;
            option.selected = (empresa.id == currentValue);
            select.appendChild(option);
        });
    }
    
    cargarSelectEmpleados(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;
        
        // Guardar valor actual
        const currentValue = select.value;
        
        select.innerHTML = '<option value="">Seleccionar docente</option>';
        
        this.empleados.forEach(empleado => {
            const option = document.createElement('option');
            option.value = empleado.id;
            option.textContent = empleado.apnom_emp;
            option.selected = (empleado.id == currentValue);
            select.appendChild(option);
        });
    }
    
    mostrarDetallesPractica(practica) {
        const estudiante = this.estudiantes.find(e => e.id == practica.estudiante);
        const empresa = this.empresas.find(e => e.id == practica.empresa);
        const empleado = this.empleados.find(e => e.id == practica.empleado);
        
        let estadoBadge = '';
        let estadoIcon = '';
        if (practica.estado === 'Finalizado') {
            estadoBadge = 'badge-finalizado';
            estadoIcon = 'fa-check-circle';
        } else if (practica.estado === 'En curso') {
            estadoBadge = 'badge-en-curso';
            estadoIcon = 'fa-play-circle';
        } else {
            estadoBadge = 'badge-pendiente';
            estadoIcon = 'fa-clock';
        }
        
        let moduloBadge = '';
        let moduloText = '';
        if (practica.tipo_efsrt === 'modulo1') {
            moduloBadge = 'badge-modulo1';
            moduloText = 'Módulo 1';
        } else if (practica.tipo_efsrt === 'modulo2') {
            moduloBadge = 'badge-modulo2';
            moduloText = 'Módulo 2';
        } else {
            moduloBadge = 'badge-modulo3';
            moduloText = 'Módulo 3';
        }
        
        const fechaInicio = practica.fecha_inicio ? new Date(practica.fecha_inicio).toLocaleDateString('es-ES', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        }) : 'No definida';
        
        const contenido = document.getElementById('contenidoDetalles');
        contenido.innerHTML = `
            <div class="space-y-6">
                <!-- Encabezado -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-xl font-bold text-primary-blue mb-2">Práctica #${practica.id}</h4>
                            <div class="flex items-center gap-3">
                                <span class="badge-estado ${estadoBadge}">
                                    <i class="fas ${estadoIcon} mr-1"></i> ${practica.estado || 'Pendiente'}
                                </span>
                                <span class="badge-estado ${moduloBadge}">
                                    <i class="fas fa-cube mr-1"></i> ${moduloText}
                                </span>
                            </div>
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            <div>ID: ${practica.id}</div>
                            <div>Registrado: ${new Date().toLocaleDateString('es-ES')}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Información del Estudiante -->
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <h5 class="font-bold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-user-graduate text-blue-500 mr-2"></i>
                        Información del Estudiante
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Nombre Completo</div>
                            <div class="font-medium">${estudiante ? (estudiante.nombre_completo || `${estudiante.nom_est} ${estudiante.ap_est}`) : 'No encontrado'}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">DNI</div>
                            <div class="font-medium">${estudiante ? estudiante.dni_est : 'No disponible'}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Programa de Estudios</div>
                            <div class="font-medium">${estudiante ? estudiante.programa : 'No disponible'}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Información de la Empresa -->
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <h5 class="font-bold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-building text-blue-500 mr-2"></i>
                        Información de la Empresa
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Empresa</div>
                            <div class="font-medium">${empresa ? empresa.razon_social : 'No encontrada'}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Supervisor de Empresa</div>
                            <div class="font-medium">${practica.supervisor_empresa || 'No especificado'}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Cargo del Supervisor</div>
                            <div class="font-medium">${practica.cargo_supervisor || 'No especificado'}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Información de la Práctica -->
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <h5 class="font-bold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-briefcase text-blue-500 mr-2"></i>
                        Información de la Práctica
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Docente Supervisor</div>
                            <div class="font-medium">${empleado ? empleado.apnom_emp : 'No asignado'}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Fecha de Inicio</div>
                            <div class="font-medium">${fechaInicio}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-sm text-gray-500 mb-1">Área de Ejecución</div>
                            <div class="font-medium">${practica.area_ejecucion || 'No especificada'}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Horas y Progreso -->
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <h5 class="font-bold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                        Horas y Progreso
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Horas Acumuladas</div>
                            <div class="font-medium">${practica.horas_acumuladas || 0} horas</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Horas Totales</div>
                            <div class="font-medium">${practica.total_horas || 0} horas</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-sm text-gray-500 mb-1">Progreso</div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span>${practica.total_horas ? Math.round(((practica.horas_acumuladas || 0) / practica.total_horas) * 100) : 0}% completado</span>
                                    <span>${practica.horas_acumuladas || 0}/${practica.total_horas || 0} horas</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: ${practica.total_horas ? Math.round(((practica.horas_acumuladas || 0) / practica.total_horas) * 100) : 0}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        this.abrirModal('modalDetallesPractica');
    }
    
    // Métodos de utilidad
    mostrarCarga(mensaje = 'Cargando...') {
        let overlay = document.getElementById('loadingOverlayPracticas');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'loadingOverlayPracticas';
            overlay.innerHTML = `
                <div class="loading-content">
                    <div class="loading mr-3"></div>
                    <span class="text-gray-700 font-medium">${mensaje}</span>
                </div>
            `;
            document.body.appendChild(overlay);
        }
        overlay.style.display = 'flex';
    }
    
    ocultarCarga() {
        const overlay = document.getElementById('loadingOverlayPracticas');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }
    
    mostrarNotificacion(titulo, mensaje, tipo = 'info', duracion = 5000) {
        const container = document.getElementById('notificationContainerPracticas');
        
        if (!container) {
            console.warn('Contenedor de notificaciones no encontrado');
            console.log(`[${tipo.toUpperCase()}] ${titulo}: ${mensaje}`);
            return null;
        }
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${tipo}`;
        
        // Icono según el tipo (igual que tu página estática)
        let icono = '';
        switch(tipo) {
            case 'success':
                icono = 'fa-check';
                break;
            case 'error':
                icono = 'fa-exclamation';
                break;
            case 'warning':
                icono = 'fa-exclamation-triangle';
                break;
            default:
                icono = 'fa-info';
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
        
        container.appendChild(notification);
        
        // Mostrar notificación con animación (igual que tu página estática)
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Configurar botón de cerrar
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            this.cerrarNotificacion(notification);
        });
        
        // Cerrar automáticamente después del tiempo especificado
        if (duracion > 0) {
            setTimeout(() => {
                if (notification.parentNode) {
                    this.cerrarNotificacion(notification);
                }
            }, duracion);
        }
        
        return notification;
    }

    cerrarNotificacion(notification) {
        notification.classList.remove('show');
        notification.classList.add('hide');
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 400);
    }
    
    abrirModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }
    
    cerrarModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }
}

// Inicializar aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.practicasApp = new PracticasApp();
});