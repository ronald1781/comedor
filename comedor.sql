-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 13-01-2020 a las 22:48:51
-- Versión del servidor: 5.7.24
-- Versión de PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `_comedor`
--
CREATE DATABASE IF NOT EXISTS `_comedor` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `_comedor`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menudetalle`
--

DROP TABLE IF EXISTS `menudetalle`;
CREATE TABLE IF NOT EXISTS `menudetalle` (
  `codmndet` int(11) NOT NULL AUTO_INCREMENT,
  `fprepmnu` date NOT NULL COMMENT 'fecha que se preparan el menu',
  `codtippto` int(11) NOT NULL COMMENT 'tipo alimento plato',
  `codmenu` int(11) NOT NULL,
  `codplto` int(11) NOT NULL,
  `impmndet` decimal(18,2) DEFAULT NULL,
  `monemndet` int(11) DEFAULT NULL COMMENT '1 soles,2 Dolares',
  `usucrmndet` int(11) NOT NULL,
  `fcrmndet` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usumdmndet` int(11) DEFAULT NULL,
  `fmdmndet` datetime DEFAULT NULL,
  `fdlmndet` datetime DEFAULT NULL,
  `estrgmndet` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codmndet`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `menudetalle`
--

INSERT INTO `menudetalle` (`codmndet`, `fprepmnu`, `codtippto`, `codmenu`, `codplto`, `impmndet`, `monemndet`, `usucrmndet`, `fcrmndet`, `usumdmndet`, `fmdmndet`, `fdlmndet`, `estrgmndet`) VALUES
(1, '2020-01-06', 1, 1, 6, '0.00', 0, 2, '2020-01-09 21:07:09', NULL, NULL, NULL, 'A'),
(2, '2020-01-06', 1, 1, 8, '0.00', 0, 2, '2020-01-09 21:07:09', NULL, NULL, NULL, 'A'),
(3, '2020-01-07', 1, 1, 2, '0.00', 0, 2, '2020-01-09 21:07:20', NULL, NULL, NULL, 'A'),
(4, '2020-01-07', 1, 1, 3, '0.00', 0, 2, '2020-01-09 21:07:20', NULL, NULL, NULL, 'A'),
(5, '2020-01-08', 1, 1, 10, '0.00', 0, 2, '2020-01-09 21:07:33', NULL, NULL, '2020-01-09 16:08:05', 'I'),
(6, '2020-01-08', 1, 1, 12, '0.00', 0, 2, '2020-01-09 21:07:33', NULL, NULL, '2020-01-09 16:08:05', 'I'),
(7, '2020-01-08', 1, 1, 1, '0.00', 0, 2, '2020-01-09 21:07:49', NULL, NULL, '2020-01-09 16:08:05', 'I'),
(8, '2020-01-08', 1, 1, 4, '0.00', 0, 2, '2020-01-09 21:07:49', NULL, NULL, '2020-01-09 16:08:05', 'I'),
(9, '2020-01-08', 1, 1, 1, '0.00', 0, 2, '2020-01-09 21:08:40', NULL, NULL, NULL, 'A'),
(10, '2020-01-08', 1, 1, 4, '0.00', 0, 2, '2020-01-09 21:08:40', NULL, NULL, NULL, 'A'),
(11, '2020-01-09', 1, 1, 5, '0.00', 0, 2, '2020-01-09 21:08:55', NULL, NULL, NULL, 'A'),
(12, '2020-01-09', 1, 1, 7, '0.00', 0, 2, '2020-01-09 21:08:55', NULL, NULL, NULL, 'A'),
(13, '2020-01-10', 1, 1, 9, '0.00', 0, 2, '2020-01-09 21:09:11', NULL, NULL, NULL, 'A'),
(14, '2020-01-10', 1, 1, 11, '0.00', 0, 2, '2020-01-09 21:09:11', NULL, NULL, NULL, 'A'),
(15, '2020-01-13', 1, 2, 1, '0.00', 0, 2, '2020-01-09 23:26:52', NULL, NULL, NULL, 'A'),
(16, '2020-01-13', 1, 2, 2, '0.00', 0, 2, '2020-01-09 23:26:52', NULL, NULL, NULL, 'A'),
(17, '2020-01-13', 1, 2, 3, '0.00', 0, 2, '2020-01-09 23:26:52', NULL, NULL, NULL, 'A'),
(18, '2020-01-14', 1, 2, 4, '0.00', 0, 2, '2020-01-09 23:27:04', NULL, NULL, NULL, 'A'),
(19, '2020-01-14', 1, 2, 5, '0.00', 0, 2, '2020-01-09 23:27:04', NULL, NULL, NULL, 'A'),
(20, '2020-01-14', 1, 2, 6, '0.00', 0, 2, '2020-01-09 23:27:04', NULL, NULL, NULL, 'A'),
(21, '2020-01-15', 1, 2, 7, '0.00', 0, 2, '2020-01-09 23:27:15', NULL, NULL, NULL, 'A'),
(22, '2020-01-15', 1, 2, 8, '0.00', 0, 2, '2020-01-09 23:27:15', NULL, NULL, NULL, 'A'),
(23, '2020-01-15', 1, 2, 9, '0.00', 0, 2, '2020-01-09 23:27:15', NULL, NULL, NULL, 'A'),
(24, '2020-01-16', 1, 2, 10, '0.00', 0, 2, '2020-01-09 23:27:30', NULL, NULL, NULL, 'A'),
(25, '2020-01-16', 1, 2, 11, '0.00', 0, 2, '2020-01-09 23:27:30', NULL, NULL, NULL, 'A'),
(26, '2020-01-16', 1, 2, 12, '0.00', 0, 2, '2020-01-09 23:27:30', NULL, NULL, NULL, 'A'),
(27, '2020-01-17', 1, 2, 13, '0.00', 0, 2, '2020-01-09 23:27:41', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `codmnus` int(11) NOT NULL AUTO_INCREMENT,
  `nommnus` varchar(120) NOT NULL,
  `cntpltmnu` int(11) NOT NULL COMMENT 'num platos por dia',
  `fdsdmnu` date DEFAULT NULL,
  `fhstmnu` date DEFAULT NULL,
  `ffnpdmnu` date DEFAULT NULL COMMENT 'fin de pedido',
  `estmenu` char(1) NOT NULL DEFAULT 'G' COMMENT 'G Generado, A Aprobado',
  `usucrmnus` int(11) NOT NULL,
  `fcrmnus` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usumdmnus` int(11) DEFAULT NULL,
  `fmdmnus` datetime DEFAULT NULL,
  `fdlmnu` datetime DEFAULT NULL,
  `estrgmnus` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codmnus`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`codmnus`, `nommnus`, `cntpltmnu`, `fdsdmnu`, `fhstmnu`, `ffnpdmnu`, `estmenu`, `usucrmnus`, `fcrmnus`, `usumdmnus`, `fmdmnus`, `fdlmnu`, `estrgmnus`) VALUES
(1, 'Menu desde 2020-01-06 hasta 2020-01-10', 2, '2020-01-06', '2020-01-10', '2020-01-03', 'G', 2, '2020-01-09 21:06:56', NULL, NULL, NULL, 'A'),
(2, 'Menu desde 2020-01-13 hasta 2020-01-17', 3, '2020-01-13', '2020-01-17', '2020-01-10', 'G', 2, '2020-01-09 22:59:28', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidodetalle`
--

