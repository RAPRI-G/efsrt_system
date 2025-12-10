/**
 * Documentos JS - Funcionalidades para la página de documentos
 * ESFRH - Sistema de Gestión de Documentos
 */

// ============================================
// CONFIGURACIÓN Y VARIABLES GLOBALES
// ============================================

let documentoActual = null;
let moduloActualDocumento = null;
let tipoDocumentoActual = null;

// ============================================
// FUNCIONES DE INICIALIZACIÓN
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ JS de Documentos cargado');
    
    // Inicializar componentes
    inicializarTabs();
    inicializarModal();
    inicializarEventos();
    inicializarTooltips();
    
    // Cargar datos iniciales si es necesario
    cargarDatosIniciales();
});

/**
 * Inicializar sistema de tabs
 */
function inicializarTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    if (tabButtons.length === 0) {
        console.warn('⚠️ No se encontraron botones de tabs');
        return;
    }
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Obtener ID del tab
            const tabId = this.getAttribute('data-tab');
            if (!tabId) {
                console.error('❌ Tab sin data-tab:', this);
                return;
            }
            
            // Remover active de todos los tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });
            
            tabContents.forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
                content.setAttribute('aria-hidden', 'true');
            });
            
            // Activar tab seleccionado
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            const tabContent = document.getElementById(tabId);
            if (tabContent) {
                tabContent.style.display = 'block';
                setTimeout(() => {
                    tabContent.classList.add('active');
                }, 50);
                tabContent.setAttribute('aria-hidden', 'false');
                
                console.log(`📂 Tab activado: ${tabId}`);
                
                // Si es módulo 2, actualizar datos
                if (tabId === 'modulo2') {
                    actualizarDatosModulo2();
                }
            } else {
                console.error(`❌ No se encontró contenido para el tab: ${tabId}`);
            }
        });
    });
    
    console.log(`✅ Tabs inicializados: ${tabButtons.length} encontrados`);
}

/**
 * Inicializar modal de vista previa
 */
function inicializarModal() {
    const modal = document.getElementById('modalDocumento');
    const closeBtn = document.getElementById('cerrarModal');
    const printBtn = document.getElementById('imprimirDocumento');
    const downloadBtn = document.getElementById('descargarDesdeModal');
    
    if (!modal) {
        console.warn('⚠️ Modal no encontrado en el DOM');
        return;
    }
    
    // Cerrar modal con botón X
    if (closeBtn) {
        closeBtn.addEventListener('click', cerrarModalDocumento);
    }
    
    // Cerrar modal al hacer clic fuera
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalDocumento();
        }
    });
    
    // Función de impresión
    if (printBtn) {
        printBtn.addEventListener('click', imprimirDocumento);
    }
    
    // Función de descarga desde modal
    if (downloadBtn) {
        downloadBtn.addEventListener('click', descargarDesdeModal);
    }
    
    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            cerrarModalDocumento();
        }
    });
    
    console.log('✅ Modal inicializado');
}

/**
 * Inicializar eventos adicionales
 */
function inicializarEventos() {
    // Eventos para vista previa (ya están en onclick en el HTML)
    // Los manejaremos directamente desde las funciones globales
    
    // Evento para logout
    const logoutBtn = document.getElementById('logoutBtnSidebar');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            cerrarSesion();
        });
    }
    
    // Evento para toggle sidebar
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', toggleSidebar);
    }
    
    // Eventos de validación de formularios si los hay
    inicializarValidaciones();
    
    console.log('✅ Eventos inicializados');
}

/**
 * Inicializar tooltips
 */
