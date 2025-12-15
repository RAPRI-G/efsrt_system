async function debugServerResponse(url) {
  try {
    const response = await fetch(url);
    const text = await response.text();
    console.log("=== DEBUG SERVER RESPONSE ===");
    console.log("URL:", url);
    console.log("Status:", response.status);
    console.log("Content-Type:", response.headers.get("content-type"));
    console.log("Response:", text.substring(0, 500)); // Primeros 500 caracteres
    return text;
  } catch (error) {
    console.error("Debug error:", error);
  }
}

// ==============================
// SISTEMA DE NOTIFICACIONES
// ==============================

function mostrarNotificacion(tipo, titulo, mensaje, duracion = 5000) {
  const container = document.getElementById("notificationContainer");

  // Limitar a 3 notificaciones simultáneas
  if (container.children.length >= 3) {
    const primera = container.firstChild;
    primera.classList.remove("show");
    setTimeout(() => primera.remove(), 300);
  }

  const notification = document.createElement("div");
  notification.className = `notification ${tipo}`;

  const iconos = {
    success: "fa-check-circle",
    error: "fa-exclamation-circle",
    warning: "fa-exclamation-triangle",
    info: "fa-info-circle",
  };

  notification.innerHTML = `
        <i class="notification-icon fas ${iconos[tipo]}"></i>
        <div class="notification-content">
            <div class="notification-title">${titulo}</div>
            <div class="notification-message">${mensaje}</div>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;

  container.appendChild(notification);

  // Animación de entrada
  setTimeout(() => notification.classList.add("show"), 10);

  // Cerrar notificación
  const closeBtn = notification.querySelector(".notification-close");
  closeBtn.addEventListener("click", () => {
    notification.classList.remove("show");
    setTimeout(() => notification.remove(), 500);
  });

  // Auto-remover después de la duración
  if (duracion > 0) {
    setTimeout(() => {
      if (notification.parentNode) {
        notification.classList.remove("show");
        setTimeout(() => notification.remove(), 500);
      }
    }, duracion);
  }
}

// ==============================
// SISTEMA DE CONFIRMACIÓN
// ==============================

function mostrarConfirmacion(titulo, mensaje, tipo = "warning") {
  return new Promise((resolve) => {
    const modal = document.getElementById("confirmationModal");
    const title = document.getElementById("confirmationTitle");
    const message = document.getElementById("confirmationMessage");
    const icon = document.getElementById("confirmationIcon");
    const confirmBtn = document.getElementById("confirmAction");
    const cancelBtn = document.getElementById("confirmCancel");

    // Configurar según el tipo
    const config =
      {
        warning: { icon: "fa-exclamation-triangle", btnClass: "" },
        danger: { icon: "fa-trash", btnClass: "" },
        success: { icon: "fa-check", btnClass: "success" },
      }[tipo] || config.warning;

    title.textContent = titulo;
    message.textContent = mensaje;
    icon.className = `confirmation-icon fas ${config.icon}`;
    confirmBtn.className = `btn-confirm ${config.btnClass}`;
    confirmBtn.textContent = tipo === "success" ? "Aceptar" : "Confirmar";

    // Mostrar modal
    modal.classList.add("show");

    // Event listeners
    const handleConfirm = () => {
      cleanup();
      resolve(true);
    };

    const handleCancel = () => {
      cleanup();
      resolve(false);
    };

    const handleKeydown = (e) => {
      if (e.key === "Escape") handleCancel();
      if (e.key === "Enter") handleConfirm();
    };

    const cleanup = () => {
      modal.classList.remove("show");
      confirmBtn.removeEventListener("click", handleConfirm);
      cancelBtn.removeEventListener("click", handleCancel);
      document.removeEventListener("keydown", handleKeydown);
    };

    confirmBtn.addEventListener("click", handleConfirm);
    cancelBtn.addEventListener("click", handleCancel);
    document.addEventListener("keydown", handleKeydown);
  });
}

// Agregar event listeners a los botones de acción - VERSIÓN MEJORADA
// Agregar event listeners a los botones de acción - VERSIÓN MEJORADA
function agregarEventListenersAcciones() {
  const usuario = window.usuarioActual || {};
  const esAdministrador = usuario.esAdministrador || false;

  console.log("Configurando listeners. Es administrador:", esAdministrador);

  // Usar event delegation para manejar clicks dinámicos
  document.addEventListener("click", function (e) {
    const usuario = window.usuarioActual || {};
    const esAdministrador = usuario.esAdministrador || false;

    // Editar
    if (e.target.closest(".editar-estudiante")) {
      const btn = e.target.closest(".editar-estudiante");
      const id = btn.getAttribute("data-id");
      abrirModalEditar(id);
    }

    // Ver detalles
    if (e.target.closest(".ver-estudiante")) {
      const btn = e.target.closest(".ver-estudiante");
      const id = btn.getAttribute("data-id");
      verEstudiante(id);
    }

    // Eliminar - SOLO si es administrador
    if (e.target.closest(".eliminar-estudiante")) {
      if (!esAdministrador) {
        mostrarNotificacion(
          "warning",
          "Acceso restringido",
          "Solo los administradores pueden eliminar estudiantes."
        );
        return;
      }

      const btn = e.target.closest(".eliminar-estudiante");
      const id = btn.getAttribute("data-id");
      eliminarEstudiante(id);
    }
  });

  // También mantener los listeners directos por compatibilidad
  setTimeout(() => {
    document.querySelectorAll(".editar-estudiante").forEach((btn) => {
      btn.addEventListener("click", function () {
        const id = this.getAttribute("data-id");
        abrirModalEditar(id);
      });
    });

    document.querySelectorAll(".ver-estudiante").forEach((btn) => {
      btn.addEventListener("click", function () {
        const id = this.getAttribute("data-id");
        verEstudiante(id);
      });
    });

    document.querySelectorAll(".eliminar-estudiante").forEach((btn) => {
      btn.addEventListener("click", async function () {
        if (!esAdministrador) {
          mostrarNotificacion(
            "warning",
            "Acceso restringido",
            "Solo los administradores pueden eliminar estudiantes."
          );
          return;
        }

        const id = this.getAttribute("data-id");
        await eliminarEstudiante(id);
      });
    });
  }, 100);
}
// ==============================
// SISTEMA DE CARGA
// ==============================

function mostrarCarga(mensaje = "Procesando...") {
  const overlay = document.getElementById("loadingOverlay");
  overlay.classList.add("show");
}

function ocultarCarga() {
  const overlay = document.getElementById("loadingOverlay");
  overlay.classList.remove("show");
}

// ==============================
// DATOS Y CONFIGURACIÓN
// ==============================

const datosEstudiantes = {
  estudiantes: [],
  programas: [],
  matriculas: [],
  practicas: [],
};

const configPaginacion = {
  paginaActual: 1,
  elementosPorPagina: 10,
  totalElementos: 0,
};

// 🔥 FUNCIÓN MEJORADA para cargar turno
function cargarTurnoEnEdicion(turno) {
  const selectTurno = document.getElementById("turno");
  if (!selectTurno) {
    console.error("❌ Select de turno no encontrado");
    return;
  }

  console.log("🔄 Cargando turno:", turno);

  // 🔥 MAPEAR LOS VALORES CORRECTAMENTE
  const mapeoTurnos = {
    D: "DIURNO",
    DIURNO: "DIURNO",
    V: "VESPERTINO",
    VESPERTINO: "VESPERTINO",
    DIURNA: "DIURNO",
    VESPERTINA: "VESPERTINO",
  };

  const turnoMapeado = mapeoTurnos[turno] || turno;
  console.log("🔄 Turno mapeado:", turnoMapeado);

  // Buscar el turno en las opciones
  for (let i = 0; i < selectTurno.options.length; i++) {
    const option = selectTurno.options[i];
    if (option.value === turnoMapeado) {
      selectTurno.value = turnoMapeado;
      console.log("✅ Turno cargado correctamente:", turnoMapeado);
      return true;
    }
  }

  console.log("❌ No se pudo cargar el turno:", turno);
  return false;
}

// ==============================
// FUNCIONES PRINCIPALES
// ==============================

// Función para cargar datos desde la base de datos
async function cargarDatosEstudiantes() {
  mostrarCarga("Cargando datos de estudiantes...");

  try {
    const response = await fetch("index.php?c=Estudiante&a=apiEstudiantes");
    const result = await response.json();

    if (result.success) {
      datosEstudiantes.estudiantes = result.data.estudiantes || [];
      datosEstudiantes.programas = result.data.programas || [];

      aplicarFiltrosYRenderizar();
      actualizarDashboardEstudiantes();
    } else {
      throw new Error(result.error);
    }
  } catch (error) {
    console.error("Error al cargar datos:", error);
    mostrarNotificacion(
      "error",
      "Error",
      "No se pudieron cargar los datos de estudiantes"
    );
  } finally {
    ocultarCarga();
  }
}

// Función para agregar nuevo estudiante
async function agregarEstudiante(formData) {
  mostrarCarga("Guardando estudiante...");

  try {
    const response = await fetch("index.php?c=Estudiante&a=crear", {
      method: "POST",
      body: formData, // 🔥 Ahora enviamos FormData directamente
    });

    const result = await response.json();

    if (result.success) {
      await cargarDatosEstudiantes();
      mostrarNotificacion(
        "success",
        "¡Estudiante agregado!",
        `El estudiante se ha registrado correctamente`
      );
      return true;
    } else {
      throw new Error(result.error);
    }
  } catch (error) {
    console.error("Error al agregar estudiante:", error);
    mostrarNotificacion("error", "Error", "No se pudo agregar el estudiante");
    return false;
  } finally {
    ocultarCarga();
  }
}

// Función para editar estudiante
async function editarEstudiante(id, formData) {
  mostrarCarga("Actualizando estudiante...");

  try {
    const response = await fetch(
      `index.php?c=Estudiante&a=actualizar&id=${id}`,
      {
        method: "POST",
        body: formData, // 🔥 Ahora enviamos FormData directamente
      }
    );

    const result = await response.json();

    if (result.success) {
      await cargarDatosEstudiantes();
      mostrarNotificacion(
        "success",
        "¡Estudiante actualizado!",
        `El estudiante se ha actualizado correctamente`
      );
      return true;
    } else {
      throw new Error(result.error);
    }
  } catch (error) {
    console.error("Error al editar estudiante:", error);
    mostrarNotificacion(
      "error",
      "Error",
      "No se pudo actualizar el estudiante"
    );
    return false;
  } finally {
    ocultarCarga();
  }
}

// Función para eliminar estudiante - ELIMINACIÓN FÍSICA
async function eliminarEstudiante(id) {
  // 🔥 VERIFICAR ROL ANTES DE NADA
  const usuario = window.usuarioActual || {};
  if (!usuario.esAdministrador) {
    mostrarNotificacion(
      "error",
      "Acceso denegado",
      "Solo los administradores pueden eliminar estudiantes."
    );
    return false;
  }
  console.log("🗑️ Iniciando ELIMINACIÓN FÍSICA del estudiante ID:", id);

  // Buscar estudiante en los datos actuales
  const estudiante = datosEstudiantes.estudiantes.find((e) => e.id == id);
  if (!estudiante) {
    console.error("❌ Estudiante no encontrado en datos locales");
    mostrarNotificacion(
      "error",
      "Error",
      "No se encontró el estudiante para eliminar"
    );
    return false;
  }

  // 🔥 MENSAJE SIMPLE SIN HTML
  const confirmado = await mostrarConfirmacion(
    "Eliminar Estudiante",
    `¿Estás seguro de que deseas ELIMINAR PERMANENTEMENTE al estudiante:\n\n${estudiante.ap_est} ${estudiante.am_est}, ${estudiante.nom_est}\n\n⚠️  Esta acción NO se puede deshacer\n⚠️  Se eliminarán todos los datos del estudiante\n⚠️  Se eliminarán matrículas y prácticas relacionadas`,
    "danger"
  );

  if (!confirmado) {
    console.log("❌ Eliminación física cancelada por el usuario");
    mostrarNotificacion(
      "info",
      "Eliminación cancelada",
      "El estudiante se mantiene en el sistema"
    );
    return false;
  }

  mostrarCarga("Eliminando permanentemente...");

  try {
    const csrfToken = document.getElementById("csrf_token").value;
    console.log("🔐 Token CSRF:", csrfToken ? "✅ Presente" : "❌ Faltante");

    const url = `index.php?c=Estudiante&a=eliminar&id=${id}`;
    console.log("🌐 URL de eliminación física:", url);

    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `csrf_token=${encodeURIComponent(csrfToken)}`,
    });

    console.log("📡 Respuesta HTTP:", response.status, response.statusText);

    const text = await response.text();
    console.log("📄 Respuesta del servidor:", text);

    let result;
    try {
      result = JSON.parse(text);
    } catch (parseError) {
      console.error("❌ Error parseando JSON:", parseError);
      throw new Error("Error en la respuesta del servidor");
    }

    if (result.success) {
      console.log("✅ ELIMINACIÓN FÍSICA exitosa, recargando datos...");
      await cargarDatosEstudiantes();
      mostrarNotificacion(
        "success",
        "¡ELIMINADO!",
        "El estudiante ha sido eliminado permanentemente del sistema"
      );
      return true;
    } else {
      console.error("❌ Error del servidor:", result.error);
      throw new Error(
        result.error || "Error al eliminar el estudiante permanentemente"
      );
    }
  } catch (error) {
    console.error("💥 Error en eliminación física:", error);
    mostrarNotificacion("error", "Error", error.message);
    return false;
  } finally {
    ocultarCarga();
  }
}

// ==============================
// FUNCIONES DE INTERFAZ
// ==============================

// Actualizar estadísticas del dashboard
function actualizarDashboardEstudiantes() {
  const totalEstudiantes = datosEstudiantes.estudiantes.length;

  // Estudiantes activos
  const estudiantesActivos = datosEstudiantes.estudiantes.filter(
    (e) => e.estado === 1
  ).length;

  // 🔥 CORRECCIÓN: Estudiantes con prácticas "En curso"
  const estudiantesPracticasCurso = datosEstudiantes.estudiantes.filter(
    (e) => e.estado_practica === "En curso"
  ).length;

  // 🔥 NUEVO: Estudiantes con prácticas "Pendiente"
  const estudiantesPracticasPendiente = datosEstudiantes.estudiantes.filter(
    (e) => e.estado_practica === "Pendiente"
  ).length;

  // 🔥 NUEVO: Estudiantes con prácticas "Finalizado"
  const estudiantesPracticasFinalizado = datosEstudiantes.estudiantes.filter(
    (e) => e.estado_practica === "Finalizado"
  ).length;

  const totalProgramas = new Set(
    datosEstudiantes.estudiantes.map((e) => e.prog_estudios)
  ).size;

  // Actualizar contadores
  document.getElementById("total-estudiantes").textContent = totalEstudiantes;
  document.getElementById("estudiantes-activos").textContent =
    estudiantesActivos;

  // 🔥 ACTUALIZAR: Mostrar estudiantes EN CURSO (no todos)
  document.getElementById("estudiantes-practicas").textContent =
    estudiantesPracticasCurso;

  document.getElementById("total-programas").textContent = totalProgramas;

  // Actualizar textos descriptivos
  document.getElementById(
    "estudiantes-texto"
  ).textContent = `${totalEstudiantes} registrados`;
  document.getElementById(
    "activos-texto"
  ).textContent = `${estudiantesActivos} activos`;

  // 🔥 TEXTO MEJORADO: Mostrar desglose de prácticas
  document.getElementById(
    "practicas-texto"
  ).textContent = `${estudiantesPracticasCurso} en curso, ${estudiantesPracticasPendiente} pendientes, ${estudiantesPracticasFinalizado} finalizados`;

  document.getElementById(
    "programas-texto"
  ).textContent = `${totalProgramas} programas`;

  // Actualizar gráficos
  inicializarGraficosEstudiantes();
}

// Aplicar filtros y renderizar la tabla
function aplicarFiltrosYRenderizar() {
  const textoBusqueda = document
    .getElementById("buscarEstudiante")
    .value.toLowerCase();
  const programaFiltro = document.getElementById("filtroPrograma").value;
  const estadoFiltro = document.getElementById("filtroEstado").value;
  const generoFiltro = document.getElementById("filtroGenero").value;

  // Filtrar estudiantes
  let estudiantesFiltrados = datosEstudiantes.estudiantes.filter(
    (estudiante) => {
      // Filtro por texto de búsqueda
      const textoCoincide =
        textoBusqueda === "" ||
        estudiante.dni_est.includes(textoBusqueda) ||
        estudiante.ap_est.toLowerCase().includes(textoBusqueda) ||
        estudiante.am_est.toLowerCase().includes(textoBusqueda) ||
        estudiante.nom_est.toLowerCase().includes(textoBusqueda);

      // Filtro por programa
      const programaCoincide =
        programaFiltro === "all" || estudiante.prog_estudios == programaFiltro;

      // Filtro por estado (null = inactivo)
      let estadoCoincide = true;
      if (estadoFiltro !== "all") {
        if (estadoFiltro === "1") {
          // Solo activos (estado = 1)
          estadoCoincide = estudiante.estado === 1;
        } else if (estadoFiltro === "0") {
          // Inactivos (estado = 0 o null)
          estadoCoincide =
            estudiante.estado === 0 || estudiante.estado === null;
        }
      }

      // Filtro por género
      const generoCoincide =
        generoFiltro === "all" || estudiante.sex_est === generoFiltro;

      return (
        textoCoincide && programaCoincide && estadoCoincide && generoCoincide
      );
    }
  );

  // 🔥 CORRECCIÓN: Guardar estudiantes filtrados globalmente
  window.estudiantesFiltradosActuales = estudiantesFiltrados;

  // Actualizar configuración de paginación
  configPaginacion.totalElementos = estudiantesFiltrados.length;
  configPaginacion.paginaActual = 1;

  // Renderizar tabla
  renderizarTablaEstudiantes(estudiantesFiltrados);
  actualizarContadores(estudiantesFiltrados.length);
  actualizarPaginacion();
}

// Renderizar la tabla de estudiantes
function renderizarTablaEstudiantes(estudiantes) {
  const tabla = document.getElementById("tabla-estudiantes-body");
  tabla.innerHTML = "";

  if (estudiantes.length === 0) {
    tabla.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    <i class="fas fa-search text-2xl text-gray-300 mb-2"></i>
                    <p class="font-medium">No se encontraron estudiantes</p>
                    <p class="text-sm">Intenta con otros términos de búsqueda</p>
                </td>
            </tr>
        `;
    return;
  }

  // 🔥 CORRECCIÓN: Usar los estudiantes filtrados actuales
  const estudiantesParaRenderizar =
    window.estudiantesFiltradosActuales || estudiantes;

  // Calcular índices para la paginación
  const inicio =
    (configPaginacion.paginaActual - 1) * configPaginacion.elementosPorPagina;
  const fin = inicio + configPaginacion.elementosPorPagina;
  const estudiantesPagina = estudiantesParaRenderizar.slice(inicio, fin);

  // 🔥 OBTENER INFORMACIÓN DEL USUARIO
  const usuario = window.usuarioActual || {};
  const esAdministrador = usuario.esAdministrador || false;

  console.log("Renderizando tabla. Usuario es administrador:", esAdministrador);

  estudiantesPagina.forEach((estudiante) => {
    const fila = document.createElement("tr");
    fila.className = "hover:bg-gray-50 transition-all duration-300 fade-in";

    // Determinar badge de estado
    let estadoBadge = "";
    if (estudiante.estado === 1) {
      estadoBadge = '<span class="badge-estado badge-activo">Activo</span>';
    } else {
      estadoBadge = '<span class="badge-estado badge-inactivo">Inactivo</span>';
    }

    // 🔥 CON ESTO:
    const practicasInfo = getInfoPracticas(estudiante);
    const practicasBadge = `<span class="${practicasInfo.clase}" title="${practicasInfo.tooltip}">
            <i class="fas ${practicasInfo.icono} mr-1"></i>
            ${practicasInfo.texto}
        </span>`;

    // 🔥 CORRECCIÓN: Generar botones según el rol
    let botonesHTML = "";

    // Todos los roles pueden ver y editar
    botonesHTML += `
            <button class="btn-accion btn-editar editar-estudiante" data-id="${estudiante.id}" title="Editar">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn-accion btn-ver ver-estudiante" data-id="${estudiante.id}" title="Ver detalles">
                <i class="fas fa-eye"></i>
            </button>
        `;

    // Solo administradores pueden eliminar
    if (esAdministrador) {
      botonesHTML += `
                <button class="btn-accion btn-eliminar eliminar-estudiante" data-id="${estudiante.id}" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            `;
    }

    fila.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-semibold mr-3 ${
                      estudiante.sex_est == "F"
                        ? "avatar-estudiante-femenino"
                        : "avatar-estudiante-masculino"
                    }">
                        ${estudiante.nom_est.charAt(
                          0
                        )}${estudiante.ap_est.charAt(0)}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-900">
                            ${estudiante.ap_est} ${estudiante.am_est}, ${
      estudiante.nom_est
    }
                        </div>
                        <div class="text-xs text-gray-500">
                            ${practicasBadge}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${
              estudiante.dni_est
            }</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${
              estudiante.nom_progest || "No asignado"
            }</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div class="font-medium">${
                  estudiante.id_matricula || "N/A"
                }</div>
                <div class="text-xs">${estudiante.turno || ""}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div>${estudiante.cel_est || "N/A"}</div>
                <div class="text-xs">${estudiante.mailp_est || ""}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${estadoBadge}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    ${botonesHTML}
                </div>
            </td>
        `;

    tabla.appendChild(fila);
  });

  // Agregar event listeners a los botones de acción
  agregarEventListenersAcciones();
}

function getInfoPracticas(estudiante) {
  const tienePracticas =
    estudiante.estado_practica || estudiante.total_practicas > 0;

  if (!tienePracticas) {
    return {
      texto: "Sin prácticas",
      clase: "badge-estado badge-inactivo",
      icono: "fa-times-circle",
      tooltip: "No tiene prácticas registradas",
      modulo: "",
    };
  }

  // Tiene prácticas - determinar estado
  const estado = estudiante.estado_practica || "Sin estado";
  const modulo = estudiante.modulo_practica || "Módulo no especificado";

  // Configurar según estado
  const configEstados = {
    "En curso": {
      texto: `En prácticas (${modulo})`,
      clase: "badge-estado badge-activo",
      icono: "fa-spinner fa-pulse",
      tooltip: `Prácticas en curso - Módulo: ${modulo}`,
    },
    Pendiente: {
      texto: `Pendiente (${modulo})`,
      clase: "badge-estado badge-warning",
      icono: "fa-clock",
      tooltip: `Prácticas pendientes - Módulo: ${modulo}`,
    },
    Finalizado: {
      texto: `Finalizado (${modulo})`,
      clase: "badge-estado badge-success",
      icono: "fa-check-circle",
      tooltip: `Prácticas finalizadas - Módulo: ${modulo}`,
    },
    default: {
      texto: `Prácticas (${modulo})`,
      clase: "badge-estado badge-info",
      icono: "fa-briefcase",
      tooltip: `Estado: ${estado} - Módulo: ${modulo}`,
    },
  };

  return configEstados[estado] || configEstados["default"];
}

// Agregar event listeners a los botones de acción
function agregarEventListenersAcciones() {
  document.querySelectorAll(".editar-estudiante").forEach((btn) => {
    btn.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      abrirModalEditar(id);
    });
  });

  document.querySelectorAll(".ver-estudiante").forEach((btn) => {
    btn.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      verEstudiante(id);
    });
  });

  document.querySelectorAll(".eliminar-estudiante").forEach((btn) => {
    btn.addEventListener("click", async function () {
      const id = this.getAttribute("data-id");
      await eliminarEstudiante(id);
    });
  });
}

// Actualizar contadores de estudiantes
function actualizarContadores(totalFiltrados) {
  const estudiantesParaContar =
    window.estudiantesFiltradosActuales || datosEstudiantes.estudiantes;
  const total = totalFiltrados || estudiantesParaContar.length;

  const inicio =
    (configPaginacion.paginaActual - 1) * configPaginacion.elementosPorPagina +
    1;
  const fin = Math.min(inicio + configPaginacion.elementosPorPagina - 1, total);

  document.getElementById(
    "estudiantes-mostrados"
  ).textContent = `${inicio}-${fin}`;
  document.getElementById("estudiantes-totales").textContent = total;

  document.getElementById("info-paginacion").textContent = `Página ${
    configPaginacion.paginaActual
  } de ${Math.ceil(total / configPaginacion.elementosPorPagina)}`;
}

// Actualizar controles de paginación
function actualizarPaginacion() {
  const totalPaginas = Math.ceil(
    configPaginacion.totalElementos / configPaginacion.elementosPorPagina
  );
  const paginacion = document.getElementById("paginacion");
  paginacion.innerHTML = "";

  if (totalPaginas <= 1) return;

  // Botón anterior
  const btnAnterior = document.createElement("button");
  btnAnterior.className = `px-3 py-1 rounded-lg border ${
    configPaginacion.paginaActual === 1
      ? "bg-gray-100 text-gray-400 cursor-not-allowed"
      : "bg-white text-gray-700 hover:bg-gray-50"
  }`;
  btnAnterior.innerHTML = '<i class="fas fa-chevron-left"></i>';
  btnAnterior.disabled = configPaginacion.paginaActual === 1;
  btnAnterior.addEventListener("click", function () {
    if (configPaginacion.paginaActual > 1) {
      configPaginacion.paginaActual--;
      // 🔥 CORRECCIÓN: Renderizar usando los estudiantes filtrados actuales
      renderizarTablaEstudiantes(
        window.estudiantesFiltradosActuales || datosEstudiantes.estudiantes
      );
      actualizarContadores(
        window.estudiantesFiltradosActuales?.length ||
          datosEstudiantes.estudiantes.length
      );
      actualizarPaginacion();
    }
  });
  paginacion.appendChild(btnAnterior);

  // Números de página
  const inicioPagina = Math.max(1, configPaginacion.paginaActual - 2);
  const finPagina = Math.min(totalPaginas, configPaginacion.paginaActual + 2);

  for (let i = inicioPagina; i <= finPagina; i++) {
    const btnPagina = document.createElement("button");
    btnPagina.className = `px-3 py-1 rounded-lg border ${
      i === configPaginacion.paginaActual
        ? "bg-primary-blue text-white"
        : "bg-white text-gray-700 hover:bg-gray-50"
    }`;
    btnPagina.textContent = i;
    btnPagina.addEventListener("click", function () {
      configPaginacion.paginaActual = i;
      // 🔥 CORRECCIÓN: Renderizar usando los estudiantes filtrados actuales
      renderizarTablaEstudiantes(
        window.estudiantesFiltradosActuales || datosEstudiantes.estudiantes
      );
      actualizarContadores(
        window.estudiantesFiltradosActuales?.length ||
          datosEstudiantes.estudiantes.length
      );
      actualizarPaginacion();
    });
    paginacion.appendChild(btnPagina);
  }

  // Botón siguiente
  const btnSiguiente = document.createElement("button");
  btnSiguiente.className = `px-3 py-1 rounded-lg border ${
    configPaginacion.paginaActual === totalPaginas
      ? "bg-gray-100 text-gray-400 cursor-not-allowed"
      : "bg-white text-gray-700 hover:bg-gray-50"
  }`;
  btnSiguiente.innerHTML = '<i class="fas fa-chevron-right"></i>';
  btnSiguiente.disabled = configPaginacion.paginaActual === totalPaginas;
  btnSiguiente.addEventListener("click", function () {
    if (configPaginacion.paginaActual < totalPaginas) {
      configPaginacion.paginaActual++;
      // 🔥 CORRECCIÓN: Renderizar usando los estudiantes filtrados actuales
      renderizarTablaEstudiantes(
        window.estudiantesFiltradosActuales || datosEstudiantes.estudiantes
      );
      actualizarContadores(
        window.estudiantesFiltradosActuales?.length ||
          datosEstudiantes.estudiantes.length
      );
      actualizarPaginacion();
    }
  });
  paginacion.appendChild(btnSiguiente);
}

// Inicializar gráficos de estudiantes
function inicializarGraficosEstudiantes() {
  // Gráfico de distribución por programa (se mantiene igual)
  const programasCount = {};
  datosEstudiantes.estudiantes.forEach((estudiante) => {
    const programa = estudiante.nom_progest || "No asignado";
    programasCount[programa] = (programasCount[programa] || 0) + 1;
  });

  const ctxProgramas = document.getElementById("programasChart");
  if (ctxProgramas) {
    if (window.programasChartInstance) {
      window.programasChartInstance.destroy();
    }

    window.programasChartInstance = new Chart(ctxProgramas, {
      type: "doughnut",
      data: {
        labels: Object.keys(programasCount),
        datasets: [
          {
            data: Object.values(programasCount),
            backgroundColor: [
              "#0C1F36",
              "#0dcaf0",
              "#198754",
              "#ffc107",
              "#6c757d",
            ],
            borderWidth: 2,
            borderColor: "#fff",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              padding: 20,
              usePointStyle: true,
              font: {
                size: 11,
              },
            },
          },
        },
      },
    });
  }

  // 🔥 CORRECCIÓN: Gráfico de estado de prácticas REAL
  const practicasCount = {
    "En curso": 0,
    Finalizado: 0,
    Pendiente: 0,
    "Sin prácticas": 0,
  };

  // Contar estudiantes por estado de prácticas
  datosEstudiantes.estudiantes.forEach((estudiante) => {
    if (estudiante.estado_practica) {
      // Si tiene estado de práctica definido
      if (estudiante.estado_practica === "En curso") {
        practicasCount["En curso"]++;
      } else if (estudiante.estado_practica === "Finalizado") {
        practicasCount["Finalizado"]++;
      } else if (estudiante.estado_practica === "Pendiente") {
        practicasCount["Pendiente"]++;
      }
    } else {
      // Si no tiene prácticas registradas
      practicasCount["Sin prácticas"]++;
    }
  });

  // 🔥 DEBUG: Ver conteo de prácticas
  console.log("Estados de prácticas:", practicasCount);

  const ctxPracticas = document.getElementById("practicasChart");
  if (ctxPracticas) {
    if (window.practicasChartInstance) {
      window.practicasChartInstance.destroy();
    }

    window.practicasChartInstance = new Chart(ctxPracticas, {
      type: "pie",
      data: {
        labels: Object.keys(practicasCount),
        datasets: [
          {
            data: Object.values(practicasCount),
            backgroundColor: [
              "#0dcaf0", // En curso - Azul
              "#198754", // Finalizado - Verde
              "#ffc107", // Pendiente - Amarillo
              "#6c757d", // Sin prácticas - Gris
            ],
            borderWidth: 2,
            borderColor: "#fff",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              padding: 20,
              usePointStyle: true,
              font: {
                size: 11,
              },
            },
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                const label = context.label || "";
                const value = context.raw || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = Math.round((value / total) * 100);
                return `${label}: ${value} (${percentage}%)`;
              },
            },
          },
        },
      },
    });
  }
}

// ==============================
// FUNCIONES DE MODALES
// ==============================

// Función para abrir modal de nuevo estudiante
// Función para abrir modal de NUEVO estudiante
function abrirModalNuevo() {
  document.getElementById("modalTitulo").textContent = "Nuevo Estudiante";
  document.getElementById("formEstudiante").reset();
  document.getElementById("estudianteId").value = "";

  // 🔥 SOLO en NUEVO: Configurar validación de DNI
  setTimeout(() => {
    configurarValidacionDNI();
  }, 100);

  // Resetear selects de ubigeo
  ["nac", "dir"].forEach((tipo) => {
    document.getElementById(`departamento_${tipo}`).value = "";
    document.getElementById(`provincia_${tipo}`).innerHTML =
      '<option value="">Provincia</option>';
    document.getElementById(`provincia_${tipo}`).disabled = true;
    document.getElementById(`distrito_${tipo}`).innerHTML =
      '<option value="">Distrito</option>';
    document.getElementById(`distrito_${tipo}`).disabled = true;
  });

  // Ocultar cualquier advertencia previa
  ocultarAdvertenciaDNI();

  document.getElementById("estudianteModal").classList.remove("hidden");
}

// 🔥 NUEVA FUNCIÓN: Actualizar token CSRF
async function actualizarTokenCSRF() {
  try {
    const response = await fetch("index.php?c=Estudiante&a=actualizarCSRF");
    const result = await response.json();

    if (result.success) {
      document.getElementById("csrf_token").value = result.token;
      console.log("Token CSRF actualizado:", result.token);
    }
  } catch (error) {
    console.error("Error al actualizar token CSRF:", error);
    // Si falla, intentamos regenerar localmente
    generarTokenCSRFLocal();
  }
}

function generarTokenCSRFLocal() {
  const token = generateRandomToken(32);
  document.getElementById("csrf_token").value = token;
  console.log("Token CSRF generado localmente:", token);
}

function generateRandomToken(length) {
  const chars =
    "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  let token = "";
  for (let i = 0; i < length; i++) {
    token += chars[Math.floor(Math.random() * chars.length)];
  }
  return token;
}

async function abrirModalEditar(id) {
  mostrarCarga("Cargando datos del estudiante...");

  try {
    const response = await fetch(`index.php?c=Estudiante&a=detalle&id=${id}`);

    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status}`);
    }

    const text = await response.text();
    let result;

    try {
      result = JSON.parse(text);
    } catch (parseError) {
      console.error("Error parseando JSON:", parseError);
      throw new Error("Error en el servidor: respuesta inválida");
    }

    if (result.success) {
      const estudiante = result.data;
      console.log("✅ Estudiante cargado para edición:", estudiante);

      // Llenar campos
      document.getElementById("modalTitulo").textContent = "Editar Estudiante";
      document.getElementById("estudianteId").value = estudiante.id;
      document.getElementById("dni_est").value = estudiante.dni_est || "";
      document.getElementById("ap_est").value = estudiante.ap_est || "";
      document.getElementById("am_est").value = estudiante.am_est || "";
      document.getElementById("nom_est").value = estudiante.nom_est || "";
      document.getElementById("cel_est").value = estudiante.cel_est || "";
      document.getElementById("dir_est").value = estudiante.dir_est || "";
      document.getElementById("mailp_est").value = estudiante.mailp_est || "";
      document.getElementById("fecnac_est").value = estudiante.fecnac_est || "";

      // Estado
      document.getElementById("estado").checked = estudiante.estado === 1;

      // Género
      const sexEst = document.getElementById("sex_est");
      if (sexEst) {
        sexEst.value = estudiante.sex_est || "";
      }

      // Programa de estudios
      const progEstudios = document.getElementById("prog_estudios");
      if (progEstudios && estudiante.prog_estudios) {
        progEstudios.value = estudiante.prog_estudios;
      }

      // Turno
      cargarTurnoEnEdicion(estudiante.turno);

      // Campos de matrícula
      document.getElementById("id_matricula").value =
        estudiante.id_matricula || "";
      document.getElementById("per_acad").value = estudiante.per_acad || "";

      // 🔥 CORRECCIÓN: Configurar validación de DNI para edición
      setTimeout(() => {
        configurarValidacionDNIEdicion(estudiante.id);
      }, 100);

      // Ubigeo
      if (estudiante.ubigeonac_est || estudiante.ubigeodir_est) {
        cargarUbigeoDesdeTexto(
          estudiante.ubigeonac_est,
          estudiante.ubigeodir_est
        );
      }

      document.getElementById("estudianteModal").classList.remove("hidden");
    } else {
      throw new Error(result.error || "Error al cargar datos del estudiante");
    }
  } catch (error) {
    console.error("Error al cargar estudiante para edición:", error);
    mostrarNotificacion("error", "Error", error.message);
  } finally {
    ocultarCarga();
  }
}

