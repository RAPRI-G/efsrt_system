// assets/js/empresas.js - VERSIÓN COMPLETA Y CORREGIDA
class EmpresaManager {
    constructor() {
        this.empresas = [];
        this.configPaginacion = {
            paginaActual: 1,
            elementosPorPagina: 10,
            totalElementos: 0,
            vistaActual: 'tabla'
        };
        
        // Instancias de gráficos para poder destruirlos
        this.chartInstances = {
            departamentos: null,
            estado: null
        };
        
        // Esperar a que el DOM esté completamente cargado
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        console.log('Inicializando EmpresaManager...');
        
        // ✅ OCULTAR MODAL DE CONFIRMACIÓN AL INICIAR
    const confirmationModal = document.getElementById('confirmationModal');
    if (confirmationModal) {
        confirmationModal.classList.remove('show');
    }
        this.cargarDatosIniciales();
        this.setupEventListeners();
        this.setupModalEvents();
    }

    // 🔄 CARGAR DATOS INICIALES
    async cargarDatosIniciales() {
        try {
            console.log('Cargando datos iniciales...');
            
            // DESTRUIR GRÁFICOS EXISTENTES AL INICIAR
            this.destruirGraficos();
            
            await Promise.all([
                this.cargarEstadisticas(),
                this.cargarEmpresas()
            ]);
            console.log('Datos iniciales cargados correctamente');
        } catch (error) {
            console.error('Error al cargar datos iniciales:', error);
            this.mostrarError('Error al cargar datos iniciales: ' + error.message);
        }
    }

    // 📊 CARGAR ESTADÍSTICAS DEL DASHBOARD
    async cargarEstadisticas() {
        try {
            console.log('Cargando estadísticas...');
            const response = await this.fetchAPI('Empresa', 'api_estadisticas');
            
            if (response.success) {
                console.log('Estadísticas cargadas:', response.data);
                this.actualizarDashboard(response.data);
                this.inicializarGraficos(response.data);
            } else {
                throw new Error(response.error || 'Error desconocido en estadísticas');
            }
        } catch (error) {
            console.error('Error cargando estadísticas:', error);
            this.mostrarError('Error al cargar estadísticas: ' + error.message);
        }
    }

    // 🏢 CARGAR LISTA DE EMPRESAS
    async cargarEmpresas(filtros = {}) {
    try {
        this.mostrarLoading(true);
        this.mostrarIndicadorBusqueda(true); // ✅ NUEVO: Mostrar indicador de búsqueda
        
        console.log('Cargando empresas con filtros:', filtros);
        
        const params = new URLSearchParams();
        
        if (filtros.busqueda) {
            params.append('busqueda', filtros.busqueda);
        }
        if (filtros.departamento && filtros.departamento !== 'all') {
            params.append('departamento', filtros.departamento);
        }
        if (filtros.estado && filtros.estado !== 'all') {
            params.append('estado', filtros.estado);
        }

        params.append('pagina', this.configPaginacion.paginaActual);
        params.append('limit', this.configPaginacion.elementosPorPagina);

        const url = `index.php?c=Empresa&a=api_empresas&${params.toString()}`;
        console.log('URL de empresas:', url);
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Respuesta de empresas:', data);

        if (data.success) {
            this.empresas = data.data;
            this.configPaginacion.totalElementos = data.total;
            this.renderizarEmpresas();
            
            // ✅ MOSTRAR MENSAJE SI NO HAY RESULTADOS
            if (this.empresas.length === 0 && filtros.busqueda) {
                this.mostrarNotificacion('info', 'Búsqueda', 'No se encontraron empresas con los criterios de búsqueda.');
            }
        } else {
            throw new Error(data.error || 'Error desconocido al cargar empresas');
        }
    } catch (error) {
        console.error('Error cargando empresas:', error);
        this.mostrarError('Error al cargar empresas: ' + error.message);
    } finally {
        this.mostrarLoading(false);
        this.mostrarIndicadorBusqueda(false); // ✅ OCULTAR indicador de búsqueda
    }
}

    // 🎯 ACTUALIZAR DASHBOARD - COMPATIBLE CON ESTRUCTURA ESTÁTICA
 // 📊 ACTUALIZAR DASHBOARD - CON MEJOR DEBUGGING
actualizarDashboard(estadisticas) {
    console.log('📊 Actualizando dashboard con:', estadisticas);
    
    // ✅ VERIFICAR QUE TENEMOS DATOS VÁLIDOS
    if (!estadisticas) {
        console.error('❌ No hay datos de estadísticas');
        return;
    }
    
    // ✅ ACTUALIZAR TARJETAS PRINCIPALES
    const elementos = {
        'total-empresas': estadisticas.total_empresas || 0,
        'empresas-activas': estadisticas.empresas_activas || 0,
        'empresas-practicas': estadisticas.empresas_con_practicas || 0
    };

    console.log('📊 Actualizando elementos:', elementos);

    Object.keys(elementos).forEach(id => {
        const elemento = document.getElementById(id);
        if (elemento) {
            elemento.textContent = elementos[id];
        } else {
            console.warn(`❌ Elemento #${id} no encontrado`);
        }
    });

    // ✅ CONTAR DEPARTAMENTOS ÚNICOS
    const departamentosCount = estadisticas.distribucion_sectores?.length || 0;
    const departamentosElement = document.getElementById('departamentos-count');
    if (departamentosElement) {
        departamentosElement.textContent = departamentosCount;
    }

    // ✅ ACTUALIZAR TEXTOS DESCRIPTIVOS
    this.actualizarTextoSiExiste('empresas-texto', 
        `${estadisticas.total_empresas || 0} registradas`);
    
    this.actualizarTextoSiExiste('activas-texto', 
        `${estadisticas.empresas_activas || 0} activas de ${estadisticas.total_empresas || 0}`);
    
    this.actualizarTextoSiExiste('practicas-texto', 
        `${estadisticas.empresas_con_practicas || 0} con prácticas activas`);
    
    this.actualizarTextoSiExiste('departamentos-texto', 
        `${departamentosCount} departamentos`);

    console.log('✅ Dashboard actualizado correctamente');
}

// 📈 INICIALIZAR GRÁFICOS - ACTUALIZADO PARA MOSTRAR ESTADOS CORRECTOS
inicializarGraficoEstado(estadisticas) {
    const ctx = document.getElementById('estadoChart');
    if (!ctx) {
        console.warn('❌ Canvas estadoChart no encontrado');
        return;
    }
    
    // ✅ DEBUG: Verificar qué datos estamos recibiendo
    console.log('📊 Datos para gráfico de estado:', estadisticas);
    
    // ✅ CORREGIDO: Obtener datos correctamente
    const activas = estadisticas.empresas_activas || 0;
    const inactivas = estadisticas.empresas_inactivas || 0;
    
    console.log('📊 Empresas activas:', activas);
    console.log('📊 Empresas inactivas:', inactivas);
    
    // ✅ Validar que tengamos datos
    if (activas === 0 && inactivas === 0) {
        console.warn('⚠️ No hay datos para el gráfico de estado');
        this.mostrarMensajeGraficoVacio('estadoChart', 'No hay datos de empresas');
        return;
    }
    
    // DESTRUIR GRÁFICO EXISTENTE
    if (this.chartInstances.estado) {
        this.chartInstances.estado.destroy();
    }
    
    // ✅ CREAR NUEVO GRÁFICO CON DATOS CORRECTOS
    this.chartInstances.estado = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Empresas Activas', 'Empresas Inactivas'],
            datasets: [{
                data: [activas, inactivas],
                backgroundColor: [
                    '#198754', // Verde para activas
                    '#6c757d'  // Gris para inactivas
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverBorderWidth: 4,
                hoverBorderColor: '#f8f9fa'
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
                            size: 12,
                            family: "'Inter', sans-serif"
                        },
                        color: '#374151'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 13 },
                    bodyFont: { size: 13 },
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
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
    
    console.log('✅ Gráfico de estado creado correctamente');
}

// 🔧 MÉTODO PARA MOSTRAR MENSAJE CUANDO NO HAY DATOS
mostrarMensajeGraficoVacio(canvasId, mensaje) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    ctx.fillStyle = '#9ca3af';
    ctx.font = '14px Inter, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText(mensaje, canvas.width / 2, canvas.height / 2);
}

