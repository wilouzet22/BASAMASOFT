<?php
/**
 * AsistenciaModel
 * Maneja todo lo relacionado con la tabla `asistencia`.
 */
class AsistenciaModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Obtiene todos los registros de asistencia con detalles completos.
     */
    public function getAll($limit = 50) {
        $this->db->query(
            'SELECT asi.*,
                    CONCAT(e.nombres, " ", e.apellidos) AS estudiante_nombre,
                    g.nombre_grupo,
                    gr.nombre_grado,
                    a.nombre_actividad,
                    a.fecha_hora_inicio,
                    ta.nombre_tipo,
                    s.nombre_sede,
                    CONCAT(p.nombres, " ", p.apellidos) AS profesor_nombre,
                    f.nombre_principal_acudiente AS familia_nombre,
                    f.apellidos_principal_acudiente AS familia_apellidos
             FROM asistencia asi
             INNER JOIN estudiantes e ON asi.id_estudiante_fk = e.id_estudiante
             INNER JOIN grupos g ON e.id_grupo_fk = g.id_grupo
             INNER JOIN grados gr ON g.id_grado_fk = gr.id_grado
             INNER JOIN actividades a ON asi.id_actividad_fk = a.id_actividad
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
             INNER JOIN profesores p ON asi.registrada_por_profesor_fk = p.id_profesor
             INNER JOIN familias f ON asi.id_familia_fk = f.id_familia
             ORDER BY asi.fecha_registro DESC
             LIMIT :limit'
        );
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Registra una nueva asistencia.
     */
    public function registrar($id_actividad, $id_familia, $id_estudiante, $id_profesor, $presente = 1) {
        $this->db->query(
            'INSERT INTO asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, registrada_por_profesor_fk, presente)
             VALUES (:id_actividad, :id_familia, :id_estudiante, :id_profesor, :presente)'
        );
        $this->db->bind(':id_actividad',  $id_actividad);
        $this->db->bind(':id_familia',    $id_familia);
        $this->db->bind(':id_estudiante', $id_estudiante);
        $this->db->bind(':id_profesor',   $id_profesor);
        $this->db->bind(':presente',      (int)$presente);
        return $this->db->execute();
    }

    /**
     * Cuenta el total de registros presentes en la base de datos.
     */
    public function countPresentes() {
        $this->db->query('SELECT COUNT(*) AS total FROM asistencia WHERE presente = 1');
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    /**
     * Cuenta el total de ausentes.
     */
    public function countAusentes() {
        $this->db->query('SELECT COUNT(*) AS total FROM asistencia WHERE presente = 0');
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }
}