// 🔥 FUNCIÓN SIMPLIFICADA PARA CARGAR UBIGEO DESDE TEXTO
async function cargarUbigeoDesdeTexto(ubigeoNac, ubigeoDir) {
  console.log("🗺️ Cargando ubigeo desde texto...");

  // Simplemente establecer los valores en los campos ocultos
  if (ubigeoNac) {
    document.getElementById("ubigeonac_est").value = ubigeoNac;
    console.log("📍 Ubigeo nacimiento establecido:", ubigeoNac);
  }

  if (ubigeoDir) {
    document.getElementById("ubigeodir_est").value = ubigeoDir;
    console.log("📍 Ubigeo dirección establecido:", ubigeoDir);
  }
}

// 🔥 FUNCIÓN AUXILIAR PARA CARGAR UBIGEO INDIVIDUAL
async function cargarUbigeoIndividual(tipo, ubigeoTexto) {
  console.log(`🔄 Cargando ubigeo ${tipo}:`, ubigeoTexto);

  try {
    // Parsear el texto (formato: "Distrito, Provincia, Departamento")
    const partes = ubigeoTexto.split(", ").map((parte) => parte.trim());

    if (partes.length === 3) {
      const [distrito, provincia, departamento] = partes;

      console.log(
        `📍 ${tipo.toUpperCase()} - Distrito: ${distrito}, Provincia: ${provincia}, Departamento: ${departamento}`
      );

      // 🔥 BUSCAR DEPARTAMENTO
      const selectDepto = document.getElementById(`departamento_${tipo}`);
      if (selectDepto) {
        for (let i = 0; i < selectDepto.options.length; i++) {
          const option = selectDepto.options[i];
          if (option.text === departamento) {
            selectDepto.value = option.value;
            console.log(`✅ Departamento ${tipo} cargado:`, departamento);

            // 🔥 CARGAR PROVINCIAS después de seleccionar departamento
            setTimeout(async () => {
              await cargarProvincias(option.value, tipo, provincia, distrito);
            }, 300);

            break;
          }
        }
      }
    } else {
      console.log(`❌ Formato de ubigeo ${tipo} no válido:`, ubigeoTexto);
    }
  } catch (error) {
    console.error(`Error procesando ubigeo ${tipo}:`, error);
  }
}

