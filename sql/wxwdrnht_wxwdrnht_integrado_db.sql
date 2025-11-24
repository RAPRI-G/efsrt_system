-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2025 a las 04:33:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `wxwdrnht_wxwdrnht_integrado_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alerta_ia`
--

CREATE TABLE `alerta_ia` (
  `id` int(11) NOT NULL,
  `documento` int(11) DEFAULT NULL,
  `convenio` int(11) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `clausula` text DEFAULT NULL,
  `sugerencia` text DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `resuelta` int(11) DEFAULT NULL,
  `resuelto_por` int(11) DEFAULT NULL,
  `tipouser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `practicas` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `horas_acumuladas` int(11) DEFAULT NULL,
  `actividad` text DEFAULT NULL,
  `visto_bueno_empresa` varchar(150) DEFAULT NULL,
  `visto_bueno_docente` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_cap`
--

CREATE TABLE `asistencia_cap` (
  `id` int(11) NOT NULL,
  `asistencia` tinyint(1) DEFAULT NULL,
  `certificacion` tinyint(1) DEFAULT NULL,
  `certificado_archivo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `usuario` int(11) DEFAULT NULL,
  `convenio` int(11) DEFAULT NULL,
  `accion` varchar(20) DEFAULT NULL,
  `tabla` varchar(100) DEFAULT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `datos_anteriores` text DEFAULT NULL,
  `datos_nuevos` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tipouser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_cap`
--

CREATE TABLE `auditoria_cap` (
  `id` int(11) NOT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `id_registro_afectado` int(11) DEFAULT NULL,
  `accion` enum('insert','update','delete') DEFAULT NULL,
  `fecha_accion` datetime DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `beneficiarios`
--

CREATE TABLE `beneficiarios` (
  `id` int(11) NOT NULL,
  `estudiante` int(11) NOT NULL,
  `resoluciones` int(11) NOT NULL,
  `porcentaje_descuento` decimal(5,2) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `registrado_por` int(11) DEFAULT NULL,
  `registrado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_sunat`
--

CREATE TABLE `cache_sunat` (
  `id` int(11) NOT NULL,
  `ruc` varchar(11) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `nombre_comercial` varchar(255) DEFAULT NULL,
  `direccion_fiscal` text DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `distrito` varchar(100) DEFAULT NULL,
  `ubigeo` varchar(6) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `condicion` varchar(50) DEFAULT NULL,
  `representante_legal` varchar(255) DEFAULT NULL,
  `representante_cargo` varchar(150) DEFAULT NULL,
  `representante_desde` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_inscripcion` date DEFAULT NULL,
  `fecha_inicio_actividades` date DEFAULT NULL,
  `fecha_consulta` datetime DEFAULT NULL,
  `fecha_expiracion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunicacion`
--

CREATE TABLE `comunicacion` (
  `id` int(11) NOT NULL,
  `convenio` int(11) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `enviado_por` int(11) DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL,
  `respondido` int(11) DEFAULT NULL,
  `archivo` varchar(500) DEFAULT NULL,
  `tipouser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `condicion_academica`
--

CREATE TABLE `condicion_academica` (
  `id` int(11) NOT NULL,
  `nombre_condicion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `condicion_laboral`
--

CREATE TABLE `condicion_laboral` (
  `id` int(11) NOT NULL,
  `nombre_condicion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `convenio`
--

CREATE TABLE `convenio` (
  `id` int(11) NOT NULL,
  `instituto` int(11) DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `iniciado_por` varchar(20) DEFAULT NULL,
  `objetivo` text DEFAULT NULL,
  `beneficios_instituto` text DEFAULT NULL,
  `beneficios_empresa` text DEFAULT NULL,
  `duracion_meses` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `renovacion_automatica` int(11) DEFAULT NULL,
  `condiciones_especiales` text DEFAULT NULL,
  `archivo_fisico` varchar(255) DEFAULT NULL,
  `qr` varchar(500) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_activacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id` int(11) NOT NULL,
  `nom_curso` varchar(60) NOT NULL,
  `modalidad_curso` enum('presencial','virtual') NOT NULL,
  `descripcion_curso` text DEFAULT NULL,
  `fechini_curso` date DEFAULT NULL,
  `fechfin_curso` date DEFAULT NULL,
  `hora_curso` varchar(50) DEFAULT NULL,
  `lugar_curso` varchar(100) DEFAULT NULL,
  `estado_curso` enum('vigente','finalizado') DEFAULT 'vigente',
  `organizador_curso` varchar(100) DEFAULT NULL,
  `certificacion_curso` tinyint(1) DEFAULT 0,
  `duracion_curso` varchar(50) DEFAULT NULL,
  `costo_curso` decimal(5,2) DEFAULT NULL,
  `creditos_curso` char(3) DEFAULT NULL,
  `foto_curso` varchar(255) DEFAULT NULL,
  `tipo_curso` int(11) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id` int(11) NOT NULL,
  `convenio` int(11) DEFAULT NULL,
  `reunion` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `archivo` varchar(500) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `tamano` bigint(20) DEFAULT NULL,
  `hash` varchar(64) DEFAULT NULL,
  `firmado_instituto` int(11) DEFAULT NULL,
  `firmado_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `efsrt_configuracion`
--

CREATE TABLE `efsrt_configuracion` (
  `id` int(11) NOT NULL,
  `horas_por_modulo` int(11) DEFAULT 128,
  `dias_habiles_semana` int(11) DEFAULT 5,
  `horas_diarias` int(11) DEFAULT 4,
  `fecha_actualizacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `efsrt_configuracion`
--

INSERT INTO `efsrt_configuracion` (`id`, `horas_por_modulo`, `dias_habiles_semana`, `horas_diarias`, `fecha_actualizacion`) VALUES
(1, 128, 5, 4, '2025-11-17 20:58:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `efsrt_documentos`
--

CREATE TABLE `efsrt_documentos` (
  `id` int(11) NOT NULL,
  `practica_id` int(11) NOT NULL,
  `tipo_documento` enum('oficio_multiple','carta_presentacion','ficha_identidad') NOT NULL,
  `contenido` text NOT NULL,
  `fecha_generacion` datetime DEFAULT current_timestamp(),
  `generado_por` int(11) DEFAULT NULL,
  `estado` enum('generado','firmado','enviado') DEFAULT 'generado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

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
(10, NULL, '19830094', 'BALVIN ROJAS OLDARICO', 'M', '990337977', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(11, NULL, '19831933', 'ROSALES PECHO ELSA DARIA', 'F', '954453792', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(12, NULL, '19835648', 'EGOAVIL VICTORIA HEBER ELISEO', 'M', '986966320', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MET', '0000-00-00', '\r', NULL),
(13, NULL, '19841070', 'DE LA CRUZ QUISPE RUBEN', 'M', '976732614', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MPI', '0000-00-00', '\r', NULL),
(14, NULL, '19848240', 'PONCE ZENTENO DE MELGAREJO NANCY', 'F', '981664997', '', '', '', '', '', '0000-00-00', 'D', 'N', 'TAQ', '0000-00-00', '\r', NULL),
(15, NULL, '19857012', 'VILCHEZ MALLQUI ARTURO PEDRO', 'M', '988097585', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MPI', '0000-00-00', '\r', NULL),
(16, NULL, '19862074', 'BALDEON BERROCAL LUZ AGNES', 'F', '980055040', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(17, NULL, '19867396', 'ARANA CANGALAYA LEONIDAS SAMUEL', 'M', '964782208', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PSE', '0000-00-00', '\r', NULL),
(18, NULL, '19867426', 'MATOS CERRON ELMER JESUS', 'M', '972901769', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PSE', '0000-00-00', '\r', NULL),
(19, NULL, '19869060', 'SOCUALAYA VASQUEZ BERNARDO', 'M', '925820658', '', '', '', '', '', '0000-00-00', 'A', 'C', 'PSE', '0000-00-00', '\r', NULL),
(20, NULL, '19875827', 'CHANCHASANAMPA LARA MAXIMO', 'M', '979554907', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MMP', '0000-00-00', '\r', NULL),
(21, NULL, '19878093', 'LOPEZ CAMARENA CLARA ELIZABETH', 'F', '984560262', '', '', '', '', '', '0000-00-00', 'D', 'N', 'TAQ', '0000-00-00', '\r', NULL),
(22, NULL, '19879636', 'NATEROS PORRAS GLADYS ELVA', 'F', '964738938', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PAT', '0000-00-00', '\r', NULL),
(23, NULL, '19885890', 'CAIRO HURTADO ELEUD SEVERO', 'M', '964224670', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MCA', '0000-00-00', '\r', NULL),
(24, NULL, '19889604', 'SUAREZ ESPINOZA CARMEN', 'F', '949884838', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PSE', '0000-00-00', '\r', NULL),
(25, NULL, '19891035', 'MENDOZA LIMACHE MIGUEL ANGEL', 'M', '995303392', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MCA', '0000-00-00', '\r', NULL),
(26, NULL, '19896397', 'TACAY ELESCANO GERARDO ALBERTO', 'M', '949965905', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(27, NULL, '19901610', 'OCHOA ALIAGA ABEL PEDRO', 'M', '934239203', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MMP', '0000-00-00', '\r', NULL),
(28, NULL, '19911732', 'MARQUEZ LUCAS LUIS JUDIT', 'M', '966246495', '', '', '', '', '', '0000-00-00', 'A', 'N', 'RHU', '0000-00-00', '\r', NULL),
(29, NULL, '19912822', 'SOCUALAYA ROJAS PRIMITIVO', 'M', '958855303', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PSE', '0000-00-00', '\r', NULL),
(30, NULL, '19913849', 'MEDRANO URBANO YOLANDA', 'F', '981479524', '', '', '', '', '', '0000-00-00', 'A', 'N', 'CPR', '0000-00-00', '\r', NULL),
(31, NULL, '19917986', 'CANCHUCAJA VALDIVIESO ROSALINDA', 'F', '968640269', '', '', '', '', '', '0000-00-00', 'D', 'N', 'TAQ', '0000-00-00', '\r', NULL),
(32, NULL, '19918032', 'VELASCO CASTRO ROBERTO JESUS', 'M', '918485172', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELO', '0000-00-00', '\r', NULL),
(33, NULL, '19919679', 'COZ ROMANI ALBERTO EVARISTO', 'M', '943618118', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MPI', '0000-00-00', '\r', NULL),
(34, NULL, '19920669', 'SERRANO PIZARRO LIEZBETH', 'F', '925496349', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(35, NULL, '19921620', 'ORDO?EZ CERRON LURDES VILMA', 'F', '964559698', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(36, NULL, '19922756', 'BRA?ES ESPINOZA LILY MARTHA', 'F', '976948323', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(37, NULL, '19928076', 'REYMUNDO SULLCARAY SIMON JOSE', 'M', '933949604', '', '', '', '', '', '0000-00-00', 'D', 'C', 'MMP', '0000-00-00', '\r', NULL),
(38, NULL, '19929923', 'TOVAR ANTICONA VALENTIN', 'M', '968282877', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MET', '0000-00-00', '\r', NULL),
(39, NULL, '19952401', 'RODRIGO MOSCOSO LUCIA', 'F', '996428087', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(40, NULL, '19961848', 'CALDERON CUYUTUPA VILMA MAXIMA', 'F', '964874745', '', '', '', '', '', '0000-00-00', 'D', 'N', 'DPW', '0000-00-00', '\r', NULL),
(41, NULL, '19963409', 'YAURI SALOME ANGEL JESUS', 'M', '975129327', '', '', '', '', '', '0000-00-00', 'A', 'N', 'TEC', '0000-00-00', '\r', NULL),
(42, NULL, '19964265', 'BRAVO GALVEZ ADOLFO', 'M', '954449265', '', '', '', '', '', '0000-00-00', 'D', 'N', 'TAQ', '0000-00-00', '\r', NULL),
(43, NULL, '19965368', 'PONCE MEZA LUIS JOSE', 'M', '979964690', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELA', '0000-00-00', '\r', NULL),
(44, NULL, '19980491', 'QUISPE TOMAS RODOLFO', 'M', '965766341', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PAT', '0000-00-00', '\r', NULL),
(45, NULL, '19982648', 'FLORES TORRES JESUS', 'M', '938404172', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(46, NULL, '19992217', 'MONTERO AMES RAMIRO PEDRO', 'M', '964950333', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MMP', '0000-00-00', '\r', NULL),
(47, NULL, '19998126', 'OLIVERA DORREGARAY SUSANA BEAT', 'F', '926085203', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(48, NULL, '20008127', 'TACAY ELESCANO ANIBAL', 'M', '954092166', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(49, NULL, '20011678', 'CARDENAS PEREZ LOURDES LIDIA', 'F', '945920504', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(50, NULL, '20012709', 'MIRANDA MORILLO FLOR MARLENE', 'F', '963636430', '', '', '', '', '', '0000-00-00', 'A', 'N', 'CAJ', '0000-00-00', '\r', NULL),
(51, NULL, '20017363', 'CASTRO RUIZ JESUS FRANCISCO', 'M', '964980365', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELO', '0000-00-00', '\r', NULL),
(52, NULL, '20019948', 'CARHUACHI RAMOS CEFERINO EDWIN', 'M', '989796397', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(53, NULL, '20020070', 'MERLO GALVEZ JUAN LUIS', 'M', '964630163', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELO', '0000-00-00', '\r', NULL),
(54, NULL, '20020624', 'CHAVEZ CANEZ PEDRO BEKER', 'M', '992328320', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELA', '0000-00-00', '\r', NULL),
(55, NULL, '20022570', 'ORTIZ JHAN CARLOS', 'M', '956538642', '', '', '', '', '', '0000-00-00', 'D', 'C', 'ELO', '0000-00-00', '\r', NULL),
(56, NULL, '20024057', 'POMA BACA IVAN', 'M', '977532038', '', '', '', '', '', '0000-00-00', 'D', 'N', 'DPW', '0000-00-00', '\r', NULL),
(57, NULL, '20027677', 'DELGADILLO PE?ALOZA JULIA ROBERTA', 'F', '951055209', '', '', '', '', '', '0000-00-00', 'A', 'N', 'TES', '0000-00-00', '\r', NULL),
(58, NULL, '20030374', 'FERNANDEZ BEJARANO RAUL ENRIQUE', 'M', '964310741', '', '', '', '', '', '0000-00-00', 'D', 'N', 'DPW', '0000-00-00', '\r', NULL),
(59, NULL, '20030549', 'SALAZAR IBARRA LEILA EDITH', 'F', '969069407', '', '', '', '', '', '0000-00-00', 'A', 'N', 'BIB', '0000-00-00', '\r', NULL),
(60, NULL, '20040535', 'GENG MONTALVAN JUSTINO', 'M', '964470640', '', '', '', '', '', '0000-00-00', 'D', 'N', 'DPW', '0000-00-00', '\r', NULL),
(61, NULL, '20042198', 'OCHOA TORRES JUAN ARTURO', 'M', '999339505', '', '', '', '', '', '0000-00-00', 'D', 'C', 'MCA', '0000-00-00', '\r', NULL),
(62, NULL, '20042628', 'SANCHEZ QUISPE OLGA LIDIA', 'F', '910325187', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(63, NULL, '20044598', 'LAZO GALVEZ ROLANDO', 'M', '926197774', '', '', '', '', '', '0000-00-00', 'D', 'N', 'DPW', '0000-00-00', '\r', NULL),
(64, NULL, '20049358', 'CASTILLO QUISPE ROCIO', 'F', '954463417', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(65, NULL, '20051426', 'RUIZ YACHACHI FABIAN', 'F', '964876554', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MCA', '0000-00-00', '\r', NULL),
(66, NULL, '20057639', 'HUAYLINOS GONZALES ENRIQUE GRIM', 'M', '956999950', '', '', '', '', '', '0000-00-00', 'D', 'N', 'DPW', '0000-00-00', '\r', NULL),
(67, NULL, '20063130', 'VARGAS NU?EZ ROSARIO', 'F', '994402154', '', '', '', '', '', '0000-00-00', 'A', 'N', 'ABA', '0000-00-00', '\r', NULL),
(68, NULL, '20066008', 'BLANCO HINOSTROZA ALEJANDRA FORTUNATA', 'F', '964874745', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(69, NULL, '20066636', 'HUAMAN PAUCAR HERCULES HERNAN', 'M', '930461868', '', '', '', '', '', '0000-00-00', 'D', 'N', 'DPW', '0000-00-00', '\r', NULL),
(70, NULL, '20069220', 'HUAMAN ALAMO ADELAIDA SONIA', 'F', '954949424', '', '', '', '', '', '0000-00-00', 'D', 'C', 'EMP', '0000-00-00', '\r', NULL),
(71, NULL, '20079131', 'BARAHONA TERREROS ESTHER', 'F', '964373556', '', '', '', '', '', '0000-00-00', 'D', 'C', 'EMP', '0000-00-00', '\r', NULL),
(72, NULL, '20088114', 'BALBIN ARAMBURU ELVA KATHERINE', 'F', '964010000', '', '', '', '', '', '0000-00-00', 'A', 'N', 'SUP', '0000-00-00', '\r', NULL),
(73, NULL, '20090169', 'MANDARACHI ALVAREZ FREDY ALFREDO', 'M', '993262876', '', '', '', '', '', '0000-00-00', 'D', 'C', 'MET', '0000-00-00', '\r', NULL),
(74, NULL, '20096529', 'CALDERON GUARDIA FLORENCIA', 'F', '964495121', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(75, NULL, '20096748', 'VITOR RIVERA PEDRO ANTONIO', 'M', '945043522', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MCA', '0000-00-00', '\r', NULL),
(76, NULL, '20101567', 'HERRERA APONTE NOEMI EUNICE', 'F', '954836187', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PSE', '0000-00-00', '\r', NULL),
(77, NULL, '20102277', 'PORRAS PUQUIO EDGAR MARCIAL', 'M', '986787081', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELA', '0000-00-00', '\r', NULL),
(78, NULL, '20102562', 'HUARACA QUISPE BEGONIA', 'F', '978554128', '', '', '', '', '', '0000-00-00', 'A', 'N', 'SIS', '0000-00-00', '\r', NULL),
(79, NULL, '20112470', 'RICSE PECHO CHRISTIAN RAMON', 'M', '939399048', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MMP', '0000-00-00', '\r', NULL),
(80, NULL, '20120930', 'VILLASANA LOPEZ MARCO', 'M', '970998597', '', '', '', '', '', '0000-00-00', 'D', 'C', 'DPW', '0000-00-00', '\r', NULL),
(81, NULL, '20402877', 'VELIZ RICALDI VICTOR HUGO', 'M', '999929774', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELA', '0000-00-00', '\r', NULL),
(82, NULL, '20403337', 'ROQUE MOLEROS WALTER', 'M', '964684628', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MPI', '0000-00-00', '\r', NULL),
(83, NULL, '20404626', 'CASTILLO BERRIOS FLOR GUADALUPE', 'F', '924035592', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(84, NULL, '20430216', 'MARIN GRANDE AMELIA VALENTINA', 'F', '964008908', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(85, NULL, '20443732', 'SALAZAR HUAMANCAYO VLADIMIR THOMAS', 'M', '954819062', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MPI', '0000-00-00', '\r', NULL),
(86, NULL, '20642048', 'GAVE QUINTANA VILMA ESTHER', 'F', '965896511', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(87, NULL, '20682499', 'FABIAN CAMARENA FRANCISCO', 'M', '964028077', '', '', '', '', '', '0000-00-00', 'D', 'N', 'EMP', '0000-00-00', '\r', NULL),
(88, NULL, '20707136', 'QUINCHO ROJAS CELIA', 'F', '989230117', '', '', '', '', '', '0000-00-00', 'D', 'C', 'TAQ', '0000-00-00', '\r', NULL),
(89, NULL, '21133450', 'MONTES SHEPHERD EDGARD MICHAEL', 'M', '963030413', '', '', '', '', '', '0000-00-00', 'D', 'N', 'DPW', '0000-00-00', '\r', NULL),
(90, NULL, '21260301', 'INGA DAMIAN GONZALO PAUL', 'M', '925900586', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ASA', '0000-00-00', '\r', NULL),
(91, NULL, '21286900', 'SEGURA MEZA LUIS ALBERTO', 'M', '949398439', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELA', '0000-00-00', '\r', NULL),
(92, NULL, '23719259', 'CARDENAS PERALTA PABLO JOSE', 'M', '971000189', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MMP', '0000-00-00', '\r', NULL),
(93, NULL, '30576859', 'FLORES SARMIENTO YVER JONY', 'M', '999010456', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MPI', '0000-00-00', '\r', NULL),
(94, NULL, '40174999', 'BLAS NAVARRO WILDER', 'M', '960493224', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MCA', '0000-00-00', '\r', NULL),
(95, NULL, '40178479', 'RAMOS CORDOVA ANA LUISA', 'F', '964001653', '', '', '', '', '', '0000-00-00', 'A', 'N', 'SEC', '0000-00-00', '\r', NULL),
(96, NULL, '40309657', 'AQUINO SIMEON MAYELA DORIS', 'F', '999955306', '', '', '', '', '', '0000-00-00', 'A', 'C', 'PSE', '0000-00-00', '\r', NULL),
(97, NULL, '40547737', 'MARQUEZ LUCAS EDWARD', 'M', '974493241', '', '', '', '', '', '0000-00-00', 'A', 'C', 'ALM', '0000-00-00', '\r', NULL),
(98, NULL, '40623086', 'MALQUI ESTRELLA TANIA GIANINA', 'F', '979106055', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PPO', '0000-00-00', '\r', NULL),
(99, NULL, '40829621', 'CORONACION ROMERO OSCAR', 'M', '990304304', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MCA', '0000-00-00', '\r', NULL),
(100, NULL, '41526641', 'LAUREANO GOMEZ IVAN RAUL', 'M', '976907382', '', '', '', '', '', '0000-00-00', 'D', 'N', 'ELO', '0000-00-00', '\r', NULL),
(101, NULL, '41582636', 'PEREZ CAPCHA CARLOS GEOVANY', 'M', '920332318', '', '', '', '', '', '0000-00-00', 'D', 'C', 'ASA', '0000-00-00', '\r', NULL),
(102, NULL, '41587311', 'CARDENAS PEINADO RUTH MARITZA', 'F', '996492886', '', '', '', '', '', '0000-00-00', 'D', 'C', 'ASA', '0000-00-00', '\r', NULL),
(103, NULL, '41594795', 'LOPEZ CORDOVA ALDO RICHARD', 'M', '993651313', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MET', '0000-00-00', '\r', NULL),
(104, NULL, '41604489', 'MANDUJANO CARDENAS IVONNE', 'F', '951512636', '', '', '', '', '', '0000-00-00', 'A', 'C', 'SEC', '0000-00-00', '\r', NULL),
(105, NULL, '41883713', 'BALBIN PAUCAR JUBER', 'M', '988010480', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MCA', '0000-00-00', '\r', NULL),
(106, NULL, '41929788', 'CASTRO PORRAS OMAR', 'M', '996969978', '', '', '', '', '', '0000-00-00', 'D', 'C', 'ELO', '0000-00-00', '\r', NULL),
(107, NULL, '42004760', 'PARRAGA OLIVERA ELVIS', 'M', '937394633', '', '', '', '', '', '0000-00-00', 'D', 'C', 'MET', '0000-00-00', '\r', NULL),
(108, NULL, '42343557', 'ALDANA LUNA FRANKLIN CRISTIAN', 'M', '930227246', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MMP', '0000-00-00', '\r', NULL),
(109, NULL, '42412913', 'DELAO PAITAMPOMA KENYN SUSY', 'F', '932768929', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PSE', '0000-00-00', '\r', NULL),
(110, NULL, '42921498', 'DE LA CRUZ BLAS GABRIEL LEVI', 'M', '947430302', '', '', '', '', '', '0000-00-00', 'D', 'C', 'MCA', '0000-00-00', '\r', NULL),
(111, NULL, '43889561', 'DELAO PAITAMPOMA MARILUZ', 'F', '929364065', '', '', '', '', '', '0000-00-00', 'A', 'N', 'PSE', '0000-00-00', '\r', NULL),
(112, NULL, '44001672', 'PUENTE YALOPOMA LUIS DAVID', 'M', '975874467', '', '', '', '', '', '0000-00-00', 'D', 'C', 'ELO', '0000-00-00', '\r', NULL),
(113, NULL, '45380686', 'QUISPE YALE ULISES', 'M', '971507960', '', '', '', '', '', '0000-00-00', 'D', 'C', 'MPI', '0000-00-00', '\r', NULL),
(114, NULL, '46386484', 'VILCATOMA CONTRERAS TO?O BRUNO', 'M', '937666155', '', '', '', '', '', '0000-00-00', 'D', 'C', 'TAQ', '0000-00-00', '\r', NULL),
(115, NULL, '46571679', 'MACHA DAMIAN ROMARIO RUBEN', 'M', '964320472', '', '', '', '', '', '0000-00-00', 'D', 'C', 'ELA', '0000-00-00', '\r', NULL),
(116, NULL, '47766672', 'SANDOVAL LOPEZ RONAL GABRIEL', 'M', '969749489', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MPI', '0000-00-00', '\r', NULL),
(117, NULL, '60210063', 'CONTRERAS PARIONA MAYCOL RAUL', 'M', '942667335', '', '', '', '', '', '0000-00-00', 'D', 'C', 'TAQ', '0000-00-00', '\r', NULL),
(118, NULL, '70077714', 'RICALDI ORE JISENIA PAOLA', 'F', '982100887', '', '', '', '', '', '0000-00-00', 'D', 'N', 'MET', '0000-00-00', '\r', NULL),
(119, NULL, '70237740', 'CUNYAS ALCANTARA CHRISTIAN EDWIN', 'M', '964070296', '', '', '', '', '', '0000-00-00', 'D', 'C', 'ELO', '0000-00-00', '\r', NULL),
(120, NULL, '70239758', 'MATEO CONDOR KEVIN ROLANDO', 'M', '933811699', '', '', '', '', '', '0000-00-00', 'D', 'C', 'DPW', '0000-00-00', '\r', NULL),
(121, NULL, '70242078', 'ROJAS ARAUJO MYSHELL ESTEFANNY', 'F', '967235683', '', '', '', '', '', '0000-00-00', 'A', 'C', 'OFI', '0000-00-00', '\r', NULL),
(122, NULL, '70346411', 'RAMOS TOVAR LIZBET KEENDY', 'F', '968443775', '', '', '', '', '', '0000-00-00', 'A', 'C', 'OFI', '0000-00-00', '\r', NULL),
(123, NULL, '70933255', 'CAJAHUAMAN MALLCO JAVIER', 'M', '910122259', '', '', '', '', '', '0000-00-00', 'D', 'C', 'DPW', '0000-00-00', '\r', NULL),
(124, NULL, '72623949', 'QUISPE CALDERON DAVID VICTOR', 'M', '953443064', '', '', '', '', '', '0000-00-00', 'D', 'C', 'ELA', '0000-00-00', '\r', NULL),
(125, NULL, '73047166', 'FERNANDEZ SINCHE ANGEL ELIAS', 'M', '918103393', '', '', '', '', '', '0000-00-00', 'S', 'C', 'MPI', '0000-00-00', '\r', NULL),
(126, NULL, '73891225', 'ZAMUDIO MARTEL ANTHONY RODRIGO', 'M', '940158606', '', '', '', '', '', '0000-00-00', 'D', 'C', 'TAQ', '0000-00-00', '\r', NULL),
(127, NULL, '76969226', 'PEREZ ZANABRIA MESIAS WILIAM', 'M', '995875333', '', '', '', '', '', '0000-00-00', 'S', 'C', 'DPW', '0000-00-00', '\r', NULL),
(128, NULL, '80559333', 'RAMOS RIVERA ERICA MAHENA', 'F', '915051041', '', '', '', '', '', '0000-00-00', 'A', 'N', 'TAD', '0000-00-00', '\r', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
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
  `fecha_actualizacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `ruc`, `razon_social`, `nombre_comercial`, `direccion_fiscal`, `telefono`, `email`, `sector`, `validado`, `registro_manual`, `estado`, `condicion_sunat`, `ubigeo`, `departamento`, `provincia`, `distrito`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, '20568733259', 'MYO LIBRA CONTRATISTAS EIRL', 'LIBRA CONTRATISTAS', 'AV. TAHUANTINSUYO N° 821 - EL TAMBO - HUANCAYO', '956260077', 'extraccionescachito@hotmail.com', 'CONSTRUCCIÓN', 1, 0, 'ACTIVO', 'HABIDO', '120125', 'JUNÍN', 'HUANCAYO', 'EL TAMBO', '2025-11-23 21:52:39', '2025-11-23 21:52:39'),
(2, '20100123456', 'TECNOLOGÍA AVANZADA SAC', 'TECNOAVANZADA', 'AV. REAL 123 - HUANCAYO', '964123456', 'info@tecnoavanzada.com', 'TECNOLOGÍA', 1, 0, 'ACTIVO', 'HABIDO', '120101', 'JUNÍN', 'HUANCAYO', 'HUANCAYO', '2025-11-23 21:52:39', '2025-11-23 21:52:39'),
(3, '20100234567', 'SOLUCIONES WEB EIRL', 'SOLWEB', 'JR. LIMA 456 - EL TAMBO', '965234567', 'contacto@solweb.com', 'DESARROLLO SOFTWARE', 1, 0, 'ACTIVO', 'HABIDO', '120125', 'JUNÍN', 'HUANCAYO', 'EL TAMBO', '2025-11-23 21:52:39', '2025-11-23 21:52:39'),
(4, '20100345678', 'INNOVACIÓN DIGITAL SAC', 'INNODIGITAL', 'AV. HUÁNCANO 789 - HUANCAYO', '966345678', 'innovacion@innodigital.com', 'TECNOLOGÍA', 1, 0, 'ACTIVO', 'HABIDO', '120101', 'JUNÍN', 'HUANCAYO', 'HUANCAYO', '2025-11-23 21:52:39', '2025-11-23 21:52:39'),
(5, '20100456789', 'SISTEMAS INTEGRALES EIRL', 'SISINTEGRAL', 'CALLE LOS ANDES 321 - CHILCA', '967456789', 'sistemas@sisintegral.com', 'INFORMÁTICA', 1, 0, 'ACTIVO', 'HABIDO', '120112', 'JUNÍN', 'HUANCAYO', 'CHILCA', '2025-11-23 21:52:39', '2025-11-23 21:52:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_convenio`
--

CREATE TABLE `estado_convenio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_renovacion`
--

CREATE TABLE `estado_renovacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

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
(10, NULL, '41394400', 'CAMAYO', 'ADRIANO', 'JOSE ALBERTO', 'M', '914162943', '', '', '', '', '41394400@institutocajas.edu.pe', '0000-00-00', '', NULL),
(11, NULL, '42370958', 'ROJAS', 'LUIS', 'ROBERTO ALEX', 'M', '941700588', '', '', '', '', 'sayd4237@gmail.com', '0000-00-00', '', NULL),
(12, NULL, '42409503', 'COSSIO', 'PARIONA', 'MARX', 'M', '953104387', '', '', '', '', '42409503@institutocajas.edu.pe', '0000-00-00', '', NULL),
(13, NULL, '42474078', 'GUZMAN', 'RAYMUNDO', 'FRAY FRANKLYN', 'M', '955211658', '', '', '', '', '42474078@institutocajas.edu.pe', '0000-00-00', '', NULL),
(14, NULL, '42511886', 'TOVAR', 'BARRERA', 'RICHAR', 'M', '941460803', '', '', '', '', '42511886@institutocajas.edu.pe', '0000-00-00', '', NULL),
(15, NULL, '43392963', 'LADRON DE GUEVARA', 'RIVEROS', 'ROSA MERCEDES', 'F', '976535401', '', '', '', '', '43392963@institutocajas.edu.pe', '0000-00-00', '', NULL),
(16, NULL, '43473716', 'ROJAS', 'ROJAS', 'YVES WILMER', 'M', '916096232', '', '', '', '', '43473716@institutocajas.edu.pe', '0000-00-00', '', NULL),
(17, NULL, '43546733', 'COCHACHI', 'REYES', 'KENNITH MILTON', 'M', '983220114', '', '', '', '', '43546733@institutocajas.edu.pe', '0000-00-00', '', NULL),
(18, NULL, '43812848', 'CANTE?O', 'YUPANQUI', 'EDSON QUINSINIO', 'M', '992273443', '', '', '', '', '43812848@institutocajas.edu.pe', '0000-00-00', '', NULL),
(19, NULL, '44489595', 'MEZA', 'ALFONZO', 'MAGDALENA SOLEDAD', 'F', '972809374', '', '', '', '', '44489595@institutocajas.edu.pe', '0000-00-00', '', NULL),
(20, NULL, '44764189', 'CASO', 'HINOSTROZA', 'MARVIN ANTONIO', 'M', '934110053', '', '', '', '', '44764189@institutocajas.edu.pe', '0000-00-00', '', NULL),
(21, NULL, '45757584', 'PABLO', 'MARCELO', 'HEDILBERTO HUBERTH', 'M', '963937976', '', '', '', '', '45757584@institutocajas.edu.pe', '0000-00-00', '', NULL),
(22, NULL, '46112372', 'CURO', 'GOMEZ', 'MARCELINO', 'M', '954937376', '', '', '', '', '46112372@institutocajas.edu.pe', '0000-00-00', '', NULL),
(23, NULL, '46535268', 'SANTIVA?EZ', 'SOTO', 'JAMES JESUS', 'M', '927866310', '', '', '', '', '46535268@institutocajas.edu.pe', '0000-00-00', '', NULL),
(24, NULL, '46812896', 'MIGUEL', 'HUAMANCAJA', 'MELISSA MIRELI', 'F', '923111071', '', '', '', '', '46812896@institutocajas.edu.pe', '0000-00-00', '', NULL),
(25, NULL, '46826842', 'GARCIA', 'GUERRERO', 'MICHAEL', 'M', '907778807', '', '', '', '', '46826842@institutocajas.edu.pe', '0000-00-00', '', NULL),
(26, NULL, '47262411', 'SOTO', 'MARAVI', 'ERIK', 'M', '964906669', '', '', '', '', '47262411@institutocajas.edu.pe', '0000-00-00', '', NULL),
(27, NULL, '47302789', 'CAMPOS', 'POCOMUCHA', 'HUGO JONER', 'M', '913298155', '', '', '', '', '47302789@institutocajas.edu.pe', '0000-00-00', '', NULL),
(28, NULL, '47338581', 'ARIAS', 'GONZALEZ', 'RENZO JOAQUIN ROGELIO', 'M', '918822092', '', '', '', '', '47338581@institutocajas.edu.pe', '0000-00-00', '', NULL),
(29, NULL, '47349124', 'LAZARO', 'ROJAS', 'HEBER ELMECIDEC', 'M', '938251629', '', '', '', '', '47349124@institutocajas.edu.pe', '0000-00-00', '', NULL),
(30, NULL, '47658790', 'RICALDI', 'ORE', 'SHEILA MARGOT', 'F', '948183437', '', '', '', '', '47658790@institutocajas.edu.pe', '0000-00-00', '', NULL),
(31, NULL, '47822503', 'GUTARRA', 'ROSALES', 'ALFREDO', 'M', '927728622', '', '', '', '', '47822503@institutocajas.edu.pe', '0000-00-00', '', NULL),
(32, NULL, '47863462', 'CARDENAS', 'SORIANO', 'LUCERO EVELYN', 'F', '910289998', '', '', '', '', '47863462@institutocajas.edu.pe', '0000-00-00', '', NULL),
(33, NULL, '48215915', 'FERNANDEZ', 'CLEMENTE', 'JON PAVEL', 'M', '926987466', '', '', '', '', '48215915@institutocajas.edu.pe', '0000-00-00', '', NULL),
(34, NULL, '48247702', 'DIAZ', 'GUTARRA', 'ALFREIRY JHORDY', 'M', '939454524', '', '', '', '', '48247702@institutocajas.edu.pe', '0000-00-00', '', NULL),
(35, NULL, '48594801', 'CASTA?EDA', 'GARCIA', 'LUIS PAUL', 'M', '985518624', '', '', '', '', '48594801@institutocajas.edu.pe', '0000-00-00', '', NULL),
(36, NULL, '48783504', 'MU?OZ', 'COTRINA', 'ABNER DARLIN', 'M', '951512636', '', '', '', '', '48783504@institutocajas.edu.pe', '0000-00-00', '', NULL),
(37, NULL, '60001847', 'RAMOS', 'SALTACHIN', 'LUIS MAYCOL', 'M', '960107841', '', '', '', '', '60001847@institutocajas.edu.pe', '0000-00-00', '', NULL),
(38, NULL, '60001925', 'CARHUAMACA', 'DE LA CRUZ', 'LEONARDO WILMER', 'M', '980426051', '', '', '', '', '60001925@institutocajas.edu.pe', '0000-00-00', '', NULL),
(39, NULL, '60001952', 'GAYTAN', 'CONGORA', 'YOSHIO MAEL', 'M', '933816470', '', '', '', '', '60001952@institutocajas.edu.pe', '0000-00-00', '', NULL),
(40, NULL, '60002086', 'ALVARADO', 'REYES', 'MAECOL MICHAEL', 'M', '976274972', '', '', '', '', '60002086@institutocajas.edu.pe', '0000-00-00', '', NULL),
(41, NULL, '60002330', 'ROMERO', 'HINOSTROZA', 'EMERSON', 'M', '952040193', '', '', '', '', '60002330@institutocajas.edu.pe', '0000-00-00', '', NULL),
(42, NULL, '60002459', 'PAEZ', 'RIOS', 'JEFERSON', 'M', '913013139', '', '', '', '', '60002459@institutocajas.edu.pe', '0000-00-00', '', NULL),
(43, NULL, '60002461', 'SULLCA', 'BRAVO', 'JHOJAN', 'M', '907218425', '', '', '', '', '60002461@institutocajas.edu.pe', '0000-00-00', '', NULL),
(44, NULL, '60002467', 'HUALLPA', 'ROJAS', 'ANGEL JOSMELL', 'M', '957095536', '', '', '', '', '60002467@institutocajas.edu.pe', '0000-00-00', '', NULL),
(45, NULL, '60002490', 'HERRERA', 'ROJAS', 'ANTHONY', 'M', '939991400', '', '', '', '', '60002490@institutocajas.edu.pe', '0000-00-00', '', NULL),
(46, NULL, '60002550', 'RIOS', 'HUAMAN', 'ADAN FRANCY', 'M', '922421908', '', '', '', '', '60002550@institutocajas.edu.pe', '0000-00-00', '', NULL),
(47, NULL, '60002571', 'CAJA', 'MANTARI', 'VICENTE ABDY', 'M', '929636143', '', '', '', '', '60002571@institutocajas.edu.pe', '0000-00-00', '', NULL),
(48, NULL, '60002601', 'CASTRO', 'MORALES', 'LEONEL', 'M', '969660433', '', '', '', '', '60002601@institutocajas.edu.pe', '0000-00-00', '', NULL),
(49, NULL, '60002697', 'DIAZ', 'PEREZ', 'MELODY NUVIEL', 'F', '949988952', '', '', '', '', '60002697@institutocajas.edu.pe', '0000-00-00', '', NULL),
(50, NULL, '60002715', 'MU?OZ', 'RODRIGUEZ', 'DILMER SLEE', 'M', '962265869', '', '', '', '', '60002715@institutocajas.edu.pe', '0000-00-00', '', NULL),
(51, NULL, '60002819', 'CARBAJAL', 'MEZA', 'JUAN MIGUEL', 'M', '944868633', '', '', '', '', '60002819@institutocajas.edu.pe', '0000-00-00', '', NULL),
(52, NULL, '60002950', 'HIDALGO', 'MAYTA', 'ANYELO ARTURO', 'M', '976907382', '', '', '', '', '60002950@institutocajas.edu.pe', '0000-00-00', '', NULL),
(53, NULL, '60002968', 'MACHA', 'JAUREGUI', 'DEIVIT MANUEL', 'M', '938749596', '', '', '', '', '60002968@institutocajas.edu.pe', '0000-00-00', '', NULL),
(54, NULL, '60003981', 'REZA', 'APUMAYTA', 'ERIKC PERCY', 'M', '987572328', '', '', '', '', '60003981@institutocajas.edu.pe', '0000-00-00', '', NULL),
(55, NULL, '60004106', 'HUAROC', 'FLORES', 'DANIEL', 'M', '998309236', '', '', '', '', '60004106@institutocajas.edu.pe', '0000-00-00', '', NULL),
(56, NULL, '60004509', 'PRUDENCIO', 'POCOMUCHA', 'FELIX ANNDY', 'M', '929472286', '', '', '', '', '60004509@institutocajas.edu.pe', '0000-00-00', '', NULL),
(57, NULL, '60004567', 'GARCIA', 'ACU?A', 'EDITH', 'F', '910293404', '', '', '', '', '60004567@institutocajas.edu.pe', '0000-00-00', '', NULL),
(58, NULL, '60004860', 'BUITRON', 'BRICE?O', 'PEDRO', 'M', '991884088', '', '', '', '', '60004860@institutocajas.edu.pe', '0000-00-00', '', NULL),
(59, NULL, '60004938', 'HUAMAN', 'MEZA', 'ANYELO', 'M', '925568506', '', '', '', '', '60004938@institutocajas.edu.pe', '0000-00-00', '', NULL),
(60, NULL, '60005056', 'PACHECO', 'CHANCA', 'JOSE GABRIEL', 'M', '998976980', '', '', '', '', '60005056@institutocajas.edu.pe', '0000-00-00', '', NULL),
(61, NULL, '60005126', 'BALVIN', 'VILLEGAS', 'DENIS ENIL', 'M', '954167759', '', '', '', '', '60005126@institutocajas.edu.pe', '0000-00-00', '', NULL),
(62, NULL, '60005203', 'VILCAPOMA', 'CUYUTUPA', 'ANDREA VALIA', 'F', '948139334', '', '', '', '', '60005203@institutocajas.edu.pe', '0000-00-00', '', NULL),
(63, NULL, '60005225', 'LULO', 'VASQUEZ', 'BRANDOL JHOLVY', 'M', '901855369', '', '', '', '', '60005225@institutocajas.edu.pe', '0000-00-00', '', NULL),
(64, NULL, '60005358', 'SALOME', 'ALCOSER', 'MICHEL MIGUEL', 'M', '967951122', '', '', '', '', '60005358@institutocajas.edu.pe', '0000-00-00', '', NULL),
(65, NULL, '60005363', 'MU?OZ', 'RODRIGUEZ', 'JHANPOOL RUSBEL', 'M', '959320707', '', '', '', '', '60005363@institutocajas.edu.pe', '0000-00-00', '', NULL),
(66, NULL, '60005372', 'LORO?A', 'PAPUICO', 'JHOCHYRA MEGA', 'F', '991500972', '', '', '', '', '60005372@institutocajas.edu.pe', '0000-00-00', '', NULL),
(67, NULL, '60005376', 'HUATUCO', 'TORRES', 'GIMMI DANILO', 'M', '953067068', '', '', '', '', '60005376@institutocajas.edu.pe', '0000-00-00', '', NULL),
(68, NULL, '60005485', 'VILLA', 'JACOBI', 'RUTT KATERIN', 'F', '928812621', '', '', '', '', '60005485@institutocajas.edu.pe', '0000-00-00', '', NULL),
(69, NULL, '60005561', 'CARDENAS', 'SALAZAR', 'ALEXANDER VICTOR', 'M', '970093617', '', '', '', '', '60005561@institutocajas.edu.pe', '0000-00-00', '', NULL),
(70, NULL, '60005582', 'LORO?A', 'MONTA?EZ', 'HERMAN PAOLO', 'M', '935733119', '', '', '', '', '60005582@institutocajas.edu.pe', '0000-00-00', '', NULL),
(71, NULL, '60005614', 'HINOSTROZA', 'OCA?O', 'JESSICA ROSY', 'F', '943731831', '', '', '', '', '60005614@institutocajas.edu.pe', '0000-00-00', '', NULL),
(72, NULL, '60005626', 'CHURAMPI', 'PAULINO', 'JORDY EDILBERTO', 'M', '987740286', '', '', '', '', '60005626@institutocajas.edu.pe', '0000-00-00', '', NULL),
(73, NULL, '60005628', 'ALVINAGORTA', 'REYES', 'REY JHUNIOR', 'M', '995808533', '', '', '', '', '60005628@institutocajas.edu.pe', '0000-00-00', '', NULL),
(74, NULL, '60005638', 'MEZA', 'COLONIO', 'GERARDO ANTONIO', 'M', '999999999', '', '', '', '', '60005638@institutocajas.edu.pe', '0000-00-00', '', NULL),
(75, NULL, '60005669', 'OSORIO', 'MONTES', 'XIOMARA ROUSSE', 'F', '961497900', '', '', '', '', '60005669@institutocajas.edu.pe', '0000-00-00', '', NULL),
(76, NULL, '60005680', 'SUAREZ', 'MIGUEL', 'WILDER RONALDO', 'M', '994911394', '', '', '', '', '60005680@institutocajas.edu.pe', '0000-00-00', '', NULL),
(77, NULL, '60005687', 'LANDA', 'MARTINEZ', 'JORGE JUNIOR', 'M', '948351609', '', '', '', '', '60005687@institutocajas.edu.pe', '0000-00-00', '', NULL),
(78, NULL, '60005716', 'ZAMUDIO', 'JUAN DE DIOS', 'MAYLIN MARY', 'F', '952374069', '', '', '', '', '60005716@institutocajas.edu.pe', '0000-00-00', '', NULL),
(79, NULL, '60005719', 'DE LA CRUZ', 'QUISPE', 'ANTHONY ALVARO', 'M', '912568322', '', '', '', '', '60005719@institutocajas.edu.pe', '0000-00-00', '', NULL),
(80, NULL, '60006543', 'GABRIEL', 'VASQUEZ', 'JORDY JOSTYRS', 'M', '947516683', '', '', '', '', '60006543@institutocajas.edu.pe', '0000-00-00', '', NULL),
(81, NULL, '60007254', 'TENICELA', 'ESPEJO', 'HAIR JARED', 'M', '973143243', '', '', '', '', '60007254@institutocajas.edu.pe', '0000-00-00', '', NULL),
(82, NULL, '60007263', 'ALVAREZ', 'VASQUEZ', 'JOSE ALEX', 'M', '904771772', '', '', '', '', '60007263@institutocajas.edu.pe', '0000-00-00', '', NULL),
(83, NULL, '60007331', 'ORDO?EZ', 'ALANYA', 'JESUS FRANCIS', 'M', '955177941', '', '', '', '', '60007331@institutocajas.edu.pe', '0000-00-00', '', NULL),
(84, NULL, '60007480', 'MILLAN', 'VELIZ', 'ROSMERY JUDITH', 'F', '934832155', '', '', '', '', '60007480@institutocajas.edu.pe', '0000-00-00', '', NULL),
(85, NULL, '60007607', 'BALDEON', 'MONTEJO', 'DANER ANGEL', 'M', '939229168', '', '', '', '', '60007607@institutocajas.edu.pe', '0000-00-00', '', NULL),
(86, NULL, '60008214', 'TORRES', 'ZAPATA', 'SANZ REYNALDO', 'M', '957028339', '', '', '', '', '60008214@institutocajas.edu.pe', '0000-00-00', '', NULL),
(87, NULL, '60008661', 'MERMA', 'SANDOVAL', 'JHOEL JHARED', 'M', '962241258', '', '', '', '', '60008661@institutocajas.edu.pe', '0000-00-00', '', NULL),
(88, NULL, '60008715', 'MALLMA', 'LOBOS', 'EIMY NAYELI', 'F', '969500132', '', '', '', '', '60008715@institutocajas.edu.pe', '0000-00-00', '', NULL),
(89, NULL, '60011654', 'PORRAS', 'TORRES', 'EVELYN DOLORES', 'F', '943276791', '', '', '', '', '60011654@institutocajas.edu.pe', '0000-00-00', '', NULL),
(90, NULL, '60011701', 'RAVICHAGUA', 'VILLAJUAN', 'YENIFER MARILI', 'F', '998955252', '', '', '', '', '60011701@institutocajas.edu.pe', '0000-00-00', '', NULL),
(91, NULL, '60011734', 'POMA', 'REYES', 'DILTON ALBERT', 'M', '947750214', '', '', '', '', '60011734@institutocajas.edu.pe', '0000-00-00', '', NULL),
(92, NULL, '60011836', 'ESPIRITU', 'APOLINARIO', 'DIEGO ARMANDO', 'M', '936937966', '', '', '', '', '60011836@institutocajas.edu.pe', '0000-00-00', '', NULL),
(93, NULL, '60034786', 'LUNA', 'QUISPE', 'FRAITKER KEN', 'M', '925493582', '', '', '', '', '60034786@institutocajas.edu.pe', '0000-00-00', '', NULL),
(94, NULL, '60047282', 'SULLCA', 'OLARTE', 'BRAYAN WILDER', 'M', '964176520', '', '', '', '', '60047282@institutocajas.edu.pe', '0000-00-00', '', NULL),
(95, NULL, '60047289', 'LUJAN', 'SULLCA', 'SERGIO', 'M', '914016350', '', '', '', '', '60047289@institutocajas.edu.pe', '0000-00-00', '', NULL),
(96, NULL, '60047359', 'CRISPIN', 'JAVIER', 'GUYEN ULISES', 'M', '986914834', '', '', '', '', '60047359@institutocajas.edu.pe', '0000-00-00', '', NULL),
(97, NULL, '60047648', 'VILCA', 'CUSI', 'ARMANDO', 'M', '934632843', '', '', '', '', '60047648@institutocajas.edu.pe', '0000-00-00', '', NULL),
(98, NULL, '60077940', 'HERRERA', 'ROQUE', 'FLOR ASTRID', 'F', '943369078', '', '', '', '', '60077940@institutocajas.edu.pe', '0000-00-00', '', NULL),
(99, NULL, '60078611', 'DIONISIO', 'MELO', 'MICHEL ANTHONY', 'M', '973291016', '', '', '', '', '60078611@institutocajas.edu.pe', '0000-00-00', '', NULL),
(100, NULL, '60080312', 'CORNEJO', 'PEREZ', 'DAVID ANTHONY', 'M', '959393043', '', '', '', '', '60080312@institutocajas.edu.pe', '0000-00-00', '', NULL),
(101, NULL, '60080387', 'SANCHEZ', 'ZAMBRANO', 'MAX ANTHONY', 'M', '982107101', '', '', '', '', '60080387@institutocajas.edu.pe', '0000-00-00', '', NULL),
(102, NULL, '60082025', 'QUISPE', 'SANCHEZ', 'RONY', 'M', '917097910', '', '', '', '', '60082025@institutocajas.edu.pe', '0000-00-00', '', NULL),
(103, NULL, '60082688', 'INGA', 'CONDORI', 'EDWIN', 'M', '901755636', '', '', '', '', '60082688@institutocajas.edu.pe', '0000-00-00', '', NULL),
(104, NULL, '60085025', 'MARAVI', 'ARMAS', 'CARLOS RAUL', 'M', '912628510', '', '', '', '', '60085025@institutocajas.edu.pe', '0000-00-00', '', NULL),
(105, NULL, '60085111', 'LIZARRAGA', 'LAZARO', 'SAUL GENME', 'M', '918260187', '', '', '', '', '60085111@institutocajas.edu.pe', '0000-00-00', '', NULL),
(106, NULL, '60085113', 'LORO?A', 'LINO', 'YENSO ALISON', 'M', '918069159', '', '', '', '', '60085113@institutocajas.edu.pe', '0000-00-00', '', NULL),
(107, NULL, '60085115', 'ALCANTARA', 'CAJA', 'YHENNER YEINS', 'M', '927435682', '', '', '', '', '60085115@institutocajas.edu.pe', '0000-00-00', '', NULL),
(108, NULL, '60085130', 'BARJA', 'HUARINGA', 'YOHAN ANDERSON', 'M', '900840458', '', '', '', '', '60085130@institutocajas.edu.pe', '0000-00-00', '', NULL),
(109, NULL, '60085157', 'SUAREZ', 'REYES', 'ELIZABETH YONILDA', 'F', '991082652', '', '', '', '', '60085157@institutocajas.edu.pe', '0000-00-00', '', NULL),
(110, NULL, '60085225', 'GUERRA', 'HERRERA', 'JUVENAL', 'M', '962936930', '', '', '', '', '60085225@institutocajas.edu.pe', '0000-00-00', '', NULL),
(111, NULL, '60085240', 'HUAMAN', 'VALERO', 'RONALDO RONALDI?O', 'M', '901164162', '', '', '', '', '60085240@institutocajas.edu.pe', '0000-00-00', '', NULL),
(112, NULL, '60085281', 'ROJAS', 'SUAZO', 'ROBER', 'M', '912248388', '', '', '', '', '60085281@institutocajas.edu.pe', '0000-00-00', '', NULL),
(113, NULL, '60085302', 'DAVALOS', 'AGUILAR', 'JOSUE RAFAEL', 'M', '992560990', '', '', '', '', '60085302@institutocajas.edu.pe', '0000-00-00', '', NULL),
(114, NULL, '60090778', 'ROMERO', 'BERROCAL', 'POWEL', 'M', '900643621', '', '', '', '', '60090778@institutocajas.edu.pe', '0000-00-00', '', NULL),
(115, NULL, '60091022', 'DE LA CRUZ', 'ADRIANO', 'RAUL', 'M', '962288829', '', '', '', '', '60091022@institutocajas.edu.pe', '0000-00-00', '', NULL),
(116, NULL, '60091605', 'LAPA', 'ALLCA', 'RUBIEL RONEL', 'M', '969482799', '', '', '', '', '60091605@institutocajas.edu.pe', '0000-00-00', '', NULL),
(117, NULL, '60092102', 'CORONEL', 'SAPALLANAY', 'MAYCOL ROMEL', 'M', '922049644', '', '', '', '', '60092102@institutocajas.edu.pe', '0000-00-00', '', NULL),
(118, NULL, '60096069', 'LAUREANO', 'DE LA CRUZ', 'JORGE LUIS', 'M', '921066083', '', '', '', '', '60096069@institutocajas.edu.pe', '0000-00-00', '', NULL),
(119, NULL, '60096086', 'MU?OZ', 'CERRON', 'JEMERSON YOEL', 'M', '928265750', '', '', '', '', '60096086@institutocajas.edu.pe', '0000-00-00', '', NULL),
(120, NULL, '60097505', 'MEDINA', 'TIBURCIO', 'MANUEL ADAN', 'M', '916558669', '', '', '', '', '60097505@institutocajas.edu.pe', '0000-00-00', '', NULL),
(121, NULL, '60100853', 'GOMEZ', 'CHOMBO', 'JIAMPIER HENRRY', 'M', '930378240', '', '', '', '', '60100853@institutocajas.edu.pe', '0000-00-00', '', NULL),
(122, NULL, '60100873', 'CONDOR', 'GOMEZ', 'SHAIR EMANUEL', 'M', '930951970', '', '', '', '', '60100873@institutocajas.edu.pe', '0000-00-00', '', NULL),
(123, NULL, '60100950', 'CAPCHA', 'ADVINCULA', 'EVELIN NOEMI', 'F', '954738955', '', '', '', '', '60100950@institutocajas.edu.pe', '0000-00-00', '', NULL),
(124, NULL, '60101114', 'RAMOS', 'ROJAS', 'CRISTIAN MANUEL', 'M', '921547796', '', '', '', '', '60101114@institutocajas.edu.pe', '0000-00-00', '', NULL),
(125, NULL, '60106767', 'ROMERO', 'GOMEZ', 'OMAR ANTONY', 'M', '921056979', '', '', '', '', '60106767@institutocajas.edu.pe', '0000-00-00', '', NULL),
(126, NULL, '60106822', 'GUTIERREZ', 'MATOS', 'ANDERSON KENIDY', 'M', '917627243', '', '', '', '', '60106822@institutocajas.edu.pe', '0000-00-00', '', NULL),
(127, NULL, '60106852', 'DE LA CRUZ', 'SULLCA', 'YEFERSON', 'M', '907533887', '', '', '', '', '60106852@institutocajas.edu.pe', '0000-00-00', '', NULL),
(128, NULL, '60109217', 'YARASCA', 'ROJAS', 'DAMIAN ANGELO', 'M', '935709775', '', '', '', '', '60109217@institutocajas.edu.pe', '0000-00-00', '', NULL),
(129, NULL, '60145213', 'LLANTOY', 'CCORAHUA', 'JOSE MIGUEL', 'M', '921516201', '', '', '', '', '60145213@institutocajas.edu.pe', '0000-00-00', '', NULL),
(130, NULL, '60145226', 'TAYPE', 'QUICHCA', 'IS?IEL JHOANESS', 'M', '951440694', '', '', '', '', '60145226@institutocajas.edu.pe', '0000-00-00', '', NULL),
(131, NULL, '60146531', 'MURILLO', 'CHAVEZ', 'ROMEL ANTONY', 'M', '999044052', '', '', '', '', '60146531@institutocajas.edu.pe', '0000-00-00', '', NULL),
(132, NULL, '60147920', 'SALAZAR', 'CARDENAS', 'ELIAN CARLOS', 'M', '968898333', '', '', '', '', '60147920@institutocajas.edu.pe', '0000-00-00', '', NULL),
(133, NULL, '60197259', 'SULLCARAY', 'TORRE', 'MARCELINO', 'M', '995291360', '', '', '', '', '60197259@institutocajas.edu.pe', '0000-00-00', '', NULL),
(134, NULL, '60197269', 'FERNANDEZ', 'TORRE', 'YOEL NEFTALI', 'M', '918190986', '', '', '', '', '60197269@institutocajas.edu.pe', '0000-00-00', '', NULL),
(135, NULL, '60199769', 'LAZO', 'GOMEZ', 'CARLOS ALFREDO', 'M', '912247852', '', '', '', '', '60199769@institutocajas.edu.pe', '0000-00-00', '', NULL),
(136, NULL, '60199772', 'RECUAY', '?AUPARI', 'ROBERT JUNIOR', 'M', '979537374', '', '', '', '', '60199772@institutocajas.edu.pe', '0000-00-00', '', NULL),
(137, NULL, '60199787', 'HERRERA', 'GONZALES', 'ROBER BRAYAN', 'M', '992682483', '', '', '', '', '60199787@institutocajas.edu.pe', '0000-00-00', '', NULL),
(138, NULL, '60199795', 'GRANADOS', 'MARQUEZ', 'ALEXANDER OMAR', 'M', '999999999', '', '', '', '', '60199795@institutocajas.edu.pe', '0000-00-00', '', NULL),
(139, NULL, '60199798', 'ZEGARRA', 'HUARCAYA', 'JHEMES ISNAIDER', 'M', '999999999', '', '', '', '', '60199798@institutocajas.edu.pe', '0000-00-00', '', NULL),
(140, NULL, '60208444', 'GUERREROS', 'RICRA', 'JUAN SEBASTHIAN', 'M', '968243589', '', '', '', '', '60208444@institutocajas.edu.pe', '0000-00-00', '', NULL),
(141, NULL, '60210099', 'SULLCA', 'HUACHOHUILLCA', 'ANDERSON', 'M', '989107636', '', '', '', '', '60210099@institutocajas.edu.pe', '0000-00-00', '', NULL),
(142, NULL, '60210188', 'MACHACUAY', 'INCHE', 'JEAMPIERE', 'M', '929783373', '', '', '', '', '60210188@institutocajas.edu.pe', '0000-00-00', '', NULL),
(143, NULL, '60210534', 'VILCA', 'RICRA', 'CARLA VANESA', 'F', '900705863', '', '', '', '', '60210534@institutocajas.edu.pe', '0000-00-00', '', NULL),
(144, NULL, '60211608', 'PONCE', 'SAMANIEGO', 'JHON ALFREDO', 'M', '932900363', '', '', '', '', '60211608@institutocajas.edu.pe', '0000-00-00', '', NULL),
(145, NULL, '60211620', 'VASQUEZ', 'CANO', 'GILDYANA ESPERANZA', 'F', '907845482', '', '', '', '', '60211620@institutocajas.edu.pe', '0000-00-00', '', NULL),
(146, NULL, '60211642', 'CRISTOBAL', 'TORPOCO', 'MISHELL MARIA DE LOS ANGELES', 'F', '922512543', '', '', '', '', '60211642@institutocajas.edu.pe', '0000-00-00', '', NULL),
(147, NULL, '60213242', 'MONGE', 'ALANYA', 'JHEFERSON MARI?O', 'M', '956388398', '', '', '', '', '60213242@institutocajas.edu.pe', '0000-00-00', '', NULL),
(148, NULL, '60214106', 'CARBAJAL', 'PARADO', 'ROY IVAN', 'M', '923450844', '', '', '', '', '60214106@institutocajas.edu.pe', '0000-00-00', '', NULL),
(149, NULL, '60214116', 'VELIZ', 'CAJA', 'JHANELY RUTH', 'F', '948415965', '', '', '', '', '60214116@institutocajas.edu.pe', '0000-00-00', '', NULL),
(150, NULL, '60214128', 'YAROPOMA', 'SOTO', 'FRANS JHONEL', 'M', '997157648', '', '', '', '', '60214128@institutocajas.edu.pe', '0000-00-00', '', NULL),
(151, NULL, '60214182', 'ROMERO', 'CORDOVA', 'JHONATAN DAVID', 'M', '990618919', '', '', '', '', '60214182@institutocajas.edu.pe', '0000-00-00', '', NULL),
(152, NULL, '60219350', 'ORE', 'PEREZ', 'FRANK JHEREMY', 'M', '924944727', '', '', '', '', '60219350@institutocajas.edu.pe', '0000-00-00', '', NULL),
(153, NULL, '60219373', 'ORE', 'SANCHEZ', 'GUENADI SAID', 'M', '964097437', '', '', '', '', '60219373@institutocajas.edu.pe', '0000-00-00', '', NULL),
(154, NULL, '60219380', 'CHUQUICHAICO', 'ROJAS', 'DANIEL JOEL', 'M', '916620322', '', '', '', '', '60219380@institutocajas.edu.pe', '0000-00-00', '', NULL),
(155, NULL, '60219385', 'PAITAN', 'CHAHUA', 'PATRIK JEFFREY', 'M', '916473844', '', '', '', '', '60219385@institutocajas.edu.pe', '0000-00-00', '', NULL),
(156, NULL, '60219394', 'PAITAMPOMA', 'PALACIOS', 'EVER SELMER', 'M', '981909679', '', '', '', '', '60219394@institutocajas.edu.pe', '0000-00-00', '', NULL),
(157, NULL, '60222716', 'TELLO', 'PICHARDO', 'SAUL', 'M', '950864120', '', '', '', '', '60222716@institutocajas.edu.pe', '0000-00-00', '', NULL),
(158, NULL, '60222720', 'SAYME', 'CUSICHE', 'ABEL JESUS', 'M', '927187518', '', '', '', '', '60222720@institutocajas.edu.pe', '0000-00-00', '', NULL),
(159, NULL, '60224353', 'PAQUIYAURI', 'APARCO', 'JUAN ANGEL', 'M', '900794320', '', '', '', '', '60224353@institutocajas.edu.pe', '0000-00-00', '', NULL),
(160, NULL, '60228967', 'CAMAYO', 'ARTICA', 'JEFFERSON', 'M', '974208316', '', '', '', '', '60228967@institutocajas.edu.pe', '0000-00-00', '', NULL),
(161, NULL, '60232954', 'VELASQUEZ', 'ALANYA', 'JOSEMANUEL JENS', 'M', '983917828', '', '', '', '', '60232954@institutocajas.edu.pe', '0000-00-00', '', NULL),
(162, NULL, '60232982', 'ESPINOZA', 'RAMOS', 'MIRIAM ANGELINE', 'F', '975864016', '', '', '', '', '60232982@institutocajas.edu.pe', '0000-00-00', '', NULL),
(163, NULL, '60232991', 'ROJAS', 'RIOS', 'LUDWIN PEDRO', 'M', '975652055', '', '', '', '', '60232991@institutocajas.edu.pe', '0000-00-00', '', NULL),
(164, NULL, '60235260', 'PAEZ', 'DAVILA', 'JAROL ESMITH', 'M', '906665619', '', '', '', '', '60235260@institutocajas.edu.pe', '0000-00-00', '', NULL),
(165, NULL, '60236086', 'PAPUICO', 'PONCE', 'BRIYITH KIMBERLY', 'F', '907119469', '', '', '', '', '60236086@institutocajas.edu.pe', '0000-00-00', '', NULL),
(166, NULL, '60237572', 'SALINAS', 'TENORIO', 'EMANUEL MARVIN', 'M', '901069773', '', '', '', '', '60237572@institutocajas.edu.pe', '0000-00-00', '', NULL),
(167, NULL, '60237599', 'VELIZ', 'HUANCA', 'ASHLEY ALESSANDRO', 'M', '936043753', '', '', '', '', '60237599@institutocajas.edu.pe', '0000-00-00', '', NULL),
(168, NULL, '60240757', 'INGA', 'CURASMA', 'EVELYN MAILY', 'F', '931620865', '', '', '', '', '60240757@institutocajas.edu.pe', '0000-00-00', '', NULL),
(169, NULL, '60241850', 'FIGUEROA', 'LIZARRAGA', 'LUIS CARLOS', 'M', '962646109', '', '', '', '', '60241850@institutocajas.edu.pe', '0000-00-00', '', NULL),
(170, NULL, '60241878', 'LAUREANO', 'QUINTO', 'ALEQUES SAMUEL', 'M', '940986696', '', '', '', '', '60241878@institutocajas.edu.pe', '0000-00-00', '', NULL),
(171, NULL, '60252378', 'GALLEGOS', 'INGA', 'ALEX MISAEL', 'M', '953232173', '', '', '', '', '60252378@institutocajas.edu.pe', '0000-00-00', '', NULL),
(172, NULL, '60252833', 'DE LA CRUZ', 'LOPEZ', 'ROSA MARIA', 'F', '964736030', '', '', '', '', '60252833@institutocajas.edu.pe', '0000-00-00', '', NULL),
(173, NULL, '60252847', 'TIRACCAYA', 'ALANYA', 'KEMBERLY JOSELYN', 'F', '965625159', '', '', '', '', '60252847@institutocajas.edu.pe', '0000-00-00', '', NULL),
(174, NULL, '60252848', 'SOTELO', 'ALANYA', 'AGUSTIN CRISOSTOMO', 'M', '942238742', '', '', '', '', '60252848@institutocajas.edu.pe', '0000-00-00', '', NULL),
(175, NULL, '60253612', 'CARHUAVILCA', 'CANICELA', 'WALTER SAMIR', 'M', '910255971', '', '', '', '', '60253612@institutocajas.edu.pe', '0000-00-00', '', NULL),
(176, NULL, '60253698', 'ARCA', 'DELAO', 'SERGIO LUIS', 'M', '962205139', '', '', '', '', '60253698@institutocajas.edu.pe', '0000-00-00', '', NULL),
(177, NULL, '60253699', 'ORTIZ', 'ALIAGA', 'DARIN ANGELA', 'F', '932466040', '', '', '', '', '60253699@institutocajas.edu.pe', '0000-00-00', '', NULL),
(178, NULL, '60256052', 'UNSIHUAY', 'OBREGON', 'LUIS ARMANDO', 'M', '941004196', '', '', '', '', '60256052@institutocajas.edu.pe', '0000-00-00', '', NULL),
(179, NULL, '60256074', 'GUTIERREZ', 'BARZOLA', 'ZUKER ESMIT', 'M', '901852903', '', '', '', '', '60256074@institutocajas.edu.pe', '0000-00-00', '', NULL),
(180, NULL, '60256087', 'YAURI', 'UBALDO', 'MARIA ISABEL', 'F', '917145768', '', '', '', '', '60256087@institutocajas.edu.pe', '0000-00-00', '', NULL),
(181, NULL, '60256090', 'HUAMAN', 'CHIPANA', 'MIJAEL JAIRO', 'M', '948488994', '', '', '', '', '60256090@institutocajas.edu.pe', '0000-00-00', '', NULL),
(182, NULL, '60257392', 'CHAVEZ', 'CASO', 'JONEL ERIK', 'M', '959012033', '', '', '', '', '60257392@institutocajas.edu.pe', '0000-00-00', '', NULL),
(183, NULL, '60259803', 'HUAIRA', 'BOZA', 'ORLAND RIK', 'M', '980992140', '', '', '', '', '60259803@institutocajas.edu.pe', '0000-00-00', '', NULL),
(184, NULL, '60260911', 'LIMACHE', 'VARGAS', 'YEFERSON JAVIER', 'M', '946647766', '', '', '', '', '60260911@institutocajas.edu.pe', '0000-00-00', '', NULL),
(185, NULL, '60263332', 'ARIAS', 'ANTONIO', 'GEREMY ALEXANDER', 'M', '932439934', '', '', '', '', '60263332@institutocajas.edu.pe', '0000-00-00', '', NULL),
(186, NULL, '60263769', 'AGUILA', 'DE LA CRUZ', 'YAX MICHAEL', 'M', '934687597', '', '', '', '', '60263769@institutocajas.edu.pe', '0000-00-00', '', NULL),
(187, NULL, '60273060', 'LAUREANO', 'DE LA CRUZ', 'MAYRA LIZETH', 'F', '927364512', '', '', '', '', '60273060@institutocajas.edu.pe', '0000-00-00', '', NULL),
(188, NULL, '60273220', 'CONTRERAS', 'HERRERA', 'JHONY JIMY', 'M', '992534465', '', '', '', '', '60273220@institutocajas.edu.pe', '0000-00-00', '', NULL),
(189, NULL, '60273239', 'HUAYTA', 'INGA', 'RONALDI?O', 'M', '999999999', '', '', '', '', '60273239@institutocajas.edu.pe', '0000-00-00', '', NULL),
(190, NULL, '60281811', 'MARCA?AUPA', 'GUERRA', 'ALDAIR MICHAEL', 'M', '921520415', '', '', '', '', '60281811@institutocajas.edu.pe', '0000-00-00', '', NULL),
(191, NULL, '60281815', 'FANO', 'IGNACIO', 'WILDER MAYCOL', 'M', '964145548', '', '', '', '', '60281815@institutocajas.edu.pe', '0000-00-00', '', NULL),
(192, NULL, '60285884', 'MAURI', 'CACERES', 'YEREMY YOJHAN', 'M', '992099495', '', '', '', '', '60285884@institutocajas.edu.pe', '0000-00-00', '', NULL),
(193, NULL, '60286560', 'APOLINAREZ', 'CARHUAS', 'EUSEBIO DESIDERIO', 'M', '920153866', '', '', '', '', '60286560@institutocajas.edu.pe', '0000-00-00', '', NULL),
(194, NULL, '60299780', 'CRISPIN', 'AVILA', 'ZUMILINDA DAMARIS', 'F', '957678221', '', '', '', '', '60299780@institutocajas.edu.pe', '0000-00-00', '', NULL),
(195, NULL, '60301130', 'LIMACHE', 'JANAMPA', 'KEINER JOSE', 'M', '981898354', '', '', '', '', '60301130@institutocajas.edu.pe', '0000-00-00', '', NULL),
(196, NULL, '60302767', 'ARMAS', 'PAYANO', 'ENOC ELIAS', 'M', '935701309', '', '', '', '', '60302767@institutocajas.edu.pe', '0000-00-00', '', NULL),
(197, NULL, '60303384', 'QUISPE', 'CASTRO', 'RUTH KARINA', 'F', '935097754', '', '', '', '', '60303384@institutocajas.edu.pe', '0000-00-00', '', NULL),
(198, NULL, '60303435', 'POMA', 'ANTIALON', 'JORDAN JESUS', 'M', '920208922', '', '', '', '', '60303435@institutocajas.edu.pe', '0000-00-00', '', NULL),
(199, NULL, '60317976', 'CUCHULA', 'HUACHO', 'LIZANDRO', 'M', '974868989', '', '', '', '', '60317976@institutocajas.edu.pe', '0000-00-00', '', NULL),
(200, NULL, '60328783', 'CORDOVA', 'CERRON', 'LUIS VICTOR', 'M', '943167532', '', '', '', '', '60328783@institutocajas.edu.pe', '0000-00-00', '', NULL),
(201, NULL, '60330122', 'CONTRERAS', 'PALOMINO', 'MARCO ANTONIO', 'M', '933658589', '', '', '', '', '60330122@institutocajas.edu.pe', '0000-00-00', '', NULL),
(202, NULL, '60330125', 'HUACHUHUILLCA', 'CAMPOS', 'JOSE MARCELO', 'M', '994700052', '', '', '', '', '60330125@institutocajas.edu.pe', '0000-00-00', '', NULL),
(203, NULL, '60330326', 'MONTA?EZ', 'PARIONA', 'AZUMI LIDIA', 'F', '910608318', '', '', '', '', '60330326@institutocajas.edu.pe', '0000-00-00', '', NULL),
(204, NULL, '60335156', 'GACHA', 'TUCTO', 'MARY NELIA', 'F', '989822606', '', '', '', '', '60335156@institutocajas.edu.pe', '0000-00-00', '', NULL),
(205, NULL, '60335374', 'PURIS', 'VARGAS', 'JHORDAN RONALDO', 'M', '906239896', '', '', '', '', '60335374@institutocajas.edu.pe', '0000-00-00', '', NULL),
(206, NULL, '60342264', 'VALERO', 'ZAMUDIO', 'MAX ANGEL', 'M', '988606102', '', '', '', '', '60342264@institutocajas.edu.pe', '0000-00-00', '', NULL),
(207, NULL, '60347704', 'MAYTA', 'ROJAS', 'JHON HEBER', 'M', '958634852', '', '', '', '', '60347704@institutocajas.edu.pe', '0000-00-00', '', NULL),
(208, NULL, '60347716', 'CAMARGO', 'QUISPE', 'FELIX SERGIO', 'M', '967500686', '', '', '', '', '60347716@institutocajas.edu.pe', '0000-00-00', '', NULL),
(209, NULL, '60351664', 'ALANYA', 'PRETIL', 'JHONNY', 'M', '939280931', '', '', '', '', '60351664@institutocajas.edu.pe', '0000-00-00', '', NULL),
(210, NULL, '60351896', 'GUERRA', 'VILA', 'JUAN JOSE', 'M', '925468436', '', '', '', '', '60351896@institutocajas.edu.pe', '0000-00-00', '', NULL),
(211, NULL, '60360379', 'SULLCARAY', 'DE LA CRUZ', 'JAIME BRADDOCK', 'M', '912638524', '', '', '', '', '60360379@institutocajas.edu.pe', '0000-00-00', '', NULL),
(212, NULL, '60362556', 'GARCIA', 'VALERO', 'ANTONY YANFRANCO', 'M', '999999999', '', '', '', '', '60362556@institutocajas.edu.pe', '0000-00-00', '', NULL),
(213, NULL, '60379967', 'GUZMAN', 'ESTEBAN', 'LIZANDRO KENET', 'M', '916520436', '', '', '', '', '60379967@institutocajas.edu.pe', '0000-00-00', '', NULL),
(214, NULL, '60382739', 'UTCA?E', 'TOSCANO', 'MARITZA YAQUELIN', 'F', '921057083', '', '', '', '', '60382739@institutocajas.edu.pe', '0000-00-00', '', NULL),
(215, NULL, '60388550', 'LUNA', 'GONZALES', 'BRIAN RICHARD', 'M', '994169569', '', '', '', '', '60388550@institutocajas.edu.pe', '0000-00-00', '', NULL),
(216, NULL, '60391502', 'LANDEON', 'REYMUNDO', 'SAUL', 'M', '950058274', '', '', '', '', '60391502@institutocajas.edu.pe', '0000-00-00', '', NULL),
(217, NULL, '60408239', 'PAUCAR', 'ROMANI', 'CRISTHIAN ALVARO', 'M', '986323656', '', '', '', '', '60408239@institutocajas.edu.pe', '0000-00-00', '', NULL),
(218, NULL, '60408698', 'RODRIGUEZ', 'SALAZAR', 'SAHMIR JHERSON', 'M', '934920679', '', '', '', '', '60408698@institutocajas.edu.pe', '0000-00-00', '', NULL),
(219, NULL, '60418858', 'GOMEZ', 'PORRAS', 'JORDAN MICHAEL', 'M', '901791980', '', '', '', '', '60418858@institutocajas.edu.pe', '0000-00-00', '', NULL),
(220, NULL, '60418916', 'HUAMAN', 'YANCE', 'HANS KEVIN', 'M', '904233605', '', '', '', '', '60418916@institutocajas.edu.pe', '0000-00-00', '', NULL),
(221, NULL, '60419324', 'APOLINARIO', 'ZUARES', 'GIOVANNI ANDERSON', 'M', '957423179', '', '', '', '', '60419324@institutocajas.edu.pe', '0000-00-00', '', NULL),
(222, NULL, '60419443', 'JAUREGUI', 'JULCARIMA', 'YEFREN BRAYAN', 'M', '902113044', '', '', '', '', '60419443@institutocajas.edu.pe', '0000-00-00', '', NULL),
(223, NULL, '60423532', 'GONZALES', 'CARRASCO', 'JOSE DANIEL', 'M', '992144340', '', '', '', '', '60423532@institutocajas.edu.pe', '0000-00-00', '', NULL),
(224, NULL, '60423556', 'NAVARRO', 'DE LA ROSA', 'JUAN ARTURO', 'M', '915965192', '', '', '', '', '60423556@institutocajas.edu.pe', '0000-00-00', '', NULL),
(225, NULL, '60456715', 'RAMOS', 'CLEMENTE', 'EVANDER', 'M', '926134558', '', '', '', '', '60456715@institutocajas.edu.pe', '0000-00-00', '', NULL),
(226, NULL, '60456849', 'ARIAS', 'QUISPE', 'ANGHELLO FERNANDO', 'M', '902016100', '', '', '', '', '60456849@institutocajas.edu.pe', '0000-00-00', '', NULL),
(227, NULL, '60462850', 'GONZALES', 'PORRAS', 'CRISTINA JENNYFER', 'F', '940850271', '', '', '', '', '60462850@institutocajas.edu.pe', '0000-00-00', '', NULL),
(228, NULL, '60462946', 'RODRIGUEZ', 'SARZO', 'FRAN ELI', 'M', '957393477', '', '', '', '', '60462946@institutocajas.edu.pe', '0000-00-00', '', NULL),
(229, NULL, '60465261', 'LOZANO', 'LUNA', 'JEYTON JOYSE', 'M', '940820781', '', '', '', '', '60465261@institutocajas.edu.pe', '0000-00-00', '', NULL),
(230, NULL, '60475301', 'RIOS', 'PARIONA', 'KENYO ANTHONY', 'M', '924035592', '', '', '', '', '60475301@institutocajas.edu.pe', '0000-00-00', '', NULL),
(231, NULL, '60480455', 'YANGALI', 'CUCHO', 'ANALY PAOLA', 'F', '913645411', '', '', '', '', '60480455@institutocajas.edu.pe', '0000-00-00', '', NULL),
(232, NULL, '60481240', 'GONZALES', 'LOZANO', 'ESAU KALEP', 'M', '932099581', '', '', '', '', '60481240@institutocajas.edu.pe', '0000-00-00', '', NULL),
(233, NULL, '60484237', 'SAU?I', 'TORPOCO', 'RONALD ADOLFO', 'M', '923546934', '', '', '', '', '60484237@institutocajas.edu.pe', '0000-00-00', '', NULL),
(234, NULL, '60495218', 'SOTO', 'LAVADO', 'MOISES JAIRO', 'M', '999945315', '', '', '', '', '60495218@institutocajas.edu.pe', '0000-00-00', '', NULL),
(235, NULL, '60495240', 'CHOCCA', 'YUPANQUI', 'MICHAEL JEFFER', 'M', '965263589', '', '', '', '', '60495240@institutocajas.edu.pe', '0000-00-00', '', NULL),
(236, NULL, '60495476', 'LLANTOY', 'MEZA', 'DEYVIS JOSE', 'M', '929645611', '', '', '', '', '60495476@institutocajas.edu.pe', '0000-00-00', '', NULL),
(237, NULL, '60500871', 'ALANYA', 'SUAREZ', 'MELIZA LIZBETH', 'F', '923658668', '', '', '', '', '60500871@institutocajas.edu.pe', '0000-00-00', '', NULL),
(238, NULL, '60500890', 'CAMPOS', 'QUISPE', 'AMISADAY SENOBIA', 'F', '914409585', '', '', '', '', '60500890@institutocajas.edu.pe', '0000-00-00', '', NULL),
(239, NULL, '60500899', 'HUAYCA?I', 'QUINTANA', 'YASELA', 'F', '974089302', '', '', '', '', '60500899@institutocajas.edu.pe', '0000-00-00', '', NULL),
(240, NULL, '60500912', 'PE?A', 'MALDONADO', 'LIZ', 'F', '984760461', '', '', '', '', '60500912@institutocajas.edu.pe', '0000-00-00', '', NULL),
(241, NULL, '60501545', 'PAITAMPOMA', 'HUAYCA?E', 'JHON EZEQUIEL', 'M', '939517497', '', '', '', '', '60501545@institutocajas.edu.pe', '0000-00-00', '', NULL),
(242, NULL, '60507638', 'JARAMILLO', 'LASTRA', 'NEYDA ESTHER', 'F', '954534513', '', '', '', '', '60507638@institutocajas.edu.pe', '0000-00-00', '', NULL),
(243, NULL, '60514781', 'ROMERO', 'HINOSTROZA', 'ANTONY', 'M', '901631596', '', '', '', '', '60514781@institutocajas.edu.pe', '0000-00-00', '', NULL),
(244, NULL, '60514840', 'ALIAGA', 'PARIACHI', 'FRANKLIN SAIT', 'M', '925292842', '', '', '', '', '60514840@institutocajas.edu.pe', '0000-00-00', '', NULL),
(245, NULL, '60524179', 'QUILCA', 'CAMAYO', 'EDWARD', 'M', '973892540', '', '', '', '', '60524179@institutocajas.edu.pe', '0000-00-00', '', NULL),
(246, NULL, '60524951', 'AQUINO', 'LINDO', 'ANGIE AZUL', 'F', '945942145', '', '', '', '', '60524951@institutocajas.edu.pe', '0000-00-00', '', NULL),
(247, NULL, '60524987', 'GUTIERREZ', 'BARZOLA', 'VICTOR HUGO', 'M', '925751838', '', '', '', '', '60524987@institutocajas.edu.pe', '0000-00-00', '', NULL),
(248, NULL, '60534329', 'VALDERRAMA', 'ROMERO', 'JHANPIER SAMAEL', 'M', '987171585', '', '', '', '', '60534329@institutocajas.edu.pe', '0000-00-00', '', NULL),
(249, NULL, '60537002', 'LIMAYMANTA', 'MARTINEZ', 'XIOMARA JIAZMIN', 'F', '949131989', '', '', '', '', '60537002@institutocajas.edu.pe', '0000-00-00', '', NULL),
(250, NULL, '60537003', 'LIMAYMANTA', 'MARTINEZ', 'GIANELA JIAZMIN', 'F', '974215852', '', '', '', '', '60537003@institutocajas.edu.pe', '0000-00-00', '', NULL),
(251, NULL, '60539097', 'ZEGARRA', 'PORTALANZA', 'LUIS ANYELO', 'M', '947086322', '', '', '', '', '60539097@institutocajas.edu.pe', '0000-00-00', '', NULL),
(252, NULL, '60539122', 'LAVADO', 'ARANDA', 'MAICOL ANTONY', 'M', '950102989', '', '', '', '', '60539122@institutocajas.edu.pe', '0000-00-00', '', NULL),
(253, NULL, '60539123', 'CARRIZO', 'CHALCO', 'CRISTHIAN JESUS', 'M', '964383855', '', '', '', '', '60539123@institutocajas.edu.pe', '0000-00-00', '', NULL),
(254, NULL, '60546167', 'PAREJAS', 'DURAN', 'HUBER DANIEL', 'M', '927440986', '', '', '', '', '60546167@institutocajas.edu.pe', '0000-00-00', '', NULL),
(255, NULL, '60547397', 'VARGAS', 'ARAUJO', 'RAFAEL', 'M', '904944826', '', '', '', '', '60547397@institutocajas.edu.pe', '0000-00-00', '', NULL),
(256, NULL, '60547436', 'CHANCHA', 'POMA', 'YHOSMIL KEVIN', 'M', '964362944', '', '', '', '', '60547436@institutocajas.edu.pe', '0000-00-00', '', NULL),
(257, NULL, '60548803', 'YARINGA?O', 'MANYARI', 'ROBINHO', 'M', '916083241', '', '', '', '', '60548803@institutocajas.edu.pe', '0000-00-00', '', NULL),
(258, NULL, '60549414', 'HUAYANAY', 'RUTTI', 'TOMY STEVEN', 'M', '900790231', '', '', '', '', '60549414@institutocajas.edu.pe', '0000-00-00', '', NULL),
(259, NULL, '60552907', 'SOSA', 'MALLMA', 'VALERIN ASUMI', 'F', '927227672', '', '', '', '', '60552907@institutocajas.edu.pe', '0000-00-00', '', NULL),
(260, NULL, '60556110', 'MENDOZA', 'ROJAS', 'THALIA VANESSA', 'F', '900770211', '', '', '', '', '60556110@institutocajas.edu.pe', '0000-00-00', '', NULL),
(261, NULL, '60556119', 'ALZAMORA', 'ESPINAL', 'EMANUEL JOSE', 'M', '970513934', '', '', '', '', '60556119@institutocajas.edu.pe', '0000-00-00', '', NULL),
(262, NULL, '60559919', 'ROQUE', 'HURTADO', 'DILBER ESTIBEN', 'M', '946532107', '', '', '', '', '60559919@institutocajas.edu.pe', '0000-00-00', '', NULL),
(263, NULL, '60565388', 'GARCIA', 'DE LA O', 'ENYI SHERLY', 'F', '972830527', '', '', '', '', '60565388@institutocajas.edu.pe', '0000-00-00', '', NULL),
(264, NULL, '60572886', 'LAZO', 'LUJAN', 'JHINO ZIDGOPAL', 'M', '960170889', '', '', '', '', '60572886@institutocajas.edu.pe', '0000-00-00', '', NULL),
(265, NULL, '60583204', 'CANO', 'PAHUACHO', 'JHONATAN', 'M', '918120369', '', '', '', '', '60583204@institutocajas.edu.pe', '0000-00-00', '', NULL),
(266, NULL, '60583230', 'LLANCO', 'DORREGARAY', 'YHAN BRENER', 'M', '926024271', '', '', '', '', '60583230@institutocajas.edu.pe', '0000-00-00', '', NULL),
(267, NULL, '60596968', 'BALTAZAR', 'DE LA CRUZ', 'JHOSMER DAYVI', 'M', '948788337', '', '', '', '', '60596968@institutocajas.edu.pe', '0000-00-00', '', NULL),
(268, NULL, '60599050', 'AYALA', 'GASPAR', 'JOSE JAVIER', 'M', '917679373', '', '', '', '', '60599050@institutocajas.edu.pe', '0000-00-00', '', NULL),
(269, NULL, '60599068', 'SEDANO', 'CERRON', 'CHRISTIAN FREDDY', 'M', '912937686', '', '', '', '', '60599068@institutocajas.edu.pe', '0000-00-00', '', NULL),
(270, NULL, '60612256', 'CHOQUE', 'ROMERO', 'JOSE JOEL', 'M', '904561774', '', '', '', '', '60612256@institutocajas.edu.pe', '0000-00-00', '', NULL),
(271, NULL, '60625066', 'LLANA', 'MARTINEZ', 'SARITA', 'F', '976378292', '', '', '', '', '60625066@institutocajas.edu.pe', '0000-00-00', '', NULL),
(272, NULL, '60632829', 'ROJAS', 'ALANYA', 'DANNY GILMER', 'M', '961522422', '', '', '', '', '60632829@institutocajas.edu.pe', '0000-00-00', '', NULL),
(273, NULL, '60638603', 'QUISPE', 'MEZA', 'ALICEIDA', 'F', '942709772', '', '', '', '', '60638603@institutocajas.edu.pe', '0000-00-00', '', NULL),
(274, NULL, '60648555', 'MAYTA', 'VALERO', 'SHEYLA NICKOL', 'F', '991405368', '', '', '', '', '60648555@institutocajas.edu.pe', '0000-00-00', '', NULL),
(275, NULL, '60699168', 'C?RDENAS', 'TORRES', 'ALEX OSCAR', 'M', '923791218', '', '', '', '', '60699168@institutocajas.edu.pe', '0000-00-00', '', NULL),
(276, NULL, '60733142', 'PALOMINO', 'ESPINAL', 'MAURICIO ALEJANDRO', 'M', '979491712', '', '', '', '', '60733142@institutocajas.edu.pe', '0000-00-00', '', NULL),
(277, NULL, '60733938', 'ALARC?N', 'LAUREANO', 'JOS? EDUARDO', 'M', '961635096', '', '', '', '', '60733938@institutocajas.edu.pe', '0000-00-00', '', NULL),
(278, NULL, '60744985', 'MU?OZ', 'JULIAN', 'ANGIE JANELA', 'F', '943859432', '', '', '', '', '60744985@institutocajas.edu.pe', '0000-00-00', '', NULL),
(279, NULL, '60750909', 'YARANGA', 'FLORES', 'HANS OWEN', 'M', '969096975', '', '', '', '', '60750909@institutocajas.edu.pe', '0000-00-00', '', NULL),
(280, NULL, '60750980', 'YARANGA', 'QUISPE', 'SHADYA NICOLE', 'F', '979379932', '', '', '', '', '60750980@institutocajas.edu.pe', '0000-00-00', '', NULL),
(281, NULL, '60751128', 'GUTIERREZ', 'HUAMANI', 'ZENON JESUS', 'M', '918017309', '', '', '', '', '60751128@institutocajas.edu.pe', '0000-00-00', '', NULL),
(282, NULL, '60751169', 'ROQUE', 'HERMITA?O', 'EDWAR YORDAN', 'M', '949094432', '', '', '', '', '60751169@institutocajas.edu.pe', '0000-00-00', '', NULL),
(283, NULL, '60759462', 'ARANDA', 'DIAZ', 'RICHARD CLAUDIO', 'M', '995262097', '', '', '', '', '60759462@institutocajas.edu.pe', '0000-00-00', '', NULL),
(284, NULL, '60761106', 'VILCHEZ', 'CAMAVILCA', 'HENRY DANIEL', 'M', '930293068', '', '', '', '', '60761106@institutocajas.edu.pe', '0000-00-00', '', NULL),
(285, NULL, '60761254', 'MILLA', 'MEDINA', 'GENESIS DANAYOU', 'F', '952057828', '', '', '', '', '60761254@institutocajas.edu.pe', '0000-00-00', '', NULL),
(286, NULL, '60761368', 'LOPEZ', 'URIBE', 'LEONARDO JOHAN', 'M', '957875142', '', '', '', '', '60761368@institutocajas.edu.pe', '0000-00-00', '', NULL),
(287, NULL, '60761509', 'MAYHUA', 'SALAZAR', 'ANDREA ROCIO', 'F', '902976953', '', '', '', '', '60761509@institutocajas.edu.pe', '0000-00-00', '', NULL),
(288, NULL, '60761542', 'HUANUCO', 'BARRA', 'JHAN BEKHAN', 'M', '901144957', '', '', '', '', '60761542@institutocajas.edu.pe', '0000-00-00', '', NULL),
(289, NULL, '60761602', 'TOVAR', 'ESPINOZA', 'JOEL DAVID', 'M', '947029506', '', '', '', '', '60761602@institutocajas.edu.pe', '0000-00-00', '', NULL),
(290, NULL, '60761651', 'QUICA?O', 'TAIPE', 'MADELEY BETSY', 'F', '903499631', '', '', '', '', '60761651@institutocajas.edu.pe', '0000-00-00', '', NULL),
(291, NULL, '60761676', 'VALENCIA', 'SANCHEZ', 'ADRIAN ALEJANDRO', 'M', '900651800', '', '', '', '', '60761676@institutocajas.edu.pe', '0000-00-00', '', NULL),
(292, NULL, '60773500', 'ENRIQUEZ', 'RAMIREZ', 'ELISABET TREYSI', 'F', '943378488', '', '', '', '', '60773500@institutocajas.edu.pe', '0000-00-00', '', NULL),
(293, NULL, '60804031', 'YA?AC', 'MENDOZA', 'ELYUD', 'M', '975624966', '', '', '', '', '60804031@institutocajas.edu.pe', '0000-00-00', '', NULL),
(294, NULL, '60806808', 'SAMANIEGO', 'REYES', 'JUAN RICARDO', 'M', '999999999', '', '', '', '', '60806808@institutocajas.edu.pe', '0000-00-00', '', NULL),
(295, NULL, '60829386', 'MEZA', 'ESCOBAR', 'GILMER ABEL', 'M', '990857807', '', '', '', '', '60829386@institutocajas.edu.pe', '0000-00-00', '', NULL),
(296, NULL, '60829713', 'ALLCA', 'CALDERON', 'SAMIR FRANCO', 'M', '970947168', '', '', '', '', '60829713@institutocajas.edu.pe', '0000-00-00', '', NULL),
(297, NULL, '60829823', 'RAMOS', 'MAYTA', 'RAFAEL CARLOS', 'M', '917627302', '', '', '', '', '60829823@institutocajas.edu.pe', '0000-00-00', '', NULL),
(298, NULL, '60829824', 'YUPANQUI', 'OSORES', 'JACK ERIK', 'M', '944789603', '', '', '', '', '60829824@institutocajas.edu.pe', '0000-00-00', '', NULL),
(299, NULL, '60829841', 'RIVERA', 'AUJATOMA', 'CRISTHIAN ESNAIDER', 'M', '931109628', '', '', '', '', '60829841@institutocajas.edu.pe', '0000-00-00', '', NULL),
(300, NULL, '60833713', 'VERA', 'ALCOSER', 'ROSAURA', 'F', '965808775', '', '', '', '', '60833713@institutocajas.edu.pe', '0000-00-00', '', NULL),
(301, NULL, '60842298', 'ZARATE', 'CANALES', 'EDISON SEBASTIAN', 'M', '935986399', '', '', '', '', '60842298@institutocajas.edu.pe', '0000-00-00', '', NULL),
(302, NULL, '60846862', 'ESCOBAR', 'PI?AS', 'LEONEL HANLET', 'M', '933585825', '', '', '', '', '60846862@institutocajas.edu.pe', '0000-00-00', '', NULL),
(303, NULL, '60846946', 'ATAU', 'DE LA CRUZ', 'STEVEN EFRAIN', 'M', '985998883', '', '', '', '', '60846946@institutocajas.edu.pe', '0000-00-00', '', NULL),
(304, NULL, '60847009', 'GALLARDO', 'MEDINA', 'RANDOLT LUIS', 'M', '956347436', '', '', '', '', '60847009@institutocajas.edu.pe', '0000-00-00', '', NULL),
(305, NULL, '60847049', 'NOLASCO', 'PALOMINO', 'FLOR DE MARIA', 'F', '999999999', '', '', '', '', '60847049@institutocajas.edu.pe', '0000-00-00', '', NULL),
(306, NULL, '60847097', 'QUISPE', 'CASTRO', 'JHUNIOR JOSE', 'M', '902079677', '', '', '', '', '60847097@institutocajas.edu.pe', '0000-00-00', '', NULL),
(307, NULL, '60847274', 'RAMOS', 'MOYA', 'CARLOS JOSUE', 'M', '926151306', '', '', '', '', '60847274@institutocajas.edu.pe', '0000-00-00', '', NULL),
(308, NULL, '60847326', 'SALCEDO', 'CHUCOS', 'RICHARD JUNNIOR', 'M', '964769859', '', '', '', '', '60847326@institutocajas.edu.pe', '0000-00-00', '', NULL),
(309, NULL, '60861414', 'CASTILLEJO', 'IGNACIO', 'DELICIA KATIUSKA', 'F', '938403364', '', '', '', '', '60861414@institutocajas.edu.pe', '0000-00-00', '', NULL),
(310, NULL, '60891297', 'TUNQUE', 'MEZA', 'JOSEP TAYWA', 'M', '988552195', '', '', '', '', '60891297@institutocajas.edu.pe', '0000-00-00', '', NULL),
(311, NULL, '60891346', 'CONTRERAS', 'PEREZ', 'KEVIN DEYVID', 'M', '989730971', '', '', '', '', '60891346@institutocajas.edu.pe', '0000-00-00', '', NULL),
(312, NULL, '60891401', 'LOAYZA', 'ECHENIQUE', 'ELIAS ELISEO', 'M', '980659807', '', '', '', '', '60891401@institutocajas.edu.pe', '0000-00-00', '', NULL),
(313, NULL, '60891416', 'BENITES', 'AYALA', 'YAREX MAURO', 'M', '936779577', '', '', '', '', '60891416@institutocajas.edu.pe', '0000-00-00', '', NULL),
(314, NULL, '60891430', 'DE LA PAZ', 'UNTIVEROS', 'JERIKO JANPIER', 'M', '997791857', '', '', '', '', '60891430@institutocajas.edu.pe', '0000-00-00', '', NULL),
(315, NULL, '60905609', 'RAMIREZ', 'CARMONA', 'MIGUEL ANGEL', 'M', '989633388', '', '', '', '', '60905609@institutocajas.edu.pe', '0000-00-00', '', NULL),
(316, NULL, '60905690', 'CHUCO', 'PEREZ', 'YOSELIN SOFIA', 'F', '932409973', '', '', '', '', '60905690@institutocajas.edu.pe', '0000-00-00', '', NULL),
(317, NULL, '60905704', 'SALAZAR', 'BUSTAMANTE', 'JESUS LUCIO MAXIMO', 'M', '904702199', '', '', '', '', '60905704@institutocajas.edu.pe', '0000-00-00', '', NULL),
(318, NULL, '60905720', 'ROSALES', 'POMASUNCO', 'JHOSEP NARCISO', 'M', '999591813', '', '', '', '', '60905720@institutocajas.edu.pe', '0000-00-00', '', NULL),
(319, NULL, '60905837', 'CRISPIN', 'VELIZ', 'KATERINEE NAHOMY', 'F', '901863134', '', '', '', '', '60905837@institutocajas.edu.pe', '0000-00-00', '', NULL),
(320, NULL, '60906328', 'PIZARRO', 'ARAGON', 'YARIZA AYELEN', 'F', '925361336', '', '', '', '', '60906328@institutocajas.edu.pe', '0000-00-00', '', NULL),
(321, NULL, '60906501', '?AUPARI', 'SALAS', 'JOHAN ANTONIO', 'M', '982231609', '', '', '', '', '60906501@institutocajas.edu.pe', '0000-00-00', '', NULL),
(322, NULL, '60906539', 'CASAVILCA', 'ORIHUELA', 'ANGHELO FABRISIO', 'M', '957262178', '', '', '', '', '60906539@institutocajas.edu.pe', '0000-00-00', '', NULL),
(323, NULL, '60906596', 'AGUSTIN', 'TRILLO', 'GUILLERMO', 'M', '902726862', '', '', '', '', '60906596@institutocajas.edu.pe', '0000-00-00', '', NULL),
(324, NULL, '60910987', 'GUILLEN', 'ROJAS', 'HAMESHA ANGHELY', 'F', '924056346', '', '', '', '', '60910987@institutocajas.edu.pe', '0000-00-00', '', NULL),
(325, NULL, '60911044', 'PAREDES', 'RAMOS', 'LUIS DANIEL', 'M', '906029725', '', '', '', '', '60911044@institutocajas.edu.pe', '0000-00-00', '', NULL),
(326, NULL, '60911065', 'CORDOVA', 'MONTA?EZ', 'MAYKEL ANTONIO', 'M', '951808697', '', '', '', '', '60911065@institutocajas.edu.pe', '0000-00-00', '', NULL),
(327, NULL, '60920757', 'TAIPE', 'PEDRAZA', 'YAAK YANFER', 'M', '985346096', '', '', '', '', '60920757@institutocajas.edu.pe', '0000-00-00', '', NULL),
(328, NULL, '60930372', 'MENDOZA', 'RODRIGUEZ', 'MIGUEL ANGEL', 'M', '963170616', '', '', '', '', '60930372@institutocajas.edu.pe', '0000-00-00', '', NULL);
INSERT INTO `estudiante` (`id`, `ubdistrito`, `dni_est`, `ap_est`, `am_est`, `nom_est`, `sex_est`, `cel_est`, `ubigeodir_est`, `ubigeonac_est`, `dir_est`, `mailp_est`, `maili_est`, `fecnac_est`, `foto_est`, `estado`) VALUES
(329, NULL, '60931370', 'CHANCOS', 'HUAMAN', 'MARIELA XIOMARA', 'F', '904409401', '', '', '', '', '60931370@institutocajas.edu.pe', '0000-00-00', '', NULL),
(330, NULL, '60932581', 'HINOSTROZA', 'AMES', 'JIMY HUOSEIN', 'M', '989516177', '', '', '', '', '60932581@institutocajas.edu.pe', '0000-00-00', '', NULL),
(331, NULL, '60932620', 'HERRERA', 'PEREZ', 'CALEF DANIEL', 'M', '932172215', '', '', '', '', '60932620@institutocajas.edu.pe', '0000-00-00', '', NULL),
(332, NULL, '60932695', 'CERRON', 'GOMEZ', 'ARIEL DAVID', 'M', '973082425', '', '', '', '', '60932695@institutocajas.edu.pe', '0000-00-00', '', NULL),
(333, NULL, '60932707', 'ALIAGA', 'BAUTISTA', 'DIEGO MIJAEL', 'M', '937435349', '', '', '', '', '60932707@institutocajas.edu.pe', '0000-00-00', '', NULL),
(334, NULL, '60932910', 'HUARCAYA', 'VELARDE', 'JHOSEP CARLOS', 'M', '942847840', '', '', '', '', '60932910@institutocajas.edu.pe', '0000-00-00', '', NULL),
(335, NULL, '60939166', 'PALOMINO', 'EULOGIO', 'SEBASTIAN JEAN POOL', 'M', '956489115', '', '', '', '', '60939166@institutocajas.edu.pe', '0000-00-00', '', NULL),
(336, NULL, '60981382', 'SOTO', 'ASTO', 'MILA LUCY', 'F', '954444949', '', '', '', '', '60981382@institutocajas.edu.pe', '0000-00-00', '', NULL),
(337, NULL, '60981510', 'CORILLOCLLA', 'PARIONA', 'ARON FERNANDO', 'M', '964032787', '', '', '', '', '60981510@institutocajas.edu.pe', '0000-00-00', '', NULL),
(338, NULL, '60981563', 'VILLAFUERTE', 'GUINEA', 'RENZO ALEXANDER', 'M', '970338272', '', '', '', '', '60981563@institutocajas.edu.pe', '0000-00-00', '', NULL),
(339, NULL, '60981811', 'ALANYA', 'GAMARRA', 'DIEGO SEBASTIAN', 'M', '960460948', '', '', '', '', '60981811@institutocajas.edu.pe', '0000-00-00', '', NULL),
(340, NULL, '60981832', 'JAUREGUI', 'ROMERO', 'DANIEL STEVEN', 'M', '976907382', '', '', '', '', '60981832@institutocajas.edu.pe', '0000-00-00', '', NULL),
(341, NULL, '60981994', 'PAUCAR', 'BENITO', 'ERICK SHIAN', 'M', '907179793', '', '', '', '', '60981994@institutocajas.edu.pe', '0000-00-00', '', NULL),
(342, NULL, '60982148', 'CAJAS', 'MARIN', 'EMANUEL JULIAN', 'M', '982305431', '', '', '', '', '60982148@institutocajas.edu.pe', '0000-00-00', '', NULL),
(343, NULL, '60982167', 'ASTETE', 'FLORES', 'DINA ISABEL', 'F', '962765557', '', '', '', '', '60982167@institutocajas.edu.pe', '0000-00-00', '', NULL),
(344, NULL, '60990593', 'PACCO', 'RODRIGO', 'YANELY KAREN', 'F', '999999999', '', '', '', '', '60990593@institutocajas.edu.pe', '0000-00-00', '', NULL),
(345, NULL, '61012130', 'GOMEZ', 'CASTILLO', 'LUIS FERNANDO', 'M', '924674842', '', '', '', '', '61012130@institutocajas.edu.pe', '0000-00-00', '', NULL),
(346, NULL, '61027199', 'CCANTO', 'VILLAYZAN', 'JHOSMELL JAIRO', 'M', '945826213', '', '', '', '', '61027199@institutocajas.edu.pe', '0000-00-00', '', NULL),
(347, NULL, '61027231', 'PORRAS', 'BENITO', 'JOSEP ABEL', 'M', '929153895', '', '', '', '', '61027231@institutocajas.edu.pe', '0000-00-00', '', NULL),
(348, NULL, '61027243', 'QUISPE', 'CONDORI', 'DEYVIS DIEGO', 'M', '921839582', '', '', '', '', '61027243@institutocajas.edu.pe', '0000-00-00', '', NULL),
(349, NULL, '61027314', 'CANALES', 'CONDOR', 'DANILO', 'M', '971424744', '', '', '', '', '61027314@institutocajas.edu.pe', '0000-00-00', '', NULL),
(350, NULL, '61054467', 'MARTINEZ', 'PAUCAR', 'JUAN GABRIEL', 'M', '967321775', '', '', '', '', '61054467@institutocajas.edu.pe', '0000-00-00', '', NULL),
(351, NULL, '61060784', 'CONTRERAS', 'TRUJILLO', 'RODRIGO ALONSO', 'M', '970189208', '', '', '', '', '61060784@institutocajas.edu.pe', '0000-00-00', '', NULL),
(352, NULL, '61060826', 'ORIHUELA', 'HUAROC', 'ALLISON SAMIRA', 'F', '993214752', '', '', '', '', '61060826@institutocajas.edu.pe', '0000-00-00', '', NULL),
(353, NULL, '61061245', 'LOPEZ', 'PARADO', 'JUAN GABRIEL', 'M', '916962100', '', '', '', '', '61061245@institutocajas.edu.pe', '0000-00-00', '', NULL),
(354, NULL, '61061388', 'FELIX', 'CORDOVA', 'ALEXIS YESHUA', 'M', '976137483', '', '', '', '', '61061388@institutocajas.edu.pe', '0000-00-00', '', NULL),
(355, NULL, '61065616', 'VILCHEZ', 'OCHOA', 'YOEL BERNABE', 'M', '997301494', '', '', '', '', '61065616@institutocajas.edu.pe', '0000-00-00', '', NULL),
(356, NULL, '61065827', 'QUISPE', 'LAVADO', 'SAUL YERSON', 'M', '975405472', '', '', '', '', '61065827@institutocajas.edu.pe', '0000-00-00', '', NULL),
(357, NULL, '61075460', 'VALENTIN', 'MARTINEZ', 'JESUS ANDREY', 'M', '912060092', '', '', '', '', '61075460@institutocajas.edu.pe', '0000-00-00', '', NULL),
(358, NULL, '61075516', 'AG?ERO', 'RICRA', 'YOSSELIN PATTY', 'F', '927925026', '', '', '', '', '61075516@institutocajas.edu.pe', '0000-00-00', '', NULL),
(359, NULL, '61083518', 'POCOMUCHA', 'VASQUEZ', 'PIERO ALEJANDRO', 'M', '957044128', '', '', '', '', '61083518@institutocajas.edu.pe', '0000-00-00', '', NULL),
(360, NULL, '61090843', 'CUTIPA', 'CONDOR', 'ALEXANDRE PIERO', 'M', '961271688', '', '', '', '', '61090843@institutocajas.edu.pe', '0000-00-00', '', NULL),
(361, NULL, '61090901', 'DIAZ', 'OSCANOA', 'KAMELY KAIT', 'F', '927215200', '', '', '', '', '61090901@institutocajas.edu.pe', '0000-00-00', '', NULL),
(362, NULL, '61105161', 'VARGAS', 'LAIME', 'JAYRO IVAN', 'M', '985844103', '', '', '', '', '61105161@institutocajas.edu.pe', '0000-00-00', '', NULL),
(363, NULL, '61111664', 'RIVERA', 'BERNAL', 'ACRIONI JEMBER LEV', 'M', '952154130', '', '', '', '', '61111664@institutocajas.edu.pe', '0000-00-00', '', NULL),
(364, NULL, '61111929', 'HUERTA', 'JIMENEZ', 'SHESIT CELESTE', 'F', '947726901', '', '', '', '', '61111929@institutocajas.edu.pe', '0000-00-00', '', NULL),
(365, NULL, '61112040', 'CASAVILCA', 'CORIS', 'MAYCOL CRISTIAN', 'M', '972076716', '', '', '', '', '61112040@institutocajas.edu.pe', '0000-00-00', '', NULL),
(366, NULL, '61112811', 'ROMERO', 'COMUN', 'CRISTIAN DANNY', 'M', '921105653', '', '', '', '', '61112811@institutocajas.edu.pe', '0000-00-00', '', NULL),
(367, NULL, '61114712', 'GASPAR', 'ALIAGA', 'RODRIGO ALEXIS', 'M', '981760140', '', '', '', '', '61114712@institutocajas.edu.pe', '0000-00-00', '', NULL),
(368, NULL, '61115111', 'PAUCAR', 'PARAGUAY', 'ALFER JORDAN', 'M', '943993126', '', '', '', '', '61115111@institutocajas.edu.pe', '0000-00-00', '', NULL),
(369, NULL, '61115158', 'QUIJADA', 'ORELLANA', 'HEIDI RUTH', 'F', '966657844', '', '', '', '', '61115158@institutocajas.edu.pe', '0000-00-00', '', NULL),
(370, NULL, '61115334', 'BENITES', 'BALDEON', 'MAX ALEXANDER', 'M', '983038422', '', '', '', '', '61115334@institutocajas.edu.pe', '0000-00-00', '', NULL),
(371, NULL, '61119749', 'YUMBATO', 'FIERRO', 'JAIRO GABRIEL', 'M', '925242891', '', '', '', '', '61119749@institutocajas.edu.pe', '0000-00-00', '', NULL),
(372, NULL, '61131180', 'ANCAJIMA', 'ESPINOZA', 'EVA LUZ', 'F', '982876290', '', '', '', '', '61131180@institutocajas.edu.pe', '0000-00-00', '', NULL),
(373, NULL, '61131721', 'SALOME', 'AVELLANEDA', 'ALEXANDER', 'M', '906116009', '', '', '', '', '61131721@institutocajas.edu.pe', '0000-00-00', '', NULL),
(374, NULL, '61131831', 'MARRO', 'REYES', 'ALDAIR', 'M', '978464167', '', '', '', '', '61131831@institutocajas.edu.pe', '0000-00-00', '', NULL),
(375, NULL, '61131877', 'GUILLEN', 'VILCAPOMA', 'MIGUEL', 'M', '941846724', '', '', '', '', '61131877@institutocajas.edu.pe', '0000-00-00', '', NULL),
(376, NULL, '61131967', 'MUCHA', 'DE LA CRUZ', 'JONATAN LUCIANO', 'M', '914884819', '', '', '', '', '61131967@institutocajas.edu.pe', '0000-00-00', '', NULL),
(377, NULL, '61144049', 'CUELLAR', 'SANTIAGO', 'JACK DEIVIS', 'M', '923342794', '', '', '', '', '61144049@institutocajas.edu.pe', '0000-00-00', '', NULL),
(378, NULL, '61159372', 'NINALAYA', 'VEGA', 'OSCAR NELSON', 'M', '947769688', '', '', '', '', '61159372@institutocajas.edu.pe', '0000-00-00', '', NULL),
(379, NULL, '61185998', 'SALAZAR', 'QUISPE', 'JHEYDI LIZ SALOME', 'F', '962393038', '', '', '', '', '61185998@institutocajas.edu.pe', '0000-00-00', '', NULL),
(380, NULL, '61192830', 'CASTA?EDA', 'ZEVALLOS', 'VICTOR MANUEL', 'M', '930925761', '', '', '', '', '61192830@institutocajas.edu.pe', '0000-00-00', '', NULL),
(381, NULL, '61203484', 'TORRES', 'ANGLAS', 'ERWIN KENNY', 'M', '951763490', '', '', '', '', '61203484@institutocajas.edu.pe', '0000-00-00', '', NULL),
(382, NULL, '61203546', 'CUCHULA', 'ASTO', 'GALCIRAN', 'M', '918587952', '', '', '', '', '61203546@institutocajas.edu.pe', '0000-00-00', '', NULL),
(383, NULL, '61203812', 'CONDOR', 'LLACUA', 'NAYI ESTELA', 'F', '946638138', '', '', '', '', '61203812@institutocajas.edu.pe', '0000-00-00', '', NULL),
(384, NULL, '61203839', 'CAPARO', 'TRUJILLANO', 'AXEL FABRICIO', 'M', '920523268', '', '', '', '', '61203839@institutocajas.edu.pe', '0000-00-00', '', NULL),
(385, NULL, '61203950', 'CARDENAS', 'MAITA', 'SAMUEL', 'M', '942057600', '', '', '', '', '61203950@institutocajas.edu.pe', '0000-00-00', '', NULL),
(386, NULL, '61204041', 'POCOMUCHA', 'CANCHANYA', 'ASUMY JHADE', 'F', '907382421', '', '', '', '', '61204041@institutocajas.edu.pe', '0000-00-00', '', NULL),
(387, NULL, '61206494', 'CHAVEZ', 'TORRES', 'ANIBAL SMITH', 'M', '904561774', '', '', '', '', '61206494@institutocajas.edu.pe', '0000-00-00', '', NULL),
(388, NULL, '61206828', 'MEZA', 'SOTO', 'SAMIR JOSU?', 'M', '970732371', '', '', '', '', '61206828@institutocajas.edu.pe', '0000-00-00', '', NULL),
(389, NULL, '61206996', 'TINOCO', 'MU?OZ', 'DIEGO ARMANDO', 'M', '936892077', '', '', '', '', '61206996@institutocajas.edu.pe', '0000-00-00', '', NULL),
(390, NULL, '61207240', 'SALAZAR', 'YUPANQUI', 'NAYARA LUSMENIA RUTH', 'F', '902416019', '', '', '', '', '61207240@institutocajas.edu.pe', '0000-00-00', '', NULL),
(391, NULL, '61207400', 'GARCIA', 'CARHUALLANQUI', 'LISET CARLA', 'F', '959094764', '', '', '', '', '61207400@institutocajas.edu.pe', '0000-00-00', '', NULL),
(392, NULL, '61240566', 'RUTTI', 'RASHUAMAN', 'ADRIANO RONALDO', 'M', '973864364', '', '', '', '', '61240566@institutocajas.edu.pe', '0000-00-00', '', NULL),
(393, NULL, '61240643', 'RUIZ', 'SAENZ', 'ALEX PAUL', 'M', '901184142', '', '', '', '', '61240643@institutocajas.edu.pe', '0000-00-00', '', NULL),
(394, NULL, '61256320', 'JANAMPA', 'LIMA', 'CLEVER', 'M', '952116412', '', '', '', '', '61256320@institutocajas.edu.pe', '0000-00-00', '', NULL),
(395, NULL, '61278711', 'LANAZCA', 'QUINTANILLA', 'WILMER ELIAS', 'M', '914042212', '', '', '', '', '61278711@institutocajas.edu.pe', '0000-00-00', '', NULL),
(396, NULL, '61278996', 'GABRIEL', 'NU?EZ', 'KEN YULER', 'M', '902238820', '', '', '', '', '61278996@institutocajas.edu.pe', '0000-00-00', '', NULL),
(397, NULL, '61280527', 'CHINCHUYA', 'DOMINOTTI', 'MAICOL RONALD', 'M', '978329198', '', '', '', '', '61280527@institutocajas.edu.pe', '0000-00-00', '', NULL),
(398, NULL, '61288946', 'PARI', 'TERRONES', 'ANDREY PAOLO PABLO', 'M', '981393109', '', '', '', '', '61288946@institutocajas.edu.pe', '0000-00-00', '', NULL),
(399, NULL, '61289611', 'GARCIA', 'PARADO', 'MARISOL', 'F', '929966816', '', '', '', '', '61289611@institutocajas.edu.pe', '0000-00-00', '', NULL),
(400, NULL, '61289866', 'PEREZ', 'RAMOS', 'SHIRLEY YAHANA', 'F', '963084218', '', '', '', '', '61289866@institutocajas.edu.pe', '0000-00-00', '', NULL),
(401, NULL, '61290016', 'TELLO', 'BENITO', 'CHRISTIAN ANTHONY', 'M', '924219272', '', '', '', '', '61290016@institutocajas.edu.pe', '0000-00-00', '', NULL),
(402, NULL, '61290226', 'HUAMAN', 'SOLANO', 'JHOSEP JHORDAN', 'M', '936275207', '', '', '', '', '61290226@institutocajas.edu.pe', '0000-00-00', '', NULL),
(403, NULL, '61290259', 'CHUQUE', 'REYES', 'RENZO DANILO', 'M', '927739963', '', '', '', '', '61290259@institutocajas.edu.pe', '0000-00-00', '', NULL),
(404, NULL, '61316564', 'RAMIREZ', 'VILCHEZ', 'SOLEDAD CRISTINA', 'F', '922926121', '', '', '', '', '61316564@institutocajas.edu.pe', '0000-00-00', '', NULL),
(405, NULL, '61319845', 'UCHUYPOMA', 'CAMPOS', 'JEANSS BECAN', 'M', '944631471', '', '', '', '', '61319845@institutocajas.edu.pe', '0000-00-00', '', NULL),
(406, NULL, '61342139', 'AVILA', 'ASTO', 'ARNIE ALEX', 'M', '948624921', '', '', '', '', '61342139@institutocajas.edu.pe', '0000-00-00', '', NULL),
(407, NULL, '61342199', 'HUAMAN', 'SEAS', 'ABNER HENRRY', 'M', '922634824', '', '', '', '', '61342199@institutocajas.edu.pe', '0000-00-00', '', NULL),
(408, NULL, '61342435', 'CCATAY', 'TRUJILLO', 'LEONEL FABRISIO', 'M', '975711735', '', '', '', '', '61342435@institutocajas.edu.pe', '0000-00-00', '', NULL),
(409, NULL, '61342653', 'VILA', 'S?NCHEZ', 'EMELY', 'F', '971962556', '', '', '', '', '61342653@institutocajas.edu.pe', '0000-00-00', '', NULL),
(410, NULL, '61342738', 'CENTENO', 'POMA', 'AMER LUIS', 'M', '903455368', '', '', '', '', '61342738@institutocajas.edu.pe', '0000-00-00', '', NULL),
(411, NULL, '61344269', 'SANTANA', 'PEREZ', 'FARID', 'M', '901653907', '', '', '', '', '61344269@institutocajas.edu.pe', '0000-00-00', '', NULL),
(412, NULL, '61346869', 'GOMEZ', 'BUSTAMANTE', 'ROQUE JUNIOR', 'M', '965755107', '', '', '', '', '61346869@institutocajas.edu.pe', '0000-00-00', '', NULL),
(413, NULL, '61375298', 'GALARZA', 'RAMOS', 'ERICK ANTHONY', 'M', '925797549', '', '', '', '', '61375298@institutocajas.edu.pe', '0000-00-00', '', NULL),
(414, NULL, '61428850', 'FERNANDEZ', 'LINO', 'JEANFRANCO ALDAIR', 'M', '946959911', '', '', '', '', '61428850@institutocajas.edu.pe', '0000-00-00', '', NULL),
(415, NULL, '61475018', 'MARQUEZ', 'CHIRINOS', 'TIAGO HUEY', 'M', '908559054', '', '', '', '', '61475018@institutocajas.edu.pe', '0000-00-00', '', NULL),
(416, NULL, '61475235', 'RAMOS', 'CCAHUANA', 'YOSMEL YERIMI', 'M', '976760678', '', '', '', '', '61475235@institutocajas.edu.pe', '0000-00-00', '', NULL),
(417, NULL, '61551717', 'ALLCA', 'DAVIRAN', 'JHEMMIS SMITH', 'M', '904002224', '', '', '', '', '61551717@institutocajas.edu.pe', '0000-00-00', '', NULL),
(418, NULL, '61554811', 'BALDEON', 'INGA', 'BETZABE ANGELA', 'F', '930747409', '', '', '', '', '61554811@institutocajas.edu.pe', '0000-00-00', '', NULL),
(419, NULL, '61561671', 'PONCE', 'RODRIGUEZ', 'LESLI', 'F', '970561814', '', '', '', '', '61561671@institutocajas.edu.pe', '0000-00-00', '', NULL),
(420, NULL, '61592673', 'GOMEZ', 'CHOMBO', 'ANGIE MAYELY', 'F', '930378050', '', '', '', '', '61592673@institutocajas.edu.pe', '0000-00-00', '', NULL),
(421, NULL, '61614849', 'HUAMAN', 'PASCUAL', 'AUGUSTO', 'M', '992402776', '', '', '', '', '61614849@institutocajas.edu.pe', '0000-00-00', '', NULL),
(422, NULL, '61648925', 'CRISPIN', 'AVILA', 'HILCER DANIEL', 'M', '938749917', '', '', '', '', '61648925@institutocajas.edu.pe', '0000-00-00', '', NULL),
(423, NULL, '61653298', 'CALDERON', 'TITO', 'MEDALY MIRIAN', 'F', '923380251', '', '', '', '', '61653298@institutocajas.edu.pe', '0000-00-00', '', NULL),
(424, NULL, '61661710', 'QUINO', 'RIVERA', 'HERNAN', 'M', '931856106', '', '', '', '', '61661710@institutocajas.edu.pe', '0000-00-00', '', NULL),
(425, NULL, '61666434', 'PARIACHI', 'PEREZ', 'WILLYAMS RONALD', 'M', '962765910', '', '', '', '', '61666434@institutocajas.edu.pe', '0000-00-00', '', NULL),
(426, NULL, '61666440', 'PARADO', 'PE?A', 'JENISON JEMIS', 'M', '943740829', '', '', '', '', '61666440@institutocajas.edu.pe', '0000-00-00', '', NULL),
(427, NULL, '61669415', 'PARIONA', 'CASTILLO', 'JHOKS', 'M', '954994177', '', '', '', '', '61669415@institutocajas.edu.pe', '0000-00-00', '', NULL),
(428, NULL, '61677848', 'ESPINOZA', 'ESTRELLA', 'JHON ALEXANDER', 'M', '947194652', '', '', '', '', '61677848@institutocajas.edu.pe', '0000-00-00', '', NULL),
(429, NULL, '61692706', 'RODRIGUEZ', 'HUAMAN', 'TANIA', 'F', '946503717', '', '', '', '', '61692706@institutocajas.edu.pe', '0000-00-00', '', NULL),
(430, NULL, '61712695', 'ROCHA', 'HUAROC', 'HILDER YAMPIER', 'M', '903094656', '', '', '', '', '61712695@institutocajas.edu.pe', '0000-00-00', '', NULL),
(431, NULL, '61731524', 'CASTILLO', 'HUAMANI', 'RICARDO AGUSTIN', 'M', '907445675', '', '', '', '', '61731524@institutocajas.edu.pe', '0000-00-00', '', NULL),
(432, NULL, '61744625', 'GARCIA', 'CAMPOS', 'JUAN DENNIS', 'M', '999999999', '', '', '', '', '61744625@institutocajas.edu.pe', '0000-00-00', '', NULL),
(433, NULL, '61895508', 'URBANO', 'JURADO', 'ANDREA PILAR', 'F', '903281820', '', '', '', '', '61895508@institutocajas.edu.pe', '0000-00-00', '', NULL),
(434, NULL, '61957140', 'REYNOSO', 'JAUREGUI', 'GERARDO KEVIN', 'M', '999999999', '', '', '', '', '61957140@institutocajas.edu.pe', '0000-00-00', '', NULL),
(435, NULL, '61990622', 'MANRIQUE', 'RODRIGUEZ', 'JOEL ABIDAN', 'M', '974514419', '', '', '', '', '61990622@institutocajas.edu.pe', '0000-00-00', '', NULL),
(436, NULL, '61997916', 'USCAMAYTA', 'VILLANUEVA', 'ABEL RONAL', 'M', '940309246', '', '', '', '', '61997916@institutocajas.edu.pe', '0000-00-00', '', NULL),
(437, NULL, '61999317', 'SINCHITULLO', 'FERNANDEZ', 'DANIEL', 'M', '963968929', '', '', '', '', '61999317@institutocajas.edu.pe', '0000-00-00', '', NULL),
(438, NULL, '62183185', 'URBANO', 'QUISPE', 'ROY MAICOL', 'M', '986956547', '', '', '', '', '62183185@institutocajas.edu.pe', '0000-00-00', '', NULL),
(439, NULL, '62211246', 'LAURENTE', 'CANTORIN', 'KAREN', 'F', '948156618', '', '', '', '', '62211246@institutocajas.edu.pe', '0000-00-00', '', NULL),
(440, NULL, '62250131', 'PORTOCARRERO', 'VELASQUEZ', 'YELSIN ANDERSON', 'M', '999999999', '', '', '', '', '62250131@institutocajas.edu.pe', '0000-00-00', '', NULL),
(441, NULL, '62259534', 'ALARCON', 'PALOMINO', 'JOSUE WILLIAMS', 'M', '934266356', '', '', '', '', '62259534@institutocajas.edu.pe', '0000-00-00', '', NULL),
(442, NULL, '62276444', 'TAPARA', 'PARIONA', 'JUAN CARLOS', 'M', '929039324', '', '', '', '', '62276444@institutocajas.edu.pe', '0000-00-00', '', NULL),
(443, NULL, '62290890', 'PAUCAR', 'TEJEDA', 'NAIRA ARACELI', 'F', '929691115', '', '', '', '', '62290890@institutocajas.edu.pe', '0000-00-00', '', NULL),
(444, NULL, '62302407', 'PORRAS', 'TAYPE', 'JEFF JOHAN', 'M', '988666166', '', '', '', '', '62302407@institutocajas.edu.pe', '0000-00-00', '', NULL),
(445, NULL, '62426287', 'CRUZ', 'PEREZ', 'DANIEL MARLON', 'M', '938239296', '', '', '', '', '62426287@institutocajas.edu.pe', '0000-00-00', '', NULL),
(446, NULL, '62615553', 'RAMIREZ', 'HUAMAN', 'ELTHON JHON', 'M', '942533172', '', '', '', '', '62615553@institutocajas.edu.pe', '0000-00-00', '', NULL),
(447, NULL, '62615688', 'YANGALI', 'TAIPE', 'DANIEL ANTONE', 'M', '917885821', '', '', '', '', '62615688@institutocajas.edu.pe', '0000-00-00', '', NULL),
(448, NULL, '62772409', 'LABAN', 'PEREYRA', 'YOSET DANIEL', 'M', '926698901', '', '', '', '', '62772409@institutocajas.edu.pe', '0000-00-00', '', NULL),
(449, NULL, '62824764', 'LAZO', 'TORO', 'ANA CECILIA', 'F', '936095679', '', '', '', '', '62824764@institutocajas.edu.pe', '0000-00-00', '', NULL),
(450, NULL, '62831340', 'MENDOZA', 'PAUCAR', 'CARLOS ALBERTO', 'M', '900275978', '', '', '', '', '62831340@institutocajas.edu.pe', '0000-00-00', '', NULL),
(451, NULL, '63079764', 'RAMOS', 'VELIZ', 'NAHOMI LEONELA', 'F', '933795543', '', '', '', '', '63079764@institutocajas.edu.pe', '0000-00-00', '', NULL),
(452, NULL, '63152861', 'AQUISE', 'ZEVALLOS', 'DIEGO JANS', 'M', '974503255', '', '', '', '', '63152861@institutocajas.edu.pe', '0000-00-00', '', NULL),
(453, NULL, '63278397', 'TORRES', 'SUAREZ', 'HENRY RICHARD', 'M', '920213604', '', '', '', '', '63278397@institutocajas.edu.pe', '0000-00-00', '', NULL),
(454, NULL, '63278429', 'NAJERA', 'CAISAHUANA', 'YUDITH ROSSIO', 'F', '987525858', '', '', '', '', '63278429@institutocajas.edu.pe', '0000-00-00', '', NULL),
(455, NULL, '63282651', 'ASTETE', 'ALMIDON', 'ESTHER ADALIA', 'F', '964996998', '', '', '', '', '63282651@institutocajas.edu.pe', '0000-00-00', '', NULL),
(456, NULL, '63794458', 'ROJAS', 'ROMAN', 'MARIELA', 'F', '944040994', '', '', '', '', '63794458@institutocajas.edu.pe', '0000-00-00', '', NULL),
(457, NULL, '70019109', 'FABIAN', 'ROJAS', 'JHON WILLIAMS', 'M', '930815990', '', '', '', '', '70019109@institutocajas.edu.pe', '0000-00-00', '', NULL),
(458, NULL, '70097574', 'SORIANO', 'TIMOTEO', 'RONALDO CESAR', 'M', '935961154', '', '', '', '', '70097574@institutocajas.edu.pe', '0000-00-00', '', NULL),
(459, NULL, '70097598', 'LANDEO', 'TOCAS', 'YORDIN ANGEL', 'M', '920254301', '', '', '', '', '70097598@institutocajas.edu.pe', '0000-00-00', '', NULL),
(460, NULL, '70131292', 'SANCHEZ', 'CLEMENTE', 'LESTER JOSSEPH', 'M', '923048715', '', '', '', '', '70131292@institutocajas.edu.pe', '0000-00-00', '', NULL),
(461, NULL, '70164185', 'ORDAYA', 'ROSALES', 'JAIME PEDRO', 'M', '916042363', '', '', '', '', '70164185@institutocajas.edu.pe', '0000-00-00', '', NULL),
(462, NULL, '70261469', 'PALOMINO', 'COSME', 'JOSE ENRIQUE', 'M', '932752491', '', '', '', '', '70261469@institutocajas.edu.pe', '0000-00-00', '', NULL),
(463, NULL, '70293484', 'OJEDA', 'LEON', 'GABY EPIFANIA', 'F', '996694722', '', '', '', '', '70293484@institutocajas.edu.pe', '0000-00-00', '', NULL),
(464, NULL, '70296794', 'MANRIQUE', 'MALPICA', 'LUIS ANGEL', 'M', '982242937', '', '', '', '', '70296794@institutocajas.edu.pe', '0000-00-00', '', NULL),
(465, NULL, '70302213', 'PALOMINO', 'BARBA', 'JOSE MANUEL', 'M', '931584467', '', '', '', '', '70302213@institutocajas.edu.pe', '0000-00-00', '', NULL),
(466, NULL, '70302245', 'BALTAZAR', 'VILCA', 'BRYAN ANTONIO', 'M', '999999325', '', '', '', '', '70302245@institutocajas.edu.pe', '0000-00-00', '', NULL),
(467, NULL, '70308211', 'ALVAREZ', 'POVIS', 'ANTONY JOSE', 'M', '931364392', '', '', '', '', '70308211@institutocajas.edu.pe', '0000-00-00', '', NULL),
(468, NULL, '70322496', 'LLANTOY', 'MEZA', 'JORGE ADRIAN', 'M', '999999999', '', '', '', '', '70322496@institutocajas.edu.pe', '0000-00-00', '', NULL),
(469, NULL, '70346573', 'CASTILLO', 'MUNIVES', 'CRISTIAN ROBINSON', 'M', '981523008', '', '', '', '', '70346573@institutocajas.edu.pe', '0000-00-00', '', NULL),
(470, NULL, '70400312', 'MAYTA', 'CAMPOS', 'VICTOR RAUL', 'M', '966806128', '', '', '', '', '70400312@institutocajas.edu.pe', '0000-00-00', '', NULL),
(471, NULL, '70531769', 'RIVAS', 'PIZARRO', 'BRAYAN SEBASTIAN', 'M', '922694680', '', '', '', '', '70531769@institutocajas.edu.pe', '0000-00-00', '', NULL),
(472, NULL, '70693427', 'FALCONI', 'LIRA', 'CARLOS ALFREDO', 'M', '987755125', '', '', '', '', '70693427@institutocajas.edu.pe', '0000-00-00', '', NULL),
(473, NULL, '70805510', 'JAVIER', '?AHUI', 'RUSBEL', 'M', '921015426', '', '', '', '', '70805510@institutocajas.edu.pe', '0000-00-00', '', NULL),
(474, NULL, '70853742', 'VENTOCILLA', 'SARAVIA', 'ALEXANDER AUDI', 'M', '921773801', '', '', '', '', '70853742@institutocajas.edu.pe', '0000-00-00', '', NULL),
(475, NULL, '70853743', 'ROSALES', 'YAPIAS', 'MANUEL FRANZ', 'M', '974718412', '', '', '', '', '70853743@institutocajas.edu.pe', '0000-00-00', '', NULL),
(476, NULL, '70934444', 'CONDOR', 'AGUILAR', 'JORGE ANDERSON', 'M', '921011461', '', '', '', '', '70934444@institutocajas.edu.pe', '0000-00-00', '', NULL),
(477, NULL, '70934467', 'MARCELO', 'SAEZ', 'EFRAIN JESUS', 'M', '912332778', '', '', '', '', '70934467@institutocajas.edu.pe', '0000-00-00', '', NULL),
(478, NULL, '70943129', 'TELLO', 'ARONI', 'JULIO CESAR', 'M', '964351598', '', '', '', '', '70943129@institutocajas.edu.pe', '0000-00-00', '', NULL),
(479, NULL, '70950349', 'INGA', 'ORE', 'MARK FRANSHESCO', 'M', '987275210', '', '', '', '', '70950349@institutocajas.edu.pe', '0000-00-00', '', NULL),
(480, NULL, '70996026', 'RAMOS', 'CARBAJAL', 'JUAN JOSE', 'M', '949194409', '', '', '', '', '70996026@institutocajas.edu.pe', '0000-00-00', '', NULL),
(481, NULL, '71102582', 'TORRES', 'SAU?I', 'DIEGO ELISEO', 'M', '939331333', '', '', '', '', '71102582@institutocajas.edu.pe', '0000-00-00', '', NULL),
(482, NULL, '71107867', 'PAUCAR', 'RODRIGUEZ', 'JEFFERSON DESIDERIO', 'M', '902231266', '', '', '', '', '71107867@institutocajas.edu.pe', '0000-00-00', '', NULL),
(483, NULL, '71119186', 'MATEO', 'REYNA', 'BRYAN JHOJAN', 'M', '930895069', '', '', '', '', '71119186@institutocajas.edu.pe', '0000-00-00', '', NULL),
(484, NULL, '71123229', 'ROQUE', 'HURTADO', 'ROY JUBER', 'M', '912741101', '', '', '', '', '71123229@institutocajas.edu.pe', '0000-00-00', '', NULL),
(485, NULL, '71126494', 'REYES', 'QUINTO', 'GIAN CARLOS', 'M', '951669185', '', '', '', '', '71126494@institutocajas.edu.pe', '0000-00-00', '', NULL),
(486, NULL, '71127611', 'VASQUEZ', 'UCHARIMA', 'ALDAIR YAIR', 'M', '954070108', '', '', '', '', '71127611@institutocajas.edu.pe', '0000-00-00', '', NULL),
(487, NULL, '71127774', 'UNTIVEROS', 'MAURI', 'BRAYAN JULIAN', 'M', '966325832', '', '', '', '', '71127774@institutocajas.edu.pe', '0000-00-00', '', NULL),
(488, NULL, '71127858', 'CERRON', 'GARCIA', 'JOSE LUIS', 'M', '914720216', '', '', '', '', '71127858@institutocajas.edu.pe', '0000-00-00', '', NULL),
(489, NULL, '71127878', 'ALCOCER', 'PALOMINO', 'ANDERSON LUIS', 'M', '942025542', '', '', '', '', '71127878@institutocajas.edu.pe', '0000-00-00', '', NULL),
(490, NULL, '71183936', 'ARISTE', 'PITOY', 'CLEBER AMERICO', 'M', '953236314', '', '', '', '', '71183936@institutocajas.edu.pe', '0000-00-00', '', NULL),
(491, NULL, '71190421', 'TAIPE', 'VELASQUEZ', 'JOS?AS BENJAM?N', 'M', '904699590', '', '', '', '', '71190421@institutocajas.edu.pe', '0000-00-00', '', NULL),
(492, NULL, '71214044', 'ASTO', 'ORIHUELA', 'ENGEMBERTH GIOVANNI', 'M', '903147171', '', '', '', '', '71214044@institutocajas.edu.pe', '0000-00-00', '', NULL),
(493, NULL, '71217153', 'TENORIO', 'SULCA', 'DANIEL JONATHAN', 'M', '913752756', '', '', '', '', '71217153@institutocajas.edu.pe', '0000-00-00', '', NULL),
(494, NULL, '71221129', 'HERRERA', 'VIVAR', 'JOSMY NAYELY', 'F', '932670206', '', '', '', '', '71221129@institutocajas.edu.pe', '0000-00-00', '', NULL),
(495, NULL, '71222946', 'LOPEZ', 'RIVERA', 'KALEF EDGAR', 'M', '928136925', '', '', '', '', '71222946@institutocajas.edu.pe', '0000-00-00', '', NULL),
(496, NULL, '71229183', 'LEON', 'SALDIVAR', 'LEONEL JHONEL', 'M', '964214644', '', '', '', '', '71229183@institutocajas.edu.pe', '0000-00-00', '', NULL),
(497, NULL, '71241297', 'ALMERCO', 'LAUREANO', 'ZIDANE BRANDY', 'M', '912173537', '', '', '', '', '71241297@institutocajas.edu.pe', '0000-00-00', '', NULL),
(498, NULL, '71244493', 'ARANA', 'GUTIERREZ', 'MAK HUNTUAN', 'M', '926422723', '', '', '', '', '71244493@institutocajas.edu.pe', '0000-00-00', '', NULL),
(499, NULL, '71249029', 'ATAUCUSI', 'HUAMANI', 'JHON ANTONY', 'M', '921972984', '', '', '', '', '71249029@institutocajas.edu.pe', '0000-00-00', '', NULL),
(500, NULL, '71249797', 'COTRINA', 'VALERIO', 'SAUL PATRICK', 'M', '997701605', '', '', '', '', '71249797@institutocajas.edu.pe', '0000-00-00', '', NULL),
(501, NULL, '71268723', 'VELARDE', 'POCCO', 'ROMER', 'M', '997850964', '', '', '', '', '71268723@institutocajas.edu.pe', '0000-00-00', '', NULL),
(502, NULL, '71277867', 'POMACARHUA', 'PALOMINO', 'RUSBEL', 'M', '934551022', '', '', '', '', '71277867@institutocajas.edu.pe', '0000-00-00', '', NULL),
(503, NULL, '71278452', 'INGA', 'YAURI', 'NAIN IDEL', 'M', '930677332', '', '', '', '', '71278452@institutocajas.edu.pe', '0000-00-00', '', NULL),
(504, NULL, '71279925', 'MEZA', 'CCANTO', 'ABRAHAM ELIAS', 'M', '910632467', '', '', '', '', '71279925@institutocajas.edu.pe', '0000-00-00', '', NULL),
(505, NULL, '71280126', 'CCENTE', 'CRISPIN', 'MICHAEL', 'M', '997044365', '', '', '', '', '71280126@institutocajas.edu.pe', '0000-00-00', '', NULL),
(506, NULL, '71280394', 'QUISPE', 'VELARDE', 'ANTONY', 'M', '981407353', '', '', '', '', '71280394@institutocajas.edu.pe', '0000-00-00', '', NULL),
(507, NULL, '71280447', 'VALER', 'RAMOS', 'ELIANE ANGIE', 'F', '987923317', '', '', '', '', '71280447@institutocajas.edu.pe', '0000-00-00', '', NULL),
(508, NULL, '71310339', 'QUISPE', 'QUISPE', 'CRISTHIAN', 'M', '974567606', '', '', '', '', '71310339@institutocajas.edu.pe', '0000-00-00', '', NULL),
(509, NULL, '71312970', 'CRISTOBAL', 'TORPOCO', 'JUNIOR MAICOL', 'M', '981360545', '', '', '', '', '71312970@institutocajas.edu.pe', '0000-00-00', '', NULL),
(510, NULL, '71313187', 'DE LA CRUZ', 'MATA', 'FELIX DANIEL', 'M', '999999999', '', '', '', '', '71313187@institutocajas.edu.pe', '0000-00-00', '', NULL),
(511, NULL, '71316414', 'QUISPE', 'PAITAN', 'YESBETH', 'F', '960124890', '', '', '', '', '71316414@institutocajas.edu.pe', '0000-00-00', '', NULL),
(512, NULL, '71349740', 'PASTRANA', 'CAMASCCA', 'FRANK DANIEL', 'M', '974723151', '', '', '', '', '71349740@institutocajas.edu.pe', '0000-00-00', '', NULL),
(513, NULL, '71349748', 'PASTRANA', 'CAMASCCA', 'JUNIOR DANIEL', 'M', '940778083', '', '', '', '', '71349748@institutocajas.edu.pe', '0000-00-00', '', NULL),
(514, NULL, '71350180', 'QUISPE', 'CURO', 'MIGUEL', 'M', '925465575', '', '', '', '', '71350180@institutocajas.edu.pe', '0000-00-00', '', NULL),
(515, NULL, '71375733', 'CRISPIN', 'DE LA CRUZ', 'LUIS GERMAN', 'M', '965230689', '', '', '', '', '71375733@institutocajas.edu.pe', '0000-00-00', '', NULL),
(516, NULL, '71375742', 'CRISPIN', 'DE LA CRUZ', 'JAIME JESUS', 'M', '974357124', '', '', '', '', '71375742@institutocajas.edu.pe', '0000-00-00', '', NULL),
(517, NULL, '71375867', 'ORIHUELA', 'FERNANDEZ', 'OSCAR SILVIO', 'M', '986836992', '', '', '', '', '71375867@institutocajas.edu.pe', '0000-00-00', '', NULL),
(518, NULL, '71379885', 'ALCANTARA', 'ORIHUELA', 'HEBER', 'M', '918371973', '', '', '', '', '71379885@institutocajas.edu.pe', '0000-00-00', '', NULL),
(519, NULL, '71379911', 'CAMASCCA', 'PASTRANA', 'MAYCOL FERNANDO', 'M', '980771464', '', '', '', '', '71379911@institutocajas.edu.pe', '0000-00-00', '', NULL),
(520, NULL, '71390394', 'VILCATOMA', 'RAMOS', 'DANIEL', 'M', '944921501', '', '', '', '', '71390394@institutocajas.edu.pe', '0000-00-00', '', NULL),
(521, NULL, '71398433', 'HUARCAYA', 'ENRIQUEZ', 'CESAR', 'M', '967736659', '', '', '', '', '71398433@institutocajas.edu.pe', '0000-00-00', '', NULL),
(522, NULL, '71408852', 'ROJAS', 'RUIZ', 'LUIS FERNANDO', 'M', '922639191', '', '', '', '', '71408852@institutocajas.edu.pe', '0000-00-00', '', NULL),
(523, NULL, '71419660', 'GOMEZ', 'CRUZ', 'ELVIS LEONARDO', 'M', '944216520', '', '', '', '', '71419660@institutocajas.edu.pe', '0000-00-00', '', NULL),
(524, NULL, '71431993', 'HUAMANI', 'ZACARIAS', 'MARYORIT BERALUZ', 'F', '999999999', '', '', '', '', '71431993@institutocajas.edu.pe', '0000-00-00', '', NULL),
(525, NULL, '71431995', 'MONTES', 'MONTES', 'JOSE RODOLFO', 'M', '921573747', '', '', '', '', '71431995@institutocajas.edu.pe', '0000-00-00', '', NULL),
(526, NULL, '71432606', 'TOLENTINO', 'TOCAS', 'JHON FERNANDO', 'M', '912129311', '', '', '', '', '71432606@institutocajas.edu.pe', '0000-00-00', '', NULL),
(527, NULL, '71433182', 'ALIAGA', 'VERA', 'KEVIN GABRIEL', 'M', '925237763', '', '', '', '', '71433182@institutocajas.edu.pe', '0000-00-00', '', NULL),
(528, NULL, '71435693', 'BENDEZU', 'CERRON', 'CLOTTEP ANDERLEHT', 'M', '931370483', '', '', '', '', '71435693@institutocajas.edu.pe', '0000-00-00', '', NULL),
(529, NULL, '71438201', 'PONCE', 'ORE', 'NOEMI INGRID', 'F', '922880657', '', '', '', '', '71438201@institutocajas.edu.pe', '0000-00-00', '', NULL),
(530, NULL, '71438263', 'VICENTE', 'AVILA', 'KOBEN DAYGORO', 'M', '925503961', '', '', '', '', '71438263@institutocajas.edu.pe', '0000-00-00', '', NULL),
(531, NULL, '71443146', 'INCHE', 'OSORES', 'BRANDON GABRIEL', 'M', '994923492', '', '', '', '', '71443146@institutocajas.edu.pe', '0000-00-00', '', NULL),
(532, NULL, '71450145', 'ROMAN', 'OCHOA', 'FLOR JANELA', 'F', '944923644', '', '', '', '', '71450145@institutocajas.edu.pe', '0000-00-00', '', NULL),
(533, NULL, '71460676', 'GOMEZ', 'CATAY', 'ANDERSON FELIX', 'M', '928793590', '', '', '', '', '71460676@institutocajas.edu.pe', '0000-00-00', '', NULL),
(534, NULL, '71468748', 'ARIAS', 'MALLQUI', 'FERNANDO JAIME', 'M', '972600413', '', '', '', '', '71468748@institutocajas.edu.pe', '0000-00-00', '', NULL),
(535, NULL, '71468762', 'ASTOCURI', 'INGA', 'JASMIN ALLIX', 'F', '944394100', '', '', '', '', '71468762@institutocajas.edu.pe', '0000-00-00', '', NULL),
(536, NULL, '71470286', 'QUISPE', 'CURO', 'WILBER RICARDO', 'M', '963876645', '', '', '', '', '71470286@institutocajas.edu.pe', '0000-00-00', '', NULL),
(537, NULL, '71473038', 'CABEZAS', 'GABRIEL', 'YULISA MABEL', 'F', '939810936', '', '', '', '', '71473038@institutocajas.edu.pe', '0000-00-00', '', NULL),
(538, NULL, '71473043', 'CAMPOS', 'MEZA', 'ERIK ABEL', 'M', '970169100', '', '', '', '', '71473043@institutocajas.edu.pe', '0000-00-00', '', NULL),
(539, NULL, '71483853', 'ESPINOZA', 'PE?ALOZA', 'WILDER', 'M', '916032059', '', '', '', '', '71483853@institutocajas.edu.pe', '0000-00-00', '', NULL),
(540, NULL, '71502077', 'PEREZ', 'MARTINEZ', 'LEONET BENNER', 'M', '999999999', '', '', '', '', '71502077@institutocajas.edu.pe', '0000-00-00', '', NULL),
(541, NULL, '71526355', 'TICLLACURI', 'QUISPE', 'RUSBELL', 'M', '949290398', '', '', '', '', '71526355@institutocajas.edu.pe', '0000-00-00', '', NULL),
(542, NULL, '71538508', 'YARANGA', 'HUAMANI', 'MARTHA', 'F', '993254686', '', '', '', '', '71538508@institutocajas.edu.pe', '0000-00-00', '', NULL),
(543, NULL, '71541462', 'SAYAS', 'LOVERA', 'JOHAN', 'M', '961350495', '', '', '', '', '71541462@institutocajas.edu.pe', '0000-00-00', '', NULL),
(544, NULL, '71543109', 'PERALTA', 'HUARACA', 'MARCELINO', 'M', '928819801', '', '', '', '', '71543109@institutocajas.edu.pe', '0000-00-00', '', NULL),
(545, NULL, '71546784', 'HINOSTROZA', 'MAYTA', 'JAVIER AUGUSTO', 'M', '963830051', '', '', '', '', '71546784@institutocajas.edu.pe', '0000-00-00', '', NULL),
(546, NULL, '71581235', 'SERAS', 'QUINTANA', 'JHON FRANCK', 'M', '924959193', '', '', '', '', '71581235@institutocajas.edu.pe', '0000-00-00', '', NULL),
(547, NULL, '71581269', 'ARROYO', 'NU?EZ', 'LUIS MARIO', 'M', '999999999', '', '', '', '', '71581269@institutocajas.edu.pe', '0000-00-00', '', NULL),
(548, NULL, '71581504', 'BERROSPI', 'NINAHUANCA', 'BETXI MILAGROS', 'F', '931089019', '', '', '', '', '71581504@institutocajas.edu.pe', '0000-00-00', '', NULL),
(549, NULL, '71582431', 'ALIAGA', 'PEREZ', 'JHOSEP WILLIAM', 'M', '901298417', '', '', '', '', '71582431@institutocajas.edu.pe', '0000-00-00', '', NULL),
(550, NULL, '71598363', 'PRADO', 'HINOSTROZA', 'ANTONIO', 'M', '991175654', '', '', '', '', '71598363@institutocajas.edu.pe', '0000-00-00', '', NULL),
(551, NULL, '71600677', 'ENRIQUEZ', 'ANGUIZ', 'ELIAS', 'M', '943308957', '', '', '', '', '71600677@institutocajas.edu.pe', '0000-00-00', '', NULL),
(552, NULL, '71600686', 'ENRIQUEZ', 'ANGUIZ', 'CLINTON TITO', 'M', '932465598', '', '', '', '', '71600686@institutocajas.edu.pe', '0000-00-00', '', NULL),
(553, NULL, '71607320', 'ALMINAGORDA', 'ALMERCO', 'ANSELMO', 'M', '972805067', '', '', '', '', '71607320@institutocajas.edu.pe', '0000-00-00', '', NULL),
(554, NULL, '71610478', 'MARCOS', 'CRUZ', 'ANTUANEL', 'F', '919525736', '', '', '', '', '71610478@institutocajas.edu.pe', '0000-00-00', '', NULL),
(555, NULL, '71645324', 'SANCHEZ', 'GIRON', 'LUIS ANGEL', 'M', '947033268', '', '', '', '', '71645324@institutocajas.edu.pe', '0000-00-00', '', NULL),
(556, NULL, '71665984', 'MARCOS', 'BAZAN', 'ALEXANDER JESUS', 'M', '904260479', '', '', '', '', '71665984@institutocajas.edu.pe', '0000-00-00', '', NULL),
(557, NULL, '71692518', 'ESLADO', 'MEJICO', 'FREDDY CARLOS', 'M', '907373780', '', '', '', '', '71692518@institutocajas.edu.pe', '0000-00-00', '', NULL),
(558, NULL, '71700275', 'PALACIOS', 'RODRIGUEZ', 'YOURBY FAUSTINO', 'M', '999999999', '', '', '', '', '71700275@institutocajas.edu.pe', '0000-00-00', '', NULL),
(559, NULL, '71704122', 'HUATUCO', 'GOMEZ', 'STING BUMER', 'M', '976907382', '', '', '', '', '71704122@institutocajas.edu.pe', '0000-00-00', '', NULL),
(560, NULL, '71704197', 'ORE', 'FABIAN', 'YAREN MAICOL', 'M', '942548477', '', '', '', '', '71704197@institutocajas.edu.pe', '0000-00-00', '', NULL),
(561, NULL, '71704210', 'GUEVARA', 'MENDOZA', 'VERONICA LIZETH', 'F', '945856840', '', '', '', '', '71704210@institutocajas.edu.pe', '0000-00-00', '', NULL),
(562, NULL, '71713694', 'HERNANDEZ', 'GAMBOA', 'LUIS ALBERTO', 'M', '999999999', '', '', '', '', '71713694@institutocajas.edu.pe', '0000-00-00', '', NULL),
(563, NULL, '71714505', 'REYNOSO', 'RIVERA', 'LADY ESTEFANY', 'F', '972445998', '', '', '', '', '71714505@institutocajas.edu.pe', '0000-00-00', '', NULL),
(564, NULL, '71718389', 'MERCADO', 'BECERRA', 'JUAN PAULINO', 'M', '933683521', '', '', '', '', '71718389@institutocajas.edu.pe', '0000-00-00', '', NULL),
(565, NULL, '71720875', 'ZAMUDIO', 'MARQUEZ', 'RIGOBERTO', 'M', '922134329', '', '', '', '', '71720875@institutocajas.edu.pe', '0000-00-00', '', NULL),
(566, NULL, '71729324', 'CABRERA', 'ROJAS', 'JOHN ROB LEE', 'M', '922703811', '', '', '', '', '71729324@institutocajas.edu.pe', '0000-00-00', '', NULL),
(567, NULL, '71735870', 'ALIAGA', 'HINOSTROZA', 'ERYK JOSE', 'M', '965953627', '', '', '', '', '71735870@institutocajas.edu.pe', '0000-00-00', '', NULL),
(568, NULL, '71735871', 'ALIAGA', 'HINOSTROZA', 'ELIAS JHERSON', 'M', '965953627', '', '', '', '', '71735871@institutocajas.edu.pe', '0000-00-00', '', NULL),
(569, NULL, '71742658', 'VENTURA', 'CHAMORRO', 'DELIA', 'F', '980682752', '', '', '', '', '71742658@institutocajas.edu.pe', '0000-00-00', '', NULL),
(570, NULL, '71745340', 'ZU?IGA', 'HUAMAN', 'SLIM LIZZY', 'F', '925737868', '', '', '', '', '71745340@institutocajas.edu.pe', '0000-00-00', '', NULL),
(571, NULL, '71764351', 'LAZO', 'GOMEZ', 'JUAN CARLOS', 'M', '983427232', '', '', '', '', '71764351@institutocajas.edu.pe', '0000-00-00', '', NULL),
(572, NULL, '71772053', '?AHUI', 'HUACHACA', 'YOLA ELSA', 'F', '964074511', '', '', '', '', '71772053@institutocajas.edu.pe', '0000-00-00', '', NULL),
(573, NULL, '71798663', 'OSORIO', 'HINOJOSA', 'YEFERSON MARLON', 'M', '948541953', '', '', '', '', '71798663@institutocajas.edu.pe', '0000-00-00', '', NULL),
(574, NULL, '71803764', 'VELARDE', 'GAMBOA', 'ROYER', 'M', '975424439', '', '', '', '', '71803764@institutocajas.edu.pe', '0000-00-00', '', NULL),
(575, NULL, '71805199', 'GAMBOA', 'SANTANA', 'DENYLSON ISACC', 'M', '924668014', '', '', '', '', '71805199@institutocajas.edu.pe', '0000-00-00', '', NULL),
(576, NULL, '71808551', 'ARAUJO', 'YANGALI', 'FLOR MILAGROS', 'F', '915185131', '', '', '', '', '71808551@institutocajas.edu.pe', '0000-00-00', '', NULL),
(577, NULL, '71808615', 'PARIONA', 'PARIONA', 'KENYO JOHANY', 'M', '901702261', '', '', '', '', '71808615@institutocajas.edu.pe', '0000-00-00', '', NULL),
(578, NULL, '71809135', 'ALIAGA', 'YAURI', 'JHEAN PIER', 'M', '906281554', '', '', '', '', '71809135@institutocajas.edu.pe', '0000-00-00', '', NULL),
(579, NULL, '71810631', 'LLANCARI', 'BALDEON', 'EDUARDO JESUS', 'M', '926980875', '', '', '', '', '71810631@institutocajas.edu.pe', '0000-00-00', '', NULL),
(580, NULL, '71811080', 'GASPAR', 'SAEZ', 'JENRY', 'M', '981485742', '', '', '', '', '71811080@institutocajas.edu.pe', '0000-00-00', '', NULL),
(581, NULL, '71812566', 'VILCA', 'CUSI', 'MADELI', 'F', '991016767', '', '', '', '', '71812566@institutocajas.edu.pe', '0000-00-00', '', NULL),
(582, NULL, '71818101', 'ESPIRITU', 'CARDENAS', 'RUIZ ARNALDO', 'M', '991707045', '', '', '', '', '71818101@institutocajas.edu.pe', '0000-00-00', '', NULL),
(583, NULL, '71818362', 'SANTOS', 'RODRIGUEZ', 'SANDRO PAUL', 'M', '921265482', '', '', '', '', '71818362@institutocajas.edu.pe', '0000-00-00', '', NULL),
(584, NULL, '71828441', 'BALVIN', 'MU?ICO', 'YULER JORGE', 'M', '957931823', '', '', '', '', '71828441@institutocajas.edu.pe', '0000-00-00', '', NULL),
(585, NULL, '71828744', 'CANO', 'FERNANDEZ', 'GENESIS LIBERTAD', 'F', '947430280', '', '', '', '', '71828744@institutocajas.edu.pe', '0000-00-00', '', NULL),
(586, NULL, '71848358', 'TOVAR', 'CHAHUAYLACC', 'KEVIN CAROL', 'M', '916858882', '', '', '', '', '71848358@institutocajas.edu.pe', '0000-00-00', '', NULL),
(587, NULL, '71849439', 'LUIS', 'GOMEZ', 'JAMYL BRANDOM', 'M', '920504763', '', '', '', '', '71849439@institutocajas.edu.pe', '0000-00-00', '', NULL),
(588, NULL, '71852059', 'ECHAVARRIA', 'CCORA', 'MARIBEL MARCELINA', 'F', '999999999', '', '', '', '', '71852059@institutocajas.edu.pe', '0000-00-00', '', NULL),
(589, NULL, '71856536', 'CASO', 'TEJEDA', 'KELY KETTY', 'F', '942562019', '', '', '', '', '71856536@institutocajas.edu.pe', '0000-00-00', '', NULL),
(590, NULL, '71863913', 'LAUREANO', 'MARAVI', 'STYVEN CHRISTIAN', 'M', '943502279', '', '', '', '', '71863913@institutocajas.edu.pe', '0000-00-00', '', NULL),
(591, NULL, '71868658', 'REZA', 'LEIVA', 'LUIS REINALDO', 'M', '982042343', '', '', '', '', '71868658@institutocajas.edu.pe', '0000-00-00', '', NULL),
(592, NULL, '71868753', 'GASPAR', 'MACHACUAY', 'MARIA FERNANDA', 'F', '917123160', '', '', '', '', '71868753@institutocajas.edu.pe', '0000-00-00', '', NULL),
(593, NULL, '71873179', 'GRANADOS', 'CAVERO', 'YUSELY MARISELA', 'F', '967087120', '', '', '', '', '71873179@institutocajas.edu.pe', '0000-00-00', '', NULL),
(594, NULL, '71873198', 'MANTARI', 'MORALES', 'LUIS ENRIQUE', 'M', '978488496', '', '', '', '', '71873198@institutocajas.edu.pe', '0000-00-00', '', NULL),
(595, NULL, '71873445', 'TORRES', 'CUADRADO', 'RENZO RAUL', 'M', '932323426', '', '', '', '', '71873445@institutocajas.edu.pe', '0000-00-00', '', NULL),
(596, NULL, '71877204', 'CALDERON', 'DE LA CRUZ', 'ROLANDO', 'M', '999999999', '', '', '', '', '71877204@institutocajas.edu.pe', '0000-00-00', '', NULL),
(597, NULL, '71877518', 'ALCOCER', 'GONZALES', 'EMILY YOMIRA', 'F', '999999999', '', '', '', '', '71877518@institutocajas.edu.pe', '0000-00-00', '', NULL),
(598, NULL, '71880326', 'GARAY', 'OSCATEGUI', 'HANNSDICK SLEYDERTH', 'M', '950113345', '', '', '', '', '71880326@institutocajas.edu.pe', '0000-00-00', '', NULL),
(599, NULL, '71881950', 'HUAMAN', 'INGA', 'RIQUELME JUAN', 'M', '934889385', '', '', '', '', '71881950@institutocajas.edu.pe', '0000-00-00', '', NULL),
(600, NULL, '71882502', 'HINOSTROZA', 'HILARIO', 'EMELYN MILAGROS', 'F', '929652859', '', '', '', '', 'emhh@iestphuaycan.onmicrosoft.com', '0000-00-00', '', NULL),
(601, NULL, '71885560', 'MERCADO', 'TORRES', 'JEAN PAUL', 'M', '997144496', '', '', '', '', '71885560@institutocajas.edu.pe', '0000-00-00', '', NULL),
(602, NULL, '71885562', 'SUAREZ', 'MERCADO', 'MARLON WILDER', 'M', '902888600', '', '', '', '', '71885562@institutocajas.edu.pe', '0000-00-00', '', NULL),
(603, NULL, '71892510', 'FERNANDEZ', 'BARRETO', 'PEDRO LUIS', 'M', '930561378', '', '', '', '', '71892510@institutocajas.edu.pe', '0000-00-00', '', NULL),
(604, NULL, '71892880', 'VERA', 'JAURIGUE', 'JHEFERSON ROQUE', 'M', '968701757', '', '', '', '', '71892880@institutocajas.edu.pe', '0000-00-00', '', NULL),
(605, NULL, '71921253', 'TAYPE', 'FERNANDEZ', 'ROBERTO', 'M', '973747014', '', '', '', '', '71921253@institutocajas.edu.pe', '0000-00-00', '', NULL),
(606, NULL, '71922520', 'LOYOLA', 'REQUIEN', 'JACK YULER', 'M', '968475067', '', '', '', '', '71922520@institutocajas.edu.pe', '0000-00-00', '', NULL),
(607, NULL, '71938330', 'SANTOS', 'CHANCA', 'MAYCOL', 'M', '900263495', '', '', '', '', '71938330@institutocajas.edu.pe', '0000-00-00', '', NULL),
(608, NULL, '71941521', 'SANCHEZ', 'PABLO', 'GRACIELA CENCIA', 'F', '936949244', '', '', '', '', '71941521@institutocajas.edu.pe', '0000-00-00', '', NULL),
(609, NULL, '71944025', 'ORE', 'DE LA CRUZ', 'JAREN JHONY', 'M', '978659891', '', '', '', '', '71944025@institutocajas.edu.pe', '0000-00-00', '', NULL),
(610, NULL, '71947427', 'CASTRO', 'PAUCAR', 'PAHOLO SANGOY', 'M', '900429131', '', '', '', '', '71947427@institutocajas.edu.pe', '0000-00-00', '', NULL),
(611, NULL, '71949756', 'HUACHUILLCA', 'QUISPE', 'JHON MAYCOL', 'M', '951282679', '', '', '', '', '71949756@institutocajas.edu.pe', '0000-00-00', '', NULL),
(612, NULL, '71958149', 'ASTO', 'SOLANO', 'JOEL EDISON', 'M', '914113237', '', '', '', '', '71958149@institutocajas.edu.pe', '0000-00-00', '', NULL),
(613, NULL, '71964548', 'BALVIN', 'CHAMORRO', 'ROY ANGEL', 'M', '988044667', '', '', '', '', '71964548@institutocajas.edu.pe', '0000-00-00', '', NULL),
(614, NULL, '71966314', 'POCOMUCHA', 'TORRES', 'DENILSON', 'M', '923994445', '', '', '', '', '71966314@institutocajas.edu.pe', '0000-00-00', '', NULL),
(615, NULL, '71966362', 'PAEZ', 'CONTRERAS', 'YOHAN NIVER', 'M', '965242366', '', '', '', '', '71966362@institutocajas.edu.pe', '0000-00-00', '', NULL),
(616, NULL, '71966409', 'BARZOLA', 'ZAPATA', 'ADDERLYN ALFREDO', 'M', '964586324', '', '', '', '', '71966409@institutocajas.edu.pe', '0000-00-00', '', NULL),
(617, NULL, '71968679', 'GAGO', 'PAEZ', 'LUIS MAYK', 'M', '960841620', '', '', '', '', '71968679@institutocajas.edu.pe', '0000-00-00', '', NULL),
(618, NULL, '71968703', 'PONCE', 'ALCOSER', 'FRANKLIN JACK', 'M', '916432589', '', '', '', '', '71968703@institutocajas.edu.pe', '0000-00-00', '', NULL),
(619, NULL, '71970361', 'AYALA', 'FIGUEROA', 'ALEXANDER JEREMY', 'M', '925998680', '', '', '', '', '71970361@institutocajas.edu.pe', '0000-00-00', '', NULL),
(620, NULL, '71972015', 'GASPAR', 'CONTRERAS', 'ORLANDO', 'M', '916588876', '', '', '', '', '71972015@institutocajas.edu.pe', '0000-00-00', '', NULL),
(621, NULL, '71972022', 'CHUQUIMANTARI', 'LAURA', 'GABRIELA ZORAIDA', 'F', '960644988', '', '', '', '', '71972022@institutocajas.edu.pe', '0000-00-00', '', NULL),
(622, NULL, '71975029', 'CACERES', 'AVILA', 'CARLOS DANIEL', 'M', '902647584', '', '', '', '', '71975029@institutocajas.edu.pe', '0000-00-00', '', NULL),
(623, NULL, '71976334', 'ALCOSER', 'HERRERA', 'ROSEEL BRAYAN', 'M', '900277082', '', '', '', '', '71976334@institutocajas.edu.pe', '0000-00-00', '', NULL),
(624, NULL, '71978775', 'HUAYTA', 'PAEZ', 'JOSE MIGUEL', 'M', '910074393', '', '', '', '', '71978775@institutocajas.edu.pe', '0000-00-00', '', NULL),
(625, NULL, '71988658', 'YALE', 'PONCE', 'CHRISTOFER ANTONY', 'M', '973873114', '', '', '', '', '71988658@institutocajas.edu.pe', '0000-00-00', '', NULL),
(626, NULL, '71990859', 'CAMPOS', 'USCAMAYTA', 'YUDITH', 'F', '999999999', '', '', '', '', '71990859@institutocajas.edu.pe', '0000-00-00', '', NULL),
(627, NULL, '71991394', 'PONCE', 'ORDO?EZ', 'KEYLA BETSY', 'F', '928258170', '', '', '', '', '71991394@institutocajas.edu.pe', '0000-00-00', '', NULL),
(628, NULL, '71991403', 'CONTRERAS', 'CHIPANA', 'HENRY NICANOR', 'M', '931110426', '', '', '', '', '71991403@institutocajas.edu.pe', '0000-00-00', '', NULL),
(629, NULL, '71996080', 'CABRERA', 'BARJA', 'ELVIS ESAU', 'M', '906234209', '', '', '', '', '71996080@institutocajas.edu.pe', '0000-00-00', '', NULL),
(630, NULL, '71999029', 'GASPAR', 'CUBA', 'DAVID BRAYAN', 'M', '936464654', '', '', '', '', '71999029@institutocajas.edu.pe', '0000-00-00', '', NULL),
(631, NULL, '71999404', 'ESTRELLA', 'HIDALGO', 'DENILSON FRANCISCO', 'M', '927521608', '', '', '', '', '71999404@institutocajas.edu.pe', '0000-00-00', '', NULL),
(632, NULL, '72002621', 'GUERRA', 'PONCE', 'JEAN PIERE', 'M', '915941215', '', '', '', '', '72002621@institutocajas.edu.pe', '0000-00-00', '', NULL),
(633, NULL, '72007440', 'QUISPE', 'SACHA', 'JOAQUIN MATEO', 'M', '929831658', '', '', '', '', '72007440@institutocajas.edu.pe', '0000-00-00', '', NULL),
(634, NULL, '72007522', 'HERRERA', 'YA?AC', 'ROBERTO CARLOS', 'M', '999999999', '', '', '', '', '72007522@institutocajas.edu.pe', '0000-00-00', '', NULL),
(635, NULL, '72011016', 'CONTRERAS', 'PRETIL', 'JIMY DAVID', 'M', '942150031', '', '', '', '', '72011016@institutocajas.edu.pe', '0000-00-00', '', NULL),
(636, NULL, '72011423', 'CHIPANA', 'SUAZO', 'JHERICO JHOSEP', 'M', '935444044', '', '', '', '', '72011423@institutocajas.edu.pe', '0000-00-00', '', NULL),
(637, NULL, '72023511', 'TAIPE', 'ESPINOZA', 'RONALD', 'M', '907549996', '', '', '', '', '72023511@institutocajas.edu.pe', '0000-00-00', '', NULL),
(638, NULL, '72034947', 'TOVAR', 'ACU?A', 'MIGUEL ANGEL', 'M', '961874388', '', '', '', '', '72034947@institutocajas.edu.pe', '0000-00-00', '', NULL),
(639, NULL, '72042305', 'ESPINOZA', 'HUILLCAS', 'ELMER', 'M', '927859040', '', '', '', '', '72042305@institutocajas.edu.pe', '0000-00-00', '', NULL),
(640, NULL, '72049157', 'DE LA CRUZ', 'CCENTE', 'JEYSON BRAYAN', 'M', '950832064', '', '', '', '', '72049157@institutocajas.edu.pe', '0000-00-00', '', NULL),
(641, NULL, '72082534', 'HERMITA?O', 'HIDALGO', 'NAYELI JEOJANA', 'F', '990133402', '', '', '', '', '72082534@institutocajas.edu.pe', '0000-00-00', '', NULL),
(642, NULL, '72102690', 'CHIRINOS', 'PUCUHUAYLA', 'CHRISTIAN GERARDO', 'M', '916426224', '', '', '', '', '72102690@institutocajas.edu.pe', '0000-00-00', '', NULL),
(643, NULL, '72109395', 'ALCANTARA', 'CHUQUILLANQUI', 'YULI?O', 'M', '947798842', '', '', '', '', '72109395@institutocajas.edu.pe', '0000-00-00', '', NULL),
(644, NULL, '72109405', 'ALCANTARA', 'CHUQUILLANQUI', 'ERIK', 'M', '989889899', '', '', '', '', '72109405@institutocajas.edu.pe', '0000-00-00', '', NULL),
(645, NULL, '72111305', 'LINARES', 'APONTE', 'ANDY MAYCOL', 'M', '965977893', '', '', '', '', '72111305@institutocajas.edu.pe', '0000-00-00', '', NULL),
(646, NULL, '72125117', 'FANO', 'IGNACIO', 'RAUL RONALD', 'M', '965409800', '', '', '', '', '72125117@institutocajas.edu.pe', '0000-00-00', '', NULL),
(647, NULL, '72132139', 'FERNANDEZ', 'CIERTO', 'CRISTIAN LUIS', 'M', '954308212', '', '', '', '', '72132139@institutocajas.edu.pe', '0000-00-00', '', NULL),
(648, NULL, '72132140', 'FERNANDEZ', 'CIERTO', 'KEVIN RIDER', 'M', '943181608', '', '', '', '', '72132140@institutocajas.edu.pe', '0000-00-00', '', NULL),
(649, NULL, '72142727', 'BECERRA', 'ROMERO', 'DEYBER ALEXIS', 'M', '926888354', '', '', '', '', '72142727@institutocajas.edu.pe', '0000-00-00', '', NULL),
(650, NULL, '72146442', 'JANAMPA', 'ROJAS', 'JORBY ABDIEL', 'M', '931920280', '', '', '', '', '72146442@institutocajas.edu.pe', '0000-00-00', '', NULL),
(651, NULL, '72182119', 'HINOSTROZA', 'MALLAUPOMA', 'CARLOS CAZSELY', 'M', '989779735', '', '', '', '', '72182119@institutocajas.edu.pe', '0000-00-00', '', NULL),
(652, NULL, '72189188', 'GASPAR', 'AGUILAR', 'ANDERSON JHOEL', 'M', '993370325', '', '', '', '', '72189188@institutocajas.edu.pe', '0000-00-00', '', NULL),
(653, NULL, '72211807', 'ORE', 'MAYTA', 'LUIS JHOVANY', 'M', '920235869', '', '', '', '', '72211807@institutocajas.edu.pe', '0000-00-00', '', NULL),
(654, NULL, '72223191', 'MATOS', 'GALARZA', 'ALEXIS MESIAS', 'M', '963256464', '', '', '', '', '72223191@institutocajas.edu.pe', '0000-00-00', '', NULL),
(655, NULL, '72228730', 'MU?OZ', 'SALVADOR', 'ALEX JILMER', 'M', '978283208', '', '', '', '', '72228730@institutocajas.edu.pe', '0000-00-00', '', NULL);
INSERT INTO `estudiante` (`id`, `ubdistrito`, `dni_est`, `ap_est`, `am_est`, `nom_est`, `sex_est`, `cel_est`, `ubigeodir_est`, `ubigeonac_est`, `dir_est`, `mailp_est`, `maili_est`, `fecnac_est`, `foto_est`, `estado`) VALUES
(656, NULL, '72236296', 'GOMEZ', 'CARLOS', 'EMERSON YEVIN', 'M', '935955396', '', '', '', '', '72236296@institutocajas.edu.pe', '0000-00-00', '', NULL),
(657, NULL, '72236341', 'AMBOLAYA', 'CORCINO', 'FRANKLIN LEONARDO', 'M', '922988646', '', '', '', '', '72236341@institutocajas.edu.pe', '0000-00-00', '', NULL),
(658, NULL, '72244994', 'LOVERA', 'MARAVI', 'CINEDIN SEBASTIAN', 'M', '901828393', '', '', '', '', '72244994@institutocajas.edu.pe', '0000-00-00', '', NULL),
(659, NULL, '72253074', 'MATEO', 'REYNA', 'JEFFERSON ALEX', 'M', '982532988', '', '', '', '', '72253074@institutocajas.edu.pe', '0000-00-00', '', NULL),
(660, NULL, '72253375', 'DE LA O', 'AYLLON', 'LYNN OWEN', 'M', '994933550', '', '', '', '', '72253375@institutocajas.edu.pe', '0000-00-00', '', NULL),
(661, NULL, '72253989', 'ZURITA', 'SILVA', 'ANDERSON ESTIF', 'M', '993240531', '', '', '', '', '72253989@institutocajas.edu.pe', '0000-00-00', '', NULL),
(662, NULL, '72254302', 'PEDROZA', 'LANAZCA', 'BRITNER', 'M', '984989016', '', '', '', '', '72254302@institutocajas.edu.pe', '0000-00-00', '', NULL),
(663, NULL, '72261678', 'ROJAS', 'DE LA CRUZ', 'FRANK ALEXIS', 'M', '964616789', '', '', '', '', '72261678@institutocajas.edu.pe', '0000-00-00', '', NULL),
(664, NULL, '72263594', 'SEDANO', 'HUAIRA', 'WILLIAM JAVIER', 'M', '902106778', '', '', '', '', '72263594@institutocajas.edu.pe', '0000-00-00', '', NULL),
(665, NULL, '72268127', 'CLEMENTE', 'OCHOA', 'JOSE MANUEL', 'M', '934348010', '', '', '', '', '72268127@institutocajas.edu.pe', '0000-00-00', '', NULL),
(666, NULL, '72272835', 'QUISPE', 'SANCHEZ', 'DENIS', 'M', '997701909', '', '', '', '', '72272835@institutocajas.edu.pe', '0000-00-00', '', NULL),
(667, NULL, '72278359', 'PEREZ', 'BERAUN', 'EDISON ALDO', 'M', '924986944', '', '', '', '', '72278359@institutocajas.edu.pe', '0000-00-00', '', NULL),
(668, NULL, '72285256', 'TITO', 'CRISPIN', 'CIPRIAN', 'M', '941901034', '', '', '', '', '72285256@institutocajas.edu.pe', '0000-00-00', '', NULL),
(669, NULL, '72288346', 'PEREZ', 'POMA', 'EDISON', 'M', '904080201', '', '', '', '', '72288346@institutocajas.edu.pe', '0000-00-00', '', NULL),
(670, NULL, '72288455', 'MARTINEZ', 'GALARZA', 'WILSON DANIEL', 'M', '939641506', '', '', '', '', '72288455@institutocajas.edu.pe', '0000-00-00', '', NULL),
(671, NULL, '72288517', 'RODRIGUEZ', 'RECUAY', 'HELEN MAYLENI', 'F', '939586855', '', '', '', '', '72288517@institutocajas.edu.pe', '0000-00-00', '', NULL),
(672, NULL, '72288916', 'MAYTA', 'BERROSPI', 'ALEX MIGDOL', 'M', '954604378', '', '', '', '', '72288916@institutocajas.edu.pe', '0000-00-00', '', NULL),
(673, NULL, '72301061', 'MENDOZA', 'CACHAY', 'ALISSON MILAGROS', 'F', '906137718', '', '', '', '', '72301061@institutocajas.edu.pe', '0000-00-00', '', NULL),
(674, NULL, '72303567', 'TITO', 'CRISPIN', 'JORGE ARMANDO', 'M', '963907554', '', '', '', '', '72303567@institutocajas.edu.pe', '0000-00-00', '', NULL),
(675, NULL, '72309734', 'MERCADO', 'MENDOZA', 'RICARDO JESUS', 'M', '918006584', '', '', '', '', '72309734@institutocajas.edu.pe', '0000-00-00', '', NULL),
(676, NULL, '72309767', 'COTERA', 'ARRIETA', 'LEONARDO FAVIO', 'M', '924654441', '', '', '', '', '72309767@institutocajas.edu.pe', '0000-00-00', '', NULL),
(677, NULL, '72351157', 'GUERREROS', 'ALIPAZAGA', 'ANTONY PAUL', 'M', '931596989', '', '', '', '', '72351157@institutocajas.edu.pe', '0000-00-00', '', NULL),
(678, NULL, '72359150', 'CAHUAZA', 'CHUQUIZUTA', 'NILEMAR SHASHENKA', 'F', '943705456', '', '', '', '', '72359150@institutocajas.edu.pe', '0000-00-00', '', NULL),
(679, NULL, '72374049', 'PANEZ', 'MATEO', 'LIZETH NICOL', 'F', '927317527', '', '', '', '', '72374049@institutocajas.edu.pe', '0000-00-00', '', NULL),
(680, NULL, '72380410', 'VILCAS', 'CORDOVA', 'JADE MASSIEL', 'F', '999999999', '', '', '', '', '72380410@institutocajas.edu.pe', '0000-00-00', '', NULL),
(681, NULL, '72380413', 'HUARANGA', 'MATOS', 'WILIAN OSWALDO', 'M', '915019162', '', '', '', '', '72380413@institutocajas.edu.pe', '0000-00-00', '', NULL),
(682, NULL, '72391042', 'PEREZ', 'CHIHUAN', 'JHOMAR ADERSON', 'M', '913594457', '', '', '', '', '72391042@institutocajas.edu.pe', '0000-00-00', '', NULL),
(683, NULL, '72408646', 'LAUREANO', 'LIZARRAGA', 'JAMELY ANACRISTINA', 'F', '951081485', '', '', '', '', '72408646@institutocajas.edu.pe', '0000-00-00', '', NULL),
(684, NULL, '72412239', 'DE LA CRUZ', 'CUEVA', 'ROSMEL MARLON', 'M', '921343168', '', '', '', '', '72412239@institutocajas.edu.pe', '0000-00-00', '', NULL),
(685, NULL, '72412271', 'DE LA CRUZ', 'SORIANO', 'MASHIEL ALEJANDRA', 'F', '954002322', '', '', '', '', '72412271@institutocajas.edu.pe', '0000-00-00', '', NULL),
(686, NULL, '72412274', 'LEON', 'LEON', 'TANIA HILDA', 'F', '931503927', '', '', '', '', '72412274@institutocajas.edu.pe', '0000-00-00', '', NULL),
(687, NULL, '72414365', 'ALANYA', 'QUINTANA', 'YENIFER NENA', 'F', '991919983', '', '', '', '', '72414365@institutocajas.edu.pe', '0000-00-00', '', NULL),
(688, NULL, '72416293', 'SOTO', 'PERALTA', 'ANGELA LUCERO', 'F', '922404710', '', '', '', '', '72416293@institutocajas.edu.pe', '0000-00-00', '', NULL),
(689, NULL, '72416373', 'CANO', 'PAHUACHO', 'RICHARD ROYER', 'M', '955869473', '', '', '', '', '72416373@institutocajas.edu.pe', '0000-00-00', '', NULL),
(690, NULL, '72416393', 'NAGERA', 'RIOS', 'JONEL', 'M', '999999999', '', '', '', '', '72416393@institutocajas.edu.pe', '0000-00-00', '', NULL),
(691, NULL, '72416410', 'SUC?O', 'RAMON', 'DERICK', 'M', '928489370', '', '', '', '', '72416410@institutocajas.edu.pe', '0000-00-00', '', NULL),
(692, NULL, '72423168', 'MEZA', 'REYES', 'MIGUEL ANGELLO', 'M', '914505550', '', '', '', '', '72423168@institutocajas.edu.pe', '0000-00-00', '', NULL),
(693, NULL, '72423244', 'CHAMORRO', 'PORRAS', 'JERSON ANDRE', 'M', '921029487', '', '', '', '', '72423244@institutocajas.edu.pe', '0000-00-00', '', NULL),
(694, NULL, '72434374', 'DELAO', 'MALLQUI', 'MASHELL SAYURI', 'F', '999999999', '', '', '', '', '72434374@institutocajas.edu.pe', '0000-00-00', '', NULL),
(695, NULL, '72438097', 'MELO', 'CABANILLAS', 'SADITH DANITZA', 'F', '970894017', '', '', '', '', '72438097@institutocajas.edu.pe', '0000-00-00', '', NULL),
(696, NULL, '72447361', 'VILLACRIZ', 'HUACACHI', 'YERSON YEFERSON', 'M', '951334953', '', '', '', '', '72447361@institutocajas.edu.pe', '0000-00-00', '', NULL),
(697, NULL, '72449467', 'VERDE', 'CERRON', 'ALVARO BRYAN', 'M', '934483554', '', '', '', '', '72449467@institutocajas.edu.pe', '0000-00-00', '', NULL),
(698, NULL, '72517182', 'QUISPE', 'FUERO', 'JESUS ANGEL', 'M', '952185414', '', '', '', '', '72517182@institutocajas.edu.pe', '0000-00-00', '', NULL),
(699, NULL, '72541102', 'HUAYNATE', 'ACHACHAU', 'JOSE LUIS', 'M', '975818374', '', '', '', '', '72541102@institutocajas.edu.pe', '0000-00-00', '', NULL),
(700, NULL, '72549332', 'ESPIRITU', 'ALAYO', 'ANGEL EFRAIN', 'M', '910601456', '', '', '', '', '72549332@institutocajas.edu.pe', '0000-00-00', '', NULL),
(701, NULL, '72551943', 'PEREZ', 'TAYPE', 'LILIANA PAMELA', 'F', '964517277', '', '', '', '', '72551943@institutocajas.edu.pe', '0000-00-00', '', 1),
(702, NULL, '72571243', 'RAPRI', 'CAPCHA', 'GABRIEL FRANCIS', 'M', '958684615', '', '', '', '', '72571243@institutocajas.edu.pe', '0000-00-00', '', 1),
(703, NULL, '72571245', 'RAPRI', 'CAPCHA', 'ANGHEL FRAN LEE', 'M', '937422626', '', '', '', '', '72571245@institutocajas.edu.pe', '0000-00-00', '', 1),
(704, NULL, '72614777', 'PANIAGUA', 'VASQUEZ', 'XIOMARA', 'F', '916217856', '', '', '', '', '72614777@institutocajas.edu.pe', '0000-00-00', '', 1),
(705, NULL, '72628543', 'AVILA', 'HUATUCO', 'ALEXIS KEVIN', 'M', '998166159', '', '', '', '', '72628543@institutocajas.edu.pe', '0000-00-00', '', 1),
(706, NULL, '72637201', 'DE LA CRUZ', 'MEZA', 'DIANA YESENIA', 'F', '934612747', '', '', '', '', '72637201@institutocajas.edu.pe', '0000-00-00', '', NULL),
(707, NULL, '72637203', 'ASCONA', 'HUAMAN', 'DEREEK LEO', 'M', '950533764', '', '', '', '', '72637203@institutocajas.edu.pe', '0000-00-00', '', NULL),
(708, NULL, '72670254', 'SULLCA', 'VARGAS', 'DEYVI IRWING', 'M', '902221894', '', '', '', '', '72670254@institutocajas.edu.pe', '0000-00-00', '', NULL),
(709, NULL, '72670265', 'SULLCA', 'VARGAS', 'JEMINA', 'F', '904561774', '', '', '', '', '72670265@institutocajas.edu.pe', '0000-00-00', '', NULL),
(710, NULL, '72670271', 'SULLCA', 'VARGAS', 'JHON ELMER', 'M', '912804721', '', '', '', '', '72670271@institutocajas.edu.pe', '0000-00-00', '', NULL),
(711, NULL, '72681302', 'ROQUE', 'ANGULO', 'JOSIAS ANTONIO', 'M', '964081884', '', '', '', '', '72681302@institutocajas.edu.pe', '0000-00-00', '', NULL),
(712, NULL, '72686241', 'LANDA', 'RICALDI', 'JHOJAN JOVANY', 'M', '989270562', '', '', '', '', '72686241@institutocajas.edu.pe', '0000-00-00', '', NULL),
(713, NULL, '72748273', 'SOLORZANO', 'CHAUCA', 'TATIANA DAMARIS', 'F', '951038386', '', '', '', '', '72748273@institutocajas.edu.pe', '0000-00-00', '', NULL),
(714, NULL, '72753879', 'TORRES', 'GASTELO', 'EKAITZ JAIRO', 'M', '924595208', '', '', '', '', '72753879@institutocajas.edu.pe', '0000-00-00', '', NULL),
(715, NULL, '72768355', 'RODRIGUEZ', 'ALANYA', 'ANGELY ASDRYD', 'F', '967126446', '', '', '', '', '72768355@institutocajas.edu.pe', '0000-00-00', '', NULL),
(716, NULL, '72773077', 'CERRON', 'GUEVARA', 'LUIS ENRIQUE', 'M', '971367187', '', '', '', '', '72773077@institutocajas.edu.pe', '0000-00-00', '', NULL),
(717, NULL, '72800916', 'PAITAN', 'TAYPE', 'HILARIO WILLIAM', 'M', '921961545', '', '', '', '', '72800916@institutocajas.edu.pe', '0000-00-00', '', NULL),
(718, NULL, '72807839', 'AMARU', 'QUISPE', 'JHON', 'M', '928294934', '', '', '', '', '72807839@institutocajas.edu.pe', '0000-00-00', '', NULL),
(719, NULL, '72813144', '?A?A', 'DIAZ', 'SAUL ALVARO', 'M', '968680255', '', '', '', '', '72813144@institutocajas.edu.pe', '0000-00-00', '', NULL),
(720, NULL, '72814300', 'RIVERA', 'MALLMA', 'MAYELI', 'F', '917041125', '', '', '', '', '72814300@institutocajas.edu.pe', '0000-00-00', '', NULL),
(721, NULL, '72850415', 'RODRIGUEZ', 'ROQUE', 'YURI JHOMAR', 'M', '978498791', '', '', '', '', '72850415@institutocajas.edu.pe', '0000-00-00', '', NULL),
(722, NULL, '72859646', 'VELIZ', 'ROMANI', 'JUAN PABLO', 'M', '941500114', '', '', '', '', '72859646@institutocajas.edu.pe', '0000-00-00', '', NULL),
(723, NULL, '72861344', 'QUISPE', 'LOBOS', 'MAICOL ANDERSON', 'M', '900999503', '', '', '', '', '72861344@institutocajas.edu.pe', '0000-00-00', '', NULL),
(724, NULL, '72875449', 'NU?EZ', 'ARONI', 'NEFTALI ESAU', 'M', '960366698', '', '', '', '', '72875449@institutocajas.edu.pe', '0000-00-00', '', NULL),
(725, NULL, '72881233', 'MATEO', 'RICRA', 'FIORELLA NAYELY', 'F', '918925230', '', '', '', '', '72881233@institutocajas.edu.pe', '0000-00-00', '', NULL),
(726, NULL, '72906206', 'VELIZ', 'HEREDIA', 'JEANPIERO AARON', 'M', '947279872', '', '', '', '', '72906206@institutocajas.edu.pe', '0000-00-00', '', NULL),
(727, NULL, '72917470', 'ANAYA', 'RIVERA', 'SAUL', 'M', '954498967', '', '', '', '', '72917470@institutocajas.edu.pe', '0000-00-00', '', NULL),
(728, NULL, '72947603', 'TOSCANO', 'AREVALO', 'JAK BISMARKC', 'M', '937302228', '', '', '', '', '72947603@institutocajas.edu.pe', '0000-00-00', '', NULL),
(729, NULL, '72979547', 'PAREJAS', 'ARAUJO', 'ABRAHAM', 'M', '906250556', '', '', '', '', '72979547@institutocajas.edu.pe', '0000-00-00', '', NULL),
(730, NULL, '73027117', 'CORAZON', 'FLORES', 'JOSUE ALEJANDRO', 'M', '968683050', '', '', '', '', '73027117@institutocajas.edu.pe', '0000-00-00', '', NULL),
(731, NULL, '73027729', 'SOLLER', 'RIVERA', 'SAMIRA TAYLI', 'F', '964902460', '', '', '', '', '73027729@institutocajas.edu.pe', '0000-00-00', '', NULL),
(732, NULL, '73063421', 'LULO', 'TAIPE', 'LUIS MIGUEL', 'M', '900301508', '', '', '', '', '73063421@institutocajas.edu.pe', '0000-00-00', '', NULL),
(733, NULL, '73078042', 'RIVAS', 'CAMARENA', 'JEFREY FRANCIS', 'M', '999694924', '', '', '', '', '73078042@institutocajas.edu.pe', '0000-00-00', '', NULL),
(734, NULL, '73087118', 'MEZA', 'CASTILLON', 'PAUL GUYIN', 'M', '954019858', '', '', '', '', '73087118@institutocajas.edu.pe', '0000-00-00', '', NULL),
(735, NULL, '73100211', 'PARIONA', 'EVACETO', 'CARLOS MANUEL', 'M', '902785144', '', '', '', '', '73100211@institutocajas.edu.pe', '0000-00-00', '', NULL),
(736, NULL, '73127152', 'ROJAS', 'POMA', 'FABRIZZIO RICARDO', 'M', '930509329', '', '', '', '', '73127152@institutocajas.edu.pe', '0000-00-00', '', NULL),
(737, NULL, '73134345', 'ALVA', 'SALVADOR', 'MAYUMI ESPERANZA', 'F', '974819218', '', '', '', '', '73134345@institutocajas.edu.pe', '0000-00-00', '', NULL),
(738, NULL, '73145710', 'MEJICO', 'DE LA CRUZ', 'ELEAZAR NATANAEL', 'M', '935691026', '', '', '', '', '73145710@institutocajas.edu.pe', '0000-00-00', '', NULL),
(739, NULL, '73198659', 'HUAMAN', 'PAITAN', 'HENDRICK GUNNER', 'M', '964606100', '', '', '', '', '73198659@institutocajas.edu.pe', '0000-00-00', '', NULL),
(740, NULL, '73204323', 'PAEZ', 'MEZA', 'JEANPIER JHAIR', 'M', '965192667', '', '', '', '', '73204323@institutocajas.edu.pe', '0000-00-00', '', NULL),
(741, NULL, '73208354', 'CUSI', 'CUBA', 'JOHAN ANDERSON', 'M', '989807651', '', '', '', '', '73208354@institutocajas.edu.pe', '0000-00-00', '', NULL),
(742, NULL, '73234952', 'DE LA CRUZ', 'VILLALVA', 'ELMER', 'M', '920457743', '', '', '', '', '73234952@institutocajas.edu.pe', '0000-00-00', '', NULL),
(743, NULL, '73264274', 'UCHUYPOMA', 'MERMA', 'JAMES', 'M', '930156483', '', '', '', '', '73264274@institutocajas.edu.pe', '0000-00-00', '', NULL),
(744, NULL, '73268592', 'ROMANI', 'JANAMPA', 'LISBETH', 'F', '974992656', '', '', '', '', '73268592@institutocajas.edu.pe', '0000-00-00', '', NULL),
(745, NULL, '73268672', 'CESAR', 'YARANGA', 'YESICA AGRIPINA', 'F', '976189445', '', '', '', '', '73268672@institutocajas.edu.pe', '0000-00-00', '', NULL),
(746, NULL, '73315726', 'QUISPE', 'TAYPE', 'MAYRA CAROL', 'F', '943659090', '', '', '', '', '73315726@institutocajas.edu.pe', '0000-00-00', '', NULL),
(747, NULL, '73353786', 'CASTRO', 'HUISACAYNA', 'DANIEL RICARDO', 'M', '953436458', '', '', '', '', '73353786@institutocajas.edu.pe', '0000-00-00', '', NULL),
(748, NULL, '73381183', 'TURCO', 'FERNANDEZ', 'BLADEMIR', 'M', '934105035', '', '', '', '', '73381183@institutocajas.edu.pe', '0000-00-00', '', NULL),
(749, NULL, '73423141', 'SANCHEZ', 'BALDEON', 'ANAMARIA DEL PILAR', 'F', '903180962', '', '', '', '', '73423141@institutocajas.edu.pe', '0000-00-00', '', NULL),
(750, NULL, '73431902', 'CERAS', 'FLORES', 'FREDY ABEL', 'M', '918550662', '', '', '', '', '73431902@institutocajas.edu.pe', '0000-00-00', '', NULL),
(751, NULL, '73440480', 'SEDANO', 'ZAPATERO', 'JOEL BRENER', 'M', '963712223', '', '', '', '', '73440480@institutocajas.edu.pe', '0000-00-00', '', NULL),
(752, NULL, '73447026', 'ARTICA', 'PORRAS', 'KIMBERLY JHADIRA', 'F', '999999999', '', '', '', '', '73447026@institutocajas.edu.pe', '0000-00-00', '', NULL),
(753, NULL, '73448244', 'GRANADOS', 'MARQUEZ', 'ADHERLYN OSCAR', 'M', '985566254', '', '', '', '', '73448244@institutocajas.edu.pe', '0000-00-00', '', NULL),
(754, NULL, '73453393', 'ALMERCO', 'HIDALGO', 'JHANCARLOS SEBASTIAN', 'M', '925097699', '', '', '', '', '73453393@institutocajas.edu.pe', '0000-00-00', '', NULL),
(755, NULL, '73458113', 'BELITO', 'IRRAZABAL', 'MAX OMAR', 'M', '951841185', '', '', '', '', '73458113@institutocajas.edu.pe', '0000-00-00', '', NULL),
(756, NULL, '73459908', 'TORRE', 'JORGE', 'STEPHANY MILAGROS LUISA', 'F', '967832783', '', '', '', '', '73459908@institutocajas.edu.pe', '0000-00-00', '', NULL),
(757, NULL, '73470997', 'ROSAS', 'LAURA', 'ARNOLD CRISTOPHER', 'M', '943469944', '', '', '', '', '73470997@institutocajas.edu.pe', '0000-00-00', '', NULL),
(758, NULL, '73474956', 'COSME', 'VASQUEZ', 'JESSICA ABIGAIL', 'F', '927791271', '', '', '', '', '73474956@institutocajas.edu.pe', '0000-00-00', '', NULL),
(759, NULL, '73486761', 'QUI?ONES', 'POMA', 'ANTONY SMITH', 'M', '937442315', '', '', '', '', '73486761@institutocajas.edu.pe', '0000-00-00', '', NULL),
(760, NULL, '73492657', 'ROQUE', 'CALDERON', 'PAUL DENYS', 'M', '918713126', '', '', '', '', '73492657@institutocajas.edu.pe', '0000-00-00', '', NULL),
(761, NULL, '73497396', 'CAMAYO', 'HUAMAN', 'JOSE LUIS', 'M', '902818283', '', '', '', '', '73497396@institutocajas.edu.pe', '0000-00-00', '', NULL),
(762, NULL, '73509728', 'RAMOS', 'SOTO', 'ISAIAS', 'M', '946828602', '', '', '', '', '73509728@institutocajas.edu.pe', '0000-00-00', '', NULL),
(763, NULL, '73527961', 'VILLANES', 'GUERRA', 'MAX VIDAL', 'M', '935652721', '', '', '', '', '73527961@institutocajas.edu.pe', '0000-00-00', '', NULL),
(764, NULL, '73580414', 'PULIDO', 'CALLUPE', 'LUCIA PAULINA', 'F', '907221431', '', '', '', '', '73580414@institutocajas.edu.pe', '0000-00-00', '', NULL),
(765, NULL, '73591433', 'LARA', 'VICTORIO', 'SEBASTIAN MIGUEL', 'M', '921672169', '', '', '', '', '73591433@institutocajas.edu.pe', '0000-00-00', '', NULL),
(766, NULL, '73592824', 'HUALPARUCA', 'ORDAYA', 'MOISES', 'M', '955356696', '', '', '', '', '73592824@institutocajas.edu.pe', '0000-00-00', '', NULL),
(767, NULL, '73593399', 'DAVIRAN', 'DELZO', 'YROSHI WILMER', 'M', '907745827', '', '', '', '', '73593399@institutocajas.edu.pe', '0000-00-00', '', NULL),
(768, NULL, '73595976', 'CABEZAS', 'BERNARDO', 'JENNIFER KEYLI', 'F', '923517821', '', '', '', '', '73595976@institutocajas.edu.pe', '0000-00-00', '', NULL),
(769, NULL, '73596756', 'MANTARI', 'MEDINA', 'ALVARO ANTONIO', 'M', '903013608', '', '', '', '', '73596756@institutocajas.edu.pe', '0000-00-00', '', NULL),
(770, NULL, '73598996', 'COPES', 'MEDINA', 'KENYI ANDY', 'M', '985388637', '', '', '', '', '73598996@institutocajas.edu.pe', '0000-00-00', '', NULL),
(771, NULL, '73607865', 'MAYTA', 'TUEROS', 'JEAN CARLOS', 'M', '981965546', '', '', '', '', '73607865@institutocajas.edu.pe', '0000-00-00', '', NULL),
(772, NULL, '73609549', 'BENDEZU', 'ROJAS', 'EDGAR', 'M', '949488271', '', '', '', '', '73609549@institutocajas.edu.pe', '0000-00-00', '', NULL),
(773, NULL, '73609551', 'DE LA CRUZ', 'CAMASCA', 'ALEX', 'M', '902618188', '', '', '', '', '73609551@institutocajas.edu.pe', '0000-00-00', '', NULL),
(774, NULL, '73611107', 'OLARTE', '?AHUI', 'GUSTAVO FLAUBERT', 'M', '942853776', '', '', '', '', '73611107@institutocajas.edu.pe', '0000-00-00', '', NULL),
(775, NULL, '73611118', 'CONTRERAS', 'HINOSTROZA', 'JHON EDGAR', 'M', '972163584', '', '', '', '', '73611118@institutocajas.edu.pe', '0000-00-00', '', NULL),
(776, NULL, '73616299', 'OLARTE', 'ZUASNABAR', 'MAX EDWARD', 'M', '992029773', '', '', '', '', '73616299@institutocajas.edu.pe', '0000-00-00', '', NULL),
(777, NULL, '73650736', 'LLANTO', 'ISLA', 'YOVANA', 'F', '969344865', '', '', '', '', '73650736@institutocajas.edu.pe', '0000-00-00', '', NULL),
(778, NULL, '73660125', 'AVILA', 'GALINDO', 'RUTH ASDRID', 'F', '961072185', '', '', '', '', '73660125@institutocajas.edu.pe', '0000-00-00', '', NULL),
(779, NULL, '73673496', 'AVILA', 'CUBA', 'YESCENIA YUCILDA', 'F', '903504504', '', '', '', '', '73673496@institutocajas.edu.pe', '0000-00-00', '', NULL),
(780, NULL, '73685358', 'CHUCO', 'LOPEZ', 'YURI EDSON', 'M', '971885322', '', '', '', '', '73685358@institutocajas.edu.pe', '0000-00-00', '', NULL),
(781, NULL, '73690745', 'POMA', 'SUELDO', 'DONATO RUBEN', 'M', '948806132', '', '', '', '', '73690745@institutocajas.edu.pe', '0000-00-00', '', NULL),
(782, NULL, '73694897', 'ROJAS', 'VENTURA', 'JHULINO JUNIOR', 'M', '999999999', '', '', '', '', '73694897@institutocajas.edu.pe', '0000-00-00', '', NULL),
(783, NULL, '73706073', 'CUECA', 'HUAYRA', 'CRISTHIAN FLAVIO', 'M', '961485040', '', '', '', '', '73706073@institutocajas.edu.pe', '0000-00-00', '', NULL),
(784, NULL, '73739136', 'LANASCA', 'TICSIHUA', 'DANY SANDRO', 'M', '954094829', '', '', '', '', '73739136@institutocajas.edu.pe', '0000-00-00', '', NULL),
(785, NULL, '73754282', 'PAUCAR', 'BENITO', 'CRISTIAN DANIEL', 'M', '948944762', '', '', '', '', '73754282@institutocajas.edu.pe', '0000-00-00', '', NULL),
(786, NULL, '73759380', 'RODRIGUEZ', 'REQUENA', 'ROGER ALBERTO', 'M', '953533429', '', '', '', '', '73759380@institutocajas.edu.pe', '0000-00-00', '', NULL),
(787, NULL, '73762113', 'HURTADO', 'VENTURA', 'ENRIQUE', 'M', '907919821', '', '', '', '', '73762113@institutocajas.edu.pe', '0000-00-00', '', NULL),
(788, NULL, '73765047', 'TAYPE', 'FERNANDEZ', 'DAYSON WALDIR', 'M', '901840591', '', '', '', '', '73765047@institutocajas.edu.pe', '0000-00-00', '', NULL),
(789, NULL, '73777855', 'SUAREZ', 'CAIRO', 'JERSON', 'M', '992539340', '', '', '', '', '73777855@institutocajas.edu.pe', '0000-00-00', '', NULL),
(790, NULL, '73779070', 'VALERO', 'CAINICELA', 'SOFIA DEL PILAR', 'F', '915353261', '', '', '', '', '73779070@institutocajas.edu.pe', '0000-00-00', '', NULL),
(791, NULL, '73779071', 'VALERO', 'CAINICELA', 'CHRIS GISENIA', 'F', '986602090', '', '', '', '', '73779071@institutocajas.edu.pe', '0000-00-00', '', NULL),
(792, NULL, '73787249', 'ESTRELLA', 'PALACIOS', 'JEFFRY ANJELO', 'M', '946015018', '', '', '', '', '73787249@institutocajas.edu.pe', '0000-00-00', '', NULL),
(793, NULL, '73792538', 'DE LA CRUZ', 'INGA', 'JHONNY', 'M', '957854617', '', '', '', '', '73792538@institutocajas.edu.pe', '0000-00-00', '', NULL),
(794, NULL, '73792935', 'RIVERA', 'CONDOR', 'MARCOS JEANPIERE', 'M', '922682958', '', '', '', '', '73792935@institutocajas.edu.pe', '0000-00-00', '', NULL),
(795, NULL, '73800054', 'HUANHUAYO', 'PAUCAR', 'JOEL', 'M', '997777021', '', '', '', '', '73800054@institutocajas.edu.pe', '0000-00-00', '', NULL),
(796, NULL, '73813509', 'HUAYNAMARCA', 'ICHPAS', 'YOJAN JHOEL', 'M', '926789764', '', '', '', '', '73813509@institutocajas.edu.pe', '0000-00-00', '', NULL),
(797, NULL, '73815056', 'LAVERIANO', 'MAGALLANES', 'FRANZ VALENTIN', 'M', '935775796', '', '', '', '', '73815056@institutocajas.edu.pe', '0000-00-00', '', NULL),
(798, NULL, '73826767', 'ROMAN', 'OSORIO', 'RAFAEL', 'M', '966227683', '', '', '', '', '73826767@institutocajas.edu.pe', '0000-00-00', '', NULL),
(799, NULL, '73831642', 'AGUILAR', 'MATOS', 'JAMIL RONAL', 'M', '990167461', '', '', '', '', '73831642@institutocajas.edu.pe', '0000-00-00', '', NULL),
(800, NULL, '73855954', 'DUE?AS', 'INGARUCA', 'ANDREW YANDEL', 'M', '992038950', '', '', '', '', '73855954@institutocajas.edu.pe', '0000-00-00', '', NULL),
(801, NULL, '73862961', 'VILLAR', 'URETA', 'EMANUEL JEANPIERR', 'M', '988534046', '', '', '', '', '73862961@institutocajas.edu.pe', '0000-00-00', '', NULL),
(802, NULL, '73936233', 'MEZA', 'HUAMAN', 'GIANCARLO ABEL', 'M', '925602410', '', '', '', '', '73936233@institutocajas.edu.pe', '0000-00-00', '', NULL),
(803, NULL, '73957088', 'FERNANDEZ', 'PALMA', 'DEYVID EDWIN', 'M', '940372222', '', '', '', '', '73957088@institutocajas.edu.pe', '0000-00-00', '', NULL),
(804, NULL, '73978659', 'ZAMUDIO', 'LIPA', 'JHERSON BRAYBEN', 'M', '987402196', '', '', '', '', '73978659@institutocajas.edu.pe', '0000-00-00', '', NULL),
(805, NULL, '73996556', 'PAQUIYAURI', 'HUILLCAS', 'YOVANY', 'M', '950518611', '', '', '', '', '73996556@institutocajas.edu.pe', '0000-00-00', '', NULL),
(806, NULL, '74023787', 'ESTEBAN', 'CHAVEZ', 'EDGAR CRISTHIAN', 'M', '916813148', '', '', '', '', '74023787@institutocajas.edu.pe', '0000-00-00', '', NULL),
(807, NULL, '74028059', 'RAYMUNDO', 'ILIZARBE', 'ELMER JESUS', 'M', '912290222', '', '', '', '', '74028059@institutocajas.edu.pe', '0000-00-00', '', NULL),
(808, NULL, '74044076', 'QUISPE', 'MANTARI', 'EDUARDO STEVE', 'M', '983308592', '', '', '', '', '74044076@institutocajas.edu.pe', '0000-00-00', '', NULL),
(809, NULL, '74090864', 'LAURA', 'SANABRIA', 'SAMUEL KEVIN', 'M', '999999999', '', '', '', '', '74090864@institutocajas.edu.pe', '0000-00-00', '', NULL),
(810, NULL, '74092055', 'ACU?A', 'SUAZO', 'KENNY BORIS', 'M', '901080725', '', '', '', '', '74092055@institutocajas.edu.pe', '0000-00-00', '', NULL),
(811, NULL, '74093836', 'TORRES', 'QUINTO', 'JEAN PIER', 'M', '977120683', '', '', '', '', '74093836@institutocajas.edu.pe', '0000-00-00', '', NULL),
(812, NULL, '74133977', 'CARRASCO', 'AVELLANEDA', 'EDWIN', 'M', '976967813', '', '', '', '', '74133977@institutocajas.edu.pe', '0000-00-00', '', NULL),
(813, NULL, '74134463', 'COTERA', 'QUISPE', 'MARK ANTHONY', 'M', '998659406', '', '', '', '', '74134463@institutocajas.edu.pe', '0000-00-00', '', NULL),
(814, NULL, '74134596', 'MATAMOROS', 'CARDENAS', 'WILLIAM', 'M', '912831118', '', '', '', '', '74134596@institutocajas.edu.pe', '0000-00-00', '', NULL),
(815, NULL, '74139034', 'ROJAS', 'MEZA', 'LUIS EDACIO', 'M', '967025070', '', '', '', '', '74139034@institutocajas.edu.pe', '0000-00-00', '', NULL),
(816, NULL, '74150828', 'CONTRERAS', 'MANRIQUE', 'CRISTHIAN ANGEL', 'M', '915920384', '', '', '', '', '74150828@institutocajas.edu.pe', '0000-00-00', '', NULL),
(817, NULL, '74152353', 'MAGUI?A', 'AQUINO', 'POOL ANDERSON', 'M', '930995807', '', '', '', '', '74152353@institutocajas.edu.pe', '0000-00-00', '', NULL),
(818, NULL, '74160423', 'SANABRIA', 'ZACARIAS', 'GERSON MARCELO', 'M', '907454182', '', '', '', '', '74160423@institutocajas.edu.pe', '0000-00-00', '', NULL),
(819, NULL, '74162130', 'CUNYAS', 'CRISPIN', 'ALEXANDER RIDER', 'M', '954099637', '', '', '', '', '74162130@institutocajas.edu.pe', '0000-00-00', '', NULL),
(820, NULL, '74164721', 'LAURA', 'MENDOZA', 'ELOHIM NEZARETH', 'M', '925133014', '', '', '', '', '74164721@institutocajas.edu.pe', '0000-00-00', '', NULL),
(821, NULL, '74210869', 'AQUINO', 'MARTINEZ', 'ANGHY SAYURY', 'F', '946914739', '', '', '', '', '74210869@institutocajas.edu.pe', '0000-00-00', '', NULL),
(822, NULL, '74210884', 'AQUINO', 'MARTINEZ', 'NIXON', 'M', '934833812', '', '', '', '', '74210884@institutocajas.edu.pe', '0000-00-00', '', NULL),
(823, NULL, '74217153', 'SOTOMAYOR', 'RIVAS', 'JUAN CARLOS', 'M', '922948260', '', '', '', '', '74217153@institutocajas.edu.pe', '0000-00-00', '', NULL),
(824, NULL, '74239431', 'CARRION', 'PRIALE', 'JERSON KEVIN', 'M', '918294202', '', '', '', '', '74239431@institutocajas.edu.pe', '0000-00-00', '', NULL),
(825, NULL, '74286472', 'CESPEDES', 'RIVERA', 'FLOR ANGELA', 'F', '981725424', '', '', '', '', '74286472@institutocajas.edu.pe', '0000-00-00', '', NULL),
(826, NULL, '74287535', 'REZA', 'AQUINO', 'FRANK EDGARDO', 'M', '916392825', '', '', '', '', '74287535@institutocajas.edu.pe', '0000-00-00', '', NULL),
(827, NULL, '74287572', 'AGUILAR', 'FRETEL', 'NAYELY', 'F', '912351364', '', '', '', '', '74287572@institutocajas.edu.pe', '0000-00-00', '', NULL),
(828, NULL, '74287731', 'OSPINA', 'PRIETO', 'FELIX SANTOS', 'M', '900560269', '', '', '', '', '74287731@institutocajas.edu.pe', '0000-00-00', '', NULL),
(829, NULL, '74288077', 'HUAMAN', 'ALANYA', 'DEYVI DAVID', 'M', '953338352', '', '', '', '', '74288077@institutocajas.edu.pe', '0000-00-00', '', NULL),
(830, NULL, '74296472', 'YAURI', 'VELASQUEZ', 'JANDRY JHULYAMS', 'M', '960766754', '', '', '', '', '74296472@institutocajas.edu.pe', '0000-00-00', '', NULL),
(831, NULL, '74307575', 'SALINAS', 'ZACARIAS', 'ANDERSON RAYMUNDO', 'M', '977873639', '', '', '', '', '74307575@institutocajas.edu.pe', '0000-00-00', '', NULL),
(832, NULL, '74309166', 'YUPANQUI', 'ENERO', 'FELIX', 'M', '954057639', '', '', '', '', '74309166@institutocajas.edu.pe', '0000-00-00', '', NULL),
(833, NULL, '74311525', 'CANALES', 'MACHUCA', 'SAUL', 'M', '980062675', '', '', '', '', '74311525@institutocajas.edu.pe', '0000-00-00', '', NULL),
(834, NULL, '74322262', 'POMA', 'PARIONA', 'ZAYDA', 'F', '929839940', '', '', '', '', '74322262@institutocajas.edu.pe', '0000-00-00', '', NULL),
(835, NULL, '74322562', 'NU?EZ', 'HUAMAN', 'JHEFERSON PIERO', 'M', '972266863', '', '', '', '', '74322562@institutocajas.edu.pe', '0000-00-00', '', NULL),
(836, NULL, '74354475', 'PUCHOC', 'HUAMANLAZO', 'NEYDER JOHAN', 'M', '971924600', '', '', '', '', '74354475@institutocajas.edu.pe', '0000-00-00', '', NULL),
(837, NULL, '74354565', 'PARCO', 'VILCA?AUPA', 'YHAIR SANTOS', 'M', '913066388', '', '', '', '', '74354565@institutocajas.edu.pe', '0000-00-00', '', NULL),
(838, NULL, '74357310', 'MEZARAIME', 'CCENCHO', 'JOSE ARMANDO', 'M', '912985555', '', '', '', '', '74357310@institutocajas.edu.pe', '0000-00-00', '', NULL),
(839, NULL, '74362465', 'ZARATE', 'OSORES', 'YOLVI JHORDAN', 'M', '919162249', '', '', '', '', '74362465@institutocajas.edu.pe', '0000-00-00', '', NULL),
(840, NULL, '74362541', 'AYZANA', 'PARADO', 'JAQUELIN ROSALINDA', 'F', '928297486', '', '', '', '', '74362541@institutocajas.edu.pe', '0000-00-00', '', NULL),
(841, NULL, '74363027', 'CHIHUAN', 'ARMAS', 'NAYAEN YORCH', 'M', '963713602', '', '', '', '', '74363027@institutocajas.edu.pe', '0000-00-00', '', NULL),
(842, NULL, '74365575', 'QUISPE', 'MENDOZA', 'CRISTHINA LIZBETH', 'F', '981505638', '', '', '', '', '74365575@institutocajas.edu.pe', '0000-00-00', '', NULL),
(843, NULL, '74366235', 'SANCHEZ', 'SALAZAR', 'WILLIAM ROEL', 'M', '924963282', '', '', '', '', '74366235@institutocajas.edu.pe', '0000-00-00', '', NULL),
(844, NULL, '74369723', 'GUTIERREZ', 'ASTO', 'ANDERSON', 'M', '919044830', '', '', '', '', '74369723@institutocajas.edu.pe', '0000-00-00', '', NULL),
(845, NULL, '74373218', 'LAUREANO', 'HERRERA', 'CELEDONIO LUIS', 'M', '978678901', '', '', '', '', '74373218@institutocajas.edu.pe', '0000-00-00', '', NULL),
(846, NULL, '74373219', 'MEZA', 'RODRIGUEZ', 'KENIA ISABEL', 'F', '962142048', '', '', '', '', '74373219@institutocajas.edu.pe', '0000-00-00', '', NULL),
(847, NULL, '74373242', 'RIOS', 'SAMANIEGO', 'WILMER', 'M', '953519840', '', '', '', '', '74373242@institutocajas.edu.pe', '0000-00-00', '', NULL),
(848, NULL, '74373252', 'OBREGON', 'PONCE', 'MARIANA BRENDA', 'F', '983335177', '', '', '', '', '74373252@institutocajas.edu.pe', '0000-00-00', '', NULL),
(849, NULL, '74383932', 'TORPOCO', 'GASPAR', 'PAUL KAIN', 'M', '975023865', '', '', '', '', '74383932@institutocajas.edu.pe', '0000-00-00', '', NULL),
(850, NULL, '74384541', 'ROMERO', 'CARDENAS', 'ROBENSON', 'M', '941842799', '', '', '', '', '74384541@institutocajas.edu.pe', '0000-00-00', '', NULL),
(851, NULL, '74384590', 'VENTURA', 'INGA', 'GISELA YURIC', 'F', '981940357', '', '', '', '', '74384590@institutocajas.edu.pe', '0000-00-00', '', NULL),
(852, NULL, '74386306', 'GASPAR', 'RIOS', 'JULINHO LUIS', 'M', '939828382', '', '', '', '', '74386306@institutocajas.edu.pe', '0000-00-00', '', NULL),
(853, NULL, '74386540', 'CHIPANA', 'TORRES', 'OMAR DAVID', 'M', '984794374', '', '', '', '', '74386540@institutocajas.edu.pe', '0000-00-00', '', NULL),
(854, NULL, '74387507', 'APOLINARIO', 'PORRAS', 'CLIFOR KLISMANN', 'M', '950382043', '', '', '', '', '74387507@institutocajas.edu.pe', '0000-00-00', '', NULL),
(855, NULL, '74392864', 'BRUNO', 'TORPOCO', 'JEAN MARCOS', 'M', '975777432', '', '', '', '', '74392864@institutocajas.edu.pe', '0000-00-00', '', NULL),
(856, NULL, '74392987', 'CAPCHA', 'VASQUEZ', 'EDVERG RUSSEL', 'M', '925260339', '', '', '', '', '74392987@institutocajas.edu.pe', '0000-00-00', '', NULL),
(857, NULL, '74396641', 'AQUINO', 'VASQUEZ', 'SERGIO', 'M', '924040503', '', '', '', '', '74396641@institutocajas.edu.pe', '0000-00-00', '', NULL),
(858, NULL, '74438104', 'VALDEZ', 'TURIN', 'GRECO GABRIEL', 'M', '980437608', '', '', '', '', '74438104@institutocajas.edu.pe', '0000-00-00', '', NULL),
(859, NULL, '74446216', 'JORGE', 'POSTILLON', 'JAVIER JESUS', 'M', '928302879', '', '', '', '', '74446216@institutocajas.edu.pe', '0000-00-00', '', NULL),
(860, NULL, '74455373', 'SOTO', 'RIVERA', 'YOSSELYN INES', 'F', '967583241', '', '', '', '', '74455373@institutocajas.edu.pe', '0000-00-00', '', NULL),
(861, NULL, '74459244', 'ESPINOZA', 'HUARANCCA', 'LUIS SALVADOR', 'M', '933438643', '', '', '', '', '74459244@institutocajas.edu.pe', '0000-00-00', '', NULL),
(862, NULL, '74459251', 'GARCIA', 'LAURA', 'LISANDRO ROY', 'M', '952495379', '', '', '', '', '74459251@institutocajas.edu.pe', '0000-00-00', '', NULL),
(863, NULL, '74459338', 'SUAREZ', 'ESPINOZA', 'ANDY MAEL', 'M', '932369297', '', '', '', '', '74459338@institutocajas.edu.pe', '0000-00-00', '', NULL),
(864, NULL, '74469065', 'MENDOZA', 'ALCANTARA', 'LUIS FERNANDO', 'M', '953817088', '', '', '', '', '74469065@institutocajas.edu.pe', '0000-00-00', '', NULL),
(865, NULL, '74474155', 'CHARAPAQUI', 'MULATO', 'CESAR DAVID', 'M', '953063311', '', '', '', '', '74474155@institutocajas.edu.pe', '0000-00-00', '', NULL),
(866, NULL, '74481414', 'ALVARADO', 'CAMPOS', 'CRISTHIAN ABEL', 'M', '939408102', '', '', '', '', '74481414@institutocajas.edu.pe', '0000-00-00', '', NULL),
(867, NULL, '74483177', 'REYES', 'QUINTANA', 'GILBER JHON', 'M', '995406716', '', '', '', '', '74483177@institutocajas.edu.pe', '0000-00-00', '', NULL),
(868, NULL, '74484391', 'GRANADOS', 'APOLINARIO', 'YOECEL OLIVER', 'M', '998807696', '', '', '', '', '74484391@institutocajas.edu.pe', '0000-00-00', '', NULL),
(869, NULL, '74490760', 'MEJICO', 'MARAVI', 'JEFFERSON LENI', 'M', '999999999', '', '', '', '', '74490760@institutocajas.edu.pe', '0000-00-00', '', NULL),
(870, NULL, '74501778', 'SOTO', 'MIGUEL', 'JORDYN PHIERO', 'M', '927248274', '', '', '', '', '74501778@institutocajas.edu.pe', '0000-00-00', '', NULL),
(871, NULL, '74533761', 'TUNQUE', 'ARCOS', 'JHERSON JHONNY', 'M', '930328464', '', '', '', '', '74533761@institutocajas.edu.pe', '0000-00-00', '', NULL),
(872, NULL, '74549753', 'CUMBRERA', 'MIGUEL', 'MAYBELL SADITH', 'F', '999999999', '', '', '', '', '74549753@institutocajas.edu.pe', '0000-00-00', '', NULL),
(873, NULL, '74554289', 'QUISPE', 'HUAMAN', 'BRAYAN STIVEN', 'M', '935717665', '', '', '', '', '74554289@institutocajas.edu.pe', '0000-00-00', '', NULL),
(874, NULL, '74554663', 'BALTAZAR', 'BERNABE', 'WILBER', 'M', '997412076', '', '', '', '', '74554663@institutocajas.edu.pe', '0000-00-00', '', NULL),
(875, NULL, '74554678', 'OLIVERA', 'CERAS', 'MARCO ANTONIO', 'M', '922219396', '', '', '', '', '74554678@institutocajas.edu.pe', '0000-00-00', '', NULL),
(876, NULL, '74593238', 'GARAY', 'CHAVARRIA', 'FRANK ROGER', 'M', '907276958', '', '', '', '', '74593238@institutocajas.edu.pe', '0000-00-00', '', NULL),
(877, NULL, '74596940', 'CANGAHUALA', 'MARTINEZ', 'MAYKER JEANPIER', 'M', '976383275', '', '', '', '', '74596940@institutocajas.edu.pe', '0000-00-00', '', NULL),
(878, NULL, '74599998', 'RAMIREZ', 'RAVICHAGUA', 'RONAR SPENCER', 'M', '966385938', '', '', '', '', '74599998@institutocajas.edu.pe', '0000-00-00', '', NULL),
(879, NULL, '74600851', 'ROCA', '?AHUINRIPA', 'YUBERTH RUBEN', 'M', '913585819', '', '', '', '', '74600851@institutocajas.edu.pe', '0000-00-00', '', NULL),
(880, NULL, '74620980', 'TITO', 'GUTIERREZ', 'ANALY MIRIAM', 'F', '918112676', '', '', '', '', '74620980@institutocajas.edu.pe', '0000-00-00', '', NULL),
(881, NULL, '74623705', 'FLORES', 'JAVIER', 'JESUS ANTONIO', 'M', '933408039', '', '', '', '', '74623705@institutocajas.edu.pe', '0000-00-00', '', NULL),
(882, NULL, '74646178', 'RAMOS', 'MERINO', 'RICHAR YOEL', 'M', '901286092', '', '', '', '', '74646178@institutocajas.edu.pe', '0000-00-00', '', NULL),
(883, NULL, '74646326', 'RAMOS', 'MERINO', 'LUIS ANGEL', 'M', '935204968', '', '', '', '', '74646326@institutocajas.edu.pe', '0000-00-00', '', NULL),
(884, NULL, '74661284', 'LAZARO', 'PORRAS', 'MILDA LORENA', 'F', '925010291', '', '', '', '', '74661284@institutocajas.edu.pe', '0000-00-00', '', NULL),
(885, NULL, '74720541', 'ALARCON', 'GONZALES', 'CESAR MIGUEL', 'M', '984464952', '', '', '', '', '74720541@institutocajas.edu.pe', '0000-00-00', '', NULL),
(886, NULL, '74758526', 'CHAVEZ', 'RODRIGUEZ', 'JHON ANGEL', 'M', '960765103', '', '', '', '', '74758526@institutocajas.edu.pe', '0000-00-00', '', NULL),
(887, NULL, '74773115', 'CAMPOS', 'CAPCHA', 'FREDY NELSON', 'M', '984095667', '', '', '', '', '74773115@institutocajas.edu.pe', '0000-00-00', '', NULL),
(888, NULL, '74801208', 'CALSIN', 'JULI', 'BIANCA NATHALY', 'F', '936260986', '', '', '', '', '74801208@institutocajas.edu.pe', '0000-00-00', '', NULL),
(889, NULL, '74804112', 'ESCOBAR', 'CORONEL', 'EDWIN JONATHAN', 'M', '929430376', '', '', '', '', '74804112@institutocajas.edu.pe', '0000-00-00', '', NULL),
(890, NULL, '74805078', 'ALVAREZ', 'UNSIHUAY', 'LUZ JASMIN', 'F', '951838751', '', '', '', '', '74805078@institutocajas.edu.pe', '0000-00-00', '', NULL),
(891, NULL, '74805347', 'ROJAS', 'SUC?O', 'ZULEMA DELCY', 'F', '977568296', '', '', '', '', '74805347@institutocajas.edu.pe', '0000-00-00', '', NULL),
(892, NULL, '74874424', 'LEON', 'PRETIL', 'GINO FRAND', 'M', '919548356', '', '', '', '', '74874424@institutocajas.edu.pe', '0000-00-00', '', NULL),
(893, NULL, '74874796', 'VALLEJOS', 'ZUASNABAR', 'ANTONY', 'M', '912803020', '', '', '', '', '74874796@institutocajas.edu.pe', '0000-00-00', '', NULL),
(894, NULL, '74885840', 'MAGUI?A', 'SUTACORO', 'ALVARO RENATO', 'M', '925423424', '', '', '', '', '74885840@institutocajas.edu.pe', '0000-00-00', '', NULL),
(895, NULL, '74885912', 'DE LA CRUZ', 'MENDOZA', 'MICHAEL BRYAN', 'M', '929176455', '', '', '', '', '74885912@institutocajas.edu.pe', '0000-00-00', '', NULL),
(896, NULL, '74888223', 'CHANCA', 'GOMEZ', 'DAYMER LEONEL', 'M', '975088538', '', '', '', '', '74888223@institutocajas.edu.pe', '0000-00-00', '', NULL),
(897, NULL, '74895639', 'GASPAR', 'GABRIEL', 'ALEXIS', 'M', '920270052', '', '', '', '', '74895639@institutocajas.edu.pe', '0000-00-00', '', NULL),
(898, NULL, '74895654', 'VILCAS', 'TAZA', 'PATRICIA', 'F', '980206132', '', '', '', '', '74895654@institutocajas.edu.pe', '0000-00-00', '', NULL),
(899, NULL, '74897821', 'TITO', 'ORELLANA', 'POOL ANGELLO', 'M', '957289088', '', '', '', '', '74897821@institutocajas.edu.pe', '0000-00-00', '', NULL),
(900, NULL, '74898948', 'OROPEZA', 'ACU?A', 'ALEX FERNANDO', 'M', '957843577', '', '', '', '', '74898948@institutocajas.edu.pe', '0000-00-00', '', NULL),
(901, NULL, '74899005', 'MESCUA', 'RAMOS', 'YORDY JEFFERSON', 'M', '957490483', '', '', '', '', '74899005@institutocajas.edu.pe', '0000-00-00', '', NULL),
(902, NULL, '74899073', 'ORTIZ', 'VASQUEZ', 'CRISTIAN', 'M', '923474542', '', '', '', '', '74899073@institutocajas.edu.pe', '0000-00-00', '', NULL),
(903, NULL, '74899285', 'RAMOS', 'ASTO', 'ANALI YOVELY', 'F', '946490400', '', '', '', '', '74899285@institutocajas.edu.pe', '0000-00-00', '', NULL),
(904, NULL, '74901110', 'MU?OZ', 'MENDOZA', 'JHON WILBER', 'M', '903210216', '', '', '', '', '74901110@institutocajas.edu.pe', '0000-00-00', '', NULL),
(905, NULL, '74909533', 'QUINTANA', 'GARCIA', 'ALEXIS DAVID', 'M', '933965999', '', '', '', '', '74909533@institutocajas.edu.pe', '0000-00-00', '', NULL),
(906, NULL, '74921834', 'HUAYANAY', 'RUTTI', 'BEHECKAN DEYVIS', 'M', '900790231', '', '', '', '', '74921834@institutocajas.edu.pe', '0000-00-00', '', NULL),
(907, NULL, '74927918', 'COLONIO', '?AHUI', 'FRANKLIN JULIO', 'M', '948061550', '', '', '', '', '74927918@institutocajas.edu.pe', '0000-00-00', '', NULL),
(908, NULL, '74931513', 'DE LA CRUZ', 'ASTO', 'JORGE LUIS', 'M', '970782752', '', '', '', '', '74931513@institutocajas.edu.pe', '0000-00-00', '', NULL),
(909, NULL, '74933844', 'HUAMAN', 'BARBOSA', 'JHONER', 'M', '903517540', '', '', '', '', '74933844@institutocajas.edu.pe', '0000-00-00', '', NULL),
(910, NULL, '74943082', 'TORRES', 'MARCELO', 'YERSI TATIANA', 'F', '918064075', '', '', '', '', '74943082@institutocajas.edu.pe', '0000-00-00', '', NULL),
(911, NULL, '74945252', 'ZAPATA', 'MEDINA', 'LUIGGI ALEXANDER', 'M', '973382111', '', '', '', '', '74945252@institutocajas.edu.pe', '0000-00-00', '', NULL),
(912, NULL, '74961805', 'POMA', 'ATAHUAMAN', 'GIANELLA GIOVANA', 'F', '931239168', '', '', '', '', '74961805@institutocajas.edu.pe', '0000-00-00', '', NULL),
(913, NULL, '74967428', 'QUISPE', 'ROMERO', 'GUIANELLA DABITZA', 'F', '928075154', '', '', '', '', '74967428@institutocajas.edu.pe', '0000-00-00', '', NULL),
(914, NULL, '74967847', 'URETA', 'ESPINOZA', 'SAMUEL', 'M', '906991628', '', '', '', '', '74967847@institutocajas.edu.pe', '0000-00-00', '', NULL),
(915, NULL, '74968109', 'VARGAS', 'MONTA?EZ', 'YENSLYN YURY', 'M', '932641402', '', '', '', '', '74968109@institutocajas.edu.pe', '0000-00-00', '', NULL),
(916, NULL, '74975013', 'SOLORZANO', 'ZACARIAS', 'JESUS REYMUNDO', 'M', '966815792', '', '', '', '', '74975013@institutocajas.edu.pe', '0000-00-00', '', NULL),
(917, NULL, '74977280', 'ESTRADA', 'NU?EZ', 'GUSTAVO ANDY', 'M', '992991442', '', '', '', '', '74977280@institutocajas.edu.pe', '0000-00-00', '', NULL),
(918, NULL, '74978089', 'SANTOS', 'ALLER', 'JOSE LUIS', 'M', '901009168', '', '', '', '', '74978089@institutocajas.edu.pe', '0000-00-00', '', NULL),
(919, NULL, '74981010', 'TAPIA', 'MEZA', 'JUBERT ESAU', 'M', '952324518', '', '', '', '', '74981010@institutocajas.edu.pe', '0000-00-00', '', NULL),
(920, NULL, '74989229', 'LOPEZ', 'MEZA', 'JEFFERSON SMITH', 'M', '923194596', '', '', '', '', '74989229@institutocajas.edu.pe', '0000-00-00', '', NULL),
(921, NULL, '74989736', 'SALOME', 'DIAZ', 'JUNIOR JESUS', 'M', '922786250', '', '', '', '', '74989736@institutocajas.edu.pe', '0000-00-00', '', NULL),
(922, NULL, '75021864', 'HUAYRA', 'TORRES', 'RAUL', 'M', '999999999', '', '', '', '', '75021864@institutocajas.edu.pe', '0000-00-00', '', NULL),
(923, NULL, '75021898', 'ORTIZ', 'VASQUEZ', 'WALTER SANTOS', 'M', '931315123', '', '', '', '', '75021898@institutocajas.edu.pe', '0000-00-00', '', NULL),
(924, NULL, '75025397', 'ESTEBAN', 'TORRES', 'JHORDAN KERRY', 'M', '999999999', '', '', '', '', '75025397@institutocajas.edu.pe', '0000-00-00', '', NULL),
(925, NULL, '75025443', 'AQUINO', 'HUIZA', 'CESAR DAVID', 'M', '931360528', '', '', '', '', '75025443@institutocajas.edu.pe', '0000-00-00', '', NULL),
(926, NULL, '75026154', 'ARREDONDO', 'VALERO', 'SERGIO ALEX', 'M', '920864203', '', '', '', '', '75026154@institutocajas.edu.pe', '0000-00-00', '', NULL),
(927, NULL, '75047349', 'BARRIOS', 'GASPAR', 'YORI JEFRI', 'M', '998135452', '', '', '', '', '75047349@institutocajas.edu.pe', '0000-00-00', '', NULL),
(928, NULL, '75047364', 'DAVILA', 'SAMANIEGO', 'FRANCO OMAR', 'M', '931514061', '', '', '', '', '75047364@institutocajas.edu.pe', '0000-00-00', '', NULL),
(929, NULL, '75049094', 'BASTIDAS', 'PUENTES', 'KAROLAI SURIMANA', 'F', '902680153', '', '', '', '', '75049094@institutocajas.edu.pe', '0000-00-00', '', NULL),
(930, NULL, '75059265', 'RAMOS', 'ORTIZ', 'DENNIS LENING', 'M', '999908876', '', '', '', '', '75059265@institutocajas.edu.pe', '0000-00-00', '', NULL),
(931, NULL, '75063421', 'ESPINOZA', 'HUACHOVILLCA', 'CLISMAR YANPIER', 'M', '924355150', '', '', '', '', '75063421@institutocajas.edu.pe', '0000-00-00', '', NULL),
(932, NULL, '75072030', 'GARAY', 'SAMANIEGO', 'JENIFER LUCERO', 'F', '917820726', '', '', '', '', '75072030@institutocajas.edu.pe', '0000-00-00', '', NULL),
(933, NULL, '75082509', 'ALVAREZ', 'CASTRO', 'JUAN', 'M', '986640151', '', '', '', '', '75082509@institutocajas.edu.pe', '0000-00-00', '', NULL),
(934, NULL, '75088876', 'GUERREROS', 'PARDAVE', 'NATALY FIORELA', 'F', '966585958', '', '', '', '', '75088876@institutocajas.edu.pe', '0000-00-00', '', NULL),
(935, NULL, '75100007', 'LLANTOY', 'APOLINARIO', 'THALIA YENIFER', 'F', '920306840', '', '', '', '', '75100007@institutocajas.edu.pe', '0000-00-00', '', NULL),
(936, NULL, '75100055', 'MORALES', 'SEGURA', 'ELVIS FARIT', 'M', '918185504', '', '', '', '', '75100055@institutocajas.edu.pe', '0000-00-00', '', NULL),
(937, NULL, '75100057', 'ALANYA', 'HUAYTA', 'IORI JHON', 'M', '945267157', '', '', '', '', '75100057@institutocajas.edu.pe', '0000-00-00', '', NULL),
(938, NULL, '75102748', 'BALBIN', 'VILLARREAL', 'JAREN JOSE', 'M', '999550058', '', '', '', '', '75102748@institutocajas.edu.pe', '0000-00-00', '', NULL),
(939, NULL, '75102999', 'EULOGIO', 'SIMON', 'KEVIN JOEL', 'M', '976207165', '', '', '', '', '75102999@institutocajas.edu.pe', '0000-00-00', '', NULL),
(940, NULL, '75103000', 'EULOGIO', 'SIMON', 'ANDRE JOAN', 'M', '974726405', '', '', '', '', '75103000@institutocajas.edu.pe', '0000-00-00', '', NULL),
(941, NULL, '75105279', 'GERARDINE', 'PAITAN', 'SAUL CALEB', 'M', '936940965', '', '', '', '', '75105279@institutocajas.edu.pe', '0000-00-00', '', NULL),
(942, NULL, '75123607', 'RODRIGUEZ', 'VILA', 'DARWIN ITALO', 'M', '916409875', '', '', '', '', '75123607@institutocajas.edu.pe', '0000-00-00', '', NULL),
(943, NULL, '75123628', 'ALBERCA', 'TORPOCO', 'MELANY BRIGITH', 'F', '907333390', '', '', '', '', '75123628@institutocajas.edu.pe', '0000-00-00', '', NULL),
(944, NULL, '75123672', 'CHUPAN', 'YUPANQUI', 'BRAYAN ALEXIS', 'M', '951034265', '', '', '', '', '75123672@institutocajas.edu.pe', '0000-00-00', '', NULL),
(945, NULL, '75134996', 'JESUS', 'YUPANQUI', 'BRANDO JACK', 'M', '933275398', '', '', '', '', '75134996@institutocajas.edu.pe', '0000-00-00', '', NULL),
(946, NULL, '75137342', 'MARIN', 'CCANTO', 'DENNER ALFREDO', 'M', '999999999', '', '', '', '', '75137342@institutocajas.edu.pe', '0000-00-00', '', NULL),
(947, NULL, '75137691', 'DE LA CRUZ', 'MARTINEZ', 'EDER IVAN', 'M', '966211833', '', '', '', '', '75137691@institutocajas.edu.pe', '0000-00-00', '', NULL),
(948, NULL, '75141605', 'RAMOS', 'NAVARRO', 'JEAN PIER', 'M', '926311445', '', '', '', '', '75141605@institutocajas.edu.pe', '0000-00-00', '', NULL),
(949, NULL, '75141897', 'LAZO', 'ISIDRO', 'AURIO MIGUEL', 'M', '939523650', '', '', '', '', '75141897@institutocajas.edu.pe', '0000-00-00', '', NULL),
(950, NULL, '75161240', 'BARZOLA', 'CHAVEZ', 'DIEGO ARMANDO', 'M', '946595887', '', '', '', '', '75161240@institutocajas.edu.pe', '0000-00-00', '', NULL),
(951, NULL, '75173855', 'CARDENAS', 'SORIANO', 'MIRELLA FABIOLA', 'F', '928518531', '', '', '', '', '75173855@institutocajas.edu.pe', '0000-00-00', '', NULL),
(952, NULL, '75174637', 'CONOZCO', 'ROJAS', 'YORDAN DANY', 'M', '918623515', '', '', '', '', '75174637@institutocajas.edu.pe', '0000-00-00', '', NULL),
(953, NULL, '75175739', 'MALLAUPOMA', 'GOMEZ', 'JEFERZON POOL', 'M', '969343600', '', '', '', '', '75175739@institutocajas.edu.pe', '0000-00-00', '', NULL),
(954, NULL, '75177452', 'CAMASCA', 'PASTRANA', 'FRANKLIN LINDER', 'M', '963968730', '', '', '', '', '75177452@institutocajas.edu.pe', '0000-00-00', '', NULL),
(955, NULL, '75179294', 'PUCHOC', 'HUAMANLAZO', 'JOSMEL AUGUSTO', 'M', '924151078', '', '', '', '', '75179294@institutocajas.edu.pe', '0000-00-00', '', NULL),
(956, NULL, '75180352', 'CRISPIN', 'ESPINOZA', 'JOSEPH ALEXANDER', 'M', '912376495', '', '', '', '', '75180352@institutocajas.edu.pe', '0000-00-00', '', NULL),
(957, NULL, '75186175', 'ENRIQUEZ', 'FLORES', 'JOSUE VIANNEY', 'M', '965159555', '', '', '', '', '75186175@institutocajas.edu.pe', '0000-00-00', '', NULL),
(958, NULL, '75191330', 'CAMARENA', 'ROJAS', 'BERKLIN MELVER', 'M', '938261322', '', '', '', '', '75191330@institutocajas.edu.pe', '0000-00-00', '', NULL),
(959, NULL, '75191781', 'SEDANO', 'VIVANCO', 'FRANK ANTONY', 'M', '975384817', '', '', '', '', '75191781@institutocajas.edu.pe', '0000-00-00', '', NULL),
(960, NULL, '75209238', 'PAYTAMPOMA', 'CORDOVA', 'RENSON ROY', 'M', '920467790', '', '', '', '', '75209238@institutocajas.edu.pe', '0000-00-00', '', NULL),
(961, NULL, '75212411', 'PEREZ', 'PAREDES', 'OSWALDO JUNIOR', 'M', '910509218', '', '', '', '', '75212411@institutocajas.edu.pe', '0000-00-00', '', NULL),
(962, NULL, '75214665', 'CCANTO', 'ELIAS', 'KENNEDY POOL', 'M', '900761676', '', '', '', '', '75214665@institutocajas.edu.pe', '0000-00-00', '', NULL),
(963, NULL, '75215109', 'CANCHAPOMA', 'VALVERDE', 'YAEL WALDO', 'M', '930957264', '', '', '', '', '75215109@institutocajas.edu.pe', '0000-00-00', '', NULL),
(964, NULL, '75215152', 'ORIHUELA', 'OBISPO', 'RICARDO FREDY', 'M', '955583685', '', '', '', '', '75215152@institutocajas.edu.pe', '0000-00-00', '', NULL),
(965, NULL, '75215304', 'ORIHUELA', 'PEINADO', 'VICTOR RAFAEL', 'M', '932540365', '', '', '', '', '75215304@institutocajas.edu.pe', '0000-00-00', '', NULL),
(966, NULL, '75215633', 'REYNA', 'CRISTOBAL', 'YEFFERSON YOLVER', 'M', '991609193', '', '', '', '', '75215633@institutocajas.edu.pe', '0000-00-00', '', NULL),
(967, NULL, '75219979', 'MIRANDA', 'CLEMENTE', 'MICHAEL EUGENIO', 'M', '950351940', '', '', '', '', '75219979@institutocajas.edu.pe', '0000-00-00', '', NULL),
(968, NULL, '75220000', 'LAZARO', 'BERAUN', 'KARLA PAHOLA', 'F', '965081424', '', '', '', '', '75220000@institutocajas.edu.pe', '0000-00-00', '', NULL),
(969, NULL, '75224195', 'ANAYA', 'HUANAY', 'JEFFERSON', 'M', '900585942', '', '', '', '', '75224195@institutocajas.edu.pe', '0000-00-00', '', NULL),
(970, NULL, '75226448', 'HUAMAN', 'CCAHUIN', 'RICHARD DANIEL', 'M', '912836548', '', '', '', '', '75226448@institutocajas.edu.pe', '0000-00-00', '', NULL),
(971, NULL, '75230209', 'LARA', 'MALLAUPOMA', 'KATHERIN NAYELI', 'F', '952733163', '', '', '', '', '75230209@institutocajas.edu.pe', '0000-00-00', '', NULL),
(972, NULL, '75231231', 'VILLAVERDE', 'ALIAGA', 'GRECIA MARIFE', 'F', '994888791', '', '', '', '', '75231231@institutocajas.edu.pe', '0000-00-00', '', NULL),
(973, NULL, '75231874', 'NOYA', 'CAJA', 'ESVILDA', 'F', '941152337', '', '', '', '', '75231874@institutocajas.edu.pe', '0000-00-00', '', NULL),
(974, NULL, '75241371', 'CCASANI', 'PAINADO', 'KALET MAURICIO', 'M', '900771943', '', '', '', '', '75241371@institutocajas.edu.pe', '0000-00-00', '', NULL),
(975, NULL, '75241917', 'VELAZCO', 'MORENO', 'JOSE DAVID', 'M', '930822313', '', '', '', '', '75241917@institutocajas.edu.pe', '0000-00-00', '', NULL),
(976, NULL, '75243606', 'ACHALLMA', 'MENDOZA', 'EFRAIN', 'M', '984485669', '', '', '', '', '75243606@institutocajas.edu.pe', '0000-00-00', '', NULL),
(977, NULL, '75243613', 'QUISPE', 'CHANCASANAMPA', 'GELIN', 'F', '926838005', '', '', '', '', '75243613@institutocajas.edu.pe', '0000-00-00', '', NULL),
(978, NULL, '75243614', 'QUISPE', 'CHANCASANAMPA', 'SAIRA NERI', 'F', '931892151', '', '', '', '', '75243614@institutocajas.edu.pe', '0000-00-00', '', NULL),
(979, NULL, '75245456', 'FRANCO', 'TAIPE', 'DIEGO ANDERSON', 'M', '922883209', '', '', '', '', '75245456@institutocajas.edu.pe', '0000-00-00', '', NULL),
(980, NULL, '75263886', 'BARRA', 'CHAVEZ', 'POOL JHOSEP', 'M', '981756759', '', '', '', '', '75263886@institutocajas.edu.pe', '0000-00-00', '', NULL),
(981, NULL, '75276788', 'BARRERA', 'SULLCA', 'NILTON JAVIER', 'M', '900094048', '', '', '', '', '75276788@institutocajas.edu.pe', '0000-00-00', '', NULL),
(982, NULL, '75276870', 'RAFAEL', 'VILCA?AUPA', 'JUAN MAX', 'M', '904741282', '', '', '', '', '75276870@institutocajas.edu.pe', '0000-00-00', '', NULL);
INSERT INTO `estudiante` (`id`, `ubdistrito`, `dni_est`, `ap_est`, `am_est`, `nom_est`, `sex_est`, `cel_est`, `ubigeodir_est`, `ubigeonac_est`, `dir_est`, `mailp_est`, `maili_est`, `fecnac_est`, `foto_est`, `estado`) VALUES
(983, NULL, '75277168', 'GUZMAN', 'TUNQUE', 'JOSMAR BENYI', 'M', '924770085', '', '', '', '', '75277168@institutocajas.edu.pe', '0000-00-00', '', NULL),
(984, NULL, '75285913', 'FERNANDEZ', 'HUACCHO', 'DENZEL DUDIKOFF', 'M', '931892438', '', '', '', '', '75285913@institutocajas.edu.pe', '0000-00-00', '', NULL),
(985, NULL, '75292211', 'ESTRADA', 'TOSCANO', 'JOHAO HUMBERTO', 'M', '927114820', '', '', '', '', '75292211@institutocajas.edu.pe', '0000-00-00', '', NULL),
(986, NULL, '75313512', 'MARTINEZ', 'TAPIA', 'RAFAEL RODRIGO', 'M', '950718479', '', '', '', '', '75313512@institutocajas.edu.pe', '0000-00-00', '', NULL),
(987, NULL, '75320504', 'COCA', 'HUAMANI', 'ERICK CRISTIANO', 'M', '923902024', '', '', '', '', '75320504@institutocajas.edu.pe', '0000-00-00', '', NULL),
(988, NULL, '75335466', 'OLARTE', 'SALVADOR', 'JEAN POOL EDWIN', 'M', '927241927', '', '', '', '', '75335466@institutocajas.edu.pe', '0000-00-00', '', NULL),
(989, NULL, '75339086', 'GERARDINI', 'LAURA', 'JUAN DANIEL', 'M', '912336151', '', '', '', '', '75339086@institutocajas.edu.pe', '0000-00-00', '', NULL),
(990, NULL, '75339093', 'CHIHUAN', 'ATAO', 'BRINER', 'M', '977176536', '', '', '', '', '75339093@institutocajas.edu.pe', '0000-00-00', '', NULL),
(991, NULL, '75342004', 'JANAMPA', 'ROJAS', 'KENJI', 'M', '901878405', '', '', '', '', '75342004@institutocajas.edu.pe', '0000-00-00', '', NULL),
(992, NULL, '75349353', 'MEZA', 'DE LA CRUZ', 'AARON JOHAN', 'M', '961005422', '', '', '', '', '75349353@institutocajas.edu.pe', '0000-00-00', '', NULL),
(993, NULL, '75349573', 'CARRION', 'PRIALE', 'DANIEL ALCIDES', 'M', '935704707', '', '', '', '', '75349573@institutocajas.edu.pe', '0000-00-00', '', NULL),
(994, NULL, '75365060', 'HUAMANI', 'SANCHEZ', 'LUIGI DAVID', 'M', '990530796', '', '', '', '', '75365060@institutocajas.edu.pe', '0000-00-00', '', NULL),
(995, NULL, '75365884', 'BRAVO', 'UCHARIMA', 'DIEGO JESUS', 'M', '999999999', '', '', '', '', '75365884@institutocajas.edu.pe', '0000-00-00', '', NULL),
(996, NULL, '75372532', 'USCAMAYTA', 'PALACIOS', 'JENRRY', 'M', '954872747', '', '', '', '', '75372532@institutocajas.edu.pe', '0000-00-00', '', NULL),
(997, NULL, '75377167', 'BALVIN', 'CARBAJAL', 'ANYELI JOSEFINA', 'F', '934770727', '', '', '', '', '75377167@institutocajas.edu.pe', '0000-00-00', '', NULL),
(998, NULL, '75383814', 'HUANCAYO', 'CUCHO', 'DAVID ELIAS', 'M', '971744466', '', '', '', '', '75383814@institutocajas.edu.pe', '0000-00-00', '', NULL),
(999, NULL, '75394957', 'SEDANO', 'VILCAPOMA', 'YERSSON YONY', 'M', '964249414', '', '', '', '', '75394957@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1000, NULL, '75435152', 'PE?A', 'PONCE', 'CARLOS JOSE', 'M', '929996163', '', '', '', '', '75435152@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1001, NULL, '75445019', 'TORPOCO', 'CUBA', 'YOSELY MAYRA', 'F', '952786908', '', '', '', '', '75445019@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1002, NULL, '75457670', 'ZAMUDIO', 'HUAYTA', 'JEAN POOL', 'M', '999999999', '', '', '', '', '75457670@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1003, NULL, '75460820', 'MEDRANO', 'ROJAS', 'ADHERLY JESUS', 'M', '929956310', '', '', '', '', '75460820@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1004, NULL, '75479412', 'CASTRO', 'PALOMINO', 'JHON ERMES', 'M', '991423060', '', '', '', '', '75479412@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1005, NULL, '75488918', 'PASTRANA', 'BARRETON', 'JHOAN JEFFERSON', 'M', '922764010', '', '', '', '', '75488918@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1006, NULL, '75495942', 'VILCA', 'DE LA CRUZ', 'CHRISTOFER LINCOLH', 'M', '999999999', '', '', '', '', '75495942@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1007, NULL, '75496924', 'MENDOZA', 'PAITAMPOMA', 'MARK ANTONY', 'M', '981117349', '', '', '', '', '75496924@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1008, NULL, '75510945', 'GARCIA', 'CHOJE', 'ALEXANDER', 'M', '906600630', '', '', '', '', '75510945@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1009, NULL, '75512234', 'ROJAS', 'QUISPE', 'ANGEL FELIX', 'M', '999999999', '', '', '', '', '75512234@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1010, NULL, '75512548', 'ANGELES', 'MIGUEL', 'HELMONT EDUARDO', 'M', '964787346', '', '', '', '', '75512548@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1011, NULL, '75516901', 'MISARI', 'ELISES', 'MISAEL BENJAMIN', 'M', '915111228', '', '', '', '', '75516901@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1012, NULL, '75517079', 'YAURI', 'QUIJADA', 'ALEXANDER PIEROL', 'M', '929044998', '', '', '', '', '75517079@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1013, NULL, '75517084', 'TOCAS', 'ARANDA', 'CIRO SEBASTIAN', 'M', '963511286', '', '', '', '', '75517084@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1014, NULL, '75519667', 'TRINIDAD', 'ENRIQUEZ', 'JUAN JONATAN', 'M', '902875453', '', '', '', '', '75519667@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1015, NULL, '75528123', 'PARIONA', 'SANCHEZ', 'JESUS MANUEL', 'M', '953397551', '', '', '', '', '75528123@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1016, NULL, '75529424', 'MARTEL', 'ROSALES', 'ROYSER FRANCO', 'M', '991050959', '', '', '', '', '75529424@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1017, NULL, '75541694', 'RIOS', 'HUAMAN', 'ALEXIS RUBEN', 'M', '901069785', '', '', '', '', '75541694@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1018, NULL, '75541730', 'CERRON', 'SILVA', 'JHOJAN SEBASTIAN', 'M', '916418576', '', '', '', '', '75541730@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1019, NULL, '75542019', 'PRADO', 'RIOS', 'DANILO JUAN', 'M', '970689098', '', '', '', '', '75542019@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1020, NULL, '75543047', 'SALOME', 'BARRETO', 'SHEYLA JUBETHSS', 'F', '947588632', '', '', '', '', '75543047@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1021, NULL, '75543140', 'VENTURA', 'INGA', 'RUTH JIMENA', 'F', '956778835', '', '', '', '', '75543140@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1022, NULL, '75544308', 'LLACCTAHUAMAN', 'ANTONIO', 'LITNA JHOLISA', 'F', '902155938', '', '', '', '', '75544308@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1023, NULL, '75548101', 'GARCIA', 'CHOJE', 'JOSE LUIS', 'M', '949624285', '', '', '', '', '75548101@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1024, NULL, '75549034', 'GALARZA', 'FLORES', 'MIGUEL HERMINIO', 'M', '999999999', '', '', '', '', '75549034@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1025, NULL, '75557502', 'ATAO', 'PAUCAR', 'EXAR WILLIAMS', 'M', '916694173', '', '', '', '', '75557502@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1026, NULL, '75560048', 'SALAZAR', 'YUPANQUI', 'YXSAIDU GLORIA', 'F', '976907382', '', '', '', '', '75560048@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1027, NULL, '75569842', 'RODRIGUEZ', 'HUAMAN', 'JAIME', 'M', '938588357', '', '', '', '', '75569842@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1028, NULL, '75575578', 'LOZANO', 'CASO', 'ANNETTE STEFANY', 'F', '921395935', '', '', '', '', '75575578@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1029, NULL, '75582861', 'AYALA', 'ROBLES', 'BETSABE SARAI', 'F', '928070345', '', '', '', '', '75582861@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1030, NULL, '75588635', 'GARCIA', 'CHANCHA', 'JOSEP ANGEL', 'M', '929144724', '', '', '', '', '75588635@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1031, NULL, '75592998', 'MANTARI', 'DAMIAN', 'LENIN RONALDO', 'M', '935654566', '', '', '', '', '75592998@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1032, NULL, '75604557', 'CASTRO', 'LAUREANO', 'ANTHONY ALEJANDRO', 'M', '992780272', '', '', '', '', '75604557@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1033, NULL, '75608548', 'VELIZ', 'LEVANO', 'YAZUKO YASHIRO', 'M', '924427201', '', '', '', '', '75608548@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1034, NULL, '75610161', 'GRANADOS', 'ORELLANA', 'GUSTAVO PIERO', 'M', '948368662', '', '', '', '', '75610161@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1035, NULL, '75618787', 'LEON', 'CAPCHA', 'KEVIN YULI?O', 'M', '930835568', '', '', '', '', '75618787@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1036, NULL, '75626774', 'VALERO', 'MEZA', 'FIORELLA JHOBALY', 'F', '953436132', '', '', '', '', '75626774@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1037, NULL, '75651740', 'BRAVO', 'MEZA', 'MARCO ANTONIO', 'M', '922497740', '', '', '', '', '75651740@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1038, NULL, '75655848', 'CARDENAS', 'CAMARGO', 'JOSE JHEFERSON', 'M', '924153238', '', '', '', '', '75655848@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1039, NULL, '75656340', 'SALAZAR', 'CAMPOS', 'JOSE LUIS', 'M', '973773030', '', '', '', '', '75656340@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1040, NULL, '75665904', 'LAZO', 'HUAYNALAYA', 'KLEBERSON', 'M', '927400057', '', '', '', '', '75665904@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1041, NULL, '75666042', 'QUISPE', 'BENDEZU', 'JERSON PIERO', 'M', '918851318', '', '', '', '', '75666042@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1042, NULL, '75680950', 'CUBA', 'PAPUICO', 'RAYMUNDO PEDRO', 'M', '944710274', '', '', '', '', '75680950@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1043, NULL, '75689415', 'HUAYTA', 'MELCHOR', 'CARLOS JESUS', 'M', '904396512', '', '', '', '', '75689415@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1044, NULL, '75689932', 'TACUNAN', 'MEZA', 'YURI', 'M', '986277568', '', '', '', '', '75689932@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1045, NULL, '75697442', 'CUBA', 'ALCOSER', 'YONEL', 'M', '967041257', '', '', '', '', '75697442@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1046, NULL, '75704479', 'HUARCAYA', 'CULQUI', 'ANGY NIKOLL', 'F', '971283090', '', '', '', '', '75704479@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1047, NULL, '75705264', 'ROSALES', 'PORRAS', 'ANDERSON', 'M', '956834713', '', '', '', '', '75705264@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1048, NULL, '75707406', 'LAZARO', 'EGOAVIL', 'GENRI RUIZ', 'M', '939597591', '', '', '', '', '75707406@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1049, NULL, '75729736', 'SALAZAR', 'ORTIZ', 'JUAN CARLOS', 'M', '929539276', '', '', '', '', '75729736@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1050, NULL, '75731466', 'MOREYRA', 'GASPAR', 'ROBER ANGEL', 'M', '900604894', '', '', '', '', '75731466@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1051, NULL, '75731469', 'FERNANDEZ', 'DUE?AS', 'SHANDOR SAMIR', 'M', '942899990', '', '', '', '', '75731469@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1052, NULL, '75732836', 'QUISPE', 'YUCRA', 'ESTEPHANO ALEXANDER PIERO', 'M', '902151917', '', '', '', '', '75732836@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1053, NULL, '75746235', 'LUNA', 'GOMEZ', 'EMERSON JAIR', 'M', '918722275', '', '', '', '', '75746235@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1054, NULL, '75747532', 'SANTIVA?EZ', 'DELGADILLO', 'JOSEPH ANTHONY', 'M', '984040941', '', '', '', '', '75747532@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1055, NULL, '75762811', 'BALDEON', 'LOPEZ', 'PEDRO LUIS', 'M', '987485092', '', '', '', '', '75762811@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1056, NULL, '75773402', 'ORDO?EZ', 'QUISPE', 'RONALDINHO', 'M', '932663443', '', '', '', '', '75773402@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1057, NULL, '75777869', 'DIAZ', 'ARANGO', 'ROSMERY LIZETH', 'F', '978634175', '', '', '', '', '75777869@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1058, NULL, '75787779', 'ORIHUELA', 'RIVERA', 'DENZEL VICTOR', 'M', '951341370', '', '', '', '', '75787779@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1059, NULL, '75793195', 'CASTRO', 'BALBUENA', 'YANDEL SAID', 'M', '954804002', '', '', '', '', '75793195@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1060, NULL, '75793583', 'CAJA', 'MARAVI', 'ANYELO AXEL', 'M', '944247213', '', '', '', '', '75793583@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1061, NULL, '75801929', 'ROJAS', 'ROSALES', 'XIOMARA PAOLA', 'F', '987882213', '', '', '', '', '75801929@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1062, NULL, '75804373', 'FERNANDEZ', 'ARANDA', 'RENZO ANDREI', 'M', '963756631', '', '', '', '', '75804373@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1063, NULL, '75816879', 'ARTEAGA', 'BLANCAS', 'KEVIN', 'M', '949113058', '', '', '', '', '75816879@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1064, NULL, '75816881', 'TORRES', 'SUAREZ', 'ROCIO MARISOL', 'F', '932377950', '', '', '', '', '75816881@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1065, NULL, '75835707', 'MAYTA', 'CENTENO', 'RONALDO CARLOS', 'M', '955530440', '', '', '', '', '75835707@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1066, NULL, '75838499', 'CABALLON', 'LIZANA', 'JHOHAN ANTONY', 'M', '912702400', '', '', '', '', '75838499@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1067, NULL, '75838500', 'VALERO', 'POMA', 'JAVIER YOJAN', 'M', '901856072', '', '', '', '', '75838500@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1068, NULL, '75838999', 'USCAMAYTA', 'ALEJANDRO', 'INGRID ADRIANA', 'F', '992126376', '', '', '', '', '75838999@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1069, NULL, '75845559', 'MAYTA', 'ORTIZ', 'FRITS JEFFERSON', 'M', '933295653', '', '', '', '', '75845559@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1070, NULL, '75854987', 'ALVARADO', 'LAZO', 'YHONATAN ESMIT', 'M', '924883977', '', '', '', '', '75854987@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1071, NULL, '75871924', 'ROSALES', 'PORRAS', 'ANIBAL', 'M', '999999999', '', '', '', '', '75871924@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1072, NULL, '75871931', 'ALANYA', 'DAVILA', 'RUTH GISELA', 'F', '980186011', '', '', '', '', '75871931@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1073, NULL, '75872203', 'LAZO', 'UNCHUPAICO', 'LUIS DAVID', 'M', '971569832', '', '', '', '', '75872203@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1074, NULL, '75875729', 'BRICE?O', 'CAMARENA', 'JEANS NILVER', 'M', '938927455', '', '', '', '', '75875729@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1075, NULL, '75879812', 'RODRIGUEZ', 'PONCE', 'ROCIO ESTEFANI', 'F', '951826911', '', '', '', '', '75879812@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1076, NULL, '75889758', 'FLORES', 'CASTILLON', 'ANTONY JHOSEPS', 'M', '962238836', '', '', '', '', '75889758@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1077, NULL, '75891701', 'ALANYA', 'BARZOLA', 'MILAGROS CECILIA', 'F', '927975250', '', '', '', '', '75891701@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1078, NULL, '75898544', 'ESTRADA', 'CORDOVA', 'JOEL', 'M', '968815461', '', '', '', '', '75898544@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1079, NULL, '75911192', 'ROJAS', 'GARCIA', 'ALEJANDRO RUSBEL', 'M', '978685925', '', '', '', '', '75911192@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1080, NULL, '75914228', 'REYMUNDO', 'HURTADO', 'LEWIS FRANCO', 'M', '952575678', '', '', '', '', '75914228@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1081, NULL, '75942756', 'ROJAS', 'USCAMAYTA', 'NICK HANS', 'M', '937322565', '', '', '', '', '75942756@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1082, NULL, '75948582', 'REYMUNDO', 'BRUNO', 'IVAN JOEL', 'M', '935798544', '', '', '', '', '75948582@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1083, NULL, '75955817', 'BRAVO', 'HINOJOSA', 'ABEL ANGEL', 'M', '912165053', '', '', '', '', '75955817@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1084, NULL, '75957374', 'ESTRADA', 'MU?OZ', 'JEFER CRISTIAN', 'M', '935627334', '', '', '', '', '75957374@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1085, NULL, '75966052', 'ORTIZ', 'ARENAS', 'NICK JEFHERSON', 'M', '935329156', '', '', '', '', '75966052@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1086, NULL, '75971346', 'CORONEL', 'ROJAS', 'RICARDO SAMUEL', 'M', '984936361', '', '', '', '', '75971346@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1087, NULL, '75973370', 'MAYHUA', 'RODRIGO', 'JEFERSON JOSE', 'M', '927178361', '', '', '', '', '75973370@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1088, NULL, '75974729', 'MALDONADO', 'APOLINARIO', 'ALEXANDER', 'M', '931772241', '', '', '', '', '75974729@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1089, NULL, '75980625', 'PAUCAR', 'CALDERON', 'WENDI YAMIRA', 'F', '956186213', '', '', '', '', '75980625@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1090, NULL, '75982084', 'APOLINARIO', 'JAUREGUI', 'DIANA', 'F', '920183339', '', '', '', '', '75982084@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1091, NULL, '75985985', 'CLEMENTE', 'JURADO', 'CARMEN BRISA', 'F', '903250732', '', '', '', '', '75985985@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1092, NULL, '75990566', 'ADARMES', 'ZENTENO', 'JUAN ARTURO', 'M', '978901387', '', '', '', '', '75990566@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1093, NULL, '75991660', 'HUERTA', 'YLANZO', 'CYNTHIA ABIGAIL', 'F', '991213838', '', '', '', '', '75991660@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1094, NULL, '75995021', 'LAZO', 'HUAMAN', 'MORELIA FATIMA', 'F', '912661254', '', '', '', '', '75995021@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1095, NULL, '75998829', 'CAMPOS', 'CALLUPE', 'RONALDO JESUS', 'M', '968393666', '', '', '', '', '75998829@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1096, NULL, '76002716', 'MENDOZA', 'PEREZ', 'EDGAR ISRAEL', 'M', '972547288', '', '', '', '', '76002716@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1097, NULL, '76027273', 'ALANYA', 'PRETIL', 'KENYI MARK', 'M', '902638571', '', '', '', '', '76027273@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1098, NULL, '76027507', 'JARAMILLO', 'LASTRA', 'ABEL MOISES', 'M', '901792160', '', '', '', '', '76027507@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1099, NULL, '76037276', 'HUARANGA', 'ARZAPALO', 'YOMAR LADIMIRO', 'M', '938396819', '', '', '', '', '76037276@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1100, NULL, '76046129', 'ERQUINIO', 'FLORES', 'ROGER RENZO', 'M', '974325803', '', '', '', '', '76046129@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1101, NULL, '76046899', 'AVILA', 'ALMONACID', 'FERNANDO', 'M', '984166990', '', '', '', '', '76046899@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1102, NULL, '76048984', 'ROJAS', 'ARANDA', 'YERSON KELVIN', 'M', '963751631', '', '', '', '', '76048984@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1103, NULL, '76049728', 'PEREZ', 'SOTO', 'EDUARDO GABRIEL', 'M', '918824305', '', '', '', '', '76049728@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1104, NULL, '76062193', 'HUARANGA', 'ARZAPALO', 'ROLANDO BRAYAN', 'M', '944367859', '', '', '', '', '76062193@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1105, NULL, '76092369', 'YACHACHIN', 'HUAMALI', 'DAVID GUIDO', 'M', '930782342', '', '', '', '', '76092369@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1106, NULL, '76120800', 'PACHECO', 'TORRES', 'MARITH SELENA', 'F', '929479984', '', '', '', '', '76120800@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1107, NULL, '76139792', 'COZ', 'GASPAR', 'LUIS ALBERTO', 'M', '934317975', '', '', '', '', '76139792@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1108, NULL, '76141884', 'JANAMPA', 'DAMAS', 'ERICK RUSBELL', 'M', '928083954', '', '', '', '', '76141884@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1109, NULL, '76147763', 'PEREZ', 'RUIZ', 'EDSON YONATHAN', 'M', '989222160', '', '', '', '', '76147763@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1110, NULL, '76155801', 'ESPINOZA', 'HUAMANI', 'DIOGENES', 'M', '935785004', '', '', '', '', '76155801@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1111, NULL, '76164698', 'AUQUI', 'ESPINOZA', 'EMERSON', 'M', '918195053', '', '', '', '', '76164698@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1112, NULL, '76166571', 'URETA', 'SOTO', 'LUIS FRANKLIN', 'M', '900614976', '', '', '', '', '76166571@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1113, NULL, '76234979', 'RUIZ', 'QUISPE', 'MICHAEL ANGEL', 'M', '916128777', '', '', '', '', '76234979@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1114, NULL, '76265609', 'CEOPA', 'SANGAMA', 'CIS', 'M', '968515376', '', '', '', '', '76265609@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1115, NULL, '76267111', 'VILCA', 'VASQUEZ', 'JUDITH MARY', 'F', '943284277', '', '', '', '', '76267111@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1116, NULL, '76285029', 'INGA', 'RANILLA', 'LUIS', 'M', '965315160', '', '', '', '', '76285029@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1117, NULL, '76288858', 'GUEVARA', 'RICRA', 'BRIGHIT DEL CARMEN', 'F', '925221966', '', '', '', '', '76288858@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1118, NULL, '76293893', 'LINDO', 'CANCHARI', 'POOL BRAYAN', 'M', '910084003', '', '', '', '', '76293893@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1119, NULL, '76342911', 'CARHUALLANQUI', 'CORNELIO', 'MARIFER SARA', 'F', '956225881', '', '', '', '', '76342911@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1120, NULL, '76351929', 'BALTAZAR', 'BERNABE', 'FILOMENO', 'M', '935006498', '', '', '', '', '76351929@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1121, NULL, '76355646', 'ARANDA', 'MEZA', 'EMER ERICK', 'M', '927949017', '', '', '', '', '76355646@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1122, NULL, '76380203', 'DIAZ', 'FERNANDEZ', 'LUIS', 'M', '966372178', '', '', '', '', '76380203@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1123, NULL, '76404828', 'CANCHARI', 'MEZA', 'GIULIANA EVA', 'F', '931072853', '', '', '', '', '76404828@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1124, NULL, '76419823', 'VELIZ', 'PARIONA', 'YOSEP ALDO', 'M', '901149138', '', '', '', '', '76419823@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1125, NULL, '76421077', 'DE LA CRUZ', 'ORIHUELA', 'DIEGO', 'M', '951465118', '', '', '', '', '76421077@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1126, NULL, '76431541', 'RUIZ', 'QUISPE', 'RALHP LEONARDO', 'M', '981304804', '', '', '', '', '76431541@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1127, NULL, '76472339', 'HERRERA', 'ROJAS', 'AMILTON', 'M', '985561636', '', '', '', '', '76472339@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1128, NULL, '76475330', 'GASPAR', 'CORDOVA', 'ANTONY YOCER', 'M', '962236508', '', '', '', '', '76475330@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1129, NULL, '76505474', 'RODRIGO', 'TAPARA', 'FRAY ANTHONY', 'M', '999999999', '', '', '', '', '76505474@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1130, NULL, '76525718', 'HUAYCA?E', 'FLORES', 'CARLOS DANIEL', 'M', '904229462', '', '', '', '', '76525718@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1131, NULL, '76525799', 'DAMIAN', 'QUI?ONES', 'OLIVER', 'M', '999999999', '', '', '', '', '76525799@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1132, NULL, '76540116', 'GASPAR', 'ALCOSER', 'ROSALIA', 'F', '953644829', '', '', '', '', '76540116@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1133, NULL, '76550675', 'MARTEL', 'YA?AC', 'VISMAR', 'M', '979190986', '', '', '', '', '76550675@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1134, NULL, '76550684', 'YARASCA', 'PONCE', 'JEANPIER CRISTIAN', 'M', '912309303', '', '', '', '', '76550684@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1135, NULL, '76550713', 'DE LA CRUZ', 'ROMAN', 'EDWIN BRANDON', 'M', '954580603', '', '', '', '', '76550713@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1136, NULL, '76560607', 'VIVAS', 'MEZA', 'PEDRO JHAMPIERE', 'M', '983731486', '', '', '', '', '76560607@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1137, NULL, '76561473', 'DIAZ', 'ARANGO', 'JUAN DAVID', 'M', '921520058', '', '', '', '', '76561473@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1138, NULL, '76564168', 'HUAYLLANI', 'MERMA', 'JHUMER ALDAID', 'M', '925640300', '', '', '', '', '76564168@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1139, NULL, '76599082', 'BERROSPI', 'NINAHUANCA', 'CLINTON YEFRI', 'M', '985745874', '', '', '', '', '76599082@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1140, NULL, '76599367', 'YUPARI', 'SALOME', 'DENILSON', 'M', '910254784', '', '', '', '', '76599367@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1141, NULL, '76610657', 'GOMEZ', 'RIVERA', 'CRISTHIAN MELLER', 'M', '991095486', '', '', '', '', '76610657@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1142, NULL, '76617579', 'ASTO', 'BUITRON', 'JESUS JHORDAN', 'M', '999999999', '', '', '', '', '76617579@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1143, NULL, '76620844', 'CONTRERAS', 'PEREZ', 'MAEL ROY', 'M', '916198304', '', '', '', '', '76620844@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1144, NULL, '76622154', 'VENTURA', 'ALFARO', 'KERRY', 'M', '999999999', '', '', '', '', '76622154@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1145, NULL, '76630004', 'TORRES', 'HINOSTROZA', 'YASMIN ROSALINDA', 'F', '974030453', '', '', '', '', '76630004@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1146, NULL, '76635546', 'JARAMILLO', 'LASTRA', 'ALEX', 'M', '925407568', '', '', '', '', '76635546@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1147, NULL, '76650732', 'ROSALES', 'CARRASCO', 'ANDREA NICOLD', 'F', '957376873', '', '', '', '', '76650732@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1148, NULL, '76655080', 'UNUCURE', 'APARCO', 'JEANPIER JOSUE', 'M', '934124827', '', '', '', '', '76655080@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1149, NULL, '76660705', 'CESPEDES', 'QUISPE', 'SALOMON', 'M', '981893052', '', '', '', '', '76660705@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1150, NULL, '76670560', 'JIMENEZ', 'ESCALANTE', 'LILIANA', 'F', '934286672', '', '', '', '', '76670560@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1151, NULL, '76674595', 'HIDALGO', 'SOTO', 'DANIEL', 'M', '992241165', '', '', '', '', '76674595@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1152, NULL, '76691369', 'NU?EZ', 'SALAZAR', 'DEYVIS FRAYVERG', 'M', '980726490', '', '', '', '', '76691369@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1153, NULL, '76725064', 'VASQUEZ', 'TORRES', 'SULME LUISA', 'F', '928334169', '', '', '', '', '76725064@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1154, NULL, '76747417', 'ROJAS', 'ESPILCO', 'LUIGI FABRIZIO', 'M', '937265300', '', '', '', '', '76747417@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1155, NULL, '76749555', 'MAMANI', 'SORIANO', 'MARIA DEL ROSARIO', 'F', '990984772', '', '', '', '', '76749555@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1156, NULL, '76762609', 'RIVERA', 'BENDEZU', 'JOSE LUIS', 'M', '901206801', '', '', '', '', '76762609@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1157, NULL, '76768957', 'SUAREZ', 'ALANYA', 'DENNY HEBERT', 'M', '923033322', '', '', '', '', '76768957@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1158, NULL, '76775057', 'QUISPE', 'SALOME', 'MAJHORY CELINA', 'F', '963478146', '', '', '', '', '76775057@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1159, NULL, '76777321', 'MONTES', 'OLAZABAL', 'JOSUE MIGUEL', 'M', '962317143', '', '', '', '', '76777321@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1160, NULL, '76779289', 'SILVESTRE', 'ZACARIAS', 'JUAN KLINSMANN', 'M', '977439320', '', '', '', '', '76779289@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1161, NULL, '76799040', 'TORRES', 'BENDEZU', 'KENNET GERARDO', 'M', '916708021', '', '', '', '', '76799040@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1162, NULL, '76799614', 'GONZALES', 'QUISPE', 'JOSE JAIRO', 'M', '918230675', '', '', '', '', '76799614@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1163, NULL, '76805580', 'LAZO', 'MAYTA', 'PEDRO JOSEPH', 'M', '920006198', '', '', '', '', '76805580@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1164, NULL, '76830468', 'SALINAS', 'ACEVEDO', 'LUIS MIGUEL', 'M', '982503163', '', '', '', '', '76830468@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1165, NULL, '76840192', 'GARCIA', 'BENDEZU', 'HENRY', 'M', '917579602', '', '', '', '', '76840192@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1166, NULL, '76840195', 'LAPA', 'UNOCC', 'OLIVER ABEL', 'M', '921295608', '', '', '', '', '76840195@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1167, NULL, '76840236', 'HUANUCO', 'CORI', 'WILLIAM FLORENCIO', 'M', '997282373', '', '', '', '', '76840236@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1168, NULL, '76853928', 'CHANCO', 'AVELLANEDA', 'EDWIN JHONY', 'M', '962497447', '', '', '', '', '76853928@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1169, NULL, '76875294', 'VILCAHUAMAN', 'ASTAHUAMAN', 'DIEGO ANDRE', 'M', '990878383', '', '', '', '', '76875294@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1170, NULL, '76916016', 'MUNIVE', 'MEZA', 'FRANCO ERIC', 'M', '979331347', '', '', '', '', '76916016@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1171, NULL, '76916592', 'CHANCASANAMPA', 'ANGUIS', 'ALEXANDER JESUS', 'M', '985478879', '', '', '', '', '76916592@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1172, NULL, '76933726', '?AHUI', 'SOTO', 'WILLIAMS FREDY', 'M', '957755615', '', '', '', '', '76933726@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1173, NULL, '76938656', 'ATAYPOMA', 'REYMUNDO', 'ELISEO', 'M', '926077168', '', '', '', '', '76938656@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1174, NULL, '76969230', 'PARIONA', 'SANCHEZ', 'ROYER', 'M', '966439061', '', '', '', '', '76969230@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1175, NULL, '76972081', 'MU?OZ', 'ALIAGA', 'ADRIAN SERAPIO', 'M', '975621041', '', '', '', '', '76972081@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1176, NULL, '76976872', 'AYLAS', 'LANDEO', 'EDU YADER ANDERSON', 'M', '954377424', '', '', '', '', '76976872@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1177, NULL, '76978652', 'BO?ON', 'SANCHEZ', 'WALTER MOISES', 'M', '913328908', '', '', '', '', '76978652@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1178, NULL, '76986347', 'ESPINAL', 'MANCILLA', 'EVELYN', 'F', '985194557', '', '', '', '', '76986347@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1179, NULL, '77019073', 'MALDONADO', 'CAMARENA', 'MARIBEL', 'F', '934820460', '', '', '', '', '77019073@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1180, NULL, '77020944', 'DE LA CRUZ', 'CAMPOS', 'ELSIDER', 'M', '999999999', '', '', '', '', '77020944@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1181, NULL, '77022570', 'QUISPE', 'CURO', 'LIDER', 'M', '912937198', '', '', '', '', '77022570@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1182, NULL, '77025066', 'VELARDE', 'POCCO', 'DEBETO EUSEBIO', 'M', '977605351', '', '', '', '', '77025066@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1183, NULL, '77027080', 'MARTINEZ', 'ALFONSO', 'RUTH ABRIL', 'F', '942990278', '', '', '', '', '77027080@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1184, NULL, '77027357', 'CANO', 'BENITO', 'ERICK CHRISTOFER', 'M', '959520125', '', '', '', '', '77027357@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1185, NULL, '77040596', 'GONZALES', 'POMACARHUA', 'CRISTOFFER GINO', 'M', '995355765', '', '', '', '', '77040596@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1186, NULL, '77043650', 'LAURA', 'HUISA', 'GINA ANTONELA', 'F', '910640478', '', '', '', '', '77043650@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1187, NULL, '77053029', 'FLORES', 'CUNYAS', 'JHORDY VICTOR', 'M', '964022948', '', '', '', '', '77053029@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1188, NULL, '77058825', 'CANTURIN', 'FERNANDEZ', 'VLADIMIR', 'M', '912441314', '', '', '', '', '77058825@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1189, NULL, '77060202', 'CARDENAS', 'SORIANO', 'GIAN MARCO', 'M', '914749944', '', '', '', '', '77060202@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1190, NULL, '77065639', 'ALMONACID', 'ESPINOZA', 'DAVID', 'M', '992564931', '', '', '', '', '77065639@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1191, NULL, '77067964', 'YUPANQUI', 'HUARINGA', 'JHON CHARLI', 'M', '998333416', '', '', '', '', '77067964@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1192, NULL, '77081916', 'CAMPOS', 'ASTO', 'JENIFER ROCIO', 'F', '965711904', '', '', '', '', '77081916@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1193, NULL, '77085198', 'HUAMAN', 'MU?OZ', 'YERZON SMITH', 'M', '987120534', '', '', '', '', '77085198@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1194, NULL, '77136205', 'SARAVIA', '?AVEZ', 'JHANKER JUAN', 'M', '948640343', '', '', '', '', '77136205@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1195, NULL, '77136532', 'AVELLANEDA', 'MACHA', 'MAURO HORACIO', 'M', '994477566', '', '', '', '', '77136532@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1196, NULL, '77171985', 'ALIAGA', 'INGARUCA', 'DEYVIS JHEFERSON', 'M', '936049913', '', '', '', '', '77171985@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1197, NULL, '77176553', 'LIZARRAGA', 'LAZARO', 'HENMY HUMBERTO', 'M', '958301363', '', '', '', '', '77176553@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1198, NULL, '77214924', 'CAMARGO', 'CAYSAHUANA', 'HEMERSON', 'M', '954093500', '', '', '', '', '77214924@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1199, NULL, '77218754', 'QUI?ONES', 'RAMOS', 'BARINIA LAILA', 'F', '914494466', '', '', '', '', '77218754@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1200, NULL, '77247324', 'CARDENAS', 'LAUREANO', 'HEYANCLER FELEX', 'M', '923107786', '', '', '', '', '77247324@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1201, NULL, '77277836', 'ARROYO', 'PRIALE', 'ASTRID LUCERO', 'F', '951141619', '', '', '', '', '77277836@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1202, NULL, '77334558', 'MONTERO', 'ESTRADA', 'MAYUMI', 'F', '959083146', '', '', '', '', '77334558@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1203, NULL, '77336678', 'MINAYA', 'PEREZ', 'JACINTO', 'M', '974444744', '', '', '', '', '77336678@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1204, NULL, '77346890', 'FERNANDEZ', 'CAMARGO', 'RUBEN KENNY', 'M', '930220639', '', '', '', '', '77346890@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1205, NULL, '77348400', 'BALTAZAR', 'GONZALES', 'ANDREE LEONARDO', 'M', '921485491', '', '', '', '', '77348400@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1206, NULL, '77351598', 'VILCAMECHE', 'BUENDIA', 'SINDEL KATHERINE', 'F', '998294847', '', '', '', '', '77351598@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1207, NULL, '77354408', 'HUANAY', 'TAIPE', 'ENRIQUE ANTONY', 'M', '901080934', '', '', '', '', '77354408@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1208, NULL, '77356832', 'PACHECO', 'HUAMAN', 'JUAN BEN HUR', 'M', '921528201', '', '', '', '', '77356832@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1209, NULL, '77392947', 'ROJAS', 'USCAMAYTA', 'ASUMY SARAI', 'F', '949542645', '', '', '', '', '77392947@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1210, NULL, '77419407', 'MONTA?EZ', 'RIOS', 'JULIAN ANDERZON', 'M', '994332525', '', '', '', '', '77419407@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1211, NULL, '77477687', 'LABAN', 'PEREYRA', 'JUNIOR EFRAIN', 'M', '960530068', '', '', '', '', '77477687@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1212, NULL, '77478618', 'BARRERA', 'CAYETANO', 'YANIRA DAMORIN', 'F', '926578606', '', '', '', '', '77478618@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1213, NULL, '77487623', 'CONDE', 'PAREJAS', 'YURI ANTONIO', 'M', '932166085', '', '', '', '', '77487623@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1214, NULL, '77539277', 'JURADO', 'MARTICORENA', 'MARYORY JOCABET', 'F', '986281368', '', '', '', '', '77539277@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1215, NULL, '77566667', 'ALVAREZ', 'ALANYA', 'HAVITH RAUL', 'M', '955928930', '', '', '', '', '77566667@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1216, NULL, '77567682', 'REYES', 'BERNARDO', 'HANS', 'M', '964147161', '', '', '', '', '77567682@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1217, NULL, '77575607', 'ESPINOZA', 'MENDEZ', 'JHENSY NEDWIN', 'M', '901762209', '', '', '', '', '77575607@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1218, NULL, '77659583', 'LAUREANO', 'RODRIGUEZ', 'JHANPIER ADRIAN', 'M', '943603477', '', '', '', '', '77659583@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1219, NULL, '77675050', 'JULCARIMA', 'PASTRANA', 'KENYI VICENTE', 'M', '915964375', '', '', '', '', '77675050@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1220, NULL, '77682974', 'SUAREZ', 'CASIMIRO', 'RUMARIO', 'M', '952888602', '', '', '', '', '77682974@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1221, NULL, '77705548', 'QUISPE', 'RUFFNER', 'ANGELO FEDERICO JOSE', 'M', '924169254', '', '', '', '', '77705548@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1222, NULL, '77797728', 'MOLINA', 'CRISPIN', 'MOISES', 'M', '953856494', '', '', '', '', '77797728@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1223, NULL, '77804774', 'CORNEJO', 'PALACIOS', 'HITLER ROY', 'M', '913846117', '', '', '', '', '77804774@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1224, NULL, '77805240', 'SULLCA', 'CUSI', 'DAVID ENRRIQUE', 'M', '917332998', '', '', '', '', '77805240@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1225, NULL, '77807738', 'PEREZ', 'RODRIGUEZ', 'FERNANDO JOSE', 'M', '927036075', '', '', '', '', '77807738@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1226, NULL, '77903101', 'CHUQUILLANQUI', 'APOLINARIO', 'HELEN', 'F', '902351973', '', '', '', '', '77903101@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1227, NULL, '77903502', 'MU?OZ', 'MARAVI', 'JHEFFERSON', 'M', '967118835', '', '', '', '', '77903502@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1228, NULL, '77905526', 'NU?EZ', 'GASPAR', 'LUIS MANUEL', 'M', '960466643', '', '', '', '', '77905526@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1229, NULL, '77911308', 'SUAZO', 'VILCHEZ', 'MARICIELO NILS', 'F', '930399430', '', '', '', '', '77911308@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1230, NULL, '77913544', 'CAHUANA', 'HUILLCAS', 'MAJIBER', 'M', '917020442', '', '', '', '', '77913544@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1231, NULL, '78005161', 'PALACIOS', 'ACHICHUAMAN', 'GEORGE DOUGLAS', 'M', '994825671', '', '', '', '', '78005161@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1232, NULL, '78005772', 'ALIAGA', 'MENDOZA', 'ALCEDES IDER', 'M', '990041526', '', '', '', '', '78005772@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1233, NULL, '78007758', 'BERNARDO', 'ENCISO', 'DAVID DANIEL', 'M', '912904280', '', '', '', '', '78007758@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1234, NULL, '78016441', 'PERALES', 'FLORES', 'BETHY MARIANA', 'F', '901970212', '', '', '', '', '78016441@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1235, NULL, '78017789', 'HUAMAN', 'HUAMAN', 'CRISTHIAN JES?S', 'M', '937337969', '', '', '', '', '78017789@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1236, NULL, '78022605', 'PARIACHI', 'PALOMINO', 'DAVID DOMINGO', 'M', '900784851', '', '', '', '', '78022605@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1237, NULL, '78022895', 'MARCOS', 'CRUZ', 'YAULIN', 'M', '987063249', '', '', '', '', '78022895@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1238, NULL, '78108086', 'LANAZCA', 'CHOQUE', 'MARCO ANTONIO', 'M', '967215150', '', '', '', '', '78108086@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1239, NULL, '78109041', 'GRADOS', 'CRISOSTOMO', 'JEAN MARTIN', 'M', '983846150', '', '', '', '', '78109041@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1240, NULL, '78109774', 'MIGUEL', 'HUAMANI', 'AISHA YADHIRA', 'F', '936145081', '', '', '', '', '78109774@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1241, NULL, '78115960', 'CAMPIAN', 'QUISPE', 'JOSE MOISES', 'M', '979091846', '', '', '', '', '78115960@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1242, NULL, '78373065', 'DELAO', 'ALEJO', 'JEFERSON DANILO', 'M', '970738907', '', '', '', '', '78373065@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1243, NULL, '78393548', 'VILLANES', 'AQUINO', 'KESLIN SMITH', 'M', '942223958', '', '', '', '', '78393548@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1244, NULL, '78635496', 'REYES', 'DIAS', 'LISSET DHAYANA', 'F', '943991422', '', '', '', '', '78635496@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1245, NULL, '78692109', 'TAPIA', 'JACON', 'DANI HUGO', 'M', '922869136', '', '', '', '', '78692109@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1246, NULL, '79005922', 'LAZARO', 'BERAUN', 'SHARUK ALEXIS', 'M', '968016617', '', '', '', '', '79005922@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1247, NULL, '79325871', 'MURILLO', 'LEYVA', 'DANIEL BERNARDO', 'M', '914577377', '', '', '', '', '79325871@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1248, NULL, '79901643', 'BARRETO', 'ENCISO', 'NICKOLL YAMILE', 'F', '936484634', '', '', '', '', '79901643@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1249, NULL, '80715618', 'LLANCO', 'APOLINARIO', 'HELEN HARUMI', 'F', '968259182', '', '', '', '', '80715618@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1250, NULL, '80835602', 'YUPANQUI', 'HUARINGA', 'JHORDYN', 'M', '928446964', '', '', '', '', '80835602@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1251, NULL, '80842547', 'POMA', 'TIZA', 'JORGE LUIS', 'M', '977661408', '', '', '', '', '80842547@institutocajas.edu.pe', '0000-00-00', '', NULL),
(1252, NULL, '80848836', 'TORRES', 'MUCHA', 'YERALDIN YHADIRA', 'F', '988814184', '', '', '', '', '80848836@institutocajas.edu.pe', '0000-00-00', '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id` int(11) NOT NULL,
  `practicas` int(11) NOT NULL,
  `puntaje_total` int(11) DEFAULT NULL,
  `escala` char(1) DEFAULT NULL,
  `apreciacion` enum('Muy Buena','Buena','Aceptable','Deficiente') DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencias`
--

CREATE TABLE `evidencias` (
  `id` int(11) NOT NULL,
  `practicas` int(11) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `archivo_url` varchar(255) DEFAULT NULL,
  `fecha_subida` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `firma`
--

CREATE TABLE `firma` (
  `id` int(11) NOT NULL,
  `documento` int(11) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `representante` int(11) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `metodo` varchar(30) DEFAULT NULL,
  `hash_documento` varchar(64) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `tipouser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_descuentos`
--

CREATE TABLE `historial_descuentos` (
  `id` int(11) NOT NULL,
  `beneficiario_id` int(11) NOT NULL,
  `monto_original` decimal(10,2) NOT NULL,
  `porcentaje_descuento` decimal(5,2) NOT NULL,
  `monto_descuento` decimal(10,2) NOT NULL,
  `monto_final` decimal(10,2) NOT NULL,
  `aplicado_por` int(11) DEFAULT NULL,
  `aplicado_en` datetime DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_laboral`
--

CREATE TABLE `historial_laboral` (
  `id` int(11) NOT NULL,
  `situacion` int(11) DEFAULT NULL,
  `tiempo_primer_empleo` varchar(30) DEFAULT NULL,
  `razon_desempleo` varchar(30) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_solicitudes`
--

CREATE TABLE `historial_solicitudes` (
  `id` int(11) NOT NULL,
  `solicitud_id` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `empleado` int(11) DEFAULT NULL,
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `id` int(11) NOT NULL,
  `noms_ins` varchar(50) DEFAULT NULL,
  `apaterno_ins` varchar(50) DEFAULT NULL,
  `amaterno_ins` varchar(50) DEFAULT NULL,
  `dni_ins` char(8) DEFAULT NULL,
  `telefono_ins` char(9) DEFAULT NULL,
  `correo_ins` varchar(50) DEFAULT NULL,
  `institucion_ins` varchar(100) DEFAULT NULL,
  `lugar_ins` varchar(100) DEFAULT NULL,
  `asistencia_cap` int(11) DEFAULT NULL,
  `curso` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instituto`
--

CREATE TABLE `instituto` (
  `id` int(11) NOT NULL,
  `ruc` varchar(11) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `nombre_comercial` varchar(255) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `activa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invitado_reunion`
--

CREATE TABLE `invitado_reunion` (
  `id` int(11) NOT NULL,
  `reunion` int(11) DEFAULT NULL,
  `nombre_completo` varchar(255) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `entidad` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `asistio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matricula`
--

CREATE TABLE `matricula` (
  `id` int(11) NOT NULL,
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
(10, 1077, 1, '202520010', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(11, 1072, 1, '202520011', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(12, 339, 1, '202520012', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(13, 937, 1, '202520013', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(14, 209, 1, '202520014', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(15, 1097, 1, '202520015', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(16, 687, 1, '202520016', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(17, 237, 1, '202520017', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(18, 885, 1, '202520018', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(19, 441, 1, '202520019', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(20, 277, 1, '202520020', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(21, 943, 1, '202520021', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(22, 107, 1, '202520022', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(23, 644, 1, '202520023', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(24, 643, 1, '202520024', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(25, 518, 1, '202520025', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(26, 597, 1, '202520026', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(27, 489, 1, '202520027', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(28, 623, 1, '202520028', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(29, 333, 1, '202520029', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(30, 568, 1, '202520030', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(31, 567, 1, '202520031', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(32, 1196, 1, '202520032', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(33, 1232, 1, '202520033', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(34, 244, 1, '202520034', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(35, 549, 1, '202520035', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(36, 527, 1, '202520036', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(37, 578, 1, '202520037', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(38, 296, 1, '202520038', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(39, 417, 1, '202520039', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(40, 754, 1, '202520040', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(41, 497, 1, '202520041', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(42, 553, 1, '202520042', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(43, 1190, 1, '202520043', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(44, 737, 1, '202520044', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(45, 866, 1, '202520045', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(46, 1070, 1, '202520046', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(47, 40, 1, '202520047', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(48, 1215, 1, '202520048', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(49, 933, 1, '202520049', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(50, 467, 1, '202520050', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(51, 890, 1, '202520051', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(52, 82, 1, '202520052', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(53, 73, 1, '202520053', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(54, 261, 1, '202520054', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(55, 718, 1, '202520055', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(56, 657, 1, '202520056', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(57, 969, 1, '202520057', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(58, 727, 1, '202520058', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(59, 372, 1, '202520059', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(60, 1010, 1, '202520060', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(61, 193, 1, '202520061', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(62, 1090, 1, '202520062', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(63, 854, 1, '202520063', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(64, 221, 1, '202520064', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(65, 925, 1, '202520065', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(66, 246, 1, '202520066', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(67, 821, 1, '202520067', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(68, 822, 1, '202520068', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(69, 857, 1, '202520069', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(70, 452, 1, '202520070', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(71, 498, 1, '202520071', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(72, 283, 1, '202520072', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(73, 1121, 1, '202520073', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(74, 576, 1, '202520074', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(75, 176, 1, '202520075', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(76, 185, 1, '202520076', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(77, 28, 1, '202520077', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(78, 534, 1, '202520078', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(79, 226, 1, '202520079', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(80, 490, 1, '202520080', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(81, 196, 1, '202520081', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(82, 926, 1, '202520082', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(83, 547, 1, '202520083', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(84, 1201, 1, '202520084', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(85, 1063, 1, '202520085', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(86, 752, 1, '202520086', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(87, 707, 1, '202520087', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(88, 455, 1, '202520088', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(89, 343, 1, '202520089', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(90, 1142, 1, '202520090', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(91, 492, 1, '202520091', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(92, 612, 1, '202520092', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(93, 535, 1, '202520093', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(94, 1025, 1, '202520094', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(95, 303, 1, '202520095', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(96, 499, 1, '202520096', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(97, 1173, 1, '202520097', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(98, 1111, 1, '202520098', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(99, 1195, 1, '202520099', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(100, 1101, 1, '202520100', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(101, 406, 1, '202520101', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(102, 779, 1, '202520102', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(103, 778, 1, '202520103', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(104, 705, 1, '202520104', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(105, 619, 1, '202520105', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(106, 268, 1, '202520106', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(107, 1029, 1, '202520107', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(108, 1176, 1, '202520108', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(109, 840, 1, '202520109', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(110, 938, 1, '202520110', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(111, 418, 1, '202520111', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(112, 1055, 1, '202520112', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(113, 85, 1, '202520113', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(114, 1120, 1, '202520114', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(115, 874, 1, '202520115', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(116, 267, 1, '202520116', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(117, 1205, 1, '202520117', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(118, 466, 1, '202520118', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(119, 997, 1, '202520119', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(120, 613, 1, '202520120', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(121, 584, 1, '202520121', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(122, 61, 1, '202520122', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(123, 108, 1, '202520123', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(124, 980, 1, '202520124', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(125, 1212, 1, '202520125', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(126, 981, 1, '202520126', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(127, 1248, 1, '202520127', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(128, 927, 1, '202520128', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(129, 950, 1, '202520129', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(130, 616, 1, '202520130', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(131, 929, 1, '202520131', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(132, 649, 1, '202520132', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(133, 755, 1, '202520133', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(134, 528, 1, '202520134', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(135, 772, 1, '202520135', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(136, 313, 1, '202520136', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(137, 370, 1, '202520137', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(138, 1233, 1, '202520138', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(139, 548, 1, '202520139', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(140, 1139, 1, '202520140', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(141, 1177, 1, '202520141', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(142, 1083, 1, '202520142', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(143, 1037, 1, '202520143', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(144, 995, 1, '202520144', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(145, 1074, 1, '202520145', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(146, 855, 1, '202520146', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(147, 58, 1, '202520147', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(148, 1066, 1, '202520148', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(149, 768, 1, '202520149', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(150, 537, 1, '202520150', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(151, 629, 1, '202520151', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(152, 566, 1, '202520152', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(153, 622, 1, '202520153', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(154, 1230, 1, '202520154', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(155, 678, 1, '202520155', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(156, 47, 1, '202520156', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(157, 1060, 1, '202520157', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(158, 342, 1, '202520158', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(159, 596, 1, '202520159', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(160, 423, 1, '202520160', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(161, 888, 1, '202520161', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(162, 958, 1, '202520162', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(163, 1198, 1, '202520163', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(164, 208, 1, '202520164', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(165, 954, 1, '202520165', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(166, 519, 1, '202520166', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(167, 10, 1, '202520167', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(168, 160, 1, '202520168', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(169, 761, 1, '202520169', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(170, 1241, 1, '202520170', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(171, 1192, 1, '202520171', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(172, 1095, 1, '202520172', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(173, 887, 1, '202520173', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(174, 538, 1, '202520174', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(175, 27, 1, '202520175', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(176, 238, 1, '202520176', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(177, 626, 1, '202520177', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(178, 349, 1, '202520178', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(179, 833, 1, '202520179', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(180, 963, 1, '202520180', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(181, 1123, 1, '202520181', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(182, 877, 1, '202520182', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(183, 1184, 1, '202520183', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(184, 585, 1, '202520184', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(185, 265, 1, '202520185', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(186, 689, 1, '202520186', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(187, 18, 1, '202520187', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(188, 1188, 1, '202520188', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(189, 3, 1, '202520189', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(190, 384, 1, '202520190', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(191, 123, 1, '202520191', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(192, 856, 1, '202520192', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(193, 51, 1, '202520193', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(194, 148, 1, '202520194', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(195, 1038, 1, '202520195', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(196, 1200, 1, '202520196', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(197, 385, 1, '202520197', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(198, 69, 1, '202520198', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(199, 1189, 1, '202520199', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(200, 32, 1, '202520200', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(201, 951, 1, '202520201', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(202, 275, 1, '202520202', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(203, 1119, 1, '202520203', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(204, 38, 1, '202520204', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(205, 175, 1, '202520205', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(206, 812, 1, '202520206', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(207, 993, 1, '202520207', '2025-II', 'V', 5, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(208, 824, 1, '202520208', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(209, 253, 1, '202520209', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(210, 365, 1, '202520210', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(211, 322, 1, '202520211', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(212, 20, 1, '202520212', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(213, 589, 1, '202520213', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(214, 35, 1, '202520214', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(215, 380, 1, '202520215', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(216, 309, 1, '202520216', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(217, 431, 1, '202520217', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(218, 469, 1, '202520218', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(219, 1059, 1, '202520219', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(220, 747, 1, '202520220', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(221, 1032, 1, '202520221', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(222, 48, 1, '202520222', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(223, 1004, 1, '202520223', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(224, 610, 1, '202520224', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(225, 6, 1, '202520225', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(226, 962, 1, '202520226', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(227, 346, 1, '202520227', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(228, 974, 1, '202520228', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(229, 408, 1, '202520229', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(230, 505, 1, '202520230', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(231, 410, 1, '202520231', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(232, 1114, 1, '202520232', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(233, 750, 1, '202520233', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(234, 488, 1, '202520234', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(235, 332, 1, '202520235', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(236, 716, 1, '202520236', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(237, 1018, 1, '202520237', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(238, 745, 1, '202520238', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(239, 1149, 1, '202520239', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(240, 825, 1, '202520240', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(241, 693, 1, '202520241', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(242, 896, 1, '202520242', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(243, 1171, 1, '202520243', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(244, 256, 1, '202520244', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(245, 1168, 1, '202520245', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(246, 329, 1, '202520246', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(247, 865, 1, '202520247', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(248, 182, 1, '202520248', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(249, 886, 1, '202520249', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(250, 387, 1, '202520250', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(251, 841, 1, '202520251', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(252, 990, 1, '202520252', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(253, 397, 1, '202520253', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(254, 636, 1, '202520254', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(255, 853, 1, '202520255', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(256, 642, 1, '202520256', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(257, 235, 1, '202520257', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(258, 270, 1, '202520258', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(259, 780, 1, '202520259', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(260, 316, 1, '202520260', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(261, 944, 1, '202520261', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(262, 403, 1, '202520262', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(263, 154, 1, '202520263', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(264, 1226, 1, '202520264', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(265, 621, 1, '202520265', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(266, 72, 1, '202520266', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(267, 1091, 1, '202520267', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(268, 665, 1, '202520268', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(269, 987, 1, '202520269', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(270, 17, 1, '202520270', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(271, 907, 1, '202520271', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(272, 1213, 1, '202520272', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(273, 476, 1, '202520273', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(274, 122, 1, '202520274', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(275, 383, 1, '202520275', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(276, 952, 1, '202520276', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(277, 628, 1, '202520277', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(278, 188, 1, '202520278', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(279, 775, 1, '202520279', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(280, 816, 1, '202520280', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(281, 201, 1, '202520281', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(282, 311, 1, '202520282', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(283, 1143, 1, '202520283', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(284, 635, 1, '202520284', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(285, 351, 1, '202520285', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(286, 770, 1, '202520286', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(287, 730, 1, '202520287', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(288, 200, 1, '202520288', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(289, 326, 1, '202520289', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(290, 337, 1, '202520290', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(291, 1223, 1, '202520291', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(292, 100, 1, '202520292', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(293, 1086, 1, '202520293', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(294, 117, 1, '202520294', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(295, 758, 1, '202520295', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(296, 12, 1, '202520296', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(297, 676, 1, '202520297', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(298, 813, 1, '202520298', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(299, 500, 1, '202520299', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(300, 1107, 1, '202520300', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(301, 422, 1, '202520301', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(302, 194, 1, '202520302', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(303, 516, 1, '202520303', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(304, 515, 1, '202520304', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(305, 956, 1, '202520305', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(306, 96, 1, '202520306', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(307, 319, 1, '202520307', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(308, 509, 1, '202520308', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(309, 146, 1, '202520309', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(310, 445, 1, '202520310', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(311, 1045, 1, '202520311', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(312, 1042, 1, '202520312', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(313, 382, 1, '202520313', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(314, 199, 1, '202520314', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(315, 783, 1, '202520315', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(316, 377, 1, '202520316', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(317, 872, 1, '202520317', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(318, 819, 1, '202520318', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(319, 22, 1, '202520319', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(320, 741, 1, '202520320', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(321, 360, 1, '202520321', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(322, 9, 1, '202520322', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(323, 1131, 1, '202520323', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(324, 113, 1, '202520324', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(325, 928, 1, '202520325', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(326, 767, 1, '202520326', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(327, 115, 1, '202520327', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(328, 908, 1, '202520328', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(329, 773, 1, '202520329', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(330, 1180, 1, '202520330', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(331, 640, 1, '202520331', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(332, 684, 1, '202520332', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(333, 793, 1, '202520333', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(334, 172, 1, '202520334', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(335, 947, 1, '202520335', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(336, 510, 1, '202520336', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(337, 895, 1, '202520337', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(338, 706, 1, '202520338', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(339, 1125, 1, '202520339', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(340, 79, 1, '202520340', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(341, 1135, 1, '202520341', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(342, 685, 1, '202520342', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(343, 127, 1, '202520343', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(344, 742, 1, '202520344', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(345, 660, 1, '202520345', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(346, 314, 1, '202520346', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(347, 1242, 1, '202520347', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(348, 694, 1, '202520348', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(349, 1137, 1, '202520349', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(350, 1057, 1, '202520350', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(351, 1122, 1, '202520351', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(352, 34, 1, '202520352', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(353, 361, 1, '202520353', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(354, 49, 1, '202520354', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(355, 99, 1, '202520355', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(356, 800, 1, '202520356', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(357, 588, 1, '202520357', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(358, 552, 1, '202520358', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(359, 551, 1, '202520359', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(360, 957, 1, '202520360', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(361, 292, 1, '202520361', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(362, 1100, 1, '202520362', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(363, 889, 1, '202520363', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(364, 302, 1, '202520364', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(365, 557, 1, '202520365', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(366, 1178, 1, '202520366', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(367, 428, 1, '202520367', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(368, 931, 1, '202520368', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(369, 1110, 1, '202520369', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(370, 861, 1, '202520370', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(371, 639, 1, '202520371', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(372, 1217, 1, '202520372', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(373, 539, 1, '202520373', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(374, 162, 1, '202520374', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(375, 700, 1, '202520375', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(376, 92, 1, '202520376', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(377, 582, 1, '202520377', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(378, 806, 1, '202520378', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(379, 924, 1, '202520379', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(380, 1078, 1, '202520380', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(381, 1084, 1, '202520381', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(382, 917, 1, '202520382', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(383, 985, 1, '202520383', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(384, 631, 1, '202520384', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(385, 792, 1, '202520385', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(386, 940, 1, '202520386', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(387, 939, 1, '202520387', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(388, 457, 1, '202520388', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(389, 472, 1, '202520389', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(390, 646, 1, '202520390', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(391, 191, 1, '202520391', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(392, 354, 1, '202520392', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(393, 1062, 1, '202520393', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(394, 603, 1, '202520394', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(395, 1204, 1, '202520395', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(396, 647, 1, '202520396', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(397, 648, 1, '202520397', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(398, 33, 1, '202520398', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(399, 1051, 1, '202520399', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(400, 984, 1, '202520400', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(401, 414, 1, '202520401', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(402, 803, 1, '202520402', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(403, 134, 1, '202520403', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(404, 169, 1, '202520404', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(405, 1076, 1, '202520405', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(406, 1187, 1, '202520406', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(407, 881, 1, '202520407', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(408, 979, 1, '202520408', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(409, 396, 1, '202520409', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(410, 80, 1, '202520410', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(411, 204, 1, '202520411', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(412, 617, 1, '202520412', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(413, 1024, 1, '202520413', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(414, 413, 1, '202520414', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(415, 304, 1, '202520415', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(416, 171, 1, '202520416', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(417, 575, 1, '202520417', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(418, 876, 1, '202520418', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(419, 598, 1, '202520419', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(420, 932, 1, '202520420', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(421, 57, 1, '202520421', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(422, 1165, 1, '202520422', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(423, 432, 1, '202520423', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(424, 391, 1, '202520424', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(425, 1030, 1, '202520425', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(426, 1008, 1, '202520426', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(427, 1023, 1, '202520427', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(428, 263, 1, '202520428', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(429, 25, 1, '202520429', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(430, 862, 1, '202520430', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(431, 399, 1, '202520431', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(432, 212, 1, '202520432', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(433, 652, 1, '202520433', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(434, 1132, 1, '202520434', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(435, 367, 1, '202520435', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(436, 620, 1, '202520436', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(437, 1128, 1, '202520437', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(438, 630, 1, '202520438', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(439, 897, 1, '202520439', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(440, 592, 1, '202520440', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(441, 852, 1, '202520441', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(442, 580, 1, '202520442', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(443, 39, 1, '202520443', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(444, 941, 1, '202520444', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(445, 989, 1, '202520445', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(446, 412, 1, '202520446', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(447, 656, 1, '202520447', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(448, 345, 1, '202520448', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(449, 533, 1, '202520449', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(450, 420, 1, '202520450', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(451, 121, 1, '202520451', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(452, 523, 1, '202520452', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(453, 219, 1, '202520453', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(454, 1141, 1, '202520454', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(455, 223, 1, '202520455', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(456, 232, 1, '202520456', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(457, 1185, 1, '202520457', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(458, 227, 1, '202520458', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(459, 1162, 1, '202520459', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(460, 1239, 1, '202520460', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(461, 868, 1, '202520461', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(462, 593, 1, '202520462', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(463, 753, 1, '202520463', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(464, 138, 1, '202520464', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(465, 1034, 1, '202520465', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(466, 110, 1, '202520466', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(467, 632, 1, '202520467', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(468, 210, 1, '202520468', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(469, 677, 1, '202520469', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(470, 934, 1, '202520470', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(471, 140, 1, '202520471', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(472, 561, 1, '202520472', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(473, 1117, 1, '202520473', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(474, 324, 1, '202520474', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(475, 375, 1, '202520475', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(476, 31, 1, '202520476', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(477, 844, 1, '202520477', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(478, 247, 1, '202520478', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(479, 179, 1, '202520479', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(480, 281, 1, '202520480', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(481, 126, 1, '202520481', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(482, 213, 1, '202520482', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(483, 13, 1, '202520483', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(484, 983, 1, '202520484', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(485, 641, 1, '202520485', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(486, 562, 1, '202520486', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(487, 137, 1, '202520487', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(488, 331, 1, '202520488', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(489, 1127, 1, '202520489', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(490, 45, 1, '202520490', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(491, 98, 1, '202520491', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(492, 494, 1, '202520492', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(493, 634, 1, '202520493', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(494, 52, 1, '202520494', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(495, 1151, 1, '202520495', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(496, 330, 1, '202520496', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(497, 600, 1, '202520497', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(498, 651, 1, '202520498', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(499, 545, 1, '202520499', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(500, 71, 1, '202520500', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(501, 202, 1, '202520501', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(502, 611, 1, '202520502', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(503, 183, 1, '202520503', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(504, 44, 1, '202520504', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(505, 766, 1, '202520505', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(506, 829, 1, '202520506', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(507, 909, 1, '202520507', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(508, 970, 1, '202520508', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(509, 181, 1, '202520509', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(510, 1235, 1, '202520510', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(511, 599, 1, '202520511', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(512, 59, 1, '202520512', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(513, 1193, 1, '202520513', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(514, 739, 1, '202520514', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(515, 421, 1, '202520515', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(516, 407, 1, '202520516', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(517, 402, 1, '202520517', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(518, 111, 1, '202520518', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(519, 220, 1, '202520519', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(520, 994, 1, '202520520', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(521, 524, 1, '202520521', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(522, 1207, 1, '202520522', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(523, 998, 1, '202520523', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(524, 795, 1, '202520524', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(525, 288, 1, '202520525', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(526, 1167, 1, '202520526', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(527, 1104, 1, '202520527', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(528, 1099, 1, '202520528', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(529, 681, 1, '202520529', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(530, 1046, 1, '202520530', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(531, 521, 1, '202520531', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(532, 334, 1, '202520532', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(533, 55, 1, '202520533', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(534, 559, 1, '202520534', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(535, 67, 1, '202520535', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(536, 906, 1, '202520536', '2025-II', 'III', 3, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(537, 258, 1, '202520537', '2025-II', 'III', 3, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(538, 1130, 1, '202520538', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(539, 239, 1, '202520539', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(540, 1138, 1, '202520540', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(541, 796, 1, '202520541', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(542, 699, 1, '202520542', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(543, 922, 1, '202520543', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(544, 189, 1, '202520544', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(545, 1043, 1, '202520545', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(546, 624, 1, '202520546', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(547, 364, 1, '202520547', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(548, 1093, 1, '202520548', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(549, 787, 1, '202520549', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(550, 531, 1, '202520550', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(551, 103, 1, '202520551', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(552, 168, 1, '202520552', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(553, 479, 1, '202520553', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(554, 1116, 1, '202520554', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(555, 503, 1, '202520555', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(556, 1108, 1, '202520556', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(557, 394, 1, '202520557', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(558, 650, 1, '202520558', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(559, 991, 1, '202520559', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(560, 1098, 1, '202520560', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(561, 1146, 1, '202520561', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(562, 242, 1, '202520562', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(563, 222, 1, '202520563', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(564, 340, 1, '202520564', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(565, 473, 1, '202520565', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(566, 945, 1, '202520566', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(567, 1150, 1, '202520567', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(568, 859, 1, '202520568', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(569, 1219, 1, '202520569', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(570, 1214, 1, '202520570', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(571, 1211, 1, '202520571', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(572, 448, 1, '202520572', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', '');
INSERT INTO `matricula` (`id`, `estudiante`, `prog_estudios`, `id_matricula`, `per_lectivo`, `per_acad`, `per_acad2`, `seccion`, `turno`, `fec_matricula`, `cond_matricula`, `est_matricula`, `est_perlec`, `obs_matricula`) VALUES
(573, 15, 1, '202520573', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(574, 784, 1, '202520574', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(575, 1238, 1, '202520575', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(576, 395, 1, '202520576', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(577, 77, 1, '202520577', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(578, 712, 1, '202520578', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(579, 459, 1, '202520579', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(580, 216, 1, '202520580', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(581, 116, 1, '202520581', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(582, 1166, 1, '202520582', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(583, 971, 1, '202520583', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(584, 765, 1, '202520584', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(585, 1186, 1, '202520585', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(586, 820, 1, '202520586', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(587, 809, 1, '202520587', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(588, 118, 1, '202520588', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(589, 187, 1, '202520589', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(590, 845, 1, '202520590', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(591, 683, 1, '202520591', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(592, 590, 1, '202520592', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(593, 170, 1, '202520593', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(594, 1218, 1, '202520594', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(595, 439, 1, '202520595', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(596, 252, 1, '202520596', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(597, 797, 1, '202520597', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(598, 968, 1, '202520598', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(599, 1246, 1, '202520599', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(600, 1048, 1, '202520600', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(601, 884, 1, '202520601', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(602, 29, 1, '202520602', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(603, 135, 1, '202520603', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(604, 571, 1, '202520604', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(605, 1094, 1, '202520605', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(606, 1040, 1, '202520606', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(607, 949, 1, '202520607', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(608, 264, 1, '202520608', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(609, 1163, 1, '202520609', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(610, 449, 1, '202520610', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(611, 1073, 1, '202520611', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(612, 1035, 1, '202520612', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(613, 686, 1, '202520613', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(614, 892, 1, '202520614', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(615, 496, 1, '202520615', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(616, 195, 1, '202520616', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(617, 184, 1, '202520617', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(618, 250, 1, '202520618', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(619, 249, 1, '202520619', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(620, 645, 1, '202520620', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(621, 1118, 1, '202520621', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(622, 1197, 1, '202520622', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(623, 105, 1, '202520623', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(624, 1022, 1, '202520624', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(625, 271, 1, '202520625', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(626, 579, 1, '202520626', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(627, 1249, 1, '202520627', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(628, 266, 1, '202520628', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(629, 777, 1, '202520629', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(630, 935, 1, '202520630', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(631, 129, 1, '202520631', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(632, 236, 1, '202520632', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(633, 468, 1, '202520633', '2025-II', 'III', 3, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(634, 312, 1, '202520634', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(635, 920, 1, '202520635', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(636, 353, 1, '202520636', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(637, 495, 1, '202520637', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(638, 286, 1, '202520638', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(639, 106, 1, '202520639', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(640, 70, 1, '202520640', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(641, 66, 1, '202520641', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(642, 658, 1, '202520642', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(643, 606, 1, '202520643', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(644, 1028, 1, '202520644', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(645, 229, 1, '202520645', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(646, 587, 1, '202520646', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(647, 95, 1, '202520647', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(648, 732, 1, '202520648', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(649, 63, 1, '202520649', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(650, 1053, 1, '202520650', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(651, 215, 1, '202520651', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(652, 93, 1, '202520652', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(653, 53, 1, '202520653', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(654, 142, 1, '202520654', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(655, 817, 1, '202520655', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(656, 894, 1, '202520656', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(657, 1088, 1, '202520657', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(658, 1179, 1, '202520658', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(659, 953, 1, '202520659', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(660, 88, 1, '202520660', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(661, 1155, 1, '202520661', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(662, 464, 1, '202520662', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(663, 435, 1, '202520663', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(664, 1031, 1, '202520664', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(665, 769, 1, '202520665', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(666, 594, 1, '202520666', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(667, 104, 1, '202520667', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(668, 190, 1, '202520668', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(669, 477, 1, '202520669', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(670, 556, 1, '202520670', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(671, 554, 1, '202520671', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(672, 1237, 1, '202520672', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(673, 946, 1, '202520673', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(674, 415, 1, '202520674', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(675, 374, 1, '202520675', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(676, 1016, 1, '202520676', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(677, 1133, 1, '202520677', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(678, 1183, 1, '202520678', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(679, 670, 1, '202520679', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(680, 350, 1, '202520680', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(681, 986, 1, '202520681', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(682, 814, 1, '202520682', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(683, 483, 1, '202520683', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(684, 659, 1, '202520684', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(685, 725, 1, '202520685', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(686, 654, 1, '202520686', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(687, 192, 1, '202520687', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(688, 1087, 1, '202520688', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(689, 287, 1, '202520689', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(690, 672, 1, '202520690', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(691, 470, 1, '202520691', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(692, 1065, 1, '202520692', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(693, 1069, 1, '202520693', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(694, 207, 1, '202520694', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(695, 771, 1, '202520695', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(696, 274, 1, '202520696', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(697, 120, 1, '202520697', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(698, 1003, 1, '202520698', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(699, 738, 1, '202520699', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(700, 869, 1, '202520700', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(701, 695, 1, '202520701', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(702, 864, 1, '202520702', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(703, 673, 1, '202520703', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(704, 1007, 1, '202520704', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(705, 450, 1, '202520705', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(706, 1096, 1, '202520706', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(707, 328, 1, '202520707', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(708, 260, 1, '202520708', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(709, 564, 1, '202520709', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(710, 675, 1, '202520710', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(711, 601, 1, '202520711', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(712, 87, 1, '202520712', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(713, 901, 1, '202520713', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(714, 19, 1, '202520714', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(715, 7, 1, '202520715', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(716, 734, 1, '202520716', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(717, 504, 1, '202520717', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(718, 74, 1, '202520718', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(719, 992, 1, '202520719', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(720, 295, 1, '202520720', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(721, 802, 1, '202520721', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(722, 692, 1, '202520722', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(723, 846, 1, '202520723', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(724, 388, 1, '202520724', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(725, 838, 1, '202520725', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(726, 24, 1, '202520726', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(727, 1240, 1, '202520727', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(728, 285, 1, '202520728', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(729, 84, 1, '202520729', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(730, 1203, 1, '202520730', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(731, 967, 1, '202520731', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(732, 1011, 1, '202520732', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(733, 1222, 1, '202520733', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(734, 147, 1, '202520734', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(735, 203, 1, '202520735', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(736, 1210, 1, '202520736', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(737, 1202, 1, '202520737', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(738, 525, 1, '202520738', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(739, 1159, 1, '202520739', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(740, 936, 1, '202520740', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(741, 1050, 1, '202520741', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(742, 376, 1, '202520742', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(743, 1170, 1, '202520743', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(744, 1175, 1, '202520744', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(745, 119, 1, '202520745', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(746, 36, 1, '202520746', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(747, 278, 1, '202520747', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(748, 1227, 1, '202520748', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(749, 904, 1, '202520749', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(750, 50, 1, '202520750', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(751, 65, 1, '202520751', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(752, 655, 1, '202520752', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(753, 131, 1, '202520753', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(754, 1247, 1, '202520754', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(755, 690, 1, '202520755', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(756, 572, 1, '202520756', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(757, 1172, 1, '202520757', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(758, 454, 1, '202520758', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(759, 719, 1, '202520759', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(760, 321, 1, '202520760', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(761, 224, 1, '202520761', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(762, 378, 1, '202520762', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(763, 305, 1, '202520763', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(764, 973, 1, '202520764', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(765, 724, 1, '202520765', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(766, 1228, 1, '202520766', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(767, 835, 1, '202520767', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(768, 1152, 1, '202520768', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(769, 848, 1, '202520769', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(770, 463, 1, '202520770', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(771, 774, 1, '202520771', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(772, 988, 1, '202520772', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(773, 776, 1, '202520773', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(774, 875, 1, '202520774', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(775, 461, 1, '202520775', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(776, 83, 1, '202520776', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(777, 1056, 1, '202520777', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(778, 609, 1, '202520778', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(779, 560, 1, '202520779', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(780, 653, 1, '202520780', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(781, 152, 1, '202520781', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(782, 4, 1, '202520782', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(783, 153, 1, '202520783', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(784, 517, 1, '202520784', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(785, 352, 1, '202520785', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(786, 964, 1, '202520786', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(787, 965, 1, '202520787', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(788, 1058, 1, '202520788', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(789, 900, 1, '202520789', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(790, 177, 1, '202520790', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(791, 1085, 1, '202520791', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(792, 902, 1, '202520792', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(793, 923, 1, '202520793', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(794, 573, 1, '202520794', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(795, 75, 1, '202520795', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(796, 828, 1, '202520796', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(797, 21, 1, '202520797', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(798, 344, 1, '202520798', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(799, 60, 1, '202520799', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(800, 1208, 1, '202520800', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(801, 1106, 1, '202520801', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(802, 615, 1, '202520802', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(803, 164, 1, '202520803', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(804, 740, 1, '202520804', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(805, 42, 1, '202520805', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(806, 241, 1, '202520806', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(807, 156, 1, '202520807', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(808, 155, 1, '202520808', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(809, 717, 1, '202520809', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(810, 1231, 1, '202520810', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(811, 558, 1, '202520811', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(812, 465, 1, '202520812', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(813, 462, 1, '202520813', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(814, 276, 1, '202520814', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(815, 335, 1, '202520815', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(816, 679, 1, '202520816', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(817, 704, 1, '202520817', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(818, 165, 1, '202520818', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(819, 159, 1, '202520819', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(820, 805, 1, '202520820', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(821, 426, 1, '202520821', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(822, 837, 1, '202520822', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(823, 325, 1, '202520823', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(824, 729, 1, '202520824', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(825, 254, 1, '202520825', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(826, 398, 1, '202520826', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(827, 1236, 1, '202520827', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(828, 425, 1, '202520828', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(829, 427, 1, '202520829', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(830, 735, 1, '202520830', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(831, 577, 1, '202520831', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(832, 1015, 1, '202520832', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(833, 1174, 1, '202520833', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(834, 1005, 1, '202520834', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(835, 512, 1, '202520835', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(836, 513, 1, '202520836', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(837, 785, 1, '202520837', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(838, 341, 1, '202520838', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(839, 1089, 1, '202520839', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(840, 368, 1, '202520840', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(841, 482, 1, '202520841', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(842, 217, 1, '202520842', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(843, 443, 1, '202520843', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(844, 960, 1, '202520844', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(845, 662, 1, '202520845', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(846, 240, 1, '202520846', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(847, 1000, 1, '202520847', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(848, 1234, 1, '202520848', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(849, 544, 1, '202520849', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(850, 667, 1, '202520850', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(851, 682, 1, '202520851', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(852, 540, 1, '202520852', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(853, 961, 1, '202520853', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(854, 669, 1, '202520854', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(855, 400, 1, '202520855', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(856, 1225, 1, '202520856', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(857, 1109, 1, '202520857', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(858, 1103, 1, '202520858', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(859, 701, 1, '202520859', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(860, 320, 1, '202520860', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(861, 386, 1, '202520861', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(862, 614, 1, '202520862', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(863, 359, 1, '202520863', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(864, 198, 1, '202520864', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(865, 912, 1, '202520865', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(866, 834, 1, '202520866', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(867, 91, 1, '202520867', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(868, 781, 1, '202520868', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(869, 1251, 1, '202520869', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(870, 502, 1, '202520870', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(871, 618, 1, '202520871', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(872, 627, 1, '202520872', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(873, 529, 1, '202520873', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(874, 419, 1, '202520874', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(875, 144, 1, '202520875', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(876, 347, 1, '202520876', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(877, 444, 1, '202520877', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(878, 89, 1, '202520878', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(879, 440, 1, '202520879', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(880, 550, 1, '202520880', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(881, 1019, 1, '202520881', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(882, 56, 1, '202520882', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(883, 955, 1, '202520883', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(884, 836, 1, '202520884', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(885, 764, 1, '202520885', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(886, 205, 1, '202520886', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(887, 290, 1, '202520887', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(888, 369, 1, '202520888', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(889, 245, 1, '202520889', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(890, 424, 1, '202520890', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(891, 759, 1, '202520891', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(892, 1199, 1, '202520892', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(893, 905, 1, '202520893', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(894, 1041, 1, '202520894', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(895, 306, 1, '202520895', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(896, 197, 1, '202520896', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(897, 977, 1, '202520897', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(898, 978, 1, '202520898', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(899, 348, 1, '202520899', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(900, 1181, 1, '202520900', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(901, 514, 1, '202520901', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(902, 536, 1, '202520902', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(903, 698, 1, '202520903', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(904, 873, 1, '202520904', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(905, 356, 1, '202520905', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(906, 723, 1, '202520906', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(907, 808, 1, '202520907', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(908, 842, 1, '202520908', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(909, 273, 1, '202520909', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(910, 511, 1, '202520910', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(911, 508, 1, '202520911', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(912, 913, 1, '202520912', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(913, 1221, 1, '202520913', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(914, 633, 1, '202520914', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(915, 1158, 1, '202520915', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(916, 666, 1, '202520916', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(917, 102, 1, '202520917', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(918, 746, 1, '202520918', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(919, 506, 1, '202520919', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(920, 1052, 1, '202520920', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(921, 982, 1, '202520921', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(922, 315, 1, '202520922', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(923, 446, 1, '202520923', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(924, 878, 1, '202520924', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(925, 404, 1, '202520925', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(926, 903, 1, '202520926', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(927, 480, 1, '202520927', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(928, 416, 1, '202520928', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(929, 225, 1, '202520929', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(930, 297, 1, '202520930', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(931, 883, 1, '202520931', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(932, 882, 1, '202520932', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(933, 307, 1, '202520933', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(934, 948, 1, '202520934', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(935, 930, 1, '202520935', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(936, 124, 1, '202520936', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(937, 37, 1, '202520937', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(938, 762, 1, '202520938', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(939, 451, 1, '202520939', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(940, 703, 1, '202520940', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(941, 702, 1, '202520941', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(942, 90, 1, '202520942', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(943, 807, 1, '202520943', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(944, 136, 1, '202520944', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(945, 1216, 1, '202520945', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(946, 1244, 1, '202520946', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(947, 867, 1, '202520947', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(948, 485, 1, '202520948', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(949, 1082, 1, '202520949', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(950, 1080, 1, '202520950', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(951, 966, 1, '202520951', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(952, 434, 1, '202520952', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(953, 563, 1, '202520953', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(954, 54, 1, '202520954', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(955, 826, 1, '202520955', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(956, 591, 1, '202520956', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(957, 30, 1, '202520957', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(958, 46, 1, '202520958', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(959, 1017, 1, '202520959', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(960, 230, 1, '202520960', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(961, 847, 1, '202520961', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(962, 733, 1, '202520962', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(963, 471, 1, '202520963', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(964, 299, 1, '202520964', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(965, 1156, 1, '202520965', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(966, 363, 1, '202520966', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(967, 794, 1, '202520967', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(968, 720, 1, '202520968', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(969, 879, 1, '202520969', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(970, 430, 1, '202520970', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(971, 1129, 1, '202520971', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(972, 715, 1, '202520972', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(973, 1027, 1, '202520973', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(974, 429, 1, '202520974', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(975, 1075, 1, '202520975', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(976, 671, 1, '202520976', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(977, 786, 1, '202520977', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(978, 721, 1, '202520978', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(979, 218, 1, '202520979', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(980, 228, 1, '202520980', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(981, 942, 1, '202520981', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(982, 272, 1, '202520982', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(983, 1102, 1, '202520983', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(984, 663, 1, '202520984', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(985, 1154, 1, '202520985', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(986, 1079, 1, '202520986', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(987, 11, 1, '202520987', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(988, 815, 1, '202520988', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(989, 736, 1, '202520989', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(990, 1009, 1, '202520990', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(991, 163, 1, '202520991', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(992, 16, 1, '202520992', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(993, 456, 1, '202520993', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(994, 1061, 1, '202520994', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(995, 522, 1, '202520995', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(996, 112, 1, '202520996', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(997, 891, 1, '202520997', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(998, 1209, 1, '202520998', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(999, 1081, 1, '202520999', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1000, 782, 1, '202521000', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1001, 532, 1, '202521001', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1002, 798, 1, '202521002', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1003, 744, 1, '202521003', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1004, 114, 1, '202521004', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1005, 850, 1, '202521005', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1006, 366, 1, '202521006', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1007, 151, 1, '202521007', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1008, 125, 1, '202521008', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1009, 243, 1, '202521009', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1010, 41, 1, '202521010', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1011, 711, 1, '202521011', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1012, 760, 1, '202521012', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1013, 282, 1, '202521013', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1014, 262, 1, '202521014', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1015, 484, 1, '202521015', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1016, 1147, 1, '202521016', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1017, 318, 1, '202521017', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1018, 1047, 1, '202521018', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1019, 1071, 1, '202521019', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1020, 475, 1, '202521020', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1021, 757, 1, '202521021', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1022, 1113, 1, '202521022', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1023, 1126, 1, '202521023', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1024, 393, 1, '202521024', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1025, 392, 1, '202521025', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1026, 317, 1, '202521026', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1027, 1039, 1, '202521027', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1028, 132, 1, '202521028', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1029, 1049, 1, '202521029', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1030, 379, 1, '202521030', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1031, 390, 1, '202521031', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1032, 1026, 1, '202521032', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1033, 308, 1, '202521033', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1034, 1164, 1, '202521034', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1035, 166, 1, '202521035', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1036, 831, 1, '202521036', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1037, 64, 1, '202521037', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1038, 373, 1, '202521038', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1039, 1020, 1, '202521039', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1040, 921, 1, '202521040', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1041, 294, 1, '202521041', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1042, 818, 1, '202521042', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1043, 749, 1, '202521043', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1044, 460, 1, '202521044', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1045, 555, 1, '202521045', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1046, 608, 1, '202521046', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1047, 843, 1, '202521047', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1048, 101, 1, '202521048', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1049, 411, 1, '202521049', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1050, 1054, 1, '202521050', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1051, 23, 1, '202521051', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1052, 918, 1, '202521052', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1053, 607, 1, '202521053', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1054, 583, 1, '202521054', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1055, 1194, 1, '202521055', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1056, 233, 1, '202521056', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1057, 543, 1, '202521057', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1058, 158, 1, '202521058', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1059, 269, 1, '202521059', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1060, 664, 1, '202521060', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1061, 999, 1, '202521061', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1062, 959, 1, '202521062', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1063, 751, 1, '202521063', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1064, 546, 1, '202521064', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1065, 2, 1, '202521065', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1066, 1160, 1, '202521066', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1067, 437, 1, '202521067', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1068, 731, 1, '202521068', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1069, 713, 1, '202521069', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1070, 916, 1, '202521070', '2025-II', 'III', 3, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1071, 458, 1, '202521071', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1072, 259, 1, '202521072', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1073, 174, 1, '202521073', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1074, 336, 1, '202521074', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1075, 234, 1, '202521075', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1076, 26, 1, '202521076', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1077, 870, 1, '202521077', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1078, 688, 1, '202521078', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1079, 860, 1, '202521079', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1080, 823, 1, '202521080', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1081, 1157, 1, '202521081', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1082, 789, 1, '202521082', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1083, 1220, 1, '202521083', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1084, 863, 1, '202521084', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1085, 602, 1, '202521085', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1086, 76, 1, '202521086', '2025-II', 'III', 3, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1087, 109, 1, '202521087', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1088, 1229, 1, '202521088', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1089, 691, 1, '202521089', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1090, 43, 1, '202521090', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1091, 1224, 1, '202521091', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1092, 141, 1, '202521092', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1093, 94, 1, '202521093', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1094, 708, 1, '202521094', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1095, 709, 1, '202521095', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1096, 710, 1, '202521096', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1097, 211, 1, '202521097', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1098, 133, 1, '202521098', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1099, 1044, 1, '202521099', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1100, 637, 1, '202521100', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1101, 327, 1, '202521101', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1102, 491, 1, '202521102', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1103, 442, 1, '202521103', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1104, 1245, 1, '202521104', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1105, 919, 1, '202521105', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1106, 788, 1, '202521106', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1107, 605, 1, '202521107', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1108, 130, 1, '202521108', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1109, 478, 1, '202521109', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1110, 401, 1, '202521110', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1111, 157, 1, '202521111', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1112, 81, 1, '202521112', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1113, 493, 1, '202521113', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1114, 541, 1, '202521114', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1115, 389, 1, '202521115', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1116, 173, 1, '202521116', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1117, 668, 1, '202521117', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1118, 674, 1, '202521118', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1119, 880, 1, '202521119', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1120, 899, 1, '202521120', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1121, 1013, 1, '202521121', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1122, 526, 1, '202521122', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1123, 1001, 1, '202521123', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1124, 849, 1, '202521124', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1125, 756, 1, '202521125', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1126, 381, 1, '202521126', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1127, 1161, 1, '202521127', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1128, 595, 1, '202521128', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1129, 714, 1, '202521129', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1130, 1145, 1, '202521130', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1131, 910, 1, '202521131', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1132, 1252, 1, '202521132', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1133, 811, 1, '202521133', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1134, 481, 1, '202521134', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1135, 453, 1, '202521135', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1136, 1064, 1, '202521136', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1137, 86, 1, '202521137', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1138, 728, 1, '202521138', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1139, 638, 1, '202521139', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1140, 14, 1, '202521140', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1141, 586, 1, '202521141', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', '');
INSERT INTO `matricula` (`id`, `estudiante`, `prog_estudios`, `id_matricula`, `per_lectivo`, `per_acad`, `per_acad2`, `seccion`, `turno`, `fec_matricula`, `cond_matricula`, `est_matricula`, `est_perlec`, `obs_matricula`) VALUES
(1142, 289, 1, '202521142', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1143, 1014, 1, '202521143', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1144, 871, 1, '202521144', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1145, 310, 1, '202521145', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1146, 748, 1, '202521146', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1147, 405, 1, '202521147', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1148, 743, 1, '202521148', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1149, 178, 1, '202521149', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1150, 487, 1, '202521150', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1151, 1148, 1, '202521151', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1152, 433, 1, '202521152', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1153, 438, 1, '202521153', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1154, 914, 1, '202521154', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1155, 1112, 1, '202521155', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1156, 1068, 1, '202521156', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1157, 996, 1, '202521157', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1158, 436, 1, '202521158', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1159, 214, 1, '202521159', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1160, 248, 1, '202521160', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1161, 858, 1, '202521161', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1162, 291, 1, '202521162', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1163, 357, 1, '202521163', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1164, 507, 1, '202521164', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1165, 791, 1, '202521165', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1166, 790, 1, '202521166', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1167, 1036, 1, '202521167', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1168, 1067, 1, '202521168', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1169, 206, 1, '202521169', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1170, 893, 1, '202521170', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1171, 255, 1, '202521171', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1172, 362, 1, '202521172', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1173, 915, 1, '202521173', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1174, 145, 1, '202521174', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1175, 1153, 1, '202521175', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1176, 486, 1, '202521176', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1177, 574, 1, '202521177', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1178, 1182, 1, '202521178', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1179, 501, 1, '202521179', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1180, 161, 1, '202521180', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1181, 975, 1, '202521181', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1182, 149, 1, '202521182', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1183, 726, 1, '202521183', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1184, 167, 1, '202521184', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1185, 1033, 1, '202521185', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1186, 1124, 1, '202521186', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1187, 722, 1, '202521187', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1188, 474, 1, '202521188', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1189, 1144, 1, '202521189', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1190, 569, 1, '202521190', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1191, 851, 1, '202521191', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1192, 1021, 1, '202521192', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1193, 300, 1, '202521193', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1194, 604, 1, '202521194', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1195, 697, 1, '202521195', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1196, 530, 1, '202521196', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1197, 409, 1, '202521197', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1198, 97, 1, '202521198', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1199, 581, 1, '202521199', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1200, 1006, 1, '202521200', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1201, 143, 1, '202521201', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1202, 1115, 1, '202521202', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1203, 1169, 1, '202521203', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1204, 1206, 1, '202521204', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1205, 62, 1, '202521205', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1206, 680, 1, '202521206', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1207, 898, 1, '202521207', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1208, 520, 1, '202521208', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1209, 1, 1, '202521209', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1210, 284, 1, '202521210', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1211, 355, 1, '202521211', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1212, 68, 1, '202521212', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1213, 696, 1, '202521213', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1214, 338, 1, '202521214', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1215, 1243, 1, '202521215', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1216, 763, 1, '202521216', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1217, 801, 1, '202521217', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1218, 972, 1, '202521218', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1219, 1136, 1, '202521219', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1220, 1105, 1, '202521220', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1221, 625, 1, '202521221', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1222, 293, 1, '202521222', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1223, 231, 1, '202521223', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1224, 447, 1, '202521224', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1225, 279, 1, '202521225', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1226, 542, 1, '202521226', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1227, 280, 1, '202521227', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1228, 1134, 1, '202521228', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1229, 128, 1, '202521229', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1230, 257, 1, '202521230', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1231, 150, 1, '202521231', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1232, 1012, 1, '202521232', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1233, 180, 1, '202521233', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1234, 830, 1, '202521234', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1235, 371, 1, '202521235', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1236, 832, 1, '202521236', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1237, 1191, 1, '202521237', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1238, 1250, 1, '202521238', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1239, 298, 1, '202521239', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1240, 1140, 1, '202521240', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1241, 1002, 1, '202521241', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1242, 78, 1, '202521242', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1243, 804, 1, '202521243', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1244, 565, 1, '202521244', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1245, 911, 1, '202521245', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1246, 5, 1, '202521246', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1247, 301, 1, '202521247', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1248, 839, 1, '202521248', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1249, 139, 1, '202521249', '2025-II', 'IV', 4, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1250, 251, 1, '202521250', '2025-II', 'II', 2, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1251, 570, 1, '202521251', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', ''),
(1252, 661, 1, '202521252', '2025-II', 'VI', 6, 'A', '', '0000-00-00', '', 'A', 'S', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medio_consecucion`
--

CREATE TABLE `medio_consecucion` (
  `id` int(11) NOT NULL,
  `nombre_medio` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel_alerta`
--

CREATE TABLE `nivel_alerta` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `bloquea` int(11) DEFAULT NULL,
  `prioridad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id` int(11) NOT NULL,
  `usuario` int(11) DEFAULT NULL,
  `convenio` int(11) DEFAULT NULL,
  `comunicacion` int(11) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `prioridad` varchar(20) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `leida` int(11) DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  `email_enviado` int(11) DEFAULT NULL,
  `tipouser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `estudiante` int(11) NOT NULL,
  `solicitudes` int(11) DEFAULT NULL,
  `tipo_pago` int(11) NOT NULL,
  `monto_original` decimal(10,2) NOT NULL,
  `monto_descuento` decimal(10,2) DEFAULT 0.00,
  `monto_final` decimal(10,2) NOT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `comprobante` varchar(255) DEFAULT NULL,
  `registrado_por` int(11) DEFAULT NULL,
  `registrado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_cap`
--

CREATE TABLE `pago_cap` (
  `id` int(11) NOT NULL,
  `monto_pago` decimal(5,2) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `estado_pago` enum('porconfirmar','confirmado') DEFAULT NULL,
  `inscripcion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante_reunion`
--

CREATE TABLE `participante_reunion` (
  `id` int(11) NOT NULL,
  `reunion` int(11) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `rol` varchar(20) DEFAULT NULL,
  `confirmo` int(11) DEFAULT NULL,
  `asistio` int(11) DEFAULT NULL,
  `tipouser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practicas`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prog_estudios`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recordatorio`
--

CREATE TABLE `recordatorio` (
  `id` int(11) NOT NULL,
  `notificacion` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `evento` varchar(50) DEFAULT NULL,
  `dias_antes` int(11) DEFAULT NULL,
  `fecha_programada` datetime DEFAULT NULL,
  `enviado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `renovacion`
--

CREATE TABLE `renovacion` (
  `id` int(11) NOT NULL,
  `convenio` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `iniciado_por` varchar(20) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_aprobacion` datetime DEFAULT NULL,
  `meses_extension` int(11) DEFAULT NULL,
  `requiere_reunion` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representante`
--

CREATE TABLE `representante` (
  `id` int(11) NOT NULL,
  `instituto` int(11) DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `nombre_completo` varchar(255) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `puede_firmar` int(11) DEFAULT NULL,
  `fecha_desde` date DEFAULT NULL,
  `fecha_hasta` date DEFAULT NULL,
  `activo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resoluciones`
--

CREATE TABLE `resoluciones` (
  `id` int(11) NOT NULL,
  `numero_resolucion` varchar(50) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `texto_respaldo` text DEFAULT NULL,
  `ruta_documento` varchar(255) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `creado_en` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reunion`
--

CREATE TABLE `reunion` (
  `id` int(11) NOT NULL,
  `convenio` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL,
  `modalidad` varchar(20) DEFAULT NULL,
  `link_virtual` varchar(500) DEFAULT NULL,
  `lugar` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `acta` varchar(500) DEFAULT NULL,
  `propuesta_por` int(11) DEFAULT NULL,
  `fecha_propuesta` datetime DEFAULT NULL,
  `fecha_confirmacion` datetime DEFAULT NULL,
  `tipouser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sector`
--

CREATE TABLE `sector` (
  `id` int(11) NOT NULL,
  `nombre_sector` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento`
--

CREATE TABLE `seguimiento` (
  `id` int(11) NOT NULL,
  `estudiante` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `situacion_laboral`
--

CREATE TABLE `situacion_laboral` (
  `id` int(11) NOT NULL,
  `estudiante` int(11) DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `trabaja` int(11) DEFAULT NULL,
  `labora_programa_estudios` int(11) DEFAULT NULL,
  `cargo_actual` varchar(200) DEFAULT NULL,
  `condicion_laboral` int(11) DEFAULT NULL,
  `ingreso_bruto_mensual` decimal(10,2) DEFAULT NULL,
  `satisfaccion_trabajo` varchar(50) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `situacion_medio`
--

CREATE TABLE `situacion_medio` (
  `situacion` int(11) NOT NULL,
  `medio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL,
  `estudiante` int(11) NOT NULL,
  `resoluciones` int(11) DEFAULT NULL,
  `tipo_solicitud` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('pendiente','en_evaluacion','aprobado','rechazado') DEFAULT 'pendiente',
  `fecha_solicitud` datetime DEFAULT NULL,
  `fecha_revision` datetime DEFAULT NULL,
  `empleado` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_convenio`
--

CREATE TABLE `tipo_convenio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `plantilla` varchar(500) DEFAULT NULL,
  `requiere_coord_academica` int(11) DEFAULT NULL,
  `activa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_curso`
--

CREATE TABLE `tipo_curso` (
  `id` int(11) NOT NULL,
  `nom_tipocurso` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `plantilla` varchar(500) DEFAULT NULL,
  `requiere_firma` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_recordatorio`
--

CREATE TABLE `tipo_recordatorio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_renovacion`
--

CREATE TABLE `tipo_renovacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `requiere_firma` int(11) DEFAULT NULL,
  `duracion_dias` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_seguimiento`
--

CREATE TABLE `tipo_seguimiento` (
  `id` int(11) NOT NULL,
  `nombre_tipo` varchar(100) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubdepartamento`
--

CREATE TABLE `ubdepartamento` (
  `id` int(11) NOT NULL,
  `departamento` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ubdepartamento`
--

INSERT INTO `ubdepartamento` (`id`, `departamento`) VALUES
(1, 'AMAZONAS'),
(2, 'ANCASH'),
(3, 'APURIMAC'),
(4, 'AREQUIPA'),
(5, 'AYACUCHO'),
(6, 'CAJAMARCA'),
(7, 'CALLAO'),
(8, 'CUSCO'),
(9, 'HUANCAVELICA'),
(10, 'HUANUCO'),
(11, 'ICA'),
(12, 'JUNIN'),
(13, 'LA LIBERTAD'),
(14, 'LAMBAYEQUE'),
(15, 'LIMA'),
(16, 'LORETO'),
(17, 'MADRE DE DIOS'),
(18, 'MOQUEGUA'),
(19, 'PASCO'),
(20, 'PIURA'),
(21, 'PUNO'),
(22, 'SAN MARTIN'),
(23, 'TACNA'),
(24, 'TUMBES'),
(25, 'UCAYALI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubdistrito`
--

CREATE TABLE `ubdistrito` (
  `id` int(11) NOT NULL,
  `distrito` varchar(250) DEFAULT NULL,
  `ubprovincia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ubdistrito`
--

INSERT INTO `ubdistrito` (`id`, `distrito`, `ubprovincia`) VALUES
(1, 'CHACHAPOYAS', 1),
(2, 'ASUNCION', 1),
(3, 'BALSAS', 1),
(4, 'CHETO', 1),
(5, 'CHILIQUIN', 1),
(6, 'CHUQUIBAMBA', 1),
(7, 'GRANADA', 1),
(8, 'HUANCAS', 1),
(9, 'LA JALCA', 1),
(10, 'LEIMEBAMBA', 1),
(11, 'LEVANTO', 1),
(12, 'MAGDALENA', 1),
(13, 'MARISCAL CASTILLA', 1),
(14, 'MOLINOPAMPA', 1),
(15, 'MONTEVIDEO', 1),
(16, 'OLLEROS', 1),
(17, 'QUINJALCA', 1),
(18, 'SAN FRANCISCO DE DAGUAS', 1),
(19, 'SAN ISIDRO DE MAINO', 1),
(20, 'SOLOCO', 1),
(21, 'SONCHE', 1),
(22, 'LA PECA', 2),
(23, 'ARAMANGO', 2),
(24, 'COPALLIN', 2),
(25, 'EL PARCO', 2),
(26, 'IMAZA', 2),
(27, 'JUMBILLA', 3),
(28, 'CHISQUILLA', 3),
(29, 'CHURUJA', 3),
(30, 'COROSHA', 3),
(31, 'CUISPES', 3),
(32, 'FLORIDA', 3),
(33, 'JAZAN', 3),
(34, 'RECTA', 3),
(35, 'SAN CARLOS', 3),
(36, 'SHIPASBAMBA', 3),
(37, 'VALERA', 3),
(38, 'YAMBRASBAMBA', 3),
(39, 'NIEVA', 4),
(40, 'EL CENEPA', 4),
(41, 'RIO SANTIAGO', 4),
(42, 'LAMUD', 5),
(43, 'CAMPORREDONDO', 5),
(44, 'COCABAMBA', 5),
(45, 'COLCAMAR', 5),
(46, 'CONILA', 5),
(47, 'INGUILPATA', 5),
(48, 'LONGUITA', 5),
(49, 'LONYA CHICO', 5),
(50, 'LUYA', 5),
(51, 'LUYA VIEJO', 5),
(52, 'MARIA', 5),
(53, 'OCALLI', 5),
(54, 'OCUMAL', 5),
(55, 'PISUQUIA', 5),
(56, 'PROVIDENCIA', 5),
(57, 'SAN CRISTOBAL', 5),
(58, 'SAN FRANCISCO DEL YESO', 5),
(59, 'SAN JERONIMO', 5),
(60, 'SAN JUAN DE LOPECANCHA', 5),
(61, 'SANTA CATALINA', 5),
(62, 'SANTO TOMAS', 5),
(63, 'TINGO', 5),
(64, 'TRITA', 5),
(65, 'SAN NICOLAS', 6),
(66, 'CHIRIMOTO', 6),
(67, 'COCHAMAL', 6),
(68, 'HUAMBO', 6),
(69, 'LIMABAMBA', 6),
(70, 'LONGAR', 6),
(71, 'MARISCAL BENAVIDES', 6),
(72, 'MILPUC', 6),
(73, 'OMIA', 6),
(74, 'SANTA ROSA', 6),
(75, 'TOTORA', 6),
(76, 'VISTA ALEGRE', 6),
(77, 'BAGUA GRANDE', 7),
(78, 'CAJARURO', 7),
(79, 'CUMBA', 7),
(80, 'EL MILAGRO', 7),
(81, 'JAMALCA', 7),
(82, 'LONYA GRANDE', 7),
(83, 'YAMON', 7),
(84, 'HUARAZ', 8),
(85, 'COCHABAMBA', 8),
(86, 'COLCABAMBA', 8),
(87, 'HUANCHAY', 8),
(88, 'INDEPENDENCIA', 8),
(89, 'JANGAS', 8),
(90, 'LA LIBERTAD', 8),
(91, 'OLLEROS', 8),
(92, 'PAMPAS', 8),
(93, 'PARIACOTO', 8),
(94, 'PIRA', 8),
(95, 'TARICA', 8),
(96, 'AIJA', 9),
(97, 'CORIS', 9),
(98, 'HUACLLAN', 9),
(99, 'LA MERCED', 9),
(100, 'SUCCHA', 9),
(101, 'LLAMELLIN', 10),
(102, 'ACZO', 10),
(103, 'CHACCHO', 10),
(104, 'CHINGAS', 10),
(105, 'MIRGAS', 10),
(106, 'SAN JUAN DE RONTOY', 10),
(107, 'CHACAS', 11),
(108, 'ACOCHACA', 11),
(109, 'CHIQUIAN', 12),
(110, 'ABELARDO PARDO LEZAMETA', 12),
(111, 'ANTONIO RAYMONDI', 12),
(112, 'AQUIA', 12),
(113, 'CAJACAY', 12),
(114, 'CANIS', 12),
(115, 'COLQUIOC', 12),
(116, 'HUALLANCA', 12),
(117, 'HUASTA', 12),
(118, 'HUAYLLACAYAN', 12),
(119, 'LA PRIMAVERA', 12),
(120, 'MANGAS', 12),
(121, 'PACLLON', 12),
(122, 'SAN MIGUEL DE CORPANQUI', 12),
(123, 'TICLLOS', 12),
(124, 'CARHUAZ', 13),
(125, 'ACOPAMPA', 13),
(126, 'AMASHCA', 13),
(127, 'ANTA', 13),
(128, 'ATAQUERO', 13),
(129, 'MARCARA', 13),
(130, 'PARIAHUANCA', 13),
(131, 'SAN MIGUEL DE ACO', 13),
(132, 'SHILLA', 13),
(133, 'TINCO', 13),
(134, 'YUNGAR', 13),
(135, 'SAN LUIS', 14),
(136, 'SAN NICOLAS', 14),
(137, 'YAUYA', 14),
(138, 'CASMA', 15),
(139, 'BUENA VISTA ALTA', 15),
(140, 'COMANDANTE NOEL', 15),
(141, 'YAUTAN', 15),
(142, 'CORONGO', 16),
(143, 'ACO', 16),
(144, 'BAMBAS', 16),
(145, 'CUSCA', 16),
(146, 'LA PAMPA', 16),
(147, 'YANAC', 16),
(148, 'YUPAN', 16),
(149, 'HUARI', 17),
(150, 'ANRA', 17),
(151, 'CAJAY', 17),
(152, 'CHAVIN DE HUANTAR', 17),
(153, 'HUACACHI', 17),
(154, 'HUACCHIS', 17),
(155, 'HUACHIS', 17),
(156, 'HUANTAR', 17),
(157, 'MASIN', 17),
(158, 'PAUCAS', 17),
(159, 'PONTO', 17),
(160, 'RAHUAPAMPA', 17),
(161, 'RAPAYAN', 17),
(162, 'SAN MARCOS', 17),
(163, 'SAN PEDRO DE CHANA', 17),
(164, 'UCO', 17),
(165, 'HUARMEY', 18),
(166, 'COCHAPETI', 18),
(167, 'CULEBRAS', 18),
(168, 'HUAYAN', 18),
(169, 'MALVAS', 18),
(170, 'CARAZ', 26),
(171, 'HUALLANCA', 26),
(172, 'HUATA', 26),
(173, 'HUAYLAS', 26),
(174, 'MATO', 26),
(175, 'PAMPAROMAS', 26),
(176, 'PUEBLO LIBRE', 26),
(177, 'SANTA CRUZ', 26),
(178, 'SANTO TORIBIO', 26),
(179, 'YURACMARCA', 26),
(180, 'PISCOBAMBA', 27),
(181, 'CASCA', 27),
(182, 'ELEAZAR GUZMAN BARRON', 27),
(183, 'FIDEL OLIVAS ESCUDERO', 27),
(184, 'LLAMA', 27),
(185, 'LLUMPA', 27),
(186, 'LUCMA', 27),
(187, 'MUSGA', 27),
(188, 'OCROS', 21),
(189, 'ACAS', 21),
(190, 'CAJAMARQUILLA', 21),
(191, 'CARHUAPAMPA', 21),
(192, 'COCHAS', 21),
(193, 'CONGAS', 21),
(194, 'LLIPA', 21),
(195, 'SAN CRISTOBAL DE RAJAN', 21),
(196, 'SAN PEDRO', 21),
(197, 'SANTIAGO DE CHILCAS', 21),
(198, 'CABANA', 22),
(199, 'BOLOGNESI', 22),
(200, 'CONCHUCOS', 22),
(201, 'HUACASCHUQUE', 22),
(202, 'HUANDOVAL', 22),
(203, 'LACABAMBA', 22),
(204, 'LLAPO', 22),
(205, 'PALLASCA', 22),
(206, 'PAMPAS', 22),
(207, 'SANTA ROSA', 22),
(208, 'TAUCA', 22),
(209, 'POMABAMBA', 23),
(210, 'HUAYLLAN', 23),
(211, 'PAROBAMBA', 23),
(212, 'QUINUABAMBA', 23),
(213, 'RECUAY', 24),
(214, 'CATAC', 24),
(215, 'COTAPARACO', 24),
(216, 'HUAYLLAPAMPA', 24),
(217, 'LLACLLIN', 24),
(218, 'MARCA', 24),
(219, 'PAMPAS CHICO', 24),
(220, 'PARARIN', 24),
(221, 'TAPACOCHA', 24),
(222, 'TICAPAMPA', 24),
(223, 'CHIMBOTE', 25),
(224, 'CACERES DEL PERU', 25),
(225, 'COISHCO', 25),
(226, 'MACATE', 25),
(227, 'MORO', 25),
(228, 'NEPE&Ntilde;A', 25),
(229, 'SAMANCO', 25),
(230, 'SANTA', 25),
(231, 'NUEVO CHIMBOTE', 25),
(232, 'SIHUAS', 26),
(233, 'ACOBAMBA', 26),
(234, 'ALFONSO UGARTE', 26),
(235, 'CASHAPAMPA', 26),
(236, 'CHINGALPO', 26),
(237, 'HUAYLLABAMBA', 26),
(238, 'QUICHES', 26),
(239, 'RAGASH', 26),
(240, 'SAN JUAN', 26),
(241, 'SICSIBAMBA', 26),
(242, 'YUNGAY', 27),
(243, 'CASCAPARA', 27),
(244, 'MANCOS', 27),
(245, 'MATACOTO', 27),
(246, 'QUILLO', 27),
(247, 'RANRAHIRCA', 27),
(248, 'SHUPLUY', 27),
(249, 'YANAMA', 27),
(250, 'ABANCAY', 28),
(251, 'CHACOCHE', 28),
(252, 'CIRCA', 28),
(253, 'CURAHUASI', 28),
(254, 'HUANIPACA', 28),
(255, 'LAMBRAMA', 28),
(256, 'PICHIRHUA', 28),
(257, 'SAN PEDRO DE CACHORA', 28),
(258, 'TAMBURCO', 28),
(259, 'ANDAHUAYLAS', 29),
(260, 'ANDARAPA', 29),
(261, 'CHIARA', 29),
(262, 'HUANCARAMA', 29),
(263, 'HUANCARAY', 29),
(264, 'HUAYANA', 29),
(265, 'KISHUARA', 29),
(266, 'PACOBAMBA', 29),
(267, 'PACUCHA', 29),
(268, 'PAMPACHIRI', 29),
(269, 'POMACOCHA', 29),
(270, 'SAN ANTONIO DE CACHI', 29),
(271, 'SAN JERONIMO', 29),
(272, 'SAN MIGUEL DE CHACCRAMPA', 29),
(273, 'SANTA MARIA DE CHICMO', 29),
(274, 'TALAVERA', 29),
(275, 'TUMAY HUARACA', 29),
(276, 'TURPO', 29),
(277, 'KAQUIABAMBA', 29),
(278, 'ANTABAMBA', 30),
(279, 'EL ORO', 30),
(280, 'HUAQUIRCA', 30),
(281, 'JUAN ESPINOZA MEDRANO', 30),
(282, 'OROPESA', 30),
(283, 'PACHACONAS', 30),
(284, 'SABAINO', 30),
(285, 'CHALHUANCA', 31),
(286, 'CAPAYA', 31),
(287, 'CARAYBAMBA', 31),
(288, 'CHAPIMARCA', 31),
(289, 'COLCABAMBA', 31),
(290, 'COTARUSE', 31),
(291, 'HUAYLLO', 31),
(292, 'JUSTO APU SAHUARAURA', 31),
(293, 'LUCRE', 31),
(294, 'POCOHUANCA', 31),
(295, 'SAN JUAN DE CHAC&Ntilde;A', 31),
(296, 'SA&Ntilde;AYCA', 31),
(297, 'SORAYA', 31),
(298, 'TAPAIRIHUA', 31),
(299, 'TINTAY', 31),
(300, 'TORAYA', 31),
(301, 'YANACA', 31),
(302, 'TAMBOBAMBA', 32),
(303, 'COTABAMBAS', 32),
(304, 'COYLLURQUI', 32),
(305, 'HAQUIRA', 32),
(306, 'MARA', 32),
(307, 'CHALLHUAHUACHO', 32),
(308, 'CHINCHEROS', 33),
(309, 'ANCO-HUALLO', 33),
(310, 'COCHARCAS', 33),
(311, 'HUACCANA', 33),
(312, 'OCOBAMBA', 33),
(313, 'ONGOY', 33),
(314, 'URANMARCA', 33),
(315, 'RANRACANCHA', 33),
(316, 'CHUQUIBAMBILLA', 34),
(317, 'CURPAHUASI', 34),
(318, 'GAMARRA', 34),
(319, 'HUAYLLATI', 34),
(320, 'MAMARA', 34),
(321, 'MICAELA BASTIDAS', 34),
(322, 'PATAYPAMPA', 34),
(323, 'PROGRESO', 34),
(324, 'SAN ANTONIO', 34),
(325, 'SANTA ROSA', 34),
(326, 'TURPAY', 34),
(327, 'VILCABAMBA', 34),
(328, 'VIRUNDO', 34),
(329, 'CURASCO', 34),
(330, 'AREQUIPA', 35),
(331, 'ALTO SELVA ALEGRE', 35),
(332, 'CAYMA', 35),
(333, 'CERRO COLORADO', 35),
(334, 'CHARACATO', 35),
(335, 'CHIGUATA', 35),
(336, 'JACOBO HUNTER', 35),
(337, 'LA JOYA', 35),
(338, 'MARIANO MELGAR', 35),
(339, 'MIRAFLORES', 35),
(340, 'MOLLEBAYA', 35),
(341, 'PAUCARPATA', 35),
(342, 'POCSI', 35),
(343, 'POLOBAYA', 35),
(344, 'QUEQUE&Ntilde;A', 35),
(345, 'SABANDIA', 35),
(346, 'SACHACA', 35),
(347, 'SAN JUAN DE SIGUAS', 35),
(348, 'SAN JUAN DE TARUCANI', 35),
(349, 'SANTA ISABEL DE SIGUAS', 35),
(350, 'SANTA RITA DE SIGUAS', 35),
(351, 'SOCABAYA', 35),
(352, 'TIABAYA', 35),
(353, 'UCHUMAYO', 35),
(354, 'VITOR', 35),
(355, 'YANAHUARA', 35),
(356, 'YARABAMBA', 35),
(357, 'YURA', 35),
(358, 'JOSE LUIS BUSTAMANTE Y RIVERO', 35),
(359, 'CAMANA', 36),
(360, 'JOSE MARIA QUIMPER', 36),
(361, 'MARIANO NICOLAS VALCARCEL', 36),
(362, 'MARISCAL CACERES', 36),
(363, 'NICOLAS DE PIEROLA', 36),
(364, 'OCO&Ntilde;A', 36),
(365, 'QUILCA', 36),
(366, 'SAMUEL PASTOR', 36),
(367, 'CARAVELI', 37),
(368, 'ACARI', 37),
(369, 'ATICO', 37),
(370, 'ATIQUIPA', 37),
(371, 'BELLA UNION', 37),
(372, 'CAHUACHO', 37),
(373, 'CHALA', 37),
(374, 'CHAPARRA', 37),
(375, 'HUANUHUANU', 37),
(376, 'JAQUI', 37),
(377, 'LOMAS', 37),
(378, 'QUICACHA', 37),
(379, 'YAUCA', 37),
(380, 'APLAO', 38),
(381, 'ANDAGUA', 38),
(382, 'AYO', 38),
(383, 'CHACHAS', 38),
(384, 'CHILCAYMARCA', 38),
(385, 'CHOCO', 38),
(386, 'HUANCARQUI', 38),
(387, 'MACHAGUAY', 38),
(388, 'ORCOPAMPA', 38),
(389, 'PAMPACOLCA', 38),
(390, 'TIPAN', 38),
(391, 'U&Ntilde;ON', 38),
(392, 'URACA', 38),
(393, 'VIRACO', 38),
(394, 'CHIVAY', 39),
(395, 'ACHOMA', 39),
(396, 'CABANACONDE', 39),
(397, 'CALLALLI', 39),
(398, 'CAYLLOMA', 39),
(399, 'COPORAQUE', 39),
(400, 'HUAMBO', 39),
(401, 'HUANCA', 39),
(402, 'ICHUPAMPA', 39),
(403, 'LARI', 39),
(404, 'LLUTA', 39),
(405, 'MACA', 39),
(406, 'MADRIGAL', 39),
(407, 'SAN ANTONIO DE CHUCA', 39),
(408, 'SIBAYO', 39),
(409, 'TAPAY', 39),
(410, 'TISCO', 39),
(411, 'TUTI', 39),
(412, 'YANQUE', 39),
(413, 'MAJES', 39),
(414, 'CHUQUIBAMBA', 40),
(415, 'ANDARAY', 40),
(416, 'CAYARANI', 40),
(417, 'CHICHAS', 40),
(418, 'IRAY', 40),
(419, 'RIO GRANDE', 40),
(420, 'SALAMANCA', 40),
(421, 'YANAQUIHUA', 40),
(422, 'MOLLENDO', 41),
(423, 'COCACHACRA', 41),
(424, 'DEAN VALDIVIA', 41),
(425, 'ISLAY', 41),
(426, 'MEJIA', 41),
(427, 'PUNTA DE BOMBON', 41),
(428, 'COTAHUASI', 42),
(429, 'ALCA', 42),
(430, 'CHARCANA', 42),
(431, 'HUAYNACOTAS', 42),
(432, 'PAMPAMARCA', 42),
(433, 'PUYCA', 42),
(434, 'QUECHUALLA', 42),
(435, 'SAYLA', 42),
(436, 'TAURIA', 42),
(437, 'TOMEPAMPA', 42),
(438, 'TORO', 42),
(439, 'AYACUCHO', 43),
(440, 'ACOCRO', 43),
(441, 'ACOS VINCHOS', 43),
(442, 'CARMEN ALTO', 43),
(443, 'CHIARA', 43),
(444, 'OCROS', 43),
(445, 'PACAYCASA', 43),
(446, 'QUINUA', 43),
(447, 'SAN JOSE DE TICLLAS', 43),
(448, 'SAN JUAN BAUTISTA', 43),
(449, 'SANTIAGO DE PISCHA', 43),
(450, 'SOCOS', 43),
(451, 'TAMBILLO', 43),
(452, 'VINCHOS', 43),
(453, 'JESUS NAZARENO', 43),
(454, 'CANGALLO', 44),
(455, 'CHUSCHI', 44),
(456, 'LOS MOROCHUCOS', 44),
(457, 'MARIA PARADO DE BELLIDO', 44),
(458, 'PARAS', 44),
(459, 'TOTOS', 44),
(460, 'SANCOS', 45),
(461, 'CARAPO', 45),
(462, 'SACSAMARCA', 45),
(463, 'SANTIAGO DE LUCANAMARCA', 45),
(464, 'HUANTA', 46),
(465, 'AYAHUANCO', 46),
(466, 'HUAMANGUILLA', 46),
(467, 'IGUAIN', 46),
(468, 'LURICOCHA', 46),
(469, 'SANTILLANA', 46),
(470, 'SIVIA', 46),
(471, 'LLOCHEGUA', 46),
(472, 'SAN MIGUEL', 47),
(473, 'ANCO', 47),
(474, 'AYNA', 47),
(475, 'CHILCAS', 47),
(476, 'CHUNGUI', 47),
(477, 'LUIS CARRANZA', 47),
(478, 'SANTA ROSA', 47),
(479, 'TAMBO', 47),
(480, 'PUQUIO', 48),
(481, 'AUCARA', 48),
(482, 'CABANA', 48),
(483, 'CARMEN SALCEDO', 48),
(484, 'CHAVI&Ntilde;A', 48),
(485, 'CHIPAO', 48),
(486, 'HUAC-HUAS', 48),
(487, 'LARAMATE', 48),
(488, 'LEONCIO PRADO', 48),
(489, 'LLAUTA', 48),
(490, 'LUCANAS', 48),
(491, 'OCA&Ntilde;A', 48),
(492, 'OTOCA', 48),
(493, 'SAISA', 48),
(494, 'SAN CRISTOBAL', 48),
(495, 'SAN JUAN', 48),
(496, 'SAN PEDRO', 48),
(497, 'SAN PEDRO DE PALCO', 48),
(498, 'SANCOS', 48),
(499, 'SANTA ANA DE HUAYCAHUACHO', 48),
(500, 'SANTA LUCIA', 48),
(501, 'CORACORA', 49),
(502, 'CHUMPI', 49),
(503, 'CORONEL CASTA&Ntilde;EDA', 49),
(504, 'PACAPAUSA', 49),
(505, 'PULLO', 49),
(506, 'PUYUSCA', 49),
(507, 'SAN FRANCISCO DE RAVACAYCO', 49),
(508, 'UPAHUACHO', 49),
(509, 'PAUSA', 50),
(510, 'COLTA', 50),
(511, 'CORCULLA', 50),
(512, 'LAMPA', 50),
(513, 'MARCABAMBA', 50),
(514, 'OYOLO', 50),
(515, 'PARARCA', 50),
(516, 'SAN JAVIER DE ALPABAMBA', 50),
(517, 'SAN JOSE DE USHUA', 50),
(518, 'SARA SARA', 50),
(519, 'QUEROBAMBA', 51),
(520, 'BELEN', 51),
(521, 'CHALCOS', 51),
(522, 'CHILCAYOC', 51),
(523, 'HUACA&Ntilde;A', 51),
(524, 'MORCOLLA', 51),
(525, 'PAICO', 51),
(526, 'SAN PEDRO DE LARCAY', 51),
(527, 'SAN SALVADOR DE QUIJE', 51),
(528, 'SANTIAGO DE PAUCARAY', 51),
(529, 'SORAS', 51),
(530, 'HUANCAPI', 52),
(531, 'ALCAMENCA', 52),
(532, 'APONGO', 52),
(533, 'ASQUIPATA', 52),
(534, 'CANARIA', 52),
(535, 'CAYARA', 52),
(536, 'COLCA', 52),
(537, 'HUAMANQUIQUIA', 52),
(538, 'HUANCARAYLLA', 52),
(539, 'HUAYA', 52),
(540, 'SARHUA', 52),
(541, 'VILCANCHOS', 52),
(542, 'VILCAS HUAMAN', 53),
(543, 'ACCOMARCA', 53),
(544, 'CARHUANCA', 53),
(545, 'CONCEPCION', 53),
(546, 'HUAMBALPA', 53),
(547, 'INDEPENDENCIA', 53),
(548, 'SAURAMA', 53),
(549, 'VISCHONGO', 53),
(550, 'CAJAMARCA', 54),
(551, 'CAJAMARCA', 54),
(552, 'ASUNCION', 54),
(553, 'CHETILLA', 54),
(554, 'COSPAN', 54),
(555, 'ENCA&Ntilde;ADA', 54),
(556, 'JESUS', 54),
(557, 'LLACANORA', 54),
(558, 'LOS BA&Ntilde;OS DEL INCA', 54),
(559, 'MAGDALENA', 54),
(560, 'MATARA', 54),
(561, 'NAMORA', 54),
(562, 'SAN JUAN', 54),
(563, 'CAJABAMBA', 55),
(564, 'CACHACHI', 55),
(565, 'CONDEBAMBA', 55),
(566, 'SITACOCHA', 55),
(567, 'CELENDIN', 56),
(568, 'CHUMUCH', 56),
(569, 'CORTEGANA', 56),
(570, 'HUASMIN', 56),
(571, 'JORGE CHAVEZ', 56),
(572, 'JOSE GALVEZ', 56),
(573, 'MIGUEL IGLESIAS', 56),
(574, 'OXAMARCA', 56),
(575, 'SOROCHUCO', 56),
(576, 'SUCRE', 56),
(577, 'UTCO', 56),
(578, 'LA LIBERTAD DE PALLAN', 56),
(579, 'CHOTA', 57),
(580, 'ANGUIA', 57),
(581, 'CHADIN', 57),
(582, 'CHIGUIRIP', 57),
(583, 'CHIMBAN', 57),
(584, 'CHOROPAMPA', 57),
(585, 'COCHABAMBA', 57),
(586, 'CONCHAN', 57),
(587, 'HUAMBOS', 57),
(588, 'LAJAS', 57),
(589, 'LLAMA', 57),
(590, 'MIRACOSTA', 57),
(591, 'PACCHA', 57),
(592, 'PION', 57),
(593, 'QUEROCOTO', 57),
(594, 'SAN JUAN DE LICUPIS', 57),
(595, 'TACABAMBA', 57),
(596, 'TOCMOCHE', 57),
(597, 'CHALAMARCA', 57),
(598, 'CONTUMAZA', 58),
(599, 'CHILETE', 58),
(600, 'CUPISNIQUE', 58),
(601, 'GUZMANGO', 58),
(602, 'SAN BENITO', 58),
(603, 'SANTA CRUZ DE TOLED', 58),
(604, 'TANTARICA', 58),
(605, 'YONAN', 58),
(606, 'CUTERVO', 59),
(607, 'CALLAYUC', 59),
(608, 'CHOROS', 59),
(609, 'CUJILLO', 59),
(610, 'LA RAMADA', 59),
(611, 'PIMPINGOS', 59),
(612, 'QUEROCOTILLO', 59),
(613, 'SAN ANDRES DE CUTERVO', 59),
(614, 'SAN JUAN DE CUTERVO', 59),
(615, 'SAN LUIS DE LUCMA', 59),
(616, 'SANTA CRUZ', 59),
(617, 'SANTO DOMINGO DE LA CAPILLA', 59),
(618, 'SANTO TOMAS', 59),
(619, 'SOCOTA', 59),
(620, 'TORIBIO CASANOVA', 59),
(621, 'BAMBAMARCA', 60),
(622, 'CHUGUR', 60),
(623, 'HUALGAYOC', 60),
(624, 'JAEN', 61),
(625, 'BELLAVISTA', 61),
(626, 'CHONTALI', 61),
(627, 'COLASAY', 61),
(628, 'HUABAL', 61),
(629, 'LAS PIRIAS', 61),
(630, 'POMAHUACA', 61),
(631, 'PUCARA', 61),
(632, 'SALLIQUE', 61),
(633, 'SAN FELIPE', 61),
(634, 'SAN JOSE DEL ALTO', 61),
(635, 'SANTA ROSA', 61),
(636, 'SAN IGNACIO', 62),
(637, 'CHIRINOS', 62),
(638, 'HUARANGO', 62),
(639, 'LA COIPA', 62),
(640, 'NAMBALLE', 62),
(641, 'SAN JOSE DE LOURDES', 62),
(642, 'TABACONAS', 62),
(643, 'PEDRO GALVEZ', 63),
(644, 'CHANCAY', 63),
(645, 'EDUARDO VILLANUEVA', 63),
(646, 'GREGORIO PITA', 63),
(647, 'ICHOCAN', 63),
(648, 'JOSE MANUEL QUIROZ', 63),
(649, 'JOSE SABOGAL', 63),
(650, 'SAN MIGUEL', 64),
(651, 'SAN MIGUEL', 64),
(652, 'BOLIVAR', 64),
(653, 'CALQUIS', 64),
(654, 'CATILLUC', 64),
(655, 'EL PRADO', 64),
(656, 'LA FLORIDA', 64),
(657, 'LLAPA', 64),
(658, 'NANCHOC', 64),
(659, 'NIEPOS', 64),
(660, 'SAN GREGORIO', 64),
(661, 'SAN SILVESTRE DE COCHAN', 64),
(662, 'TONGOD', 64),
(663, 'UNION AGUA BLANCA', 64),
(664, 'SAN PABLO', 65),
(665, 'SAN BERNARDINO', 65),
(666, 'SAN LUIS', 65),
(667, 'TUMBADEN', 65),
(668, 'SANTA CRUZ', 66),
(669, 'ANDABAMBA', 66),
(670, 'CATACHE', 66),
(671, 'CHANCAYBA&Ntilde;OS', 66),
(672, 'LA ESPERANZA', 66),
(673, 'NINABAMBA', 66),
(674, 'PULAN', 66),
(675, 'SAUCEPAMPA', 66),
(676, 'SEXI', 66),
(677, 'UTICYACU', 66),
(678, 'YAUYUCAN', 66),
(679, 'CALLAO', 67),
(680, 'BELLAVISTA', 67),
(681, 'CARMEN DE LA LEGUA REYNOSO', 67),
(682, 'LA PERLA', 67),
(683, 'LA PUNTA', 67),
(684, 'VENTANILLA', 67),
(685, 'CUSCO', 67),
(686, 'CCORCA', 67),
(687, 'POROY', 67),
(688, 'SAN JERONIMO', 67),
(689, 'SAN SEBASTIAN', 67),
(690, 'SANTIAGO', 67),
(691, 'SAYLLA', 67),
(692, 'WANCHAQ', 67),
(693, 'ACOMAYO', 68),
(694, 'ACOPIA', 68),
(695, 'ACOS', 68),
(696, 'MOSOC LLACTA', 68),
(697, 'POMACANCHI', 68),
(698, 'RONDOCAN', 68),
(699, 'SANGARARA', 68),
(700, 'ANTA', 69),
(701, 'ANCAHUASI', 69),
(702, 'CACHIMAYO', 69),
(703, 'CHINCHAYPUJIO', 69),
(704, 'HUAROCONDO', 69),
(705, 'LIMATAMBO', 69),
(706, 'MOLLEPATA', 69),
(707, 'PUCYURA', 69),
(708, 'ZURITE', 69),
(709, 'CALCA', 70),
(710, 'COYA', 70),
(711, 'LAMAY', 70),
(712, 'LARES', 70),
(713, 'PISAC', 70),
(714, 'SAN SALVADOR', 70),
(715, 'TARAY', 70),
(716, 'YANATILE', 70),
(717, 'YANAOCA', 71),
(718, 'CHECCA', 71),
(719, 'KUNTURKANKI', 71),
(720, 'LANGUI', 71),
(721, 'LAYO', 71),
(722, 'PAMPAMARCA', 71),
(723, 'QUEHUE', 71),
(724, 'TUPAC AMARU', 71),
(725, 'SICUANI', 72),
(726, 'CHECACUPE', 72),
(727, 'COMBAPATA', 72),
(728, 'MARANGANI', 72),
(729, 'PITUMARCA', 72),
(730, 'SAN PABLO', 72),
(731, 'SAN PEDRO', 72),
(732, 'TINTA', 72),
(733, 'SANTO TOMAS', 73),
(734, 'CAPACMARCA', 73),
(735, 'CHAMACA', 73),
(736, 'COLQUEMARCA', 73),
(737, 'LIVITACA', 73),
(738, 'LLUSCO', 73),
(739, 'QUI&Ntilde;OTA', 73),
(740, 'VELILLE', 73),
(741, 'ESPINAR', 74),
(742, 'CONDOROMA', 74),
(743, 'COPORAQUE', 74),
(744, 'OCORURO', 74),
(745, 'PALLPATA', 74),
(746, 'PICHIGUA', 74),
(747, 'SUYCKUTAMBO', 74),
(748, 'ALTO PICHIGUA', 74),
(749, 'SANTA ANA', 75),
(750, 'ECHARATE', 75),
(751, 'HUAYOPATA', 75),
(752, 'MARANURA', 75),
(753, 'OCOBAMBA', 75),
(754, 'QUELLOUNO', 75),
(755, 'KIMBIRI', 75),
(756, 'SANTA TERESA', 75),
(757, 'VILCABAMBA', 75),
(758, 'PICHARI', 75),
(759, 'PARURO', 76),
(760, 'ACCHA', 76),
(761, 'CCAPI', 76),
(762, 'COLCHA', 76),
(763, 'HUANOQUITE', 76),
(764, 'OMACHA', 76),
(765, 'PACCARITAMBO', 76),
(766, 'PILLPINTO', 76),
(767, 'YAURISQUE', 76),
(768, 'PAUCARTAMBO', 77),
(769, 'CAICAY', 77),
(770, 'CHALLABAMBA', 77),
(771, 'COLQUEPATA', 77),
(772, 'HUANCARANI', 77),
(773, 'KOS&Ntilde;IPATA', 77),
(774, 'URCOS', 78),
(775, 'ANDAHUAYLILLAS', 78),
(776, 'CAMANTI', 78),
(777, 'CCARHUAYO', 78),
(778, 'CCATCA', 78),
(779, 'CUSIPATA', 78),
(780, 'HUARO', 78),
(781, 'LUCRE', 78),
(782, 'MARCAPATA', 78),
(783, 'OCONGATE', 78),
(784, 'OROPESA', 78),
(785, 'QUIQUIJANA', 78),
(786, 'URUBAMBA', 79),
(787, 'CHINCHERO', 79),
(788, 'HUAYLLABAMBA', 79),
(789, 'MACHUPICCHU', 79),
(790, 'MARAS', 79),
(791, 'OLLANTAYTAMBO', 79),
(792, 'YUCAY', 79),
(793, 'HUANCAVELICA', 80),
(794, 'ACOBAMBILLA', 80),
(795, 'ACORIA', 80),
(796, 'CONAYCA', 80),
(797, 'CUENCA', 80),
(798, 'HUACHOCOLPA', 80),
(799, 'HUAYLLAHUARA', 80),
(800, 'IZCUCHACA', 80),
(801, 'LARIA', 80),
(802, 'MANTA', 80),
(803, 'MARISCAL CACERES', 80),
(804, 'MOYA', 80),
(805, 'NUEVO OCCORO', 80),
(806, 'PALCA', 80),
(807, 'PILCHACA', 80),
(808, 'VILCA', 80),
(809, 'YAULI', 80),
(810, 'ASCENSION', 80),
(811, 'HUANDO', 80),
(812, 'ACOBAMBA', 81),
(813, 'ANDABAMBA', 81),
(814, 'ANTA', 81),
(815, 'CAJA', 81),
(816, 'MARCAS', 81),
(817, 'PAUCARA', 81),
(818, 'POMACOCHA', 81),
(819, 'ROSARIO', 81),
(820, 'LIRCAY', 82),
(821, 'ANCHONGA', 82),
(822, 'CALLANMARCA', 82),
(823, 'CCOCHACCASA', 82),
(824, 'CHINCHO', 82),
(825, 'CONGALLA', 82),
(826, 'HUANCA-HUANCA', 82),
(827, 'HUAYLLAY GRANDE', 82),
(828, 'JULCAMARCA', 82),
(829, 'SAN ANTONIO DE ANTAPARCO', 82),
(830, 'SANTO TOMAS DE PATA', 82),
(831, 'SECCLLA', 82),
(832, 'CASTROVIRREYNA', 83),
(833, 'ARMA', 83),
(834, 'AURAHUA', 83),
(835, 'CAPILLAS', 83),
(836, 'CHUPAMARCA', 83),
(837, 'COCAS', 83),
(838, 'HUACHOS', 83),
(839, 'HUAMATAMBO', 83),
(840, 'MOLLEPAMPA', 83),
(841, 'SAN JUAN', 83),
(842, 'SANTA ANA', 83),
(843, 'TANTARA', 83),
(844, 'TICRAPO', 83),
(845, 'CHURCAMPA', 84),
(846, 'ANCO', 84),
(847, 'CHINCHIHUASI', 84),
(848, 'EL CARMEN', 84),
(849, 'LA MERCED', 84),
(850, 'LOCROJA', 84),
(851, 'PAUCARBAMBA', 84),
(852, 'SAN MIGUEL DE MAYOCC', 84),
(853, 'SAN PEDRO DE CORIS', 84),
(854, 'PACHAMARCA', 84),
(855, 'HUAYTARA', 85),
(856, 'AYAVI', 85),
(857, 'CORDOVA', 85),
(858, 'HUAYACUNDO ARMA', 85),
(859, 'LARAMARCA', 85),
(860, 'OCOYO', 85),
(861, 'PILPICHACA', 85),
(862, 'QUERCO', 85),
(863, 'QUITO-ARMA', 85),
(864, 'SAN ANTONIO DE CUSICANCHA', 85),
(865, 'SAN FRANCISCO DE SANGAYAICO', 85),
(866, 'SAN ISIDRO', 85),
(867, 'SANTIAGO DE CHOCORVOS', 85),
(868, 'SANTIAGO DE QUIRAHUARA', 85),
(869, 'SANTO DOMINGO DE CAPILLAS', 85),
(870, 'TAMBO', 85),
(871, 'PAMPAS', 86),
(872, 'ACOSTAMBO', 86),
(873, 'ACRAQUIA', 86),
(874, 'AHUAYCHA', 86),
(875, 'COLCABAMBA', 86),
(876, 'DANIEL HERNANDEZ', 86),
(877, 'HUACHOCOLPA', 86),
(878, 'HUARIBAMBA', 86),
(879, '&Ntilde;AHUIMPUQUIO', 86),
(880, 'PAZOS', 86),
(881, 'QUISHUAR', 86),
(882, 'SALCABAMBA', 86),
(883, 'SALCAHUASI', 86),
(884, 'SAN MARCOS DE ROCCHAC', 86),
(885, 'SURCUBAMBA', 86),
(886, 'TINTAY PUNCU', 86),
(887, 'HUANUCO', 87),
(888, 'AMARILIS', 87),
(889, 'CHINCHAO', 87),
(890, 'CHURUBAMBA', 87),
(891, 'MARGOS', 87),
(892, 'QUISQUI', 87),
(893, 'SAN FRANCISCO DE CAYRAN', 87),
(894, 'SAN PEDRO DE CHAULAN', 87),
(895, 'SANTA MARIA DEL VALLE', 87),
(896, 'YARUMAYO', 87),
(897, 'PILLCO MARCA', 87),
(898, 'AMBO', 88),
(899, 'CAYNA', 88),
(900, 'COLPAS', 88),
(901, 'CONCHAMARCA', 88),
(902, 'HUACAR', 88),
(903, 'SAN FRANCISCO', 88),
(904, 'SAN RAFAEL', 88),
(905, 'TOMAY KICHWA', 88),
(906, 'LA UNION', 89),
(907, 'CHUQUIS', 89),
(908, 'MARIAS', 89),
(909, 'PACHAS', 89),
(910, 'QUIVILLA', 89),
(911, 'RIPAN', 89),
(912, 'SHUNQUI', 89),
(913, 'SILLAPATA', 89),
(914, 'YANAS', 89),
(915, 'HUACAYBAMBA', 90),
(916, 'CANCHABAMBA', 90),
(917, 'COCHABAMBA', 90),
(918, 'PINRA', 90),
(919, 'LLATA', 91),
(920, 'ARANCAY', 91),
(921, 'CHAVIN DE PARIARCA', 91),
(922, 'JACAS GRANDE', 91),
(923, 'JIRCAN', 91),
(924, 'MIRAFLORES', 91),
(925, 'MONZON', 91),
(926, 'PUNCHAO', 91),
(927, 'PU&Ntilde;OS', 91),
(928, 'SINGA', 91),
(929, 'TANTAMAYO', 91),
(930, 'RUPA-RUPA', 92),
(931, 'DANIEL ALOMIA ROBLES', 92),
(932, 'HERMILIO VALDIZAN', 92),
(933, 'JOSE CRESPO Y CASTILLO', 92),
(934, 'LUYANDO', 92),
(935, 'MARIANO DAMASO BERAUN', 92),
(936, 'HUACRACHUCO', 93),
(937, 'CHOLON', 93),
(938, 'SAN BUENAVENTURA', 93),
(939, 'PANAO', 94),
(940, 'CHAGLLA', 94),
(941, 'MOLINO', 94),
(942, 'UMARI', 94),
(943, 'PUERTO INCA', 95),
(944, 'CODO DEL POZUZO', 95),
(945, 'HONORIA', 95),
(946, 'TOURNAVISTA', 95),
(947, 'YUYAPICHIS', 95),
(948, 'JESUS', 96),
(949, 'BA&Ntilde;OS', 96),
(950, 'JIVIA', 96),
(951, 'QUEROPALCA', 96),
(952, 'RONDOS', 96),
(953, 'SAN FRANCISCO DE ASIS', 96),
(954, 'SAN MIGUEL DE CAURI', 96),
(955, 'CHAVINILLO', 97),
(956, 'CAHUAC', 97),
(957, 'CHACABAMBA', 97),
(958, 'APARICIO POMARES', 97),
(959, 'JACAS CHICO', 97),
(960, 'OBAS', 97),
(961, 'PAMPAMARCA', 97),
(962, 'CHORAS', 97),
(963, 'ICA', 98),
(964, 'LA TINGUI&Ntilde;A', 98),
(965, 'LOS AQUIJES', 98),
(966, 'OCUCAJE', 98),
(967, 'PACHACUTEC', 98),
(968, 'PARCONA', 98),
(969, 'PUEBLO NUEVO', 98),
(970, 'SALAS', 98),
(971, 'SAN JOSE DE LOS MOLINOS', 98),
(972, 'SAN JUAN BAUTISTA', 98),
(973, 'SANTIAGO', 98),
(974, 'SUBTANJALLA', 98),
(975, 'TATE', 98),
(976, 'YAUCA DEL ROSARIO', 98),
(977, 'CHINCHA ALTA', 99),
(978, 'ALTO LARAN', 99),
(979, 'CHAVIN', 99),
(980, 'CHINCHA BAJA', 99),
(981, 'EL CARMEN', 99),
(982, 'GROCIO PRADO', 99),
(983, 'PUEBLO NUEVO', 99),
(984, 'SAN JUAN DE YANAC', 99),
(985, 'SAN PEDRO DE HUACARPANA', 99),
(986, 'SUNAMPE', 99),
(987, 'TAMBO DE MORA', 99),
(988, 'NAZCA', 100),
(989, 'CHANGUILLO', 100),
(990, 'EL INGENIO', 100),
(991, 'MARCONA', 100),
(992, 'VISTA ALEGRE', 100),
(993, 'PALPA', 101),
(994, 'LLIPATA', 101),
(995, 'RIO GRANDE', 101),
(996, 'SANTA CRUZ', 101),
(997, 'TIBILLO', 101),
(998, 'PISCO', 102),
(999, 'HUANCANO', 102),
(1000, 'HUMAY', 102),
(1001, 'INDEPENDENCIA', 102),
(1002, 'PARACAS', 102),
(1003, 'SAN ANDRES', 102),
(1004, 'SAN CLEMENTE', 102),
(1005, 'TUPAC AMARU INCA', 102),
(1006, 'HUANCAYO', 103),
(1007, 'CARHUACALLANGA', 103),
(1008, 'CHACAPAMPA', 103),
(1009, 'CHICCHE', 103),
(1010, 'CHILCA', 103),
(1011, 'CHONGOS ALTO', 103),
(1012, 'CHUPURO', 103),
(1013, 'COLCA', 103),
(1014, 'CULLHUAS', 103),
(1015, 'EL TAMBO', 103),
(1016, 'HUACRAPUQUIO', 103),
(1017, 'HUALHUAS', 103),
(1018, 'HUANCAN', 103),
(1019, 'HUASICANCHA', 103),
(1020, 'HUAYUCACHI', 103),
(1021, 'INGENIO', 103),
(1022, 'PARIAHUANCA', 103),
(1023, 'PILCOMAYO', 103),
(1024, 'PUCARA', 103),
(1025, 'QUICHUAY', 103),
(1026, 'QUILCAS', 103),
(1027, 'SAN AGUSTIN', 103),
(1028, 'SAN JERONIMO DE TUNAN', 103),
(1029, 'SA&Ntilde;O', 103),
(1030, 'SAPALLANGA', 103),
(1031, 'SICAYA', 103),
(1032, 'SANTO DOMINGO DE ACOBAMBA', 103),
(1033, 'VIQUES', 103),
(1034, 'CONCEPCION', 104),
(1035, 'ACO', 104),
(1036, 'ANDAMARCA', 104),
(1037, 'CHAMBARA', 104),
(1038, 'COCHAS', 104),
(1039, 'COMAS', 104),
(1040, 'HEROINAS TOLEDO', 104),
(1041, 'MANZANARES', 104),
(1042, 'MARISCAL CASTILLA', 104),
(1043, 'MATAHUASI', 104),
(1044, 'MITO', 104),
(1045, 'NUEVE DE JULIO', 104),
(1046, 'ORCOTUNA', 104),
(1047, 'SAN JOSE DE QUERO', 104),
(1048, 'SANTA ROSA DE OCOPA', 104),
(1049, 'CHANCHAMAYO', 105),
(1050, 'PERENE', 105),
(1051, 'PICHANAQUI', 105),
(1052, 'SAN LUIS DE SHUARO', 105),
(1053, 'SAN RAMON', 105),
(1054, 'VITOC', 105),
(1055, 'JAUJA', 106),
(1056, 'ACOLLA', 106),
(1057, 'APATA', 106),
(1058, 'ATAURA', 106),
(1059, 'CANCHAYLLO', 106),
(1060, 'CURICACA', 106),
(1061, 'EL MANTARO', 106),
(1062, 'HUAMALI', 106),
(1063, 'HUARIPAMPA', 106),
(1064, 'HUERTAS', 106),
(1065, 'JANJAILLO', 106),
(1066, 'JULCAN', 106),
(1067, 'LEONOR ORDO&Ntilde;EZ', 106),
(1068, 'LLOCLLAPAMPA', 106),
(1069, 'MARCO', 106),
(1070, 'MASMA', 106),
(1071, 'MASMA CHICCHE', 106),
(1072, 'MOLINOS', 106),
(1073, 'MONOBAMBA', 106),
(1074, 'MUQUI', 106),
(1075, 'MUQUIYAUYO', 106),
(1076, 'PACA', 106),
(1077, 'PACCHA', 106),
(1078, 'PANCAN', 106),
(1079, 'PARCO', 106),
(1080, 'POMACANCHA', 106),
(1081, 'RICRAN', 106),
(1082, 'SAN LORENZO', 106),
(1083, 'SAN PEDRO DE CHUNAN', 106),
(1084, 'SAUSA', 106),
(1085, 'SINCOS', 106),
(1086, 'TUNAN MARCA', 106),
(1087, 'YAULI', 106),
(1088, 'YAUYOS', 106),
(1089, 'JUNIN', 107),
(1090, 'CARHUAMAYO', 107),
(1091, 'ONDORES', 107),
(1092, 'ULCUMAYO', 107),
(1093, 'SATIPO', 108),
(1094, 'COVIRIALI', 108),
(1095, 'LLAYLLA', 108),
(1096, 'MAZAMARI', 108),
(1097, 'PAMPA HERMOSA', 108),
(1098, 'PANGOA', 108),
(1099, 'RIO NEGRO', 108),
(1100, 'RIO TAMBO', 108),
(1101, 'TARMA', 109),
(1102, 'ACOBAMBA', 109),
(1103, 'HUARICOLCA', 109),
(1104, 'HUASAHUASI', 109),
(1105, 'LA UNION', 109),
(1106, 'PALCA', 109),
(1107, 'PALCAMAYO', 109),
(1108, 'SAN PEDRO DE CAJAS', 109),
(1109, 'TAPO', 109),
(1110, 'LA OROYA', 110),
(1111, 'CHACAPALPA', 110),
(1112, 'HUAY-HUAY', 110),
(1113, 'MARCAPOMACOCHA', 110),
(1114, 'MOROCOCHA', 110),
(1115, 'PACCHA', 110),
(1116, 'SANTA BARBARA DE CARHUACAYAN', 110),
(1117, 'SANTA ROSA DE SACCO', 110),
(1118, 'SUITUCANCHA', 110),
(1119, 'YAULI', 110),
(1120, 'CHUPACA', 111),
(1121, 'AHUAC', 111),
(1122, 'CHONGOS BAJO', 111),
(1123, 'HUACHAC', 111),
(1124, 'HUAMANCACA CHICO', 111),
(1125, 'SAN JUAN DE ISCOS', 111),
(1126, 'SAN JUAN DE JARPA', 111),
(1127, 'TRES DE DICIEMBRE', 111),
(1128, 'YANACANCHA', 111),
(1129, 'TRUJILLO', 112),
(1130, 'EL PORVENIR', 112),
(1131, 'FLORENCIA DE MORA', 112),
(1132, 'HUANCHACO', 112),
(1133, 'LA ESPERANZA', 112),
(1134, 'LAREDO', 112),
(1135, 'MOCHE', 112),
(1136, 'POROTO', 112),
(1137, 'SALAVERRY', 112),
(1138, 'SIMBAL', 112),
(1139, 'VICTOR LARCO HERRERA', 112),
(1140, 'ASCOPE', 113),
(1141, 'CHICAMA', 113),
(1142, 'CHOCOPE', 113),
(1143, 'MAGDALENA DE CAO', 113),
(1144, 'PAIJAN', 113),
(1145, 'RAZURI', 113),
(1146, 'SANTIAGO DE CAO', 113),
(1147, 'CASA GRANDE', 113),
(1148, 'BOLIVAR', 114),
(1149, 'BAMBAMARCA', 114),
(1150, 'CONDORMARCA', 114),
(1151, 'LONGOTEA', 114),
(1152, 'UCHUMARCA', 114),
(1153, 'UCUNCHA', 114),
(1154, 'CHEPEN', 115),
(1155, 'PACANGA', 115),
(1156, 'PUEBLO NUEVO', 115),
(1157, 'JULCAN', 116),
(1158, 'CALAMARCA', 116),
(1159, 'CARABAMBA', 116),
(1160, 'HUASO', 116),
(1161, 'OTUZCO', 117),
(1162, 'AGALLPAMPA', 117),
(1163, 'CHARAT', 117),
(1164, 'HUARANCHAL', 117),
(1165, 'LA CUESTA', 117),
(1166, 'MACHE', 117),
(1167, 'PARANDAY', 117),
(1168, 'SALPO', 117),
(1169, 'SINSICAP', 117),
(1170, 'USQUIL', 117),
(1171, 'SAN PEDRO DE LLOC', 118),
(1172, 'GUADALUPE', 118),
(1173, 'JEQUETEPEQUE', 118),
(1174, 'PACASMAYO', 118),
(1175, 'SAN JOSE', 118),
(1176, 'TAYABAMBA', 119),
(1177, 'BULDIBUYO', 119),
(1178, 'CHILLIA', 119),
(1179, 'HUANCASPATA', 119),
(1180, 'HUAYLILLAS', 119),
(1181, 'HUAYO', 119),
(1182, 'ONGON', 119),
(1183, 'PARCOY', 119),
(1184, 'PATAZ', 119),
(1185, 'PIAS', 119),
(1186, 'SANTIAGO DE CHALLAS', 119),
(1187, 'TAURIJA', 119),
(1188, 'URPAY', 119),
(1189, 'HUAMACHUCO', 120),
(1190, 'CHUGAY', 120),
(1191, 'COCHORCO', 120),
(1192, 'CURGOS', 120),
(1193, 'MARCABAL', 120),
(1194, 'SANAGORAN', 120),
(1195, 'SARIN', 120),
(1196, 'SARTIMBAMBA', 120),
(1197, 'SANTIAGO DE CHUCO', 121),
(1198, 'ANGASMARCA', 121),
(1199, 'CACHICADAN', 121),
(1200, 'MOLLEBAMBA', 121),
(1201, 'MOLLEPATA', 121),
(1202, 'QUIRUVILCA', 121),
(1203, 'SANTA CRUZ DE CHUCA', 121),
(1204, 'SITABAMBA', 121),
(1205, 'GRAN CHIMU', 122),
(1206, 'CASCAS', 122),
(1207, 'LUCMA', 122),
(1208, 'MARMOT', 122),
(1209, 'SAYAPULLO', 122),
(1210, 'VIRU', 123),
(1211, 'CHAO', 123),
(1212, 'GUADALUPITO', 123),
(1213, 'CHICLAYO', 124),
(1214, 'CHONGOYAPE', 124),
(1215, 'ETEN', 124),
(1216, 'ETEN PUERTO', 124),
(1217, 'JOSE LEONARDO ORTIZ', 124),
(1218, 'LA VICTORIA', 124),
(1219, 'LAGUNAS', 124),
(1220, 'MONSEFU', 124),
(1221, 'NUEVA ARICA', 124),
(1222, 'OYOTUN', 124),
(1223, 'PICSI', 124),
(1224, 'PIMENTEL', 124),
(1225, 'REQUE', 124),
(1226, 'SANTA ROSA', 124),
(1227, 'SA&Ntilde;A', 124),
(1228, 'CAYALTI', 124),
(1229, 'PATAPO', 124),
(1230, 'POMALCA', 124),
(1231, 'PUCALA', 124),
(1232, 'TUMAN', 124),
(1233, 'FERRE&Ntilde;AFE', 125),
(1234, 'CA&Ntilde;ARIS', 125),
(1235, 'INCAHUASI', 125),
(1236, 'MANUEL ANTONIO MESONES MURO', 125),
(1237, 'PITIPO', 125),
(1238, 'PUEBLO NUEVO', 125),
(1239, 'LAMBAYEQUE', 126),
(1240, 'CHOCHOPE', 126),
(1241, 'ILLIMO', 126),
(1242, 'JAYANCA', 126),
(1243, 'MOCHUMI', 126),
(1244, 'MORROPE', 126),
(1245, 'MOTUPE', 126),
(1246, 'OLMOS', 126),
(1247, 'PACORA', 126),
(1248, 'SALAS', 126),
(1249, 'SAN JOSE', 126),
(1250, 'TUCUME', 126),
(1251, 'LIMA', 127),
(1252, 'ANCON', 127),
(1253, 'ATE', 127),
(1254, 'BARRANCO', 127),
(1255, 'BRE&Ntilde;A', 127),
(1256, 'CARABAYLLO', 127),
(1257, 'CHACLACAYO', 127),
(1258, 'CHORRILLOS', 127),
(1259, 'CIENEGUILLA', 127),
(1260, 'COMAS', 127),
(1261, 'EL AGUSTINO', 127),
(1262, 'INDEPENDENCIA', 127),
(1263, 'JESUS MARIA', 127),
(1264, 'LA MOLINA', 127),
(1265, 'LA VICTORIA', 127),
(1266, 'LINCE', 127),
(1267, 'LOS OLIVOS', 127),
(1268, 'LURIGANCHO', 127),
(1269, 'LURIN', 127),
(1270, 'MAGDALENA DEL MAR', 127),
(1271, 'MAGDALENA VIEJA', 127),
(1272, 'MIRAFLORES', 127),
(1273, 'PACHACAMAC', 127),
(1274, 'PUCUSANA', 127),
(1275, 'PUENTE PIEDRA', 127),
(1276, 'PUNTA HERMOSA', 127),
(1277, 'PUNTA NEGRA', 127),
(1278, 'RIMAC', 127),
(1279, 'SAN BARTOLO', 127),
(1280, 'SAN BORJA', 127),
(1281, 'SAN ISIDRO', 127),
(1282, 'SAN JUAN DE LURIGANCHO', 127),
(1283, 'SAN JUAN DE MIRAFLORES', 127),
(1284, 'SAN LUIS', 127),
(1285, 'SAN MARTIN DE PORRES', 127),
(1286, 'SAN MIGUEL', 127),
(1287, 'SANTA ANITA', 127),
(1288, 'SANTA MARIA DEL MAR', 127),
(1289, 'SANTA ROSA', 127),
(1290, 'SANTIAGO DE SURCO', 127),
(1291, 'SURQUILLO', 127),
(1292, 'VILLA EL SALVADOR', 127),
(1293, 'VILLA MARIA DEL TRIUNFO', 127),
(1294, 'BARRANCA', 128),
(1295, 'PARAMONGA', 128),
(1296, 'PATIVILCA', 128),
(1297, 'SUPE', 128),
(1298, 'SUPE PUERTO', 128),
(1299, 'CAJATAMBO', 129),
(1300, 'COPA', 129),
(1301, 'GORGOR', 129),
(1302, 'HUANCAPON', 129),
(1303, 'MANAS', 129),
(1304, 'CANTA', 130),
(1305, 'ARAHUAY', 130),
(1306, 'HUAMANTANGA', 130),
(1307, 'HUAROS', 130),
(1308, 'LACHAQUI', 130),
(1309, 'SAN BUENAVENTURA', 130),
(1310, 'SANTA ROSA DE QUIVES', 130),
(1311, 'SAN VICENTE DE CA&Ntilde;ETE', 131),
(1312, 'ASIA', 131),
(1313, 'CALANGO', 131),
(1314, 'CERRO AZUL', 131),
(1315, 'CHILCA', 131),
(1316, 'COAYLLO', 131),
(1317, 'IMPERIAL', 131),
(1318, 'LUNAHUANA', 131),
(1319, 'MALA', 131),
(1320, 'NUEVO IMPERIAL', 131),
(1321, 'PACARAN', 131),
(1322, 'QUILMANA', 131),
(1323, 'SAN ANTONIO', 131),
(1324, 'SAN LUIS', 131),
(1325, 'SANTA CRUZ DE FLORES', 131),
(1326, 'ZU&Ntilde;IGA', 131),
(1327, 'HUARAL', 132),
(1328, 'ATAVILLOS ALTO', 132),
(1329, 'ATAVILLOS BAJO', 132),
(1330, 'AUCALLAMA', 132),
(1331, 'CHANCAY', 132),
(1332, 'IHUARI', 132),
(1333, 'LAMPIAN', 132),
(1334, 'PACARAOS', 132),
(1335, 'SAN MIGUEL DE ACOS', 132),
(1336, 'SANTA CRUZ DE ANDAMARCA', 132),
(1337, 'SUMBILCA', 132),
(1338, 'VEINTISIETE DE NOVIEMBRE', 132),
(1339, 'MATUCANA', 133),
(1340, 'ANTIOQUIA', 133),
(1341, 'CALLAHUANCA', 133),
(1342, 'CARAMPOMA', 133),
(1343, 'CHICLA', 133),
(1344, 'CUENCA', 133),
(1345, 'HUACHUPAMPA', 133),
(1346, 'HUANZA', 133),
(1347, 'HUAROCHIRI', 133),
(1348, 'LAHUAYTAMBO', 133),
(1349, 'LANGA', 133),
(1350, 'LARAOS', 133),
(1351, 'MARIATANA', 133),
(1352, 'RICARDO PALMA', 133),
(1353, 'SAN ANDRES DE TUPICOCHA', 133),
(1354, 'SAN ANTONIO', 133),
(1355, 'SAN BARTOLOME', 133),
(1356, 'SAN DAMIAN', 133),
(1357, 'SAN JUAN DE IRIS', 133),
(1358, 'SAN JUAN DE TANTARANCHE', 133),
(1359, 'SAN LORENZO DE QUINTI', 133),
(1360, 'SAN MATEO', 133),
(1361, 'SAN MATEO DE OTAO', 133),
(1362, 'SAN PEDRO DE CASTA', 133),
(1363, 'SAN PEDRO DE HUANCAYRE', 133),
(1364, 'SANGALLAYA', 133),
(1365, 'SANTA CRUZ DE COCACHACRA', 133),
(1366, 'SANTA EULALIA', 133),
(1367, 'SANTIAGO DE ANCHUCAYA', 133),
(1368, 'SANTIAGO DE TUNA', 133),
(1369, 'SANTO DOMINGO DE LOS OLLEROS', 133),
(1370, 'SURCO', 133),
(1371, 'HUACHO', 134),
(1372, 'AMBAR', 134),
(1373, 'CALETA DE CARQUIN', 134),
(1374, 'CHECRAS', 134),
(1375, 'HUALMAY', 134),
(1376, 'HUAURA', 134),
(1377, 'LEONCIO PRADO', 134),
(1378, 'PACCHO', 134),
(1379, 'SANTA LEONOR', 134),
(1380, 'SANTA MARIA', 134),
(1381, 'SAYAN', 134),
(1382, 'VEGUETA', 134),
(1383, 'OYON', 135),
(1384, 'ANDAJES', 135),
(1385, 'CAUJUL', 135),
(1386, 'COCHAMARCA', 135),
(1387, 'NAVAN', 135),
(1388, 'PACHANGARA', 135),
(1389, 'YAUYOS', 136),
(1390, 'ALIS', 136),
(1391, 'AYAUCA', 136),
(1392, 'AYAVIRI', 136),
(1393, 'AZANGARO', 136),
(1394, 'CACRA', 136),
(1395, 'CARANIA', 136),
(1396, 'CATAHUASI', 136),
(1397, 'CHOCOS', 136),
(1398, 'COCHAS', 136),
(1399, 'COLONIA', 136),
(1400, 'HONGOS', 136),
(1401, 'HUAMPARA', 136),
(1402, 'HUANCAYA', 136),
(1403, 'HUANGASCAR', 136),
(1404, 'HUANTAN', 136),
(1405, 'HUA&Ntilde;EC', 136),
(1406, 'LARAOS', 136),
(1407, 'LINCHA', 136),
(1408, 'MADEAN', 136),
(1409, 'MIRAFLORES', 136),
(1410, 'OMAS', 136),
(1411, 'PUTINZA', 136),
(1412, 'QUINCHES', 136),
(1413, 'QUINOCAY', 136),
(1414, 'SAN JOAQUIN', 136),
(1415, 'SAN PEDRO DE PILAS', 136),
(1416, 'TANTA', 136),
(1417, 'TAURIPAMPA', 136),
(1418, 'TOMAS', 136),
(1419, 'TUPE', 136),
(1420, 'VI&Ntilde;AC', 136),
(1421, 'VITIS', 136),
(1422, 'IQUITOS', 137),
(1423, 'ALTO NANAY', 137),
(1424, 'FERNANDO LORES', 137),
(1425, 'INDIANA', 137),
(1426, 'LAS AMAZONAS', 137),
(1427, 'MAZAN', 137),
(1428, 'NAPO', 137),
(1429, 'PUNCHANA', 137),
(1430, 'PUTUMAYO', 137),
(1431, 'TORRES CAUSANA', 137),
(1432, 'BELEN', 137),
(1433, 'SAN JUAN BAUTISTA', 137),
(1434, 'YURIMAGUAS', 138),
(1435, 'BALSAPUERTO', 138),
(1436, 'BARRANCA', 138),
(1437, 'CAHUAPANAS', 138),
(1438, 'JEBEROS', 138),
(1439, 'LAGUNAS', 138),
(1440, 'MANSERICHE', 138),
(1441, 'MORONA', 138),
(1442, 'PASTAZA', 138),
(1443, 'SANTA CRUZ', 138),
(1444, 'TENIENTE CESAR LOPEZ ROJAS', 138),
(1445, 'NAUTA', 139),
(1446, 'PARINARI', 139),
(1447, 'TIGRE', 139),
(1448, 'TROMPETEROS', 139),
(1449, 'URARINAS', 139),
(1450, 'RAMON CASTILLA', 140),
(1451, 'PEBAS', 140),
(1452, 'YAVARI', 140),
(1453, 'SAN PABLO', 140),
(1454, 'REQUENA', 141),
(1455, 'ALTO TAPICHE', 141),
(1456, 'CAPELO', 141),
(1457, 'EMILIO SAN MARTIN', 141),
(1458, 'MAQUIA', 141),
(1459, 'PUINAHUA', 141),
(1460, 'SAQUENA', 141),
(1461, 'SOPLIN', 141),
(1462, 'TAPICHE', 141),
(1463, 'JENARO HERRERA', 141),
(1464, 'YAQUERANA', 141),
(1465, 'CONTAMANA', 142),
(1466, 'INAHUAYA', 142),
(1467, 'PADRE MARQUEZ', 142),
(1468, 'PAMPA HERMOSA', 142),
(1469, 'SARAYACU', 142),
(1470, 'VARGAS GUERRA', 142),
(1471, 'TAMBOPATA', 143),
(1472, 'INAMBARI', 143),
(1473, 'LAS PIEDRAS', 143),
(1474, 'LABERINTO', 143),
(1475, 'MANU', 144),
(1476, 'FITZCARRALD', 144),
(1477, 'MADRE DE DIOS', 144),
(1478, 'HUEPETUHE', 144),
(1479, 'I&Ntilde;APARI', 145),
(1480, 'IBERIA', 145),
(1481, 'TAHUAMANU', 145),
(1482, 'MOQUEGUA', 146),
(1483, 'CARUMAS', 146),
(1484, 'CUCHUMBAYA', 146),
(1485, 'SAMEGUA', 146),
(1486, 'SAN CRISTOBAL', 146),
(1487, 'TORATA', 146),
(1488, 'OMATE', 147),
(1489, 'CHOJATA', 147),
(1490, 'COALAQUE', 147),
(1491, 'ICHU&Ntilde;A', 147),
(1492, 'LA CAPILLA', 147),
(1493, 'LLOQUE', 147),
(1494, 'MATALAQUE', 147),
(1495, 'PUQUINA', 147),
(1496, 'QUINISTAQUILLAS', 147),
(1497, 'UBINAS', 147),
(1498, 'YUNGA', 147),
(1499, 'ILO', 148),
(1500, 'EL ALGARROBAL', 148),
(1501, 'PACOCHA', 148),
(1502, 'CHAUPIMARCA', 149),
(1503, 'HUACHON', 149),
(1504, 'HUARIACA', 149),
(1505, 'HUAYLLAY', 149),
(1506, 'NINACACA', 149),
(1507, 'PALLANCHACRA', 149),
(1508, 'PAUCARTAMBO', 149),
(1509, 'SAN FCO.DE ASIS DE YARUSYACAN', 149),
(1510, 'SIMON BOLIVAR', 149),
(1511, 'TICLACAYAN', 149),
(1512, 'TINYAHUARCO', 149),
(1513, 'VICCO', 149),
(1514, 'YANACANCHA', 149),
(1515, 'YANAHUANCA', 150),
(1516, 'CHACAYAN', 150),
(1517, 'GOYLLARISQUIZGA', 150),
(1518, 'PAUCAR', 150),
(1519, 'SAN PEDRO DE PILLAO', 150),
(1520, 'SANTA ANA DE TUSI', 150),
(1521, 'TAPUC', 150),
(1522, 'VILCABAMBA', 150),
(1523, 'OXAPAMPA', 151),
(1524, 'CHONTABAMBA', 151),
(1525, 'HUANCABAMBA', 151),
(1526, 'PALCAZU', 151),
(1527, 'POZUZO', 151),
(1528, 'PUERTO BERMUDEZ', 151),
(1529, 'VILLA RICA', 151),
(1530, 'PIURA', 152),
(1531, 'CASTILLA', 152),
(1532, 'CATACAOS', 152),
(1533, 'CURA MORI', 152),
(1534, 'EL TALLAN', 152),
(1535, 'LA ARENA', 152),
(1536, 'LA UNION', 152),
(1537, 'LAS LOMAS', 152),
(1538, 'TAMBO GRANDE', 152),
(1539, 'AYABACA', 153),
(1540, 'FRIAS', 153),
(1541, 'JILILI', 153),
(1542, 'LAGUNAS', 153),
(1543, 'MONTERO', 153),
(1544, 'PACAIPAMPA', 153),
(1545, 'PAIMAS', 153),
(1546, 'SAPILLICA', 153),
(1547, 'SICCHEZ', 153),
(1548, 'SUYO', 153),
(1549, 'HUANCABAMBA', 154),
(1550, 'CANCHAQUE', 154),
(1551, 'EL CARMEN DE LA FRONTERA', 154),
(1552, 'HUARMACA', 154),
(1553, 'LALAQUIZ', 154),
(1554, 'SAN MIGUEL DE EL FAIQUE', 154),
(1555, 'SONDOR', 154),
(1556, 'SONDORILLO', 154),
(1557, 'CHULUCANAS', 155),
(1558, 'BUENOS AIRES', 155),
(1559, 'CHALACO', 155),
(1560, 'LA MATANZA', 155),
(1561, 'MORROPON', 155),
(1562, 'SALITRAL', 155),
(1563, 'SAN JUAN DE BIGOTE', 155),
(1564, 'SANTA CATALINA DE MOSSA', 155),
(1565, 'SANTO DOMINGO', 155),
(1566, 'YAMANGO', 155),
(1567, 'PAITA', 156),
(1568, 'AMOTAPE', 156),
(1569, 'ARENAL', 156),
(1570, 'COLAN', 156),
(1571, 'LA HUACA', 156),
(1572, 'TAMARINDO', 156),
(1573, 'VICHAYAL', 156),
(1574, 'SULLANA', 157),
(1575, 'BELLAVISTA', 157),
(1576, 'IGNACIO ESCUDERO', 157),
(1577, 'LANCONES', 157),
(1578, 'MARCAVELICA', 157),
(1579, 'MIGUEL CHECA', 157),
(1580, 'QUERECOTILLO', 157),
(1581, 'SALITRAL', 157),
(1582, 'PARI&Ntilde;AS', 158),
(1583, 'EL ALTO', 158),
(1584, 'LA BREA', 158),
(1585, 'LOBITOS', 158),
(1586, 'LOS ORGANOS', 158),
(1587, 'MANCORA', 158),
(1588, 'SECHURA', 159),
(1589, 'BELLAVISTA DE LA UNION', 159),
(1590, 'BERNAL', 159),
(1591, 'CRISTO NOS VALGA', 159),
(1592, 'VICE', 159),
(1593, 'RINCONADA LLICUAR', 159),
(1594, 'PUNO', 160),
(1595, 'ACORA', 160),
(1596, 'AMANTANI', 160),
(1597, 'ATUNCOLLA', 160),
(1598, 'CAPACHICA', 160),
(1599, 'CHUCUITO', 160),
(1600, 'COATA', 160),
(1601, 'HUATA', 160),
(1602, 'MA&Ntilde;AZO', 160),
(1603, 'PAUCARCOLLA', 160),
(1604, 'PICHACANI', 160),
(1605, 'PLATERIA', 160),
(1606, 'SAN ANTONIO', 160),
(1607, 'TIQUILLACA', 160),
(1608, 'VILQUE', 160),
(1609, 'AZANGARO', 161),
(1610, 'ACHAYA', 161),
(1611, 'ARAPA', 161),
(1612, 'ASILLO', 161),
(1613, 'CAMINACA', 161),
(1614, 'CHUPA', 161),
(1615, 'JOSE DOMINGO CHOQUEHUANCA', 161),
(1616, 'MU&Ntilde;ANI', 161),
(1617, 'POTONI', 161),
(1618, 'SAMAN', 161),
(1619, 'SAN ANTON', 161),
(1620, 'SAN JOSE', 161),
(1621, 'SAN JUAN DE SALINAS', 161),
(1622, 'SANTIAGO DE PUPUJA', 161),
(1623, 'TIRAPATA', 161),
(1624, 'MACUSANI', 162),
(1625, 'AJOYANI', 162),
(1626, 'AYAPATA', 162),
(1627, 'COASA', 162),
(1628, 'CORANI', 162),
(1629, 'CRUCERO', 162),
(1630, 'ITUATA', 162),
(1631, 'OLLACHEA', 162),
(1632, 'SAN GABAN', 162),
(1633, 'USICAYOS', 162),
(1634, 'JULI', 163),
(1635, 'DESAGUADERO', 163),
(1636, 'HUACULLANI', 163),
(1637, 'KELLUYO', 163),
(1638, 'PISACOMA', 163),
(1639, 'POMATA', 163),
(1640, 'ZEPITA', 163),
(1641, 'ILAVE', 164),
(1642, 'CAPAZO', 164),
(1643, 'PILCUYO', 164),
(1644, 'SANTA ROSA', 164),
(1645, 'CONDURIRI', 164),
(1646, 'HUANCANE', 165),
(1647, 'COJATA', 165),
(1648, 'HUATASANI', 165),
(1649, 'INCHUPALLA', 165),
(1650, 'PUSI', 165),
(1651, 'ROSASPATA', 165),
(1652, 'TARACO', 165),
(1653, 'VILQUE CHICO', 165),
(1654, 'LAMPA', 166),
(1655, 'CABANILLA', 166),
(1656, 'CALAPUJA', 166),
(1657, 'NICASIO', 166),
(1658, 'OCUVIRI', 166),
(1659, 'PALCA', 166),
(1660, 'PARATIA', 166),
(1661, 'PUCARA', 166),
(1662, 'SANTA LUCIA', 166),
(1663, 'VILAVILA', 166),
(1664, 'AYAVIRI', 167),
(1665, 'ANTAUTA', 167),
(1666, 'CUPI', 167),
(1667, 'LLALLI', 167),
(1668, 'MACARI', 167),
(1669, 'NU&Ntilde;OA', 167),
(1670, 'ORURILLO', 167),
(1671, 'SANTA ROSA', 167),
(1672, 'UMACHIRI', 167),
(1673, 'MOHO', 168),
(1674, 'CONIMA', 168),
(1675, 'HUAYRAPATA', 168),
(1676, 'TILALI', 168),
(1677, 'PUTINA', 169),
(1678, 'ANANEA', 169),
(1679, 'PEDRO VILCA APAZA', 169),
(1680, 'QUILCAPUNCU', 169),
(1681, 'SINA', 169),
(1682, 'JULIACA', 170),
(1683, 'CABANA', 170),
(1684, 'CABANILLAS', 170),
(1685, 'CARACOTO', 170),
(1686, 'SANDIA', 171),
(1687, 'CUYOCUYO', 171),
(1688, 'LIMBANI', 171),
(1689, 'PATAMBUCO', 171),
(1690, 'PHARA', 171),
(1691, 'QUIACA', 171),
(1692, 'SAN JUAN DEL ORO', 171),
(1693, 'YANAHUAYA', 171),
(1694, 'ALTO INAMBARI', 171),
(1695, 'YUNGUYO', 172),
(1696, 'ANAPIA', 172),
(1697, 'COPANI', 172),
(1698, 'CUTURAPI', 172),
(1699, 'OLLARAYA', 172),
(1700, 'TINICACHI', 172),
(1701, 'UNICACHI', 172),
(1702, 'MOYOBAMBA', 173),
(1703, 'CALZADA', 173),
(1704, 'HABANA', 173),
(1705, 'JEPELACIO', 173),
(1706, 'SORITOR', 173),
(1707, 'YANTALO', 173),
(1708, 'BELLAVISTA', 174),
(1709, 'ALTO BIAVO', 174),
(1710, 'BAJO BIAVO', 174),
(1711, 'HUALLAGA', 174),
(1712, 'SAN PABLO', 174),
(1713, 'SAN RAFAEL', 174),
(1714, 'SAN JOSE DE SISA', 175),
(1715, 'AGUA BLANCA', 175),
(1716, 'SAN MARTIN', 175),
(1717, 'SANTA ROSA', 175),
(1718, 'SHATOJA', 175),
(1719, 'SAPOSOA', 176),
(1720, 'ALTO SAPOSOA', 176),
(1721, 'EL ESLABON', 176),
(1722, 'PISCOYACU', 176),
(1723, 'SACANCHE', 176),
(1724, 'TINGO DE SAPOSOA', 176),
(1725, 'LAMAS', 177),
(1726, 'ALONSO DE ALVARADO', 177),
(1727, 'BARRANQUITA', 177),
(1728, 'CAYNARACHI', 177),
(1729, 'CU&Ntilde;UMBUQUI', 177),
(1730, 'PINTO RECODO', 177),
(1731, 'RUMISAPA', 177),
(1732, 'SAN ROQUE DE CUMBAZA', 177),
(1733, 'SHANAO', 177),
(1734, 'TABALOSOS', 177),
(1735, 'ZAPATERO', 177),
(1736, 'JUANJUI', 178),
(1737, 'CAMPANILLA', 178),
(1738, 'HUICUNGO', 178),
(1739, 'PACHIZA', 178),
(1740, 'PAJARILLO', 178),
(1741, 'PICOTA', 179),
(1742, 'BUENOS AIRES', 179),
(1743, 'CASPISAPA', 179),
(1744, 'PILLUANA', 179),
(1745, 'PUCACACA', 179),
(1746, 'SAN CRISTOBAL', 179),
(1747, 'SAN HILARION', 179),
(1748, 'SHAMBOYACU', 179),
(1749, 'TINGO DE PONASA', 179),
(1750, 'TRES UNIDOS', 179),
(1751, 'RIOJA', 180),
(1752, 'AWAJUN', 180),
(1753, 'ELIAS SOPLIN VARGAS', 180),
(1754, 'NUEVA CAJAMARCA', 180),
(1755, 'PARDO MIGUEL', 180),
(1756, 'POSIC', 180),
(1757, 'SAN FERNANDO', 180),
(1758, 'YORONGOS', 180),
(1759, 'YURACYACU', 180),
(1760, 'TARAPOTO', 181),
(1761, 'ALBERTO LEVEAU', 181),
(1762, 'CACATACHI', 181),
(1763, 'CHAZUTA', 181),
(1764, 'CHIPURANA', 181),
(1765, 'EL PORVENIR', 181),
(1766, 'HUIMBAYOC', 181),
(1767, 'JUAN GUERRA', 181),
(1768, 'LA BANDA DE SHILCAYO', 181),
(1769, 'MORALES', 181),
(1770, 'PAPAPLAYA', 181),
(1771, 'SAN ANTONIO', 181),
(1772, 'SAUCE', 181),
(1773, 'SHAPAJA', 181),
(1774, 'TOCACHE', 182),
(1775, 'NUEVO PROGRESO', 182),
(1776, 'POLVORA', 182),
(1777, 'SHUNTE', 182),
(1778, 'UCHIZA', 182),
(1779, 'TACNA', 183),
(1780, 'ALTO DE LA ALIANZA', 183),
(1781, 'CALANA', 183),
(1782, 'CIUDAD NUEVA', 183),
(1783, 'INCLAN', 183),
(1784, 'PACHIA', 183),
(1785, 'PALCA', 183),
(1786, 'POCOLLAY', 183),
(1787, 'SAMA', 183),
(1788, 'CORONEL GREGORIO ALBARRACIN LANCHIPA', 183),
(1789, 'CANDARAVE', 184),
(1790, 'CAIRANI', 184),
(1791, 'CAMILACA', 184),
(1792, 'CURIBAYA', 184),
(1793, 'HUANUARA', 184),
(1794, 'QUILAHUANI', 184),
(1795, 'LOCUMBA', 185),
(1796, 'ILABAYA', 185),
(1797, 'ITE', 185),
(1798, 'TARATA', 186),
(1799, 'CHUCATAMANI', 186),
(1800, 'ESTIQUE', 186),
(1801, 'ESTIQUE-PAMPA', 186),
(1802, 'SITAJARA', 186),
(1803, 'SUSAPAYA', 186),
(1804, 'TARUCACHI', 186),
(1805, 'TICACO', 186),
(1806, 'TUMBES', 187),
(1807, 'CORRALES', 187),
(1808, 'LA CRUZ', 187),
(1809, 'PAMPAS DE HOSPITAL', 187),
(1810, 'SAN JACINTO', 187),
(1811, 'SAN JUAN DE LA VIRGEN', 187),
(1812, 'ZORRITOS', 188),
(1813, 'CASITAS', 188),
(1814, 'ZARUMILLA', 189),
(1815, 'AGUAS VERDES', 189),
(1816, 'MATAPALO', 189),
(1817, 'PAPAYAL', 189),
(1818, 'CALLERIA', 190),
(1819, 'CAMPOVERDE', 190),
(1820, 'IPARIA', 190),
(1821, 'MASISEA', 190),
(1822, 'YARINACOCHA', 190),
(1823, 'NUEVA REQUENA', 190),
(1824, 'RAYMONDI', 191),
(1825, 'SEPAHUA', 191),
(1826, 'TAHUANIA', 191),
(1827, 'YURUA', 191),
(1828, 'PADRE ABAD', 192),
(1829, 'IRAZOLA', 192),
(1830, 'CURIMANA', 192),
(1831, 'PURUS', 193);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubprovincia`
--

CREATE TABLE `ubprovincia` (
  `id` int(11) NOT NULL,
  `provincia` varchar(250) DEFAULT NULL,
  `ubdepartamento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ubprovincia`
--

INSERT INTO `ubprovincia` (`id`, `provincia`, `ubdepartamento`) VALUES
(1, 'CHACHAPOYAS ', 1),
(2, 'BAGUA', 1),
(3, 'BONGARA', 1),
(4, 'CONDORCANQUI', 1),
(5, 'LUYA', 1),
(6, 'RODRIGUEZ DE MENDOZA', 1),
(7, 'UTCUBAMBA', 1),
(8, 'HUARAZ', 2),
(9, 'AIJA', 2),
(10, 'ANTONIO RAYMONDI', 2),
(11, 'ASUNCION', 2),
(12, 'BOLOGNESI', 2),
(13, 'CARHUAZ', 2),
(14, 'CARLOS FERMIN FITZCARRALD', 2),
(15, 'CASMA', 2),
(16, 'CORONGO', 2),
(17, 'HUARI', 2),
(18, 'HUARMEY', 2),
(19, 'HUAYLAS', 2),
(20, 'MARISCAL LUZURIAGA', 2),
(21, 'OCROS', 2),
(22, 'PALLASCA', 2),
(23, 'POMABAMBA', 2),
(24, 'RECUAY', 2),
(25, 'SANTA', 2),
(26, 'SIHUAS', 2),
(27, 'YUNGAY', 2),
(28, 'ABANCAY', 3),
(29, 'ANDAHUAYLAS', 3),
(30, 'ANTABAMBA', 3),
(31, 'AYMARAES', 3),
(32, 'COTABAMBAS', 3),
(33, 'CHINCHEROS', 3),
(34, 'GRAU', 3),
(35, 'AREQUIPA', 4),
(36, 'CAMANA', 4),
(37, 'CARAVELI', 4),
(38, 'CASTILLA', 4),
(39, 'CAYLLOMA', 4),
(40, 'CONDESUYOS', 4),
(41, 'ISLAY', 4),
(42, 'LA UNION', 4),
(43, 'HUAMANGA', 5),
(44, 'CANGALLO', 5),
(45, 'HUANCA SANCOS', 5),
(46, 'HUANTA', 5),
(47, 'LA MAR', 5),
(48, 'LUCANAS', 5),
(49, 'PARINACOCHAS', 5),
(50, 'PAUCAR DEL SARA SARA', 5),
(51, 'SUCRE', 5),
(52, 'VICTOR FAJARDO', 5),
(53, 'VILCAS HUAMAN', 5),
(54, 'CAJAMARCA', 6),
(55, 'CAJABAMBA', 6),
(56, 'CELENDIN', 6),
(57, 'CHOTA ', 6),
(58, 'CONTUMAZA', 6),
(59, 'CUTERVO', 6),
(60, 'HUALGAYOC', 6),
(61, 'JAEN', 6),
(62, 'SAN IGNACIO', 6),
(63, 'SAN MARCOS', 6),
(64, 'SAN PABLO', 6),
(65, 'SANTA CRUZ', 6),
(66, 'CALLAO', 7),
(67, 'CUSCO', 8),
(68, 'ACOMAYO', 8),
(69, 'ANTA', 8),
(70, 'CALCA', 8),
(71, 'CANAS', 8),
(72, 'CANCHIS', 8),
(73, 'CHUMBIVILCAS', 8),
(74, 'ESPINAR', 8),
(75, 'LA CONVENCION', 8),
(76, 'PARURO', 8),
(77, 'PAUCARTAMBO', 8),
(78, 'QUISPICANCHI', 8),
(79, 'URUBAMBA', 8),
(80, 'HUANCAVELICA', 9),
(81, 'ACOBAMBA', 9),
(82, 'ANGARAES', 9),
(83, 'CASTROVIRREYNA', 9),
(84, 'CHURCAMPA', 9),
(85, 'HUAYTARA', 9),
(86, 'TAYACAJA', 9),
(87, 'HUANUCO', 10),
(88, 'AMBO', 10),
(89, 'DOS DE MAYO', 10),
(90, 'HUACAYBAMBA', 10),
(91, 'HUAMALIES', 10),
(92, 'LEONCIO PRADO', 10),
(93, 'MARA&Ntilde;ON', 10),
(94, 'PACHITEA', 10),
(95, 'PUERTO INCA', 10),
(96, 'LAURICOCHA', 10),
(97, 'YAROWILCA', 10),
(98, 'ICA', 11),
(99, 'CHINCHA', 11),
(100, 'NAZCA', 11),
(101, 'PALPA', 11),
(102, 'PISCO', 11),
(103, 'HUANCAYO', 12),
(104, 'CONCEPCION', 12),
(105, 'CHANCHAMAYO', 12),
(106, 'JAUJA', 12),
(107, 'JUNIN', 12),
(108, 'SATIPO', 12),
(109, 'TARMA', 12),
(110, 'YAULI', 12),
(111, 'CHUPACA', 12),
(112, 'TRUJILLO', 13),
(113, 'ASCOPE', 13),
(114, 'BOLIVAR', 13),
(115, 'CHEPEN', 13),
(116, 'JULCAN', 13),
(117, 'OTUZCO', 13),
(118, 'PACASMAYO', 13),
(119, 'PATAZ', 13),
(120, 'SANCHEZ CARRION', 13),
(121, 'SANTIAGO DE CHUCO', 13),
(122, 'GRAN CHIMU', 13),
(123, 'VIRU', 13),
(124, 'CHICLAYO', 14),
(125, 'FERRE&Ntilde;AFE', 14),
(126, 'LAMBAYEQUE', 14),
(127, 'LIMA', 15),
(128, 'BARRANCA', 15),
(129, 'CAJATAMBO', 15),
(130, 'CANTA', 15),
(131, 'CA&Ntilde;ETE', 15),
(132, 'HUARAL', 15),
(133, 'HUAROCHIRI', 15),
(134, 'HUAURA', 15),
(135, 'OYON', 15),
(136, 'YAUYOS', 15),
(137, 'MAYNAS', 16),
(138, 'ALTO AMAZONAS', 16),
(139, 'LORETO', 16),
(140, 'MARISCAL RAMON CASTILLA', 16),
(141, 'REQUENA', 16),
(142, 'UCAYALI', 16),
(143, 'TAMBOPATA', 17),
(144, 'MANU', 17),
(145, 'TAHUAMANU', 17),
(146, 'MARISCAL NIETO', 18),
(147, 'GENERAL SANCHEZ CERRO', 18),
(148, 'ILO', 18),
(149, 'PASCO', 19),
(150, 'DANIEL ALCIDES CARRION', 19),
(151, 'OXAPAMPA', 19),
(152, 'PIURA', 20),
(153, 'AYABACA', 20),
(154, 'HUANCABAMBA', 20),
(155, 'MORROPON', 20),
(156, 'PAITA', 20),
(157, 'SULLANA', 20),
(158, 'TALARA', 20),
(159, 'SECHURA', 20),
(160, 'PUNO', 21),
(161, 'AZANGARO', 21),
(162, 'CARABAYA', 21),
(163, 'CHUCUITO', 21),
(164, 'EL COLLAO', 21),
(165, 'HUANCANE', 21),
(166, 'LAMPA', 21),
(167, 'MELGAR', 21),
(168, 'MOHO', 21),
(169, 'SAN ANTONIO DE PUTINA', 21),
(170, 'SAN ROMAN', 21),
(171, 'SANDIA', 21),
(172, 'YUNGUYO', 21),
(173, 'MOYOBAMBA', 22),
(174, 'BELLAVISTA', 22),
(175, 'EL DORADO', 22),
(176, 'HUALLAGA', 22),
(177, 'LAMAS', 22),
(178, 'MARISCAL CACERES', 22),
(179, 'PICOTA', 22),
(180, 'RIOJA', 22),
(181, 'SAN MARTIN', 22),
(182, 'TOCACHE', 22),
(183, 'TACNA', 23),
(184, 'CANDARAVE', 23),
(185, 'JORGE BASADRE', 23),
(186, 'TARATA', 23),
(187, 'TUMBES', 24),
(188, 'CONTRALMIRANTE VILLAR', 24),
(189, 'ZARUMILLA', 24),
(190, 'CORONEL PORTILLO', 25),
(191, 'ATALAYA', 25),
(192, 'PADRE ABAD', 25),
(193, 'PURUS', 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_didacticas`
--

CREATE TABLE `unidades_didacticas` (
  `id` int(11) NOT NULL,
  `nombre_unidad` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creditos` int(11) NOT NULL CHECK (`creditos` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(200) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `estuempleado` int(11) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `tipo`, `estuempleado`, `token`, `estado`, `nivel`) VALUES
(1, 'admin', '$2y$10$8asrZfSaluo8qKPoMaGdcuEeDucF9ue21hcD820LPLW36q/6gtYMm', 2, 1, NULL, NULL, NULL),
(2, 'admin_efsrt', '$2y$10$E/FzKyiK9Rwq67r8KPVhzegIV9MMh9IKBaw2C9wAtjFYQj6Vl5aNi', 2, 1, 'a46e3e5bb37fc89a12532e3baf8c2d7c6c3c9e325e96ab8f61c977b445a1e061', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alerta_ia`
--
ALTER TABLE `alerta_ia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documento` (`documento`),
  ADD KEY `convenio` (`convenio`),
  ADD KEY `nivel` (`nivel`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `practicas` (`practicas`);

--
-- Indices de la tabla `asistencia_cap`
--
ALTER TABLE `asistencia_cap`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convenio` (`convenio`);

--
-- Indices de la tabla `auditoria_cap`
--
ALTER TABLE `auditoria_cap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante` (`estudiante`),
  ADD KEY `resoluciones` (`resoluciones`),
  ADD KEY `registrado_por` (`registrado_por`);

--
-- Indices de la tabla `cache_sunat`
--
ALTER TABLE `cache_sunat`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comunicacion`
--
ALTER TABLE `comunicacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convenio` (`convenio`);

--
-- Indices de la tabla `condicion_academica`
--
ALTER TABLE `condicion_academica`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `condicion_laboral`
--
ALTER TABLE `condicion_laboral`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `convenio`
--
ALTER TABLE `convenio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instituto` (`instituto`),
  ADD KEY `empresa` (`empresa`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `estado` (`estado`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_curso` (`tipo_curso`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convenio` (`convenio`),
  ADD KEY `reunion` (`reunion`),
  ADD KEY `tipo` (`tipo`);

--
-- Indices de la tabla `efsrt_configuracion`
--
ALTER TABLE `efsrt_configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `efsrt_documentos`
--
ALTER TABLE `efsrt_documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `practica_id` (`practica_id`),
  ADD KEY `generado_por` (`generado_por`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prog_estudios` (`prog_estudios`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_convenio`
--
ALTER TABLE `estado_convenio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_renovacion`
--
ALTER TABLE `estado_renovacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ubdistrito` (`ubdistrito`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `practicas` (`practicas`);

--
-- Indices de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `practicas` (`practicas`);

--
-- Indices de la tabla `firma`
--
ALTER TABLE `firma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documento` (`documento`),
  ADD KEY `representante` (`representante`);

--
-- Indices de la tabla `historial_descuentos`
--
ALTER TABLE `historial_descuentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beneficiario_id` (`beneficiario_id`),
  ADD KEY `aplicado_por` (`aplicado_por`);

--
-- Indices de la tabla `historial_laboral`
--
ALTER TABLE `historial_laboral`
  ADD PRIMARY KEY (`id`),
  ADD KEY `situacion` (`situacion`);

--
-- Indices de la tabla `historial_solicitudes`
--
ALTER TABLE `historial_solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud_id` (`solicitud_id`),
  ADD KEY `empleado` (`empleado`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asistencia_cap` (`asistencia_cap`),
  ADD KEY `curso` (`curso`);

--
-- Indices de la tabla `instituto`
--
ALTER TABLE `instituto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `invitado_reunion`
--
ALTER TABLE `invitado_reunion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reunion` (`reunion`);

--
-- Indices de la tabla `matricula`
--
ALTER TABLE `matricula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante` (`estudiante`),
  ADD KEY `prog_estudios` (`prog_estudios`);

--
-- Indices de la tabla `medio_consecucion`
--
ALTER TABLE `medio_consecucion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nivel_alerta`
--
ALTER TABLE `nivel_alerta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convenio` (`convenio`),
  ADD KEY `comunicacion` (`comunicacion`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante` (`estudiante`),
  ADD KEY `solicitudes` (`solicitudes`),
  ADD KEY `tipo_pago` (`tipo_pago`),
  ADD KEY `registrado_por` (`registrado_por`);

--
-- Indices de la tabla `pago_cap`
--
ALTER TABLE `pago_cap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inscripcion` (`inscripcion`);

--
-- Indices de la tabla `participante_reunion`
--
ALTER TABLE `participante_reunion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reunion` (`reunion`);

--
-- Indices de la tabla `practicas`
--
ALTER TABLE `practicas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante` (`estudiante`),
  ADD KEY `empleado` (`empleado`),
  ADD KEY `empresa` (`empresa`),
  ADD KEY `fk_practicas_docente` (`docente_supervisor`);

--
-- Indices de la tabla `prog_estudios`
--
ALTER TABLE `prog_estudios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recordatorio`
--
ALTER TABLE `recordatorio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notificacion` (`notificacion`),
  ADD KEY `tipo` (`tipo`);

--
-- Indices de la tabla `renovacion`
--
ALTER TABLE `renovacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convenio` (`convenio`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `estado` (`estado`);

--
-- Indices de la tabla `representante`
--
ALTER TABLE `representante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instituto` (`instituto`),
  ADD KEY `empresa` (`empresa`);

--
-- Indices de la tabla `resoluciones`
--
ALTER TABLE `resoluciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_resolucion` (`numero_resolucion`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `reunion`
--
ALTER TABLE `reunion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convenio` (`convenio`);

--
-- Indices de la tabla `sector`
--
ALTER TABLE `sector`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante` (`estudiante`),
  ADD KEY `tipo` (`tipo`);

--
-- Indices de la tabla `situacion_laboral`
--
ALTER TABLE `situacion_laboral`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante` (`estudiante`),
  ADD KEY `empresa` (`empresa`),
  ADD KEY `condicion_laboral` (`condicion_laboral`);

--
-- Indices de la tabla `situacion_medio`
--
ALTER TABLE `situacion_medio`
  ADD PRIMARY KEY (`situacion`,`medio`),
  ADD KEY `medio` (`medio`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante` (`estudiante`),
  ADD KEY `resoluciones` (`resoluciones`),
  ADD KEY `empleado` (`empleado`);

--
-- Indices de la tabla `tipo_convenio`
--
ALTER TABLE `tipo_convenio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_curso`
--
ALTER TABLE `tipo_curso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipo_recordatorio`
--
ALTER TABLE `tipo_recordatorio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_renovacion`
--
ALTER TABLE `tipo_renovacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_seguimiento`
--
ALTER TABLE `tipo_seguimiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ubdepartamento`
--
ALTER TABLE `ubdepartamento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ubdistrito`
--
ALTER TABLE `ubdistrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ubprovincia` (`ubprovincia`);

--
-- Indices de la tabla `ubprovincia`
--
ALTER TABLE `ubprovincia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ubdepartamento` (`ubdepartamento`);

--
-- Indices de la tabla `unidades_didacticas`
--
ALTER TABLE `unidades_didacticas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alerta_ia`
--
ALTER TABLE `alerta_ia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia_cap`
--
ALTER TABLE `asistencia_cap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auditoria_cap`
--
ALTER TABLE `auditoria_cap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cache_sunat`
--
ALTER TABLE `cache_sunat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comunicacion`
--
ALTER TABLE `comunicacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `condicion_academica`
--
ALTER TABLE `condicion_academica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `condicion_laboral`
--
ALTER TABLE `condicion_laboral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `convenio`
--
ALTER TABLE `convenio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `efsrt_configuracion`
--
ALTER TABLE `efsrt_configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `efsrt_documentos`
--
ALTER TABLE `efsrt_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estado_convenio`
--
ALTER TABLE `estado_convenio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_renovacion`
--
ALTER TABLE `estado_renovacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1253;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `firma`
--
ALTER TABLE `firma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_descuentos`
--
ALTER TABLE `historial_descuentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_laboral`
--
ALTER TABLE `historial_laboral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_solicitudes`
--
ALTER TABLE `historial_solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `instituto`
--
ALTER TABLE `instituto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `invitado_reunion`
--
ALTER TABLE `invitado_reunion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `matricula`
--
ALTER TABLE `matricula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1253;

--
-- AUTO_INCREMENT de la tabla `medio_consecucion`
--
ALTER TABLE `medio_consecucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nivel_alerta`
--
ALTER TABLE `nivel_alerta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago_cap`
--
ALTER TABLE `pago_cap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `participante_reunion`
--
ALTER TABLE `participante_reunion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `practicas`
--
ALTER TABLE `practicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `prog_estudios`
--
ALTER TABLE `prog_estudios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `recordatorio`
--
ALTER TABLE `recordatorio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `renovacion`
--
ALTER TABLE `renovacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `representante`
--
ALTER TABLE `representante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resoluciones`
--
ALTER TABLE `resoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reunion`
--
ALTER TABLE `reunion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sector`
--
ALTER TABLE `sector`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `situacion_laboral`
--
ALTER TABLE `situacion_laboral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_convenio`
--
ALTER TABLE `tipo_convenio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_curso`
--
ALTER TABLE `tipo_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_recordatorio`
--
ALTER TABLE `tipo_recordatorio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_renovacion`
--
ALTER TABLE `tipo_renovacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_seguimiento`
--
ALTER TABLE `tipo_seguimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubdepartamento`
--
ALTER TABLE `ubdepartamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `ubdistrito`
--
ALTER TABLE `ubdistrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1832;

--
-- AUTO_INCREMENT de la tabla `ubprovincia`
--
ALTER TABLE `ubprovincia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT de la tabla `unidades_didacticas`
--
ALTER TABLE `unidades_didacticas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alerta_ia`
--
ALTER TABLE `alerta_ia`
  ADD CONSTRAINT `alerta_ia_ibfk_1` FOREIGN KEY (`documento`) REFERENCES `documento` (`id`),
  ADD CONSTRAINT `alerta_ia_ibfk_2` FOREIGN KEY (`convenio`) REFERENCES `convenio` (`id`),
  ADD CONSTRAINT `alerta_ia_ibfk_3` FOREIGN KEY (`nivel`) REFERENCES `nivel_alerta` (`id`);

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`practicas`) REFERENCES `practicas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`convenio`) REFERENCES `convenio` (`id`);

--
-- Filtros para la tabla `auditoria_cap`
--
ALTER TABLE `auditoria_cap`
  ADD CONSTRAINT `auditoria_cap_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  ADD CONSTRAINT `beneficiarios_ibfk_1` FOREIGN KEY (`estudiante`) REFERENCES `estudiante` (`id`),
  ADD CONSTRAINT `beneficiarios_ibfk_2` FOREIGN KEY (`resoluciones`) REFERENCES `resoluciones` (`id`),
  ADD CONSTRAINT `beneficiarios_ibfk_3` FOREIGN KEY (`registrado_por`) REFERENCES `empleado` (`id`);

--
-- Filtros para la tabla `comunicacion`
--
ALTER TABLE `comunicacion`
  ADD CONSTRAINT `comunicacion_ibfk_1` FOREIGN KEY (`convenio`) REFERENCES `convenio` (`id`);

--
-- Filtros para la tabla `convenio`
--
ALTER TABLE `convenio`
  ADD CONSTRAINT `convenio_ibfk_1` FOREIGN KEY (`instituto`) REFERENCES `instituto` (`id`),
  ADD CONSTRAINT `convenio_ibfk_2` FOREIGN KEY (`empresa`) REFERENCES `empresa` (`id`),
  ADD CONSTRAINT `convenio_ibfk_3` FOREIGN KEY (`tipo`) REFERENCES `tipo_convenio` (`id`),
  ADD CONSTRAINT `convenio_ibfk_4` FOREIGN KEY (`estado`) REFERENCES `estado_convenio` (`id`);

--
-- Filtros para la tabla `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`tipo_curso`) REFERENCES `tipo_curso` (`id`),
  ADD CONSTRAINT `curso_ibfk_2` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`convenio`) REFERENCES `convenio` (`id`),
  ADD CONSTRAINT `documento_ibfk_2` FOREIGN KEY (`reunion`) REFERENCES `reunion` (`id`),
  ADD CONSTRAINT `documento_ibfk_3` FOREIGN KEY (`tipo`) REFERENCES `tipo_documento` (`id`);

--
-- Filtros para la tabla `efsrt_documentos`
--
ALTER TABLE `efsrt_documentos`
  ADD CONSTRAINT `efsrt_documentos_ibfk_1` FOREIGN KEY (`practica_id`) REFERENCES `practicas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `efsrt_documentos_ibfk_2` FOREIGN KEY (`generado_por`) REFERENCES `empleado` (`id`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`prog_estudios`) REFERENCES `prog_estudios` (`id`);

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`ubdistrito`) REFERENCES `ubdistrito` (`id`);

--
-- Filtros para la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`practicas`) REFERENCES `practicas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD CONSTRAINT `evidencias_ibfk_1` FOREIGN KEY (`practicas`) REFERENCES `practicas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `firma`
--
ALTER TABLE `firma`
  ADD CONSTRAINT `firma_ibfk_1` FOREIGN KEY (`documento`) REFERENCES `documento` (`id`),
  ADD CONSTRAINT `firma_ibfk_2` FOREIGN KEY (`representante`) REFERENCES `representante` (`id`);

--
-- Filtros para la tabla `historial_descuentos`
--
ALTER TABLE `historial_descuentos`
  ADD CONSTRAINT `historial_descuentos_ibfk_1` FOREIGN KEY (`beneficiario_id`) REFERENCES `beneficiarios` (`id`),
  ADD CONSTRAINT `historial_descuentos_ibfk_2` FOREIGN KEY (`aplicado_por`) REFERENCES `empleado` (`id`);

--
-- Filtros para la tabla `historial_laboral`
--
ALTER TABLE `historial_laboral`
  ADD CONSTRAINT `historial_laboral_ibfk_1` FOREIGN KEY (`situacion`) REFERENCES `situacion_laboral` (`id`);

--
-- Filtros para la tabla `historial_solicitudes`
--
ALTER TABLE `historial_solicitudes`
  ADD CONSTRAINT `historial_solicitudes_ibfk_1` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`),
  ADD CONSTRAINT `historial_solicitudes_ibfk_2` FOREIGN KEY (`empleado`) REFERENCES `empleado` (`id`);

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`asistencia_cap`) REFERENCES `asistencia_cap` (`id`),
  ADD CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`curso`) REFERENCES `curso` (`id`);

--
-- Filtros para la tabla `invitado_reunion`
--
ALTER TABLE `invitado_reunion`
  ADD CONSTRAINT `invitado_reunion_ibfk_1` FOREIGN KEY (`reunion`) REFERENCES `reunion` (`id`);

--
-- Filtros para la tabla `matricula`
--
ALTER TABLE `matricula`
  ADD CONSTRAINT `matricula_ibfk_1` FOREIGN KEY (`estudiante`) REFERENCES `estudiante` (`id`),
  ADD CONSTRAINT `matricula_ibfk_2` FOREIGN KEY (`prog_estudios`) REFERENCES `prog_estudios` (`id`);

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`convenio`) REFERENCES `convenio` (`id`),
  ADD CONSTRAINT `notificacion_ibfk_2` FOREIGN KEY (`comunicacion`) REFERENCES `comunicacion` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`estudiante`) REFERENCES `estudiante` (`id`),
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`solicitudes`) REFERENCES `solicitudes` (`id`),
  ADD CONSTRAINT `pagos_ibfk_3` FOREIGN KEY (`tipo_pago`) REFERENCES `tipo_pago` (`id`),
  ADD CONSTRAINT `pagos_ibfk_4` FOREIGN KEY (`registrado_por`) REFERENCES `empleado` (`id`);

--
-- Filtros para la tabla `pago_cap`
--
ALTER TABLE `pago_cap`
  ADD CONSTRAINT `pago_cap_ibfk_1` FOREIGN KEY (`inscripcion`) REFERENCES `inscripcion` (`id`);

--
-- Filtros para la tabla `participante_reunion`
--
ALTER TABLE `participante_reunion`
  ADD CONSTRAINT `participante_reunion_ibfk_1` FOREIGN KEY (`reunion`) REFERENCES `reunion` (`id`);

--
-- Filtros para la tabla `practicas`
--
ALTER TABLE `practicas`
  ADD CONSTRAINT `fk_practicas_docente` FOREIGN KEY (`docente_supervisor`) REFERENCES `empleado` (`id`),
  ADD CONSTRAINT `practicas_ibfk_1` FOREIGN KEY (`estudiante`) REFERENCES `estudiante` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `practicas_ibfk_2` FOREIGN KEY (`empleado`) REFERENCES `empleado` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `practicas_ibfk_3` FOREIGN KEY (`empresa`) REFERENCES `empresa` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `recordatorio`
--
ALTER TABLE `recordatorio`
  ADD CONSTRAINT `recordatorio_ibfk_1` FOREIGN KEY (`notificacion`) REFERENCES `notificacion` (`id`),
  ADD CONSTRAINT `recordatorio_ibfk_2` FOREIGN KEY (`tipo`) REFERENCES `tipo_recordatorio` (`id`);

--
-- Filtros para la tabla `renovacion`
--
ALTER TABLE `renovacion`
  ADD CONSTRAINT `renovacion_ibfk_1` FOREIGN KEY (`convenio`) REFERENCES `convenio` (`id`),
  ADD CONSTRAINT `renovacion_ibfk_2` FOREIGN KEY (`tipo`) REFERENCES `tipo_renovacion` (`id`),
  ADD CONSTRAINT `renovacion_ibfk_3` FOREIGN KEY (`estado`) REFERENCES `estado_renovacion` (`id`);

--
-- Filtros para la tabla `representante`
--
ALTER TABLE `representante`
  ADD CONSTRAINT `representante_ibfk_1` FOREIGN KEY (`instituto`) REFERENCES `instituto` (`id`),
  ADD CONSTRAINT `representante_ibfk_2` FOREIGN KEY (`empresa`) REFERENCES `empresa` (`id`);

--
-- Filtros para la tabla `resoluciones`
--
ALTER TABLE `resoluciones`
  ADD CONSTRAINT `resoluciones_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `empleado` (`id`);

--
-- Filtros para la tabla `reunion`
--
ALTER TABLE `reunion`
  ADD CONSTRAINT `reunion_ibfk_1` FOREIGN KEY (`convenio`) REFERENCES `convenio` (`id`);

--
-- Filtros para la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD CONSTRAINT `seguimiento_ibfk_1` FOREIGN KEY (`estudiante`) REFERENCES `estudiante` (`id`),
  ADD CONSTRAINT `seguimiento_ibfk_2` FOREIGN KEY (`tipo`) REFERENCES `tipo_seguimiento` (`id`);

--
-- Filtros para la tabla `situacion_laboral`
--
ALTER TABLE `situacion_laboral`
  ADD CONSTRAINT `situacion_laboral_ibfk_1` FOREIGN KEY (`estudiante`) REFERENCES `estudiante` (`id`),
  ADD CONSTRAINT `situacion_laboral_ibfk_2` FOREIGN KEY (`empresa`) REFERENCES `empresa` (`id`),
  ADD CONSTRAINT `situacion_laboral_ibfk_3` FOREIGN KEY (`condicion_laboral`) REFERENCES `condicion_laboral` (`id`);

--
-- Filtros para la tabla `situacion_medio`
--
ALTER TABLE `situacion_medio`
  ADD CONSTRAINT `situacion_medio_ibfk_1` FOREIGN KEY (`situacion`) REFERENCES `situacion_laboral` (`id`),
  ADD CONSTRAINT `situacion_medio_ibfk_2` FOREIGN KEY (`medio`) REFERENCES `medio_consecucion` (`id`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`estudiante`) REFERENCES `estudiante` (`id`),
  ADD CONSTRAINT `solicitudes_ibfk_2` FOREIGN KEY (`resoluciones`) REFERENCES `resoluciones` (`id`),
  ADD CONSTRAINT `solicitudes_ibfk_3` FOREIGN KEY (`empleado`) REFERENCES `empleado` (`id`);

--
-- Filtros para la tabla `ubdistrito`
--
ALTER TABLE `ubdistrito`
  ADD CONSTRAINT `ubdistrito_ibfk_1` FOREIGN KEY (`ubprovincia`) REFERENCES `ubprovincia` (`id`);

--
-- Filtros para la tabla `ubprovincia`
--
ALTER TABLE `ubprovincia`
  ADD CONSTRAINT `ubprovincia_ibfk_1` FOREIGN KEY (`ubdepartamento`) REFERENCES `ubdepartamento` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