function inicializarTooltips() {
    // Tooltips para botones deshabilitados
    const disabledButtons = document.querySelectorAll('button:disabled, .btn-download:disabled');
    disabledButtons.forEach(button => {
        if (button.hasAttribute('title')) return;
        
        const parentCard = button.closest('.document-card');
        if (parentCard) {
            const estadoBadge = parentCard.querySelector('.estado-badge');
            if (estadoBadge && estadoBadge.textContent.includes('Pendiente')) {
                button.title = 'Disponible al completar el módulo';
            } else if (button.classList.contains('btn-download')) {
                button.title = 'Documento en proceso';
            }
        }
    });
    
    // Tooltips para porcentajes
    const progressBars = document.querySelectorAll('.progress-container');
    progressBars.forEach(container => {
        const progressFill = container.querySelector('.progress-fill');
        if (progressFill) {
            const width = progressFill.style.width;
            progressFill.title = `Progreso: ${width}`;
        }
    });
}

/**
 * Cargar datos iniciales
 */
function cargarDatosIniciales() {
    // Actualizar fecha actual en elementos que lo requieran
    const fechaElements = document.querySelectorAll('.fecha-actual');
    const fechaActual = new Date().toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
    
    fechaElements.forEach(el => {
        el.textContent = fechaActual;
    });
    
    // Si hay módulo 2 activo, cargar sus datos
    const modulo2Tab = document.querySelector('[data-tab="modulo2"]');
    if (modulo2Tab && modulo2Tab.classList.contains('active')) {
        actualizarDatosModulo2();
    }
}

// ============================================
// FUNCIONES DE DOCUMENTOS
// ============================================

/**
 * Ver documento en modal
 * @param {string} tipo - Tipo de documento (solicitud, carta, asistencias, evaluacion)
 * @param {number} modulo - Número del módulo (1, 2, 3)
 */
function verDocumento(tipo, modulo) {
    if (!tipo || !modulo) {
        mostrarNotificacion('Error: Tipo o módulo no especificado', 'error');
        return;
    }
    
    console.log(`📄 Solicitando vista previa: ${tipo} - Módulo ${modulo}`);
    
    // Actualizar variables globales
    documentoActual = tipo;
    moduloActualDocumento = modulo;
    tipoDocumentoActual = tipo;
    
    // Mostrar modal con estado de carga
    mostrarModalCargando();
    
    // Realizar petición AJAX
    const url = `index.php?c=Documento&a=preview&tipo=${tipo}&modulo=${modulo}&_=${Date.now()}`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        // Mostrar contenido en modal
        mostrarContenidoModal(data.titulo, data.contenido);
        
        // Configurar botón de descarga
        const downloadBtn = document.getElementById('descargarDesdeModal');
        if (downloadBtn) {
            downloadBtn.setAttribute('data-document-url', 
                `index.php?c=Documento&a=generar&tipo=${tipo}&modulo=${modulo}`);
        }
        
        mostrarNotificacion('Documento cargado correctamente', 'success');
    })
    .catch(error => {
        console.error('❌ Error cargando documento:', error);
        mostrarErrorModal('Error al cargar el documento', error.message);
    });
}

/**
 * Ver ficha de asistencias
 * @param {number} modulo - Número del módulo
 */
function verFichaAsistencias(modulo) {
    // Verificar si el módulo está completado
    const moduloElement = document.getElementById(`modulo${modulo}`);
    if (!moduloElement) {
        mostrarNotificacion('Módulo no encontrado', 'error');
        return;
    }
    
    const estadoBadge = moduloElement.querySelector('.estado-badge');
    const estado = estadoBadge ? estadoBadge.textContent.toLowerCase() : '';
    
    // Si está en curso, preguntar si quiere ver versión parcial
    if (estado.includes('en curso') || estado.includes('pendiente')) {
        const horasElement = moduloElement.querySelector('.info-value');
        const horasText = horasElement ? horasElement.textContent : '';
        
        Swal.fire({
            title: 'Documento Parcial',
            text: `Este módulo está ${estado}. ¿Deseas ver la versión parcial del documento?`,
            html: horasText ? `<p class="text-sm text-gray-600 mt-2">${horasText}</p>` : '',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Sí, ver parcial',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280'
        }).then((result) => {
            if (result.isConfirmed) {
                verDocumento('asistencias', modulo);
            }
        });
    } else {
        verDocumento('asistencias', modulo);
    }
}