// 🔥 NUEVA FUNCIÓN: Cargar datos de ubigeo en edición
async function cargarUbigeoEnEdicion(estudiante) {
  console.log("🗺️ Cargando datos de ubigeo para edición:", estudiante);

  try {
    // 🔥 CARGAR LUGAR DE NACIMIENTO si existe
    if (estudiante.ubigeonac_est) {
      console.log("📍 Ubigeo nacimiento encontrado:", estudiante.ubigeonac_est);
      // Aquí necesitaríamos una función para parsear el ubigeo y cargar los selects
      // Por ahora, lo dejamos como texto en el campo oculto
      document.getElementById("ubigeonac_est").value = estudiante.ubigeonac_est;
    }

    // 🔥 CARGAR LUGAR ACTUAL si existe
    if (estudiante.ubigeodir_est) {
      console.log("📍 Ubigeo dirección encontrado:", estudiante.ubigeodir_est);
      document.getElementById("ubigeodir_est").value = estudiante.ubigeodir_est;
    }

    // 🔥 CARGAR DEPARTAMENTOS, PROVINCIAS Y DISTRITOS
    // Esto es más complejo - necesitaríamos saber los IDs específicos
    // Por ahora, mostramos un mensaje
    console.log(
      "ℹ️ Para cargar ubigeo automáticamente, necesitamos los IDs de departamento/provincia/distrito"
    );
  } catch (error) {
    console.error("Error cargando ubigeo:", error);
  }
}

