-- Ejecutar una sola vez en instalaciones existentes antes de desplegar el cambio.
-- En instalaciones nuevas, las columnas ya están incluidas en edusaftV1.sql.
ALTER TABLE actividades
    ADD COLUMN IF NOT EXISTS imagen_principal VARCHAR(500) NULL AFTER requiere_asistencia_por_hijo;

ALTER TABLE actividades
    ADD COLUMN IF NOT EXISTS fotos LONGTEXT NULL AFTER imagen_principal;

-- Conserva como principal la primera foto de actividades que ya tenían galería.
UPDATE actividades
SET imagen_principal = JSON_UNQUOTE(JSON_EXTRACT(fotos, '$[0]'))
WHERE imagen_principal IS NULL
  AND JSON_VALID(fotos)
  AND JSON_LENGTH(fotos) > 0;

-- Corrige las URL generadas por versiones anteriores del cargador.
UPDATE actividades
SET imagen_principal = REPLACE(imagen_principal, '/assets/img/actividades/', '/public/assets/img/actividades/'),
    fotos = REPLACE(fotos, '/assets/img/actividades/', '/public/assets/img/actividades/');