/**
 * Generar ficha de asistencias para descarga
 * @param {number} modulo - Número del módulo
 */
function generarFichaAsistencias(modulo) {
    // Similar a verFichaAsistencias pero con confirmación
    const moduloElement = document.getElementById(`modulo${modulo}`);
    if (!moduloElement) return;
    
    const estadoBadge = moduloElement.querySelector('.estado-badge');
    const estado = estadoBadge ? estadoBadge.textContent.toLowerCase() : '';
    
    let mensaje = '¿Estás seguro de que deseas descargar la ficha de asistencias?';
    
    if (estado.includes('en curso') || estado.includes('pendiente')) {
        mensaje = 'Este módulo no está completado. ¿Deseas descargar la versión parcial?';
    }
    
    Swal.fire({
        title: 'Descargar Documento',
        text: mensaje,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, descargar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir a la URL de descarga
            window.location.href = `index.php?c=Documento&a=generar&tipo=asistencias&modulo=${modulo}`;
        }
    });
}

/**
 * Descargar documento desde modal
 */
function descargarDesdeModal() {
    const downloadBtn = document.getElementById('descargarDesdeModal');
    if (!downloadBtn) return;
    
    const url = downloadBtn.getAttribute('data-document-url');
    if (!url) {
        mostrarNotificacion('No hay documento para descargar', 'error');
        return;
    }
    
    // Mostrar confirmación
    Swal.fire({
        title: 'Descargar Documento',
        text: '¿Estás seguro de que deseas descargar este documento?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, descargar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            // Cerrar modal primero
            cerrarModalDocumento();
            
            // Mostrar notificación de descarga
            mostrarNotificacion('Iniciando descarga...', 'info');
            
            // Pequeño delay para que se cierre el modal
            setTimeout(() => {
                window.location.href = url;
            }, 300);
        }
    });
}

/**
 * Imprimir documento actual
 */
