<?php
/**
 * ActividadModel
 * Maneja las consultas de actividades/eventos para la interfaz pública (modo visitante).
 */
class ActividadModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Obtiene las próximas actividades para el modo visitante.
     * Prioriza actividades futuras (las más cercanas primero)
     * y luego incluye actividades pasadas (las más recientes primero).
     */
    public function getProximasActividadesVisitante($limit = 3) {
        $this->db->query(
            'SELECT a.*, ta.nombre_tipo, s.nombre_sede
             FROM actividades a
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
             ORDER BY 
                 CASE WHEN a.fecha_hora_inicio >= NOW() THEN 0 ELSE 1 END, 
                 CASE WHEN a.fecha_hora_inicio >= NOW() THEN a.fecha_hora_inicio END ASC,
                 CASE WHEN a.fecha_hora_inicio < NOW() THEN a.fecha_hora_inicio END DESC
             LIMIT :limit'
        );
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Obtiene todas las actividades para el calendario de visitantes.
     */
    public function getAllActividadesVisitante() {
        $this->db->query(
            'SELECT a.*, ta.nombre_tipo, s.nombre_sede
             FROM actividades a
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
             ORDER BY a.fecha_hora_inicio ASC'
        );
        return $this->db->resultSet();
    }
}
