// asistencias-estudiante.js - VERSIÓN COMPLETA Y CORREGIDA
// ==============================
// CLASE PRINCIPAL
// ==============================
class AsistenciasEstudiante {
    constructor() {
        this.modulos = window.datosEstudiante.modulos || {};
        this.practicas = window.datosEstudiante.practicas || {};
        this.asistencias = window.datosEstudiante.asistencias || {};
        this.estudiante = window.datosEstudiante.estudiante || {};
        this.moduloActivo = window.datosEstudiante.moduloActivo || 'modulo1';
        this.baseUrl = window.datosEstudiante.baseUrl || '';
        this.csrfToken = window.datosEstudiante.csrfToken || '';
        
        this.init();
    }
    
    init() {
    console.log('=== DEBUG INICIO ===');
    console.log('Módulos disponibles:', Object.keys(this.modulos));
    console.log('Contenedor módulos existe:', document.getElementById('contenedor-modulos'));
    console.log('Template existe:', document.getElementById('template-modulo'));
    console.log('=== DEBUG FIN ===');
    
    // Cargar módulo activo
    this.cargarModulo(this.moduloActivo);
    
    // Configurar eventos
    this.configurarEventos();
    
    // Configurar formatos de hora
    this.configurarFormatosHora();
}
    
    cargarModulo(moduloId) {
    console.log('Intentando cargar módulo:', moduloId);
    
    const modulo = this.modulos[moduloId];
    if (!modulo) {
        console.error('Módulo no encontrado:', moduloId);
        this.mostrarError('Módulo no encontrado');
        return;
    }
    
    // Actualizar tabs visualmente
    this.actualizarTabs(moduloId);
    
    // Cargar contenido del módulo
    this.cargarContenidoModulo(moduloId, modulo);
    
    // Guardar como módulo activo
    this.moduloActivo = moduloId;
}
    
    actualizarTabs(moduloId) {
    console.log('Actualizando tabs para:', moduloId);
    
    // Remover clase active de todos los tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Agregar clase active al tab seleccionado
    const tabSeleccionado = document.querySelector(`.tab-button[data-tab="${moduloId}"]`);
    if (tabSeleccionado) {
        tabSeleccionado.classList.add('active');
        console.log('Tab activado:', tabSeleccionado);
    }
}
    
    cargarContenidoModulo(moduloId, modulo) {
    console.log('Cargando contenido para módulo:', moduloId);
    
    const contenedor = document.getElementById('contenedor-modulos');
    const template = document.getElementById('template-modulo');
    
    if (!contenedor || !template) {
        console.error('Elementos no encontrados');
        return;
    }
    
    // 1. LIMPIAR contenedor
    contenedor.innerHTML = '';
    
    // 2. Clonar template
    const clone = template.content.cloneNode(true);
    
    // 3. IMPORTANTE: Agregar clase .active al tab-content
    const tabContent = clone.querySelector('.tab-content');
    if (tabContent) {
        tabContent.classList.add('active');
        console.log('✅ Clase .active agregada al tab-content');
    }
    
    // 4. Actualizar datos
    this.actualizarDatosModulo(clone, moduloId, modulo);
    
    // 5. Agregar al DOM
    contenedor.appendChild(clone);
    
    // 6. Cargar asistencias
    this.cargarAsistencias(moduloId);
    
    console.log('✅ Módulo cargado:', moduloId);
    
    // 7. DEBUG: Verificar en consola
    setTimeout(() => {
        const loadedContent = document.querySelector('.tab-content.active');
        console.log('🔍 Tab-content cargado:', loadedContent);
        console.log('🔍 Estilo display:', loadedContent ? window.getComputedStyle(loadedContent).display : 'No encontrado');
    }, 100);
}
    
    actualizarDatosModulo(clone, moduloId, modulo) {
    console.log('Actualizando datos del módulo:', moduloId);
    
    // Icono según estado
    const icono = clone.querySelector('#modulo-icon');
    if (icono) {
        let iconClass = '';
        let iconColor = '';
        
        switch(modulo.estado) {
            case 'completado':
                iconClass = 'fa-check-circle';
                iconColor = 'text-green-500';
                break;
            case 'en_curso':
                iconClass = 'fa-play-circle';
                iconColor = 'text-blue-500';
                break;
            default:
                iconClass = 'fa-clock';
                iconColor = 'text-amber-500';
        }
        
        icono.className = `fas ${iconClass} ${iconColor} mr-2`;
        console.log('Icono actualizado:', icono.className);
    }
    
    // Nombre del módulo
    const nombre = clone.querySelector('#modulo-nombre');
    if (nombre) {
        nombre.textContent = modulo.nombre || `Módulo ${moduloId.charAt(moduloId.length - 1)}`;
        console.log('Nombre actualizado:', nombre.textContent);
    }
    
    // Información del módulo
    this.cargarInfoModulo(clone, moduloId, modulo);
    
    // Estadísticas
    this.cargarEstadisticas(clone, moduloId, modulo);
    
    // Botón de nueva asistencia
    this.cargarBotonAsistencia(clone, moduloId, modulo);
}
    
