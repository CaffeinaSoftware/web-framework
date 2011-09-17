-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 17-09-2011 a las 19:45:04
-- Versión del servidor: 5.1.53
-- Versión de PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `mini_programa`
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
  `default` varchar(50) NOT NULL,
  PRIMARY KEY (`id_argumento`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `argumento`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo`
--

CREATE TABLE IF NOT EXISTS `metodo` (
  `id_metodo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `sesion_valida` tinyint(1) NOT NULL,
  `grupo` int(11) NOT NULL,
  `ejemplo_peticion` text NOT NULL,
  `ejemplo_respuesta` text NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id_metodo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `metodo`
--


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
  PRIMARY KEY (`id_respuesta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `respuesta`
--

