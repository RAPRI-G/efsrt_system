CREATE TABLE `estudiante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dni_est` char(8) DEFAULT NULL,
  `ap_est` varchar(40) DEFAULT NULL,
  `am_est` varchar(40) DEFAULT NULL,
  `nom_est` varchar(40) DEFAULT NULL,
  `cel_est` char(9) DEFAULT NULL,
  `ubigeonac_est` char(6) DEFAULT NULL,
  `dir_est` varchar(40) DEFAULT NULL,
  `mailp_est` varchar(40) DEFAULT NULL,
  `fecnac_est` date DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_dni` (`dni_est`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `matricula` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante` int(11) DEFAULT NULL,
  `prog_estudios` int(11) DEFAULT NULL,
  `id_matricula` char(9) DEFAULT NULL,
  `per_acad` varchar(3) DEFAULT NULL,
  `turno` varchar(20) DEFAULT NULL,
  `fec_matricula` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estudiante` (`estudiante`),
  KEY `prog_estudios` (`prog_estudios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `prog_estudios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_progest` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `empleado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dni_emp` char(8) DEFAULT NULL,
  `apnom_emp` varchar(60) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ruc` varchar(11) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `representante_legal` varchar(255) DEFAULT NULL,
  `direccion_fiscal` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `distrito` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'ACTIVO',
  PRIMARY KEY (`id`),
  KEY `idx_ruc` (`ruc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `practicas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante` int(11) NOT NULL,
  `empleado` int(11) DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `modulo` varchar(100) DEFAULT NULL,
  `tipo_efsrt` enum('modulo1','modulo2','modulo3') DEFAULT NULL,
  `periodo_academico` varchar(50) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `total_horas` int(11) DEFAULT 0,
  `area_ejecucion` varchar(255) DEFAULT NULL,
  `supervisor_empresa` varchar(255) DEFAULT NULL,
  `cargo_supervisor` varchar(150) DEFAULT NULL,
  `estado` enum('En curso','Finalizado','Pendiente') DEFAULT 'Pendiente',
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `estudiante` (`estudiante`),
  KEY `empleado` (`empleado`),
  KEY `empresa` (`empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practicas` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `horas_acumuladas` int(11) DEFAULT NULL,
  `actividad` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `practicas` (`practicas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `efsrt_documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practica_id` int(11) NOT NULL,
  `tipo_documento` enum('oficio_multiple','carta_presentacion','ficha_identidad') NOT NULL,
  `numero_oficio` varchar(50) DEFAULT NULL,
  `contenido` text NOT NULL,
  `fecha_documento` date DEFAULT NULL,
  `fecha_generacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `generado_por` int(11) DEFAULT NULL,
  `estado` enum('generado','firmado','enviado') DEFAULT 'generado',
  PRIMARY KEY (`id`),
  KEY `practica_id` (`practica_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(200) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `estuempleado` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
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