    // En asistencias-estudiante.js, modifica cargarInfoModulo:
cargarInfoModulo(clone, moduloId, modulo) {
    const contenedorInfo = clone.querySelector('#info-modulo');
    if (!contenedorInfo) return;
    
    const practica = this.practicas[moduloId];
    const itemsInfo = [];
    
    // Estudiante
    itemsInfo.push({
        label: 'Estudiante',
        value: `${this.estudiante.ap_est} ${this.estudiante.am_est || ''}, ${this.estudiante.nom_est}`
    });
    
    // Empresa
    if (practica && practica.razon_social) {
        itemsInfo.push({
            label: 'Empresa',
            value: practica.razon_social
        });
    }
    
    // Docente Supervisor
    if (practica && practica.docente_nombre) {
        itemsInfo.push({
            label: 'Docente Supervisor',
            value: practica.docente_nombre
        });
    }
    
    // Supervisor de Empresa
    if (practica && practica.supervisor_empresa) {
        let supervisor = practica.supervisor_empresa;
        if (practica.cargo_supervisor) {
            supervisor += ` - ${practica.cargo_supervisor}`;
        }
        itemsInfo.push({
            label: 'Supervisor de Empresa',
            value: supervisor
        });
    }
    
    // Área
    if (modulo.area_ejecucion) {
        itemsInfo.push({
            label: 'Área',
            value: modulo.area_ejecucion
        });
    }
    
    // Fecha de Inicio
    if (modulo.fecha_inicio) {
        itemsInfo.push({
            label: 'Fecha de Inicio',
            value: this.formatearFecha(modulo.fecha_inicio)
        });
    }
    
    // Fecha de Fin
    if (modulo.fecha_fin) {
        itemsInfo.push({
            label: 'Fecha de Fin',
            value: this.formatearFecha(modulo.fecha_fin)
        });
    } else if (modulo.estado === 'en_curso') {
        itemsInfo.push({
            label: 'Fecha de Fin',
            value: '<span class="text-amber-600 font-medium">Por completar horas</span>'
        });
    }
    
    // Estado
    let estadoHtml = '';
    let badgeClase = '';
    
    switch(modulo.estado) {
        case 'completado':
            estadoHtml = 'Completado';
            badgeClase = 'badge-success';
            break;
        case 'en_curso':
            estadoHtml = 'En curso';
            badgeClase = 'badge-en-curso';
            break;
        case 'pendiente':
        case 'no_iniciado':
            estadoHtml = 'Pendiente';
            badgeClase = 'badge-pendiente';
            break;
    }
    
    itemsInfo.push({
        label: 'Estado',
        value: `<span class="badge ${badgeClase}">${estadoHtml}</span>`
    });
    
    // Generar HTML con la estructura correcta
    const html = itemsInfo.map(item => `
        <div class="info-item">
            <div class="info-label">${item.label}</div>
            <div class="info-value">${item.value}</div>
        </div>
    `).join('');
    
    contenedorInfo.innerHTML = html;
}
    
    cargarEstadisticas(clone, moduloId, modulo) {
        const asistencias = this.asistencias[moduloId] || [];
        const horasAcumuladas = modulo.horas_acumuladas || 0;
        const horasRequeridas = modulo.horas_requeridas || 128;
        const porcentaje = modulo.porcentaje || 0;
        
        // Formatear horas acumuladas
        const horasFormateadas = horasAcumuladas % 1 === 0 
            ? `${horasAcumuladas}h` 
            : `${Math.floor(horasAcumuladas)}h ${Math.round((horasAcumuladas % 1) * 60)}min`;
        
        // Total asistencias
        const totalAsistencias = clone.querySelector('#total-asistencias');
        if (totalAsistencias) totalAsistencias.textContent = asistencias.length;
        
        // Horas registradas
        const horasRegistradas = clone.querySelector('#horas-registradas');
        if (horasRegistradas) horasRegistradas.textContent = horasFormateadas;
        
        // Porcentaje
        const porcentajeElem = clone.querySelector('#porcentaje-progreso');
        if (porcentajeElem) porcentajeElem.textContent = `${porcentaje}%`;
        
        // Horas actuales
        const horasActuales = clone.querySelector('#horas-actuales');
        if (horasActuales) horasActuales.textContent = horasFormateadas;
        
        // Horas requeridas
        const horasRequeridasElem = clone.querySelector('#horas-requeridas');
        if (horasRequeridasElem) horasRequeridasElem.textContent = `${horasRequeridas}h`;
        
        // Barra de progreso
        const barraProgreso = clone.querySelector('#barra-progreso');
        if (barraProgreso) {
            barraProgreso.style.width = `${porcentaje}%`;
            if (porcentaje >= 100) {
                barraProgreso.classList.add('progress-fill-completed');
                barraProgreso.classList.remove('progress-fill');
            } else {
                barraProgreso.classList.remove('progress-fill-completed');
                barraProgreso.classList.add('progress-fill');
            }
        }
    }
    
