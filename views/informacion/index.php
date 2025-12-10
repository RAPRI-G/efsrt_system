<?php

// Incluir el header
include 'views/layouts/header.php';

// views/informacion/index.php
$estadisticas = isset($estadisticas) ? $estadisticas : [];
$usuario = SessionHelper::getUser();
?>

<!-- Main Content -->

<div class="p-6">
    <!-- Área de Bienvenida -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-3xl font-bold text-primary-blue mb-2">Centro de Información EFSRT</h1>
                <p class="text-gray-600">Información relevante y recursos del sistema de Experiencias Formativas en Situaciones Reales de Trabajo</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="quick-stat">
                <div id="modulosCount" class="number">
                    <?php echo $estadisticas['modulos_activos'] ?? '3'; ?>
                </div>
                <div class="text-sm text-gray-600">Módulos Disponibles</div>
            </div>
            <div class="quick-stat">
                <div class="number">128</div>
                <div class="text-sm text-gray-600">Horas por Módulo</div>
            </div>
            <div class="quick-stat">
                <div id="empresasCount" class="number">
                    <?php echo $estadisticas['empresas_activas'] ?? '24'; ?>
                </div>
                <div class="text-sm text-gray-600">Empresas Activas</div>
            </div>
            <div class="quick-stat">
                <div class="number">85%</div>
                <div class="text-sm text-gray-600">Tasa de Éxito</div>
            </div>
        </div>
    </div>

    <!-- Tabs de Navegación (solo 3 tabs) -->
    <div class="bg-white rounded-2xl shadow-lg mb-8">
        <div class="tabs">
            <button class="tab active" data-tab="general">Información General</button>
            <button class="tab" data-tab="procesos">Procesos EFSRT</button>
            <button class="tab" data-tab="soporte">Soporte Técnico</button>
        </div>

        <div class="p-6">
            <!-- Tab: Información General -->
            <div id="general" class="tab-content active">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="feature-card">
                        <div class="feature-icon bg-blue-100 text-blue-600">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">¿Qué es EFSRT?</h3>
                        <p class="text-gray-600 mb-4">Las Experiencias Formativas en Situaciones Reales de Trabajo son actividades prácticas que permiten a los estudiantes aplicar sus conocimientos en entornos laborales reales.</p>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>Desarrollo de competencias profesionales</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>Aprendizaje en contexto real de trabajo</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>Integración teoría-práctica</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>Preparación para el mundo laboral</span>
                            </li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon bg-green-100 text-green-600">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Objetivos del Sistema</h3>
                        <p class="text-gray-600 mb-4">Este sistema tiene como objetivo principal gestionar eficientemente todas las experiencias formativas de los estudiantes.</p>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>Gestión centralizada de prácticas</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>Seguimiento en tiempo real</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>Generación automática de reportes</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                <span>Automatización de procesos administrativos</span>
                            </li>
                        </ul>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon bg-purple-100 text-purple-600">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Roles en el Sistema</h3>
                        <p class="text-gray-600 mb-4">El sistema está diseñado para diferentes tipos de usuarios con permisos específicos.</p>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white mr-3">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Administrador</p>
                                    <p class="text-sm text-gray-500">Acceso completo al sistema</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white mr-3">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Docente Supervisor</p>
                                    <p class="text-sm text-gray-500">Gestiona prácticas asignadas</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center text-white mr-3">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Estudiante</p>
                                    <p class="text-sm text-gray-500">Consulta sus prácticas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progresión de Módulos -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <h3 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-road text-blue-500 mr-3"></i>
                        Progresión de Módulos EFSRT
                    </h3>

                    <div class="progress-steps">
                        <div class="step active">
                            1
                            <div class="step-label">Módulo 1</div>
                        </div>
                        <div class="step">
                            2
                            <div class="step-label">Módulo 2</div>
                        </div>
                        <div class="step">
                            3
                            <div class="step-label">Módulo 3</div>
                        </div>
                        <div class="progress-bar" id="progressBar" style="width: 33%;"></div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-bold mb-4">Requisitos por Módulo</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="py-3 px-4 text-left">Módulo</th>
                                        <th class="py-3 px-4 text-left">Horas Requeridas</th>
                                        <th class="py-3 px-4 text-left">Competencias</th>
                                        <th class="py-3 px-4 text-left">Evaluación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                                    1
                                                </div>
                                                <div>
                                                    <span class="font-medium">Módulo 1</span>
                                                    <p class="text-sm text-gray-500">Introducción y adaptación</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-bold text-blue-600">128 horas</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1">Básicas</span>
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Adaptación</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-sm">Informe + Evaluación del supervisor</span>
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold mr-3">
                                                    2
                                                </div>
                                                <div>
                                                    <span class="font-medium">Módulo 2</span>
                                                    <p class="text-sm text-gray-500">Desarrollo y aplicación</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-bold text-green-600">128 horas</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mr-1">Técnicas</span>
                                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aplicación</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-sm">Proyecto + Evaluación integral</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold mr-3">
                                                    3
                                                </div>
                                                <div>
                                                    <span class="font-medium">Módulo 3</span>
                                                    <p class="text-sm text-gray-500">Especialización avanzada</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-bold text-purple-600">128 horas</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded mr-1">Especializadas</span>
                                            <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">Liderazgo</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-sm">Portafolio + Presentación final</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 bg-blue-50 rounded-xl p-5">
                        <h5 class="font-bold mb-3 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Información Importante
                        </h5>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-clock text-blue-500 mt-1 mr-2"></i>
                                <span class="text-sm">Cada módulo requiere <strong>128 horas de práctica</strong> para su aprobación</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <span class="text-sm">Los módulos deben completarse en secuencia (Módulo 1 → Módulo 2 → Módulo 3)</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-2"></i>
                                <span class="text-sm">El sistema verifica automáticamente el cumplimiento de horas a través del registro de asistencias</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tab: Procesos EFSRT -->
            <div id="procesos" class="tab-content">
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-project-diagram text-blue-500 mr-3"></i>
                        Flujo de Procesos del Sistema
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                            </div>
                            <h4 class="font-bold mb-2">Registro</h4>
                            <p class="text-sm text-gray-600">Registro de estudiantes, empresas y docentes en el sistema</p>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-handshake text-green-600 text-2xl"></i>
                            </div>
                            <h4 class="font-bold mb-2">Asignación</h4>
                            <p class="text-sm text-gray-600">Vinculación de estudiantes con empresas y docentes supervisores</p>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tasks text-yellow-600 text-2xl"></i>
                            </div>
                            <h4 class="font-bold mb-2">Ejecución</h4>
                            <p class="text-sm text-gray-600">Seguimiento de prácticas y registro de asistencias</p>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                            </div>
                            <h4 class="font-bold mb-2">Evaluación</h4>
                            <p class="text-sm text-gray-600">Evaluación final y generación de reportes de resultados</p>
                        </div>
                    </div>
                </div>

                <!-- Accordion de Procesos -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-list-alt text-blue-500 mr-3"></i>
                        Procedimientos Detallados
                    </h3>

                    <div class="accordion">
                        <div class="accordion-header">
                            <span class="font-medium">Proceso de Registro de Nueva Práctica</span>
                            <i class="fas fa-chevron-down transition-transform duration-300"></i>
                        </div>
                        <div class="accordion-content">
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Verificar que el estudiante esté matriculado y activo en el sistema</li>
                                <li>Confirmar que la empresa tenga convenio vigente con la institución</li>
                                <li>Asignar docente supervisor disponible para el período</li>
                                <li>Definir fechas de inicio y fin de la práctica (mínimo 128 horas)</li>
                                <li>Seleccionar el módulo correspondiente (1, 2 o 3)</li>
                                <li>Generar documentos necesarios automáticamente (carta de presentación, ficha de identidad)</li>
                                <li>Registrar la práctica en el sistema con estado "Pendiente"</li>
                                <li>Notificar a estudiante, empresa y docente supervisor</li>
                            </ol>
                        </div>
                    </div>

                    <div class="accordion">
                        <div class="accordion-header">
                            <span class="font-medium">Seguimiento y Monitoreo de Prácticas</span>
                            <i class="fas fa-chevron-down transition-transform duration-300"></i>
                        </div>
                        <div class="accordion-content">
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span><strong>Registro diario/semanal de asistencias:</strong> El estudiante registra sus horas trabajadas a través del sistema</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span><strong>Supervisión mensual:</strong> El docente supervisor revisa el progreso y genera reportes</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span><strong>Evaluación parcial:</strong> A la mitad del período se realiza una evaluación intermedia</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span><strong>Reporte del supervisor de empresa:</strong> Evaluación del desempeño en la empresa</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span><strong>Evaluación final:</strong> Por parte del docente supervisor basada en todos los criterios</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="accordion">
                        <div class="accordion-header">
                            <span class="font-medium">Cierre y Evaluación Final de Práctica</span>
                            <i class="fas fa-chevron-down transition-transform duration-300"></i>
                        </div>
                        <div class="accordion-content">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="font-bold mb-3 text-blue-600">Documentos Requeridos para Cierre</h5>
                                    <ul class="space-y-3">
                                        <li class="flex items-start">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium">Informe final del estudiante</p>
                                                <p class="text-sm text-gray-600">Reflexión sobre la experiencia y aprendizajes</p>
                                            </div>
                                        </li>
                                        <li class="flex items-start">
                                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3">
                                                <i class="fas fa-file-signature"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium">Evaluación del supervisor de empresa</p>
                                                <p class="text-sm text-gray-600">Formato oficial de evaluación</p>
                                            </div>
                                        </li>
                                        
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold mb-3 text-green-600">Criterios de Evaluación Final</h5>
                                    <ul class="space-y-3">
                                        <li>
                                            <div class="flex justify-between mb-1">
                                                <span class="font-medium">Cumplimiento de horas (128h)</span>
                                                <span class="font-bold text-blue-600">30%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full" style="width: 100%"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="flex justify-between mb-1">
                                                <span class="font-medium">Desempeño en actividades</span>
                                                <span class="font-bold text-green-600">40%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="flex justify-between mb-1">
                                                <span class="font-medium">Desarrollo de competencias</span>
                                                <span class="font-bold text-purple-600">30%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-purple-500 h-2 rounded-full" style="width: 100%"></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                        <p class="text-sm text-yellow-800">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            <strong>Nota:</strong> El sistema cambia automáticamente el estado a "Finalizado" cuando se alcanzan las 128 horas registradas.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Módulos -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h4 class="font-bold mb-4 flex items-center">
                        <i class="fas fa-cubes text-blue-500 mr-3"></i>
                        Especificaciones por Módulo
                    </h4>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-1"></i>
                            </div>
                            <h4 class="font-bold mb-2">Módulo 1</h4>
                            <p class="text-sm text-gray-600 mb-2">Introducción y Adaptación</p>
                            <p class="text-lg font-bold text-blue-600">128 horas</p>
                            <p class="text-xs text-gray-500 mt-1">Competencias básicas</p>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-2"></i>
                            </div>
                            <h4 class="font-bold mb-2">Módulo 2</h4>
                            <p class="text-sm text-gray-600 mb-2">Desarrollo y Aplicación</p>
                            <p class="text-lg font-bold text-green-600">128 horas</p>
                            <p class="text-xs text-gray-500 mt-1">Competencias técnicas</p>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-3"></i>
                            </div>
                            <h4 class="font-bold mb-2">Módulo 3</h4>
                            <p class="text-sm text-gray-600 mb-2">Especialización Avanzada</p>
                            <p class="text-lg font-bold text-purple-600">128 horas</p>
                            <p class="text-xs text-gray-500 mt-1">Competencias especializadas</p>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4 class="font-bold mb-2">Total EFSRT</h4>
                            <p class="text-sm text-gray-600 mb-2">Horas completas</p>
                            <p class="text-lg font-bold text-red-600">384 horas</p>
                            <p class="text-xs text-gray-500 mt-1">Suma de 3 módulos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Soporte Técnico -->
            <div id="soporte" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="lg:col-span-2">
                        <h3 class="text-xl font-bold mb-6 flex items-center">
                            <i class="fas fa-headset text-blue-500 mr-3"></i>
                            Soporte y Contacto
                        </h3>

                        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                            <h4 class="font-bold mb-4">Preguntas Frecuentes (FAQ)</h4>

                            <div class="faq-item">
                                <div class="faq-question">
                                    <span>¿Cómo registro las 128 horas de práctica?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    <p>Las horas se registran automáticamente a través del sistema de asistencias. El estudiante debe registrar sus horas trabajadas diariamente/semanalmente, y el sistema acumulará hasta completar las 128 horas requeridas por módulo.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question">
                                    <span>¿Qué pasa si no completo las 128 horas?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    <p>El sistema no marcará el módulo como "Finalizado" hasta que se registren las 128 horas completas. El estudiante debe coordinarlo con su supervisor para extender el período de práctica hasta cumplir con el requisito.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <div class="faq-question">
                                    <span>¿Puedo cambiar de módulo antes de completar las 128 horas?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    <p>No, los módulos deben completarse secuencialmente. Para pasar al siguiente módulo, primero debe completar las 128 horas del módulo actual y obtener la evaluación aprobatoria.</p>
                                </div>
                            </div>

                            
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold mb-6 flex items-center">
                            <i class="fas fa-address-book text-blue-500 mr-3"></i>
                            Contacto Directo
                        </h3>

                        <div class="space-y-6">
                            <div class="contact-card">
                                <div class="contact-avatar">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <h4 class="font-bold">Departamento de EFSRT</h4>
                                <p class="text-sm text-gray-600 mb-3">Responsable del sistema y gestión de prácticas</p>
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                        <span>efsrt@esfrh.edu.pe</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-phone text-green-500 mr-2"></i>
                                        <span>(01) 123-4567</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                        <span>Lun-Vie: 8am - 6pm</span>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-card">
                                <div class="contact-avatar">
                                    <i class="fas fa-laptop-code"></i>
                                </div>
                                <h4 class="font-bold">Soporte Técnico</h4>
                                <p class="text-sm text-gray-600 mb-3">Asistencia técnica especializada del sistema</p>
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                        <span>soporte@esfrh.edu.pe</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-phone text-green-500 mr-2"></i>
                                        <span>(01) 765-4321</span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                        <span>24/7 para emergencias</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 rounded-xl p-5">
                                <h4 class="font-bold mb-3 flex items-center">
                                    <i class="fas fa-bullhorn text-blue-600 mr-2"></i>
                                    Estado del Sistema
                                </h4>
                                <div class="flex items-center mb-2">
                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                    <span class="font-medium">Todos los sistemas operativos</span>
                                </div>
                                <p class="text-sm text-gray-600">Última actualización: <?php echo date('d/m/Y H:i'); ?></p>
                                <div class="mt-4">
                                    <h5 class="font-bold text-sm mb-2">Próximos Mantenimientos</h5>
                                    <p class="text-sm text-gray-600">Sábado, 15 de Marzo<br>2:00 AM - 4:00 AM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sistema de Tabs
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                tab.classList.add('active');
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Sistema de Accordion
        const accordionHeaders = document.querySelectorAll('.accordion-header');
        accordionHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                const icon = header.querySelector('i');

                document.querySelectorAll('.accordion-content').forEach(accContent => {
                    if (accContent !== content && accContent.classList.contains('active')) {
                        accContent.classList.remove('active');
                        accContent.previousElementSibling.classList.remove('active');
                        accContent.previousElementSibling.querySelector('i').style.transform = 'rotate(0deg)';
                    }
                });

                header.classList.toggle('active');
                content.classList.toggle('active');

                if (content.classList.contains('active')) {
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Sistema de FAQ
        const faqQuestions = document.querySelectorAll('.faq-question');
        faqQuestions.forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const icon = question.querySelector('i');

                document.querySelectorAll('.faq-answer').forEach(faqAnswer => {
                    if (faqAnswer !== answer && faqAnswer.classList.contains('active')) {
                        faqAnswer.classList.remove('active');
                        faqAnswer.previousElementSibling.querySelector('i').style.transform = 'rotate(0deg)';
                    }
                });

                answer.classList.toggle('active');

                if (answer.classList.contains('active')) {
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Formulario de soporte
        const formSoporte = document.getElementById('formSoporte');
        if (formSoporte) {
            formSoporte.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());

                // Aquí puedes enviar los datos al servidor
                console.log('Datos del formulario:', data);

                // Simular envío exitoso
                alert('Solicitud enviada correctamente. Nos pondremos en contacto contigo pronto.');
                formSoporte.reset();
            });
        }

        // Cargar datos dinámicos via AJAX
        function cargarDatosDinamicos() {
            fetch('index.php?c=Informacion&a=getEstadisticasAjax')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        actualizarContador('empresasCount', data.empresas_count);
                        // Puedes actualizar más contadores si los agregas
                    }
                })
                .catch(error => console.error('Error cargando datos:', error));
        }

        function actualizarContador(elementId, valor) {
            const elemento = document.getElementById(elementId);
            if (elemento) {
                elemento.textContent = valor;
            }
        }

        // Inicializar
        cargarDatosDinamicos();

        // Simulación de progresión en pasos
        const steps = document.querySelectorAll('.step');
        const progressBar = document.querySelector('.progress-bar');
        let currentStep = 0;

        function updateProgress() {
            steps.forEach((step, index) => {
                if (index <= currentStep) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });

            const progressPercentage = (currentStep / (steps.length - 1)) * 100;
            progressBar.style.width = `${progressPercentage}%`;
        }

        
        updateProgress();

        // Opcional: Simulación de cambio de pasos
         setInterval(() => {
             currentStep = (currentStep + 1) % steps.length;
             updateProgress();
        }, 2000);
    });
