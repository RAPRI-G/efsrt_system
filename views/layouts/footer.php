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

    <!-- Local JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/search.js"></script>
<<<<<<< HEAD
    
    
=======
>>>>>>> 91d2d54616c4522e37d058508f3e9ca7c763ae23
</body>

</html>