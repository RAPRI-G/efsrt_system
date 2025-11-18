<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Experiencias Formativas</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            
            <?php if(isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?c=Login&a=auth">
                <div class="form-group">
                    <label class="form-label" for="usuario">
                        Usuario
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-left-icon"></i>
                        <input type="text" id="usuario" name="usuario" class="form-input" placeholder="Ingresa tu usuario" required 
                               value="<?php echo $_POST['usuario'] ?? ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">
                        Contraseña
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-left-icon"></i>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Ingresa tu contraseña" required>
                        <div class="input-icon" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Selector de Roles (informativo, no se envía al servidor) -->
                <div class="roles-group">
                    <label class="roles-label">Tipo de usuario</label>
                    <div class="roles-container">
                        <div class="role-option" data-role="administrador">
                            <i class="fas fa-crown role-icon"></i>
                            <span class="role-name">Administrador</span>
                        </div>
                        <div class="role-option" data-role="docente">
                            <i class="fas fa-chalkboard-teacher role-icon"></i>
                            <span class="role-name">Docente</span>
                        </div>
                        <div class="role-option" data-role="estudiante">
                            <i class="fas fa-user-graduate role-icon"></i>
                            <span class="role-name">Estudiante</span>
                        </div>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <div class="checkbox-input" id="rememberCheckbox"></div>
                    <label class="checkbox-label" for="rememberCheckbox">
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
        </div>
    </div>

    <script src="assets/js/login.js"></script>
</body>
</html>