// Módulos functionality
class ModulosDashboard {
    constructor() {
        this.data = null;
        this.configPaginacion = {
            paginaActual: 1,
            elementosPorPagina: 5,
            totalElementos: 0
        };
        this.init();
    }
    
    init() {
        this.cargarDatos();
        this.inicializarEventos();
    }
    
    async cargarDatos() {
    try {
        console.log('🚀 Iniciando carga de datos...');
        this.mostrarLoading(true);
        
        // 🔥 URL SIMPLE - ESTO DEBE FUNCIONAR
        const url = 'index.php?c=Modulos&a=getModulosData';
        console.log('📡 URL de petición:', url);
        
        // 🔥 Agregar timestamp para evitar cache
        const timestamp = new Date().getTime();
        const urlConCache = `${url}&_=${timestamp}`;
        
        console.log('⏳ Realizando petición fetch...');
        const response = await fetch(urlConCache, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin'
        });
        
        console.log('✅ Respuesta recibida. Status:', response.status);
        
        if (!response.ok) {
            throw new Error(`Error HTTP ${response.status}: ${response.statusText}`);
        }
        
        const text = await response.text();
        console.log('📄 Respuesta (primeros 500 chars):', text.substring(0, 500));
        
        let result;
        try {
            result = JSON.parse(text);
        } catch (parseError) {
            console.error('❌ Error parseando JSON:', parseError);
            console.error('Texto completo:', text);
            throw new Error('Respuesta no es JSON válido');
        }
        
        if (result.success) {
            this.data = result.data;
            console.log('🎉 Datos cargados exitosamente');
            console.log('📊 Estadísticas:', this.data.estadisticas);
            console.log('👨‍🎓 Estudiantes:', this.data.estudiantes?.length || 0);
            console.log('📚 Módulos:', this.data.modulos?.length || 0);
            console.log('🏢 Empresas:', this.data.empresas?.length || 0);
            
            this.actualizarDashboard();
        } else {
            console.error('❌ Error del servidor:', result.error);
            throw new Error(result.error || 'Error desconocido del servidor');
        }
    } catch (error) {
        console.error('💥 Error crítico al cargar datos:', error);
        this.mostrarError('Error: ' + error.message);
    } finally {
        this.mostrarLoading(false);
    }
}
    
    actualizarDashboard() {
        try {
            this.actualizarEstadisticas();
            this.actualizarFiltros();
            this.renderizarVistaProgreso();
            this.inicializarGraficos();
        } catch (error) {
            console.error('Error actualizando dashboard:', error);
        }
    }
    
    actualizarEstadisticas() {
        if (!this.data || !this.data.estadisticas) {
            console.warn('No hay datos de estadísticas');
            return;
        }
        
        const stats = this.data.estadisticas;
        
        // Animación de contadores
        this.animarContador('total-estudiantes', stats.total_estudiantes || 0);
        this.animarContador('modulos-activos', stats.modulos_activos || 0);
        this.animarContador('modulos-finalizados', stats.modulos_finalizados || 0);
        this.animarContador('progreso-promedio', stats.progreso_promedio || 0);
        
        // Actualizar textos descriptivos
        document.getElementById('estudiantes-texto').textContent = `${stats.total_estudiantes || 0} con módulos activos`;
        document.getElementById('activos-texto').textContent = `${stats.modulos_activos || 0} en ejecución`;
        document.getElementById('finalizados-texto').textContent = `${stats.modulos_finalizados || 0} completados`;
        document.getElementById('promedio-texto').textContent = `Progreso general`;
    }
    
    animarContador(elementId, valorFinal) {
        const element = document.getElementById(elementId);
        if (!element) {
            console.warn(`Elemento no encontrado: ${elementId}`);
            return;
        }
        
        const valorInicial = parseInt(element.textContent) || 0;
        const duracion = 1000;
        const paso = (valorFinal - valorInicial) / (duracion / 16);
        let valorActual = valorInicial;
        
        const timer = setInterval(() => {
            valorActual += paso;
            if ((paso > 0 && valorActual >= valorFinal) || (paso < 0 && valorActual <= valorFinal)) {
                valorActual = valorFinal;
                clearInterval(timer);
            }
            element.textContent = Math.round(valorActual);
        }, 16);
    }
    
    actualizarFiltros() {
    if (!this.data || !this.data.estudiantes) {
        console.warn('⚠️ No hay datos de estudiantes para filtros');
        return;
    }
    
    const programas = [...new Set(this.data.estudiantes.map(e => e.programa).filter(p => p))];
    const selectPrograma = document.getElementById('filtroPrograma');
    
    if (!selectPrograma) {
        console.warn('Elemento filtroPrograma no encontrado');
        return;
    }
    
    // Limpiar opciones existentes (excepto la primera)
    selectPrograma.innerHTML = '<option value="all">Todos los programas</option>';
    
    programas.sort().forEach(programa => {
        const option = document.createElement('option');
        option.value = programa;
        option.textContent = programa;
        selectPrograma.appendChild(option);
    });
    
    console.log('✅ Filtros actualizados. Programas disponibles:', programas);
}
    
    // En la función renderizarVistaProgreso, mejorar el cálculo:
