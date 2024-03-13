-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 01, 2023 at 05:16 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_credinstantes`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `table_reset`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `table_reset` ()  BEGIN
	TRUNCATE TABLE tbl_clientes;
	TRUNCATE TABLE tbl_pagosabonos;
	TRUNCATE TABLE tbl_abonoscreditos;
	TRUNCATE TABLE tbl_creditos;

END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `ObtenerValorDinamico`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `ObtenerValorDinamico` (`idCredito` INT, `NumPago` INT, `campo` VARCHAR(255)) RETURNS VARCHAR(255) CHARSET latin1 BEGIN
    DECLARE Valor_Return VARCHAR(255) DEFAULT 'N/D';
		
		
		 IF campo = 'FechaPago' THEN
		 
		    SELECT T2.fecha_cuota
        INTO Valor_Return
        FROM Tbl_AbonosCreditos T2 
        WHERE T2.id_creditos = idCredito 
				AND T2.NumPago  = NumPago 
        AND T2.activo = 1 
        ORDER BY T2.fecha_cuota DESC 
        LIMIT 1;

			
			IF Valor_Return ='N/D' THEN
        SELECT Pagado  INTO Valor_Return FROM tbl_pagosabonos WHERE id_creditos = idCredito AND numero_pago = NumPago ;
				IF Valor_Return = 0 THEN
					SET Valor_Return = NULL;
					ELSE
					SELECT T2.fecha_cuota INTO Valor_Return FROM Tbl_AbonosCreditos T2 WHERE T2.id_creditos = 1 AND T2.activo = 1 ORDER BY T2.id_abonoscreditos DESC LIMIT 1;
				END IF;
			END IF;


    ELSEIF campo = 'SaldoCuota' THEN
		
       SELECT T2.saldo_cuota
        INTO Valor_Return
        FROM Tbl_AbonosCreditos T2 
        WHERE T2.id_creditos = idCredito 
				AND T2.NumPago  = NumPago 
        AND T2.activo = 1 
        ORDER BY T2.fecha_cuota DESC 
        LIMIT 1;
				
				
				IF Valor_Return ='N/D' THEN
					SELECT Pagado  INTO Valor_Return FROM tbl_pagosabonos WHERE id_creditos = idCredito AND numero_pago = NumPago ;
					
					IF Valor_Return = 0 THEN
						SET Valor_Return = NULL;
					ELSE
						SET Valor_Return = 0.0000;
					END IF;
				END IF;


				

    END IF;

-- 
    
    
    RETURN Valor_Return;
END$$

DROP FUNCTION IF EXISTS `ObtenerValorDinamico_bk`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `ObtenerValorDinamico_bk` (`idCredito` INT, `fechaPago` DATE, `campo` VARCHAR(255)) RETURNS VARCHAR(255) CHARSET latin1 BEGIN
    DECLARE valor VARCHAR(255);
    
    IF campo = 'FechaPago' THEN
        SELECT T2.fecha_cuota 
        INTO valor
        FROM Tbl_AbonosCreditos T2 
        WHERE T2.id_creditos = idCredito 
        -- AND T2.fecha_cuota BETWEEN fechaPago AND DATE_ADD(fechaPago, INTERVAL 6 DAY) 
				AND T2.fecha_cuota BETWEEN DATE_ADD( fechaPago , INTERVAL -5 DAY ) AND DATE_ADD(fechaPago, INTERVAL 6 DAY) 
        AND T2.activo = 1 
        ORDER BY T2.fecha_cuota DESC 
        LIMIT 1;
    ELSEIF campo = 'SaldoCuota' THEN
        SELECT T2.saldo_cuota
        INTO valor
        FROM Tbl_AbonosCreditos T2 
        WHERE T2.id_creditos = idCredito 
        -- AND T2.fecha_cuota BETWEEN fechaPago AND DATE_ADD(fechaPago, INTERVAL 6 DAY) 
				AND T2.fecha_cuota BETWEEN DATE_ADD( fechaPago , INTERVAL -5 DAY ) AND DATE_ADD(fechaPago, INTERVAL 6 DAY) 
        AND T2.activo = 1 
        ORDER BY T2.fecha_cuota DESC 
        LIMIT 1;
    ELSEIF campo = 'ID_PAGO' THEN
		
			 SELECT T2.id_abonoscreditos
					INTO valor
					FROM Tbl_AbonosCreditos T2 
					WHERE T2.id_creditos = idCredito 
					-- AND T2.fecha_cuota BETWEEN fechaPago AND DATE_ADD(fechaPago, INTERVAL 6 DAY) 
					AND T2.fecha_cuota BETWEEN DATE_ADD( fechaPago , INTERVAL -5 DAY ) AND DATE_ADD(fechaPago, INTERVAL 6 DAY) 
					AND T2.activo = 1 
					ORDER BY T2.fecha_cuota DESC 
					LIMIT 1;
		 ELSE
        SET valor = NULL;
    END IF;
    
    
    RETURN valor;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cat_departamento`
