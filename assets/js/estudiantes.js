// Configuración global
const API_BASE = 'index.php?c=Estudiante&a=';

// Gestión de Estudiantes - Funcionalidad Principal
class GestionEstudiantes {
    constructor() {
        this.init();
    }

    init() {
        this.inicializarEventListeners();
        this.inicializarGraficos();
    }

    inicializarEventListeners() {
        // Modal de estudiante
        document.getElementById('btnNuevoEstudiante').addEventListener('click', () => this.abrirModalNuevo());
        document.getElementById('cerrarModal').addEventListener('click', () => this.cerrarModalEstudiante());
        document.getElementById('cancelarForm').addEventListener('click', () => this.cerrarModalEstudiante());
        document.getElementById('formEstudiante').addEventListener('submit', (e) => this.guardarEstudiante(e));
        
        // Event listeners para botones de acción
        document.addEventListener('click', (e) => {
            if (e.target.closest('.editar-estudiante')) {
                const id = e.target.closest('.editar-estudiante').getAttribute('data-id');
                this.abrirModalEditar(id);
            }
            
            if (e.target.closest('.ver-estudiante')) {
                const id = e.target.closest('.ver-estudiante').getAttribute('data-id');
                this.verEstudiante(id);
            }
            
            if (e.target.closest('.eliminar-estudiante')) {
                const id = e.target.closest('.eliminar-estudiante').getAttribute('data-id');
                this.eliminarEstudiante(id);
            }
        });
        
        // Búsqueda en tiempo real
        document.getElementById('buscarEstudiante').addEventListener('input', (e) => {
            const termino = e.target.value.toLowerCase();
            if (termino.length > 2) {
                this.buscarEstudiantes(termino);
            } else {
                document.getElementById('resultadosBusqueda').style.display = 'none';
            }
        });

        // Exportar
        document.getElementById('btnExportar').addEventListener('click', () => this.exportarDatos());
        
        // Validación en tiempo real del formulario
        this.inicializarValidacionesFormulario();
    }

    inicializarValidacionesFormulario() {
        const form = document.getElementById('formEstudiante');
        
        // Validar DNI (8 dígitos)
        form.querySelector('#dni_est').addEventListener('input', (e) => {
            const dni = e.target.value;
            if (dni.length === 8 && !/^\d+$/.test(dni)) {
                this.mostrarErrorCampo(e.target, 'El DNI debe contener solo números');
            } else {
                this.limpiarErrorCampo(e.target);
            }
        });

        // Validar celular (9 dígitos)
        form.querySelector('#cel_est').addEventListener('input', (e) => {
            const celular = e.target.value;
            if (celular && (celular.length !== 9 || !/^\d+$/.test(celular))) {
                this.mostrarErrorCampo(e.target, 'El celular debe tener 9 dígitos');
            } else {
                this.limpiarErrorCampo(e.target);
            }
        });

        // Validar emails
        ['#mailp_est', '#maili_est'].forEach(selector => {
            form.querySelector(selector).addEventListener('blur', (e) => {
                const email = e.target.value;
                if (email && !this.validarEmail(email)) {
                    this.mostrarErrorCampo(e.target, 'El email no tiene un formato válido');
                } else {
                    this.limpiarErrorCampo(e.target);
                }
            });
        });
    }

    validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

     mostrarErrorCampo(campo, mensaje) {
        this.limpiarErrorCampo(campo);
        campo.classList.add('border-red-500');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-xs mt-1';
        errorDiv.textContent = mensaje;
        campo.parentNode.appendChild(errorDiv);
    }

