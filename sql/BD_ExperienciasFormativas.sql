CREATE TABLE `estudiante` (
  `id` int(11) NOT NULL,
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
  `estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id`, `ubdistrito`, `dni_est`, `ap_est`, `am_est`, `nom_est`, `sex_est`, `cel_est`, `ubigeodir_est`, `ubigeonac_est`, `dir_est`, `mailp_est`, `maili_est`, `fecnac_est`, `foto_est`, `estado`) VALUES
(1, NULL, '09986759', 'VILCHEZ', 'ASTUPI?AN', 'ADOLFO', 'M', '994122181', '', '', '', '', '09986759@institutocajas.edu.pe', '0000-00-00', '', 1),
(2, NULL, '10753076', 'SERRANO', 'ECHEVARRIA', 'CHRISTIAN EDUARDO', 'M', '982989635', '', '', '', '', '10753076@institutocajas.edu.pe', '0000-00-00', '', 1),
(3, NULL, '19868839', 'CAPACYACHI', 'OROYA', 'JAVIER ALFONSO', 'M', '954541040', '', '', '', '', '19868839@institutocajas.edu.pe', '0000-00-00', '', 1),
(4, NULL, '20053901', 'ORE', 'ROJAS', 'JORGE LUIS', 'M', '996559992', '', '', '', '', '20053901@institutocajas.edu.pe', '0000-00-00', '', 1),
(5, NULL, '20066638', 'ZARATE', 'AGUILAR', 'MIGUEL ANGEL', 'M', '931744607', '', '', '', '', '20066638@institutocajas.edu.pe', '0000-00-00', '', 1),
(6, NULL, '20080751', 'CASTRO', 'PAYTAN', 'ALEXIS JOHANN', 'M', '964476156', '', '', '', '', '20080751@institutocajas.edu.pe', '0000-00-00', '', NULL),
(7, NULL, '20443029', 'MEZA', 'CARHUANCHO', 'JOSE DANIEL', 'M', '955831935', '', '', '', '', '20443029@institutocajas.edu.pe', '0000-00-00', '', NULL),
(8, NULL, '40831887', 'ALANOCA', 'ROJAS', 'JOSE LUIS', 'M', '989772866', '', '', '', '', '40831887@institutocajas.edu.pe', '0000-00-00', '', NULL),
(9, NULL, '40997599', 'CUYUTUPAC', 'MUSUCANCHA', 'FRANKLIN ELVIS', 'M', '964300415', '', '', '', '', '40997599@institutocajas.edu.pe', '0000-00-00', '', NULL),
(10, NULL, '41394400', 'CAMAYO', 'ADRIANO', 'JOSE ALBERTO', 'M', '914162943', '', '', '', '', '41394400@institutocajas.edu.pe', '0000-00-00', '', NULL);