renderizarVistaProgreso() {
    const contenedor = document.getElementById('listaEstudiantes');
    if (!contenedor) return;

    if (!this.data || !this.data.estudiantes) {
        contenedor.innerHTML = `
            <div class="col-span-3 bg-white rounded-2xl shadow-lg p-8 text-center">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay datos disponibles</h3>
                <p class="text-gray-500">No se encontraron estudiantes con módulos registrados</p>
            </div>
        `;
        return;
    }

    // Aplicar filtros
    let estudiantesFiltrados = this.aplicarFiltros(this.data.estudiantes);
    
    // Calcular índices para la paginación
    const inicio = (this.configPaginacion.paginaActual - 1) * this.configPaginacion.elementosPorPagina;
    const fin = inicio + this.configPaginacion.elementosPorPagina;
    const estudiantesPagina = estudiantesFiltrados.slice(inicio, fin);
    
    if (estudiantesPagina.length === 0) {
        contenedor.innerHTML = `
            <div class="col-span-3 bg-white rounded-2xl shadow-lg p-8 text-center">
                <i class="fas fa-user-graduate text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No se encontraron estudiantes</h3>
                <p class="text-gray-500">No hay estudiantes que coincidan con los filtros aplicados</p>
            </div>
        `;
        return;
    }
    
    contenedor.innerHTML = estudiantesPagina.map(estudiante => {
        const modulosEstudiante = this.data.modulos ? 
            this.data.modulos.filter(m => m.estudiante == estudiante.id) : [];
        
        console.log(`📊 Estudiante ${estudiante.nombre_completo} tiene ${modulosEstudiante.length} módulos:`, modulosEstudiante);
        
        // Calcular progreso general del estudiante
        let totalHorasCompletadas = 0;
        let totalHorasTotales = 0;
        let modulosCompletados = 0;
        let modulosEnCurso = 0;
        
        modulosEstudiante.forEach(modulo => {
            const horasAcum = parseInt(modulo.horas_acumuladas) || 0;
            const horasTotal = parseInt(modulo.total_horas) || 0;
            
            totalHorasCompletadas += horasAcum;
            totalHorasTotales += horasTotal;
            
            if (modulo.estado === 'Finalizado') {
                modulosCompletados++;
            } else if (modulo.estado === 'En curso') {
                modulosEnCurso++;
            }
        });
        
        const progresoGeneral = totalHorasTotales > 0 ? 
            Math.round((totalHorasCompletadas / totalHorasTotales) * 100) : 0;
        
        return `
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-estudiante fade-in">
                <div class="p-6">
                    <!-- Encabezado del estudiante -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                        <div class="flex items-center mb-4 md:mb-0">
                            <div class="avatar-estudiante mr-4">
                                ${estudiante.iniciales || '??'}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-primary-blue">${estudiante.nombre_completo || 'Nombre no disponible'}</h3>
                                <p class="text-gray-600">${estudiante.programa || 'Programa no disponible'} - DNI: ${estudiante.dni_est || 'N/A'}</p>
                                <div class="flex items-center mt-1 text-sm text-gray-500">
                                    <span class="flex items-center mr-3">
                                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                        ${modulosCompletados} finalizados
                                    </span>
                                    <span class="flex items-center mr-3">
                                        <i class="fas fa-play-circle text-blue-500 mr-1"></i>
                                        ${modulosEnCurso} en curso
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-cube text-gray-500 mr-1"></i>
                                        ${modulosEstudiante.length} total
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Progreso General</p>
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: ${progresoGeneral}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold">${progresoGeneral}%</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">${totalHorasCompletadas}h / ${totalHorasTotales}h</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barra de progreso general -->
                    <div class="mb-6">
                        <div class="flex justify-between text-sm mb-1">
                            <span>Progreso en todos los módulos</span>
                            <span>${progresoGeneral}% completado</span>
                        </div>
                        <div class="progreso-general">
                            <div class="h-full bg-gradient-to-r from-green-500 to-blue-500" style="width: ${progresoGeneral}%"></div>
                        </div>
                    </div>
                    
                    <!-- Módulos del estudiante -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        ${modulosEstudiante.length > 0 ? 
                            modulosEstudiante.map(modulo => {
                                const empresa = this.data.empresas ? 
                                    this.data.empresas.find(e => e.id == modulo.empresa) : null;
                                
                                const horasAcum = parseInt(modulo.horas_acumuladas) || 0;
                                const horasTotal = parseInt(modulo.total_horas) || 0;
                                const progreso = horasTotal > 0 ? 
                                    Math.round((horasAcum / horasTotal) * 100) : 0;
                                
                                let estadoIndicador = '';
                                let estadoTexto = modulo.estado || 'Pendiente';
                                let estadoColor = 'gray';
                                
                                if (modulo.estado === 'Finalizado') {
                                    estadoIndicador = 'estado-finalizado';
                                    estadoColor = 'green';
                                } else if (modulo.estado === 'En curso') {
                                    estadoIndicador = 'estado-en-curso';
                                    estadoColor = 'blue';
                                } else {
                                    estadoIndicador = 'estado-pendiente';
                                    estadoColor = 'gray';
                                }
                                
                                const moduloNombre = this.getNombreModulo(modulo.tipo_efsrt);
                                const moduloClase = modulo.tipo_efsrt || 'modulo1';
                                
                                // Formatear fechas
                                const fechaInicio = modulo.fecha_inicio ? 
                                    new Date(modulo.fecha_inicio).toLocaleDateString('es-ES') : 'N/A';
                                const fechaFin = modulo.fecha_fin ? 
                                    new Date(modulo.fecha_fin).toLocaleDateString('es-ES') : 'N/A';
                                
                                return `
                                    <div class="modulo-mini-card ${moduloClase} bg-white border border-gray-200 rounded-xl p-4 hover:shadow-lg transition-shadow">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <span class="badge-estado badge-${moduloClase}">${moduloNombre}</span>
                                                <span class="estado-indicador ${estadoIndicador}"></span>
                                                <span class="text-xs text-${estadoColor}-600 font-medium">${estadoTexto}</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-500">${modulo.periodo_academico || 'N/A'}</p>
                                            </div>
                                        </div>
                                        <h4 class="font-semibold text-gray-800 mb-2 text-sm">${modulo.area_ejecucion || 'Sin área especificada'}</h4>
                                        <p class="text-xs text-gray-600 mb-3">${empresa ? empresa.razon_social : 'Empresa no encontrada'}</p>
                                        
                                        <div class="mb-2">
                                            <div class="flex justify-between text-xs mb-1">
                                                <span class="font-medium">Progreso</span>
                                                <span class="font-semibold">${progreso}%</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill progress-${moduloClase}" style="width: ${progreso}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between text-xs text-gray-500 mb-2">
                                            <span><i class="fas fa-clock mr-1"></i>${horasAcum}h / ${horasTotal}h</span>
                                        </div>
                                        
                                        <div class="text-xs text-gray-400 border-t pt-2 mt-2">
                                            <div class="flex justify-between">
                                                <span>Inicio: ${fechaInicio}</span>
                                                <span>Fin: ${fechaFin}</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }).join('') : 
                            '<div class="col-span-3 text-center py-8 text-gray-500"><i class="fas fa-cube text-2xl mb-2"></i><p>No hay módulos registrados para este estudiante</p></div>'
                        }
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    this.actualizarContadores(estudiantesFiltrados.length);
    this.actualizarPaginacion(estudiantesFiltrados.length);
    
    console.log('✅ Vista de progreso renderizada para', estudiantesFiltrados.length, 'estudiantes');
}