</script>

<?php
// Incluir el footer
include 'views/layouts/footer.php';
?>

<style>
    /* Estilos específicos para la página de información */
    .info-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    /* Progress Steps */
    .progress-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 40px 0;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 4px;
        background: #e0e0e0;
        transform: translateY(-50%);
        z-index: 1;
    }

    .progress-bar {
        position: absolute;
        top: 50%;
        left: 0;
        height: 4px;
        background: #0dcaf0;
        transform: translateY(-50%);
        z-index: 2;
        transition: width 0.5s ease;
    }

    .step {
        width: 40px;
        height: 40px;
        background: white;
        border: 3px solid #e0e0e0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #666;
        position: relative;
        z-index: 3;
        transition: all 0.3s ease;
    }

    .step.active {
        border-color: #0dcaf0;
        background: #0dcaf0;
        color: white;
        transform: scale(1.1);
    }

    .step-label {
        position: absolute;
        top: 50px;
        text-align: center;
        font-size: 0.85rem;
        color: #666;
        width: 100px;
        left: 50%;
        transform: translateX(-50%);
    }

    /* Accordion */
    .accordion {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .accordion-header {
        padding: 20px;
        background: #f8fafc;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.3s ease;
    }

    .accordion-header:hover {
        background: #f1f5f9;
    }

    .accordion-header.active {
        background: #0C1F36;
        color: white;
    }

    .accordion-content {
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .accordion-content.active {
        padding: 20px;
        max-height: 1000px;
    }

    /* Feature Cards */
    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 32px;
    }

    /* Tabs */
    .tabs {
        display: flex;
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .tab {
        padding: 15px 25px;
        cursor: pointer;
        font-weight: 600;
        color: #6b7280;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .tab.active {
        color: #0C1F36;
        border-bottom-color: #0dcaf0;
        background: linear-gradient(to top, rgba(13, 202, 240, 0.1), transparent);
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .tab-content.active {
        display: block;
    }

    /* Contact Cards */
    .contact-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .contact-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0C1F36, #1a365d);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 32px;
        font-weight: bold;
    }

    /* FAQ */
    .faq-item {
        background: white;
        border-radius: 12px;
        margin-bottom: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .faq-question {
        padding: 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        transition: background 0.3s ease;
    }

    .faq-question:hover {
        background: #f8fafc;
    }

    .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-answer.active {
        padding: 0 20px 20px;
        max-height: 500px;
    }

    /* Quick Stats */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin: 20px 0;
    }

    .quick-stat {
        background: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .quick-stat .number {
        font-size: 24px;
        font-weight: bold;
        color: #0C1F36;
        margin-bottom: 5px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .stat-item {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #0C1F36, #1a365d);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 24px;
    }

    /* Animaciones */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }
</style>