--

DROP TABLE IF EXISTS `cat_departamento`;
CREATE TABLE IF NOT EXISTS `cat_departamento` (
  `id_departamento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_departamento` varchar(30) DEFAULT NULL,
  `activo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_departamento`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cat_departamento`
--

INSERT INTO `cat_departamento` (`id_departamento`, `nombre_departamento`, `activo`) VALUES
(1, 'CARAZO', 1),
(2, 'LEON', 1),
(3, 'MASAYA', 1),
(4, 'RIVAS', 1),
(6, 'Granada', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cat_diassemana`
--

DROP TABLE IF EXISTS `cat_diassemana`;
CREATE TABLE IF NOT EXISTS `cat_diassemana` (
  `id_diassemana` int(11) NOT NULL AUTO_INCREMENT,
  `dia_semana` varchar(50) NOT NULL,
  `dia_semananum` int(11) NOT NULL,
  `activo` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id_diassemana`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cat_diassemana`
--

INSERT INTO `cat_diassemana` (`id_diassemana`, `dia_semana`, `dia_semananum`, `activo`) VALUES
(1, 'LUNES', 1, b'1'),
(2, 'MARTES', 2, b'1'),
(3, 'MIERCOLES', 3, b'1'),
(4, 'JUEVES', 4, b'1'),
(5, 'VIERNES', 5, b'1'),
(6, 'SABADO', 6, b'1'),
(7, 'DOMINGO', 7, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `cat_municipio`
--

DROP TABLE IF EXISTS `cat_municipio`;
CREATE TABLE IF NOT EXISTS `cat_municipio` (
  `id_municipio` int(11) NOT NULL AUTO_INCREMENT,
  `id_departamento` int(11) DEFAULT NULL,
  `nombre_municipio` varchar(10) DEFAULT NULL,
  `activo` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id_municipio`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cat_municipio`
--

INSERT INTO `cat_municipio` (`id_municipio`, `id_departamento`, `nombre_municipio`, `activo`) VALUES
(4, 1, 'CARAZO', b'1'),
(5, 1, 'GRANADA', b'1'),
(6, 1, 'MASAYA', b'1'),
(7, 1, 'RIVAS', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `cat_zona`
--

DROP TABLE IF EXISTS `cat_zona`;
CREATE TABLE IF NOT EXISTS `cat_zona` (
  `id_zona` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_zona` varchar(30) DEFAULT NULL,
  `activo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_zona`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cat_zona`
--

INSERT INTO `cat_zona` (`id_zona`, `nombre_zona`, `activo`) VALUES
(1, 'Zona Jinotepe', 1),
(2, 'Zona Nandaime', 1),
(3, 'Zona la concha y san marcos', 1),
(4, 'Zona Diriamba y dolores', 1),
(5, 'Zona los pueblos', 1),
(13, 'CREAR NUEVA ZONA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`id`, `descripcion`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'S', '2022-02-22 16:47:52', '2022-08-29 15:15:49'),
(2, 'Cobrador', 'S', '2023-09-29 16:30:12', '2023-09-29 16:30:12'),
(3, 'Operadores', 'S', '2023-09-29 16:30:12', '2023-09-29 16:30:12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_abonoscreditos`
--

DROP TABLE IF EXISTS `tbl_abonoscreditos`;
CREATE TABLE IF NOT EXISTS `tbl_abonoscreditos` (
  `id_abonoscreditos` int(11) NOT NULL AUTO_INCREMENT,
  `id_creditos` int(11) NOT NULL,
  `registrado_por` int(16) DEFAULT NULL,
  `fecha_cuota` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `NumPago` int(11) NOT NULL DEFAULT '0',
  `Descuento` decimal(19,4) NOT NULL DEFAULT '0.0000',
  `pago_capital` decimal(19,4) DEFAULT '0.0000',
  `pago_intereses` decimal(19,4) DEFAULT NULL,
  `cuota_credito` decimal(19,4) NOT NULL DEFAULT '0.0000',
  `cuota_cobrada` decimal(19,4) NOT NULL DEFAULT '0.0000',
  `intereses_por_cuota` decimal(19,4) DEFAULT NULL,
  `abono_dia1` decimal(19,4) DEFAULT NULL,
  `abono_dia2` decimal(19,4) DEFAULT NULL,
  `fecha_cuota_secc1` datetime DEFAULT NULL,
  `fecha_cuota_secc2` datetime DEFAULT NULL,
  `fecha_programada` datetime DEFAULT NULL,
  `completado` bit(1) DEFAULT NULL,
  `saldo_cuota` decimal(19,4) DEFAULT NULL,
  `saldo_anterior` decimal(19,4) DEFAULT NULL,
  `saldo_actual` decimal(19,4) DEFAULT NULL,
  `activo` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id_abonoscreditos`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_abonoscreditos`
--

INSERT INTO `tbl_abonoscreditos` (`id_abonoscreditos`, `id_creditos`, `registrado_por`, `fecha_cuota`, `NumPago`, `Descuento`, `pago_capital`, `pago_intereses`, `cuota_credito`, `cuota_cobrada`, `intereses_por_cuota`, `abono_dia1`, `abono_dia2`, `fecha_cuota_secc1`, `fecha_cuota_secc2`, `fecha_programada`, `completado`, `saldo_cuota`, `saldo_anterior`, `saldo_actual`, `activo`) VALUES
(1, 1, 7, '2023-12-01 00:00:00', 1, '0.0000', '1000.0000', '480.0000', '1480.0000', '1480.0000', '480.0000', '1480.0000', NULL, '2023-12-01 00:00:00', NULL, NULL, b'1', '0.0000', '17760.0000', '16280.0000', b'1'),
(2, 1, 7, '2023-12-01 00:00:00', 2, '0.0000', '1000.0000', '480.0000', '1480.0000', '7400.0000', '480.0000', '7400.0000', NULL, '2023-12-01 00:00:00', NULL, NULL, b'1', '0.0000', '16280.0000', '14800.0000', b'1'),
(3, 1, 7, '2023-12-01 00:00:00', 3, '0.0000', '1000.0000', '480.0000', '1480.0000', '5920.0000', '480.0000', '5920.0000', NULL, '2023-12-01 00:00:00', NULL, NULL, b'1', '0.0000', '14800.0000', '13320.0000', b'1'),
(4, 1, 7, '2023-12-01 00:00:00', 4, '0.0000', '1000.0000', '480.0000', '1480.0000', '4440.0000', '480.0000', '4440.0000', NULL, '2023-12-01 00:00:00', NULL, NULL, b'1', '0.0000', '13320.0000', '11840.0000', b'1'),
(5, 1, 7, '2023-12-01 00:00:00', 5, '0.0000', '1000.0000', '480.0000', '1480.0000', '2960.0000', '480.0000', '2960.0000', NULL, '2023-12-01 00:00:00', NULL, NULL, b'1', '0.0000', '11840.0000', '10360.0000', b'1'),
(6, 1, 7, '2023-12-01 00:00:00', 6, '0.0000', '1000.0000', '480.0000', '1480.0000', '1480.0000', '480.0000', '1480.0000', NULL, '2023-12-01 00:00:00', NULL, NULL, b'1', '0.0000', '10360.0000', '8880.0000', b'1'),
(7, 1, 7, '2023-12-29 00:00:00', 0, '880.0000', '6000.0000', '2000.0000', '1480.0000', '8000.0000', '480.0000', '8000.0000', NULL, '2023-12-29 00:00:00', NULL, NULL, b'1', '0.0000', '8880.0000', '0.0000', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clientes`
--

DROP TABLE IF EXISTS `tbl_clientes`;
CREATE TABLE IF NOT EXISTS `tbl_clientes` (
  `id_clientes` int(11) NOT NULL AUTO_INCREMENT,
  `id_municipio` int(11) NOT NULL,
  `id_zona` int(11) NOT NULL DEFAULT '1',
  `nombre` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `direccion_domicilio` varchar(250) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '100',
  `activo` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id_clientes`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_clientes`
--

INSERT INTO `tbl_clientes` (`id_clientes`, `id_municipio`, `id_zona`, `nombre`, `apellidos`, `direccion_domicilio`, `cedula`, `telefono`, `score`, `activo`) VALUES
(1, 4, 1, 'NOMBRE', 'APELLIDO', 'N/D', '000-000000-0000A', '+505-0000-0000', 100, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_credinstante`
--

DROP TABLE IF EXISTS `tbl_credinstante`;
CREATE TABLE IF NOT EXISTS `tbl_credinstante` (
  `id_credinstante` int(11) NOT NULL AUTO_INCREMENT,
  `id_municipio` int(11) NOT NULL,
  `UserId` int(5) NOT NULL DEFAULT '1',
  `cargo_responsable` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `nombre_sucursal` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_credinstante`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_creditos`
--

DROP TABLE IF EXISTS `tbl_creditos`;
CREATE TABLE IF NOT EXISTS `tbl_creditos` (
  `id_creditos` int(11) NOT NULL AUTO_INCREMENT,
  `creado_por` int(5) NOT NULL,
  `id_diassemana` int(11) NOT NULL,
  `id_clientes` int(11) NOT NULL,
  `fecha_apertura` datetime NOT NULL,
  `fecha_ultimo_abono` datetime DEFAULT NULL,
  `fecha_culmina` datetime DEFAULT NULL,
  `monto_credito` decimal(19,4) NOT NULL,
  `plazo` decimal(5,2) NOT NULL,
  `taza_interes` decimal(5,2) NOT NULL,
  `numero_cuotas` decimal(5,2) NOT NULL,
  `total` decimal(28,8) DEFAULT NULL,
  `cuota` decimal(35,13) DEFAULT NULL,
  `saldo` decimal(19,4) DEFAULT '0.0000',
  `interes` decimal(28,8) DEFAULT NULL,
  `intereses_por_cuota` decimal(35,13) DEFAULT NULL,
  `salud_credito` int(10) DEFAULT '1',
  `estado_credito` int(10) DEFAULT NULL,
  `activo` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id_creditos`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_creditos`
--

INSERT INTO `tbl_creditos` (`id_creditos`, `creado_por`, `id_diassemana`, `id_clientes`, `fecha_apertura`, `fecha_ultimo_abono`, `fecha_culmina`, `monto_credito`, `plazo`, `taza_interes`, `numero_cuotas`, `total`, `cuota`, `saldo`, `interes`, `intereses_por_cuota`, `salud_credito`, `estado_credito`, `activo`) VALUES
(1, 7, 1, 1, '2023-12-01 00:00:00', '2024-02-23 00:00:00', '2023-12-29 00:00:00', '12000.0000', '3.00', '16.00', '12.00', '17760.00000000', '1480.0000000000000', '0.0000', '5760.00000000', '480.0000000000000', 1, 1, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_estados`
--

DROP TABLE IF EXISTS `tbl_estados`;
CREATE TABLE IF NOT EXISTS `tbl_estados` (
  `id_estados` int(10) NOT NULL AUTO_INCREMENT,
  `nombre_estado` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_estados`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_estados`
--

INSERT INTO `tbl_estados` (`id_estados`, `nombre_estado`) VALUES
(1, 'Al Dia'),
(2, 'En Mora'),
(3, 'Vencido'),
(4, 'Inactivo');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logs`
--

DROP TABLE IF EXISTS `tbl_logs`;
CREATE TABLE IF NOT EXISTS `tbl_logs` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_log`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_logs`
--

INSERT INTO `tbl_logs` (`id_log`, `created_at`, `updated_at`) VALUES
(9, '2023-10-05 22:03:23', '2023-10-05 22:03:23'),
(10, '2023-10-06 21:29:15', '2023-10-06 21:29:15'),
(11, '2023-10-07 16:25:50', '2023-10-07 16:25:50'),
(12, '2023-10-08 15:38:07', '2023-10-08 15:38:07'),
(13, '2023-10-09 13:35:55', '2023-10-09 13:35:55'),
(14, '2023-10-10 14:26:50', '2023-10-10 14:26:50'),
(15, '2023-10-11 12:12:00', '2023-10-11 12:12:00'),
(16, '2023-10-12 12:53:28', '2023-10-12 12:53:28'),
(17, '2023-10-13 11:57:15', '2023-10-13 11:57:15'),
(18, '2023-10-14 13:59:20', '2023-10-14 13:59:20'),
(19, '2023-10-15 15:49:06', '2023-10-15 15:49:06'),
(20, '2023-10-16 12:12:21', '2023-10-16 12:12:21'),
(21, '2023-10-17 12:59:52', '2023-10-17 12:59:52'),
(22, '2023-10-18 14:23:37', '2023-10-18 14:23:37'),
(23, '2023-10-19 13:51:00', '2023-10-19 13:51:00'),
(24, '2023-10-20 15:51:17', '2023-10-20 15:51:17'),
(25, '2023-10-22 02:18:03', '2023-10-22 02:18:03'),
(26, '2023-10-22 23:35:21', '2023-10-22 23:35:21'),
(27, '2023-10-23 11:51:02', '2023-10-23 11:51:02'),
(28, '2023-10-24 12:55:50', '2023-10-24 12:55:50'),
(29, '2023-10-25 13:06:58', '2023-10-25 13:06:58'),
(30, '2023-10-26 16:49:52', '2023-10-26 16:49:52'),
(32, '2023-10-27 15:04:16', '2023-10-27 15:04:16'),
(33, '2023-10-28 19:32:41', '2023-10-28 19:32:41'),
(34, '2023-10-29 17:21:15', '2023-10-29 17:21:15'),
(35, '2023-10-30 13:54:37', '2023-10-30 13:54:37'),
(36, '2023-10-31 13:57:43', '2023-10-31 13:57:43'),
(37, '2023-11-01 13:41:46', '2023-11-01 13:41:46'),
(38, '2023-11-02 15:34:38', '2023-11-02 15:34:38'),
(39, '2023-11-03 13:58:04', '2023-11-03 13:58:04'),
(40, '2023-11-04 16:47:24', '2023-11-04 16:47:24'),
(41, '2023-11-05 18:26:47', '2023-11-05 18:26:47'),
(42, '2023-11-06 13:41:37', '2023-11-06 13:41:37'),
(43, '2023-11-07 14:20:46', '2023-11-07 14:20:46'),
(44, '2023-11-08 13:29:33', '2023-11-08 13:29:33'),
(45, '2023-11-09 12:01:01', '2023-11-09 12:01:01'),
(46, '2023-11-10 14:13:50', '2023-11-10 14:13:50'),
(47, '2023-11-11 15:22:15', '2023-11-11 15:22:15'),
(48, '2023-11-13 00:26:40', '2023-11-13 00:26:40'),
(49, '2023-11-13 13:14:59', '2023-11-13 13:14:59'),
(50, '2023-11-14 13:43:53', '2023-11-14 13:43:53'),
(51, '2023-11-15 15:55:40', '2023-11-15 15:55:40'),
(52, '2023-11-16 16:27:17', '2023-11-16 16:27:17'),
(53, '2023-11-17 14:49:29', '2023-11-17 14:49:29'),
(54, '2023-11-18 16:34:32', '2023-11-18 16:34:32'),
(55, '2023-11-19 13:42:51', '2023-11-19 13:42:51'),
(56, '2023-11-20 13:58:25', '2023-11-20 13:58:25'),
(57, '2023-11-21 13:08:18', '2023-11-21 13:08:18'),
(58, '2023-11-22 12:55:41', '2023-11-22 12:55:41'),
(59, '2023-11-23 14:59:49', '2023-11-23 14:59:49'),
(62, '2023-11-24 18:34:12', '2023-11-24 18:34:12'),
(63, '2023-11-25 14:52:25', '2023-11-25 14:52:25'),
(64, '2023-11-26 17:08:39', '2023-11-26 17:08:39'),
(65, '2023-11-27 13:07:51', '2023-11-27 13:07:51'),
(66, '2023-11-28 14:22:59', '2023-11-28 14:22:59'),
(67, '2023-11-29 14:38:50', '2023-11-29 14:38:50'),
(68, '2023-11-30 14:36:28', '2023-11-30 14:36:28'),
(69, '2023-12-01 14:03:04', '2023-12-01 14:03:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pagosabonos`
--

DROP TABLE IF EXISTS `tbl_pagosabonos`;
CREATE TABLE IF NOT EXISTS `tbl_pagosabonos` (
  `id_pagoabono` int(11) NOT NULL AUTO_INCREMENT,
  `id_creditos` int(11) DEFAULT NULL,
  `numero_pago` int(11) DEFAULT NULL,
  `FechaPago` datetime DEFAULT NULL,
  `Pagado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_pagoabono`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pagosabonos`
--

INSERT INTO `tbl_pagosabonos` (`id_pagoabono`, `id_creditos`, `numero_pago`, `FechaPago`, `Pagado`) VALUES
(1, 1, 1, '2023-12-08 00:00:00', 1),
(2, 1, 2, '2023-12-15 00:00:00', 1),
(3, 1, 3, '2023-12-22 00:00:00', 1),
(4, 1, 4, '2023-12-29 00:00:00', 1),
(5, 1, 5, '2024-01-05 00:00:00', 1),
(6, 1, 6, '2024-01-12 00:00:00', 1),
(7, 1, 7, '2024-01-19 00:00:00', 1),
(8, 1, 8, '2024-01-26 00:00:00', 1),
(9, 1, 9, '2024-02-02 00:00:00', 1),
(10, 1, 10, '2024-02-09 00:00:00', 1),
(11, 1, 11, '2024-02-16 00:00:00', 1),
(12, 1, 12, '2024-02-23 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `activo` varchar(5) DEFAULT NULL,
  `Comment` varchar(400) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `id_zona` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nombre`, `email`, `password`, `activo`, `Comment`, `id_rol`, `id_zona`, `created_at`, `updated_at`) VALUES
(7, 'Wilber Ramos', 'wilmer@credinstantes.com', '$2y$10$jySsuRRw1eU0SpOz5jL5nuK0pfSG3XZLDA/YOa2s14WE92Lu1Twwy', 'S', 'Será el encargado de realizar cualquier operación sobre el sistema,\neste tendrá todos los privilegios posibles, y será el encargado de dar\nde alta a otros usuarios, asignar sus roles, dar de baja, definir qué\nacción pueden tener sobre las ventanas (editar, eliminar, consultar,\ninsertar).', 1, 1, '2022-08-29 21:41:07', '2023-11-08 03:14:47'),
(30, 'Wilmer Ramos', 'demo@demo.com', '$2y$10$jySsuRRw1eU0SpOz5jL5nuK0pfSG3XZLDA/YOa2s14WE92Lu1Twwy', '0', 'dsfsdfsd', 3, 1, '2023-09-29 17:45:29', '2023-09-29 17:51:21'),
(31, 'maryan adan', 'endscom@gmail.com', '$2y$10$jySsuRRw1eU0SpOz5jL5nuK0pfSG3XZLDA/YOa2s14WE92Lu1Twwy', '0', '123456', 2, 1, '2023-09-29 18:11:49', '2023-09-29 18:11:49'),
(32, 'Demo', 'demo2@demo.com', '$2y$10$jySsuRRw1eU0SpOz5jL5nuK0pfSG3XZLDA/YOa2s14WE92Lu1Twwy', 'S', 'Será el encargado de realizar cualquier operación sobre el sistema,\r\neste tendrá todos los privilegios posibles, y será el encargado de dar\r\nde alta a otros usuarios, asignar sus roles, dar de baja, definir qué\r\nacción pueden tener sobre las ventanas (editar, eliminar, consultar,\r\ninsertar).', 1, 1, '2022-08-29 21:41:07', '2023-10-01 18:54:02'),
(33, 'Yanira Duarte', 'yanirad@credinstante.com', '$2y$10$jySsuRRw1eU0SpOz5jL5nuK0pfSG3XZLDA/YOa2s14WE92Lu1Twwy', '0', 'Operaciones y manejo de sistema', 3, 1, '2023-10-11 05:45:46', '2023-11-08 03:07:01'),
(34, 'Cobrador zona #1 Nandaime', 'Nandaime@credinstante.com', '$2y$10$jySsuRRw1eU0SpOz5jL5nuK0pfSG3XZLDA/YOa2s14WE92Lu1Twwy', 'S', 'Zona Nandaime', 2, 1, '2023-10-11 05:49:16', '2023-11-08 03:06:23'),
(35, 'Yanira duarte', 'Yanirad@gmail.com', '$2y$10$jySsuRRw1eU0SpOz5jL5nuK0pfSG3XZLDA/YOa2s14WE92Lu1Twwy', 'S', 'Operaciones', 3, 1, '2023-11-08 03:09:12', '2023-11-08 03:09:12'),
(36, 'ALONSO RAMOS', 'ALONSOR@.COM', '$2y$10$jySsuRRw1eU0SpOz5jL5nuK0pfSG3XZLDA/YOa2s14WE92Lu1Twwy', '0', 'COBRANZA', 2, 2, '2023-11-15 02:05:03', '2023-11-15 02:05:03'),
(37, 'ALONSO RAMOS', 'ALONSO@RAMOS.COM', '$2y$10$jySsuRRw1eU0SpOz5jL5nuK0pfSG3XZLDA/YOa2s14WE92Lu1Twwy', 'S', 'COBRAR', 2, 2, '2023-11-15 02:08:39', '2023-11-15 02:08:39');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_fecha_pagos`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_fecha_pagos`;
CREATE TABLE IF NOT EXISTS `view_fecha_pagos` (
`ID_CREDITO` int(11)
,`ID_CLIENTE` int(11)
,`ID_ZONA` int(11)
,`NUM_PAGO` int(11)
,`FECHA_PAGO` datetime
,`FECHA_ABONO` varchar(255)
,`SALDO_PENDIENTE` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_fecha_pagos_bk`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_fecha_pagos_bk`;
CREATE TABLE IF NOT EXISTS `view_fecha_pagos_bk` (
`ID_PAGO` varchar(255)
,`ID_CREDITO` int(11)
,`ID_CLIENTE` int(11)
,`ID_ZONA` int(11)
,`NUM_PAGO` int(11)
,`FECHA_PAGO` datetime
,`FECHA_ABONO` varchar(255)
,`SALDO_PENDIENTE` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_logs_pagos`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_logs_pagos`;
CREATE TABLE IF NOT EXISTS `view_logs_pagos` (
`id_abonoscreditos` int(11)
,`id_creditos` int(11)
,`id_clientes` int(11)
,`id_zona` int(11)
,`CAPITAL` decimal(20,4)
,`INTERES` decimal(19,4)
,`FECHA_ABONO` datetime
,`activo` bit(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_status_cliente`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_status_cliente`;
CREATE TABLE IF NOT EXISTS `view_status_cliente` (
`ID` int(11)
,`ID_CREDITO` int(11)
,`NOMBRE` varchar(150)
,`APELLIDOS` varchar(150)
,`DIRECCIN` varchar(250)
,`CEDULA` varchar(50)
,`TELEFONO` varchar(20)
,`ULTIMA_FECHA_ABONO` datetime
,`SALDO_CREDITO` decimal(19,4)
,`DIA_VISITA` int(11)
,`DIAS_EN_MORA` int(7)
,`DIAS_PARA_VENCER` int(7)
,`VENCIDO` varchar(1)
,`MORA` varchar(1)
,`CLIENTE_ACTIVO` bit(1)
,`CREDITO_ACTIVO` bit(1)
);

-- --------------------------------------------------------

--
-- Structure for view `view_fecha_pagos`
--
DROP TABLE IF EXISTS `view_fecha_pagos`;

DROP VIEW IF EXISTS `view_fecha_pagos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_fecha_pagos`  AS SELECT `t0`.`id_creditos` AS `ID_CREDITO`, `t1`.`id_clientes` AS `ID_CLIENTE`, `t2`.`id_zona` AS `ID_ZONA`, `t0`.`numero_pago` AS `NUM_PAGO`, `t0`.`FechaPago` AS `FECHA_PAGO`, `ObtenerValorDinamico`(`t0`.`id_creditos`,`t0`.`numero_pago`,'FechaPago') AS `FECHA_ABONO`, coalesce(`ObtenerValorDinamico`(`t0`.`id_creditos`,`t0`.`numero_pago`,'SaldoCuota'),`t1`.`cuota`) AS `SALDO_PENDIENTE` FROM ((`tbl_pagosabonos` `t0` join `tbl_creditos` `t1` on((`t0`.`id_creditos` = `t1`.`id_creditos`))) join `tbl_clientes` `t2` on((`t2`.`id_clientes` = `t1`.`id_clientes`))) WHERE (`t1`.`activo` = 1) ;

-- --------------------------------------------------------

--
-- Structure for view `view_fecha_pagos_bk`
--
DROP TABLE IF EXISTS `view_fecha_pagos_bk`;

DROP VIEW IF EXISTS `view_fecha_pagos_bk`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_fecha_pagos_bk`  AS SELECT `ObtenerValorDinamico_bk`(`t0`.`id_creditos`,`t0`.`FechaPago`,'ID_PAGO') AS `ID_PAGO`, `t0`.`id_creditos` AS `ID_CREDITO`, `t1`.`id_clientes` AS `ID_CLIENTE`, `t2`.`id_zona` AS `ID_ZONA`, `t0`.`numero_pago` AS `NUM_PAGO`, `t0`.`FechaPago` AS `FECHA_PAGO`, `ObtenerValorDinamico_bk`(`t0`.`id_creditos`,`t0`.`FechaPago`,'FechaPago') AS `FECHA_ABONO`, coalesce(`ObtenerValorDinamico_bk`(`t0`.`id_creditos`,`t0`.`FechaPago`,'SaldoCuota'),`t1`.`cuota`) AS `SALDO_PENDIENTE` FROM ((`tbl_pagosabonos` `t0` join `tbl_creditos` `t1` on((`t0`.`id_creditos` = `t1`.`id_creditos`))) join `tbl_clientes` `t2` on((`t2`.`id_clientes` = `t1`.`id_clientes`))) WHERE (`t1`.`activo` = 1) ;

-- --------------------------------------------------------

--
-- Structure for view `view_logs_pagos`
--
DROP TABLE IF EXISTS `view_logs_pagos`;

DROP VIEW IF EXISTS `view_logs_pagos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_logs_pagos`  AS SELECT `t0`.`id_abonoscreditos` AS `id_abonoscreditos`, `t0`.`id_creditos` AS `id_creditos`, `t1`.`id_clientes` AS `id_clientes`, `t2`.`id_zona` AS `id_zona`, (`t0`.`abono_dia1` - `t0`.`intereses_por_cuota`) AS `CAPITAL`, `t0`.`pago_intereses` AS `INTERES`, `t0`.`fecha_cuota_secc1` AS `FECHA_ABONO`, `t0`.`activo` AS `activo` FROM ((`tbl_abonoscreditos` `t0` join `tbl_creditos` `t1` on((`t0`.`id_creditos` = `t1`.`id_creditos`))) join `tbl_clientes` `t2` on((`t2`.`id_clientes` = `t1`.`id_clientes`))) WHERE (`t0`.`activo` = 1) ;

-- --------------------------------------------------------

--
-- Structure for view `view_status_cliente`
--
DROP TABLE IF EXISTS `view_status_cliente`;

DROP VIEW IF EXISTS `view_status_cliente`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_status_cliente`  AS SELECT `c`.`id_clientes` AS `ID`, `cr`.`id_creditos` AS `ID_CREDITO`, `c`.`nombre` AS `NOMBRE`, `c`.`apellidos` AS `APELLIDOS`, `c`.`direccion_domicilio` AS `DIRECCIN`, `c`.`cedula` AS `CEDULA`, `c`.`telefono` AS `TELEFONO`, max(coalesce(`ac`.`fecha_cuota`,`cr`.`fecha_apertura`)) AS `ULTIMA_FECHA_ABONO`, `cr`.`saldo` AS `SALDO_CREDITO`, `cr`.`id_diassemana` AS `DIA_VISITA`, (to_days(now()) - to_days(max(coalesce(`ac`.`fecha_cuota`,`cr`.`fecha_apertura`)))) AS `DIAS_EN_MORA`, (to_days(`cr`.`fecha_ultimo_abono`) - to_days(now())) AS `DIAS_PARA_VENCER`, (case when ((to_days(`cr`.`fecha_ultimo_abono`) - to_days(now())) > 0) then 'N' else 'S' end) AS `VENCIDO`, (case when (ifnull((select sum(`t0`.`SALDO_PENDIENTE`) AS `tt` from `view_fecha_pagos` `t0` where ((`t0`.`ID_CREDITO` = `cr`.`id_creditos`) and (`t0`.`FECHA_PAGO` <= (now() - interval 1 day))) group by `t0`.`ID_CREDITO`),0) > 0) then 'S' else 'N' end) AS `MORA`, `c`.`activo` AS `CLIENTE_ACTIVO`, `cr`.`activo` AS `CREDITO_ACTIVO` FROM (((`tbl_clientes` `c` left join `tbl_creditos` `cr` on((`c`.`id_clientes` = `cr`.`id_clientes`))) left join `tbl_abonoscreditos` `ac` on((`cr`.`id_creditos` = `ac`.`id_creditos`))) left join `tbl_pagosabonos` `pa` on(((`cr`.`id_creditos` = `pa`.`id_creditos`) and (`ac`.`id_abonoscreditos` = `pa`.`numero_pago`)))) WHERE (`c`.`activo` = 1) GROUP BY `c`.`id_clientes`, `cr`.`fecha_ultimo_abono`, `cr`.`saldo`, `cr`.`id_diassemana`, `cr`.`activo`, `cr`.`id_creditos` ORDER BY `c`.`id_clientes` ASC ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