// 🔥 NUEVA FUNCIÓN: Configurar validación de DNI para edición
function configurarValidacionDNIEdicion(estudianteId) {
  const inputDNI = document.getElementById("dni_est");
  let timeout = null;

  // Limpiar event listeners anteriores
  inputDNI.replaceWith(inputDNI.cloneNode(true));
  const newInputDNI = document.getElementById("dni_est");

  newInputDNI.addEventListener("input", function () {
    const dni = this.value.trim();

    if (timeout) {
      clearTimeout(timeout);
    }

    ocultarAdvertenciaDNI();

    if (dni.length === 8) {
      if (!validarFormatoDNI(dni)) {
        mostrarAdvertenciaDNI("El DNI debe contener solo 8 dígitos numéricos.");
        return;
      }

      timeout = setTimeout(async () => {
        // 🔥 CORRECCIÓN: Pasar el ID del estudiante a excluir
        const existe = await verificarDNIExistenteEdicion(dni, estudianteId);
        if (existe) {
          mostrarAdvertenciaDNI(
            "Este DNI ya está registrado en OTRO estudiante. No podrás guardar los cambios."
          );
        }
      }, 500);
    }
  });
}

// Función para verificar si el DNI existe (MEJORADA)
async function verificarDNIExistente(dni, excluirId = null) {
  if (!dni || dni.length !== 8) return false;

  try {
    let url = `index.php?c=Estudiante&a=verificarDNI&dni=${dni}`;
    if (excluirId) {
      url += `&excluir_id=${excluirId}`;
    }

    const response = await fetch(url);
    const result = await response.json();

    if (result.success !== undefined) {
      return result.existe;
    } else {
      // Para compatibilidad con la versión anterior
      return result.existe || false;
    }
  } catch (error) {
    console.error("Error al verificar DNI:", error);
    return false;
  }
}

