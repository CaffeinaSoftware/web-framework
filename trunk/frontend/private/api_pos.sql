-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 16-02-2012 a las 18:30:34
-- Versión del servidor: 5.1.53
-- Versión de PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `api_pos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `argumento`
--

CREATE TABLE IF NOT EXISTS `argumento` (
  `id_argumento` int(11) NOT NULL AUTO_INCREMENT,
  `id_metodo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `ahuevo` tinyint(1) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `defaults` varchar(50) NOT NULL,
  PRIMARY KEY (`id_argumento`),
  KEY `id_metodo` (`id_metodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13273 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion`
--

CREATE TABLE IF NOT EXISTS `clasificacion` (
  `id_clasificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_proyecto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id_clasificacion`),
  KEY `id_proyecto` (`id_proyecto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `httptesting_paquete_de_pruebas`
--

CREATE TABLE IF NOT EXISTS `httptesting_paquete_de_pruebas` (
  `id_paquete_de_pruebas` int(11) NOT NULL AUTO_INCREMENT,
  `id_proyecto` int(11) NOT NULL COMMENT 'Proyecto al que pertenece esta prueba',
  `pruebas` longtext NOT NULL COMMENT 'Texto de pruebas',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del paquete',
  `descripcion` varchar(256) NOT NULL COMMENT 'Descripcion del paquete',
  `locked` tinyint(1) NOT NULL COMMENT 'Si el paquete puede ser editado o no',
  PRIMARY KEY (`id_paquete_de_pruebas`),
  KEY `id_proyecto` (`id_proyecto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `httptesting_ruta`
--

CREATE TABLE IF NOT EXISTS `httptesting_ruta` (
  `id_ruta` int(11) NOT NULL AUTO_INCREMENT,
  `id_proyecto` int(11) NOT NULL COMMENT 'Id del proyecto al qe pertenece esta ruta',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la ruta',
  `ruta` varchar(100) NOT NULL COMMENT 'Ruta explicita',
  PRIMARY KEY (`id_ruta`),
  KEY `id_proyecto` (`id_proyecto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo`
--

CREATE TABLE IF NOT EXISTS `metodo` (
  `id_metodo` int(11) NOT NULL AUTO_INCREMENT,
  `id_clasificacion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `sesion_valida` tinyint(1) NOT NULL,
  `grupo` int(11) NOT NULL,
  `ejemplo_peticion` text NOT NULL,
  `ejemplo_respuesta` text NOT NULL,
  `descripcion` text NOT NULL,
  `subtitulo` varchar(100) NOT NULL,
  `regresa_html` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_metodo`),
  KEY `id_clasificacion` (`id_clasificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=320 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto`
--

CREATE TABLE IF NOT EXISTS `proyecto` (
  `id_proyecto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL COMMENT 'nombre del proyecto',
  `descripcion` varchar(260) NOT NULL COMMENT 'descricpion del proyecto',
  PRIMARY KEY (`id_proyecto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabla de proyectos' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE IF NOT EXISTS `respuesta` (
  `id_respuesta` int(11) NOT NULL AUTO_INCREMENT,
  `id_metodo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo` varchar(30) NOT NULL,
  PRIMARY KEY (`id_respuesta`),
  KEY `id_metodo` (`id_metodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=888 ;

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `argumento`
--
ALTER TABLE `argumento`
  ADD CONSTRAINT `argumento_ibfk_1` FOREIGN KEY (`id_metodo`) REFERENCES `metodo` (`id_metodo`);

--
-- Filtros para la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  ADD CONSTRAINT `clasificacion_ibfk_1` FOREIGN KEY (`id_proyecto`) REFERENCES `proyecto` (`id_proyecto`);

--
-- Filtros para la tabla `httptesting_paquete_de_pruebas`
--
ALTER TABLE `httptesting_paquete_de_pruebas`
  ADD CONSTRAINT `httptesting_paquete_de_pruebas_ibfk_1` FOREIGN KEY (`id_proyecto`) REFERENCES `proyecto` (`id_proyecto`);

--
-- Filtros para la tabla `metodo`
--
ALTER TABLE `metodo`
  ADD CONSTRAINT `metodo_ibfk_1` FOREIGN KEY (`id_clasificacion`) REFERENCES `clasificacion` (`id_clasificacion`);

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `respuesta_ibfk_1` FOREIGN KEY (`id_metodo`) REFERENCES `metodo` (`id_metodo`);