    actualizarTextoSiExiste(id, texto) {
        const elemento = document.getElementById(id);
        if (elemento) {
            elemento.textContent = texto;
        }
    }

    // 📈 INICIALIZAR GRÁFICOS CON DESTRUCCIÓN PREVIA
    inicializarGraficos(estadisticas) {
        console.log('Inicializando gráficos...');
        
        // DESTRUIR GRÁFICOS EXISTENTES ANTES DE CREAR NUEVOS
        this.destruirGraficos();
        
        const canvasDepartamentos = document.getElementById('departamentosChart');
        const canvasEstado = document.getElementById('estadoChart');
        
        if (canvasDepartamentos) {
            this.inicializarGraficoDepartamentos(estadisticas.distribucion_sectores);
        } else {
            console.warn('Canvas departamentosChart no encontrado');
        }
        
        if (canvasEstado) {
            this.inicializarGraficoEstado(estadisticas);
        } else {
            console.warn('Canvas estadoChart no encontrado');
        }
    }

    // 🔥 MÉTODO: DESTRUIR GRÁFICOS EXISTENTES
    destruirGraficos() {
        console.log('Destruyendo gráficos existentes...');
        
        if (this.chartInstances.departamentos) {
            console.log('Destruyendo gráfico de departamentos...');
            this.chartInstances.departamentos.destroy();
            this.chartInstances.departamentos = null;
        }
        
        if (this.chartInstances.estado) {
            console.log('Destruyendo gráfico de estado...');
            this.chartInstances.estado.destroy();
            this.chartInstances.estado = null;
        }
    }

    inicializarGraficoDepartamentos(distribucionSectores) {
        const ctx = document.getElementById('departamentosChart').getContext('2d');
        
        // Verificar que hay datos
        if (!distribucionSectores || distribucionSectores.length === 0) {
            console.warn('No hay datos para el gráfico de departamentos');
            return;
        }
        
        const labels = distribucionSectores.map(item => item.sector);
        const data = distribucionSectores.map(item => item.cantidad);
        
        // GUARDAR LA INSTANCIA PARA PODER DESTRUIRLA DESPUÉS
        this.chartInstances.departamentos = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#0C1F36', '#0dcaf0', '#198754', '#ffc107', '#6c757d',
                        '#6610f2', '#d63384', '#fd7e14', '#20c997', '#0dcaf0'
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
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }


    // 🏢 RENDERIZAR EMPRESAS (TABLA O TARJETAS)
    renderizarEmpresas() {
        console.log('Renderizando empresas...');
        
        if (this.configPaginacion.vistaActual === 'tabla') {
            this.renderizarTablaEmpresas();
        } else {
            this.renderizarTarjetasEmpresas();
        }
        this.actualizarContadores();
        this.actualizarPaginacion();
    }