// 🔥 NUEVA FUNCIÓN: Verificar DNI excluyendo el ID actual (más robusta)
async function verificarDNIExistenteEdicion(dni, excluirId) {
  if (!dni || dni.length !== 8) return false;

  try {
    const response = await fetch(
      `index.php?c=Estudiante&a=verificarDNI&dni=${dni}&excluir_id=${excluirId}`
    );

    const text = await response.text();
    let result;

    try {
      result = JSON.parse(text);
    } catch (e) {
      console.error("Respuesta no es JSON válido:", text);
      return false;
    }

    console.log(
      `🔍 Verificación DNI: ${dni}, Excluir: ${excluirId}, Existe: ${result.existe}`
    );
    return result.existe || false;
  } catch (error) {
    console.error("Error al verificar DNI:", error);
    return false;
  }
}

function cerrarModalEstudiante() {
  document.getElementById("estudianteModal").classList.add("hidden");
}


async function verEstudiante(id) {
    mostrarCarga('Cargando detalles...');
    
    try {
        const response = await fetch(`index.php?c=Estudiante&a=detalle&id=${id}`);
        
        // 🔥 DEBUG: Ver la respuesta cruda
        const text = await response.text();
        console.log('📄 Respuesta del servidor (detalle):', text);
        
        let result;
        
        try {
            result = JSON.parse(text);
        } catch (parseError) {
            console.error('Respuesta no es JSON:', text);
            throw new Error('Error en el servidor: respuesta inválida');
        }
        
        if (result.success) {
            const estudiante = result.data;
            console.log('✅ Estudiante cargado para detalles:', estudiante);
            
            // 🔥 DEBUG: Ver qué campos de prácticas llegan
            console.log('📊 Campos de prácticas disponibles:');
            console.log('- estado_practica:', estudiante.estado_practica);
            console.log('- modulo_practica:', estudiante.modulo_practica);
            console.log('- empresa_practica:', estudiante.empresa_practica);
            console.log('- total_practicas:', estudiante.total_practicas);
            console.log('- total_practicas_curso:', estudiante.total_practicas_curso);
            
            mostrarDetallesEstudiante(estudiante);
        } else {
            throw new Error(result.error || 'Error al cargar detalles del estudiante');
        }
    } catch (error) {
        console.error('Error al cargar detalles:', error);
        mostrarNotificacion('error', 'Error', error.message);
    } finally {
        ocultarCarga();
    }
}

// Función auxiliar para formatear fechas
function formatearFecha(fecha) {
    if (!fecha || fecha === '0000-00-00' || fecha === 'null') {
        return 'No especificada';
    }
    
    try {
        const fechaObj = new Date(fecha);
        if (isNaN(fechaObj.getTime())) {
            return 'Fecha inválida';
        }
        
        const opciones = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            timeZone: 'UTC' // Para evitar problemas de zona horaria
        };
        
        return fechaObj.toLocaleDateString('es-ES', opciones);
    } catch (error) {
        console.error('Error formateando fecha:', fecha, error);
        return 'Error al formatear';
    }
}