DROP TABLE IF EXISTS `pedidodetalle`;
CREATE TABLE IF NOT EXISTS `pedidodetalle` (
  `codpd` int(11) NOT NULL AUTO_INCREMENT,
  `codped` int(11) NOT NULL,
  `codmdp` int(11) NOT NULL COMMENT 'codigo menu detalle',
  `fpedd` datetime DEFAULT NULL,
  `estdp` char(1) NOT NULL DEFAULT 'P' COMMENT 'P Pedido C consumido',
  `fcnspd` datetime DEFAULT NULL,
  `califpltodp` int(11) DEFAULT NULL COMMENT '1 bajo, 2 mediano,3 bueno,4 muy bueno y 5 execelente con extrellitas',
  `usucrpd` int(11) NOT NULL,
  `fcrpd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usumdpd` int(11) DEFAULT NULL,
  `fmdpd` datetime DEFAULT NULL,
  `fdelpd` datetime DEFAULT NULL,
  `estrgpd` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codpd`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pedidodetalle`
--

INSERT INTO `pedidodetalle` (`codpd`, `codped`, `codmdp`, `fpedd`, `estdp`, `fcnspd`, `califpltodp`, `usucrpd`, `fcrpd`, `usumdpd`, `fmdpd`, `fdelpd`, `estrgpd`) VALUES
(1, 1, 2, NULL, 'P', NULL, NULL, 1, '2020-01-09 21:11:04', NULL, NULL, NULL, 'A'),
(2, 1, 3, NULL, 'P', NULL, NULL, 1, '2020-01-09 21:11:04', NULL, NULL, NULL, 'A'),
(3, 1, 10, NULL, 'P', NULL, NULL, 1, '2020-01-09 21:11:04', NULL, NULL, NULL, 'A'),
(4, 1, 12, NULL, 'P', NULL, NULL, 1, '2020-01-09 21:11:04', NULL, NULL, NULL, 'A'),
(5, 1, 13, NULL, 'C', '2020-01-10 14:06:24', 0, 1, '2020-01-09 21:11:04', 1, '2020-01-10 14:06:24', NULL, 'A'),
(6, 2, 16, NULL, 'C', '2020-01-13 10:34:23', 0, 1, '2020-01-10 21:33:49', 1, '2020-01-13 10:34:23', NULL, 'A'),
(7, 2, 20, NULL, 'P', NULL, NULL, 1, '2020-01-10 21:33:49', NULL, NULL, NULL, 'A'),
(8, 2, 21, NULL, 'P', NULL, NULL, 1, '2020-01-10 21:33:49', NULL, NULL, NULL, 'A'),
(9, 2, 25, NULL, 'P', NULL, NULL, 1, '2020-01-10 21:33:49', NULL, NULL, NULL, 'A'),
(10, 2, 27, NULL, 'P', NULL, NULL, 1, '2020-01-10 21:33:49', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `codped` int(11) NOT NULL AUTO_INCREMENT,
  `codper` int(11) NOT NULL,
  `cdmnu` int(11) NOT NULL,
  `comen` varchar(180) DEFAULT NULL COMMENT 'Comentario o sugerencia',
  `fechped` datetime NOT NULL,
  `estped` char(1) NOT NULL DEFAULT 'P' COMMENT 'p pedido c consumido',
  `feconped` datetime DEFAULT NULL,
  `usucrped` int(11) NOT NULL,
  `fcrped` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usumdped` int(11) DEFAULT NULL,
  `fmdped` datetime DEFAULT NULL,
  `estrgped` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codped`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`codped`, `codper`, `cdmnu`, `comen`, `fechped`, `estped`, `feconped`, `usucrped`, `fcrped`, `usumdped`, `fmdped`, `estrgped`) VALUES
(1, 1, 1, 'gracias', '2020-01-09 16:11:04', 'P', NULL, 1, '2020-01-09 21:11:04', NULL, NULL, 'A'),
(2, 1, 2, '', '2020-01-10 16:33:49', 'P', NULL, 1, '2020-01-10 21:33:49', NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

DROP TABLE IF EXISTS `personas`;
CREATE TABLE IF NOT EXISTS `personas` (
  `codper` int(11) NOT NULL AUTO_INCREMENT,
  `dniper` varchar(12) NOT NULL,
  `nomper` varchar(80) NOT NULL,
  `apepper` varchar(60) NOT NULL,
  `apmper` varchar(75) NOT NULL,
  `emailper` varchar(180) DEFAULT NULL,
  `sucper` int(11) DEFAULT NULL,
  `usucrper` int(11) NOT NULL,
  `fcrper` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usumdper` int(11) DEFAULT NULL,
  `fmdper` datetime DEFAULT NULL,
  `fdelper` datetime DEFAULT NULL,
  `estrgper` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codper`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`codper`, `dniper`, `nomper`, `apepper`, `apmper`, `emailper`, `sucper`, `usucrper`, `fcrper`, `usumdper`, `fmdper`, `fdelper`, `estrgper`) VALUES
(1, '42264935', 'ronald', 'ramos', 'gutierrez', 'rramos@mym.com.pe', 1, 1, '2020-01-02 14:33:51', 1, '2020-01-03 15:38:54', NULL, 'A'),
(2, '47491168', 'Juan Daniel', 'gutierrez', 'Paz', 'jgutierrez@mym.com.pe', 1, 1, '2020-01-03 20:32:05', 1, '2020-01-03 15:39:31', '2020-01-03 15:39:40', 'A'),
(3, '422649359', 'ronald', 'ramos', 'gutierrez', '', 1, 1, '2020-01-03 23:07:50', NULL, NULL, NULL, 'A'),
(4, '56823459', 'dame', 'uno', 'mas', '', 1, 1, '2020-01-03 23:32:27', NULL, NULL, NULL, 'A'),
(5, '425689', 'jorge', 'perez', 'garcia', '', 4, 1, '2020-01-04 14:43:31', NULL, NULL, NULL, 'A'),
(6, '123456', '', '', '', '', NULL, 1, '2020-01-04 15:12:20', 1, '2020-01-04 10:12:55', '2020-01-04 10:21:26', 'I'),
(7, '45896589', 'david', 'chavez', 'gharcia', '', 3, 1, '2020-01-04 15:19:52', NULL, NULL, NULL, 'A'),
(8, '12345678', 'demos ', 'registro', 'pedir', '', 5, 1, '2020-01-10 00:05:48', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

DROP TABLE IF EXISTS `platos`;
CREATE TABLE IF NOT EXISTS `platos` (
  `codplto` int(11) NOT NULL AUTO_INCREMENT,
  `tipopto` int(11) NOT NULL COMMENT '1 menu,2 sopas,3 refresco,4 verduras cocidas,5 verduras crudas',
  `nomplto` varchar(180) NOT NULL,
  `imgplto` varchar(120) DEFAULT NULL,
  `descplto` text,
  `usucrplto` int(11) NOT NULL,
  `fcrplto` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usumdplto` int(11) DEFAULT NULL,
  `fmdplto` datetime DEFAULT NULL,
  `fdelplto` datetime DEFAULT NULL,
  `estrgplto` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codplto`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`codplto`, `tipopto`, `nomplto`, `imgplto`, `descplto`, `usucrplto`, `fcrplto`, `usumdplto`, `fmdplto`, `fdelplto`, `estrgplto`) VALUES
(1, 1, 'Arroz con pollo', 'arroz_con_pollo.png', '', 1, '2020-01-02 05:00:00', NULL, NULL, NULL, 'A'),
(2, 1, 'ceviche', 'ceviche.jpg', '', 2, '2020-01-04 18:31:25', NULL, NULL, NULL, 'A'),
(3, 1, 'arroz chaufa', 'arroz_chaufa.jpg', '', 2, '2020-01-04 18:33:16', 2, '2020-01-04 13:37:24', '2020-01-04 13:39:54', 'A'),
(4, 1, 'seco de res con frejoles', 'seco_res_frejoles.jpg', '', 2, '2020-01-04 18:44:20', 2, '2020-01-04 13:45:30', '2020-01-04 13:44:35', 'A'),
(5, 1, 'Malaya con yuda dorada', '', '', 2, '2020-01-04 18:47:44', NULL, NULL, NULL, 'A'),
(6, 1, 'Arroz a la cubana', '', '', 2, '2020-01-04 18:48:07', NULL, NULL, NULL, 'A'),
(7, 1, 'pescado frito con yuca', '', '', 2, '2020-01-04 18:48:34', NULL, NULL, NULL, 'A'),
(8, 1, 'pure con pollo al horno', '', '', 2, '2020-01-04 18:49:41', NULL, NULL, NULL, 'A'),
(9, 1, 'olloquito de carne', '', '', 2, '2020-01-04 18:50:00', NULL, NULL, NULL, 'A'),
(10, 1, 'picante de carne', '', '', 2, '2020-01-04 18:50:29', NULL, NULL, NULL, 'A'),
(11, 1, 'aji de gallina', '', '', 2, '2020-01-04 18:51:08', NULL, NULL, NULL, 'A'),
(12, 1, 'cau cau de pollo', '', '', 2, '2020-01-04 18:53:04', NULL, NULL, NULL, 'A'),
(13, 1, 'guiso de quinua con filete de pollo', '', '', 2, '2020-01-04 18:54:33', NULL, NULL, NULL, 'A'),
(14, 2, 'moron', '', '', 2, '2020-01-08 17:05:27', NULL, NULL, NULL, 'A'),
(15, 4, 'emoliente', '', '', 2, '2020-01-08 17:05:46', NULL, NULL, NULL, 'A'),
(16, 5, 'Platano', '', '', 2, '2020-01-08 17:05:58', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE IF NOT EXISTS `sucursal` (
  `codsuc` int(11) NOT NULL AUTO_INCREMENT,
  `nomsuc` varchar(120) NOT NULL,
  `diresuc` varchar(160) DEFAULT NULL,
  `usucrsuc` int(11) NOT NULL,
  `fcrsuc` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usumdsuc` int(11) DEFAULT NULL,
  `fmdsuc` datetime DEFAULT NULL,
  `fdelsuc` datetime DEFAULT NULL,
  `estrgsuc` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codsuc`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`codsuc`, `nomsuc`, `diresuc`, `usucrsuc`, `fcrsuc`, `usumdsuc`, `fmdsuc`, `fdelsuc`, `estrgsuc`) VALUES
(1, 'Arriola 17', NULL, 1, '2020-01-04 14:26:39', NULL, NULL, NULL, 'A'),
(2, 'Arriola 14', NULL, 1, '2020-01-04 14:26:39', NULL, NULL, NULL, 'A'),
(3, 'Arriola 15', NULL, 1, '2020-01-04 14:26:39', NULL, NULL, NULL, 'A'),
(4, 'Ate', NULL, 1, '2020-01-04 14:26:39', NULL, NULL, NULL, 'A'),
(5, 'Los Olivos', NULL, 1, '2020-01-04 14:26:39', NULL, NULL, NULL, 'A'),
(6, 'Tapiceros', NULL, 1, '2020-01-04 14:26:39', NULL, NULL, NULL, 'A'),
(7, 'Chamorro', NULL, 1, '2020-01-04 14:26:39', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoalimento`
--

DROP TABLE IF EXISTS `tipoalimento`;
CREATE TABLE IF NOT EXISTS `tipoalimento` (
  `codtali` int(11) NOT NULL AUTO_INCREMENT,
  `nomali` varchar(160) NOT NULL,
  `usucrali` int(11) NOT NULL,
  `fcrali` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usumdali` int(11) DEFAULT NULL,
  `fmdali` datetime DEFAULT NULL,
  `fdelali` datetime DEFAULT NULL,
  `estrgali` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codtali`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipoalimento`
--

INSERT INTO `tipoalimento` (`codtali`, `nomali`, `usucrali`, `fcrali`, `usumdali`, `fmdali`, `fdelali`, `estrgali`) VALUES
(1, 'Menu', 1, '2020-01-04 15:38:45', NULL, NULL, NULL, 'A'),
(2, 'Sopa', 1, '2020-01-04 15:38:45', NULL, NULL, NULL, 'A'),
(3, 'Dieta', 1, '2020-01-04 15:38:45', NULL, NULL, NULL, 'A'),
(4, 'Refresco', 1, '2020-01-04 15:38:45', NULL, NULL, NULL, 'A'),
(5, 'Postre', 1, '2020-01-04 15:38:45', NULL, NULL, NULL, 'A'),
(6, 'Verduras Cocidas', 1, '2020-01-04 15:38:45', NULL, NULL, NULL, 'A'),
(7, 'Verduras crudas', 1, '2020-01-04 15:38:45', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `codusu` int(11) NOT NULL AUTO_INCREMENT,
  `emailusu` varchar(180) NOT NULL,
  `usuausu` varchar(15) NOT NULL,
  `passusu` varchar(32) NOT NULL,
  `prfusu` int(11) DEFAULT NULL COMMENT '0 admin sist, 1 administrador,2 Chef',
  `fcrusu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usucrusu` int(11) NOT NULL,
  `fmdusu` datetime DEFAULT NULL,
  `usumdusu` int(11) DEFAULT NULL,
  `fdelusu` datetime DEFAULT NULL,
  `estrgusu` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codusu`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`codusu`, `emailusu`, `usuausu`, `passusu`, `prfusu`, `fcrusu`, `usucrusu`, `fmdusu`, `usumdusu`, `fdelusu`, `estrgusu`) VALUES
(1, 'admin@mym.pe', 'sistema', 'a141c47927929bc2d1fb6d336a256df4', 0, '2020-01-02 14:35:11', 0, NULL, NULL, NULL, 'A'),
(2, 'cocina@mym.pe', 'Cocina', 'e99a18c428cb38d5f260853678922e03', 1, '2020-01-03 17:23:42', 1, '2020-01-03 12:54:25', 1, NULL, 'A'),
(3, 'chef@mym.pe', 'chef', '613d3b9c91e9445abaeca02f2342e5a6', 2, '2020-01-03 17:40:17', 1, NULL, NULL, '2020-01-03 13:02:56', 'A');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
