// ==============================
// VARIABLES GLOBALES
// ==============================

let datosReportes = {};
let chartEstadoPracticas = null;
let chartModulos = null;
let chartEvolucionMensual = null;
let chartTopEmpresas = null;
let intervaloActualizacion = null;

// ==============================
// FUNCIONES DE CARGA DE DATOS
// ==============================

async function cargarDatosDashboard() {
    try {
        console.log('🔄 Cargando datos del dashboard...');
        
        const response = await fetch('index.php?c=Reportes&a=datosDashboard');
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        datosReportes = await response.json();
        console.log('✅ Datos recibidos:', datosReportes);
        
        actualizarDashboard();
        inicializarGraficos();
        
    } catch (error) {
        console.error('❌ Error cargando datos:', error);
        mostrarError('No se pudieron cargar los datos del dashboard');
    }
}

// ==============================
// FUNCIONES DE ACTUALIZACIÓN DE UI
// ==============================

function actualizarDashboard() {
    const stats = datosReportes.estadisticas || {};
    
    // Actualizar estadísticas principales
    actualizarElemento('total-estudiantes', stats.total_estudiantes || 0);
    actualizarElemento('practicas-activas', stats.total_practicas || 0);
    actualizarElemento('horas-cumplidas', stats.horas_cumplidas || 0);
    actualizarElemento('tasa-finalizacion', `${stats.tasa_finalizacion || 0}%`);
    
    // Actualizar contadores de gráficos
    actualizarElemento('count-en-curso', stats.practicas_en_curso || 0);
    actualizarElemento('count-finalizado', stats.practicas_finalizadas || 0);
    actualizarElemento('count-pendiente', stats.practicas_pendientes || 0);
    
    // Actualizar fecha
    const fechaElement = document.getElementById('fecha-actualizacion');
    if (fechaElement) {
        const fechaActual = datosReportes.fecha_actual || new Date().toLocaleString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        fechaElement.textContent = `Actualizado: ${fechaActual}`;
    }
    
    // Actualizar tendencias
    actualizarTendencias();
}

function actualizarElemento(id, valor) {
    const elemento = document.getElementById(id);
    if (elemento) {
        elemento.textContent = valor;
    }
}

function actualizarTendencias() {
    // Estas tendencias son simuladas - puedes adaptarlas a datos reales
    const tendencias = {
        estudiantes: '↑ 12% este mes',
        practicas: '↑ 8% este mes',
        horas: '↑ 15% este mes',
        finalizacion: '↑ 5% este mes'
    };
    
    actualizarElemento('tendencia-estudiantes', tendencias.estudiantes);
    actualizarElemento('tendencia-practicas', tendencias.practicas);
    actualizarElemento('tendencia-horas', tendencias.horas);
    actualizarElemento('tendencia-finalizacion', tendencias.finalizacion);
}

// ==============================
// FUNCIONES DE GRÁFICOS
// ==============================

function inicializarGraficos() {
    const datos = datosReportes;
    
    // Gráfico 1: Distribución por Estado
    inicializarGraficoEstadoPracticas(datos.datosEstado);
    
    // Gráfico 2: Distribución por Módulo
    inicializarGraficoModulos(datos.datosModulos);
    
    // Gráfico 3: Evolución Mensual
    inicializarGraficoEvolucionMensual(datos.evolucionMensual);
    
    // Gráfico 4: Top Empresas
    inicializarGraficoTopEmpresas(datos.topEmpresas);
}

function inicializarGraficoEstadoPracticas(datos) {
    const ctx = document.getElementById('chartEstadoPracticas');
    if (!ctx) return;
    
    // Destruir gráfico existente
    if (chartEstadoPracticas) {
        chartEstadoPracticas.destroy();
    }
    
    if (!datos || !datos.labels || datos.labels.length === 0) {
        mostrarGraficoVacio(ctx, 'No hay datos de prácticas');
        return;
    }
    
    chartEstadoPracticas = new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: datos.labels,
            datasets: [{
                data: datos.data,
                backgroundColor: datos.colors || ['#0ea5e9', '#10b981', '#f59e0b'],
                borderWidth: 1,
                hoverOffset: 10
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
            }
        }
    });
}

function inicializarGraficoModulos(datos) {
    const ctx = document.getElementById('chartModulos');
    if (!ctx) return;
    
    if (chartModulos) {
        chartModulos.destroy();
    }
    
    if (!datos || !datos.labels || datos.labels.length === 0) {
        mostrarGraficoVacio(ctx, 'No hay datos por módulo');
        return;
    }
    
    chartModulos = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: datos.labels,
            datasets: [{
                label: 'Prácticas',
                data: datos.data,
                backgroundColor: datos.colors || ['#3b82f6', '#10b981', '#8b5cf6'],
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: { 
                    display: false 
                }
            }
        }
    });
    
    // Actualizar contadores de módulos
    actualizarContadoresModulos(datos.data);
}

function actualizarContadoresModulos(datos) {
    const modulosData = datos || [0, 0, 0];
    
    actualizarElemento('count-modulo1', modulosData[0] || 0);
    actualizarElemento('count-modulo2', modulosData[1] || 0);
    actualizarElemento('count-modulo3', modulosData[2] || 0);
}

function inicializarGraficoEvolucionMensual(datos) {
    const ctx = document.getElementById('chartEvolucionMensual');
    if (!ctx) return;
    
    if (chartEvolucionMensual) {
        chartEvolucionMensual.destroy();
    }
    
    if (!datos || !datos.labels || datos.labels.length === 0) {
        mostrarGraficoVacio(ctx, 'No hay datos históricos');
        return;
    }
    
    chartEvolucionMensual = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: datos.labels,
            datasets: [{
                label: 'Prácticas Iniciadas',
                data: datos.data,
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            interaction: {
                intersect: false,
                mode: 'nearest'
            }
        }
    });
}