    renderizarTablaEmpresas() {
        const tabla = document.getElementById('tabla-empresas');
        if (!tabla) {
            console.error('Elemento #tabla-empresas no encontrado');
            return;
        }
        
        if (this.empresas.length === 0) {
            tabla.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-search text-2xl text-gray-300 mb-2"></i>
                        <p class="font-medium">No se encontraron empresas</p>
                        <p class="text-sm">Intenta con otros términos de búsqueda</p>
                    </td>
                </tr>
            `;
            return;
        }

        const inicio = (this.configPaginacion.paginaActual - 1) * this.configPaginacion.elementosPorPagina;
        const fin = inicio + this.configPaginacion.elementosPorPagina;
        const empresasPagina = this.empresas.slice(inicio, fin);

        tabla.innerHTML = empresasPagina.map(empresa => `
            <tr class="hover:bg-gray-50 transition-all duration-300 fade-in">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="avatar-empresa h-10 w-10 rounded-lg flex items-center justify-center text-white font-semibold mr-3">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-gray-900">
                                ${empresa.razon_social}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${empresa.ruc}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${empresa.representante_legal || 'No especificado'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div>${empresa.departamento}, ${empresa.provincia}</div>
                    <div class="text-xs">${empresa.distrito}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div>${empresa.telefono || 'N/A'}</div>
                    <div class="text-xs">${empresa.email}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="badge-estado ${empresa.estado === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo'}">
                        ${empresa.estado}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <button class="btn-accion btn-editar editar-empresa" data-id="${empresa.id}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-ver ver-empresa" data-id="${empresa.id}" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-accion btn-eliminar eliminar-empresa" data-id="${empresa.id}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        this.setupActionButtons();
    }

    renderizarTarjetasEmpresas() {
        const contenedor = document.getElementById('vistaTarjetas');
        if (!contenedor) {
            console.error('Elemento #vistaTarjetas no encontrado');
            return;
        }
        
        if (this.empresas.length === 0) {
            contenedor.innerHTML = `
                <div class="col-span-3 bg-white rounded-2xl shadow-lg p-8 text-center">
                    <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No se encontraron empresas</h3>
                    <p class="text-gray-500">No hay empresas que coincidan con los filtros aplicados</p>
                </div>
            `;
            return;
        }

        const inicio = (this.configPaginacion.paginaActual - 1) * this.configPaginacion.elementosPorPagina;
        const fin = inicio + this.configPaginacion.elementosPorPagina;
        const empresasPagina = this.empresas.slice(inicio, fin);

        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
        
        grid.innerHTML = empresasPagina.map(empresa => `
            <div class="bg-white rounded-2xl shadow-lg p-6 card-empresa fade-in">
                <div class="flex justify-between items-start mb-4">
                    <div class="avatar-empresa h-14 w-14 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="flex space-x-2">
                        <button class="btn-accion btn-ver ver-empresa" data-id="${empresa.id}" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-accion btn-editar editar-empresa" data-id="${empresa.id}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-primary-blue mb-2">${empresa.razon_social}</h3>
                <div class="flex items-center text-sm text-gray-500 mb-3">
                    <i class="fas fa-id-card mr-2"></i>
                    <span>RUC: ${empresa.ruc}</span>
                </div>
                <div class="text-sm text-gray-600 mb-4">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-user-tie mr-2 text-blue-500"></i>
                        <span class="truncate">${empresa.representante_legal || 'No especificado'}</span>
                    </div>
                    <div class="flex items-center mb-1">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                        <span>${empresa.departamento}, ${empresa.distrito}</span>
                    </div>
                    <div class="flex items-center mb-1">
                        <i class="fas fa-phone mr-2 text-blue-500"></i>
                        <span>${empresa.telefono || 'N/A'}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-2 text-blue-500"></i>
                        <span class="truncate">${empresa.email}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="badge-estado ${empresa.estado === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo'}">
                        ${empresa.estado}
                    </span>
                </div>
            </div>
        `).join('');

        contenedor.innerHTML = '';
        contenedor.appendChild(grid);
        
        this.setupActionButtons();
    }

    // 🔍 VALIDAR RUC EN TIEMPO REAL
setupRucValidation() {
    const rucInput = document.getElementById('ruc');
    if (!rucInput) return;
    
    let validationTimeout;
    
    rucInput.addEventListener('input', (e) => {
        this.limpiarValidacionRuc();
        
        const ruc = e.target.value.trim();
        
        // Validación básica inmediata
        if (ruc.length > 0) {
            this.validarFormatoRuc(ruc);
        }
        
        // Validación con servidor (con delay)
        clearTimeout(validationTimeout);
        validationTimeout = setTimeout(() => {
            this.validarRucEnServidor(ruc);
        }, 800);
    });
    
    // Validar al perder foco
    rucInput.addEventListener('blur', (e) => {
        const ruc = e.target.value.trim();
        if (ruc.length > 0) {
            this.validarRucEnServidor(ruc);
        }
    });
}

// 🧹 LIMPIAR ESTADO DE VALIDACIÓN
limpiarValidacionRuc() {
    const rucInput = document.getElementById('ruc');
    const feedback = document.getElementById('rucFeedback');
    
    if (rucInput) {
        rucInput.classList.remove('border-green-500', 'border-red-500', 'border-yellow-500');
    }
    
    if (feedback) {
        feedback.remove();
    }
}

// ✅ VALIDAR FORMATO DE RUC (frontend)
validarFormatoRuc(ruc) {
    const rucInput = document.getElementById('ruc');
    if (!rucInput) return;
    
    // Validar que solo tenga números
    if (!/^\d*$/.test(ruc)) {
        this.mostrarErrorRuc('El RUC solo debe contener números');
        return false;
    }
    
    // Validar longitud
    if (ruc.length > 0 && ruc.length !== 11) {
        this.mostrarAdvertenciaRuc('El RUC debe tener 11 dígitos');
        return false;
    }
    
    if (ruc.length === 11) {
        this.mostrarExitoRuc('Formato de RUC válido');
        return true;
    }
    
    return null; // Aún no está completo
}

// 🔍 VALIDAR RUC EN EL SERVIDOR
async validarRucEnServidor(ruc) {
    if (!ruc || ruc.length !== 11) return;
    
    try {
        this.mostrarLoadingRuc(true);
        
        const empresaId = document.getElementById('empresaId')?.value || null;
        const params = new URLSearchParams({ ruc: ruc });
        if (empresaId) params.append('excluir_id', empresaId);
        
        const response = await this.fetchAPI('Empresa', 'api_validar_ruc', params);
        
        if (response.success) {
            if (response.data.existe) {
                this.mostrarErrorRuc('Este RUC ya está registrado en el sistema');
            } else {
                this.mostrarExitoRuc('RUC disponible');
            }
        } else {
            this.mostrarAdvertenciaRuc('No se pudo verificar el RUC');
        }
        
    } catch (error) {
        console.error('Error validando RUC:', error);
        this.mostrarAdvertenciaRuc('Error al conectar con el servidor');
    } finally {
        this.mostrarLoadingRuc(false);
    }
}

// 🎨 MOSTRAR ESTADOS DE VALIDACIÓN
mostrarErrorRuc(mensaje) {
    this.mostrarFeedbackRuc(mensaje, 'red');
}

mostrarAdvertenciaRuc(mensaje) {
    this.mostrarFeedbackRuc(mensaje, 'yellow');
}

mostrarExitoRuc(mensaje) {
    this.mostrarFeedbackRuc(mensaje, 'green');
}

mostrarFeedbackRuc(mensaje, color) {
    const rucInput = document.getElementById('ruc');
    if (!rucInput) return;
    
    // Limpiar feedback anterior
    this.limpiarValidacionRuc();
    
    // Aplicar estilos al input
    rucInput.classList.add(`border-${color}-500`);
    
    // Crear elemento de feedback
    const feedback = document.createElement('div');
    feedback.id = 'rucFeedback';
    feedback.className = `mt-1 text-sm text-${color}-600 flex items-center`;
    feedback.innerHTML = `
        <i class="fas ${this.getIconoValidacion(color)} mr-1"></i>
        ${mensaje}
    `;
    
    rucInput.parentNode.appendChild(feedback);
}

getIconoValidacion(color) {
    switch (color) {
        case 'green': return 'fa-check-circle';
        case 'red': return 'fa-exclamation-circle';
        case 'yellow': return 'fa-exclamation-triangle';
        default: return 'fa-info-circle';
    }
}

// 🔄 MOSTRAR/OCULTAR LOADING
mostrarLoadingRuc(mostrar) {
    const rucInput = document.getElementById('ruc');
    if (!rucInput) return;
    
    let loadingIcon = rucInput.parentNode.querySelector('.ruc-loading');
    
    if (mostrar && !loadingIcon) {
        loadingIcon = document.createElement('div');
        loadingIcon.className = 'ruc-loading absolute right-10 top-2';
        loadingIcon.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-500"></i>';
        rucInput.parentNode.appendChild(loadingIcon);
    } else if (!mostrar && loadingIcon) {
        loadingIcon.remove();
    }
}

    // 🔘 CONFIGURAR BOTONES DE ACCIÓN
    setupActionButtons() {

        console.log('🔧 Configurando botones de acción...');
        // Botones de editar
        document.querySelectorAll('.editar-empresa').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                this.abrirModalEditar(id);
            });
        });
        
        // Botones de ver
        document.querySelectorAll('.ver-empresa').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                this.verEmpresa(id);
            });
        });
        
        // Botones de eliminar
    document.querySelectorAll('.eliminar-empresa').forEach(btn => {
        // ✅ REMOVER EVENT LISTENERS EXISTENTES PRIMERO
        btn.replaceWith(btn.cloneNode(true));
    });
    
    // ✅ VOLVER A AGREGAR EVENT LISTENERS
    document.querySelectorAll('.eliminar-empresa').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const id = e.currentTarget.getAttribute('data-id');
            console.log('🗑️ Click en eliminar empresa ID:', id);
            
            if (id) {
                this.eliminarEmpresa(id);
            } else {
                console.error('❌ ID no encontrado en botón eliminar');
            }
        });
    });
    }

    // 📝 ABRIR MODAL PARA EDITAR/CREAR EMPRESA
    async abrirModalEditar(id = null) {
    const modal = document.getElementById('empresaModal');
    const titulo = document.getElementById('modalTitulo');
    const form = document.getElementById('formEmpresa');
    
    if (!modal || !titulo || !form) {
        console.error('Elementos del modal no encontrados');
        return;
    }
    
    // ✅ RESETEAR FORMULARIO PRIMERO
    form.reset();
    this.limpiarValidacionRuc();
    
    // ✅ RESETEAR SELECTS DE UBICACIÓN
    this.actualizarSelect('provincia_id', [], 'Seleccionar provincia');
    this.actualizarSelect('distrito_id', [], 'Seleccionar distrito');
    document.getElementById('provincia_id').disabled = true;
    document.getElementById('distrito_id').disabled = true;
    
    // ✅ CARGAR DEPARTAMENTOS
    await this.cargarDepartamentos();
    
    if (id) {
        // Modo edición
        titulo.textContent = 'Editar Empresa';
        await this.cargarDatosEmpresa(id, form);
    } else {
        // Modo creación
        titulo.textContent = 'Nueva Empresa';
        const empresaId = document.getElementById('empresaId');
        if (empresaId) empresaId.value = '';
    }
    
    modal.classList.remove('hidden');
}

    async cargarDatosEmpresa(id, form) {
    try {
        const response = await this.fetchAPI('Empresa', 'api_empresa', { id });
        
        if (response.success) {
            const empresa = response.data;
            
            console.log('📝 Datos de empresa para editar:', empresa);
            
            // ✅ DATOS BÁSICOS
            this.setValue('empresaId', empresa.id);
            this.setValue('ruc', empresa.ruc);
            this.setValue('razon_social', empresa.razon_social);
            this.setValue('representante_legal', empresa.representante_legal || '');
            this.setValue('direccion_fiscal', empresa.direccion_fiscal);
            this.setValue('telefono', empresa.telefono || '');
            this.setValue('email', empresa.email);
            this.setValue('estado', empresa.estado);
            
            // ✅ CARGAR UBICACIÓN CON IDs
            await this.cargarUbicacionParaEdicion(empresa);
            
        } else {
            throw new Error(response.error);
        }
    } catch (error) {
        this.mostrarError('Error al cargar datos de la empresa: ' + error.message);
    }
}

