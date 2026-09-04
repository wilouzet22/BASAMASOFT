-- Tabla para códigos QR de asistencia únicos (uso único)
-- Ejecutar una sola vez en instalaciones existentes.
-- En instalaciones nuevas, incluir en edusaftV1.sql.

CREATE TABLE IF NOT EXISTS `qr_asistencia` (
  `id_qr` INT NOT NULL AUTO_INCREMENT,
  `id_actividad_fk` INT NOT NULL,
  `id_familia_fk` INT NOT NULL,
  `id_estudiante_fk` INT NOT NULL,
  `token` VARCHAR(64) NOT NULL COMMENT 'Token único del QR (hash)',
  `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expira_en` DATETIME NOT NULL COMMENT 'Fecha/hora de expiración del QR',
  `usado` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=pendiente, 1=usado',
  `usado_en` DATETIME DEFAULT NULL COMMENT 'Cuándo se escaneó y registró asistencia',
  PRIMARY KEY (`id_qr`),
  UNIQUE KEY `uk_token` (`token`),
  KEY `idx_actividad` (`id_actividad_fk`),
  KEY `idx_familia` (`id_familia_fk`),
  KEY `idx_estudiante` (`id_estudiante_fk`),
  KEY `idx_expira` (`expira_en`),
  CONSTRAINT `fk_qr_actividad` FOREIGN KEY (`id_actividad_fk`) REFERENCES `actividades` (`id_actividad`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_familia` FOREIGN KEY (`id_familia_fk`) REFERENCES `familias` (`id_familia`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_estudiante` FOREIGN KEY (`id_estudiante_fk`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;