    cargarBotonAsistencia(clone, moduloId, modulo) {
    const contenedor = clone.querySelector('#boton-nueva-asistencia');
    if (!contenedor) return;
    
    console.log('Estado del módulo para botón:', modulo.estado, 'Practica ID:', modulo.practica_id);
    
    if (modulo.estado === 'en_curso' && modulo.practica_id) {
        contenedor.innerHTML = `
            <button class="btn-add btn-nueva-asistencia" 
                    data-modulo="${moduloId}" 
                    data-practica="${modulo.practica_id}"
                    onclick="window.asistenciasApp.abrirModalAsistencia('nueva', '${moduloId}', '${modulo.practica_id}')">
                <i class="fas fa-plus"></i>
                Registrar Nueva Asistencia
            </button>
        `;
        console.log('✅ Botón de nueva asistencia creado');
    } else {
        contenedor.innerHTML = `
            <div class="text-gray-500 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                El módulo no está en curso o no tiene práctica asignada
            </div>
        `;
    }
}
    
    cargarAsistencias(moduloId) {
    const contenedor = document.querySelector('#contenedor-asistencias');
    if (!contenedor) return;
    
    const asistencias = this.asistencias[moduloId] || [];
    const modulo = this.modulos[moduloId];
    
    console.log('Cargando asistencias para módulo:', moduloId, 'Total:', asistencias.length);
    
    if (asistencias.length === 0) {
        contenedor.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3 class="text-lg font-medium mb-2">No hay asistencias registradas</h3>
                <p class="text-gray-600 mb-4">Comienza registrando tu primera asistencia</p>
                ${modulo.estado === 'en_curso' && modulo.practica_id ? 
                    `<button class="btn-add" 
                            onclick="window.asistenciasApp.abrirModalAsistencia('nueva', '${moduloId}', '${modulo.practica_id}')">
                        <i class="fas fa-plus"></i>
                        Registrar Primera Asistencia
                    </button>` : 
                    '<p class="text-gray-500 text-sm">El módulo no está disponible para registrar asistencias</p>'
                }
            </div>
        `;
        return;
    }
    
    // Ordenar por fecha (más reciente primero)
    const asistenciasOrdenadas = [...asistencias].sort((a, b) => 
        new Date(b.fecha) - new Date(a.fecha)
    );
    
    // Agrupar por mes
    const agrupadasPorMes = this.agruparAsistenciasPorMes(asistenciasOrdenadas);
    
    let html = '';
    
    for (const [mes, datosMes] of Object.entries(agrupadasPorMes)) {
        html += `
            <div class="asistencias-agrupadas">
                <div class="mes-header">
                    <span>${mes}</span>
                    <span class="mes-total">${datosMes.horas} horas</span>
                </div>
        `;
        
        datosMes.asistencias.forEach(asistencia => {
            const fecha = new Date(asistencia.fecha);
            const diaSemana = this.obtenerNombreDia(fecha.getDay());
            const horaEntrada = this.formatearHora(asistencia.hora_entrada);
            const horaSalida = this.formatearHora(asistencia.hora_salida);
            const esAMEntrada = this.esAM(asistencia.hora_entrada);
            const esAMSalida = this.esAM(asistencia.hora_salida);
            const horas = parseFloat(asistencia.horas_acumuladas) || 0;
            const horasFormateadas = horas % 1 === 0 ? 
                `${horas}h` : 
                `${Math.floor(horas)}h ${Math.round((horas % 1) * 60)}min`;
            
            // Determinar botones según estado del módulo
            let botones = '';
            if (modulo && modulo.estado === 'en_curso') {
                botones = `
                    <button class="btn-icon btn-edit" 
                            onclick="window.asistenciasApp.editarAsistencia('${asistencia.id}', '${moduloId}')"
                            title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon btn-delete" 
                            onclick="window.asistenciasApp.eliminarAsistencia('${asistencia.id}', '${moduloId}')"
                            title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            } else {
                botones = `
                    <button class="btn-icon btn-edit" disabled title="No editable">
                        <i class="fas fa-edit"></i>
                    </button>
                `;
            }
            
            html += `
                <div class="dia-row" data-id="${asistencia.id}">
                    <div class="dia-fecha">
                        ${this.formatearFecha(asistencia.fecha)}
                        <span class="dia-dia">(${diaSemana})</span>
                    </div>
                    <div class="dia-horas">
                        <div class="hora-entrada">
                            <span class="hora-badge">${esAMEntrada ? 'AM' : 'PM'}</span>
                            ${horaEntrada}
                        </div>
                        <div class="hora-salida">
                            <span class="hora-badge">${esAMSalida ? 'AM' : 'PM'}</span>
                            ${horaSalida}
                        </div>
                    </div>
                    <div class="dia-actividad">${this.escapeHtml(asistencia.actividad || '')}</div>
                    <div class="dia-total horas-formateadas">${horasFormateadas}</div>
                    <div class="action-buttons">
                        ${botones}
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
    }
    
    contenedor.innerHTML = html;
    console.log('✅ Asistencias cargadas:', asistencias.length, 'registros');
}
    
    configurarEventos() {
    // Delegación de eventos para tabs
    document.addEventListener('click', (e) => {
        // Tabs de módulos
        if (e.target.closest('.tab-button')) {
            const tab = e.target.closest('.tab-button');
            const moduloId = tab.getAttribute('data-tab');
            
            // Solo si no está activo
            if (!tab.classList.contains('active')) {
                this.cargarModulo(moduloId);
            }
        }
        
        // Botón logout
        if (e.target.closest('#logoutBtnSidebar')) {
            e.preventDefault();
            this.mostrarConfirmacionLogout();
        }
    });
    
    // Toggle sidebar
    const toggleSidebar = document.getElementById('toggleSidebar');
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', () => this.toggleSidebar());
    }
}
    
    configurarEventosModulo(moduloId) {
        // Delegación de eventos para botones dinámicos
        document.addEventListener('click', (e) => {
            // Nueva asistencia
            if (e.target.closest('.btn-nueva-asistencia')) {
                const button = e.target.closest('.btn-nueva-asistencia');
                const practicaId = button.getAttribute('data-practica');
                this.abrirModalAsistencia('nueva', moduloId, practicaId);
            }
            
            // Primera asistencia
            if (e.target.closest('.btn-primera-asistencia')) {
                const modulo = this.modulos[moduloId];
                if (modulo && modulo.practica_id) {
                    this.abrirModalAsistencia('nueva', moduloId, modulo.practica_id);
                }
            }
        });
    }
    
    configurarEventosAsistencias() {
        // Delegación de eventos para botones de asistencias
        document.addEventListener('click', (e) => {
            // Editar
            if (e.target.closest('.btn-edit') && !e.target.closest('.btn-edit:disabled')) {
                const button = e.target.closest('.btn-edit');
                const asistenciaId = button.getAttribute('data-id');
                const moduloId = button.getAttribute('data-modulo');
                this.editarAsistencia(asistenciaId, moduloId);
            }
            
            // Eliminar
            if (e.target.closest('.btn-delete')) {
                const button = e.target.closest('.btn-delete');
                const asistenciaId = button.getAttribute('data-id');
                const moduloId = button.getAttribute('data-modulo');
                this.eliminarAsistencia(asistenciaId, moduloId);
            }
        });
    }
    
    configurarFormatosHora() {
        // Toggle formatos
        document.addEventListener('click', (e) => {
            if (e.target.closest('.toggle-format-btn')) {
                const button = e.target.closest('.toggle-format-btn');
                const target = button.getAttribute('data-target');
                this.toggleFormatoHora(target);
            }
        });
        
        // Actualizar previsualización
        ['horaEntrada', 'horaSalida'].forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', () => this.actualizarPrevisualizacion());
            }
        });
        
        // Calcular horas al cambiar fecha
        const fechaInput = document.getElementById('fecha');
        if (fechaInput) {
            fechaInput.addEventListener('change', () => this.actualizarPrevisualizacion());
        }
    }
    
    // ==============================
    // MÉTODOS PARA MODAL ASISTENCIA
    // ==============================
    
    abrirModalAsistencia(accion, moduloId, practicaId = null) {
    console.log('Abriendo modal:', { accion, moduloId, practicaId });
    
    const modal = document.getElementById('modalAsistencia');
    const form = document.getElementById('formAsistencia');
    const titulo = document.getElementById('modal-titulo');
    
    if (!modal || !form || !titulo) {
        console.error('Elementos del modal no encontrados');
        return;
    }
    
    // 1. Limpiar formulario
    form.reset();
    document.getElementById('asistenciaId').value = '';
    
    // 2. Configurar práctica
    if (practicaId) {
        document.getElementById('practicaId').value = practicaId;
    }
    
    // 3. Configurar título
    const tituloTexto = document.querySelector('#modal-titulo-texto');
    if (tituloTexto) {
        tituloTexto.textContent = accion === 'nueva' ? 
            'Registrar Nueva Asistencia' : 'Editar Asistencia';
    }
    
    // 4. Establecer fecha actual por defecto (máximo hoy)
    const hoy = new Date().toISOString().split('T')[0];
    const fechaInput = document.getElementById('fecha');
    if (fechaInput) {
        fechaInput.value = hoy;
        fechaInput.max = hoy;
    }
    
    // 5. Establecer horas por defecto
    document.getElementById('horaEntrada').value = '08:00';
    document.getElementById('horaSalida').value = '16:00';
    
    // 6. Limpiar actividad
    document.getElementById('actividad').value = '';
    
    // 7. Actualizar previsualización
    this.actualizarPrevisualizacion();
    
    // 8. Mostrar modal
    modal.style.display = 'flex';
    console.log('✅ Modal abierto en modo:', accion);
    
    // 9. Enfocar primer campo
    setTimeout(() => {
        fechaInput.focus();
    }, 100);
}
    
    cerrarModalAsistencia() {
        const modal = document.getElementById('modalAsistencia');
        if (modal) {
            modal.style.display = 'none';
        }
    }
    
    async editarAsistencia(asistenciaId, moduloId) {
    console.log('Solicitando edición de asistencia:', asistenciaId);
    
    try {
        // Mostrar loading
        this.mostrarNotificacion('Cargando asistencia...', 'info');
        
        // Obtener datos de la asistencia
        const response = await fetch(`${this.baseUrl}obtener&id=${asistenciaId}`);
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            const asistencia = data.data;
            console.log('Asistencia recibida:', asistencia);
            
            // Abrir modal en modo edición
            this.abrirModalAsistencia('editar', moduloId, asistencia.practicas);
            
            // Llenar formulario con los datos
            document.getElementById('asistenciaId').value = asistencia.id;
            document.getElementById('practicaId').value = asistencia.practicas;
            document.getElementById('fecha').value = asistencia.fecha;
            
            // Formatear hora para mostrar (quitar segundos)
            if (asistencia.hora_entrada) {
                const horaEntrada = asistencia.hora_entrada.substring(0, 5);
                document.getElementById('horaEntrada').value = horaEntrada;
            }
            
            if (asistencia.hora_salida) {
                const horaSalida = asistencia.hora_salida.substring(0, 5);
                document.getElementById('horaSalida').value = horaSalida;
            }
            
            document.getElementById('actividad').value = asistencia.actividad || '';
            
            // Actualizar previsualización
            this.actualizarPrevisualizacion();
            
            console.log('✅ Formulario cargado para edición');
        } else {
            throw new Error(data.error || 'Error al cargar la asistencia');
        }
    } catch (error) {
        console.error('Error al cargar asistencia:', error);
        this.mostrarError(`Error: ${error.message}`);
    }
}
    
    async eliminarAsistencia(asistenciaId, moduloId) {
    console.log('Solicitando eliminación de asistencia:', asistenciaId);
    
    // 1. Mostrar confirmación
    const confirmacion = await this.mostrarConfirmacion(
        '¿Estás seguro de que deseas eliminar esta asistencia? Esta acción no se puede deshacer.'
    );
    
    if (!confirmacion) {
        console.log('Eliminación cancelada por el usuario');
        return;
    }
    
    try {
        // 2. Preparar datos
        const formData = new FormData();
        formData.append('id', asistenciaId);
        formData.append('csrf_token', this.csrfToken);
        
        // 3. Enviar petición
        const response = await fetch(`${this.baseUrl}eliminar`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        // 4. Procesar respuesta
        if (data.success) {
            this.mostrarExito(data.message || 'Asistencia eliminada correctamente');
            
            // RECARGAR PÁGINA COMPLETA después de 1 segundo
            setTimeout(() => {
                console.log('🔄 Recargando página...');
                window.location.reload();
            }, 1000);
            
        } else {
            throw new Error(data.error || 'Error al eliminar la asistencia');
        }
        
    } catch (error) {
        console.error('Error al eliminar asistencia:', error);
        this.mostrarError(error.message || 'Error de conexión');
    }
}
    
    async guardarAsistencia() {
    try {
        console.log('=== REGISTRO DE ASISTENCIA ===');
        
        const form = document.getElementById('formAsistencia');
        const asistenciaId = document.getElementById('asistenciaId').value;
        
        // 1. Validar formulario
        if (!form.checkValidity()) {
            form.reportValidity();
            this.mostrarError('Complete todos los campos requeridos');
            return;
        }
        
        // 2. Obtener datos del formulario
        const fechaElement = document.getElementById('fecha');
        let fecha = fechaElement.value; // El input date devuelve "YYYY-MM-DD"
        
        console.log('📋 DEPURACIÓN DE FECHA:');
        console.log('- Valor directo del input date:', fecha);
        console.log('- Tipo del valor:', typeof fecha);
        
        // VALIDACIÓN CRÍTICA: Si el input date está vacío o mal formado
        if (!fecha || !/^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
            this.mostrarError('Fecha no válida. Use el selector de fecha o ingrese en formato YYYY-MM-DD');
            return;
        }
        
        // El input type="date" ya devuelve YYYY-MM-DD, ¡NO CONVERTIRLO!
        // Solo validamos y usamos directamente
        const fechaFormateada = fecha; // Ya está en YYYY-MM-DD
        
        console.log('📅 Fecha a enviar (directo del input):', fechaFormateada);
        
        // Verificar que no sea fecha futura (comparación simple de strings)
        const hoy = new Date();
        const hoyFormateado = hoy.toISOString().split('T')[0]; // YYYY-MM-DD
        
        console.log('- Hoy (YYYY-MM-DD):', hoyFormateado);
        
        if (fechaFormateada > hoyFormateado) {
            this.mostrarError('No puedes registrar asistencias con fecha futura');
            return;
        }
        
        // 3. Obtener resto de datos
        const horaEntradaInput = document.getElementById('horaEntrada').value.trim();
        const horaSalidaInput = document.getElementById('horaSalida').value.trim();
        const practicaId = document.getElementById('practicaId').value;
        const actividad = document.getElementById('actividad').value.trim();
        
        // 4. Validar que tengamos un practicaId válido
        if (!practicaId) {
            this.mostrarError('Error: No se encontró el ID de la práctica.');
            return;
        }
        
        // 5. Parsear horas a formato 24h
        const horaEntrada24 = this.parseHoraInput(horaEntradaInput);
        const horaSalida24 = this.parseHoraInput(horaSalidaInput);
        
        if (!horaEntrada24) {
            this.mostrarError('Formato de hora de entrada no válido. Use: 8:15, 08:15, 8.15am o 2.25pm');
            return;
        }
        
        if (!horaSalida24) {
            this.mostrarError('Formato de hora de salida no válido. Use: 16:45, 4.45pm o 17:30');
            return;
        }
        
        // 6. Calcular horas
        const [h1, m1] = horaEntrada24.split(':').map(Number);
        const [h2, m2] = horaSalida24.split(':').map(Number);
        
        let horas = h2 - h1;
        let minutos = m2 - m1;
        
        if (minutos < 0) {
            horas -= 1;
            minutos += 60;
        }
        
        const horasDecimal = horas + (minutos / 60);
        
        if (horasDecimal <= 0) {
            this.mostrarError('La hora de salida debe ser mayor a la hora de entrada');
            return;
        }
        
        if (horasDecimal > 12) {
            this.mostrarError('La jornada no puede exceder las 12 horas diarias');
            return;
        }
        
        // 7. Preparar FormData - ENVIAR FECHA DIRECTAMENTE
        const formData = new FormData();
        formData.append('csrf_token', this.csrfToken);
        formData.append('practica_id', practicaId);
        formData.append('fecha', fechaFormateada);  // ← Ya está en YYYY-MM-DD
        formData.append('hora_entrada', horaEntrada24 + ':00');
        formData.append('hora_salida', horaSalida24 + ':00');
        formData.append('actividad', actividad);
        formData.append('horas_acumuladas', horasDecimal.toString());
        
        // Si es edición
        if (asistenciaId) {
            formData.append('id', asistenciaId);
        }
        
        // DEBUG: Mostrar todo lo que se envía
        console.log('📦 DATOS A ENVIAR AL SERVIDOR:');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}: ${value}`);
        }
        
        // 8. Determinar endpoint
        const endpoint = asistenciaId ? 'actualizar' : 'registrar';
        const url = `${this.baseUrl}${endpoint}`;
        
        console.log('🌐 Enviando a:', url);
        
        // 9. Mostrar loading
        const guardarBtn = document.getElementById('guardarAsistencia');
        const originalText = guardarBtn.innerHTML;
        guardarBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';
        guardarBtn.disabled = true;
        
        // 10. Enviar petición
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });
        
        console.log('📥 Status respuesta:', response.status);
        
        // 11. Procesar respuesta
        let data;
        try {
            const text = await response.text();
            console.log('📄 Respuesta texto:', text);
            data = JSON.parse(text);
            console.log('📄 Respuesta JSON:', data);
        } catch (parseError) {
            console.error('❌ Error parseando respuesta:', parseError);
            throw new Error('Respuesta del servidor no válida');
        }
        
        // 12. Restaurar botón
        guardarBtn.innerHTML = originalText;
        guardarBtn.disabled = false;
        
        if (data.success) {
            console.log('✅ Éxito:', data);
            this.mostrarExito(data.message || 'Asistencia guardada correctamente');
            
            // Cerrar modal
            this.cerrarModalAsistencia();
            
            // RECARGAR PÁGINA COMPLETA después de 1 segundo
            setTimeout(() => {
                console.log('🔄 Recargando página...');
                window.location.reload();
            }, 1000);
            
        } else {
            console.error('❌ Error del servidor:', data);
            this.mostrarError(data.error || 'Error al guardar la asistencia');
        }
        
    } catch (error) {
        console.error('🔥 Error completo:', error);
        this.mostrarError('Error: ' + error.message);
        
        // Restaurar botón
        const guardarBtn = document.getElementById('guardarAsistencia');
        if (guardarBtn) {
            guardarBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Guardar Asistencia';
            guardarBtn.disabled = false;
        }
    }
}
    
    // ==============================
    // MÉTODOS UTILITARIOS
    // ==============================
    
    agruparAsistenciasPorMes(asistencias) {
        const agrupadas = {};
        
        asistencias.forEach(asistencia => {
            const fecha = new Date(asistencia.fecha);
            const mes = fecha.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
            const mesCapitalizado = mes.charAt(0).toUpperCase() + mes.slice(1);
            
            if (!agrupadas[mesCapitalizado]) {
                agrupadas[mesCapitalizado] = {
                    asistencias: [],
                    horas: 0
                };
            }
            
            agrupadas[mesCapitalizado].asistencias.push(asistencia);
            agrupadas[mesCapitalizado].horas += parseFloat(asistencia.horas_acumuladas) || 0;
        });
        
        return agrupadas;
    }
    
    formatearFecha(fechaStr) {
        if (!fechaStr) return '';
        const fecha = new Date(fechaStr);
        return fecha.toLocaleDateString('es-ES');
    }
    
    formatearHora(horaStr) {
        if (!horaStr) return '';
        const hora = new Date(`2000-01-01T${horaStr}`);
        return hora.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
    }
    
    obtenerNombreDia(diaNumero) {
        const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return dias[diaNumero] || '';
    }
    
    esAM(horaStr) {
        if (!horaStr) return true;
        const hora = parseInt(horaStr.split(':')[0]);
        return hora < 12;
    }
    
    parseHoraInput(horaInput) {
        if (!horaInput) return null;
        
        // Limpiar espacios y convertir a minúsculas
        let input = horaInput.trim().toLowerCase();
        
        // Si ya está en formato HH:MM, retornar directamente
        if (/^\d{1,2}:\d{2}$/.test(input)) {
            const [horas, minutos] = input.split(':').map(Number);
            if (horas >= 0 && horas <= 23 && minutos >= 0 && minutos <= 59) {
                return `${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}`;
            }
        }
        
        // Si está en formato decimal con am/pm (ej: 2.25pm, 8.30am)
        const matchDecimal = input.match(/^(\d+(?:\.\d+)?)(am|pm)$/);
        if (matchDecimal) {
            const valor = parseFloat(matchDecimal[1]);
            const esPM = matchDecimal[2] === 'pm';
            
            const horas = Math.floor(valor);
            const minutosDecimal = valor - horas;
            const minutos = Math.round(minutosDecimal * 60);
            
            let hora24 = esPM && horas !== 12 ? horas + 12 : horas;
            if (!esPM && horas === 12) hora24 = 0;
            
            return `${hora24.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}`;
        }
        
        // Si está en formato con puntos y am/pm (ej: 2.25 pm, 8.30 am)
        const matchDecimalEspacio = input.match(/^(\d+(?:\.\d+)?)\s*(am|pm)$/);
        if (matchDecimalEspacio) {
            const valor = parseFloat(matchDecimalEspacio[1]);
            const esPM = matchDecimalEspacio[2] === 'pm';
            
            const horas = Math.floor(valor);
            const minutosDecimal = valor - horas;
            const minutos = Math.round(minutosDecimal * 60);
            
            let hora24 = esPM && horas !== 12 ? horas + 12 : horas;
            if (!esPM && horas === 12) hora24 = 0;
            
            return `${hora24.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}`;
        }
        
        return null;
    }
    
    actualizarPrevisualizacion() {
        const entrada = document.getElementById('horaEntrada');
        const salida = document.getElementById('horaSalida');
        const previsualizacion = document.getElementById('previsualizacionHoras');
        const horasCalculadas = document.getElementById('horasCalculadas');
        
        if (!entrada || !salida || !previsualizacion || !horasCalculadas) return;
        
        const horaEntrada = entrada.value.trim();
        const horaSalida = salida.value.trim();
        
        if (horaEntrada && horaSalida) {
            // Convertir a formato 24h para cálculo
            const horaEntrada24 = this.parseHoraInput(horaEntrada);
            const horaSalida24 = this.parseHoraInput(horaSalida);
            
            if (horaEntrada24 && horaSalida24) {
                // Calcular diferencia
                const [h1, m1] = horaEntrada24.split(':').map(Number);
                const [h2, m2] = horaSalida24.split(':').map(Number);
                
                let horas = h2 - h1;
                let minutos = m2 - m1;
                
                if (minutos < 0) {
                    horas -= 1;
                    minutos += 60;
                }
                
                const horasDecimal = horas + (minutos / 60);
                const horasFormateadas = this.formatearHorasDecimal(horasDecimal);
                
                // Actualizar previsualización
                document.getElementById('prevEntrada').textContent = horaEntrada24;
                document.getElementById('prevEntradaAMPM').textContent = this.getAMPM(h1);
                document.getElementById('prevSalida').textContent = horaSalida24;
                document.getElementById('prevSalidaAMPM').textContent = this.getAMPM(h2);
                document.getElementById('prevTotal').textContent = horasFormateadas;
                horasCalculadas.value = horasFormateadas;
                
                previsualizacion.classList.remove('hidden');
                return;
            }
        }
        
        previsualizacion.classList.add('hidden');
        horasCalculadas.value = '';
    }
    
    getAMPM(hora) {
        return hora < 12 ? 'AM' : 'PM';
    }
    
    formatearHorasDecimal(horas) {
        const horasEnteras = Math.floor(horas);
        const minutos = Math.round((horas - horasEnteras) * 60);
        
        if (minutos === 0) {
            return `${horasEnteras}h`;
        } else if (horasEnteras === 0) {
            return `${minutos} min`;
        } else {
            return `${horasEnteras}h ${minutos} min`;
        }
    }
    
    toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (sidebar && mainContent) {
            sidebar.classList.toggle('collapsed');
            
            if (sidebar.classList.contains('collapsed')) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-20');
            } else {
                mainContent.classList.remove('ml-20');
                mainContent.classList.add('ml-64');
            }
        }
    }
    
    async mostrarConfirmacion(mensaje) {
        return new Promise((resolve) => {
            const modal = document.getElementById('modalConfirmacion');
            const mensajeElem = document.getElementById('mensajeConfirmacion');
            const cancelarBtn = document.getElementById('cancelarAccion');
            const confirmarBtn = document.getElementById('confirmarAccion');
            
            if (!modal || !mensajeElem || !cancelarBtn || !confirmarBtn) {
                resolve(false);
                return;
            }
            
            mensajeElem.textContent = mensaje;
            
            const limpiarEventos = () => {
                cancelarBtn.onclick = null;
                confirmarBtn.onclick = null;
            };
            
            cancelarBtn.onclick = () => {
                limpiarEventos();
                modal.style.display = 'none';
                resolve(false);
            };
            
            confirmarBtn.onclick = () => {
                limpiarEventos();
                modal.style.display = 'none';
                resolve(true);
            };
            
            modal.style.display = 'flex';
        });
    }
    
    mostrarExito(mensaje) {
        this.mostrarNotificacion(mensaje, 'success');
    }
    
    mostrarError(mensaje) {
        this.mostrarNotificacion(mensaje, 'error');
    }
    
    mostrarNotificacion(mensaje, tipo = 'info') {
        const contenedor = document.getElementById('notificaciones-container');
        if (!contenedor) return;
        
        const notificacion = document.createElement('div');
        notificacion.className = `notification ${tipo}`;
        
        let icono = '';
        switch(tipo) {
            case 'success':
                icono = 'fa-check-circle';
                break;
            case 'error':
                icono = 'fa-exclamation-circle';
                break;
            default:
                icono = 'fa-info-circle';
        }
        
        notificacion.innerHTML = `
            <div class="notification-icon">
                <i class="fas ${icono}"></i>
            </div>
            <div class="notification-content">
                <p class="notification-message">${this.escapeHtml(mensaje)}</p>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        contenedor.appendChild(notificacion);
        
        // Mostrar con animación
        setTimeout(() => {
            notificacion.classList.add('show');
        }, 10);
        
        // Configurar cierre
        const closeBtn = notificacion.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            this.cerrarNotificacion(notificacion);
        });
        
        // Cerrar automáticamente después de 5 segundos
        setTimeout(() => {
            if (notificacion.parentNode) {
                this.cerrarNotificacion(notificacion);
            }
        }, 5000);
    }
    
    cerrarNotificacion(notificacion) {
        notificacion.classList.remove('show');
        setTimeout(() => {
            if (notificacion.parentNode) {
                notificacion.parentNode.removeChild(notificacion);
            }
        }, 400);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    toggleFormatoHora(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    const valor = input.value.trim();
    if (!valor) return;
    
    // Intentar detectar formato actual
    if (valor.includes('.') || valor.includes('am') || valor.includes('pm')) {
        // Convertir de decimal/am/pm a 24h
        const hora24 = this.parseHoraInput(valor);
        if (hora24) {
            input.value = hora24;
        }
    } else {
        // Convertir de 24h a decimal con am/pm
        const horaDecimal = this.formatearHoraDecimal(valor);
        if (horaDecimal) {
            input.value = horaDecimal;
        }
    }
    
    this.actualizarPrevisualizacion();
}

formatearHoraDecimal(hora24) {
    if (!hora24) return '';
    
    const [horas, minutos] = hora24.split(':').map(Number);
    const ampm = horas >= 12 ? 'pm' : 'am';
    
    let horas12 = horas % 12;
    if (horas12 === 0) horas12 = 12;
    
    if (minutos === 0) {
        return `${horas12}${ampm}`;
    }
    
    const minutosDecimal = minutos / 60;
    const horaDecimal = horas12 + minutosDecimal;
    const horaRedondeada = Math.round(horaDecimal * 100) / 100;
    
    return `${horaRedondeada}${ampm}`;
}
}

// ==============================
// INICIALIZACIÓN
// ==============================
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM cargado, inicializando aplicación...');
    
    // Verificar datos
    if (!window.datosEstudiante) {
        console.error('No se encontraron datos del estudiante');
        this.mostrarError('Error al cargar los datos del estudiante');
        return;
    }
    
    // Inicializar aplicación
    window.asistenciasApp = new AsistenciasEstudiante();
    
    // ==================== CONFIGURAR EVENTOS DEL MODAL ====================
    const modal = document.getElementById('modalAsistencia');
    if (modal) {
        // Cerrar modal al hacer clic fuera
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                window.asistenciasApp.cerrarModalAsistencia();
            }
        });
        
        // Botón cerrar (X)
        const cerrarBtn = document.getElementById('cerrarModal');
        if (cerrarBtn) {
            cerrarBtn.addEventListener('click', () => {
                window.asistenciasApp.cerrarModalAsistencia();
            });
        }
        
        // Botón cancelar
        const cancelarBtn = document.getElementById('cancelarAsistencia');
        if (cancelarBtn) {
            cancelarBtn.addEventListener('click', () => {
                window.asistenciasApp.cerrarModalAsistencia();
            });
        }
        
        // Botón guardar
        const guardarBtn = document.getElementById('guardarAsistencia');
        if (guardarBtn) {
            guardarBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                await window.asistenciasApp.guardarAsistencia();
            });
        }
        
        // Actualizar previsualización al cambiar horas
        const horaEntrada = document.getElementById('horaEntrada');
        const horaSalida = document.getElementById('horaSalida');
        
        if (horaEntrada && horaSalida) {
            horaEntrada.addEventListener('input', () => {
                window.asistenciasApp.actualizarPrevisualizacion();
            });
            horaSalida.addEventListener('input', () => {
                window.asistenciasApp.actualizarPrevisualizacion();
            });
        }
    }
    
    // ==================== CONFIGURAR EVENTOS DE FORMATO ====================
    const toggleEntrada = document.querySelector('[data-target="horaEntrada"]');
    const toggleSalida = document.querySelector('[data-target="horaSalida"]');
    
    if (toggleEntrada) {
        toggleEntrada.addEventListener('click', () => {
            window.asistenciasApp.toggleFormatoHora('horaEntrada');
        });
    }
    
    if (toggleSalida) {
        toggleSalida.addEventListener('click', () => {
            window.asistenciasApp.toggleFormatoHora('horaSalida');
        });
    }
    
    console.log('✅ Aplicación inicializada correctamente');
});
