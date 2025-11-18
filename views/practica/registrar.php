<?php 
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Registrar Nueva Práctica EFSRT</h3>
            </div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?c=Practica&a=registrar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estudiante *</label>
                                <select name="estudiante_id" class="form-select" required>
                                    <option value="">Seleccionar estudiante</option>
                                    <?php foreach($estudiantes as $est): ?>
                                        <option value="<?php echo $est['id']; ?>">
                                            <?php echo $est['ap_est'] . ' ' . $est['am_est'] . ' ' . $est['nom_est'] . ' - ' . $est['dni_est']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Empresa *</label>
                                <select name="empresa_id" class="form-select" required>
                                    <option value="">Seleccionar empresa</option>
                                    <?php foreach($empresas as $emp): ?>
                                        <option value="<?php echo $emp['id']; ?>">
                                            <?php echo $emp['razon_social']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Módulo EFSRT *</label>
                                <select name="tipo_efsrt" class="form-select" required>
                                    <option value="">Seleccionar módulo</option>
                                    <option value="modulo1">Módulo 1 - Primer Año</option>
                                    <option value="modulo2">Módulo 2 - Segundo Año</option>
                                    <option value="modulo3">Módulo 3 - Tercer Año</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Docente Supervisor</label>
                                <select name="docente_supervisor" class="form-select">
                                    <option value="">Seleccionar docente</option>
                                    <?php foreach($docentes as $doc): ?>
                                        <option value="<?php echo $doc['id']; ?>">
                                            <?php echo $doc['apnom_emp']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Área de Ejecución</label>
                                <input type="text" name="area_ejecucion" class="form-control" 
                                       placeholder="Ej: Oficina de TI">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Periodo Académico *</label>
                                <input type="text" name="periodo_academico" class="form-control" 
                                       placeholder="Ej: 2025-I" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Turno *</label>
                                <select name="turno" class="form-select" required>
                                    <option value="">Seleccionar turno</option>
                                    <option value="MAÑANA">Mañana</option>
                                    <option value="TARDE">Tarde</option>
                                    <option value="NOCHE">Noche</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio *</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin *</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?c=Practica&a=index" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Registrar Práctica</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>