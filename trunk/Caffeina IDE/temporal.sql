-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 04-07-2011 a las 23:04:54
-- Versión del servidor: 5.1.54
-- Versión de PHP: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `BDTorneos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DetalleEdicion`
--

CREATE TABLE IF NOT EXISTS `DetalleEdicion` (
  `IDEdicion` int(11) NOT NULL,
  `IDEquipo` int(11) NOT NULL,
  `FechaSolicitud` date DEFAULT NULL,
  `FechaInscripcion` date DEFAULT NULL,
  `Alta` tinyint(1) DEFAULT NULL,
  `Ganados` int(11) DEFAULT '0',
  `Perdidos` int(11) DEFAULT '0',
  `Empatados` int(11) DEFAULT '0',
  `Amarillas` int(11) DEFAULT '0',
  `Rojas` int(11) DEFAULT '0',
  `GolesFavor` int(11) DEFAULT '0',
  `GolesContra` int(11) DEFAULT '0',
  PRIMARY KEY (`IDEdicion`,`IDEquipo`),
  KEY `IDEquipo` (`IDEquipo`),
  KEY `IDEdicion` (`IDEdicion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DetalleEquipo`
--

CREATE TABLE IF NOT EXISTS `DetalleEquipo` (
  `IDEquipo` int(11) NOT NULL,
  `IDJugador` int(11) NOT NULL,
  `NumJUgador` int(11) DEFAULT NULL,
  `Posicion` varchar(25) DEFAULT NULL,
  `FechaIngreso` date DEFAULT NULL,
  `Titular` tinyint(1) DEFAULT '0',
  `Aceptado` tinyint(1) DEFAULT '0',
  `JuegosSancionados` int(11) DEFAULT '0',
  PRIMARY KEY (`IDEquipo`,`IDJugador`),
  KEY `IDEquipo` (`IDEquipo`),
  KEY `IDJugador` (`IDJugador`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DetalleGrupo`
--

CREATE TABLE IF NOT EXISTS `DetalleGrupo` (
  `IDGrupo` int(11) NOT NULL,
  `IDEquipo` int(11) NOT NULL,
  `Puntos` int(11) DEFAULT '0',
  PRIMARY KEY (`IDGrupo`,`IDEquipo`),
  KEY `IDEquipo` (`IDEquipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EdicionTorneo`
--

CREATE TABLE IF NOT EXISTS `EdicionTorneo` (
  `IDEdicion` int(11) NOT NULL AUTO_INCREMENT,
  `IDTorneo` int(11) NOT NULL,
  `NumEquipos` int(11) DEFAULT NULL,
  `FechaInicio` date DEFAULT NULL,
  `FechaFin` date DEFAULT NULL,
  `Edicion` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDEdicion`,`IDTorneo`),
  KEY `IDTorneo` (`IDTorneo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Equipo`
--

CREATE TABLE IF NOT EXISTS `Equipo` (
  `IDEquipo` int(11) NOT NULL AUTO_INCREMENT,
  `IDDirector` int(11) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `Region` varchar(50) DEFAULT NULL,
  `Campeonatos` int(11) DEFAULT '0',
  PRIMARY KEY (`IDEquipo`),
  UNIQUE KEY `Nombre` (`Nombre`),
  KEY `IDDirector` (`IDDirector`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EventosPartido`
--

CREATE TABLE IF NOT EXISTS `EventosPartido` (
  `IDEvento` int(11) NOT NULL AUTO_INCREMENT,
  `IDPartido` int(11) NOT NULL,
  `IDEquipo` int(11) NOT NULL,
  `IDJugador` int(11) NOT NULL,
  `Evento` int(11) NOT NULL,
  `Minuto` float NOT NULL,
  `Tarjeta` int(11) DEFAULT NULL,
  `IDNuevoJugador` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDEvento`,`IDPartido`),
  KEY `IDJugador` (`IDJugador`),
  KEY `IDNuevoJugador` (`IDNuevoJugador`),
  KEY `IDPartido` (`IDPartido`),
  KEY `IDEquipo` (`IDEquipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Grupos`
--

CREATE TABLE IF NOT EXISTS `Grupos` (
  `IDGrupo` int(11) NOT NULL AUTO_INCREMENT,
  `IDEdicionTorneo` int(11) NOT NULL,
  `Grupo` char(1) NOT NULL,
  PRIMARY KEY (`IDGrupo`),
  KEY `IDEdicionTorneo` (`IDEdicionTorneo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Jornada`
--

CREATE TABLE IF NOT EXISTS `Jornada` (
  `IDJornada` int(11) NOT NULL AUTO_INCREMENT,
  `IDEdicionTorneo` int(11) NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaFin` date NOT NULL,
  `Numero` int(11) NOT NULL,
  PRIMARY KEY (`IDJornada`),
  KEY `IDEdicionTorneo` (`IDEdicionTorneo`),
  KEY `Numero` (`Numero`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Partido`
--

CREATE TABLE IF NOT EXISTS `Partido` (
  `IDPartido` int(11) NOT NULL AUTO_INCREMENT,
  `IDJornada` int(11) NOT NULL,
  `IDEquipoLocal` int(11) NOT NULL,
  `IDEquipoVisitante` int(11) NOT NULL,
  `IDArbrito` int(11) NOT NULL,
  `Estadio` varchar(50) DEFAULT NULL,
  `NumPartido` int(11) NOT NULL,
  `GolLocal` int(11) DEFAULT NULL,
  `GolVisitante` int(11) DEFAULT NULL,
  `FechaPartido` date DEFAULT NULL,
  `TieExtra` tinyint(1) DEFAULT NULL,
  `Penalties` tinyint(1) DEFAULT NULL,
  `Jugado` tinyint(1) DEFAULT '0',
  `Ronda` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDPartido`,`IDJornada`),
  KEY `IDJornada` (`IDJornada`),
  KEY `IDEquipoLocal` (`IDEquipoLocal`),
  KEY `IDEquipoVisitante` (`IDEquipoVisitante`),
  KEY `IDArbrito` (`IDArbrito`),
  KEY `IDPartido` (`IDPartido`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipoTorneo`
--

CREATE TABLE IF NOT EXISTS `TipoTorneo` (
  `IDTipoTorneo` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(35) DEFAULT NULL,
  `NumJugadores` int(11) DEFAULT NULL,
  `NumTiempos` int(11) DEFAULT NULL,
  `TiempoJuego` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDTipoTorneo`),
  UNIQUE KEY `Nombre` (`Nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Torneo`
--

CREATE TABLE IF NOT EXISTS `Torneo` (
  `IDTorneo` int(11) NOT NULL AUTO_INCREMENT,
  `IDTipoTorneo` int(11) DEFAULT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `Ciudad` varchar(30) DEFAULT NULL,
  `Ediciones` int(11) DEFAULT '0',
  `NumMaxEquipos` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDTorneo`),
  UNIQUE KEY `Nombre` (`Nombre`),
  KEY `IDTipoTorneo` (`IDTipoTorneo`),
  KEY `IDTorneo` (`IDTorneo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE IF NOT EXISTS `Usuarios` (
  `IDUser` int(11) NOT NULL AUTO_INCREMENT,
  `TipoUsuario` int(11) DEFAULT '0',
  `Nombre` varchar(50) DEFAULT NULL,
  `ApePaterno` varchar(30) DEFAULT NULL,
  `ApeMaterno` varchar(30) DEFAULT NULL,
  `Email` varchar(40) NOT NULL,
  `NickName` varchar(12) NOT NULL,
  `Pass` varchar(20) NOT NULL,
  `Ciudad` varchar(30) DEFAULT NULL,
  `Colonia` varchar(30) DEFAULT NULL,
  `Edad` int(11) DEFAULT NULL,
  `Foto` blob,
  PRIMARY KEY (`IDUser`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `NickName` (`NickName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `DetalleEdicion`
--
ALTER TABLE `DetalleEdicion`
  ADD CONSTRAINT `DetalleEdicion_ibfk_1` FOREIGN KEY (`IDEdicion`) REFERENCES `EdicionTorneo` (`IDEdicion`),
  ADD CONSTRAINT `DetalleEdicion_ibfk_2` FOREIGN KEY (`IDEquipo`) REFERENCES `Equipo` (`IDEquipo`);

--
-- Filtros para la tabla `DetalleEquipo`
--
ALTER TABLE `DetalleEquipo`
  ADD CONSTRAINT `DetalleEquipo_ibfk_1` FOREIGN KEY (`IDEquipo`) REFERENCES `Equipo` (`IDEquipo`),
  ADD CONSTRAINT `DetalleEquipo_ibfk_2` FOREIGN KEY (`IDJugador`) REFERENCES `Usuarios` (`IDUser`);

--
-- Filtros para la tabla `DetalleGrupo`
--
ALTER TABLE `DetalleGrupo`
  ADD CONSTRAINT `DetalleGrupo_ibfk_1` FOREIGN KEY (`IDGrupo`) REFERENCES `Grupos` (`IDGrupo`),
  ADD CONSTRAINT `DetalleGrupo_ibfk_2` FOREIGN KEY (`IDEquipo`) REFERENCES `Equipo` (`IDEquipo`);

--
-- Filtros para la tabla `EdicionTorneo`
--
ALTER TABLE `EdicionTorneo`
  ADD CONSTRAINT `EdicionTorneo_ibfk_1` FOREIGN KEY (`IDTorneo`) REFERENCES `Torneo` (`IDTorneo`);

--
-- Filtros para la tabla `Equipo`
--
ALTER TABLE `Equipo`
  ADD CONSTRAINT `Equipo_ibfk_1` FOREIGN KEY (`IDDirector`) REFERENCES `Usuarios` (`IDUser`);

--
-- Filtros para la tabla `EventosPartido`
--
ALTER TABLE `EventosPartido`
  ADD CONSTRAINT `EventosPartido_ibfk_1` FOREIGN KEY (`IDPartido`) REFERENCES `Partido` (`IDPartido`),
  ADD CONSTRAINT `EventosPartido_ibfk_2` FOREIGN KEY (`IDEquipo`) REFERENCES `Equipo` (`IDEquipo`),
  ADD CONSTRAINT `EventosPartido_ibfk_3` FOREIGN KEY (`IDJugador`) REFERENCES `Usuarios` (`IDUser`),
  ADD CONSTRAINT `EventosPartido_ibfk_4` FOREIGN KEY (`IDNuevoJugador`) REFERENCES `Usuarios` (`IDUser`);

--
-- Filtros para la tabla `Grupos`
--
ALTER TABLE `Grupos`
  ADD CONSTRAINT `Grupos_ibfk_1` FOREIGN KEY (`IDEdicionTorneo`) REFERENCES `EdicionTorneo` (`IDEdicion`);

--
-- Filtros para la tabla `Jornada`
--
ALTER TABLE `Jornada`
  ADD CONSTRAINT `Jornada_ibfk_1` FOREIGN KEY (`IDEdicionTorneo`) REFERENCES `EdicionTorneo` (`IDEdicion`);

--
-- Filtros para la tabla `Partido`
--
ALTER TABLE `Partido`
  ADD CONSTRAINT `Partido_ibfk_1` FOREIGN KEY (`IDJornada`) REFERENCES `Jornada` (`IDJornada`),
  ADD CONSTRAINT `Partido_ibfk_2` FOREIGN KEY (`IDEquipoLocal`) REFERENCES `Equipo` (`IDEquipo`),
  ADD CONSTRAINT `Partido_ibfk_3` FOREIGN KEY (`IDEquipoVisitante`) REFERENCES `Equipo` (`IDEquipo`),
  ADD CONSTRAINT `Partido_ibfk_4` FOREIGN KEY (`IDArbrito`) REFERENCES `Usuarios` (`IDUser`);

--
-- Filtros para la tabla `Torneo`
--
ALTER TABLE `Torneo`
  ADD CONSTRAINT `Torneo_ibfk_1` FOREIGN KEY (`IDTipoTorneo`) REFERENCES `TipoTorneo` (`IDTipoTorneo`);
