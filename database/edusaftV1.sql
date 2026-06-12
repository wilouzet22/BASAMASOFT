-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 31-05-2026 a las 22:42:22
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

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id_actividad`, `nombre_actividad`, `descripcion`, `fecha_hora_inicio`, `fecha_hora_fin`, `id_tipo_actividad_fk`, `id_sede_fk`, `creada_por_profesor_fk`, `requiere_asistencia_por_hijo`) VALUES
(1, 'Clase de Matemáticas Integrales', 'Introducción al cálculo de áreas', '2026-06-01 07:00:00', '2026-06-01 09:00:00', 1, 1, 1, 1),
(2, 'Primera Entrega de Informes 2026', 'Entrega del segundo periodo académico', '2026-06-05 16:30:00', '2026-06-05 18:30:00', 2, 2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_grupo`
--

CREATE TABLE `actividad_grupo` (
  `id_actividad_grupo` int NOT NULL,
  `id_actividad_fk` int NOT NULL,
  `id_grupo_fk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `actividad_grupo`
--

INSERT INTO `actividad_grupo` (`id_actividad_grupo`, `id_actividad_fk`, `id_grupo_fk`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_administrador` int NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_administrador`, `nombres`, `apellidos`, `correo`, `password_hash`, `telefono`) VALUES
(1, 'Carlos', 'Restrepo', 'carlos.admin@institucion.edu.co', '/q+Oi8pUMHTu1/c9IqWXZg==', '3001234567');

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

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id_asistencia`, `id_actividad_fk`, `id_familia_fk`, `id_estudiante_fk`, `fecha_registro`, `registrada_por_profesor_fk`, `presente`) VALUES
(1, 1, 2, 1, '2026-06-01 07:15:00', 1, 1),
(2, 2, 1, 2, '2026-06-05 16:45:00', 2, 1);

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

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `nombres`, `apellidos`, `documento_identidad`, `fecha_nacimiento`, `id_grupo_fk`) VALUES
(1, 'Santiago', 'Tobón', '1001555444', '2009-05-14', 1),
(2, 'Mateo', 'Guzmán', '1002666777', '2008-11-22', 2);

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

--
-- Volcado de datos para la tabla `familias`
--

INSERT INTO `familias` (`id_familia`, `nombre_principal_acudiente`, `apellidos_principal_acudiente`, `documento_principal_acudiente`, `email_contacto`, `telefono_contacto`, `username`, `password_hash`) VALUES
(1, 'Martha', 'Guzmán', '32555666', 'martha.guzman@mail.com', '3159998877', 'mguzman', '/q+Oi8pUMHTu1/c9IqWXZg=='),
(2, 'Jorge', 'Tobón', '98444333', 'jorge.tobon@mail.com', '3017776655', 'jtobon', '/q+Oi8pUMHTu1/c9IqWXZg==');

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

--
-- Volcado de datos para la tabla `familia_estudiante`
--

INSERT INTO `familia_estudiante` (`id_familia_estudiante`, `id_familia_fk`, `id_estudiante_fk`, `parentesco`) VALUES
(1, 2, 1, 'Padre'),
(2, 1, 2, 'Madre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id_grado` int NOT NULL,
  `nombre_grado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id_grado`, `nombre_grado`) VALUES
(1, 'Décimo (10°)'),
(2, 'Once (11°)');

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

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre_grupo`, `id_grado_fk`, `id_sede_fk`) VALUES
(1, 'Grupo 10-A', 1, 1),
(2, 'Grupo 11-B', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id_log` int NOT NULL,
  `id_administrador_fk` int DEFAULT NULL,
  `id_profesor_fk` int DEFAULT NULL,
  `id_familia_fk` int DEFAULT NULL,
  `id_rol_fk` int NOT NULL,
  `accion_realizada` varchar(255) NOT NULL,
  `ip_direccion` varchar(45) NOT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `log`
--

INSERT INTO `log` (`id_log`, `id_administrador_fk`, `id_profesor_fk`, `id_familia_fk`, `id_rol_fk`, `accion_realizada`, `ip_direccion`, `timestamp`) VALUES
(1, 1, NULL, NULL, 1, 'Creación de nuevo usuario Profesor id_profesor=2 en el sistema', '192.168.1.50', '2026-05-31 17:39:08'),
(2, NULL, 1, NULL, 2, 'Registro de asistencia completado para la actividad id=1', '192.168.1.62', '2026-05-31 17:39:08'),
(3, NULL, NULL, 1, 3, 'Ingreso al portal de acudientes para consultar boletín', '181.134.45.12', '2026-05-31 17:39:08');

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

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id_profesor`, `nombres`, `apellidos`, `documento_identidad`, `email`, `telefono`, `username`, `password_hash`) VALUES
(1, 'Andrés', 'Mendoza', '71234567', 'andres.mendoza@institucion.edu.co', '3115556677', 'amendoza', '/q+Oi8pUMHTu1/c9IqWXZg=='),
(2, 'Beatriz', 'Elena', '43987654', 'beatriz.elena@institucion.edu.co', '3124445566', 'belena', '/q+Oi8pUMHTu1/c9IqWXZg==');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_grupo`
--