function imprimirDocumento() {
    const preview = document.getElementById('documentoPreview');
    if (!preview) {
        mostrarNotificacion('No hay contenido para imprimir', 'error');
        return;
    }
    
    // Crear ventana de impresión
    const ventanaImpresion = window.open('', '_blank');
    
    // Estilos optimizados para impresión
    const estilosImpresion = `
        <style>
            @media print {
                @page {
                    margin: 1cm;
                    size: A4;
                }
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12pt;
                    line-height: 1.5;
                    color: #000;
                }
                .no-print {
                    display: none !important;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 10pt;
                    page-break-inside: avoid;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 6px;
                    text-align: left;
                }
                th {
                    background-color: #f5f5f5;
                    font-weight: bold;
                }
                .signature-line {
                    height: 1px;
                    background: #000;
                    width: 200px;
                    margin: 20px auto 5px auto;
                }
                .document-header {
                    text-align: center;
                    margin-bottom: 20px;
                    padding-bottom: 15px;
                    border-bottom: 2px solid #000;
                }
                h1, h2, h3 {
                    page-break-after: avoid;
                }
                p {
                    margin-bottom: 10px;
                }
            }
            body {
                padding: 20px;
                max-width: 1000px;
                margin: 0 auto;
            }
        </style>
    `;
    
    ventanaImpresion.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Imprimir Documento - ESFRH</title>
            ${estilosImpresion}
        </head>
        <body>
            ${preview.innerHTML}
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() {
                        window.close();
                    }, 500);
                };
            </script>
        </body>
        </html>
    `);
    
    ventanaImpresion.document.close();
    
    mostrarNotificacion('Preparando impresión...', 'info');
}

// ============================================
// FUNCIONES DEL MODAL
// ============================================

/**
 * Mostrar modal con estado de carga
 */
function mostrarModalCargando() {
    const modal = document.getElementById('modalDocumento');
    const titulo = document.getElementById('modalTituloTexto');
    const preview = document.getElementById('documentoPreview');
    
    if (!modal || !titulo || !preview) return;
    
    titulo.textContent = 'Cargando documento...';
    preview.innerHTML = `
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
            <h3 class="text-lg font-medium text-gray-700 mb-2">Generando vista previa</h3>
            <p class="text-gray-500">Por favor, espera un momento...</p>
        </div>
    `;
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

/**
 * Mostrar contenido en modal
 * @param {string} titulo - Título del documento
 * @param {string} contenido - HTML del contenido
 */
function mostrarContenidoModal(titulo, contenido) {
    const modal = document.getElementById('modalDocumento');
    const tituloElement = document.getElementById('modalTituloTexto');
    const preview = document.getElementById('documentoPreview');
    
    if (!modal || !tituloElement || !preview) return;
    
    tituloElement.textContent = titulo;
    preview.innerHTML = contenido;
    
    // Asegurar que el modal esté visible
    modal.style.display = 'flex';
    
    // Agregar clase de animación
    setTimeout(() => {
        modal.classList.add('modal-visible');
    }, 10);
}

/**
 * Mostrar error en modal
 * @param {string} titulo - Título del error
 * @param {string} mensaje - Mensaje detallado
 */
function mostrarErrorModal(titulo, mensaje) {
    const modal = document.getElementById('modalDocumento');
    const tituloElement = document.getElementById('modalTituloTexto');
    const preview = document.getElementById('documentoPreview');
    
    if (!modal || !tituloElement || !preview) return;
    
    tituloElement.textContent = 'Error';
    preview.innerHTML = `
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-red-700 mb-2">${titulo}</h3>
            <p class="text-gray-600 mb-4">${mensaje}</p>
            <button onclick="cerrarModalDocumento()" class="btn-preview px-4 py-2">
                <i class="fas fa-times mr-2"></i>
                Cerrar
            </button>
        </div>
    `;
    
    modal.style.display = 'flex';
}

/**
 * Cerrar modal de documento
 */
function cerrarModalDocumento() {
    const modal = document.getElementById('modalDocumento');
    if (!modal) return;
    
    // Animación de salida
    modal.classList.remove('modal-visible');
    
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Limpiar variables
        documentoActual = null;
        moduloActualDocumento = null;
        tipoDocumentoActual = null;
        
        // Limpiar botón de descarga
        const downloadBtn = document.getElementById('descargarDesdeModal');
        if (downloadBtn) {
            downloadBtn.removeAttribute('data-document-url');
        }
    }, 300);
}

// ============================================
// FUNCIONES UTILITARIAS
// ============================================

/**
 * Mostrar notificación
 * @param {string} mensaje - Mensaje a mostrar
 * @param {string} tipo - Tipo de notificación (success, error, info, warning)
 */
function mostrarNotificacion(mensaje, tipo = 'info') {
    // Configurar colores según tipo
    const colores = {
        success: {
            bg: 'bg-green-50',
            border: 'border-green-200',
            text: 'text-green-800',
            icon: 'fa-check-circle'
        },
        error: {
            bg: 'bg-red-50',
            border: 'border-red-200',
            text: 'text-red-800',
            icon: 'fa-exclamation-circle'
        },
        info: {
            bg: 'bg-blue-50',
            border: 'border-blue-200',
            text: 'text-blue-800',
            icon: 'fa-info-circle'
        },
        warning: {
            bg: 'bg-amber-50',
            border: 'border-amber-200',
            text: 'text-amber-800',
            icon: 'fa-exclamation-triangle'
        }
    };
    
    const config = colores[tipo] || colores.info;
    
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `fixed top-6 right-6 z-50 max-w-md w-full transform transition-all duration-300 translate-x-full`;
    notification.id = 'notification-' + Date.now();
    
    notification.innerHTML = `
        <div class="${config.bg} ${config.border} border rounded-lg shadow-lg p-4 flex items-start">
            <div class="flex-shrink-0">
                <i class="fas ${config.icon} ${config.text} text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium ${config.text}">${mensaje}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Agregar al DOM
    document.body.appendChild(notification);
    
    // Animación de entrada
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 10);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

