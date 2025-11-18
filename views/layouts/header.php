<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema EFSRT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'institucional': {
                            '50': '#f8f9fa',
                            '100': '#e9ecef',
                            '200': '#dee2e6',
                            '300': '#ced4da',
                            '400': '#6c757d',
                            '500': '#495057',
                            '600': '#343a40',
                            '700': '#212529',
                            '800': '#1a1e21',
                            '900': '#0C1F36',
                        },
                        'accent': {
                            '400': '#fb7185',
                            '500': '#f43f5e',
                            '600': '#e11d48',
                        },
                        'success': '#198754',
                        'warning': '#ffc107',
                        'info': '#0dcaf0',
                        'primary-blue': '#0C1F36'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(180deg, #0A1729 0%, #0C1F36 50%, #0E2542 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .submenu-text,
        .sidebar.collapsed .user-info,
        .sidebar.collapsed .search-container {
            display: none;
        }

        .main-content {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .active-menu {
            background: linear-gradient(90deg, rgba(13, 202, 240, 0.2) 0%, rgba(13, 202, 240, 0.1) 100%);
            color: white;
            border-left: 4px solid #0dcaf0;
        }

        .header-gradient {
            background: linear-gradient(135deg, #0A1729 0%, #0C1F36 50%, #0E2542 100%);
            box-shadow: 0 4px 20px rgba(12, 31, 54, 0.15);
            border-bottom: 3px solid #0dcaf0;
        }

        .menu-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-left: 4px solid #0dcaf0;
            transform: translateX(4px);
        }

        .search-box {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 4px 15px rgba(13, 202, 240, 0.2);
        }

        .user-avatar {
            background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
            box-shadow: 0 4px 15px rgba(13, 202, 240, 0.3);
        }

        .user-icon {
            background: linear-gradient(135deg, #0C1F36 0%, #1a365d 100%);
            box-shadow: 0 4px 15px rgba(12, 31, 54, 0.3);
        }

        .logout-btn {
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(220, 38, 38, 0.3);
            transform: translateX(5px);
        }

        .header-icon {
            transition: all 0.3s ease;
        }

        .header-icon:hover {
            transform: scale(1.1);
            color: #f8f9fa;
        }

        .nav-divider {
            border-color: rgba(255, 255, 255, 0.1);
        }

        .institute-name {
            background: linear-gradient(90deg, #0dcaf0, #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
        }

        .logout-section {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header con color #0C1F36 -->
    <header class="header-gradient fixed top-0 left-0 right-0 z-10">
        <div class="flex items-center justify-between px-6 py-4">
            <!-- Logo ESFRH -->
            <div class="flex items-center space-x-4">
                <button id="toggleSidebar" class="text-white hover:text-info transition-all duration-300 transform hover:scale-110">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div class="flex items-center space-x-3">
                    <div class="logo-container h-10 w-10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-primary-blue text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-lg font-bold text-white leading-tight">SISTEMA EFSRT</h1>
                        <p class="text-xs text-blue-300">Experiencias Formativas</p>
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

                <!-- Perfil de Usuario -->
                <div class="flex items-center space-x-2 text-white">
                    <div class="user-icon h-8 w-8 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <span class="hidden md:block text-sm font-medium">
                        <?php
                        require_once 'helpers/SessionHelper.php';
                        if (SessionHelper::isLoggedIn()):
                            $usuario = SessionHelper::get('usuario');
                            echo $usuario['nombre_completo'];
                        else:
                            echo 'Invitado';
                        endif;
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar con color #0C1F36 -->
    <div class="sidebar fixed left-0 top-0 h-screen w-64 overflow-hidden text-white shadow-xl pt-16">
        <div class="sidebar-content">
            <!-- Información del Usuario -->
            <div class="p-5 border-b nav-divider user-info">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="user-avatar h-12 w-12 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                        <?php
                        if (SessionHelper::isLoggedIn()):
                            $usuario = SessionHelper::get('usuario');
                            echo substr($usuario['nombre_completo'], 0, 1);
                        else:
                            echo 'U';
                        endif;
                        ?>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-white">
                            <?php
                            if (SessionHelper::isLoggedIn()):
                                echo $usuario['nombre_completo'];
                            else:
                                echo 'Usuario';
                            endif;
                            ?>
                        </span>
                        <span class="text-xs text-blue-300 bg-blue-900/30 px-2 py-1 rounded-full mt-1 inline-block">
                            <?php
                            if (SessionHelper::isLoggedIn()):
                                echo ucfirst($usuario['rol']);
                            else:
                                echo 'Invitado';
                            endif;
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Buscador -->
            <div class="p-5 border-b nav-divider search-container">
                <div class="relative">
                    <input type="text" placeholder="Buscar estudiantes, módulos..."
                        class="w-full py-3 pl-10 pr-4 rounded-xl search-box text-white placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm transition-all duration-300">
                    <i class="fas fa-search absolute left-3 top-3.5 text-blue-300"></i>
                </div>
            </div>

            <!-- Menú de Navegación PARA EFSRT -->
            <nav class="mt-2 px-3">
                <ul class="space-y-2">
                    <!-- Inicio -->
                    <li>
                        <a href="index.php?c=Inicio&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Inicio' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-home text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Inicio</span>
                        </a>
                    </li>

                    <!-- Prácticas -->
                    <li>
                        <a href="index.php?c=Practica&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Practica' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-briefcase text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Prácticas</span>
                        </a>
                    </li>

                    <!-- Estudiantes -->
                    <li>
                        <a href="index.php?c=Estudiante&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Estudiante' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-users text-lg w-6"></i>
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
                    <!-- Configuración -->
                    <li>
                        <a href="index.php?c=Configuracion&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Configuracion' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-cog text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Configuración</span>
                        </a>
                    </li>

                    <!-- Ayuda -->
                    <li>
                        <a href="index.php?c=Ayuda&a=index" class="menu-item flex items-center px-4 py-3 text-blue-200 rounded-lg transition-all duration-300 <?php echo ($_GET['c'] ?? '') == 'Ayuda' ? 'active-menu' : ''; ?>">
                            <i class="fas fa-info-circle text-lg w-6"></i>
                            <span class="menu-text ml-3 font-medium">Ayuda</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- CERRAR SESIÓN - EN LA PARTE INFERIOR -->
        <div class="logout-section p-4 border-t nav-divider mt-auto">
            <button id="logoutBtnSidebar" class="logout-btn w-full flex items-center px-4 py-3 text-red-300 rounded-lg transition-all duration-300 hover:bg-red-900/30">
                <i class="fas fa-sign-out-alt text-lg w-6"></i>
                <span class="menu-text ml-3 font-medium">Cerrar Sesión</span>
            </button>
        </div>
    </div>

    <!-- Contenido Principal -->
    <main class="main-content ml-64 pt-16 min-h-screen transition-all duration-300">
        <div class="p-6"></div>