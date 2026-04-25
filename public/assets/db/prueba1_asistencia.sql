-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 27-10-2025 a las 22:47:33
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prueba1_asistencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id_actividad` int NOT NULL,
  `nombre_actividad` varchar(255) NOT NULL,
  `descripcion` text,
  `fecha_hora_inicio` datetime NOT NULL,
  `fecha_hora_fin` datetime DEFAULT NULL,
  `id_tipo_actividad_fk` int NOT NULL,
  `id_sede_fk` int NOT NULL,
  `creada_por_profesor_fk` int DEFAULT NULL,
  `requiere_asistencia_por_hijo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_grupo`
--

CREATE TABLE `actividad_grupo` (
  `id_actividad_grupo` int NOT NULL,
  `id_actividad_fk` int NOT NULL,
  `id_grupo_fk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int NOT NULL,
  `id_actividad_fk` int NOT NULL,
  `id_familia_fk` int NOT NULL,
  `id_estudiante_fk` int NOT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `registrada_por_profesor_fk` int NOT NULL,
  `presente` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `documento_identidad` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `id_grupo_fk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familias`
--

CREATE TABLE `familias` (
  `id_familia` int NOT NULL,
  `nombre_principal_acudiente` varchar(100) NOT NULL,
  `apellidos_principal_acudiente` varchar(100) NOT NULL,
  `documento_principal_acudiente` varchar(20) NOT NULL,
  `email_contacto` varchar(100) NOT NULL,
  `telefono_contacto` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familia_estudiante`
--

CREATE TABLE `familia_estudiante` (
  `id_familia_estudiante` int NOT NULL,
  `id_familia_fk` int NOT NULL,
  `id_estudiante_fk` int NOT NULL,
  `parentesco` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id_grado` int NOT NULL,
  `nombre_grado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id_grupo` int NOT NULL,
  `nombre_grupo` varchar(50) NOT NULL,
  `id_grado_fk` int NOT NULL,
  `id_sede_fk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id_profesor` int NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `documento_identidad` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_grupo`
--

CREATE TABLE `profesor_grupo` (
  `id_profesor_grupo` int NOT NULL,
  `id_profesor_fk` int NOT NULL,
  `id_grupo_fk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

CREATE TABLE `sedes` (
  `id_sede` int NOT NULL,
  `nombre_sede` varchar(100) NOT NULL,
  `direccion_sede` varchar(255) DEFAULT NULL,
  `telefono_sede` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_actividad`
--

CREATE TABLE `tipos_actividad` (
  `id_tipo_actividad` int NOT NULL,
  `nombre_tipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id_actividad`),
  ADD UNIQUE KEY `nombre_actividad` (`nombre_actividad`,`fecha_hora_inicio`,`id_sede_fk`),
  ADD KEY `fk_actividad_tipo` (`id_tipo_actividad_fk`),
  ADD KEY `fk_actividad_sede` (`id_sede_fk`),
  ADD KEY `fk_actividad_creador` (`creada_por_profesor_fk`);

--
-- Indices de la tabla `actividad_grupo`
--
ALTER TABLE `actividad_grupo`
  ADD PRIMARY KEY (`id_actividad_grupo`),
  ADD UNIQUE KEY `id_actividad_fk` (`id_actividad_fk`,`id_grupo_fk`),
  ADD KEY `fk_ag_grupo` (`id_grupo_fk`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD UNIQUE KEY `id_actividad_fk` (`id_actividad_fk`,`id_familia_fk`,`id_estudiante_fk`),
  ADD KEY `fk_asistencia_familia` (`id_familia_fk`),
  ADD KEY `fk_asistencia_estudiante` (`id_estudiante_fk`),
  ADD KEY `fk_asistencia_registrador` (`registrada_por_profesor_fk`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`),
  ADD KEY `fk_estudiante_grupo` (`id_grupo_fk`);

--
-- Indices de la tabla `familias`
--
ALTER TABLE `familias`
  ADD PRIMARY KEY (`id_familia`),
  ADD UNIQUE KEY `documento_principal_acudiente` (`documento_principal_acudiente`),
  ADD UNIQUE KEY `email_contacto` (`email_contacto`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `familia_estudiante`
--
ALTER TABLE `familia_estudiante`
  ADD PRIMARY KEY (`id_familia_estudiante`),
  ADD UNIQUE KEY `id_familia_fk` (`id_familia_fk`,`id_estudiante_fk`),
  ADD KEY `fk_fe_estudiante` (`id_estudiante_fk`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id_grado`),
  ADD UNIQUE KEY `nombre_grado` (`nombre_grado`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `nombre_grupo` (`nombre_grupo`,`id_sede_fk`),
  ADD KEY `fk_grupo_grado` (`id_grado_fk`),
  ADD KEY `fk_grupo_sede` (`id_sede_fk`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id_profesor`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `profesor_grupo`
--
ALTER TABLE `profesor_grupo`
  ADD PRIMARY KEY (`id_profesor_grupo`),
  ADD UNIQUE KEY `id_profesor_fk` (`id_profesor_fk`,`id_grupo_fk`),
  ADD KEY `fk_pg_grupo` (`id_grupo_fk`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`id_sede`),
  ADD UNIQUE KEY `nombre_sede` (`nombre_sede`);

--
-- Indices de la tabla `tipos_actividad`
--
ALTER TABLE `tipos_actividad`
  ADD PRIMARY KEY (`id_tipo_actividad`),
  ADD UNIQUE KEY `nombre_tipo` (`nombre_tipo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id_actividad` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `actividad_grupo`
--
ALTER TABLE `actividad_grupo`
  MODIFY `id_actividad_grupo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `familias`
--
ALTER TABLE `familias`
  MODIFY `id_familia` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `familia_estudiante`
--
ALTER TABLE `familia_estudiante`
  MODIFY `id_familia_estudiante` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id_profesor` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesor_grupo`
--
ALTER TABLE `profesor_grupo`
  MODIFY `id_profesor_grupo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id_sede` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_actividad`
--
ALTER TABLE `tipos_actividad`
  MODIFY `id_tipo_actividad` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `fk_actividad_creador` FOREIGN KEY (`creada_por_profesor_fk`) REFERENCES `profesores` (`id_profesor`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_actividad_sede` FOREIGN KEY (`id_sede_fk`) REFERENCES `sedes` (`id_sede`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_actividad_tipo` FOREIGN KEY (`id_tipo_actividad_fk`) REFERENCES `tipos_actividad` (`id_tipo_actividad`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `actividad_grupo`
--
ALTER TABLE `actividad_grupo`
  ADD CONSTRAINT `fk_ag_actividad` FOREIGN KEY (`id_actividad_fk`) REFERENCES `actividades` (`id_actividad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ag_grupo` FOREIGN KEY (`id_grupo_fk`) REFERENCES `grupos` (`id_grupo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `fk_asistencia_actividad` FOREIGN KEY (`id_actividad_fk`) REFERENCES `actividades` (`id_actividad`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistencia_estudiante` FOREIGN KEY (`id_estudiante_fk`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistencia_familia` FOREIGN KEY (`id_familia_fk`) REFERENCES `familias` (`id_familia`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistencia_registrador` FOREIGN KEY (`registrada_por_profesor_fk`) REFERENCES `profesores` (`id_profesor`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `fk_estudiante_grupo` FOREIGN KEY (`id_grupo_fk`) REFERENCES `grupos` (`id_grupo`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `familia_estudiante`
--
ALTER TABLE `familia_estudiante`
  ADD CONSTRAINT `fk_fe_estudiante` FOREIGN KEY (`id_estudiante_fk`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fe_familia` FOREIGN KEY (`id_familia_fk`) REFERENCES `familias` (`id_familia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `fk_grupo_grado` FOREIGN KEY (`id_grado_fk`) REFERENCES `grados` (`id_grado`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grupo_sede` FOREIGN KEY (`id_sede_fk`) REFERENCES `sedes` (`id_sede`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesor_grupo`
--
ALTER TABLE `profesor_grupo`
  ADD CONSTRAINT `fk_pg_grupo` FOREIGN KEY (`id_grupo_fk`) REFERENCES `grupos` (`id_grupo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pg_profesor` FOREIGN KEY (`id_profesor_fk`) REFERENCES `profesores` (`id_profesor`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
