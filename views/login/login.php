<?php
// DEBUG: Verificar que el token llegue a la vista
if (isset($csrf_token)) {
    error_log("🔐 VISTA: Token disponible - " . $csrf_token);
} else {
    error_log("❌ VISTA: Token NO disponible");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Experiencias Formativas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <!-- Background curves -->
    <div class="curve-top"></div>
    <div class="curve-bottom"></div>

    <!-- Floating circles -->
    <div class="floating-circle circle-1"></div>
    <div class="floating-circle circle-2"></div>
    <div class="floating-circle circle-3"></div>

    <div class="login-wrapper">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-decoration"></div>
            <div class="welcome-decoration-2"></div>
            <div class="welcome-content">
                <div class="welcome-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1 class="welcome-title">Bienvenido</h1>
                <p class="welcome-subtitle">Sistema de Experiencias Formativas en Situaciones Reales de Trabajo</p>

                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Acceso seguro y protegido</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <span>Gestión de prácticas EFSRT</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span>Documentos automatizados</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Section -->
        <div class="login-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-user-lock section-icon"></i>
                    INICIAR SESIÓN
                </h2>
                <p class="section-subtitle">Ingresa tus credenciales para acceder al sistema</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- ... código anterior ... -->

            <form method="POST" action="index.php?c=Login&a=auth">
                <!-- TOKEN CSRF -->
                <input type="hidden" name="csrf_token" value="<?php echo SessionHelper::getCSRFToken(); ?>">
                <input type="hidden" name="rol" id="rolInput" value="">

                <div class="form-group">
                    <label class="form-label" for="usuarioInput">
                        Usuario
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-left-icon"></i>
                        <input type="text" id="usuarioInput" name="usuario" class="form-input"
                            placeholder="Ingresa tu usuario" required
                            value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>"
                            autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="passwordInput">
                        Contraseña
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-left-icon"></i>
                        <input type="password" id="passwordInput" name="password" class="form-input"
                            placeholder="Ingresa tu contraseña" required
                            autocomplete="current-password">
                        <div class="input-icon" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>

                <!-- Selector de Roles -->
                <div class="roles-group">
                    <label class="roles-label">Tipo de usuario <span style="color: red;">*</span></label>
                    <div class="roles-container">
                        <div class="role-option" data-role="administrador" id="roleAdmin">
                            <i class="fas fa-crown role-icon"></i>
                            <span class="role-name">Administrador</span>
                        </div>
                        <div class="role-option" data-role="docente" id="roleDocente">
                            <i class="fas fa-chalkboard-teacher role-icon"></i>
                            <span class="role-name">Docente</span>
                        </div>
                        <div class="role-option" data-role="estudiante" id="roleEstudiante">
                            <i class="fas fa-user-graduate role-icon"></i>
                            <span class="role-name">Estudiante</span>
                        </div>
                    </div>
                    <small class="text-danger" id="roleError" style="display: none; color: #dc3545; font-size: 12px;">
                        Debe seleccionar un tipo de usuario
                    </small>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="rememberCheckboxInput" class="hidden"> <!-- INPUT REAL OCULTO -->
                    <div class="checkbox-input" id="rememberCheckboxVisual" aria-labelledby="rememberLabel"></div>
                    <label class="checkbox-label" id="rememberLabel" for="rememberCheckboxInput"> <!-- CORREGIDO -->
                        Recordar mis datos
                    </label>
                </div>

                <button type="submit" class="btn-signin pulse">
                    <i class="fas fa-sign-in-alt"></i>
                    INICIAR SESIÓN
                </button>

                <!-- Footer institucional -->
                <div class="institutional-footer">
                    <div class="institutional-name">IESTP "Andrés Avelino Cáceres Dorregaray"</div>
                    <div class="institutional-subtitle">Sistema de Gestión EFSRT - 2025</div>
                </div>
            </form>

            <!-- ... código posterior ... -->
        </div>
    </div>
    <script>
        // 🔐 BLOQUEO EN PÁGINA DE LOGIN
        if (window.history.replaceState) {
            // Reemplazar la entrada actual en el historial
            window.history.replaceState(null, null, window.location.href);
        }

        // Prevenir cache
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };

        // Si llega aquí estando logueado, redirigir
        if (window.location.href.indexOf('c=Login') !== -1) {
            setTimeout(function() {
                // Pequeño delay para evitar bucles
                window.history.replaceState(null, null, 'index.php?c=Inicio&a=index');
            }, 100);
        }
    </script>

    <script src="assets/js/login.js"></script>
</body>

</html>