function mostrarDetallesEstudiante(estudiante) {
    console.log('🎯 Mostrando detalles COMPLETOS del estudiante:', estudiante);
    
    // Guardar referencia global
    window.estudianteDetalleActual = estudiante;
    
    // Llenar los detalles básicos (esto se mantiene igual)
    document.getElementById('detalleModalTitulo').textContent = `Detalles de ${estudiante.ap_est} ${estudiante.am_est}, ${estudiante.nom_est}`;
    
    // Configurar avatar
    const detalleAvatar = document.getElementById('detalleAvatar');
    detalleAvatar.textContent = `${estudiante.nom_est.charAt(0)}${estudiante.ap_est.charAt(0)}`;
    detalleAvatar.className = `h-20 w-20 rounded-full flex items-center justify-center text-white font-bold text-2xl mr-0 md:mr-6 mb-4 md:mb-0 shadow-lg ${estudiante.sex_est == 'F' ? 'avatar-estudiante-femenino' : 'avatar-estudiante-masculino'}`;
    
    // Información principal (se mantiene igual)
    document.getElementById('detalleNombre').textContent = `${estudiante.ap_est} ${estudiante.am_est}, ${estudiante.nom_est}`;
    document.getElementById('detallePrograma').textContent = estudiante.nom_progest || 'No asignado';
    document.getElementById('detalleProgramaNombre').textContent = estudiante.nom_progest || 'No asignado';
    document.getElementById('detalleDni').textContent = estudiante.dni_est || 'No especificado';
    document.getElementById('detalleNacimiento').textContent = formatearFecha(estudiante.fecnac_est);
    document.getElementById('detalleCelular').textContent = estudiante.cel_est || 'No especificado';
    document.getElementById('detalleEmailPersonal').textContent = estudiante.mailp_est || 'No especificado';
    document.getElementById('detalleDireccion').textContent = estudiante.dir_est || 'No especificado';
    document.getElementById('detallePeriodo').textContent = estudiante.per_acad || 'No especificado';
    document.getElementById('detalleTurno').textContent = estudiante.turno || 'No especificado';
    document.getElementById('detalleMatricula').textContent = estudiante.id_matricula || 'No especificado';
    
    // Información de ubicación
    document.getElementById('detalleLugarNacimiento').textContent = estudiante.ubigeonac_est || 'No especificado';
    document.getElementById('detalleLugarActual').textContent = estudiante.ubigeodir_est || 'No especificado';
    
    // Estado
    const estadoElement = document.getElementById('detalleEstado');
    if (estudiante.estado === 1) {
        estadoElement.textContent = 'Activo';
        estadoElement.className = 'bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full';
    } else {
        estadoElement.textContent = 'Inactivo';
        estadoElement.className = 'bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full';
    }
    
    // 🔥 CORRECCIÓN COMPLETA: Información de TODAS las prácticas
    const practicasInfo = document.getElementById('detallePracticasInfo');
    let practicasHTML = '';
    
    console.log('📊 Datos de prácticas recibidos:', {
        estado: estudiante.estado_practica,
        modulo: estudiante.modulo_practica,
        empresa: estudiante.empresa_nombre,  // 🔥 AHORA con nombre
        todas_practicas: estudiante.todas_practicas
    });
    
    // Tiene prácticas registradas
    if (estudiante.todas_practicas && estudiante.todas_practicas.length > 0) {
        const totalPracticas = estudiante.todas_practicas.length;
        const practicasEnCurso = estudiante.todas_practicas.filter(p => p.estado === 'En curso').length;
        const practicasFinalizadas = estudiante.todas_practicas.filter(p => p.estado === 'Finalizado').length;
        
        practicasHTML = `
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-briefcase mr-2 text-blue-500"></i>
                        Historial de Prácticas
                    </h5>
                    <div class="flex space-x-2">
                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                            <i class="fas fa-layer-group mr-1"></i>${totalPracticas} total
                        </span>
                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                            <i class="fas fa-check mr-1"></i>${practicasFinalizadas} finalizadas
                        </span>
                        <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">
                            <i class="fas fa-spinner mr-1"></i>${practicasEnCurso} en curso
                        </span>
                    </div>
                </div>
                
                <!-- ÚLTIMA PRÁCTICA (DESTACADA) -->
                ${renderizarUltimaPractica(estudiante)}
                
                <!-- LISTA DE TODAS LAS PRÁCTICAS -->
                <div class="mt-6">
                    <h6 class="font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-history mr-2 text-gray-500"></i>
                        Todas las prácticas realizadas
                    </h6>
                    <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                        ${renderizarListaPracticas(estudiante.todas_practicas)}
                    </div>
                </div>
            </div>
        `;
    } else {
        // No tiene prácticas registradas
        practicasHTML = `
            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                <i class="fas fa-briefcase text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-600 font-medium mb-1">El estudiante no tiene prácticas registradas</p>
                <p class="text-sm text-gray-500 mb-4">No se encontraron registros de prácticas en el sistema</p>
            </div>
        `;
    }
    
    practicasInfo.innerHTML = practicasHTML;
    
    // Configurar el botón de editar
    const editarBtn = document.getElementById('editarDesdeDetalle');
    if (editarBtn) {
        editarBtn.onclick = function() {
            cerrarDetalleModalEstudiante();
            abrirModalEditar(estudiante.id);
        };
    }
    
    document.getElementById('detalleEstudianteModal').classList.remove('hidden');
}

