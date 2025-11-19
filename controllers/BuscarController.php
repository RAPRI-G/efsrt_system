<?php
require_once 'models/EstudianteModel.php';

class BuscarController {
    
    public function estudiantes() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $termino = $_POST['termino'] ?? '';
            
            if (empty($termino) || strlen($termino) < 2) {
                echo json_encode([]);
                return;
            }
            
            $estudianteModel = new EstudianteModel();
            $estudiantes = $estudianteModel->buscarEstudiantes($termino);
            
            $resultados = [];
            foreach ($estudiantes as $est) {
                $resultados[] = [
                    'id' => $est['id'],
                    'texto' => $est['ap_est'] . ' ' . $est['am_est'] . ' ' . $est['nom_est'],
                    'dni' => $est['dni_est'],
                    'celular' => $est['cel_est'] ?? '',
                    'email' => $est['mailp_est'] ?? '',
                    'url' => 'index.php?c=Estudiante&a=ver&id=' . $est['id']
                ];
            }
            
            header('Content-Type: application/json');
            echo json_encode($resultados);
            return;
        }
    }
}
?>