<?php require_once 'views/layouts/header.php'; ?>

<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-primary-blue mb-2">Crear Nuevo Documento</h1>
            <p class="text-gray-600">Completa los datos para generar un nuevo documento oficial</p>
        </div>
        
        <!-- Formulario -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form id="formCrearDocumento" method="POST" action="index.php?c=Documento&a=crear">
                <input type="hidden" name="csrf_token" value="<?php echo SessionHelper::getCSRFToken(); ?>">
                
                <!-- Paso 1: Seleccionar Estudiante -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-800 rounded-full h-8 w-8 flex items-center justify-center mr-3">1</span>
                        Seleccionar Estudiante
                    </h3>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estudiante:</label>
                        <select id="selectEstudiante" name="estudiante_id" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Selecciona un estudiante --</option>
                            <?php foreach ($estudiantes as $est): ?>
                                <option value="<?php echo $est['id']; ?>">
                                    <?php echo htmlspecialchars($est['nombre_completo'] . ' - ' . $est['programa']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Prácticas del estudiante (se carga dinámicamente) -->
                    <div id="practicasContainer" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Práctica:</label>
                        <select id="selectPractica" name="practica_id" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Selecciona una práctica --</option>
                        </select>
                        <div id="horasInfo" class="mt-2"></div>
                    </div>
                </div>
                
                <!-- Paso 2: Tipo de Documento -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-800 rounded-full h-8 w-8 flex items-center justify-center mr-3">2</span>
                        Tipo de Documento
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="tipo-documento-option border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-colors"
                             data-tipo="carta_presentacion">
                            <div class="icon-carta icon-documento mx-auto mb-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h4 class="font-bold text-lg mb-2 text-center">Carta de Presentación</h4>
                            <p class="text-sm text-gray-600 text-center">Documento oficial para presentar al estudiante en la empresa</p>
                        </div>
                        
                        <div class="tipo-documento-option border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-green-500 hover:bg-green-50 transition-colors"
                             data-tipo="oficio_multiple">
                            <div class="icon-solicitud icon-documento mx-auto mb-3">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <h4 class="font-bold text-lg mb-2 text-center">Oficio Múltiple</h4>
                            <p class="text-sm text-gray-600 text-center">Solicitud formal para realizar experiencias formativas</p>
                        </div>
                        
                        <div class="tipo-documento-option border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-colors"
                             data-tipo="ficha_identidad">
                            <div class="icon-asistencias icon-documento mx-auto mb-3">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <h4 class="font-bold text-lg mb-2 text-center">Ficha de Identidad</h4>
                            <p class="text-sm text-gray-600 text-center">Registro de asistencias y actividades realizadas</p>
                        </div>
                    </div>
                    <input type="hidden" id="tipoDocumento" name="tipo_documento" required>
                </div>
                
                <!-- Paso 3: Fecha -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-800 rounded-full h-8 w-8 flex items-center justify-center mr-3">3</span>
                        Fecha del Documento
                    </h3>
                    
                    <div class="w-full md:w-1/3">
                        <input type="date" name="fecha_documento" 
                               value="<?php echo date('Y-m-d'); ?>"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <a href="index.php?c=Documento&a=index" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                    <button type="submit" id="btnCrearDocumento" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-file-alt mr-2"></i> Generar Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectEstudiante = document.getElementById('selectEstudiante');
    const practicasContainer = document.getElementById('practicasContainer');
    const selectPractica = document.getElementById('selectPractica');
    const horasInfo = document.getElementById('horasInfo');
    const tipoDocumentoInput = document.getElementById('tipoDocumento');
    const btnCrearDocumento = document.getElementById('btnCrearDocumento');
    
    let tipoDocumentoSeleccionado = '';
    let practicaSeleccionadaId = '';
    let estudianteSeleccionadoId = '';
    
    // Seleccionar tipo de documento
    document.querySelectorAll('.tipo-documento-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remover selección anterior
            document.querySelectorAll('.tipo-documento-option').forEach(opt => {
                opt.classList.remove('border-blue-500', 'bg-blue-50');
            });
            
            // Marcar como seleccionado
            this.classList.add('border-blue-500', 'bg-blue-50');
            tipoDocumentoSeleccionado = this.dataset.tipo;
            tipoDocumentoInput.value = tipoDocumentoSeleccionado;
            
            verificarFormularioCompleto();
        });
    });
    
    // Cambiar estudiante
    selectEstudiante.addEventListener('change', function() {
        estudianteSeleccionadoId = this.value;
        
        if (estudianteSeleccionadoId) {
            cargarPracticasEstudiante(estudianteSeleccionadoId);
            practicasContainer.classList.remove('hidden');
        } else {
            practicasContainer.classList.add('hidden');
            selectPractica.innerHTML = '<option value="">-- Selecciona una práctica --</option>';
            horasInfo.innerHTML = '';
            practicaSeleccionadaId = '';
        }
        
        verificarFormularioCompleto();
    });
    
    // Cambiar práctica
    selectPractica.addEventListener('change', function() {
        practicaSeleccionadaId = this.value;
        
        if (practicaSeleccionadaId) {
            cargarHorasPractica(practicaSeleccionadaId);
        } else {
            horasInfo.innerHTML = '';
        }
        
        verificarFormularioCompleto();
    });
    
    // Función para cargar prácticas del estudiante
    function cargarPracticasEstudiante(estudianteId) {
        fetch(`index.php?c=Documento&a=obtenerPracticasAjax&estudiante_id=${estudianteId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectPractica.innerHTML = '<option value="">-- Selecciona una práctica --</option>';
                    
                    if (data.data.length === 0) {
                        selectPractica.innerHTML += '<option value="" disabled>No hay prácticas registradas</option>';
                        return;
                    }
                    
                    data.data.forEach(practica => {
                        const option = document.createElement('option');
                        option.value = practica.id;
                        option.textContent = `${practica.nombre_modulo} - ${practica.nombre_empresa || 'Sin empresa'}`;
                        option.dataset.horasCompletadas = practica.horas_info?.horas_acumuladas || 0;
                        option.dataset.horasRequeridas = practica.horas_info?.total_horas || 128;
                        selectPractica.appendChild(option);
                    });
                } else {
                    console.error('Error:', data.error);
                    selectPractica.innerHTML = '<option value="" disabled>Error al cargar prácticas</option>';
                }
            })
            .catch(error => {
                console.error('Error al cargar prácticas:', error);
                selectPractica.innerHTML = '<option value="" disabled>Error al cargar prácticas</option>';
            });
    }
    
    // Función para cargar horas de práctica
    function cargarHorasPractica(practicaId) {
        fetch(`index.php?c=Documento&a=verificarHorasAjax&practica_id=${practicaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const horas = data.data;
                    if (horas.completado) {
                        horasInfo.innerHTML = `
                            <div class="p-3 bg-green-100 text-green-800 rounded-lg">
                                <i class="fas fa-check-circle mr-2"></i>
                                Horas completadas: ${horas.horas_acumuladas}/${horas.total_horas} (${horas.porcentaje}%)
                            </div>
                        `;
                    } else {
                        horasInfo.innerHTML = `
                            <div class="p-3 bg-yellow-100 text-yellow-800 rounded-lg">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Horas incompletas: ${horas.horas_acumuladas}/${horas.total_horas} (${horas.porcentaje}%)
                                <div class="text-sm mt-1">
                                    La ficha de identidad solo se puede generar con horas completas
                                </div>
                            </div>
                        `;
                    }
                } else {
                    console.error('Error:', data.error);
                    horasInfo.innerHTML = '<div class="text-red-500">Error al cargar información de horas</div>';
                }
            })
            .catch(error => {
                console.error('Error al cargar horas:', error);
                horasInfo.innerHTML = '<div class="text-red-500">Error al cargar información de horas</div>';
            });
    }
    
    // Verificar si el formulario está completo
    function verificarFormularioCompleto() {
        const formularioCompleto = estudianteSeleccionadoId && 
                                   practicaSeleccionadaId && 
                                   tipoDocumentoSeleccionado;
        
        // Si es ficha de identidad, verificar horas completadas
        if (formularioCompleto && tipoDocumentoSeleccionado === 'ficha_identidad') {
            const optionSeleccionada = selectPractica.selectedOptions[0];
            const horasCompletadas = parseInt(optionSeleccionada?.dataset.horasCompletadas || 0);
            const horasRequeridas = parseInt(optionSeleccionada?.dataset.horasRequeridas || 128);
            
            if (horasCompletadas < horasRequeridas) {
                btnCrearDocumento.disabled = true;
                btnCrearDocumento.title = 'No se puede generar ficha de identidad con horas incompletas';
                return;
            }
        }
        
        btnCrearDocumento.disabled = !formularioCompleto;
        btnCrearDocumento.title = formularioCompleto ? 'Generar documento' : 'Completa todos los campos';
    }
    
    // Manejar envío del formulario
    document.getElementById('formCrearDocumento').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (btnCrearDocumento.disabled) {
            return;
        }
        
        btnCrearDocumento.disabled = true;
        btnCrearDocumento.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generando...';
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = 'index.php?c=Documento&a=vistaPrevia&id=' + data.id;
                }
            } else {
                alert('Error: ' + data.error);
                btnCrearDocumento.disabled = false;
                btnCrearDocumento.innerHTML = '<i class="fas fa-file-alt mr-2"></i> Generar Documento';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al crear documento');
            btnCrearDocumento.disabled = false;
            btnCrearDocumento.innerHTML = '<i class="fas fa-file-alt mr-2"></i> Generar Documento';
        });
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>