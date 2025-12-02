<!-- views/practica/modals/detalles_practica.php -->
<div id="modalDetallesPractica" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto mx-4">
        <div class="modal-header bg-gradient-to-r from-blue-900 to-blue-700 text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Detalles de Práctica
                </h3>
                <button class="cerrar-modal text-white hover:text-blue-200 transition-colors duration-300" data-modal="modalDetallesPractica">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="modal-body p-6" id="contenidoDetalles">
            <!-- Los detalles se cargarán dinámicamente -->
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Cargando detalles...</p>
            </div>
        </div>
        
        <div class="modal-footer bg-gray-50 p-6 border-t border-gray-200 rounded-b-2xl">
            <div class="flex justify-end">
                <button class="cerrar-modal bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors duration-300" data-modal="modalDetallesPractica">
                    <i class="fas fa-times mr-2"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>