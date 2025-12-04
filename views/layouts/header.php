<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ESFRH</title>

    <!-- CDN Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Local CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body class="bg-gray-50">
    <!-- Header con color #0C1F36 -->
    <header class="header-gradient fixed top-0 left-0 right-0 z-10">
        <div class="flex items-center justify-between px-6 py-4">
            <!-- Logo ESFRH y Menú Hamburguesa -->
            <div class="flex items-center space-x-4">
                <button id="toggleSidebar" class="text-white hover:text-info transition-all duration-300 transform hover:scale-110">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div class="flex items-center space-x-3">
                    <div class="logo-container h-10 w-10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-primary-blue text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-lg font-bold text-white leading-tight">ESFRH</h1>
                        <p class="text-xs text-blue-300">Experiencias Formativas</p>
                    </div>
                </div>
            </div>

            <!-- Elementos Utilitarios (buscador, notificaciones, perfil) -->
            <div class="flex items-center space-x-4">
                <!-- 🔥 BUSCADOR AL LADO DE NOTIFICACIONES -->
                <div class="header-search hidden lg:block">
                    <div class="relative">
                        <input type="text"
                            id="buscadorEstudiantes"
                            placeholder="Buscar estudiantes..."
                            class="w-64 py-2 pl-10 pr-4 rounded-xl search-box text-white placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm transition-all duration-300"
                            autocomplete="off">
                        <i class="fas fa-search absolute left-3 top-2.5 text-blue-300"></i>

                        <!-- Resultados del buscador -->
                        <div id="resultadosBusqueda" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl z-50 hidden max-h-80 overflow-y-auto border border-gray-200">
                            <!-- Los resultados se cargarán aquí -->
                        </div>
                    </div>
                </div>

                <!-- Elementos Utilitarios -->
                <div class="flex items-center space-x-4">
                    <!-- Notificaciones -->
                    <div class="relative">
                        <button class="header-icon text-white hover:text-blue-300 transition-all duration-300" title="Notificaciones">
                            <i class="fas fa-bell text-lg"></i>
                        </button>
                        <span class="notification-dot absolute -top-1 -right-1 bg-accent-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </div>

                    <!-- Botón de búsqueda para móviles -->
                    <button class="header-icon text-white hover:text-blue-300 transition-all duration-300 lg:hidden" title="Buscar">
                        <i class="fas fa-search text-lg"></i>
                    </button>

                    <!-- Perfil de Usuario -->
                    <div class="flex items-center space-x-2 text-white">
                        <div class="user-icon h-8 w-8 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="hidden md:block text-sm font-medium">
                            <?php
                            if (SessionHelper::isLoggedIn()):
                                $usuario = SessionHelper::get('usuario');
                                echo htmlspecialchars($usuario['nombre_completo'] ?? 'Administrador');
                            else:
                                echo 'Administrador';
                            endif;
                            ?>
                        </span>
                    </div>
                    <!-- DEBUG TEMPORAL -->
                    <div style="display: none;">
                        <?php
                        if (SessionHelper::isLoggedIn()):
                            $usuario = SessionHelper::get('usuario');
                            echo "DEBUG - ID: " . $usuario['id'] . "<br>";
                            echo "DEBUG - Usuario: " . $usuario['usuario'] . "<br>";
                            echo "DEBUG - Nombre Completo: " . ($usuario['nombre_completo'] ?? 'NO HAY NOMBRE') . "<br>";
                            echo "DEBUG - Rol: " . $usuario['rol'] . "<br>";
                            echo "DEBUG - Tipo: " . $usuario['tipo'] . "<br>";
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar con color #0C1F36 -->
    <div class="sidebar fixed left-0 top-0 h-screen w-64 overflow-hidden text-white shadow-xl pt-16">
        <div class="sidebar-content sidebar-adjust">
            <!-- Información del Usuario -->
            <div class="p-5 border-b nav-divider user-info">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="user-avatar h-12 w-12 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                        <?php
                        if (SessionHelper::isLoggedIn()):
                            $usuario = SessionHelper::get('usuario');
                            echo strtoupper(substr($usuario['nombre_completo'] ?? 'S', 0, 1));
                        else:
                            echo 'S';
                        endif;
                        ?>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-white">
                            <?php
                            if (SessionHelper::isLoggedIn()):
                                echo htmlspecialchars($usuario['nombre_completo'] ?? 'Soller Rivera Samira');
                            else:
                                echo 'Soller Rivera Samira';
                            endif;
                            ?>
                        </span>
                        <span class="text-xs text-blue-300 bg-blue-900/30 px-2 py-1 rounded-full mt-1 inline-block">
                            <?php
                            if (SessionHelper::isLoggedIn()):
                                echo ucfirst($usuario['rol'] ?? 'Administrador');
                            else:
                                echo 'Administrador';
                            endif;
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Menú de Navegación -->
            <nav class="mt-2 px-3">
                <ul class="space-y-2">
                    <!-- Inicio -->
                    <li>
                        <a href="index.php?c=Inicio&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Inicio' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-home text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Inicio</span>
                        </a>
                    </li>

                    <!-- Estudiantes -->
                    <li>
                        <a href="index.php?c=Estudiante&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Estudiante' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-user-graduate text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Estudiantes</span>
                        </a>
                    </li>

                    <!-- Empresas -->
                    <li>
                        <a href="index.php?c=Empresa&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Empresa' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-building text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Empresas</span>
                        </a>
                    </li>

                    <!-- Módulos -->
                    <li>
                        <a href="index.php?c=Modulos&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Modulos' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-cubes text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Módulos</span>
                        </a>
                    </li>

                    <!-- Prácticas -->
                    <li>
                        <a href="index.php?c=Practica&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Practica' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-briefcase text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Prácticas</span>
                        </a>
                    </li>

                    <!-- Asistencias -->
                    <li>
                        <a href="index.php?c=Asistencia&a=dashboard" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 active-menu">
                            <i class="fas fa-calendar-check text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Asistencias</span>
                        </a>
                    </li>

                    <!-- Documentos -->
                    <li>
                        <a href="index.php?c=Documento&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Documento' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-file-alt text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Documentos</span>
                        </a>
                    </li>

                    <!-- Reportes -->
                    <li>
                        <a href="index.php?c=Reporte&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Reporte' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-chart-bar text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Reportes</span>
                        </a>
                    </li>
                </ul>

                <!-- Menús inferiores -->
                <ul class="space-y-2 mt-6">
                    <!-- Usuarios -->
                    <li>
                        <a href="index.php?c=Usuario&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300">
                            <i class="fas fa-users text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Usuarios</span>
                        </a>
                    </li>

                    <!-- Información -->
                    <li>
                        <a href="#" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300">
                            <i class="fas fa-info-circle text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Información</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- CERRAR SESIÓN - EN LA PARTE INFERIOR -->
        <div class="logout-section p-4 border-t nav-divider">
            <button id="logoutBtnSidebar" class="logout-btn w-full flex items-center px-4 py-3 text-red-300 rounded-lg transition-all duration-300 hover:bg-red-900/30">
                <i class="fas fa-sign-out-alt text-lg w-6"></i>
                <span class="menu-text ml-3 font-medium">Cerrar Sesión</span>
            </button>
        </div>
    </div>

    <!-- Contenido Principal -->
    <main class="main-content ml-64 pt-16 min-h-screen transition-all duration-300">
        <div class="p-6">