CREATE TABLE `profesor_grupo` (
  `id_profesor_grupo` int NOT NULL,
  `id_profesor_fk` int NOT NULL,
  `id_grupo_fk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `profesor_grupo`
--

INSERT INTO `profesor_grupo` (`id_profesor_grupo`, `id_profesor_fk`, `id_grupo_fk`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Control total de la configuración institucional y usuarios.'),
(2, 'Profesor', 'Gestión de grupos, creación de actividades y toma de asistencias.'),
(3, 'Acudiente/Familia', 'Visualización de reportes de asistencia de sus respectivos hijos.');

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

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`id_sede`, `nombre_sede`, `direccion_sede`, `telefono_sede`) VALUES
(1, 'Sede Principal', 'Calle 60 # 45-12', '6042345678'),
(2, 'Sede Juvenil', 'Carrera 70 # 12-34', '6048765432');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_actividad`
--

CREATE TABLE `tipos_actividad` (
  `id_tipo_actividad` int NOT NULL,
  `nombre_tipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tipos_actividad`
--

INSERT INTO `tipos_actividad` (`id_tipo_actividad`, `nombre_tipo`) VALUES
(1, 'Clase Regular'),
(3, 'Evento Cultural/Deportivo'),
(2, 'Reunión de Padres');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id_actividad`),
  ADD UNIQUE KEY `nombre_actividad` (`nombre_actividad`,`fecha_hora_inicio`,`id_sede_fk`),
  ADD KEY `fk_actividad_creador` (`creada_por_profesor_fk`),
  ADD KEY `fk_actividad_sede` (`id_sede_fk`),
  ADD KEY `fk_actividad_tipo` (`id_tipo_actividad_fk`);

--
-- Indices de la tabla `actividad_grupo`
--
ALTER TABLE `actividad_grupo`
  ADD PRIMARY KEY (`id_actividad_grupo`),
  ADD UNIQUE KEY `id_actividad_fk` (`id_actividad_fk`,`id_grupo_fk`),
  ADD KEY `fk_ag_grupo` (`id_grupo_fk`);

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_administrador`),
  ADD UNIQUE KEY `uk_correo_admin` (`correo`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD UNIQUE KEY `id_actividad_fk` (`id_actividad_fk`,`id_familia_fk`,`id_estudiante_fk`),
  ADD KEY `fk_asistencia_estudiante` (`id_estudiante_fk`),
  ADD KEY `fk_asistencia_familia` (`id_familia_fk`),
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
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_log_rol` (`id_rol_fk`),
  ADD KEY `fk_log_admin` (`id_administrador_fk`),
  ADD KEY `fk_log_profesor` (`id_profesor_fk`),
  ADD KEY `fk_log_familia` (`id_familia_fk`);

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
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `uk_nombre_rol` (`nombre`);

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
  MODIFY `id_actividad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `actividad_grupo`
--
ALTER TABLE `actividad_grupo`
  MODIFY `id_actividad_grupo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_administrador` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `familias`
--
ALTER TABLE `familias`
  MODIFY `id_familia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `familia_estudiante`
--
ALTER TABLE `familia_estudiante`
  MODIFY `id_familia_estudiante` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id_profesor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `profesor_grupo`
--
ALTER TABLE `profesor_grupo`
  MODIFY `id_profesor_grupo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id_sede` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipos_actividad`
--
ALTER TABLE `tipos_actividad`
  MODIFY `id_tipo_actividad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Filtros para la tabla `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_admin` FOREIGN KEY (`id_administrador_fk`) REFERENCES `administrador` (`id_administrador`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_log_familia` FOREIGN KEY (`id_familia_fk`) REFERENCES `familias` (`id_familia`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_log_profesor` FOREIGN KEY (`id_profesor_fk`) REFERENCES `profesores` (`id_profesor`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_log_rol` FOREIGN KEY (`id_rol_fk`) REFERENCES `roles` (`id_rol`) ON DELETE RESTRICT ON UPDATE CASCADE;

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