    limpiarErrorCampo(campo) {
        campo.classList.remove('border-red-500');
        const errorDiv = campo.parentNode.querySelector('.text-red-500');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    abrirModalNuevo() {
        document.getElementById('modalTitulo').textContent = 'Nuevo Estudiante';
        document.getElementById('formEstudiante').reset();
        document.getElementById('estudianteId').value = '';
        document.getElementById('btnGuardarTexto').textContent = 'Guardar Estudiante';
        document.getElementById('estado').checked = true;
        
        // Limpiar errores
        const form = document.getElementById('formEstudiante');
        form.querySelectorAll('input, select').forEach(campo => {
            this.limpiarErrorCampo(campo);
        });
        
        document.getElementById('estudianteModal').classList.remove('hidden');
    }

     async abrirModalEditar(id) {
        try {
            const response = await fetch(`${API_BASE}detalle&id=${id}`);
            const result = await response.json();
            
            if (result.success) {
                this.llenarFormularioEdicion(result.data);
            } else {
                this.mostrarAlerta('error', result.error);
            }
        } catch (error) {
            this.mostrarAlerta('error', 'Error al cargar datos del estudiante');
        }
    }

    llenarFormularioEdicion(estudiante) {
        document.getElementById('modalTitulo').textContent = 'Editar Estudiante';
        document.getElementById('estudianteId').value = estudiante.id;
        document.getElementById('dni_est').value = estudiante.dni_est;
        document.getElementById('ubdistrito').value = estudiante.ubdistrito || '';
        document.getElementById('ap_est').value = estudiante.ap_est;
        document.getElementById('am_est').value = estudiante.am_est || '';
        document.getElementById('nom_est').value = estudiante.nom_est;
        document.getElementById('sex_est').value = estudiante.sex_est;
        document.getElementById('cel_est').value = estudiante.cel_est || '';
        document.getElementById('ubigeodir_est').value = estudiante.ubigeodir_est || '';
        document.getElementById('ubigeonac_est').value = estudiante.ubigeonac_est || '';
        document.getElementById('dir_est').value = estudiante.dir_est || '';
        document.getElementById('mailp_est').value = estudiante.mailp_est || '';
        document.getElementById('maili_est').value = estudiante.maili_est || '';
        document.getElementById('fecnac_est').value = estudiante.fecnac_est || '';
        document.getElementById('estado').checked = estudiante.estado == 1;
        document.getElementById('btnGuardarTexto').textContent = 'Actualizar Estudiante';
        
        // Limpiar errores
        const form = document.getElementById('formEstudiante');
        form.querySelectorAll('input, select').forEach(campo => {
            this.limpiarErrorCampo(campo);
        });
        
        document.getElementById('estudianteModal').classList.remove('hidden');
    }

     async guardarEstudiante(e) {
        e.preventDefault();
        
        // Validar formulario antes de enviar
        if (!this.validarFormulario()) {
            this.mostrarAlerta('error', 'Por favor, corrige los errores en el formulario');
            return;
        }
        
        const formData = new FormData(e.target);
        const id = formData.get('id');
        const loadingIcon = document.getElementById('loadingIcon');
        const submitBtn = e.target.querySelector('button[type="submit"]');
        
        try {
            loadingIcon.classList.remove('hidden');
            submitBtn.disabled = true;
            
            const url = id ? `${API_BASE}actualizar&id=${id}` : `${API_BASE}crear`;
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.mostrarAlerta('success', result.message);
                this.cerrarModalEstudiante();
                // Recargar la página para ver los cambios
                setTimeout(() => window.location.reload(), 1500);
            } else {
                this.mostrarAlerta('error', result.error);
            }
        } catch (error) {
            this.mostrarAlerta('error', 'Error al guardar estudiante');
        } finally {
            loadingIcon.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

     validarFormulario() {
        let valido = true;
        const form = document.getElementById('formEstudiante');
        
        // Validar campos requeridos
        const camposRequeridos = ['dni_est', 'ap_est', 'nom_est', 'sex_est'];
        camposRequeridos.forEach(campoId => {
            const campo = form.querySelector(`#${campoId}`);
            if (!campo.value.trim()) {
                this.mostrarErrorCampo(campo, 'Este campo es requerido');
                valido = false;
            }
        });
        
        // Validar DNI
        const dni = form.querySelector('#dni_est').value;
        if (dni && (dni.length !== 8 || !/^\d+$/.test(dni))) {
            this.mostrarErrorCampo(form.querySelector('#dni_est'), 'El DNI debe tener 8 dígitos numéricos');
            valido = false;
        }
        
        // Validar emails
        ['mailp_est', 'maili_est'].forEach(campoId => {
            const email = form.querySelector(`#${campoId}`).value;
            if (email && !this.validarEmail(email)) {
                this.mostrarErrorCampo(form.querySelector(`#${campoId}`), 'Email no válido');
                valido = false;
            }
        });
        
        return valido;
    }

    async eliminarEstudiante(id) {
        if (!confirm('¿Estás seguro de que deseas eliminar este estudiante? Esta acción no se puede deshacer.')) {
            return;
        }
        
        try {
            const response = await fetch(`${API_BASE}eliminar&id=${id}`);
            const result = await response.json();
            
            if (result.success) {
                this.mostrarAlerta('success', result.message);
                setTimeout(() => window.location.reload(), 1500);
            } else {
                this.mostrarAlerta('error', result.error);
            }
        } catch (error) {
            this.mostrarAlerta('error', 'Error al eliminar estudiante');
        }
    }

     async verEstudiante(id) {
        try {
            const response = await fetch(`${API_BASE}detalle&id=${id}`);
            const result = await response.json();
            
            if (result.success) {
                this.mostrarDetalleEstudiante(result.data);
            } else {
                this.mostrarAlerta('error', result.error);
            }
        } catch (error) {
            this.mostrarAlerta('error', 'Error al cargar detalles del estudiante');
        }
    }

    mostrarDetalleEstudiante(estudiante) {
        const modal = document.getElementById('detalleEstudianteModal');
        const avatarClass = estudiante.sex_est === 'F' ? 'avatar-estudiante-femenino' : 'avatar-estudiante-masculino';
        const estadoBadge = estudiante.estado == 1 ? 
            '<span class="badge-estado badge-activo">Activo</span>' : 
            '<span class="badge-estado badge-inactivo">Inactivo</span>';
        
        modal.innerHTML = `
            <div class="bg-white rounded-2xl w-full max-w-3xl mx-4 modal-content">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-primary-blue to-blue-800 text-white rounded-t-2xl">
                    <div class="flex items-center">
                        <i class="fas fa-user-graduate text-xl mr-3"></i>
                        <h3 class="text-xl font-bold">Detalles del Estudiante</h3>
                    </div>
                    <button onclick="gestionEstudiantes.cerrarDetalleModal()" class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <div class="flex flex-col md:flex-row items-start md:items-center mb-8 pb-6 border-b border-gray-200">
                        <div class="h-20 w-20 rounded-full flex items-center justify-center text-white font-bold text-2xl mr-0 md:mr-6 mb-4 md:mb-0 shadow-lg ${avatarClass}">
                            ${estudiante.nom_est.charAt(0)}${estudiante.ap_est.charAt(0)}
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-primary-blue mb-1">${estudiante.ap_est} ${estudiante.am_est || ''}, ${estudiante.nom_est}</h2>
                            <div class="flex flex-wrap gap-2 mb-2">
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">${estudiante.nom_progest || 'No asignado'}</span>
                                ${estadoBadge}
                            </div>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-id-card mr-2 text-primary-blue"></i>
                                    <span>${estudiante.dni_est}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-venus-mars mr-2 text-primary-blue"></i>
                                    <span>${estudiante.sex_est === 'M' ? 'Masculino' : 'Femenino'}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-birthday-cake mr-2 text-primary-blue"></i>
                                    <span>${estudiante.fecnac_est ? new Date(estudiante.fecnac_est).toLocaleDateString('es-ES') : 'No especificada'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h4 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
                                <i class="fas fa-address-book mr-2"></i>
                                Información de Contacto
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-mobile-alt mr-2 text-blue-500"></i>
                                        Celular:
                                    </span>
                                    <span class="text-sm text-gray-600 text-right">${estudiante.cel_est || 'No especificado'}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                        Email Personal:
                                    </span>
                                    <span class="text-sm text-gray-600 text-right break-all">${estudiante.mailp_est || 'No especificado'}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-university mr-2 text-blue-500"></i>
                                        Email Institucional:
                                    </span>
                                    <span class="text-sm text-gray-600 text-right break-all">${estudiante.maili_est || 'No especificado'}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h4 class="text-lg font-semibold text-primary-blue mb-4 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Información de Ubicación
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-home mr-2 text-blue-500"></i>
                                        Dirección:
                                    </span>
                                    <span class="text-sm text-gray-600 text-right">${estudiante.dir_est || 'No especificada'}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-map-pin mr-2 text-blue-500"></i>
                                        Ubigeo Dirección:
                                    </span>
                                    <span class="text-sm text-gray-600 text-right">${estudiante.ubigeodir_est || 'No especificado'}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-map-marked-alt mr-2 text-blue-500"></i>
                                        Ubigeo Nacimiento:
                                    </span>
                                    <span class="text-sm text-gray-600 text-right">${estudiante.ubigeonac_est || 'No especificado'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ${estudiante.estado_practica ? `
                    <div class="mt-6 bg-blue-50 rounded-xl p-5 border border-blue-200">
                        <h4 class="text-lg font-semibold text-primary-blue mb-3 flex items-center">
                            <i class="fas fa-briefcase mr-2"></i>
                            Información de Prácticas
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">Estado:</span>
                                <span class="px-3 py-1 rounded-full ${estudiante.estado_practica === 'En curso' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">${estudiante.estado_practica}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">Módulo:</span>
                                <span class="text-gray-600">${estudiante.modulo || 'No asignado'}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">Empresa:</span>
                                <span class="text-gray-600">${estudiante.empresa_practica || 'No asignada'}</span>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-between items-center">
                    <button onclick="gestionEstudiantes.cerrarDetalleModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300 flex items-center">
                        <i class="fas fa-times mr-2"></i> Cerrar
                    </button>
                    <div class="flex space-x-3">
                        <button onclick="gestionEstudiantes.abrirModalEditar(${estudiante.id}); gestionEstudiantes.cerrarDetalleModal()" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors duration-300 flex items-center">
                            <i class="fas fa-edit mr-2"></i> Editar Estudiante
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        modal.classList.remove('hidden');
    }

    cerrarDetalleModal() {
        document.getElementById('detalleEstudianteModal').classList.add('hidden');
    }

     cerrarModalEstudiante() {
        document.getElementById('estudianteModal').classList.add('hidden');
    }
    
    buscarEstudiantes(termino) {
        // Implementar búsqueda en tiempo real si es necesario
        console.log('Buscando:', termino);
    }

    exportarDatos() {
        // Implementar exportación de datos
        this.mostrarAlerta('info', 'Función de exportación en desarrollo');
    }

    mostrarAlerta(tipo, mensaje) {
        // Crear alerta temporal
        const alerta = document.createElement('div');
        alerta.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            tipo === 'success' ? 'bg-green-500 text-white' : 
            tipo === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        alerta.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${tipo === 'success' ? 'check' : tipo === 'error' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
                <span>${mensaje}</span>
            </div>
        `;
        
        document.body.appendChild(alerta);
        
        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    inicializarGraficos() {
        if (typeof estudiantesData === 'undefined') return;
        
        // Gráfico de distribución por programa
        const programasData = estudiantesData.programas || [];
        const estudiantesDataArray = estudiantesData.estudiantes || [];
        
        const programasCount = {};
        estudiantesDataArray.forEach(estudiante => {
            const programa = estudiante.nom_progest || 'No asignado';
            programasCount[programa] = (programasCount[programa] || 0) + 1;
        });
        
        const ctxProgramas = document.getElementById('programasChart');
        if (ctxProgramas) {
            new Chart(ctxProgramas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(programasCount),
                    datasets: [{
                        data: Object.values(programasCount),
                        backgroundColor: ['#0C1F36', '#0dcaf0', '#198754', '#ffc107', '#6c757d', '#fb7185'],
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

        // Gráfico de distribución por género
        const generoCount = {
            'Masculino': estudiantesData.estadisticas?.masculinos || 0,
            'Femenino': estudiantesData.estadisticas?.femeninos || 0
        };
        
        const ctxGenero = document.getElementById('generoChart');
        if (ctxGenero) {
            new Chart(ctxGenero.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: Object.keys(generoCount),
                    datasets: [{
                        data: Object.values(generoCount),
                        backgroundColor: ['#0dcaf0', '#fb7185'],
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
}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.gestionEstudiantes = new GestionEstudiantes();
});