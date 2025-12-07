<?php
class ReportesController
{
    private $model;
    private $estudianteModel;
    private $practicaModel;
    private $empresaModel;

    public function __construct()
    {
        require_once 'models/ReportesModel.php';
        require_once 'models/EstudianteModel.php';
        require_once 'models/PracticaModel.php';
        require_once 'models/EmpresaModel.php';
        
        $this->model = new ReportesModel();
        $this->estudianteModel = new EstudianteModel();
        $this->practicaModel = new PracticaModel();
        $this->empresaModel = new EmpresaModel();
    }

    public function index()
    {
        if (!SessionHelper::isLoggedIn()) {
            header("Location: index.php?c=Login&a=index");
            exit;
        }

        $usuario = SessionHelper::get('usuario');
        
        // Obtener estadísticas generales
        $estadisticas = $this->model->obtenerEstadisticasDashboard();
        
        // Obtener datos para gráficos
        $datosEstadoPracticas = $this->model->obtenerDatosGraficoEstadoPracticas();
        $datosModulos = $this->model->obtenerDatosGraficoModulos();
        $topEmpresas = $this->model->obtenerTopEmpresas(5);
        $evolucionMensual = $this->model->obtenerEvolucionMensual();
        
        // Preparar datos para JavaScript
        $datosJson = json_encode([
            'estadisticas' => $estadisticas,
            'datosEstado' => $datosEstadoPracticas,
            'datosModulos' => $datosModulos,
            'topEmpresas' => $topEmpresas,
            'evolucionMensual' => $evolucionMensual
        ]);

        // Cargar vista
        require_once 'views/layouts/header.php';
        require_once 'views/reportes/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function datosDashboard()
    {
        header('Content-Type: application/json');
        
        $estadisticas = $this->model->obtenerEstadisticasDashboard();
        $datosEstado = $this->model->obtenerDatosGraficoEstadoPracticas();
        $datosModulos = $this->model->obtenerDatosGraficoModulos();
        $topEmpresas = $this->model->obtenerTopEmpresas(5);
        $evolucionMensual = $this->model->obtenerEvolucionMensual();
        
        echo json_encode([
            'estadisticas' => $estadisticas,
            'datosEstado' => $datosEstado,
            'datosModulos' => $datosModulos,
            'topEmpresas' => $topEmpresas,
            'evolucionMensual' => $evolucionMensual,
            'fecha_actual' => date('d/m/Y H:i')
        ]);
    }

    public function actualizar()
    {
        header('Content-Type: application/json');
        
        // Obtener datos actualizados
        $estadisticas = $this->model->obtenerEstadisticasDashboard();
        
        echo json_encode([
            'estadisticas' => $estadisticas,
            'fecha_actual' => date('d/m/Y H:i')
        ]);
    }
}
?>