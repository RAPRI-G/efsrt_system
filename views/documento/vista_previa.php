<?php require_once 'views/layouts/header.php'; ?>

<div class="main-content ml-64 mt-16 p-6 transition-all duration-300">
    <div class="max-w-6xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-primary-blue mb-2">Vista Previa del Documento</h1>
                <p class="text-gray-600">Documento generado para <?php echo htmlspecialchars($documento['nombre_estudiante']); ?></p>
            </div>
            <div class="flex space-x-3">
                <button onclick="window.print()" class="btn-imprimir">
                    <i class="fas fa-print mr-2"></i> Imprimir
                </button>
                <a href="index.php?c=Documento&a=descargarPDF&id=<?php echo $documento['id']; ?>" 
                   class="btn-descargar">
                    <i class="fas fa-download mr-2"></i> Descargar PDF
                </a>
                <a href="index.php?c=Documento&a=index" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>
        
        <!-- Información del documento -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Tipo de Documento</p>
                    <p class="font-medium">
                        <?php 
                        $tipos = [
                            'carta_presentacion' => 'Carta de Presentación',
                            'oficio_multiple' => 'Oficio Múltiple', 
                            'ficha_identidad' => 'Ficha de Identidad'
                        ];
                        echo $tipos[$documento['tipo_documento']] ?? 'Documento';
                        ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Estudiante</p>
                    <p class="font-medium"><?php echo htmlspecialchars($documento['nombre_estudiante']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Módulo</p>
                    <p class="font-medium"><?php echo htmlspecialchars($documento['modulo']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Estado</p>
                    <p class="font-medium">
                        <?php if ($documento['estado'] == 'generado'): ?>
                            <span class="badge-enviado">Enviado</span>
                        <?php else: ?>
                            <span class="badge-pendiente">Pendiente</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <?php if ($documento['estado'] == 'pendiente'): ?>
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-3 text-xl"></i>
                        <div>
                            <p class="font-medium text-blue-800">Documento listo para enviar</p>
                            <p class="text-sm text-blue-600">
                                Este documento está pendiente de envío. Puedes enviarlo al estudiante para que lo imprima y firme.
                            </p>
                        </div>
                        <div class="ml-auto">
                            <button onclick="enviarDocumento(<?php echo $documento['id']; ?>)" class="btn-enviar">
                                <i class="fas fa-paper-plane mr-2"></i> Enviar al Estudiante
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Vista previa del contenido -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="vista-previa-documento p-8">
                <?php echo $documento['contenido']; ?>
            </div>
        </div>
        
        <!-- Información adicional para fichas de identidad -->
        <?php if ($documento['tipo_documento'] == 'ficha_identidad' && !empty($horas_info)): ?>
            <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Información de Horas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Horas Acumuladas</p>
                        <p class="text-2xl font-bold text-blue-600"><?php echo $horas_info['horas_acumuladas']; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Horas Requeridas</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo $horas_info['total_horas']; ?></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500 mb-2">Progreso</p>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-green-500 h-4 rounded-full" 
                                 style="width: <?php echo min($horas_info['porcentaje'], 100); ?>%">
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            <?php echo $horas_info['porcentaje']; ?>% completado
                            <?php if ($horas_info['completado']): ?>
                                <span class="text-green-600 font-medium">✓ Completado</span>
                            <?php else: ?>
                                <span class="text-yellow-600 font-medium">En progreso</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.vista-previa-documento {
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
    font-size: 14px;
}

.vista-previa-documento h2 {
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.vista-previa-documento p {
    margin-bottom: 10px;
}

@media print {
    .main-content {
        margin-left: 0 !important;
    }
    
    .vista-previa-documento {
        font-size: 12pt;
        line-height: 1.5;
    }
    
    /* Ocultar elementos no necesarios para impresión */
    .sidebar, .header-gradient, .btn-imprimir, .btn-descargar, .btn-secondary, .btn-enviar {
        display: none !important;
    }
}
</style>

<script>
function enviarDocumento(id) {
    if (!confirm('¿Estás seguro de enviar este documento al estudiante?')) {
        return;
    }
    
    fetch(`index.php?c=Documento&a=enviar&id=${id}&csrf_token=<?php echo SessionHelper::getCSRFToken(); ?>`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar documento');
        });
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>