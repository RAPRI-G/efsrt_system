// Dashboard functionality
class Dashboard {
    constructor() {
        this.data = null;
        this.estadoChart = null;
        this.modulosChart = null;
        this.init();
    }
    
    init() {
        this.cargarDatos();
        this.inicializarEventos();
    }
    
    async cargarDatos() {
        try {
            this.mostrarLoading(true);
            
            const response = await fetch('index.php?c=Inicio&a=getDashboardData');
            const result = await response.json();
            
            if (result.success) {
                this.data = result.data;
                this.actualizarDashboard();
            } else {
                this.mostrarError('Error al cargar los datos del dashboard');
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error de conexión al servidor');
        } finally {
            this.mostrarLoading(false);
        }
    }
    
    actualizarDashboard() {
        this.actualizarEstadisticas();
        this.actualizarTablaPracticas();
        this.actualizarPracticasEnCurso();
        this.inicializarGraficos();
        this.actualizarActividadReciente();
    }
    
    actualizarEstadisticas() {
        const stats = this.data.estadisticas;
        
        // Animación de contadores
        this.animarContador('practicas-activas', stats.practicas_activas);
        this.animarContador('total-estudiantes', stats.total_estudiantes);
        this.animarContador('total-empresas', stats.total_empresas);
        this.animarContador('total-docentes', stats.total_docentes);
        
        // Actualizar textos descriptivos
        document.getElementById('practicas-texto').textContent = `${stats.practicas_activas} en curso`;
        document.getElementById('estudiantes-texto').textContent = `${stats.total_estudiantes} registrados`;
        document.getElementById('empresas-texto').textContent = `${stats.total_empresas} activas`;
        document.getElementById('docentes-texto').textContent = `${stats.total_docentes} disponibles`;
    }
    
    animarContador(elementId, valorFinal) {
        const element = document.getElementById(elementId);
        const valorInicial = parseInt(element.textContent) || 0;
        const duracion = 1000; // 1 segundo
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
    
    actualizarTablaPracticas() {
        const tabla = document.getElementById('tabla-practicas');
        const practicas = this.data.practicas;
        
        if (practicas.length === 0) {
            tabla.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No hay prácticas registradas
                    </td>
                </tr>
            `;
            return;
        }
        
        tabla.innerHTML = practicas.map(practica => {
            const porcentaje = practica.total_horas > 0 ? 
                ((practica.horas_acumuladas / practica.total_horas) * 100).toFixed(1) : 0;
            
            const estadoClase = this.getClaseEstado(practica.estado);
            const estadoTexto = practica.estado || 'Pendiente';
            
            return `
                <tr class="hover:bg-gray-50 transition-colors duration-300">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-semibold mr-3">
                                ${practica.nom_est ? practica.nom_est.charAt(0) : '?'}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">
                                    ${practica.ap_est || ''} ${practica.am_est || ''}, ${practica.nom_est || 'N/A'}
                                </div>
                                <div class="text-xs text-gray-500">${practica.dni_est || ''}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${practica.programa || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${this.getNombreModulo(practica.tipo_efsrt)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${practica.nombre_empresa || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-3">
                                <div class="h-2.5 rounded-full" style="width: ${porcentaje}%; background-color: ${practica.estado === 'En curso' ? '#3b82f6' : '#16a34a'}"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">${practica.horas_acumuladas || 0}/${practica.total_horas || 0}h</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${estadoClase}">
                            ${estadoTexto}
                        </span>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    actualizarPracticasEnCurso() {
        const contenedor = document.getElementById('practicas-en-curso');
        const practicasEnCurso = this.data.graficos.practicas_en_curso;
        
        // Limpiar contenido existente
        contenedor.innerHTML = '';
        
        if (!practicasEnCurso || practicasEnCurso.length === 0) {
            contenedor.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p>No hay prácticas en curso</p>
                </div>
            `;
            return;
        }
        
        practicasEnCurso.forEach(practica => {
            const porcentaje = practica.total_horas > 0 ? 
                ((practica.horas_acumuladas / practica.total_horas) * 100).toFixed(1) : 0;
            
            const card = document.createElement('div');
            card.className = 'flex items-center justify-between p-4 bg-blue-50 rounded-xl mb-3';
            card.innerHTML = `
                <div>
                    <p class="font-semibold text-primary-blue">${this.getNombreModulo(practica.tipo_efsrt)}</p>
                    <p class="text-sm text-gray-600">${practica.nom_est || ''} ${practica.ap_est || ''} - ${practica.razon_social || 'N/A'}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-blue-500">${practica.horas_acumuladas || 0}/${practica.total_horas || 0}h</p>
                    <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: ${porcentaje}%"></div>
                    </div>
                </div>
            `;
            contenedor.appendChild(card);
        });
    }
    
    inicializarGraficos() {
        this.crearGraficoEstadoPracticas();
        this.crearGraficoDistribucionModulos();
    }
    
    crearGraficoEstadoPracticas() {
        const ctx = document.getElementById('estadoPracticasChart');
        
        // Verificar si el canvas existe
        if (!ctx) {
            console.error('Canvas estadoPracticasChart no encontrado');
            return;
        }
        
        // Destruir gráfico anterior si existe
        if (this.estadoChart) {
            this.estadoChart.destroy();
            this.estadoChart = null;
        }
        
        const datos = this.data.graficos.estado_practicas;
        
        // Verificar si hay datos
        if (!datos || Object.keys(datos).length === 0) {
            ctx.parentElement.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-chart-pie text-3xl mb-2"></i>
                    <p>No hay datos para mostrar</p>
                </div>
            `;
            return;
        }
        
        this.estadoChart = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(datos),
                datasets: [{
                    data: Object.values(datos),
                    backgroundColor: ['#3b82f6', '#10b981', '#6b7280'],
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
    
    crearGraficoDistribucionModulos() {
        const ctx = document.getElementById('modulosChart');
        
        // Verificar si el canvas existe
        if (!ctx) {
            console.error('Canvas modulosChart no encontrado');
            return;
        }
        
        // Destruir gráfico anterior si existe
        if (this.modulosChart) {
            this.modulosChart.destroy();
            this.modulosChart = null;
        }
        
        const datos = this.data.graficos.distribucion_modulos;
        
        // Verificar si hay datos
        if (!datos || Object.keys(datos).length === 0) {
            ctx.parentElement.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-chart-bar text-3xl mb-2"></i>
                    <p>No hay datos para mostrar</p>
                </div>
            `;
            return;
        }
        
        this.modulosChart = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: Object.keys(datos),
                datasets: [{
                    label: 'Cantidad de Prácticas',
                    data: Object.values(datos),
                    backgroundColor: ['#0C1F36', '#0dcaf0', '#198754'],
                    borderWidth: 0,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
    
    actualizarActividadReciente() {
        const contenedor = document.getElementById('actividad-reciente');
        const actividades = this.data.actividad_reciente;
        
        // Limpiar contenido existente
        contenedor.innerHTML = '';
        
        if (!actividades || actividades.length === 0) {
            const div = document.createElement('div');
            div.className = 'flex items-start';
            div.innerHTML = `
                <div class="bg-gray-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-info-circle text-gray-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-primary-blue">Sin actividad reciente</p>
                    <p class="text-sm text-gray-600">No hay actividad registrada en los últimos días</p>
                    <p class="text-xs text-gray-400 mt-1">Hace unos momentos</p>
                </div>
            `;
            contenedor.appendChild(div);
            return;
        }
        
        actividades.forEach(actividad => {
            const icono = actividad.tipo === 'practica' ? 'fa-briefcase' : 'fa-calendar-check';
            const color = actividad.tipo === 'practica' ? 'blue' : 'green';
            
            const div = document.createElement('div');
            div.className = 'flex items-start mb-4 last:mb-0';
            div.innerHTML = `
                <div class="bg-${color}-100 p-3 rounded-lg mr-4">
                    <i class="fas ${icono} text-${color}-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-primary-blue">${actividad.descripcion}</p>
                    <p class="text-xs text-gray-400 mt-1">${this.formatearFecha(actividad.fecha)}</p>
                </div>
            `;
            contenedor.appendChild(div);
        });
    }
    
    formatearFecha(fechaString) {
        const fecha = new Date(fechaString);
        const ahora = new Date();
        const diffMs = ahora - fecha;
        const diffMinutos = Math.floor(diffMs / 60000);
        const diffHoras = Math.floor(diffMs / 3600000);
        const diffDias = Math.floor(diffMs / 86400000);
        
        if (diffMinutos < 1) return 'Hace unos momentos';
        if (diffMinutos < 60) return `Hace ${diffMinutos} minutos`;
        if (diffHoras < 24) return `Hace ${diffHoras} horas`;
        if (diffDias === 1) return 'Ayer';
        return `Hace ${diffDias} días`;
    }
    
    getClaseEstado(estado) {
        const clases = {
            'En curso': 'bg-yellow-100 text-yellow-800',
            'Finalizado': 'bg-green-100 text-green-800',
            'Pendiente': 'bg-gray-100 text-gray-800'
        };
        return clases[estado] || 'bg-gray-100 text-gray-800';
    }
    
    getNombreModulo(tipo_efsrt) {
        const modulos = {
            'modulo1': 'Módulo 1',
            'modulo2': 'Módulo 2',
            'modulo3': 'Módulo 3'
        };
        return modulos[tipo_efsrt] || tipo_efsrt;
    }
    
    mostrarError(mensaje) {
        // Crear notificación de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg z-50';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>${mensaje}</span>
            </div>
        `;
        
        document.body.appendChild(errorDiv);
        
        // Remover después de 5 segundos
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.parentNode.removeChild(errorDiv);
            }
        }, 5000);
    }
    
    mostrarLoading(mostrar) {
        const loadingElement = document.getElementById('loading-indicator');
        if (loadingElement) {
            loadingElement.style.display = mostrar ? 'block' : 'none';
        }
    }
    
    inicializarEventos() {
        // Filtro por estado
        const filterEstado = document.getElementById('filterEstado');
        if (filterEstado) {
            filterEstado.addEventListener('change', (e) => {
                this.filtrarTablaPorEstado(e.target.value);
            });
        }
        
        // Botón de exportar
        const btnExportar = document.getElementById('btnExportar');
        if (btnExportar) {
            btnExportar.addEventListener('click', () => {
                this.exportarDatos();
            });
        }
        
        // Botón de actualizar
        const btnActualizar = document.getElementById('btnActualizar');
        if (btnActualizar) {
            btnActualizar.addEventListener('click', () => {
                this.cargarDatos();
            });
        }
    }
    
    filtrarTablaPorEstado(estado) {
        const filas = document.querySelectorAll('#tabla-practicas tr');
        
        filas.forEach(fila => {
            if (estado === 'all' || filas.length === 1) {
                fila.style.display = '';
            } else {
                const estadoCelda = fila.querySelector('td:nth-child(6) span');
                if (estadoCelda) {
                    const estadoFila = estadoCelda.textContent.trim();
                    fila.style.display = estadoFila === estado ? '' : 'none';
                }
            }
        });
    }
    
    exportarDatos() {
        // Implementar exportación de datos
        alert('Funcionalidad de exportación en desarrollo');
    }
    
    // Método para limpiar gráficos antes de actualizar
    limpiarGraficos() {
        if (this.estadoChart) {
            this.estadoChart.destroy();
            this.estadoChart = null;
        }
        if (this.modulosChart) {
            this.modulosChart.destroy();
            this.modulosChart = null;
        }
    }
}

// Variable global para la instancia de Dashboard
let dashboardInstance = null;

// Inicializar dashboard cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    dashboardInstance = new Dashboard();
    
    // Actualizar datos cada 30 segundos (usando la misma instancia)
    setInterval(() => {
        if (dashboardInstance) {
            dashboardInstance.cargarDatos();
        }
    }, 30000);
    
    // Limpiar gráficos al cerrar/actualizar la página
    window.addEventListener('beforeunload', () => {
        if (dashboardInstance) {
            dashboardInstance.limpiarGraficos();
        }
    });
});

// Exportar instancia para uso global si es necesario
window.Dashboard = Dashboard;