function inicializarGraficoTopEmpresas(datos) {
    const ctx = document.getElementById('chartTopEmpresas');
    if (!ctx) return;
    
    if (chartTopEmpresas) {
        chartTopEmpresas.destroy();
    }
    
    if (!datos || datos.length === 0) {
        mostrarGraficoVacio(ctx, 'No hay datos de empresas');
        return;
    }
    
    const empresasLabels = datos.map(e => {
        const nombre = e.razon_social || 'Empresa sin nombre';
        return nombre.length > 25 ? nombre.substring(0, 25) + '...' : nombre;
    });
    
    const empresasData = datos.map(e => e.cantidad_practicas || 0);
    
    // CORRECCIÓN: Cambiar 'horizontalBar' por 'bar' con indexAxis: 'y'
    chartTopEmpresas = new Chart(ctx.getContext('2d'), {
        type: 'bar',  // Cambiado de 'horizontalBar' a 'bar'
        data: {
            labels: empresasLabels,
            datasets: [{
                label: 'Prácticas',
                data: empresasData,
                backgroundColor: '#ec4899',
                borderWidth: 1,
                borderRadius: 4,
                hoverBackgroundColor: '#db2777'
            }]
        },
        options: {
            indexAxis: 'y',  // Esto hace que sea horizontal
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0
                    }
                }
            },
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const empresa = datos[context.dataIndex];
                            return [
                                `Prácticas: ${context.raw}`,
                                `Horas totales: ${empresa.total_horas || 0}`,
                                empresa.razon_social || ''
                            ];
                        }
                    }
                }
            }
        }
    });
}

function mostrarGraficoVacio(ctx, mensaje) {
    const context = ctx.getContext('2d');
    
    // Limpiar canvas
    context.clearRect(0, 0, ctx.width, ctx.height);
    
    // Mostrar mensaje
    context.fillStyle = '#6b7280';
    context.font = '16px Arial';
    context.textAlign = 'center';
    context.textBaseline = 'middle';
    context.fillText(mensaje, ctx.width / 2, ctx.height / 2);
}

function mostrarError(mensaje) {
    console.error('❌ Error:', mensaje);
    
    // Puedes agregar una notificación en la UI si quieres
    const errorDiv = document.getElementById('error-notification');
    if (errorDiv) {
        errorDiv.textContent = mensaje;
        errorDiv.classList.remove('hidden');
        
        setTimeout(() => {
            errorDiv.classList.add('hidden');
        }, 5000);
    }
}

// ==============================
// FUNCIONES DE MANEJO DE EVENTOS
// ==============================

function configurarEventos() {
    // Botón de actualizar manual
    const btnActualizar = document.getElementById('btn-actualizar-manual');
    if (btnActualizar) {
        btnActualizar.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
            this.disabled = true;
            
            cargarDatosDashboard().finally(() => {
                this.innerHTML = '<i class="fas fa-sync-alt"></i> Actualizar';
                this.disabled = false;
            });
        });
    }
    
    // Toggle sidebar (si no está ya en main.js)
    const toggleBtn = document.getElementById('toggleSidebar');
    if (toggleBtn && !toggleBtn.hasListener) {
        toggleBtn.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('collapsed');
            
            if (sidebar.classList.contains('collapsed')) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-20');
            } else {
                mainContent.classList.remove('ml-20');
                mainContent.classList.add('ml-64');
            }
        });
        toggleBtn.hasListener = true;
    }
    
    // Efectos hover en tarjetas
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
    
    // Redimensionar gráficos cuando cambia el tamaño de la ventana
    window.addEventListener('resize', redimensionarGraficos);
}

function redimensionarGraficos() {
    const graficos = [chartEstadoPracticas, chartModulos, chartEvolucionMensual, chartTopEmpresas];
    
    graficos.forEach(grafico => {
        if (grafico) {
            grafico.resize();
        }
    });
}

// ==============================
// FUNCIONES DE CONTROL
// ==============================

function iniciarActualizacionAutomatica() {
    // Detener intervalo anterior si existe
    if (intervaloActualizacion) {
        clearInterval(intervaloActualizacion);
    }
    
    // Configurar nuevo intervalo (cada 30 segundos)
    intervaloActualizacion = setInterval(cargarDatosDashboard, 30000);
}

function detenerActualizacionAutomatica() {
    if (intervaloActualizacion) {
        clearInterval(intervaloActualizacion);
        intervaloActualizacion = null;
    }
}

// ==============================
// INICIALIZACIÓN
// ==============================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando Dashboard de Reportes...');
    
    // Configurar eventos
    configurarEventos();
    
    // Cargar datos iniciales
    cargarDatosDashboard().then(() => {
        console.log('✅ Dashboard cargado correctamente');
    });
    
    // Iniciar actualización automática
    iniciarActualizacionAutomatica();
    
    // Manejar visibilidad de la página
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            detenerActualizacionAutomatica();
        } else {
            iniciarActualizacionAutomatica();
            cargarDatosDashboard(); // Actualizar al volver
        }
    });
});

// ==============================
// EXPORTAR FUNCIONES (si necesitas usarlas desde otros archivos)
// ==============================

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        cargarDatosDashboard,
        actualizarDashboard,
        inicializarGraficos,
        iniciarActualizacionAutomatica,
        detenerActualizacionAutomatica
    };
}