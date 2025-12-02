<!-- views/practica/modals/confirmar_eliminar.php -->
<div id="modalConfirmarEliminar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-6 w-96 max-w-md mx-4">
        <div class="text-center">
            <div class="bg-yellow-100 p-4 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-primary-blue mb-2">¿Eliminar Práctica?</h3>
            <p class="text-gray-600 mb-6">
                Esta acción no se puede deshacer. La práctica será eliminada permanentemente del sistema y todos los datos asociados se perderán.
            </p>
            <div class="flex justify-center space-x-3">
                <button id="cancelarEliminar" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors duration-300">
                    Cancelar
                </button>
                <button id="confirmarEliminar" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-300">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>