<!-- views/practica/modals/nueva_practica.php -->
<div id="modalNuevaPractica" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto mx-4">
        <div class="modal-header bg-gradient-to-r from-blue-900 to-blue-700 text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Nueva Práctica
                </h3>
                <button class="cerrar-modal text-white hover:text-blue-200 transition-colors duration-300" data-modal="modalNuevaPractica">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="modal-body p-6">
            <form id="formNuevaPractica">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Estudiante *</label>
                        <select class="form-select" id="nuevoEstudiante" required>
                            <option value="">Seleccionar estudiante</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Empresa *</label>
                        <select class="form-select" id="nuevaEmpresa" required>
                            <option value="">Seleccionar empresa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Docente Supervisor *</label>
                        <select class="form-select" id="nuevoEmpleado" required>
                            <option value="">Seleccionar docente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipo de Módulo *</label>
                        <select class="form-select" id="nuevoTipoModulo" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="modulo1">Módulo 1</option>
                            <option value="modulo2">Módulo 2</option>
                            <option value="modulo3">Módulo 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha de Inicio *</label>
                        <input type="date" class="form-input" id="nuevaFechaInicio" required>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label class="form-label">Área de Ejecución *</label>
                        <input type="text" class="form-input" id="nuevaArea" placeholder="Ej: Laboratorio, Departamento de Sistemas, Área de Recursos Humanos" required>
                        <p class="text-xs text-gray-500 mt-1">Lugar específico dentro de la empresa donde se realiza la práctica</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supervisor de Empresa *</label>
                        <input type="text" class="form-input" id="nuevoSupervisor" placeholder="Nombre del supervisor" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cargo del Supervisor *</label>
                        <input type="text" class="form-input" id="nuevoCargo" placeholder="Ej: Líder de Desarrollo, Gerente de Proyectos" required>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                    <p><strong>Nota:</strong> El estado se establecerá automáticamente como "En curso" y el sistema gestionará las horas acumuladas.</p>
                </div>
            </form>
        </div>
        
        <div class="modal-footer bg-gray-50 p-6 border-t border-gray-200 rounded-b-2xl">
            <div class="flex justify-end space-x-3">
                <button class="cerrar-modal bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors duration-300" data-modal="modalNuevaPractica">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </button>
                <button id="guardarNuevaPractica" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-300">
                    <i class="fas fa-save mr-2"></i> Guardar Práctica
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }
    
    .form-input, .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #0C1F36;
        box-shadow: 0 0 0 3px rgba(12, 31, 54, 0.1);
    }
</style>