<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h3>Generar Documentos - Práctica EFSRT</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h5>Datos de la Práctica</h5>
            <p><strong>Estudiante:</strong> <?php echo $practica['ap_est'] . ' ' . $practica['am_est'] . ' ' . $practica['nom_est']; ?></p>
            <p><strong>Programa:</strong> <?php echo $practica['nom_progest']; ?></p>
            <p><strong>Empresa:</strong> <?php echo $practica['razon_social']; ?></p>
            <p><strong>Módulo:</strong> <?php echo strtoupper($practica['tipo_efsrt']); ?></p>
        </div>

        <div class="d-grid gap-2 d-md-flex">
            <a href="#" class="btn btn-primary me-2">Generar Oficio Múltiple</a>
            <a href="#" class="btn btn-success me-2">Generar Carta de Presentación</a>
            <a href="#" class="btn btn-info me-2">Generar Ficha de Identidad</a>
            <a href="index.php?c=Practica&a=index" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>