/**
 * Actualizar datos del módulo 2
 */
function actualizarDatosModulo2() {
    const modulo2Element = document.getElementById('modulo2');
    if (!modulo2Element) return;
    
    // Aquí podrías hacer una petición AJAX para obtener datos actualizados
    // Por ahora, solo actualizamos la fecha de actualización
    
    const fechaElements = modulo2Element.querySelectorAll('.fecha-actualizacion');
    const fechaActual = new Date().toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    fechaElements.forEach(el => {
        el.textContent = fechaActual;
    });
    
    console.log('📊 Datos del Módulo 2 actualizados');
}

/**
 * Formatear fecha
 * @param {string} fechaStr - Cadena de fecha
 * @returns {string} Fecha formateada
 */
function formatearFecha(fechaStr) {
    if (!fechaStr) return 'No definida';
    
    try {
        const fecha = new Date(fechaStr);
        return fecha.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    } catch (e) {
        return fechaStr;
    }
}

/**
 * Calcular porcentaje
 * @param {number} actual - Valor actual
 * @param {number} total - Valor total
 * @returns {number} Porcentaje
 */
function calcularPorcentaje(actual, total) {
    if (!total || total === 0) return 0;
    return Math.min(Math.round((actual / total) * 100), 100);
}

/**
 * Formatear horas
 * @param {number} horas - Número de horas
 * @returns {string} Horas formateadas
 */
function formatearHoras(horas) {
    if (!horas || horas === 0) return '0 horas';
    
    const dias = Math.floor(horas / 8);
    const horasRestantes = horas % 8;
    
    let resultado = `${horas} horas`;
    if (dias > 0) {
        resultado = `${dias} día${dias > 1 ? 's' : ''}`;
        if (horasRestantes > 0) {
            resultado += ` y ${horasRestantes} horas`;
        }
    }
    
    return resultado;
}

// ============================================
// FUNCIONES DE INTERFAZ
// ============================================

/**
 * Toggle sidebar
 */
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (!sidebar || !mainContent) return;
    
    sidebar.classList.toggle('collapsed');
    
    if (sidebar.classList.contains('collapsed')) {
        mainContent.classList.remove('ml-64');
        mainContent.classList.add('ml-20');
        
        // Guardar preferencia
        localStorage.setItem('sidebar-collapsed', 'true');
    } else {
        mainContent.classList.remove('ml-20');
        mainContent.classList.add('ml-64');
        
        // Guardar preferencia
        localStorage.setItem('sidebar-collapsed', 'false');
    }
    
    // Reajustar tabs si es necesario
    setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
    }, 300);
}

/**
 * Cerrar sesión
 */
function cerrarSesion() {
    Swal.fire({
        title: '¿Cerrar sesión?',
        text: '¿Estás seguro de que deseas salir del sistema?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarNotificacion('Cerrando sesión...', 'info');
            
            // Redirigir a logout
            setTimeout(() => {
                window.location.href = 'index.php?c=Login&a=logout';
            }, 1000);
        }
    });
}

/**
 * Inicializar validaciones de formularios
 */
function inicializarValidaciones() {
    // Validación para formularios de búsqueda si existen
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                
                // Resaltar campos inválidos
                const invalidFields = this.querySelectorAll(':invalid');
                invalidFields.forEach(field => {
                    field.classList.add('border-red-500');
                    
                    // Remover clase después de corrección
                    field.addEventListener('input', function() {
                        if (this.checkValidity()) {
                            this.classList.remove('border-red-500');
                        }
                    });
                });
                
                mostrarNotificacion('Por favor, completa todos los campos requeridos', 'error');
            }
            
            this.classList.add('was-validated');
        });
    });
}