// 🔧 ESTABLECER VALOR EN SELECT
setSelectValue(selectId, value) {
    const select = document.getElementById(selectId);
    if (select && value) {
        select.value = value;
        console.log(`✅ Select ${selectId} establecido a:`, value);
    } else {
        console.warn(`⚠️ No se pudo establecer ${selectId} a:`, value);
    }
}

// 📋 OBTENER DEPARTAMENTOS (para búsqueda)
async obtenerDepartamentos() {
    try {
        const response = await this.fetchAPI('Empresa', 'api_departamentos');
        return response.success ? response.data : [];
    } catch (error) {
        console.error('Error obteniendo departamentos:', error);
        return [];
    }
}

// 🔄 CARGAR UBICACIÓN PARA EDICIÓN
async cargarUbicacionParaEdicion(empresa) {
    try {
        console.log('📍 Cargando ubicación para edición:', {
            departamento: empresa.departamento,
            provincia: empresa.provincia, 
            distrito: empresa.distrito,
            departamento_id: empresa.departamento_id,
            provincia_id: empresa.provincia_id,
            distrito_id: empresa.distrito_id
        });
        
        // ✅ CARGAR DEPARTAMENTOS PRIMERO
        await this.cargarDepartamentos();
        
        // ✅ SI TENEMOS IDs, USARLOS DIRECTAMENTE
        if (empresa.departamento_id) {
            console.log('✅ Usando IDs de ubicación');
            
            // Establecer departamento
            this.setSelectValue('departamento_id', empresa.departamento_id);
            
            // Cargar y establecer provincia
            await this.cargarProvincias(empresa.departamento_id);
            if (empresa.provincia_id) {
                this.setSelectValue('provincia_id', empresa.provincia_id);
                
                // Cargar y establecer distrito
                await this.cargarDistritos(empresa.provincia_id);
                if (empresa.distrito_id) {
                    this.setSelectValue('distrito_id', empresa.distrito_id);
                }
            }
        } else {
            // ✅ FALLBACK: BUSCAR POR NOMBRES
            console.log('⚠️ Usando búsqueda por nombres');
            await this.buscarUbicacionPorNombres(empresa);
        }
        
    } catch (error) {
        console.error('❌ Error cargando ubicación:', error);
        // En caso de error, al menos cargar departamentos
        await this.cargarDepartamentos();
    }
}

// 📋 OBTENER PROVINCIAS (para búsqueda)
async obtenerProvincias(departamentoId) {
    try {
        const response = await this.fetchAPI('Empresa', 'api_provincias', { 
            departamento_id: departamentoId 
        });
        return response.success ? response.data : [];
    } catch (error) {
        console.error('Error obteniendo provincias:', error);
        return [];
    }
}

// 📋 OBTENER DISTRITOS (para búsqueda)
async obtenerDistritos(provinciaId) {
    try {
        const response = await this.fetchAPI('Empresa', 'api_distritos', { 
            provincia_id: provinciaId 
        });
        return response.success ? response.data : [];
    } catch (error) {
        console.error('Error obteniendo distritos:', error);
        return [];
    }
}

