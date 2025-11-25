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
        
        // Esperar a que el DOM esté completamente cargado
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        console.log('Inicializando EmpresaManager...');
        this.cargarDatosIniciales();
        this.setupEventListeners();
        this.setupModalEvents();
    }

    // 🔄 CARGAR DATOS INICIALES
    async cargarDatosIniciales() {
        try {
            console.log('Cargando datos iniciales...');
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
            console.log('Cargando empresas con filtros:', filtros);
            
            const params = new URLSearchParams();
            if (filtros.busqueda) params.append('busqueda', filtros.busqueda);
            if (filtros.sector && filtros.sector !== 'all') params.append('sector', filtros.sector);
            if (filtros.validado && filtros.validado !== 'all') params.append('validado', filtros.validado);
            if (filtros.estado && filtros.estado !== 'all') params.append('estado', filtros.estado);

            const url = `index.php?c=Empresa&a=api_empresas&${params.toString()}`;
            console.log('URL de empresas:', url);
            
            const response = await fetch(url);
            const data = await response.json();

            console.log('Respuesta de empresas:', data);

            if (data.success) {
                this.empresas = data.data;
                this.configPaginacion.totalElementos = data.total;
                this.renderizarEmpresas();
            } else {
                throw new Error(data.error || 'Error desconocido al cargar empresas');
            }
        } catch (error) {
            console.error('Error cargando empresas:', error);
            this.mostrarError('Error al cargar empresas: ' + error.message);
        } finally {
            this.mostrarLoading(false);
        }
    }

    // 🎯 ACTUALIZAR DASHBOARD - CON VERIFICACIÓN DE ELEMENTOS
    actualizarDashboard(estadisticas) {
        console.log('Actualizando dashboard con:', estadisticas);
        
        // Verificar y actualizar elementos solo si existen
        const elementos = {
            'total-empresas': estadisticas.total_empresas,
            'empresas-validadas': estadisticas.empresas_validadas,
            'empresas-practicas': estadisticas.empresas_con_practicas
        };

        Object.keys(elementos).forEach(id => {
            const elemento = document.getElementById(id);
            if (elemento) {
                elemento.textContent = elementos[id];
            } else {
                console.warn(`Elemento #${id} no encontrado`);
            }
        });

        // Contar sectores únicos
        const sectoresCount = estadisticas.distribucion_sectores?.length || 0;
        const sectoresElement = document.getElementById('sectores-count');
        if (sectoresElement) {
            sectoresElement.textContent = sectoresCount;
        }

        // Actualizar textos descriptivos si existen
        this.actualizarTextoSiExiste('empresas-texto', `${estadisticas.total_empresas} registradas`);
        this.actualizarTextoSiExiste('validadas-texto', `${estadisticas.empresas_validadas} validadas`);
        this.actualizarTextoSiExiste('practicas-texto', `${estadisticas.empresas_con_practicas} con prácticas`);
        this.actualizarTextoSiExiste('sectores-texto', `${sectoresCount} sectores`);
    }

    actualizarTextoSiExiste(id, texto) {
        const elemento = document.getElementById(id);
        if (elemento) {
            elemento.textContent = texto;
        }
    }

    // 📈 INICIALIZAR GRÁFICOS CON VERIFICACIÓN
    inicializarGraficos(estadisticas) {
        console.log('Inicializando gráficos...');
        
        // Verificar si los canvas existen antes de inicializar
        const canvasSectores = document.getElementById('sectoresChart');
        const canvasValidacion = document.getElementById('validacionChart');
        
        if (canvasSectores) {
            this.inicializarGraficoSectores(estadisticas.distribucion_sectores);
        } else {
            console.warn('Canvas sectoresChart no encontrado');
        }
        
        if (canvasValidacion) {
            this.inicializarGraficoValidacion(estadisticas);
        } else {
            console.warn('Canvas validacionChart no encontrado');
        }
    }

    inicializarGraficoSectores(distribucionSectores) {
        const ctx = document.getElementById('sectoresChart').getContext('2d');
        
        // Verificar que hay datos
        if (!distribucionSectores || distribucionSectores.length === 0) {
            console.warn('No hay datos para el gráfico de sectores');
            return;
        }
        
        const labels = distribucionSectores.map(item => item.sector);
        const data = distribucionSectores.map(item => item.cantidad);
        
        new Chart(ctx, {
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

    inicializarGraficoValidacion(estadisticas) {
        const ctx = document.getElementById('validacionChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Validadas', 'No Validadas'],
                datasets: [{
                    data: [
                        estadisticas.empresas_validadas || 0,
                        (estadisticas.total_empresas || 0) - (estadisticas.empresas_validadas || 0)
                    ],
                    backgroundColor: ['#198754', '#6c757d'],
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

    // 🏢 RENDERIZAR EMPRESAS (TABLA O TARJETAS) - CON VERIFICACIÓN
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
                        No se encontraron empresas que coincidan con los filtros
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
                                ${empresa.nombre_comercial || empresa.razon_social}
                            </div>
                            <div class="text-xs text-gray-500">
                                ${empresa.razon_social}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${empresa.ruc}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="sector-badge sector-${this.getSectorClass(empresa.sector)}">${empresa.sector}</span>
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
                    ${empresa.validado == 1 ? '<span class="badge-estado badge-validado ml-2">Validado</span>' : ''}
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
                <h3 class="text-lg font-bold text-primary-blue mb-2">${empresa.nombre_comercial || empresa.razon_social}</h3>
                <div class="flex items-center text-sm text-gray-500 mb-3">
                    <i class="fas fa-id-card mr-2"></i>
                    <span>RUC: ${empresa.ruc}</span>
                </div>
                <div class="mb-4">
                    <span class="sector-badge sector-${this.getSectorClass(empresa.sector)}">${empresa.sector}</span>
                </div>
                <div class="text-sm text-gray-600 mb-4">
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
                    ${empresa.validado == 1 ? 
                        '<span class="badge-estado badge-validado">Validado</span>' : 
                        '<span class="badge-estado badge-pendiente">No Validado</span>'
                    }
                </div>
            </div>
        `).join('');

        contenedor.innerHTML = '';
        contenedor.appendChild(grid);
        
        this.setupActionButtons();
    }

    // 🔘 CONFIGURAR BOTONES DE ACCIÓN
    setupActionButtons() {
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
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                this.eliminarEmpresa(id);
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
        
        if (id) {
            // Modo edición
            titulo.textContent = 'Editar Empresa';
            await this.cargarDatosEmpresa(id, form);
        } else {
            // Modo creación
            titulo.textContent = 'Nueva Empresa';
            form.reset();
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
                
                this.setValue('empresaId', empresa.id);
                this.setValue('ruc', empresa.ruc);
                this.setValue('razon_social', empresa.razon_social);
                this.setValue('nombre_comercial', empresa.nombre_comercial || '');
                this.setValue('direccion_fiscal', empresa.direccion_fiscal);
                this.setValue('telefono', empresa.telefono || '');
                this.setValue('email', empresa.email);
                this.setValue('sector', empresa.sector);
                this.setValue('ubigeo', empresa.ubigeo || '');
                this.setValue('departamento', empresa.departamento || '');
                this.setValue('provincia', empresa.provincia || '');
                this.setValue('distrito', empresa.distrito || '');
                this.setValue('condicion_sunat', empresa.condicion_sunat || '');
                this.setValue('estado', empresa.estado);
                
                this.setChecked('validado', empresa.validado == 1);
                this.setChecked('registro_manual', empresa.registro_manual == 1);
            } else {
                throw new Error(response.error);
            }
        } catch (error) {
            this.mostrarError('Error al cargar datos de la empresa: ' + error.message);
        }
    }

    setValue(id, value) {
        const element = document.getElementById(id);
        if (element) element.value = value;
    }

    setChecked(id, checked) {
        const element = document.getElementById(id);
        if (element) element.checked = checked;
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

    mostrarDetallesEmpresa(empresa) {
        // Llenar modal de detalles con los datos de la empresa
        this.setTextContent('detalleModalTitulo', `Detalles de ${empresa.nombre_comercial || empresa.razon_social}`);
        this.setTextContent('detalleNombre', empresa.nombre_comercial || empresa.razon_social);
        this.setTextContent('detalleRuc', `RUC: ${empresa.ruc}`);
        this.setTextContent('detalleUbicacion', `${empresa.departamento}, ${empresa.distrito}`);
        this.setTextContent('detalleTelefono', empresa.telefono || 'N/A');
        this.setTextContent('detalleEmail', empresa.email);
        this.setTextContent('detalleDireccion', empresa.direccion_fiscal);
        this.setTextContent('detalleUbigeo', empresa.ubigeo || 'N/A');
        this.setTextContent('detalleRazonSocial', empresa.razon_social);
        this.setTextContent('detalleNombreComercial', empresa.nombre_comercial || 'No especificado');
        this.setTextContent('detalleCondicionSunat', empresa.condicion_sunat || 'No especificado');
        this.setTextContent('detalleRegistroManual', empresa.registro_manual == 1 ? 'Sí' : 'No');

        // Sector
        const detalleSector = document.getElementById('detalleSector');
        if (detalleSector) {
            detalleSector.className = `sector-badge sector-${this.getSectorClass(empresa.sector)}`;
            detalleSector.textContent = empresa.sector;
        }

        // Estado
        const estadoElement = document.getElementById('detalleEstado');
        if (estadoElement) {
            estadoElement.textContent = empresa.estado;
            estadoElement.className = `badge-estado ${empresa.estado === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo'}`;
        }

        // Validado
        const validadoElement = document.getElementById('detalleValidado');
        if (validadoElement) {
            validadoElement.textContent = empresa.validado == 1 ? 'Validado' : 'No Validado';
            validadoElement.className = `badge-estado ${empresa.validado == 1 ? 'badge-validado' : 'badge-pendiente'}`;
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
        if (!confirm('¿Estás seguro de que deseas eliminar esta empresa?')) {
            return;
        }

        try {
            const response = await this.fetchAPI('Empresa', 'api_eliminar', { id });
            
            if (response.success) {
                this.mostrarExito('Empresa eliminada correctamente');
                this.cargarEmpresas(); // Recargar lista
                this.cargarEstadisticas(); // Actualizar dashboard
            } else {
                throw new Error(response.error);
            }
        } catch (error) {
            this.mostrarError('Error al eliminar empresa: ' + error.message);
        }
    }

    // 💾 GUARDAR EMPRESA (CREAR/ACTUALIZAR)
    async guardarEmpresa(formData) {
        try {
            const response = await this.fetchAPI('Empresa', 'api_guardar', null, {
                method: 'POST',
                body: JSON.stringify(formData)
            });

            if (response.success) {
                this.mostrarExito(response.message);
                this.cerrarModalEmpresa();
                this.cargarEmpresas(); // Recargar lista
                this.cargarEstadisticas(); // Actualizar dashboard
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
            sector: document.getElementById('filtroSector')?.value || 'all',
            validado: document.getElementById('filtroValidado')?.value || 'all',
            estado: document.getElementById('filtroEstado')?.value || 'all'
        };

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

    // 🎛️ CONFIGURAR EVENT LISTENERS
    setupEventListeners() {
        // Búsqueda en tiempo real
        let timeout;
        const buscarInput = document.getElementById('buscarEmpresa');
        if (buscarInput) {
            buscarInput.addEventListener('input', (e) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => this.aplicarFiltros(), 500);
            });
        }

        // Filtros
        this.addChangeListener('filtroSector', () => this.aplicarFiltros());
        this.addChangeListener('filtroValidado', () => this.aplicarFiltros());
        this.addChangeListener('filtroEstado', () => this.aplicarFiltros());

        // Cambio de vista
        this.addClickListener('btnVistaTabla', () => this.cambiarVista('tabla'));
        this.addClickListener('btnVistaTarjetas', () => this.cambiarVista('tarjetas'));

        // Botones de acción
        this.addClickListener('btnNuevaEmpresa', () => this.abrirModalEditar());
        this.addClickListener('btnRefrescar', () => this.cargarDatosIniciales());
        this.addClickListener('btnExportar', () => this.exportarDatos());
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

        // Modal de detalles
        this.addClickListener('cerrarDetalleModal', () => this.cerrarDetalleModal());
        this.addClickListener('cerrarDetalleBtn', () => this.cerrarDetalleModal());
        this.addClickListener('editarDesdeDetalle', () => this.editarDesdeDetalle());
        this.addClickListener('imprimirDetalle', () => window.print());

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
            nombre_comercial: document.getElementById('nombre_comercial')?.value || '',
            direccion_fiscal: document.getElementById('direccion_fiscal')?.value || '',
            telefono: document.getElementById('telefono')?.value || '',
            email: document.getElementById('email')?.value || '',
            sector: document.getElementById('sector')?.value || '',
            validado: document.getElementById('validado')?.checked || false,
            registro_manual: document.getElementById('registro_manual')?.checked || false,
            estado: document.getElementById('estado')?.value || 'ACTIVO',
            condicion_sunat: document.getElementById('condicion_sunat')?.value || '',
            ubigeo: document.getElementById('ubigeo')?.value || '',
            departamento: document.getElementById('departamento')?.value || '',
            provincia: document.getElementById('provincia')?.value || '',
            distrito: document.getElementById('distrito')?.value || ''
        };

        // Validaciones básicas
        if (!formData.ruc || !formData.razon_social || !formData.direccion_fiscal || !formData.email || !formData.sector) {
            this.mostrarError('Por favor complete todos los campos obligatorios (*)');
            return;
        }

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

    getSectorClass(sector) {
        const sectores = {
            'TECNOLOGÍA': 'tecnologia',
            'CONSTRUCCIÓN': 'industria',
            'SERVICIOS': 'servicios',
            'COMERCIO': 'comercio',
            'INFORMÁTICA': 'tecnologia',
            'DESARROLLO SOFTWARE': 'tecnologia'
        };
        return sectores[sector?.toUpperCase()] || 'otros';
    }

    // 📤 EXPORTAR DATOS
    exportarDatos() {
        this.mostrarExito('Función de exportación activada. En una aplicación real, se descargaría un archivo Excel/CSV.');
    }

    // 🔧 UTILIDADES MEJORADAS
    async fetchAPI(controller, action, params = null, options = {}) {
        let url = `index.php?c=${controller}&a=${action}`;
        
        if (params) {
            const searchParams = new URLSearchParams(params);
            url += `&${searchParams.toString()}`;
        }

        console.log(`Fetching: ${url}`);

        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
            },
            ...options
        };

        try {
            const response = await fetch(url, defaultOptions);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error en fetchAPI:', error);
            throw error;
        }
    }

    mostrarLoading(mostrar) {
        console.log(mostrar ? 'Mostrando loading...' : 'Ocultando loading...');
    }

    mostrarError(mensaje) {
        console.error('Error:', mensaje);
        alert('Error: ' + mensaje);
    }

    mostrarExito(mensaje) {
        console.log('Éxito:', mensaje);
        alert('Éxito: ' + mensaje);
    }
}

// 🚀 INICIALIZAR LA APLICACIÓN CON VERIFICACIÓN
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando EmpresaManager...');
    window.empresaManager = new EmpresaManager();
});