// 🔥 NUEVA FUNCIÓN: Renderizar última práctica destacada
function renderizarUltimaPractica(estudiante) {
    const estado = estudiante.estado_practica || 'Sin estado';
    const modulo = estudiante.modulo_practica || 'No especificado';
    const empresa = estudiante.empresa_nombre || 'No asignada';
    const fechaInicio = estudiante.fecha_inicio_practica ? formatearFecha(estudiante.fecha_inicio_practica) : 'No especificada';
    const fechaFin = estudiante.fecha_fin_practica ? formatearFecha(estudiante.fecha_fin_practica) : 'En curso';
    const horas = estudiante.horas_practica || 0;
    
    // Estilos según estado
    let estadoClass = 'bg-gray-100 text-gray-800';
    let estadoIcon = 'fa-briefcase';
    
    if (estado === 'En curso') {
        estadoClass = 'bg-blue-100 text-blue-800';
        estadoIcon = 'fa-spinner fa-pulse';
    } else if (estado === 'Finalizado') {
        estadoClass = 'bg-green-100 text-green-800';
        estadoIcon = 'fa-check-circle';
    } else if (estado === 'Pendiente') {
        estadoClass = 'bg-yellow-100 text-yellow-800';
        estadoIcon = 'fa-clock';
    }
    
    return `
        <div class="bg-gradient-to-r from-blue-50 to-white p-5 rounded-xl border border-blue-100 mb-4 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h6 class="font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        Última Práctica Registrada
                    </h6>
                    <p class="text-sm text-gray-600">Información de la práctica más reciente</p>
                </div>
                <span class="text-sm px-3 py-1 rounded-full ${estadoClass} font-medium">
                    <i class="fas ${estadoIcon} mr-1"></i>${estado}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <div class="info-item">
                        <i class="fas fa-book text-blue-500 info-icon"></i>
                        <div>
                            <p class="text-xs text-gray-500">Módulo</p>
                            <p class="text-sm font-medium">${modulo}</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-building text-blue-500 info-icon"></i>
                        <div>
                            <p class="text-xs text-gray-500">Empresa</p>
                            <p class="text-sm font-medium">${empresa}</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="info-item">
                        <i class="fas fa-calendar-alt text-blue-500 info-icon"></i>
                        <div>
                            <p class="text-xs text-gray-500">Período</p>
                            <p class="text-sm font-medium">${fechaInicio} - ${fechaFin}</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock text-blue-500 info-icon"></i>
                        <div>
                            <p class="text-xs text-gray-500">Horas</p>
                            <p class="text-sm font-medium">${horas} horas programadas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// 🔥 NUEVA FUNCIÓN: Renderizar lista de todas las prácticas
function renderizarListaPracticas(practicas) {
    if (!practicas || practicas.length === 0) {
        return '<p class="text-gray-500 text-center py-4">No hay prácticas para mostrar</p>';
    }
    
    let html = '';
    
    practicas.forEach((practica, index) => {
        // Determinar color según estado
        let estadoColor = '';
        let estadoIcon = '';
        
        switch(practica.estado) {
            case 'En curso':
                estadoColor = 'text-blue-600 bg-blue-50 border-blue-200';
                estadoIcon = 'fa-spinner fa-pulse';
                break;
            case 'Finalizado':
                estadoColor = 'text-green-600 bg-green-50 border-green-200';
                estadoIcon = 'fa-check-circle';
                break;
            case 'Pendiente':
                estadoColor = 'text-yellow-600 bg-yellow-50 border-yellow-200';
                estadoIcon = 'fa-clock';
                break;
            default:
                estadoColor = 'text-gray-600 bg-gray-50 border-gray-200';
                estadoIcon = 'fa-question-circle';
        }
        
        // Formatear fechas
        const fechaInicio = practica.fecha_inicio ? formatearFechaCorta(practica.fecha_inicio) : 'N/A';
        const fechaFin = practica.fecha_fin ? formatearFechaCorta(practica.fecha_fin) : 'En curso';
        
        html += `
            <div class="flex items-center justify-between p-3 bg-white border ${estadoColor} rounded-lg hover:shadow transition-shadow">
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <span class="text-xs font-medium px-2 py-1 rounded-full ${estadoColor} mr-2">
                            <i class="fas ${estadoIcon} mr-1"></i>${practica.estado || 'Sin estado'}
                        </span>
                        <span class="text-xs text-gray-500">${fechaInicio} → ${fechaFin}</span>
                    </div>
                    <p class="font-medium text-sm">${practica.modulo || 'Sin módulo'}</p>
                    <p class="text-xs text-gray-600">
                        <i class="fas fa-building mr-1"></i>
                        ${practica.empresa_nombre || 'Sin empresa'} 
                        • ${practica.total_horas || 0} horas
                    </p>
                </div>
            </div>
        `;
    });
    
    return html;
}

// 🔥 NUEVA FUNCIÓN: Formatear fecha corta
function formatearFechaCorta(fecha) {
    if (!fecha || fecha === '0000-00-00') return 'N/A';
    
    try {
        const fechaObj = new Date(fecha);
        if (isNaN(fechaObj.getTime())) return 'Fecha inválida';
        
        const dia = fechaObj.getDate().toString().padStart(2, '0');
        const mes = (fechaObj.getMonth() + 1).toString().padStart(2, '0');
        const año = fechaObj.getFullYear();
        
        return `${dia}/${mes}/${año}`;
    } catch (error) {
        console.error('Error formateando fecha corta:', error);
        return 'Error';
    }
}

// 🔥 FUNCIÓN AUXILIAR: Para abrir modal de nueva práctica
function abrirModalNuevaPractica(estudianteId) {
    console.log('Abrir modal para nueva práctica del estudiante:', estudianteId);
    // Aquí puedes redirigir a la página de creación de prácticas
    // o abrir un modal específico
    window.open(`index.php?c=Practica&a=crear&estudiante_id=${estudianteId}`, '_blank');
}

// 🔥 FUNCIÓN AUXILIAR: Para ver detalle de una práctica específica
function verDetallePractica(practicaId) {
    console.log('Ver detalle de práctica ID:', practicaId);
    // Redirigir a la página de detalles de práctica
    window.open(`index.php?c=Practica&a=detalle&id=${practicaId}`, '_blank');
}

function cerrarDetalleModalEstudiante() {
  document.getElementById("detalleEstudianteModal").classList.add("hidden");
}

// ==============================
// VALIDACIÓN DE DNI EN TIEMPO REAL
// ==============================

// Función para verificar si el DNI existe
async function verificarDNIExistente(dni) {
  if (!dni || dni.length !== 8) return false;

  try {
    const response = await fetch(
      `index.php?c=Estudiante&a=verificarDNI&dni=${dni}`
    );
    const result = await response.json();
    return result.existe;
  } catch (error) {
    console.error("Error al verificar DNI:", error);
    return false;
  }
}

// Función para mostrar advertencia de DNI existente
function mostrarAdvertenciaDNI(mensaje) {
  let advertencia = document.getElementById("advertenciaDNI");

  if (!advertencia) {
    advertencia = document.createElement("div");
    advertencia.id = "advertenciaDNI";
    advertencia.className =
      "mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start";

    const inputDNI = document.getElementById("dni_est");
    inputDNI.parentNode.appendChild(advertencia);
  }

  advertencia.innerHTML = `
        <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-3"></i>
        <div class="flex-1">
            <p class="text-sm font-medium text-yellow-800">¡Advertencia!</p>
            <p class="text-sm text-yellow-700">${mensaje}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-yellow-500 hover:text-yellow-700">
            <i class="fas fa-times"></i>
        </button>
    `;
}

// Función para ocultar advertencia de DNI
function ocultarAdvertenciaDNI() {
  const advertencia = document.getElementById("advertenciaDNI");
  if (advertencia) {
    advertencia.remove();
  }
}

// Función para validar formato de DNI
function validarFormatoDNI(dni) {
  return /^\d{8}$/.test(dni);
}

// Función para configurar validación de DNI (solo para NUEVOS estudiantes)
function configurarValidacionDNI() {
  const inputDNI = document.getElementById("dni_est");
  let timeout = null;

  inputDNI.addEventListener("input", function () {
    const dni = this.value.trim();

    if (timeout) {
      clearTimeout(timeout);
    }

    ocultarAdvertenciaDNI();

    if (dni.length === 8) {
      if (!validarFormatoDNI(dni)) {
        mostrarAdvertenciaDNI("El DNI debe contener solo 8 dígitos numéricos.");
        return;
      }

      // 🔥 SOLO en CREACIÓN: Verificar si el DNI existe
      timeout = setTimeout(async () => {
        const existe = await verificarDNIExistente(dni);
        if (existe) {
          mostrarAdvertenciaDNI(
            "Este DNI ya está registrado en el sistema. No podrás guardar el estudiante."
          );
        }
      }, 500);
    }
  });
}

// ==============================
// MANEJO DE UBIGEO
// ==============================

// 🔥 FUNCIÓN MEJORADA PARA CARGAR PROVINCIAS CON SELECCIÓN
async function cargarProvincias(
  departamentoId,
  tipo,
  provinciaTarget = null,
  distritoTarget = null
) {
  if (!departamentoId) return;

  try {
    const response = await fetch(
      `index.php?c=Estudiante&a=obtenerProvincias&departamento_id=${departamentoId}`
    );
    const result = await response.json();

    if (result.success) {
      const selectProvincia = document.getElementById(`provincia_${tipo}`);
      const selectDistrito = document.getElementById(`distrito_${tipo}`);

      // Limpiar y habilitar provincia
      selectProvincia.innerHTML = '<option value="">Provincia</option>';
      selectProvincia.disabled = false;

      // Limpiar y deshabilitar distrito
      selectDistrito.innerHTML = '<option value="">Distrito</option>';
      selectDistrito.disabled = true;

      // Llenar provincias
      result.data.forEach((provincia) => {
        const option = document.createElement("option");
        option.value = provincia.id;
        option.textContent = provincia.provincia;
        selectProvincia.appendChild(option);
      });

      // 🔥 SELECCIONAR PROVINCIA SI SE ESPECIFICA
      if (provinciaTarget) {
        setTimeout(() => {
          seleccionarProvincia(tipo, provinciaTarget, distritoTarget);
        }, 200);
      }
    }
  } catch (error) {
    console.error("Error al cargar provincias:", error);
  }
}

// 🔥 FUNCIÓN PARA SELECCIONAR PROVINCIA
async function seleccionarProvincia(
  tipo,
  provinciaTarget,
  distritoTarget = null
) {
  const selectProvincia = document.getElementById(`provincia_${tipo}`);

  for (let i = 0; i < selectProvincia.options.length; i++) {
    const option = selectProvincia.options[i];
    if (option.text === provinciaTarget) {
      selectProvincia.value = option.value;
      console.log(`✅ Provincia ${tipo} cargada:`, provinciaTarget);

      // 🔥 CARGAR DISTRITOS después de seleccionar provincia
      setTimeout(async () => {
        await cargarDistritos(option.value, tipo, distritoTarget);
      }, 300);

      break;
    }
  }
}

// 🔥 FUNCIÓN MEJORADA PARA CARGAR DISTRITOS CON SELECCIÓN
async function cargarDistritos(provinciaId, tipo, distritoTarget = null) {
  if (!provinciaId) return;

  try {
    const response = await fetch(
      `index.php?c=Estudiante&a=obtenerDistritos&provincia_id=${provinciaId}`
    );
    const result = await response.json();

    if (result.success) {
      const selectDistrito = document.getElementById(`distrito_${tipo}`);

      // Limpiar y habilitar distrito
      selectDistrito.innerHTML = '<option value="">Distrito</option>';
      selectDistrito.disabled = false;

      // Llenar distritos
      result.data.forEach((distrito) => {
        const option = new Option(distrito.distrito, distrito.id);
        selectDistrito.add(option);
      });

      // 🔥 SELECCIONAR DISTRITO SI SE ESPECIFICA
      if (distritoTarget) {
        setTimeout(() => {
          const selectDistrito = document.getElementById(`distrito_${tipo}`);
          for (let i = 0; i < selectDistrito.options.length; i++) {
            const option = selectDistrito.options[i];
            if (option.text === distritoTarget) {
              selectDistrito.value = option.value;
              console.log(`✅ Distrito ${tipo} cargado:`, distritoTarget);

              // Actualizar hidden field
              document.getElementById(`ubigeo${tipo}_est`).value =
                distritoTarget;
              break;
            }
          }
        }, 200);
      }
    }
  } catch (error) {
    console.error("Error al cargar distritos:", error);
  }
}

// 🔥 CORRECCIÓN: Función mejorada para actualizar el ubigeo hidden
function actualizarUbigeoHidden(tipo) {
  const distritoId = document.getElementById(`distrito_${tipo}`).value;
  console.log(`Ubigeo ${tipo} seleccionado:`, distritoId);
}

// Función para cargar ubigeo en edición
async function cargarUbigeoEnEdicion(estudianteId) {
  // Esta función necesitaría obtener los datos del estudiante y cargar los selects
  // Se implementaría cuando cargues los datos para edición
}

// ==============================
// EVENT LISTENERS PRINCIPALES
// ==============================

document.addEventListener("DOMContentLoaded", function () {
  console.log("Inicializando página de estudiantes...");

  console.log("Usuario actual:", window.usuarioActual);

  // 🔥 CORRECCIÓN: Ocultar botones según rol
  const btnNuevoEstudiante = document.getElementById("btnNuevoEstudiante");
  const btnExportar = document.getElementById("btnExportar");

  const usuario = window.usuarioActual || {};

  if (usuario.esDocente) {
    // Para docentes, podemos ocultar o deshabilitar ciertos botones
    if (btnNuevoEstudiante) {
      btnNuevoEstudiante.style.display = "none";
      console.log('✅ Botón "Nuevo Estudiante" ocultado para docente');
    }

    if (btnExportar) {
      btnExportar.style.display = "none";
      console.log('✅ Botón "Exportar CSV" ocultado para docente');
    }

    // También podríamos agregar una clase al body para estilos específicos
    document.body.classList.add("docente-view");
  }

  if (usuario.esEstudiante) {
    // Los estudiantes no deberían estar aquí, pero por si acaso
    mostrarNotificacion(
      "error",
      "Acceso denegado",
      "No tienes permiso para acceder a esta página."
    );
    setTimeout(() => {
      window.location.href = "index.php?c=DashboardEstudiante&a=index";
    }, 2000);
    return;
  }

  // Cargar datos iniciales
  cargarDatosEstudiantes();

  // Botón Nuevo Estudiante
  document
    .getElementById("btnNuevoEstudiante")
    .addEventListener("click", abrirModalNuevo);

  // Event listeners para ubigeo - Nacimiento
  document
    .getElementById("departamento_nac")
    .addEventListener("change", function () {
      const departamentoId = this.value;
      cargarProvincias(departamentoId, "nac");
    });

  document
    .getElementById("provincia_nac")
    .addEventListener("change", function () {
      const provinciaId = this.value;
      cargarDistritos(provinciaId, "nac");
    });

  document
    .getElementById("distrito_nac")
    .addEventListener("change", function () {
      actualizarUbigeoHidden("nac");
    });

  // Event listeners para ubigeo - Dirección
  document
    .getElementById("departamento_dir")
    .addEventListener("change", function () {
      const departamentoId = this.value;
      cargarProvincias(departamentoId, "dir");
    });

  document
    .getElementById("provincia_dir")
    .addEventListener("change", function () {
      const provinciaId = this.value;
      cargarDistritos(provinciaId, "dir");
    });

  document
    .getElementById("distrito_dir")
    .addEventListener("change", function () {
      actualizarUbigeoHidden("dir");
    });

  // Envío del formulario de estudiante - VERSIÓN CON VALIDACIÓN DE DNI
  document
    .getElementById("formEstudiante")
    .addEventListener("submit", async function (e) {
      e.preventDefault();

      const id = document.getElementById("estudianteId").value;
      const dni = document.getElementById("dni_est").value.trim();

      // 🔥 CORRECCIÓN: Solo validar formato básico del DNI
      if (dni.length > 0 && !/^\d{8}$/.test(dni)) {
        mostrarNotificacion(
          "error",
          "Error",
          "El DNI debe tener exactamente 8 dígitos numéricos."
        );
        return;
      }

      // 🔥 CORRECCIÓN: Validar solo campos REQUERIDOS
      const camposRequeridos = ["ap_est", "nom_est", "sex_est"];
      for (const campo of camposRequeridos) {
        const input = document.getElementById(campo);
        if (input && !input.value.trim()) {
          mostrarNotificacion(
            "error",
            "Error",
            `El campo ${
              input.previousElementSibling?.textContent || campo
            } es requerido`
          );
          input.focus();
          return;
        }
      }

      function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
      }

      // 🔥 CORRECCIÓN: Validar formato de email SI SE PROPORCIONA
      const email = document.getElementById("mailp_est").value.trim();
      if (email && !isValidEmail(email)) {
        mostrarNotificacion(
          "error",
          "Error",
          "El email no tiene un formato válido"
        );
        return;
      }

      mostrarCarga(
        id ? "Actualizando estudiante..." : "Guardando estudiante..."
      );

      try {
        const formData = new FormData(this);

        // 🔥 CORRECCIÓN: Obtener ubigeo como texto
        const distritoNac = document.getElementById("distrito_nac");
        const provinciaNac = document.getElementById("provincia_nac");
        const departamentoNac = document.getElementById("departamento_nac");

        const distritoDir = document.getElementById("distrito_dir");
        const provinciaDir = document.getElementById("provincia_dir");
        const departamentoDir = document.getElementById("departamento_dir");

        // Ubigeo de nacimiento
        if (departamentoNac.value && provinciaNac.value && distritoNac.value) {
          const lugarNacimiento = `${
            distritoNac.options[distritoNac.selectedIndex].text
          }, ${provinciaNac.options[provinciaNac.selectedIndex].text}, ${
            departamentoNac.options[departamentoNac.selectedIndex].text
          }`;
          formData.set("ubigeonac_est", lugarNacimiento);
        } else {
          formData.set("ubigeonac_est", "");
        }

        // Ubigeo de dirección
        if (departamentoDir.value && provinciaDir.value && distritoDir.value) {
          const lugarActual = `${
            distritoDir.options[distritoDir.selectedIndex].text
          }, ${provinciaDir.options[provinciaDir.selectedIndex].text}, ${
            departamentoDir.options[departamentoDir.selectedIndex].text
          }`;
          formData.set("ubigeodir_est", lugarActual);
        } else {
          formData.set("ubigeodir_est", "");
        }

        // 🔥 CORRECCIÓN: Asegurar que todos los campos tengan valor
        formData.forEach((value, key) => {
          console.log(`${key}: ${value}`);
        });

        let exito = false;

        if (id) {
          exito = await editarEstudiante(id, formData);
        } else {
          exito = await agregarEstudiante(formData);
        }

        if (exito) {
          cerrarModalEstudiante();
        }
      } catch (error) {
        console.error("Error en envío:", error);
        mostrarNotificacion(
          "error",
          "Error",
          error.message || "Ocurrió un error al guardar."
        );
      } finally {
        ocultarCarga();
      }
    });

  // Botón Refrescar
  document
    .getElementById("btnRefrescar")
    .addEventListener("click", function () {
      cargarDatosEstudiantes();
    });

  // Botón Exportar - VERSIÓN MEJORADA
  document.getElementById("btnExportar").addEventListener("click", function () {
    exportarEstudiantesCSV();
  });

  // 🔥 NUEVA FUNCIÓN: Exportar estudiantes a CSV
  async function exportarEstudiantesCSV() {
    mostrarCarga("Generando archivo CSV...");

    try {
      // Obtener los filtros actuales
      const filtros = {
        busqueda: document.getElementById("buscarEstudiante").value,
        programa: document.getElementById("filtroPrograma").value,
        estado: document.getElementById("filtroEstado").value,
        genero: document.getElementById("filtroGenero").value,
      };

      // Construir URL con parámetros
      const params = new URLSearchParams();
      Object.keys(filtros).forEach((key) => {
        if (filtros[key] && filtros[key] !== "all") {
          params.append(key, filtros[key]);
        }
      });

      const url = `index.php?c=Estudiante&a=exportarCSV&${params.toString()}`;

      // Crear enlace temporal para descarga
      const link = document.createElement("a");
      link.href = url;
      link.style.display = "none";
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      // Esperar un momento para que se complete la descarga
      setTimeout(() => {
        ocultarCarga();
        mostrarNotificacion(
          "success",
          "¡Exportación exitosa!",
          "El archivo CSV se ha descargado correctamente"
        );
      }, 2000);
    } catch (error) {
      console.error("Error al exportar:", error);
      ocultarCarga();
      mostrarNotificacion(
        "error",
        "Error en exportación",
        "No se pudo generar el archivo CSV"
      );
    }
  }

  // 🔥 FUNCIÓN AUXILIAR: Mostrar progreso de exportación
  function mostrarProgresoExportacion(progreso) {
    let progresoElement = document.getElementById("progresoExportacion");

    if (!progresoElement) {
      progresoElement = document.createElement("div");
      progresoElement.id = "progresoExportacion";
      progresoElement.className =
        "fixed bottom-4 right-4 bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-50";
      progresoElement.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Exportando datos</p>
                    <p class="text-xs text-gray-500">${progreso}% completado</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: ${progreso}%"></div>
            </div>
        `;
      document.body.appendChild(progresoElement);
    } else {
      const barra = progresoElement.querySelector(".bg-blue-600");
      const texto = progresoElement.querySelector(".text-xs");
      barra.style.width = `${progreso}%`;
      texto.textContent = `${progreso}% completado`;
    }
  }

  function ocultarProgresoExportacion() {
    const progresoElement = document.getElementById("progresoExportacion");
    if (progresoElement) {
      progresoElement.remove();
    }
  }

  // Event listeners para cerrar modales
  document
    .getElementById("cerrarModal")
    .addEventListener("click", cerrarModalEstudiante);
  document
    .getElementById("cancelarForm")
    .addEventListener("click", cerrarModalEstudiante);
  document
    .getElementById("cerrarDetalleModal")
    .addEventListener("click", cerrarDetalleModalEstudiante);
  document
    .getElementById("cerrarDetalleBtn")
    .addEventListener("click", cerrarDetalleModalEstudiante);

  // Cerrar modal al hacer clic fuera
  document
    .getElementById("estudianteModal")
    .addEventListener("click", function (e) {
      if (e.target === this) {
        cerrarModalEstudiante();
      }
    });

  document
    .getElementById("detalleEstudianteModal")
    .addEventListener("click", function (e) {
      if (e.target === this) {
        cerrarDetalleModalEstudiante();
      }
    });

  // Event listeners para filtros
  document
    .getElementById("filtroPrograma")
    .addEventListener("change", aplicarFiltrosYRenderizar);
  document
    .getElementById("filtroEstado")
    .addEventListener("change", aplicarFiltrosYRenderizar);
  document
    .getElementById("filtroGenero")
    .addEventListener("change", aplicarFiltrosYRenderizar);

  // Event listener para búsqueda
  document
    .getElementById("buscarEstudiante")
    .addEventListener("input", function () {
      aplicarFiltrosYRenderizar();
    });
});

// Hacer funciones disponibles globalmente para debugging
window.abrirModalNuevo = abrirModalNuevo;
window.cerrarModalEstudiante = cerrarModalEstudiante;
window.cargarDatosEstudiantes = cargarDatosEstudiantes;