// Método para exportar datos a CSV
exportarCSV() {
    try {
        if (!this.data || !this.data.estudiantes || !this.data.modulos) {
            this.mostrarError('No hay datos para exportar');
            return;
        }
        
        // Aplicar los mismos filtros que en la vista
        const estudiantesFiltrados = this.aplicarFiltros(this.data.estudiantes);
        
        if (estudiantesFiltrados.length === 0) {
            this.mostrarError('No hay datos para exportar con los filtros aplicados');
            return;
        }
        
        // Preparar datos para CSV
        const csvData = this.prepararDatosParaCSV(estudiantesFiltrados);
        
        // Crear y descargar archivo CSV
        this.descargarArchivoCSV(csvData, 'modulos_efsrt_' + this.getFechaActual() + '.csv');
        
        this.mostrarNotificacion('Exportación completada', 'success');
        
    } catch (error) {
        console.error('Error exportando CSV:', error);
        this.mostrarError('Error al exportar: ' + error.message);
    }
}

// Preparar datos estructurados para CSV
prepararDatosParaCSV(estudiantes) {
    const lineas = [];
    
    // 🔥 ENCABEZADOS DETALLADOS
    const encabezados = [
        'ID Estudiante',
        'DNI',
        'Estudiante',
        'Programa',
        'Módulo',
        'Tipo de Módulo',
        'Empresa',
        'Área de Ejecución',
        'Supervisor Empresa',
        'Cargo Supervisor',
        'Período Académico',
        'Fecha Inicio',
        'Fecha Fin',
        'Horas Totales',
        'Horas Acumuladas',
        'Progreso (%)',
        'Estado',
        'Fecha Registro'
    ];
    
    lineas.push(encabezados.join(','));
    
    // 🔥 DATOS DE CADA MÓDULO
    estudiantes.forEach(estudiante => {
        const modulosEstudiante = this.data.modulos.filter(m => 
            m.estudiante && m.estudiante == estudiante.id
        );
        
        if (modulosEstudiante.length === 0) {
            // Si no tiene módulos, mostrar solo datos del estudiante
            const filaEstudiante = [
                estudiante.id || '',
                estudiante.dni_est || '',
                `"${estudiante.nombre_completo || ''}"`,
                `"${estudiante.programa || ''}"`,
                'SIN MÓDULOS',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '0%',
                'Sin asignar',
                ''
            ];
            lineas.push(filaEstudiante.join(','));
        } else {
            modulosEstudiante.forEach(modulo => {
                const empresa = this.data.empresas ? 
                    this.data.empresas.find(e => e.id == modulo.empresa) : null;
                
                const horasAcum = parseInt(modulo.horas_acumuladas) || 0;
                const horasTotal = parseInt(modulo.total_horas) || 0;
                const progreso = horasTotal > 0 ? 
                    Math.round((horasAcum / horasTotal) * 100) : 0;
                
                // 🔥 ESCAPAR COMILLAS EN TEXTOS
                const escapeCSV = (text) => {
                    if (text === null || text === undefined) return '';
                    const textStr = String(text);
                    if (textStr.includes(',') || textStr.includes('"') || textStr.includes('\n')) {
                        return `"${textStr.replace(/"/g, '""')}"`;
                    }
                    return textStr;
                };
                
                const fila = [
                    estudiante.id || '',
                    estudiante.dni_est || '',
                    escapeCSV(estudiante.nombre_completo),
                    escapeCSV(estudiante.programa),
                    this.getNombreModulo(modulo.tipo_efsrt),
                    modulo.tipo_efsrt || '',
                    escapeCSV(empresa ? empresa.razon_social : ''),
                    escapeCSV(modulo.area_ejecucion),
                    escapeCSV(modulo.supervisor_empresa),
                    escapeCSV(modulo.cargo_supervisor),
                    modulo.periodo_academico || '',
                    modulo.fecha_inicio || '',
                    modulo.fecha_fin || '',
                    horasTotal,
                    horasAcum,
                    `${progreso}%`,
                    modulo.estado || 'Pendiente',
                    modulo.fecha_registro || new Date().toISOString().split('T')[0]
                ];
                
                lineas.push(fila.join(','));
            });
        }
    });
    
    // 🔥 AGREGAR RESUMEN AL FINAL
    lineas.push(''); // Línea en blanco
    lineas.push('RESUMEN ESTADÍSTICO');
    lineas.push(`"Total Estudiantes","${estudiantes.length}"`);
    lineas.push(`"Total Módulos","${this.data.modulos.length}"`);
    
    const modulosActivos = this.data.modulos.filter(m => m.estado === 'En curso').length;
    const modulosFinalizados = this.data.modulos.filter(m => m.estado === 'Finalizado').length;
    
    lineas.push(`"Módulos Activos","${modulosActivos}"`);
    lineas.push(`"Módulos Finalizados","${modulosFinalizados}"`);
    lineas.push(`"Módulos Pendientes","${this.data.modulos.length - modulosActivos - modulosFinalizados}"`);
    lineas.push(`"Fecha de Exportación","${this.getFechaHoraActual()}"`);
    
    return lineas.join('\n');
}

