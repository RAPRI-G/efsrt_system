-- Tabla de estudiantes
CREATE TABLE `estudiante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ubdistrito` int(11) DEFAULT NULL,
  `dni_est` char(8) DEFAULT NULL,
  `ap_est` varchar(40) DEFAULT NULL,
  `am_est` varchar(40) DEFAULT NULL,
  `nom_est` varchar(40) DEFAULT NULL,
  `sex_est` char(1) DEFAULT NULL,
  `cel_est` char(9) DEFAULT NULL,
  `ubigeodir_est` char(6) DEFAULT NULL,
  `ubigeonac_est` char(6) DEFAULT NULL,
  `dir_est` varchar(40) DEFAULT NULL,
  `mailp_est` varchar(40) DEFAULT NULL,
  `maili_est` varchar(40) DEFAULT NULL,
  `fecnac_est` date DEFAULT NULL,
  `foto_est` varchar(40) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ubdistrito` (`ubdistrito`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla de programas de estudio
CREATE TABLE `prog_estudios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_progest` varchar(40) DEFAULT NULL,
  `perfilingre_progest` text DEFAULT NULL,
  `perfilegre_progest` text DEFAULT NULL,
  `id_progest` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla de matrícula
CREATE TABLE `matricula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante` int(11) DEFAULT NULL,
  `prog_estudios` int(11) DEFAULT NULL,
  `id_matricula` char(9) DEFAULT NULL,
  `per_lectivo` varchar(7) DEFAULT NULL,
  `per_acad` varchar(3) DEFAULT NULL,
  `per_acad2` int(1) DEFAULT NULL,
  `seccion` char(1) DEFAULT NULL,
  `turno` char(1) DEFAULT NULL,
  `fec_matricula` date DEFAULT NULL,
  `cond_matricula` char(1) DEFAULT NULL,
  `est_matricula` char(1) DEFAULT NULL,
  `est_perlec` char(1) DEFAULT NULL,
  `obs_matricula` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estudiante` (`estudiante`),
  KEY `prog_estudios` (`prog_estudios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla de empleados (docentes)
CREATE TABLE `empleado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prog_estudios` int(11) DEFAULT NULL,
  `dni_emp` char(8) DEFAULT NULL,
  `apnom_emp` varchar(60) DEFAULT NULL,
  `sex_emp` char(1) DEFAULT NULL,
  `cel_emp` char(9) DEFAULT NULL,
  `ubigeodir_emp` char(6) DEFAULT NULL,
  `ubigeonac_emp` char(6) DEFAULT NULL,
  `dir_emp` varchar(40) DEFAULT NULL,
  `mailp_emp` varchar(40) DEFAULT NULL,
  `maili_emp` varchar(40) DEFAULT NULL,
  `fecnac_emp` date DEFAULT NULL,
  `cargo_emp` char(1) DEFAULT NULL,
  `cond_emp` char(1) DEFAULT NULL,
  `id_progest` char(3) DEFAULT NULL,
  `fecinc_emp` date DEFAULT NULL,
  `foto_emp` varchar(40) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prog_estudios` (`prog_estudios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla de empresas
CREATE TABLE `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ruc` varchar(11) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `nombre_comercial` varchar(255) DEFAULT NULL,
  `direccion_fiscal` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sector` varchar(100) DEFAULT NULL,
  `validado` int(11) DEFAULT NULL,
  `registro_manual` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `condicion_sunat` varchar(20) DEFAULT NULL,
  `ubigeo` varchar(10) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `distrito` varchar(100) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla principal de prácticas/EFSRT
CREATE TABLE `practicas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante` int(11) NOT NULL,
  `empleado` int(11) DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `modulo` varchar(100) DEFAULT NULL,
  `periodo_academico` varchar(50) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `total_horas` int(11) DEFAULT 0,
  `estado` enum('En curso','Finalizado','Pendiente') DEFAULT 'Pendiente',
  PRIMARY KEY (`id`),
  KEY `estudiante` (`estudiante`),
  KEY `empleado` (`empleado`),
  KEY `empresa` (`empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla de asistencias
CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practicas` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `horas_acumuladas` int(11) DEFAULT NULL,
  `actividad` text DEFAULT NULL,
  `visto_bueno_empresa` varchar(150) DEFAULT NULL,
  `visto_bueno_docente` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `practicas` (`practicas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla de evaluaciones
CREATE TABLE `evaluaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practicas` int(11) NOT NULL,
  `puntaje_total` int(11) DEFAULT NULL,
  `escala` char(1) DEFAULT NULL,
  `apreciacion` enum('Muy Buena','Buena','Aceptable','Deficiente') DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `practicas` (`practicas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla de documentos EFSRT
CREATE TABLE `efsrt_documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practica_id` int(11) NOT NULL,
  `tipo_documento` enum('oficio_multiple','carta_presentacion','ficha_identidad') NOT NULL,
  `contenido` text NOT NULL,
  `fecha_generacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `generado_por` int(11) DEFAULT NULL,
  `estado` enum('generado','firmado','enviado') DEFAULT 'generado',
  PRIMARY KEY (`id`),
  KEY `practica_id` (`practica_id`),
  KEY `generado_por` (`generado_por`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla de configuración EFSRT
CREATE TABLE `efsrt_configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `horas_por_modulo` int(11) DEFAULT 128,
  `dias_habiles_semana` int(11) DEFAULT 5,
  `horas_diarias` int(11) DEFAULT 4,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Tabla de usuarios
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(200) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `estuempleado` int(11) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `ubdepartamento` (
  `id` int(11) NOT NULL,
  `departamento` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ubdistrito` (
  `id` int(11) NOT NULL,
  `distrito` varchar(250) DEFAULT NULL,
  `ubprovincia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ubprovincia` (
  `id` int(11) NOT NULL,
  `provincia` varchar(250) DEFAULT NULL,
  `ubdepartamento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =============================================
-- INSERCIÓN DE DATOS DE PRUEBA
-- =============================================

-- Datos para tabla EMPRESA
INSERT INTO `empresa` (`ruc`, `razon_social`, `nombre_comercial`, `direccion_fiscal`, `telefono`, `email`, `sector`, `validado`, `registro_manual`, `estado`, `condicion_sunat`, `ubigeo`, `departamento`, `provincia`, `distrito`, `fecha_creacion`, `fecha_actualizacion`) VALUES
('20568733259', 'MYO LIBRA CONTRATISTAS EIRL', 'LIBRA CONTRATISTAS', 'AV. TAHUANTINSUYO N° 821 - EL TAMBO - HUANCAYO', '956260077', 'extraccionescachito@hotmail.com', 'CONSTRUCCIÓN', 1, 0, 'ACTIVO', 'HABIDO', '120125', 'JUNÍN', 'HUANCAYO', 'EL TAMBO', '2024-01-15 10:00:00', '2024-01-15 10:00:00'),
('20100123456', 'TECNOLOGÍA AVANZADA SAC', 'TECNOAVANZADA', 'AV. REAL 123 - HUANCAYO', '964123456', 'info@tecnoavanzada.com', 'TECNOLOGÍA', 1, 0, 'ACTIVO', 'HABIDO', '120101', 'JUNÍN', 'HUANCAYO', 'HUANCAYO', '2024-01-16 09:30:00', '2024-01-16 09:30:00'),
('20100234567', 'SOLUCIONES WEB EIRL', 'SOLWEB', 'JR. LIMA 456 - EL TAMBO', '965234567', 'contacto@solweb.com', 'DESARROLLO SOFTWARE', 1, 0, 'ACTIVO', 'HABIDO', '120125', 'JUNÍN', 'HUANCAYO', 'EL TAMBO', '2024-01-17 14:20:00', '2024-01-17 14:20:00'),
('20100345678', 'INNOVACIÓN DIGITAL SAC', 'INNODIGITAL', 'AV. HUÁNCANO 789 - HUANCAYO', '966345678', 'innovacion@innodigital.com', 'TECNOLOGÍA', 1, 0, 'ACTIVO', 'HABIDO', '120101', 'JUNÍN', 'HUANCAYO', 'HUANCAYO', '2024-01-18 11:15:00', '2024-01-18 11:15:00'),
('20100456789', 'SISTEMAS INTEGRALES EIRL', 'SISINTEGRAL', 'CALLE LOS ANDES 321 - CHILCA', '967456789', 'sistemas@sisintegral.com', 'INFORMÁTICA', 1, 0, 'ACTIVO', 'HABIDO', '120112', 'JUNÍN', 'HUANCAYO', 'CHILCA', '2024-01-19 16:45:00', '2024-01-19 16:45:00');

-- Datos para tabla PRACTICAS
INSERT INTO `practicas` (`estudiante`, `empleado`, `empresa`, `modulo`, `periodo_academico`, `fecha_inicio`, `fecha_fin`, `total_horas`, `estado`) VALUES
(1, 3, 1, 'INTEGRACIÓN DE APLICACIONES WEB Y MÓVILES', 'VI', '2024-09-26', '2024-12-13', 64, 'En curso'),
(2, 3, 2, 'DESARROLLO DE APLICACIONES WEB', 'VI', '2024-09-25', '2024-12-12', 72, 'En curso'),
(3, 3, 3, 'DISEÑO DE INTERFACES DE USUARIO', 'VI', '2024-09-24', '2024-12-11', 68, 'En curso'),
(4, 3, 4, 'PROGRAMACIÓN WEB AVANZADA', 'VI', '2024-09-23', '2024-12-10', 60, 'En curso'),
(5, 3, 5, 'BASE DE DATOS Y BACKEND', 'VI', '2024-09-22', '2024-12-09', 76, 'En curso');

-- Datos para tabla ASISTENCIAS
INSERT INTO `asistencias` (`practicas`, `fecha`, `hora_entrada`, `hora_salida`, `horas_acumuladas`, `actividad`, `visto_bueno_empresa`, `visto_bueno_docente`) VALUES
(1, '2024-09-26', '08:00:00', '12:00:00', 4, 'Familiarizarse con el entorno de trabajo', 'María Jesús Meza', 'Ing. Rolando Lazo'),
(1, '2024-09-30', '08:30:00', '12:30:00', 4, 'Conocer el trabajo de la empresa y todos sus rubros', 'María Jesús Meza', 'Ing. Rolando Lazo'),
(1, '2024-10-02', '08:00:00', '12:00:00', 4, 'Recopilación de requerimientos para la web y app', 'María Jesús Meza', 'Ing. Rolando Lazo'),
(1, '2024-10-03', '08:30:00', '12:30:00', 4, 'Estudiar las páginas y aplicaciones de la competencia', 'María Jesús Meza', 'Ing. Rolando Lazo'),
(1, '2024-10-07', '08:00:00', '12:15:00', 4, 'Diseñar los prototipos del sitio web y app', 'María Jesús Meza', 'Ing. Rolando Lazo');

-- Datos para tabla EVALUACIONES
INSERT INTO `evaluaciones` (`practicas`, `puntaje_total`, `escala`, `apreciacion`, `observaciones`) VALUES
(1, 85, 'B', 'Buena', 'Estudiante muestra buen desempeño en el desarrollo web, con capacidad técnica destacada.'),
(2, 92, 'A', 'Muy Buena', 'Excelentes habilidades de programación y trabajo en equipo.'),
(3, 78, 'B', 'Buena', 'Buen diseño de interfaces, necesita mejorar en aspectos técnicos avanzados.'),
(4, 88, 'B', 'Buena', 'Dominio de tecnologías backend, buen manejo de base de datos.'),
(5, 95, 'A', 'Muy Buena', 'Destacado en todas las áreas, muy proactivo y con excelentes soluciones.');

-- Datos para tabla EVIDENCIAS
INSERT INTO `evidencias` (`practicas`, `descripcion`, `archivo_url`) VALUES
(1, 'Prototipo de aplicación web desarrollada', 'evidencias/prototipo_app_web.pdf'),
(1, 'Código fuente del proyecto', 'evidencias/codigo_fuente.zip'),
(1, 'Documentación técnica del sistema', 'evidencias/documentacion_tecnica.docx'),
(2, 'Interfaz de usuario diseñada', 'evidencias/design_ui_ux.png'),
(3, 'Base de datos implementada', 'evidencias/estructura_bd.sql');

-- Datos para tabla EFSRT_DOCUMENTOS
INSERT INTO `efsrt_documentos` (`practica_id`, `tipo_documento`, `contenido`, `generado_por`, `estado`) VALUES
(1, 'oficio_multiple', 'Oficio Múltiple N° 2025-IESTP"AACD"/DG solicitando EFSRT para empresa LIBRA CONTRATISTAS EIRL', 3, 'generado'),
(1, 'carta_presentacion', 'Carta de presentación del estudiante CONDORI HUINCHO, Jheferson para prácticas en LIBRA CONTRATISTAS EIRL', 3, 'firmado'),
(1, 'ficha_identidad', 'Ficha de identidad del estudiante con datos personales y académicos completos', 3, 'enviado'),
(2, 'oficio_multiple', 'Oficio Múltiple para empresa TECNOLOGÍA AVANZADA SAC', 3, 'generado'),
(3, 'carta_presentacion', 'Carta de presentación para SOLUCIONES WEB EIRL', 3, 'firmado');

-- Datos para tabla USUARIOS
INSERT INTO `usuarios` (`usuario`, `password`, `tipo`, `estuempleado`, `token`, `estado`, `nivel`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NULL, 1, 1);

-- Configuración EFSRT
INSERT INTO `efsrt_configuracion` (`horas_por_modulo`, `dias_habiles_semana`, `horas_diarias`) 
VALUES (128, 5, 4);