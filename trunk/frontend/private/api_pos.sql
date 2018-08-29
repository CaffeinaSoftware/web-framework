-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generaciÃ³n: 29-08-2018 a las 00:03:15
-- VersiÃ³n del servidor: 5.6.31-0ubuntu0.15.10.1
-- VersiÃ³n de PHP: 5.6.11-1ubuntu3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `framework`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `argumento`
--

CREATE TABLE `argumento` (
  `id_argumento` int(11) NOT NULL,
  `id_metodo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `ahuevo` tinyint(1) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `defaults` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion`
--

CREATE TABLE `clasificacion` (
  `id_clasificacion` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `grupo` varchar(64) NOT NULL,
  `usuario` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantis_category_table`
--

CREATE TABLE `mantis_category_table` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL DEFAULT '',
  `status` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantis_project_file_table`
--

CREATE TABLE `mantis_project_file_table` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `diskfile` varchar(250) NOT NULL DEFAULT '',
  `filename` varchar(250) NOT NULL DEFAULT '',
  `folder` varchar(250) NOT NULL DEFAULT '',
  `filesize` int(11) NOT NULL DEFAULT '0',
  `file_type` varchar(250) NOT NULL DEFAULT '',
  `content` longblob NOT NULL,
  `date_added` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantis_project_hierarchy_table`
--

CREATE TABLE `mantis_project_hierarchy_table` (
  `child_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `inherit_parent` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantis_project_table`
--

CREATE TABLE `mantis_project_table` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL DEFAULT '',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  `view_state` smallint(6) NOT NULL DEFAULT '10',
  `access_min` smallint(6) NOT NULL DEFAULT '10',
  `file_path` varchar(250) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `inherit_global` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantis_project_user_list_table`
--

CREATE TABLE `mantis_project_user_list_table` (
  `project_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `access_level` smallint(6) NOT NULL DEFAULT '10'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantis_project_version_table`
--

CREATE TABLE `mantis_project_version_table` (
  `id` int(11) NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `version` varchar(64) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `released` tinyint(4) NOT NULL DEFAULT '1',
  `obsolete` tinyint(4) NOT NULL DEFAULT '0',
  `date_order` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo`
--

CREATE TABLE `metodo` (
  `id_metodo` int(11) NOT NULL,
  `id_clasificacion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `sesion_valida` tinyint(1) NOT NULL,
  `grupo` int(11) NOT NULL,
  `ejemplo_peticion` text NOT NULL,
  `ejemplo_respuesta` text NOT NULL,
  `descripcion` text NOT NULL,
  `subtitulo` varchar(100) NOT NULL,
  `regresa_html` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `id_registro` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `id_clasificacion` int(11) DEFAULT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `usuario` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `operacion` enum('editar','agregar','borrar','generar') NOT NULL COMMENT 'Operacion que se realizo',
  `descripcion` varchar(250) NOT NULL COMMENT 'Descripcion de la operacion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `id_respuesta` int(11) NOT NULL,
  `id_metodo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `usuario` varchar(64) NOT NULL,
  `mail` varchar(128) NOT NULL,
  `pass` varchar(1024) NOT NULL,
  `empleado` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Ãndices para tablas volcadas
--

--
-- Indices de la tabla `argumento`
--
ALTER TABLE `argumento`
  ADD PRIMARY KEY (`id_argumento`),
  ADD KEY `id_metodo` (`id_metodo`);

--
-- Indices de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  ADD PRIMARY KEY (`id_clasificacion`),
  ADD KEY `id_proyecto` (`id_proyecto`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD UNIQUE KEY `grupo` (`grupo`,`usuario`);

--
-- Indices de la tabla `mantis_category_table`
--
ALTER TABLE `mantis_category_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_category_project_name` (`project_id`,`name`);

--
-- Indices de la tabla `mantis_project_file_table`
--
ALTER TABLE `mantis_project_file_table`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mantis_project_hierarchy_table`
--
ALTER TABLE `mantis_project_hierarchy_table`
  ADD KEY `idx_project_hierarchy_child_id` (`child_id`),
  ADD KEY `idx_project_hierarchy_parent_id` (`parent_id`);

--
-- Indices de la tabla `mantis_project_table`
--
ALTER TABLE `mantis_project_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_project_name` (`name`),
  ADD KEY `idx_project_view` (`view_state`);

--
-- Indices de la tabla `mantis_project_user_list_table`
--
ALTER TABLE `mantis_project_user_list_table`
  ADD PRIMARY KEY (`project_id`,`user_id`),
  ADD KEY `idx_project_user` (`user_id`);

--
-- Indices de la tabla `mantis_project_version_table`
--
ALTER TABLE `mantis_project_version_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_project_version` (`project_id`,`version`);

--
-- Indices de la tabla `metodo`
--
ALTER TABLE `metodo`
  ADD PRIMARY KEY (`id_metodo`),
  ADD KEY `id_clasificacion` (`id_clasificacion`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_proyecto` (`id_proyecto`,`id_clasificacion`,`id_metodo`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `id_metodo` (`id_metodo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `argumento`
--
ALTER TABLE `argumento`
  MODIFY `id_argumento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16912;
--
-- AUTO_INCREMENT de la tabla `clasificacion`
--
ALTER TABLE `clasificacion`
  MODIFY `id_clasificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT de la tabla `mantis_category_table`
--
ALTER TABLE `mantis_category_table`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT de la tabla `mantis_project_file_table`
--
ALTER TABLE `mantis_project_file_table`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `mantis_project_table`
--
ALTER TABLE `mantis_project_table`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT de la tabla `mantis_project_version_table`
--
ALTER TABLE `mantis_project_version_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT de la tabla `metodo`
--
ALTER TABLE `metodo`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=462;
--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=876;
--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1292;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