// 🔍 BÚSQUEDA DE UBICACIÓN POR NOMBRES (FALLBACK)
async buscarUbicacionPorNombres(empresa) {
    if (!empresa.departamento) return;
    
    // Cargar departamentos y buscar coincidencia
    const departamentos = await this.obtenerDepartamentos();
    const departamentoEncontrado = departamentos.find(d => 
        d.departamento === empresa.departamento
    );
    
    if (departamentoEncontrado) {
        this.setSelectValue('departamento_id', departamentoEncontrado.id);
        await this.cargarProvincias(departamentoEncontrado.id);
        
        // Buscar provincia
        const provincias = await this.obtenerProvincias(departamentoEncontrado.id);
        const provinciaEncontrada = provincias.find(p => 
            p.provincia === empresa.provincia
        );
        
        if (provinciaEncontrada) {
            this.setSelectValue('provincia_id', provinciaEncontrada.id);
            await this.cargarDistritos(provinciaEncontrada.id);
            
            // Buscar distrito
            const distritos = await this.obtenerDistritos(provinciaEncontrada.id);
            const distritoEncontrado = distritos.find(d => 
                d.distrito === empresa.distrito
            );
            
            if (distritoEncontrado) {
                this.setSelectValue('distrito_id', distritoEncontrado.id);
            }
        }
    }
}

    setValue(id, value) {
        const element = document.getElementById(id);
        if (element) element.value = value;
    }

    // 👁️ VER DETALLES DE EMPRESA
    async verEmpresa(id) {
        try {
            const response = await this.fetchAPI('Empresa', 'api_empresa', { id });
            
            if (response.success) {
                this.mostrarDetallesEmpresa(response.data);
            } else {
                throw new Error(response.error);
            }
        } catch (error) {
            this.mostrarError('Error al cargar detalles: ' + error.message);
        }
    }

    async cargarDepartamentos() {
    try {
        const response = await this.fetchAPI('Empresa', 'api_departamentos');
        if (response.success) {
            // Guardar el valor actual antes de actualizar
            const select = document.getElementById('departamento_id');
            const valorActual = select ? select.value : '';
            
            this.actualizarSelect('departamento_id', response.data, 'Seleccionar departamento');
            
            // Restaurar valor si existe
            if (valorActual && select) {
                select.value = valorActual;
            }
        }
    } catch (error) {
        console.error('Error cargando departamentos:', error);
    }
}

async cargarProvincias(departamentoId) {
    try {
        const response = await this.fetchAPI('Empresa', 'api_provincias', { departamento_id: departamentoId });
        if (response.success) {
            this.actualizarSelect('provincia_id', response.data, 'Seleccionar provincia');
            document.getElementById('provincia_id').disabled = false;
            
            // Limpiar distritos
            this.actualizarSelect('distrito_id', [], 'Seleccionar distrito');
            document.getElementById('distrito_id').disabled = true;
        }
    } catch (error) {
        console.error('Error cargando provincias:', error);
    }
}

async cargarDistritos(provinciaId) {
    try {
        const response = await this.fetchAPI('Empresa', 'api_distritos', { provincia_id: provinciaId });
        if (response.success) {
            this.actualizarSelect('distrito_id', response.data, 'Seleccionar distrito');
            document.getElementById('distrito_id').disabled = false;
        }
    } catch (error) {
        console.error('Error cargando distritos:', error);
    }
}