// Función para descargar archivo CSV
descargarArchivoCSV(csvContent, fileName) {
    // Crear blob
    const blob = new Blob(['\ufeff' + csvContent], { 
        type: 'text/csv;charset=utf-8;' 
    });
    
    // Crear enlace de descarga
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', fileName);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Liberar memoria
    setTimeout(() => URL.revokeObjectURL(url), 100);
}

// Función para obtener fecha actual formateada
getFechaActual() {
    const now = new Date();
    return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
}

getFechaHoraActual() {
    const now = new Date();
    return now.toISOString().replace('T', ' ').substring(0, 19);
}
    
    aplicarFiltros(estudiantes) {
    const filtroPrograma = document.getElementById('filtroPrograma');
    const filtroEstado = document.getElementById('filtroEstado');
    const busqueda = document.getElementById('buscarEstudiante');
    
    if (!filtroPrograma || !filtroEstado || !busqueda) {
        return estudiantes;
    }
    
    const filtroProgramaValor = filtroPrograma.value;
    const filtroEstadoValor = filtroEstado.value;
    const busquedaValor = busqueda.value.toLowerCase();
    
    let estudiantesFiltrados = estudiantes;
    
    // Filtrar por programa
    if (filtroProgramaValor !== 'all') {
        estudiantesFiltrados = estudiantesFiltrados.filter(e => 
            e.programa && e.programa === filtroProgramaValor
        );
    }
    
    // Filtrar por búsqueda
    if (busquedaValor) {
        estudiantesFiltrados = estudiantesFiltrados.filter(e => {
            const nombreCompleto = e.nombre_completo ? e.nombre_completo.toLowerCase() : '';
            const dni = e.dni_est ? e.dni_est : '';
            return nombreCompleto.includes(busquedaValor) || dni.includes(busquedaValor);
        });
    }
    
    // 🔥 CORRECCIÓN: Filtrar por estado de módulos
    if (filtroEstadoValor !== 'all' && this.data && this.data.modulos) {
        estudiantesFiltrados = estudiantesFiltrados.filter(estudiante => {
            const modulosEstudiante = this.data.modulos.filter(m => 
                m.estudiante && m.estudiante == estudiante.id
            );
            
            if (modulosEstudiante.length === 0) return false;
            
            if (filtroEstadoValor === 'completado') {
                // Al menos un módulo finalizado
                return modulosEstudiante.some(m => m.estado === 'Finalizado');
            } else if (filtroEstadoValor === 'en-progreso') {
                // Al menos un módulo en curso
                return modulosEstudiante.some(m => m.estado === 'En curso');
            } else if (filtroEstadoValor === 'pendiente') {
                // Todos los módulos pendientes o al menos uno pendiente
                return modulosEstudiante.some(m => m.estado === 'Pendiente') || 
                       modulosEstudiante.every(m => m.estado === 'Pendiente');
            }
            
            return true;
        });
    }
    
    console.log(`🔍 Filtros aplicados: Programa=${filtroProgramaValor}, Estado=${filtroEstadoValor}, Búsqueda=${busquedaValor}`);
    console.log(`📊 Resultados filtrados: ${estudiantesFiltrados.length} de ${estudiantes.length} estudiantes`);
    
    return estudiantesFiltrados;
}
    
    actualizarContadores(totalFiltrados) {
        const inicio = (this.configPaginacion.paginaActual - 1) * this.configPaginacion.elementosPorPagina + 1;
        const fin = Math.min(inicio + this.configPaginacion.elementosPorPagina - 1, totalFiltrados);
        
        document.getElementById('estudiantes-mostrados').textContent = `${inicio}-${fin}`;
        document.getElementById('estudiantes-totales').textContent = totalFiltrados;
    }
    
    actualizarPaginacion(totalFiltrados) {
        const totalPaginas = Math.ceil(totalFiltrados / this.configPaginacion.elementosPorPagina);
        const paginacion = document.getElementById('paginacion-estudiantes');
        
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
                this.renderizarVistaProgreso();
            }
        });
        paginacion.appendChild(btnAnterior);
        
        // Números de página
        const inicioPagina = Math.max(1, this.configPaginacion.paginaActual - 2);
        const finPagina = Math.min(totalPaginas, this.configPaginacion.paginaActual + 2);
        
        for (let i = inicioPagina; i <= finPagina; i++) {
            const btnPagina = document.createElement('button');
            btnPagina.className = `px-3 py-1 rounded-lg border ${i === this.configPaginacion.paginaActual ? 
                'bg-primary-blue text-white' : 
                'bg-white text-gray-700 hover:bg-gray-50'}`;
            btnPagina.textContent = i;
            btnPagina.addEventListener('click', () => {
                this.configPaginacion.paginaActual = i;
                this.renderizarVistaProgreso();
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
                this.renderizarVistaProgreso();
            }
        });
        paginacion.appendChild(btnSiguiente);
    }
    
    inicializarGraficos() {
        if (!this.data || !this.data.graficos) {
            console.warn('No hay datos para gráficos');
            return;
        }
        
        this.crearGraficoTipoModulos();
        this.crearGraficoEstadoModulos();
    }
    
    crearGraficoTipoModulos() {
        const ctx = document.getElementById('tipoModuloChart');
        if (!ctx) {
            console.warn('Canvas tipoModuloChart no encontrado');
            return;
        }
        
        const datos = this.data.graficos.tipo_modulos;
        if (!datos) {
            console.warn('No hay datos para gráfico de tipos de módulos');
            return;
        }
        
        if (this.tipoModuloChart) {
            this.tipoModuloChart.destroy();
        }
        
        this.tipoModuloChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(datos),
                datasets: [{
                    data: Object.values(datos),
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
    
    crearGraficoEstadoModulos() {
        const ctx = document.getElementById('estadoModuloChart');
        if (!ctx) {
            console.warn('Canvas estadoModuloChart no encontrado');
            return;
        }
        
        const datos = this.data.graficos.estado_modulos;
        if (!datos) {
            console.warn('No hay datos para gráfico de estado de módulos');
            return;
        }
        
        if (this.estadoModuloChart) {
            this.estadoModuloChart.destroy();
        }
        
        this.estadoModuloChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(datos),
                datasets: [{
                    data: Object.values(datos),
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
    
    getNombreModulo(tipo_efsrt) {
    const modulos = {
        'modulo1': 'Módulo 1',
        'modulo2': 'Módulo 2', 
        'modulo3': 'Módulo 3'
    };
    return modulos[tipo_efsrt] || (tipo_efsrt ? tipo_efsrt.charAt(0).toUpperCase() + tipo_efsrt.slice(1) : 'Módulo');
}
    
    inicializarEventos() {
        // Botón Vista Progreso
        const btnVistaProgreso = document.getElementById('btnVistaProgreso');
    if (btnVistaProgreso) {
        btnVistaProgreso.addEventListener('click', () => {
            const vistaProgreso = document.getElementById('vistaProgreso');
            if (vistaProgreso) {
                // 🔥 CORRECCIÓN: Hacer scroll al inicio de la sección vistaProgreso
                vistaProgreso.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start' // Esto asegura que se vea el inicio
                });
                
                // 🔥 OPCIÓN ADICIONAL: Resaltar la sección momentáneamente
                vistaProgreso.classList.add('ring-2', 'ring-blue-500', 'rounded-2xl');
                setTimeout(() => {
                    vistaProgreso.classList.remove('ring-2', 'ring-blue-500', 'rounded-2xl');
                }, 2000);
            }
        });
        console.log('✅ Botón vista progreso configurado para ir al inicio');
    }

    // Botón Exportar
    const btnExportar = document.getElementById('btnExportar');
    if (btnExportar) {
        btnExportar.addEventListener('click', () => {
            this.exportarCSV();
        });
    }

        // Botón Refrescar
    const btnRefrescar = document.getElementById('btnRefrescar');
    if (btnRefrescar) {
        btnRefrescar.addEventListener('click', () => {
            this.cargarDatos();
            this.mostrarNotificacion('Datos actualizados', 'info');
        });
    }

         // Filtros
    const filtroPrograma = document.getElementById('filtroPrograma');
    const filtroEstado = document.getElementById('filtroEstado');
    const buscarEstudiante = document.getElementById('buscarEstudiante');
    
    if (filtroPrograma) {
        filtroPrograma.addEventListener('change', () => {
            this.configPaginacion.paginaActual = 1;
            this.renderizarVistaProgreso();
        });
    }
    
    if (filtroEstado) {
        filtroEstado.addEventListener('change', () => {
            this.configPaginacion.paginaActual = 1;
            this.renderizarVistaProgreso();
        });
    }
    
    if (buscarEstudiante) {
        buscarEstudiante.addEventListener('input', () => {
            this.configPaginacion.paginaActual = 1;
            this.renderizarVistaProgreso();
        });
    }

    }
    
    mostrarError(mensaje) {
        console.error(mensaje);
        // Puedes implementar notificaciones toast aquí
        const notificacion = document.createElement('div');
        notificacion.className = 'fixed top-20 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50';
        notificacion.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>${mensaje}</span>
            </div>
        `;
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            if (document.body.contains(notificacion)) {
                document.body.removeChild(notificacion);
            }
        }, 5000);
    }
    
    mostrarLoading(mostrar) {
        // Implementar indicador de carga si es necesario
        const loading = document.getElementById('loading-indicator');
        if (loading) {
            loading.style.display = mostrar ? 'block' : 'none';
        }
    }
    
    mostrarNotificacion(mensaje, tipo = 'info') {
    const colores = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'info': 'bg-blue-500',
        'warning': 'bg-yellow-500'
    };
    
    const iconos = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'info': 'fa-info-circle',
        'warning': 'fa-exclamation-triangle'
    };
    
    // Remover notificaciones anteriores
    const notificacionesAnteriores = document.querySelectorAll('.notificacion-toast');
    notificacionesAnteriores.forEach(notif => notif.remove());
    
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion-toast fixed top-24 right-4 ${colores[tipo]} text-white p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full flex items-center`;
    
    notificacion.innerHTML = `
        <i class="fas ${iconos[tipo]} mr-3 text-lg"></i>
        <div>
            <div class="font-medium">${mensaje}</div>
            <div class="text-xs opacity-80 mt-1">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
        </div>
    `;
    
    document.body.appendChild(notificacion);
    
    // Animación de entrada
    setTimeout(() => {
        notificacion.classList.remove('translate-x-full');
    }, 10);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        notificacion.classList.add('translate-x-full');
        setTimeout(() => {
            if (notificacion.parentNode) {
                notificacion.parentNode.removeChild(notificacion);
            }
        }, 300);
    }, 5000);
}
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new ModulosDashboard();
});