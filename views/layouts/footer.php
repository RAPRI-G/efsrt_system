        </div>
    </main>

    <!-- Modal de Confirmación de Cierre de Sesión -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 w-96 max-w-md mx-4">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-4">
                    <i class="fas fa-sign-out-alt text-red-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary-blue">Cerrar Sesión</h3>
            </div>
            <p class="text-gray-600 mb-6">¿Estás seguro de que deseas cerrar sesión? Serás redirigido a la página de inicio de sesión.</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelLogout" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300">
                    Cancelar
                </button>
                <a href="index.php?c=Login&a=logout" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-300">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
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

        // Set active menu item basado en la URL actual
        function setActiveMenu() {
            const currentPage = window.location.href;
            const menuItems = document.querySelectorAll('.menu-item');
            
            menuItems.forEach(item => {
                const href = item.getAttribute('href');
                if (currentPage.includes(href.split('?')[1] || href)) {
                    item.classList.add('active-menu');
                } else {
                    item.classList.remove('active-menu');
                }
            });
        }

        // Logout functionality
        const logoutModal = document.getElementById('logoutModal');
        const logoutBtnSidebar = document.getElementById('logoutBtnSidebar');
        const cancelLogout = document.getElementById('cancelLogout');

        function openLogoutModal() {
            logoutModal.classList.remove('hidden');
            logoutModal.classList.add('flex');
        }

        function closeLogoutModal() {
            logoutModal.classList.remove('flex');
            logoutModal.classList.add('hidden');
        }

        logoutBtnSidebar.addEventListener('click', openLogoutModal);
        cancelLogout.addEventListener('click', closeLogoutModal);

        // Cerrar modal al hacer clic fuera
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                closeLogoutModal();
            }
        });

        // Inicializar la página
        document.addEventListener('DOMContentLoaded', function() {
            setActiveMenu();
        });
    </script>
</body>
</html>