actualizarSelect(elementId, datos, textoDefault = 'Seleccionar') {
    const select = document.getElementById(elementId);
    if (!select) return;
    
    select.innerHTML = `<option value="">${textoDefault}</option>`;
    
    datos.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.departamento || item.provincia || item.distrito;
        select.appendChild(option);
    });
}

    mostrarDetallesEmpresa(empresa) {
        // Llenar modal de detalles con los datos de la empresa
        this.setTextContent('detalleModalTitulo', `Detalles de ${empresa.razon_social}`);
        this.setTextContent('detalleNombre', empresa.razon_social);
        this.setTextContent('detalleRuc', `RUC: ${empresa.ruc}`);
        this.setTextContent('detalleUbicacion', `${empresa.departamento}, ${empresa.distrito}`);
        this.setTextContent('detalleTelefono', empresa.telefono || 'N/A');
        this.setTextContent('detalleEmail', empresa.email);
        this.setTextContent('detalleDireccion', empresa.direccion_fiscal);
        this.setTextContent('detalleUbicacionCompleta', `${empresa.departamento} / ${empresa.provincia} / ${empresa.distrito}`);
        this.setTextContent('detalleRazonSocial', empresa.razon_social);
        this.setTextContent('detalleRepresentanteLegal', empresa.representante_legal || 'No especificado');

        // Estado
        const estadoElement = document.getElementById('detalleEstado');
        if (estadoElement) {
            estadoElement.textContent = empresa.estado;
            estadoElement.className = `badge-estado ${empresa.estado === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo'}`;
        }

        // Mostrar modal
        const modal = document.getElementById('detalleEmpresaModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    setTextContent(id, text) {
        const element = document.getElementById(id);
        if (element) element.textContent = text;
    }

    // 🗑️ ELIMINAR EMPRESA
  async eliminarEmpresa(id) {
    try {
        console.log('🗑️ Iniciando proceso de eliminación para empresa ID:', id);
        
        // ✅ OBTENER DATOS DE LA EMPRESA
        const empresaResponse = await this.fetchAPI('Empresa', 'api_empresa', { id });
        console.log('📊 Datos de empresa obtenidos:', empresaResponse);
        
        if (!empresaResponse.success) {
            throw new Error('No se pudieron obtener los datos de la empresa');
        }
        
        const empresa = empresaResponse.data;
        console.log('🏢 Empresa a eliminar:', empresa.razon_social, '- RUC:', empresa.ruc);
        
        // ✅ MOSTRAR CONFIRMACIÓN
        console.log('🔄 Mostrando confirmación...');
        const confirmado = await this.mostrarConfirmacionEliminacion(empresa);
        console.log('✅ Usuario confirmó:', confirmado);
        
        if (!confirmado) {
            this.mostrarNotificacion('info', 'Acción cancelada', 'La empresa no fue eliminada');
            return;
        }

        console.log('🚀 Procediendo con eliminación...');
        
        // ✅ ELIMINAR DIRECTAMENTE
        const response = await this.fetchAPI('Empresa', 'api_eliminar', { id });
        console.log('📨 Respuesta de eliminación:', response);
        
        if (response.success) {
            this.mostrarNotificacion('success', '¡Empresa eliminada!', 'La empresa ha sido eliminada permanentemente del sistema');
            await this.cargarEmpresas(); // Recargar lista
            await this.cargarEstadisticas(); // Actualizar dashboard
        } else {
            throw new Error(response.error);
        }
        
    } catch (error) {
        console.error('❌ Error completo al eliminar empresa:', error);
        
        // ✅ MENSAJES DE ERROR ESPECÍFICOS
        if (error.message.includes('prácticas asociadas')) {
            this.mostrarError('No se puede eliminar: ' + error.message + '. Primero elimine las prácticas asociadas.');
        } else {
            this.mostrarError('Error al eliminar empresa: ' + error.message);
        }
    }
}

// 🗑️ MOSTRAR CONFIRMACIÓN ESPECÍFICA PARA ELIMINACIÓN
mostrarConfirmacionEliminacion(empresa) {
    return new Promise((resolve) => {
        console.log('🎯 Creando modal de confirmación...');
        
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl p-6 w-96 max-w-md mx-4">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 p-3 rounded-full mr-4">
                        <i class="fas fa-trash text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary-blue">Eliminar Empresa</h3>
                </div>
                
                <p class="text-gray-600 mb-2">
                    <strong>Empresa:</strong> ${empresa.razon_social}
                </p>
                <p class="text-gray-600 mb-4">
                    <strong>RUC:</strong> ${empresa.ruc}
                </p>
                
                <p class="text-red-600 font-semibold mb-6">
                    ⚠️ ¿Estás seguro de que deseas eliminar permanentemente esta empresa?<br>
                    <span class="text-sm font-normal">Esta acción no se puede deshacer.</span>
                </p>
                
                <div class="flex justify-end space-x-3">
                    <button id="cancelarEliminacion" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300">
                        Cancelar
                    </button>
                    <button id="confirmarEliminacion" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-300 flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        Eliminar Permanentemente
                    </button>
                </div>
            </div>
        `;
        
        // ✅ AGREGAR EVENT LISTENERS DIRECTAMENTE
        const confirmarBtn = modal.querySelector('#confirmarEliminacion');
        const cancelarBtn = modal.querySelector('#cancelarEliminacion');
        
        console.log('🔘 Botones encontrados:', { confirmarBtn, cancelarBtn });
        
        const confirmarHandler = () => {
            console.log('✅ Usuario confirmó eliminación');
            document.body.removeChild(modal);
            resolve(true);
        };
        
        const cancelarHandler = () => {
            console.log('❌ Usuario canceló eliminación');
            document.body.removeChild(modal);
            resolve(false);
        };
        
        const clickFueraHandler = (e) => {
            if (e.target === modal) {
                console.log('👆 Usuario hizo clic fuera del modal');
                document.body.removeChild(modal);
                resolve(false);
            }
        };
        
        const keydownHandler = (e) => {
            if (e.key === 'Escape') {
                console.log('⌨️ Usuario presionó Escape');
                document.body.removeChild(modal);
                resolve(false);
            }
            if (e.key === 'Enter') {
                console.log('⌨️ Usuario presionó Enter');
                document.body.removeChild(modal);
                resolve(true);
            }
        };
        
        // Asignar event listeners
        confirmarBtn.addEventListener('click', confirmarHandler);
        cancelarBtn.addEventListener('click', cancelarHandler);
        modal.addEventListener('click', clickFueraHandler);
        document.addEventListener('keydown', keydownHandler);
        
        // Limpiar event listeners cuando se remueva el modal
        modal.addEventListener('remove', () => {
            confirmarBtn.removeEventListener('click', confirmarHandler);
            cancelarBtn.removeEventListener('click', cancelarHandler);
            modal.removeEventListener('click', clickFueraHandler);
            document.removeEventListener('keydown', keydownHandler);
        });
        
        console.log('📋 Agregando modal al DOM...');
        document.body.appendChild(modal);
        console.log('✅ Modal agregado correctamente');
        
        // Enfocar el botón de cancelar por seguridad
        cancelarBtn.focus();
    });
}

    // 💾 GUARDAR EMPRESA (CREAR/ACTUALIZAR)
    async guardarEmpresa(formData) {
        try {
            const response = await this.fetchAPI('Empresa', 'api_guardar', null, {
                method: 'POST',
                body: JSON.stringify(formData)
            });

            if (response.success) {
                this.mostrarNotificacion('success', '¡Éxito!', response.message);
                this.cerrarModalEmpresa();
                await this.cargarEmpresas(); // Recargar lista
                await this.cargarEstadisticas(); // Actualizar dashboard
            } else {
                throw new Error(response.error);
            }
        } catch (error) {
            this.mostrarError('Error al guardar empresa: ' + error.message);
        }
    }

    // 🔍 APLICAR FILTROS
   aplicarFiltros() {
    const filtros = {
        busqueda: document.getElementById('buscarEmpresa')?.value || '',
        departamento: document.getElementById('filtroDepartamento')?.value || 'all',
        estado: document.getElementById('filtroEstado')?.value || 'all'
    };

    console.log('Aplicando filtros:', filtros);
    this.configPaginacion.paginaActual = 1;
    this.cargarEmpresas(filtros);
}

    // 📄 PAGINACIÓN
    actualizarContadores() {
        const inicio = (this.configPaginacion.paginaActual - 1) * this.configPaginacion.elementosPorPagina + 1;
        const fin = Math.min(inicio + this.configPaginacion.elementosPorPagina - 1, this.configPaginacion.totalElementos);
        
        if (this.configPaginacion.vistaActual === 'tabla') {
            this.setTextContent('empresas-mostradas', `${inicio}-${fin}`);
            this.setTextContent('empresas-totales', this.configPaginacion.totalElementos);
            this.setTextContent('info-paginacion', 
                `Página ${this.configPaginacion.paginaActual} de ${Math.ceil(this.configPaginacion.totalElementos / this.configPaginacion.elementosPorPagina)}`);
        } else {
            this.setTextContent('tarjetas-mostradas', `${inicio}-${fin}`);
            this.setTextContent('tarjetas-totales', this.configPaginacion.totalElementos);
        }
    }

    actualizarPaginacion() {
        const totalPaginas = Math.ceil(this.configPaginacion.totalElementos / this.configPaginacion.elementosPorPagina);
        const paginacionId = this.configPaginacion.vistaActual === 'tabla' ? 'paginacion' : 'paginacion-tarjetas';
        const paginacion = document.getElementById(paginacionId);
        
        if (!paginacion) return;
        
        if (totalPaginas <= 1) {
            paginacion.innerHTML = '';
            return;
        }

        let html = '';

        // Botón anterior
        html += `<button class="px-3 py-1 rounded-lg border ${this.configPaginacion.paginaActual === 1 ? 
            'bg-gray-100 text-gray-400 cursor-not-allowed' : 
            'bg-white text-gray-700 hover:bg-gray-50'}" 
            ${this.configPaginacion.paginaActual === 1 ? 'disabled' : ''}
            onclick="empresaManager.cambiarPagina(${this.configPaginacion.paginaActual - 1})">
            <i class="fas fa-chevron-left"></i>
        </button>`;

        // Números de página
        const inicioPagina = Math.max(1, this.configPaginacion.paginaActual - 2);
        const finPagina = Math.min(totalPaginas, this.configPaginacion.paginaActual + 2);

        for (let i = inicioPagina; i <= finPagina; i++) {
            html += `<button class="px-3 py-1 rounded-lg border ${i === this.configPaginacion.paginaActual ? 
                'bg-primary-blue text-white' : 
                'bg-white text-gray-700 hover:bg-gray-50'}" 
                onclick="empresaManager.cambiarPagina(${i})">
                ${i}
            </button>`;
        }

        // Botón siguiente
        html += `<button class="px-3 py-1 rounded-lg border ${this.configPaginacion.paginaActual === totalPaginas ? 
            'bg-gray-100 text-gray-400 cursor-not-allowed' : 
            'bg-white text-gray-700 hover:bg-gray-50'}" 
            ${this.configPaginacion.paginaActual === totalPaginas ? 'disabled' : ''}
            onclick="empresaManager.cambiarPagina(${this.configPaginacion.paginaActual + 1})">
            <i class="fas fa-chevron-right"></i>
        </button>`;

        paginacion.innerHTML = html;
    }

    cambiarPagina(pagina) {
        this.configPaginacion.paginaActual = pagina;
        this.renderizarEmpresas();
    }

    restaurarGraficos() {
    console.log('🔄 Restaurando gráficos...');
    
    // Destruir gráficos existentes
    this.destruirGraficos();
    
    // Volver a cargar estadísticas para regenerar gráficos
    this.cargarEstadisticas().then(() => {
        console.log('✅ Gráficos restaurados correctamente');
    }).catch(error => {
        console.error('❌ Error restaurando gráficos:', error);
    });
}

    // 🎛️ CONFIGURAR EVENT LISTENERS
    setupEventListeners() {
    // ✅ BUSQUEDA EN TIEMPO REAL MEJORADA
    let searchTimeout;
    const buscarInput = document.getElementById('buscarEmpresa');
    if (buscarInput) {
        buscarInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.trim();
            console.log('Búsqueda ingresada:', searchTerm);
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.aplicarFiltros();
            }, 500); // Esperar 500ms después de que el usuario deje de escribir
        });
        
        // ✅ También buscar al presionar Enter
        buscarInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                clearTimeout(searchTimeout);
                this.aplicarFiltros();
            }
        });
    }

    // ✅ FILTROS DE DEPARTAMENTO Y ESTADO
    this.addChangeListener('filtroDepartamento', () => {
        console.log('Departamento cambiado');
        this.aplicarFiltros();
    });

    this.addChangeListener('filtroEstado', () => {
        console.log('Estado cambiado');
        this.aplicarFiltros();
    });

    // ✅ BOTONES DE ACCIÓN
    this.addClickListener('btnNuevaEmpresa', () => this.abrirModalEditar());
    this.addClickListener('btnRefrescar', () => {
        // Limpiar búsqueda y recargar
        if (buscarInput) buscarInput.value = '';
        this.cargarDatosIniciales();
    });

     // ✅ DROPDOWN EXPORTAR
    const btnExportar = document.getElementById('btnExportar');
    const exportarDropdown = document.getElementById('exportarDropdown');
    
    if (btnExportar && exportarDropdown) {
        btnExportar.addEventListener('click', (e) => {
            e.stopPropagation();
            exportarDropdown.classList.toggle('hidden');
        });
        
        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', () => {
            exportarDropdown.classList.add('hidden');
        });
    }

    // ✅ RESTAURAR GRÁFICOS DESPUÉS DE EXPORTAR
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            // La pestaña volvió a ser visible (posiblemente después de exportar)
            setTimeout(() => {
                this.restaurarGraficos();
            }, 1000);
        }
    });

    // ✅ CAMBIO DE VISTA
    this.addClickListener('btnVistaTabla', () => this.cambiarVista('tabla'));
    this.addClickListener('btnVistaTarjetas', () => this.cambiarVista('tarjetas'));
}

    addChangeListener(id, callback) {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', callback);
        }
    }

    addClickListener(id, callback) {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('click', callback);
        }
    }

    // 🔍 MOSTRAR INDICADOR DE BÚSQUEDA
mostrarIndicadorBusqueda(mostrar) {
    const buscarInput = document.getElementById('buscarEmpresa');
    if (!buscarInput) return;
    
    const parent = buscarInput.parentElement;
    if (mostrar) {
        // Agregar icono de carga
        if (!parent.querySelector('.search-loading')) {
            const loadingIcon = document.createElement('div');
            loadingIcon.className = 'search-loading absolute right-3 top-2.5';
            loadingIcon.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-500"></i>';
            parent.appendChild(loadingIcon);
        }
    } else {
        // Remover icono de carga
        const loadingIcon = parent.querySelector('.search-loading');
        if (loadingIcon) {
            loadingIcon.remove();
        }
    }
}

    setupModalEvents() {
        // Modal de empresa
        const formEmpresa = document.getElementById('formEmpresa');
        if (formEmpresa) {
            formEmpresa.addEventListener('submit', (e) => {
                e.preventDefault();
                this.guardarEmpresaDesdeFormulario();
            });
        }

        this.addClickListener('cerrarModal', () => this.cerrarModalEmpresa());
        this.addClickListener('cancelarForm', () => this.cerrarModalEmpresa());
        this.setupRucValidation();

        // Modal de detalles
        this.addClickListener('cerrarDetalleModal', () => this.cerrarDetalleModal());
        this.addClickListener('cerrarDetalleBtn', () => this.cerrarDetalleModal());
        this.addClickListener('editarDesdeDetalle', () => this.editarDesdeDetalle());
        this.addClickListener('imprimirDetalle', () => window.print());

        this.addChangeListener('departamento_id', (e) => {
        const departamentoId = e.target.value;
        if (departamentoId) {
            this.cargarProvincias(departamentoId);
        } else {
            this.actualizarSelect('provincia_id', [], 'Primero seleccione departamento');
            this.actualizarSelect('distrito_id', [], 'Primero seleccione provincia');
            document.getElementById('provincia_id').disabled = true;
            document.getElementById('distrito_id').disabled = true;
        }
    });

    this.addChangeListener('provincia_id', (e) => {
        const provinciaId = e.target.value;
        if (provinciaId) {
            this.cargarDistritos(provinciaId);
        } else {
            this.actualizarSelect('distrito_id', [], 'Primero seleccione provincia');
            document.getElementById('distrito_id').disabled = true;
        }
    });

        // Cerrar modales al hacer clic fuera
        const empresaModal = document.getElementById('empresaModal');
        if (empresaModal) {
            empresaModal.addEventListener('click', (e) => {
                if (e.target === e.currentTarget) this.cerrarModalEmpresa();
            });
        }

        const detalleModal = document.getElementById('detalleEmpresaModal');
        if (detalleModal) {
            detalleModal.addEventListener('click', (e) => {
                if (e.target === e.currentTarget) this.cerrarDetalleModal();
            });
        }
    }

    // 🔄 MÉTODOS AUXILIARES
    cambiarVista(vista) {
        this.configPaginacion.vistaActual = vista;
        this.configPaginacion.paginaActual = 1;

        const vistaTabla = document.getElementById('vistaTabla');
        const vistaTarjetas = document.getElementById('vistaTarjetas');
        const btnVistaTabla = document.getElementById('btnVistaTabla');
        const btnVistaTarjetas = document.getElementById('btnVistaTarjetas');

        if (vista === 'tabla') {
            if (vistaTabla) vistaTabla.classList.remove('hidden');
            if (vistaTarjetas) vistaTarjetas.classList.add('hidden');
            if (btnVistaTabla) {
                btnVistaTabla.classList.add('bg-white', 'shadow-sm', 'text-primary-blue');
                btnVistaTabla.classList.remove('text-gray-600');
            }
            if (btnVistaTarjetas) {
                btnVistaTarjetas.classList.remove('bg-white', 'shadow-sm', 'text-primary-blue');
                btnVistaTarjetas.classList.add('text-gray-600');
            }
        } else {
            if (vistaTabla) vistaTabla.classList.add('hidden');
            if (vistaTarjetas) vistaTarjetas.classList.remove('hidden');
            if (btnVistaTarjetas) {
                btnVistaTarjetas.classList.add('bg-white', 'shadow-sm', 'text-primary-blue');
                btnVistaTarjetas.classList.remove('text-gray-600');
            }
            if (btnVistaTabla) {
                btnVistaTabla.classList.remove('bg-white', 'shadow-sm', 'text-primary-blue');
                btnVistaTabla.classList.add('text-gray-600');
            }
        }

        this.renderizarEmpresas();
    }

    guardarEmpresaDesdeFormulario() {
    const formData = {
        id: document.getElementById('empresaId')?.value || null,
        ruc: document.getElementById('ruc')?.value || '',
        razon_social: document.getElementById('razon_social')?.value || '',
        representante_legal: document.getElementById('representante_legal')?.value || '',
        direccion_fiscal: document.getElementById('direccion_fiscal')?.value || '',
        telefono: document.getElementById('telefono')?.value || '',
        email: document.getElementById('email')?.value || '',
        departamento_id: document.getElementById('departamento_id')?.value || '',
        provincia_id: document.getElementById('provincia_id')?.value || '',
        distrito_id: document.getElementById('distrito_id')?.value || '',
        estado: document.getElementById('estado')?.value || 'ACTIVO'
    };

    // Validaciones básicas
    if (!formData.ruc || !formData.razon_social || !formData.direccion_fiscal || !formData.email) {
        this.mostrarError('Por favor complete todos los campos obligatorios (*)');
        return;
    }

    // Validar ubicación
    if (!formData.departamento_id || !formData.provincia_id || !formData.distrito_id) {
        this.mostrarError('Por favor seleccione departamento, provincia y distrito');
        return;
    }

    console.log('Datos a guardar:', formData);
    this.guardarEmpresa(formData);
}

    editarDesdeDetalle() {
        this.cerrarDetalleModal();
        const empresaId = document.getElementById('empresaId')?.value;
        if (empresaId) {
            this.abrirModalEditar(empresaId);
        }
    }

    cerrarModalEmpresa() {
        const modal = document.getElementById('empresaModal');
        if (modal) modal.classList.add('hidden');
    }

    cerrarDetalleModal() {
        const modal = document.getElementById('detalleEmpresaModal');
        if (modal) modal.classList.add('hidden');
    }

    // 📤 EXPORTAR DATOS - COMPLETO
async exportarDatos() {
    try {
        this.mostrarLoading(true);
        
        const filtros = {
            busqueda: document.getElementById('buscarEmpresa')?.value || '',
            departamento: document.getElementById('filtroDepartamento')?.value || 'all',
            estado: document.getElementById('filtroEstado')?.value || 'all'
        };
        
        const params = new URLSearchParams();
        if (filtros.busqueda) params.append('busqueda', filtros.busqueda);
        if (filtros.departamento !== 'all') params.append('departamento', filtros.departamento);
        if (filtros.estado !== 'all') params.append('estado', filtros.estado);
        
        const url = `index.php?c=Empresa&a=exportar&${params.toString()}`;
        
        // ✅ SOLUCIÓN: Usar window.open en lugar de crear un link
        const exportWindow = window.open(url, '_blank');
        
        // Verificar si la ventana se bloqueó
        if (!exportWindow || exportWindow.closed || typeof exportWindow.closed == 'undefined') {
            this.mostrarNotificacion('warning', 'Popup bloqueado', 'Por favor permite popups para descargar el archivo', 5000);
            
            // ✅ ALTERNATIVA: Usar iframe
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = url;
            document.body.appendChild(iframe);
            setTimeout(() => document.body.removeChild(iframe), 5000);
        }
        
        this.mostrarNotificacion('success', 'Exportación iniciada', 'El archivo Excel se está generando', 3000);
        
    } catch (error) {
        console.error('❌ Error exportando datos:', error);
        this.mostrarError('Error al exportar: ' + error.message);
    } finally {
        this.mostrarLoading(false);
    }
}

// 📊 EXPORTAR ESTADÍSTICAS - USANDO EMPRESACONTROLLER
async exportarEstadisticas() {
    try {
        this.mostrarLoading(true);
        
        const url = `index.php?c=Empresa&a=exportarEstadisticas`;
        
        // ✅ SOLUCIÓN: Usar window.open
        const exportWindow = window.open(url, '_blank');
        
        if (!exportWindow || exportWindow.closed || typeof exportWindow.closed == 'undefined') {
            this.mostrarNotificacion('warning', 'Popup bloqueado', 'Por favor permite popups para descargar el reporte', 5000);
            
            // ✅ ALTERNATIVA: Usar iframe
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = url;
            document.body.appendChild(iframe);
            setTimeout(() => document.body.removeChild(iframe), 5000);
        }
        
        this.mostrarNotificacion('success', 'Reporte iniciado', 'El reporte de estadísticas se está generando', 3000);
        
    } catch (error) {
        console.error('❌ Error exportando estadísticas:', error);
        this.mostrarError('Error al exportar estadísticas: ' + error.message);
    } finally {
        this.mostrarLoading(false);
    }
}

    async fetchAPI(controller, action, params = null, options = {}) {
    let url = `index.php?c=${controller}&a=${action}`;
    
    if (params) {
        const searchParams = new URLSearchParams(params);
        url += `&${searchParams.toString()}`;
    }

    console.log('🌐 Fetch URL:', url);

    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
        },
        ...options
    };

    try {
        console.log('🔄 Realizando fetch...');
        const response = await fetch(url, defaultOptions);
        console.log('📡 Status de respuesta:', response.status, response.statusText);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('📨 Datos parseados:', data);
        return data;
        
    } catch (error) {
        console.error('❌ Error en fetchAPI:', error);
        throw error;
    }
}

    mostrarLoading(mostrar) {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            if (mostrar) {
                overlay.classList.add('show');
            } else {
                overlay.classList.remove('show');
            }
        }
    }

    mostrarError(mensaje) {
        console.error('Error:', mensaje);
        this.mostrarNotificacion('error', 'Error', mensaje);
    }

    // ==============================
    // SISTEMA DE NOTIFICACIONES
    // ==============================
    
    mostrarNotificacion(tipo, titulo, mensaje, duracion = 5000) {
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
    
    mostrarConfirmacion(titulo, mensaje, tipo = 'warning') {
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

    // 🔥 NUEVO: LIMPIAR TODO AL SALIR/CAMBIAR PÁGINA
    cleanup() {
        console.log('Limpiando recursos...');
        this.destruirGraficos();
    }
}

// 🚀 INICIALIZAR LA APLICACIÓN CON VERIFICACIÓN
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando EmpresaManager...');
    window.empresaManager = new EmpresaManager();
});

// 🔥 NUEVO: LIMPIAR RECURSOS AL SALIR DE LA PÁGINA
window.addEventListener('beforeunload', function() {
    if (window.empresaManager) {
        window.empresaManager.cleanup();
    }
});