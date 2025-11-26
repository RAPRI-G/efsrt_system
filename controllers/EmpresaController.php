<?php
require_once 'models/EmpresaModel.php';
require_once 'models/PracticaModel.php';
require_once 'models/UbigeoModel.php';

class EmpresaController
{
    private $empresaModel;
    private $practicaModel;
    private $ubigeoModel;

    public function __construct()
    {
        $this->empresaModel = new EmpresaModel();
        $this->practicaModel = new PracticaModel();
        $this->ubigeoModel = new UbigeoModel();
    }

    public function index()
    {
        // Cargar departamentos para el filtro
        $departamentos = $this->ubigeoModel->obtenerDepartamentos();
        require_once 'views/empresa/empresa.php';
    }

    // ✅ NUEVO: API para obtener departamentos
    public function api_departamentos()
    {
        header('Content-Type: application/json');
        try {
            $departamentos = $this->ubigeoModel->obtenerDepartamentos();
            echo json_encode([
                'success' => true,
                'data' => $departamentos
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function api_provincias()
    {
        header('Content-Type: application/json');
        try {
            $departamentoId = $_GET['departamento_id'] ?? null;
            if (!$departamentoId) {
                throw new Exception('ID de departamento no proporcionado');
            }

            $provincias = $this->ubigeoModel->obtenerProvinciasPorDepartamento($departamentoId);
            echo json_encode([
                'success' => true,
                'data' => $provincias
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function api_distritos()
    {
        header('Content-Type: application/json');
        try {
            $provinciaId = $_GET['provincia_id'] ?? null;
            if (!$provinciaId) {
                throw new Exception('ID de provincia no proporcionado');
            }

            $distritos = $this->ubigeoModel->obtenerDistritosPorProvincia($provinciaId);
            echo json_encode([
                'success' => true,
                'data' => $distritos
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // API MEJORADA: Obtener empresas con paginación
    public function api_empresas()
    {
        header('Content-Type: application/json');

        try {
            $filtros = [
                'busqueda' => $_GET['busqueda'] ?? '',
                'departamento' => $_GET['departamento'] ?? 'all',
                'estado' => $_GET['estado'] ?? 'all'
            ];

            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $elementosPorPagina = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = ($pagina - 1) * $elementosPorPagina;

            $empresas = $this->empresaModel->obtenerEmpresasConFiltros($filtros, $elementosPorPagina, $offset);
            $total = $this->empresaModel->contarEmpresasConFiltros($filtros);

            echo json_encode([
                'success' => true,
                'data' => $empresas,
                'total' => $total,
                'pagina' => $pagina,
                'totalPaginas' => ceil($total / $elementosPorPagina)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // API MEJORADA: Obtener una empresa específica
    public function api_empresa()
    {
        header('Content-Type: application/json');

        try {
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception('ID de empresa no proporcionado');
            }

            $empresa = $this->empresaModel->obtenerEmpresaPorId($id);

            if ($empresa) {
                echo json_encode([
                    'success' => true,
                    'data' => $empresa
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Empresa no encontrada'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // API MEJORADA: Crear/editar empresa con validación
    public function api_guardar()
    {
        header('Content-Type: application/json');

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                throw new Exception('Datos no válidos');
            }

            $id = $input['id'] ?? null;

            // Validaciones básicas
            if (empty($input['ruc']) || empty($input['razon_social']) || empty($input['email'])) {
                throw new Exception('Los campos RUC, Razón Social y Email son obligatorios');
            }

            // ✅ OBTENER NOMBRES DE UBICACIÓN DESDE LOS IDs
            $departamentoNombre = '';
            $provinciaNombre = '';
            $distritoNombre = '';

            if (!empty($input['departamento_id'])) {
                $ubicacion = $this->ubigeoModel->obtenerUbicacionCompleta(
                    $input['departamento_id'],
                    $input['provincia_id'],
                    $input['distrito_id']
                );

                if ($ubicacion) {
                    $departamentoNombre = $ubicacion['departamento'];
                    $provinciaNombre = $ubicacion['provincia'];
                    $distritoNombre = $ubicacion['distrito'];
                }
            }

            // ✅ CORREGIDO: Estructura correcta para el modelo
            $datos = [
                ':ruc' => $this->sanitize($input['ruc']),
                ':razon_social' => $this->sanitize($input['razon_social']),
                ':representante_legal' => $this->sanitize($input['representante_legal'] ?? ''),
                ':direccion_fiscal' => $this->sanitize($input['direccion_fiscal'] ?? ''),
                ':telefono' => $this->sanitize($input['telefono'] ?? ''),
                ':email' => $this->sanitize($input['email']),
                ':departamento' => $departamentoNombre,
                ':provincia' => $provinciaNombre,
                ':distrito' => $distritoNombre,
                ':estado' => $this->sanitize($input['estado'] ?? 'ACTIVO')
            ];

            if ($id) {
                // Actualizar empresa existente
                $result = $this->empresaModel->actualizarEmpresa($id, $datos);
                $mensaje = 'Empresa actualizada correctamente';
            } else {
                // Crear nueva empresa
                $result = $this->empresaModel->crearEmpresa($datos);
                $mensaje = 'Empresa creada correctamente';
            }

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => $mensaje
                ]);
            } else {
                throw new Exception('No se pudo guardar la empresa');
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // API MEJORADA: Eliminar empresa
  public function api_eliminar() {
    header('Content-Type: application/json');
    
    try {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            throw new Exception('ID de empresa no proporcionado');
        }
        
        $result = $this->empresaModel->eliminarEmpresa($id);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Empresa eliminada permanentemente del sistema'
            ]);
        } else {
            throw new Exception('No se pudo eliminar la empresa');
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

    // API MEJORADA: Estadísticas - CORREGIDO
    public function api_estadisticas()
    {
        header('Content-Type: application/json');

        try {
            // ✅ OBTENER ESTADÍSTICAS COMPLETAS
            $total_empresas = $this->empresaModel->contarTotalEmpresas();
            $empresas_activas = $this->empresaModel->contarEmpresasActivas();
            $distribucion_sectores = $this->empresaModel->contarEmpresasPorSector();
            $empresas_con_practicas = $this->empresaModel->contarEmpresasConPracticas();
            $distribucion_estados = $this->empresaModel->contarEmpresasPorEstado();

            // ✅ CALCULAR INACTIVAS CORRECTAMENTE
            $empresas_inactivas = $total_empresas - $empresas_activas;

            // ✅ DEBUG: Log para verificar datos
            error_log("📊 ESTADÍSTICAS CALCULADAS:");
            error_log("Total empresas: " . $total_empresas);
            error_log("Empresas activas: " . $empresas_activas);
            error_log("Empresas inactivas: " . $empresas_inactivas);
            error_log("Con prácticas: " . $empresas_con_practicas);

            echo json_encode([
                'success' => true,
                'data' => [
                    'total_empresas' => $total_empresas,
                    'empresas_activas' => $empresas_activas,
                    'empresas_inactivas' => $empresas_inactivas,
                    'distribucion_sectores' => $distribucion_sectores,
                    'empresas_con_practicas' => $empresas_con_practicas,
                    'distribucion_estados' => $distribucion_estados
                ]
            ]);
        } catch (Exception $e) {
            error_log("❌ ERROR EN API_ESTADÍSTICAS: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // 🧪 MÉTODO TEMPORAL PARA PROBAR DATOS (eliminar después)
public function debugEstadisticas() {
    header('Content-Type: application/json');
    
    $total_empresas = $this->empresaModel->contarTotalEmpresas();
    $empresas_activas = $this->empresaModel->contarEmpresasActivas();
    $distribucion_estados = $this->empresaModel->contarEmpresasPorEstado();
    
    echo json_encode([
        'debug' => true,
        'total_empresas' => $total_empresas,
        'empresas_activas' => $empresas_activas,
        'empresas_inactivas_calculadas' => $total_empresas - $empresas_activas,
        'distribucion_estados' => $distribucion_estados,
        'sql_activas' => "SELECT COUNT(*) as total FROM empresa WHERE estado = 'ACTIVO'",
        'sql_total' => "SELECT COUNT(*) as total FROM empresa",
        'sql_estados' => "SELECT estado, COUNT(*) as cantidad FROM empresa GROUP BY estado"
    ]);
}

    // En controllers/EmpresaController.php - agrega este método:
    public function exportar()
    {
        try {
            $filtros = [
                'busqueda' => $_GET['busqueda'] ?? '',
                'departamento' => $_GET['departamento'] ?? 'all',
                'estado' => $_GET['estado'] ?? 'all'
            ];

            $empresas = $this->empresaModel->obtenerEmpresasConFiltros($filtros, null, 0);
            $this->generarExcelEmpresas($empresas, $filtros);
        } catch (Exception $e) {
            header('Location: index.php?c=Empresa&a=index&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    // 🔧 MÉTODOS PRIVADOS PARA GENERAR EXCEL
    private function generarExcelEmpresas($empresas, $filtros)
    {
        $fecha = date('Y-m-d_H-i-s');
        $filename = "reporte_empresas_{$fecha}.xls";

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");

        echo "\xEF\xBB\xBF"; // BOM UTF-8

        // 🔥 CABECERA DEL REPORTE
        echo "REPORTE DE EMPRESAS - SISTEMA EFSRT\n";
        echo "Fecha de exportación: " . date('d/m/Y H:i:s') . "\n";

        // 🔥 INFORMACIÓN DE FILTROS APLICADOS
        if (!empty($filtros['busqueda']) || $filtros['departamento'] !== 'all' || $filtros['estado'] !== 'all') {
            echo "Filtros aplicados:\n";
            if (!empty($filtros['busqueda'])) {
                echo "Búsqueda: " . $filtros['busqueda'] . "\n";
            }
            if ($filtros['departamento'] !== 'all') {
                echo "Departamento: " . $filtros['departamento'] . "\n";
            }
            if ($filtros['estado'] !== 'all') {
                echo "Estado: " . $filtros['estado'] . "\n";
            }
        }

        echo "Total de empresas: " . count($empresas) . "\n\n";

        // 🔥 ESTADÍSTICAS RÁPIDAS
        $activas = array_filter($empresas, function ($empresa) {
            return $empresa['estado'] === 'ACTIVO';
        });

        $inactivas = array_filter($empresas, function ($empresa) {
            return $empresa['estado'] === 'INACTIVO';
        });

        echo "RESUMEN ESTADÍSTICO:\n";
        echo "Empresas activas: " . count($activas) . " (" . round((count($activas) / count($empresas)) * 100, 2) . "%)\n";
        echo "Empresas inactivas: " . count($inactivas) . " (" . round((count($inactivas) / count($empresas)) * 100, 2) . "%)\n\n";

        // 🔥 DISTRIBUCIÓN POR DEPARTAMENTO EN EXCEL
        $departamentos = [];
        foreach ($empresas as $empresa) {
            $depto = $empresa['departamento'] ?: 'No especificado';
            $departamentos[$depto] = ($departamentos[$depto] ?? 0) + 1;
        }

        if (!empty($departamentos)) {
            echo "DISTRIBUCIÓN POR DEPARTAMENTO:\n";
            echo "Departamento\tCantidad\tPorcentaje\n";
            foreach ($departamentos as $depto => $cantidad) {
                $porcentaje = round(($cantidad / count($empresas)) * 100, 2);
                echo "{$depto}\t{$cantidad}\t{$porcentaje}%\n";
            }
            echo "\n";
        }

        // 🔥 DISTRIBUCIÓN POR ESTADO EN EXCEL
        $estados = [];
        foreach ($empresas as $empresa) {
            $estado = $empresa['estado'] ?: 'No especificado';
            $estados[$estado] = ($estados[$estado] ?? 0) + 1;
        }

        if (!empty($estados)) {
            echo "DISTRIBUCIÓN POR ESTADO:\n";
            echo "Estado\tCantidad\tPorcentaje\n";
            foreach ($estados as $estado => $cantidad) {
                $porcentaje = round(($cantidad / count($empresas)) * 100, 2);
                echo "{$estado}\t{$cantidad}\t{$porcentaje}%\n";
            }
            echo "\n";
        }

        // 🔥 LISTA DETALLADA DE EMPRESAS
        echo "LISTA DETALLADA DE EMPRESAS\n";
        echo "================================================================================\n";

        // CABECERA DE LA TABLA
        echo "ID\tRUC\tRAZÓN SOCIAL\tREPRESENTANTE LEGAL\tTELÉFONO\tEMAIL\tUBICACIÓN\tESTADO\n";

        // DATOS DE LAS EMPRESAS
        foreach ($empresas as $empresa) {
            $ubicacion = trim($empresa['departamento'] . ', ' . $empresa['provincia'] . ', ' . $empresa['distrito'], ', ');
            if (empty(trim($ubicacion, ', '))) {
                $ubicacion = 'No especificada';
            }

            echo ($empresa['id'] ?? '') . "\t";
            echo "'" . ($empresa['ruc'] ?? '') . "\t";
            echo ($empresa['razon_social'] ?? '') . "\t";
            echo ($empresa['representante_legal'] ?? '') . "\t";
            echo "'" . ($empresa['telefono'] ?? '') . "\t";
            echo ($empresa['email'] ?? '') . "\t";
            echo $ubicacion . "\t";
            echo ($empresa['estado'] ?? '') . "\n";
        }

        // 🔥 PIE DEL REPORTE
        echo "\n================================================================================\n";
        echo "Este reporte fue generado automáticamente por el Sistema EFSRT\n";
        echo "© " . date('Y') . " - Todos los derechos reservados\n";

        exit;
    }

    public function exportarEstadisticas()
    {
        try {
            $estadisticas = $this->obtenerEstadisticasCompletas();
            $this->generarExcelEstadisticas($estadisticas);
        } catch (Exception $e) {
            header('Location: index.php?c=Empresa&a=index&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    private function generarExcelEstadisticas($estadisticas)
    {
        $fecha = date('Y-m-d_H-i-s');
        $filename = "reporte_estadisticas_empresas_{$fecha}.xls";

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");

        echo "\xEF\xBB\xBF"; // BOM UTF-8

        // 🔥 CABECERA PRINCIPAL
        echo "REPORTE ESTADÍSTICO COMPLETO - EMPRESAS EFSRT\n";
        echo "Fecha de generación: " . date('d/m/Y H:i:s') . "\n\n";

        // 🔥 ESTADÍSTICAS GENERALES
        echo "ESTADÍSTICAS GENERALES\n";
        echo "=====================\n";

        $estadisticasPrincipales = [
            ['TOTAL DE EMPRESAS REGISTRADAS', $estadisticas['total_empresas'] ?? 0],
            ['EMPRESAS ACTIVAS', $estadisticas['empresas_activas'] ?? 0],
            ['EMPRESAS INACTIVAS', $estadisticas['empresas_inactivas'] ?? 0],
            ['EMPRESAS CON PRÁCTICAS ACTIVAS', $estadisticas['empresas_con_practicas'] ?? 0],
            ['PORCENTAJE DE EMPRESAS ACTIVAS', round((($estadisticas['empresas_activas'] ?? 0) / ($estadisticas['total_empresas'] ?? 1)) * 100, 2) . '%']
        ];

        foreach ($estadisticasPrincipales as $fila) {
            echo $fila[0] . "\t" . $fila[1] . "\n";
        }

        echo "\n";

        // 🔥 DISTRIBUCIÓN POR DEPARTAMENTO (GRÁFICO EN DATOS)
        echo "DISTRIBUCIÓN GEOGRÁFICA - POR DEPARTAMENTO\n";
        echo "==========================================\n";
        echo "DEPARTAMENTO\tCANTIDAD\tPORCENTAJE\tBARRA\n";

        $totalEmpresas = $estadisticas['total_empresas'] ?? 1;
        foreach ($estadisticas['distribucion_sectores'] as $distribucion) {
            $cantidad = $distribucion['cantidad'] ?? 0;
            $porcentaje = round(($cantidad / $totalEmpresas) * 100, 2);
            $barra = str_repeat('█', max(1, round($porcentaje / 5))); // Barra visual

            echo ($distribucion['sector'] ?? 'No especificado') . "\t";
            echo $cantidad . "\t";
            echo $porcentaje . "%\t";
            echo $barra . "\n";
        }

        echo "\n";

        // 🔥 DISTRIBUCIÓN POR ESTADO (GRÁFICO EN DATOS)
        echo "DISTRIBUCIÓN POR ESTADO\n";
        echo "=======================\n";
        echo "ESTADO\tCANTIDAD\tPORCENTAJE\tBARRA\n";

        foreach ($estadisticas['distribucion_estados'] as $estado) {
            $cantidad = $estado['cantidad'] ?? 0;
            $porcentaje = round(($cantidad / $totalEmpresas) * 100, 2);
            $barra = str_repeat('█', max(1, round($porcentaje / 5))); // Barra visual

            echo ($estado['estado'] ?? 'No especificado') . "\t";
            echo $cantidad . "\t";
            echo $porcentaje . "%\t";
            echo $barra . "\n";
        }

        echo "\n";

        // 🔥 RESUMEN EJECUTIVO
        echo "RESUMEN EJECUTIVO\n";
        echo "=================\n";

        $empresaMasComun = '';
        $maxCantidad = 0;
        foreach ($estadisticas['distribucion_sectores'] as $distribucion) {
            if (($distribucion['cantidad'] ?? 0) > $maxCantidad) {
                $maxCantidad = $distribucion['cantidad'];
                $empresaMasComun = $distribucion['sector'];
            }
        }

        $resumen = [
            ['Departamento con más empresas', $empresaMasComun . ' (' . $maxCantidad . ' empresas)'],
            ['Tasa de actividad', round((($estadisticas['empresas_activas'] ?? 0) / $totalEmpresas) * 100, 2) . '%'],
            ['Empresas disponibles para prácticas', $estadisticas['empresas_activas'] ?? 0 - ($estadisticas['empresas_con_practicas'] ?? 0)],
            ['Diversidad geográfica', count($estadisticas['distribucion_sectores']) . ' departamentos distintos']
        ];

        foreach ($resumen as $fila) {
            echo $fila[0] . "\t" . $fila[1] . "\n";
        }

        echo "\n";
        echo "================================================================================\n";
        echo "Reporte generado automáticamente - Sistema EFSRT\n";
        echo "© " . date('Y') . " - Información confidencial\n";

        exit;
    }

    private function obtenerEstadisticasCompletas()
    {
        $total_empresas = $this->empresaModel->contarTotalEmpresas();
        $empresas_activas = $this->empresaModel->contarEmpresasActivas();
        $distribucion_sectores = $this->empresaModel->contarEmpresasPorSector();
        $empresas_con_practicas = $this->empresaModel->contarEmpresasConPracticas();
        $distribucion_estados = $this->empresaModel->contarEmpresasPorEstado();

        return [
            'total_empresas' => $total_empresas,
            'empresas_activas' => $empresas_activas,
            'empresas_inactivas' => $total_empresas - $empresas_activas,
            'distribucion_sectores' => $distribucion_sectores,
            'empresas_con_practicas' => $empresas_con_practicas,
            'distribucion_estados' => $distribucion_estados
        ];
    }

    private function generarExcel($empresas, $filtros)
    {
        $fecha = date('Y-m-d_H-i-s');
        $filename = "empresas_exportadas_{$fecha}.xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");

        echo "\xEF\xBB\xBF"; // BOM UTF-8

        // CABECERA
        echo "ID\tRUC\tRAZÓN SOCIAL\tREPRESENTANTE LEGAL\tDIRECCIÓN FISCAL\tTELÉFONO\tEMAIL\tDEPARTAMENTO\tPROVINCIA\tDISTRITO\tESTADO\tFECHA REGISTRO\n";

        // DATOS
        foreach ($empresas as $empresa) {
            echo ($empresa['id'] ?? '') . "\t";
            echo "'" . ($empresa['ruc'] ?? '') . "\t"; // RUC
            echo ($empresa['razon_social'] ?? '') . "\t";
            echo ($empresa['representante_legal'] ?? '') . "\t";
            echo ($empresa['direccion_fiscal'] ?? '') . "\t";
            echo "'" . ($empresa['telefono'] ?? '') . "\t"; // Teléfono
            echo ($empresa['email'] ?? '') . "\t";
            echo ($empresa['departamento'] ?? '') . "\t";
            echo ($empresa['provincia'] ?? '') . "\t";
            echo ($empresa['distrito'] ?? '') . "\t";
            echo ($empresa['estado'] ?? '') . "\t";
            echo (isset($empresa['fecha_creacion']) ? date('d/m/Y', strtotime($empresa['fecha_creacion'])) : '') . "\n";
        }

        exit;
    }

    private function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}
