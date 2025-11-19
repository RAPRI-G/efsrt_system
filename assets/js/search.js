// Buscador en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscadorEstudiantes');
    const resultados = document.getElementById('resultadosBusqueda');
    let timeoutId;

    if (buscador && resultados) {
        // Evento de input
        buscador.addEventListener('input', function(e) {
            clearTimeout(timeoutId);
            const termino = e.target.value.trim();

            if (termino.length < 2) {
                resultados.classList.add('hidden');
                resultados.innerHTML = '';
                return;
            }

            timeoutId = setTimeout(() => {
                buscarEstudiantes(termino);
            }, 300);
        });

        // Ocultar resultados al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!buscador.contains(e.target) && !resultados.contains(e.target)) {
                resultados.classList.add('hidden');
            }
        });

        // Mostrar resultados al hacer focus si hay término
        buscador.addEventListener('focus', function() {
            const termino = this.value.trim();
            if (termino.length >= 2 && resultados.innerHTML.trim() !== '') {
                resultados.classList.remove('hidden');
            }
        });

        // Limpiar al presionar ESC
        buscador.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                resultados.classList.add('hidden');
                this.value = '';
            }
        });
    }

    function buscarEstudiantes(termino) {
        const formData = new FormData();
        formData.append('termino', termino);

        fetch('index.php?c=Buscar&a=estudiantes', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                mostrarResultados(data);
            })
            .catch(error => {
                console.error('Error en la búsqueda:', error);
                mostrarError();
            });
    }

    function mostrarResultados(resultados) {
        const contenedor = document.getElementById('resultadosBusqueda');

        if (resultados.length === 0) {
            contenedor.innerHTML = `
                <div class="p-4 text-gray-500 text-center">
                    <i class="fas fa-search mb-2 text-gray-400"></i>
                    <p class="text-sm">No se encontraron estudiantes</p>
                    <p class="text-xs text-gray-400 mt-1">Intenta con otro nombre o DNI</p>
                </div>
            `;
        } else {
            contenedor.innerHTML = resultados.map(est => `
                <a href="${est.url}" class="block p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors duration-200 group">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                                ${est.texto}
                            </div>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-id-card mr-1"></i>${est.dni}
                                </span>
                                ${est.celular ? `
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-phone mr-1"></i>${est.celular}
                                </span>
                                ` : ''}
                                ${est.email ? `
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-envelope mr-1"></i>${est.email}
                                </span>
                                ` : ''}
                            </div>
                        </div>
                        <div class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-chevron-right text-blue-500 text-sm"></i>
                        </div>
                    </div>
                </a>
            `).join('');
        }

        contenedor.classList.remove('hidden');
    }

    function mostrarError() {
        const contenedor = document.getElementById('resultadosBusqueda');
        contenedor.innerHTML = `
            <div class="p-4 text-red-500 text-center">
                <i class="fas fa-exclamation-triangle mb-2"></i>
                <p class="text-sm">Error al buscar estudiantes</p>
                <p class="text-xs text-red-400 mt-1">Intenta nuevamente</p>
            </div>
        `;
        contenedor.classList.remove('hidden');
    }
});