/**
 * Copiar al portapapeles
 * @param {string} texto - Texto a copiar
 * @param {string} mensaje - Mensaje de éxito
 */
function copiarAlPortapapeles(texto, mensaje = 'Copiado al portapapeles') {
    if (!navigator.clipboard) {
        // Fallback para navegadores antiguos
        const textArea = document.createElement('textarea');
        textArea.value = texto;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    } else {
        navigator.clipboard.writeText(texto);
    }
    
    mostrarNotificacion(mensaje, 'success');
}

// ============================================
// FUNCIONES DE CARGA DINÁMICA
// ============================================

/**
 * Cargar más documentos (paginación)
 * @param {number} pagina - Página a cargar
 */
function cargarMasDocumentos(pagina = 1) {
    const contenedor = document.getElementById('historial-documentos');
    const botonCargar = document.getElementById('btn-cargar-mas');
    
    if (!contenedor || !botonCargar) return;
    
    // Mostrar loading
    botonCargar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Cargando...';
    botonCargar.disabled = true;
    
    // Simular carga (en producción sería una petición AJAX)
    setTimeout(() => {
        // Aquí iría la lógica real de carga
        console.log(`Cargando página ${pagina} de documentos...`);
        
        // Restaurar botón
        botonCargar.innerHTML = '<i class="fas fa-plus mr-2"></i> Cargar más documentos';
        botonCargar.disabled = false;
        
        // Incrementar página para próxima carga
        botonCargar.setAttribute('data-pagina', pagina + 1);
        
        mostrarNotificacion('Documentos cargados correctamente', 'success');
    }, 1500);
}

/**
 * Exportar documentos
 * @param {string} formato - Formato de exportación (pdf, excel, csv)
 */
function exportarDocumentos(formato = 'pdf') {
    Swal.fire({
        title: 'Exportar Documentos',
        text: `¿Exportar todos los documentos en formato ${formato.toUpperCase()}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `Sí, exportar ${formato.toUpperCase()}`,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarNotificacion(`Generando archivo ${formato.toUpperCase()}...`, 'info');
            
            // Simular generación de archivo
            setTimeout(() => {
                // En producción, esto redirigiría a la URL de exportación
                // window.location.href = `index.php?c=Documento&a=exportar&formato=${formato}`;
                
                mostrarNotificacion(`Archivo ${formato.toUpperCase()} generado correctamente`, 'success');
            }, 2000);
        }
    });
}

// ============================================
// MANEJO DE ERRORES GLOBALES
// ============================================

// Capturar errores no manejados
window.addEventListener('error', function(e) {
    console.error('❌ Error no manejado:', e.error);
    
    // Solo mostrar notificación en producción para errores críticos
    if (window.location.hostname !== 'localhost') {
        mostrarNotificacion('Ocurrió un error inesperado. Por favor, recarga la página.', 'error');
    }
});

// Manejar promesas rechazadas no capturadas
window.addEventListener('unhandledrejection', function(e) {
    console.error('❌ Promesa rechazada no manejada:', e.reason);
    
    // No mostrar notificación para errores de fetch cancelados
    if (e.reason.name !== 'AbortError') {
        mostrarNotificacion('Error en la solicitud. Por favor, inténtalo de nuevo.', 'error');
    }
});

// ============================================
// EXPORTAR FUNCIONES GLOBALES
// ============================================

// Hacer funciones disponibles globalmente
window.verDocumento = verDocumento;
window.verFichaAsistencias = verFichaAsistencias;
window.generarFichaAsistencias = generarFichaAsistencias;
window.cerrarModalDocumento = cerrarModalDocumento;
window.mostrarNotificacion = mostrarNotificacion;

console.log('🚀 Módulo de Documentos JS cargado correctamente');