CREATE TABLE `prog_estudios` (
  `id` int(11) NOT NULL,
  `nom_progest` varchar(40) DEFAULT NULL,
  `perfilingre_progest` text DEFAULT NULL,
  `perfilegre_progest` text DEFAULT NULL,
  `id_progest` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `prog_estudios`
--

INSERT INTO `prog_estudios` (`id`, `nom_progest`, `perfilingre_progest`, `perfilegre_progest`, `id_progest`) VALUES
(1, 'ASISTENCIA ADMINISTRATIVA', NULL, NULL, 'ASA'),
(2, 'DISEÑO Y PROGRAMACIÓN WEB', NULL, NULL, 'DPW'),
(3, 'ELECTRICIDAD INDUSTRIAL', NULL, NULL, 'ELA'),
(4, 'ELECTRÓNICA INDUSTRIAL', NULL, NULL, 'ELO'),
(5, 'EMPLEABILIDAD', NULL, NULL, 'EMP'),
(6, 'MECATRÓNICA AUTOMOTRIZ', NULL, NULL, 'MCA'),
(7, 'METALURGIA', NULL, NULL, 'MET'),
(8, 'MANTENIMIENTO DE MAQUINARIA PESADA', NULL, NULL, 'MMP'),
(9, 'MECÁNICA DE PRODUCCIÓN INDUSTRIAL', NULL, NULL, 'MPI'),
(10, 'TECNOLOGÍA DE ANÁLISIS QUÍMICO', NULL, NULL, 'TAQ');

CREATE TABLE `matricula` (
  `id` int(11) NOT NULL,
  `estudiante` int(11) DEFAULT NULL,
  `prog_estudios` int(11) DEFAULT NULL,
  `id_matricula` char(9) DEFAULT NULL,
  `per_lectivo` varchar(7) DEFAULT NULL,
  `per_acad` varchar(3) DEFAULT NULL,
  `per_acad2` int(1) DEFAULT NULL,
  `seccion` char(1) DEFAULT NULL,
  `turno` VARCHAR(20) DEFAULT NULL,
  `fec_matricula` date DEFAULT NULL,
  `cond_matricula` char(1) DEFAULT NULL,
  `est_matricula` char(1) DEFAULT NULL,
  `est_perlec` char(1) DEFAULT NULL,
  `obs_matricula` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `matricula`
--

INSERT INTO `matricula` (`id`, `estudiante`, `prog_estudios`, `id_matricula`, `per_lectivo`, `per_acad`, `per_acad2`, `seccion`, `turno`, `fec_matricula`, `cond_matricula`, `est_matricula`, `est_perlec`, `obs_matricula`) VALUES
(1, 976, 1, '202520001', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(2, 810, 1, '202520002', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(3, 1092, 1, '202520003', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(4, 358, 1, '202520004', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(5, 186, 1, '202520005', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(6, 827, 1, '202520006', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(7, 799, 1, '202520007', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(8, 323, 1, '202520008', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(9, 8, 1, '202520009', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(10, 1077, 1, '202520010', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', '');

CREATE TABLE `empleado` (
  `id` int(11) NOT NULL,
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
  `estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `prog_estudios`, `dni_emp`, `apnom_emp`, `sex_emp`, `cel_emp`, `ubigeodir_emp`, `ubigeonac_emp`, `dir_emp`, `mailp_emp`, `maili_emp`, `fecnac_emp`, `cargo_emp`, `cond_emp`, `id_progest`, `fecinc_emp`, `foto_emp`, `estado`) VALUES
(1, NULL, '04001427', 'COLQUI BARRERA JAVIER WILLY', 'M', '964889458', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MET', '0000-00-00', '\r', NULL),
(2, NULL, '06588533', 'CAMPEAN TORPOCO ANGEL YTALO', 'M', '964308384', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELO', '0000-00-00', '\r', NULL),
(3, NULL, '10784186', 'LIMAS LUNA DAFNI GEYDI', 'F', '964942460', '', '', '', '', '', '0000-00-00', 'D', 'C', 'DPW', '0000-00-00', '\r', NULL),
(4, NULL, '15396418', 'DE LA CRUZ CASTILLON EVARISTO CALIXTO', 'M', '943637244', '', '', '', '', '', '0000-00-00', 'D', 'N', 'TAQ', '0000-00-00', '\r', NULL),
(5, NULL, '18001312', 'SALGADO MARIN ALEX ENRIQUE', 'M', '942402643', '', '', '', '', '', '0000-00-00', 'D', 'N', 'TAQ', '0000-00-00', '\r', NULL),
(6, NULL, '19811657', 'RIVEROS CHAHUAYO CARLOS ELMER', 'M', '993801669', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MET', '0000-00-00', '\r', NULL),
(7, NULL, '19818411', 'ZARATE CASTA?EDA JORGE', 'M', '955607797', '', '', '', '', '', '0000-00-00', 'A', 'N', 'TEC', '0000-00-00', '\r', NULL),
(8, NULL, '19822189', 'ACU?A OSPINAL ENRIQUE', 'M', '933316609', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELA', '0000-00-00', '\r', NULL),
(9, NULL, '19822285', 'SORIANO VERA JOSE SABINO', 'M', '964910351', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELO', '0000-00-00', '\r', NULL),
(10, NULL, '19830094', 'BALVIN ROJAS OLDARICO', 'M', '990337977', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL);

-- Tabla de empresas COMPLETA con representante_legal
CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `ruc` varchar(11) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `representante_legal` varchar(255) DEFAULT NULL, -- CAMPO NUEVO AGREGADO
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
  `fecha_actualizacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empresa` ACTUALIZADO
--

INSERT INTO `empresa` (`id`, `ruc`, `razon_social`, `representante_legal`, `nombre_comercial`, `direccion_fiscal`, `telefono`, `email`, `sector`, `validado`, `registro_manual`, `estado`, `condicion_sunat`, `ubigeo`, `departamento`, `provincia`, `distrito`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, '20568733259', 'MYO LIBRA CONTRATISTAS EIRL', 'María Jesús Gelinda Meza de Romani', 'LIBRA CONTRATISTAS', 'AV. TAHUANTINSUYO N° 821 - EL TAMBO - HUANCAYO', '956260077', 'extraccionescachito@hotmail.com', 'CONSTRUCCIÓN', 1, 0, 'ACTIVO', 'HABIDO', '120125', 'JUNÍN', 'HUANCAYO', 'EL TAMBO', '2025-11-23 21:52:39', '2025-11-23 21:52:39'),
(2, '20100123456', 'TECNOLOGÍA AVANZADA SAC', 'Carlos Manuel Rodríguez Pérez', 'TECNOAVANZADA', 'AV. REAL 123 - HUANCAYO', '964123456', 'info@tecnoavanzada.com', 'TECNOLOGÍA', 1, 0, 'ACTIVO', 'HABIDO', '120101', 'JUNÍN', 'HUANCAYO', 'HUANCAYO', '2025-11-23 21:52:39', '2025-11-23 21:52:39'),
(3, '20100234567', 'SOLUCIONES WEB EIRL', 'Ana Lucía Fernández García', 'SOLWEB', 'JR. LIMA 456 - EL TAMBO', '965234567', 'contacto@solweb.com', 'DESARROLLO SOFTWARE', 1, 0, 'ACTIVO', 'HABIDO', '120125', 'JUNÍN', 'HUANCAYO', 'EL TAMBO', '2025-11-23 21:52:39', '2025-11-23 21:52:39'),
(4, '20100345678', 'INNOVACIÓN DIGITAL SAC', 'Luis Alberto Torres Mendoza', 'INNODIGITAL', 'AV. HUÁNCANO 789 - HUANCAYO', '966345678', 'innovacion@innodigital.com', 'TECNOLOGÍA', 1, 0, 'ACTIVO', 'HABIDO', '120101', 'JUNÍN', 'HUANCAYO', 'HUANCAYO', '2025-11-23 21:52:39', '2025-11-23 21:52:39'),
(5, '20100456789', 'SISTEMAS INTEGRALES EIRL', 'María Elena Silva Castro', 'SISINTEGRAL', 'CALLE LOS ANDES 321 - CHILCA', '967456789', 'sistemas@sisintegral.com', 'INFORMÁTICA', 1, 0, 'ACTIVO', 'HABIDO', '120112', 'JUNÍN', 'HUANCAYO', 'CHILCA', '2025-11-23 21:52:39', '2025-11-23 21:52:39');

-- Agregar la clave primaria AUTO_INCREMENT si no la tiene
ALTER TABLE `empresa` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- Agregar índice para búsquedas rápidas
ALTER TABLE `empresa` ADD INDEX `idx_ruc` (`ruc`);
ALTER TABLE `empresa` ADD INDEX `idx_razon_social` (`razon_social`);

CREATE TABLE `practicas` (
  `id` int(11) NOT NULL,
  `estudiante` int(11) NOT NULL,
  `empleado` int(11) DEFAULT NULL,
  `docente_supervisor` int(11) DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `modulo` varchar(100) DEFAULT NULL,
  `tipo_efsrt` enum('modulo1','modulo2','modulo3') DEFAULT NULL,
  `periodo_academico` varchar(50) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `total_horas` int(11) DEFAULT 0,
  `horas_acumuladas` int(11) DEFAULT 0,
  `area_ejecucion` varchar(255) DEFAULT NULL,
  `supervisor_empresa` varchar(255) DEFAULT NULL,
  `cargo_supervisor` varchar(150) DEFAULT NULL,
  `periodo_academico_efsrt` varchar(50) DEFAULT NULL,
  `turno_efsrt` varchar(20) DEFAULT NULL,
  `estado` enum('En curso','Finalizado','Pendiente') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `practicas`
--

INSERT INTO `practicas` (`id`, `estudiante`, `empleado`, `docente_supervisor`, `empresa`, `modulo`, `tipo_efsrt`, `periodo_academico`, `fecha_inicio`, `fecha_fin`, `total_horas`, `horas_acumuladas`, `area_ejecucion`, `supervisor_empresa`, `cargo_supervisor`, `periodo_academico_efsrt`, `turno_efsrt`, `estado`) VALUES
(1, 1, 3, NULL, 1, 'INTEGRACIÓN DE APLICACIONES WEB Y MÓVILES', 'modulo2', 'VI', '2024-09-26', '2024-12-13', 128, 16, NULL, NULL, NULL, NULL, NULL, 'En curso'),
(2, 2, 3, NULL, 1, 'DESARROLLO DE APLICACIONES WEB', 'modulo1', 'VI', '2024-09-25', '2024-12-12', 128, 20, NULL, NULL, NULL, NULL, NULL, 'En curso'),
(3, 3, 3, NULL, 1, 'DISEÑO DE INTERFACES DE USUARIO', 'modulo3', 'VI', '2024-09-24', '2024-12-11', 128, 24, NULL, NULL, NULL, NULL, NULL, 'En curso'),
(4, 4, 3, NULL, 1, 'PROGRAMACIÓN WEB AVANZADA', 'modulo1', 'VI', '2024-09-23', '2024-12-10', 128, 28, NULL, NULL, NULL, NULL, NULL, 'Pendiente'),
(5, 5, 3, NULL, 1, 'BASE DE DATOS Y BACKEND', 'modulo1', 'VI', '2024-09-22', '2024-12-09', 128, 128, NULL, NULL, NULL, NULL, NULL, 'Finalizado');

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practicas` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `horas_acumuladas` int(11) DEFAULT NULL,
  `actividad` text DEFAULT NULL,
  -- ❌ ELIMINAMOS los vistos buenos (se harán manuales en papel)
  PRIMARY KEY (`id`),
  KEY `idx_practicas` (`practicas`),
  KEY `idx_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Datos de prueba SIN vistos buenos
INSERT INTO `asistencias` (`practicas`, `fecha`, `hora_entrada`, `hora_salida`, `horas_acumuladas`, `actividad`) VALUES
(1, '2024-09-26', '08:00:00', '12:00:00', 4, 'Familiarizarse con el entorno de trabajo'),
(1, '2024-09-30', '08:30:00', '12:30:00', 4, 'Conocer el trabajo de la empresa y todos sus rubros'),
(1, '2024-10-02', '08:00:00', '12:00:00', 4, 'Recopilación de requerimientos para la web y app'),
(1, '2024-10-03', '08:30:00', '12:30:00', 4, 'Estudiar las páginas y aplicaciones de la competencia'),
(1, '2024-10-07', '08:00:00', '12:15:00', 4, 'Diseñar los prototipos del sitio web y app');

CREATE TABLE `efsrt_documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practica_id` int(11) NOT NULL,
  `tipo_documento` enum('oficio_multiple','carta_presentacion','ficha_identidad') NOT NULL,
  `numero_oficio` varchar(50) DEFAULT NULL, -- CAMPO NUEVO
  `contenido` text NOT NULL,
  `fecha_documento` date DEFAULT NULL, -- CAMPO NUEVO
  `fecha_generacion` datetime DEFAULT current_timestamp(),
  `generado_por` int(11) DEFAULT NULL,
  `estado` enum('generado','firmado','enviado') DEFAULT 'generado',
  PRIMARY KEY (`id`),
  KEY `idx_practica_id` (`practica_id`),
  KEY `idx_tipo_documento` (`tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Insertar más datos de prueba si es necesario
INSERT INTO `efsrt_documentos` (`practica_id`, `tipo_documento`, `numero_oficio`, `contenido`, `fecha_documento`, `generado_por`, `estado`) VALUES
(1, 'ficha_identidad', '2025-IESTP"AACD"/DG', 'Ficha de identidad del estudiante...', '2025-05-27', 3, 'generado'),
(2, 'oficio_multiple', '2025-IESTP"AACD"/DG-002', 'Oficio múltiple para empresa...', '2025-05-27', 3, 'generado');

-- Tabla de configuración EFSRT
CREATE TABLE `efsrt_configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `horas_por_modulo` int(11) DEFAULT 128,
  `dias_habiles_semana` int(11) DEFAULT 5,
  `horas_diarias` int(11) DEFAULT 4,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

INSERT INTO `efsrt_configuracion` (`id`, `horas_por_modulo`, `dias_habiles_semana`, `horas_diarias`, `fecha_actualizacion`) VALUES
(1, 128, 5, 4, '2025-11-17 20:58:17');

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

-- Datos para tabla USUARIOS
INSERT INTO `usuarios` (`usuario`, `password`, `tipo`, `estuempleado`, `token`, `estado`, `nivel`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NULL, 1, 1);

---------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------

-- Actualizar los datos existentes
UPDATE `matricula` SET 
`turno` = CASE 
    WHEN `turno` = 'D' THEN 'DIURNO'
    WHEN `turno` = 'V' THEN 'VESPERTINO' 
    ELSE 'DIURNO' 
END;

-- Actualizar datos de prueba para que tengan turno completo
UPDATE `matricula` SET `turno` = 'VESPERTINO' WHERE `id` IN (1,2,3,4,5);
---------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------
-- VISTA: vista_estudiante_efsrt
---------------------------------------------------------------------------------------------------------------------------------------------------

CREATE VIEW `vista_estudiante_efsrt` AS
SELECT 
    -- Datos básicos del estudiante
    e.`id` as estudiante_id,
    e.`dni_est`,
    CONCAT(e.`ap_est`, ' ', e.`am_est`, ', ', e.`nom_est`) as nombre_completo,
    e.`ap_est`,
    e.`am_est`, 
    e.`nom_est`,
    e.`cel_est`,
    e.`dir_est`,
    e.`mailp_est`,
    e.`maili_est`,
    e.`fecnac_est`,
    e.`ubigeonac_est`,
    
    -- Datos de matrícula y programa
    m.`id_matricula`,
    m.`per_acad` as periodo_academico,
    m.`turno`,
    p.`id` as programa_id,
    p.`nom_progest` as programa_estudios,
    
    -- Año de ingreso (de la fecha de matrícula)
    YEAR(m.`fec_matricula`) as año_ingreso,
    
    -- Lugar de nacimiento (si necesitas unirlo con ubigeo después)
    e.`ubigeonac_est` as ubigeo_nacimiento
    
FROM `estudiante` e
LEFT JOIN `matricula` m ON e.`id` = m.`estudiante`
LEFT JOIN `prog_estudios` p ON m.`prog_estudios` = p.`id`
WHERE e.`estado` = 1 AND m.`est_matricula` = '1';

---------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------

-- Vista para obtener el lugar de nacimiento en texto completo
CREATE VIEW `vista_ubigeo_completo` AS
SELECT 
    di.`id` as ubigeo_id,
    CONCAT(d.`departamento`, '/', p.`provincia`, '/', di.`distrito`) as lugar_completo,
    d.`departamento`,
    p.`provincia`, 
    di.`distrito`
FROM `ubdistrito` di
JOIN `ubprovincia` p ON di.`ubprovincia` = p.`id`
JOIN `ubdepartamento` d ON p.`ubdepartamento` = d.`id`;

---------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------

-- Vista final que une todo
CREATE VIEW `vista_efsrt_completa` AS
SELECT 
    e.*,
    u.`lugar_completo` as lugar_nacimiento,
    u.`departamento` as region_nacimiento,
    u.`provincia` as provincia_nacimiento,
    u.`distrito` as distrito_nacimiento
FROM `vista_estudiante_efsrt` e
LEFT JOIN `vista_ubigeo_completo` u ON e.`ubigeo_nacimiento` = u.`ubigeo_id`;

---------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------

-- Vista completa de empresas para EFSRT
CREATE VIEW `vista_empresa_efsrt` AS
SELECT 
    e.`id`,
    e.`ruc`,
    e.`razon_social`,
    e.`representante_legal`,
    e.`nombre_comercial`,
    e.`direccion_fiscal` as direccion,
    e.`telefono`,
    e.`email`,
    e.`sector`,
    CONCAT(e.`departamento`, ' / ', e.`provincia`, ' / ', e.`distrito`) as ubicacion_completa,
    e.`departamento`,
    e.`provincia`, 
    e.`distrito`
FROM `empresa` e;