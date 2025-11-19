// Configuración de Tailwind
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

// Funcionalidad del sidebar
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    const toggleSidebar = document.getElementById('toggleSidebar');
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            sidebar.classList.toggle('collapsed');

            if (sidebar.classList.contains('collapsed')) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-20');
            } else {
                mainContent.classList.remove('ml-20');
                mainContent.classList.add('ml-64');
            }
        });
    }

    // Set active menu item basado en el controlador actual
    setActiveMenu();

    // Logout functionality
    initializeLogoutModal();
});

// Función para establecer menú activo
function setActiveMenu() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentController = urlParams.get('c');
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && href.includes('?')) {
            const itemParams = new URLSearchParams(href.split('?')[1]);
            const itemController = itemParams.get('c');

            if (currentController === itemController) {
                item.classList.add('active-menu');
            } else {
                item.classList.remove('active-menu');
            }
        }
    });
}

// Función para inicializar modal de logout
function initializeLogoutModal() {
    const logoutModal = document.getElementById('logoutModal');
    const logoutBtnSidebar = document.getElementById('logoutBtnSidebar');
    const cancelLogout = document.getElementById('cancelLogout');

    function openLogoutModal() {
        if (logoutModal) {
            logoutModal.classList.remove('hidden');
            logoutModal.classList.add('flex');
        }
    }

    function closeLogoutModal() {
        if (logoutModal) {
            logoutModal.classList.remove('flex');
            logoutModal.classList.add('hidden');
        }
    }

    if (logoutBtnSidebar) {
        logoutBtnSidebar.addEventListener('click', openLogoutModal);
    }

    if (cancelLogout) {
        cancelLogout.addEventListener('click', closeLogoutModal);
    }

    // Cerrar modal al hacer clic fuera
    if (logoutModal) {
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                closeLogoutModal();
            }
        });
    }
}