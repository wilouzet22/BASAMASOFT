<?php
/**
 * FamiliaModel
 * Maneja todo lo relacionado con la tabla `familias` y sus estudiantes asociados.
 */
class FamiliaModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Busca una familia por username.
     */
    public function findByUsername($username) {
        $this->db->query('SELECT * FROM familias WHERE username = :username LIMIT 1');
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    /**
     * Obtiene los estudiantes (hijos) asociados a una familia, con grado y grupo.
     */
    public function getEstudiantesByFamilia($id_familia) {
        $this->db->query(
            'SELECT e.*, g.nombre_grupo, gr.nombre_grado, s.nombre_sede, fe.parentesco
             FROM familia_estudiante fe
             INNER JOIN estudiantes e ON fe.id_estudiante_fk = e.id_estudiante
             INNER JOIN grupos g ON e.id_grupo_fk = g.id_grupo
             INNER JOIN grados gr ON g.id_grado_fk = gr.id_grado
             INNER JOIN sedes s ON g.id_sede_fk = s.id_sede
             WHERE fe.id_familia_fk = :id_familia
             ORDER BY e.apellidos ASC'
        );
        $this->db->bind(':id_familia', $id_familia);
        return $this->db->resultSet();
    }

    /**
     * Obtiene el historial de asistencia de los estudiantes de una familia.
     */
    public function getAsistenciasByFamilia($id_familia, $limit = 20) {
        $this->db->query(
            'SELECT asi.*,
                    CONCAT(e.nombres, " ", e.apellidos) AS estudiante_nombre,
                    g.nombre_grupo,
                    gr.nombre_grado,
                    a.nombre_actividad,
                    a.fecha_hora_inicio,
                    ta.nombre_tipo,
                    s.nombre_sede,
                    CONCAT(p.nombres, " ", p.apellidos) AS profesor_nombre
             FROM asistencia asi
             INNER JOIN estudiantes e ON asi.id_estudiante_fk = e.id_estudiante
             INNER JOIN grupos g ON e.id_grupo_fk = g.id_grupo
             INNER JOIN grados gr ON g.id_grado_fk = gr.id_grado
             INNER JOIN actividades a ON asi.id_actividad_fk = a.id_actividad
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
             INNER JOIN profesores p ON asi.registrada_por_profesor_fk = p.id_profesor
             WHERE asi.id_familia_fk = :id_familia
             ORDER BY asi.fecha_registro DESC
             LIMIT :limit'
        );
        $this->db->bind(':id_familia', $id_familia);
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Calcula el porcentaje de asistencia de un estudiante específico.
     * Retorna un objeto con total, presentes y porcentaje.
     */
    public function getEstadisticasEstudiante($id_estudiante, $id_familia) {
        $this->db->query(
            'SELECT
                COUNT(*) AS total,
                SUM(presente) AS presentes
             FROM asistencia
             WHERE id_estudiante_fk = :id_estudiante
               AND id_familia_fk = :id_familia'
        );
        $this->db->bind(':id_estudiante', $id_estudiante);
        $this->db->bind(':id_familia', $id_familia);
        $row = $this->db->single();

        $total    = $row ? (int)$row->total : 0;
        $presentes= $row ? (int)$row->presentes : 0;
        $porcentaje = ($total > 0) ? round(($presentes / $total) * 100) : 0;

        return (object)[
            'total'      => $total,
            'presentes'  => $presentes,
            'ausentes'   => $total - $presentes,
            'porcentaje' => $porcentaje,
        ];
    }

    /**
     * Obtiene las próximas actividades de los grupos de los estudiantes de la familia.
     */
    public function getProximasActividades($id_familia) {
        $this->db->query(
            'SELECT DISTINCT a.*, ta.nombre_tipo, s.nombre_sede,
                    GROUP_CONCAT(g.nombre_grupo SEPARATOR ", ") AS grupos
             FROM actividades a
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
             INNER JOIN actividad_grupo ag ON a.id_actividad = ag.id_actividad_fk
             INNER JOIN grupos g ON ag.id_grupo_fk = g.id_grupo
             INNER JOIN estudiantes e ON g.id_grupo = e.id_grupo_fk
             INNER JOIN familia_estudiante fe ON e.id_estudiante = fe.id_estudiante_fk
             WHERE fe.id_familia_fk = :id_familia
               AND a.fecha_hora_inicio >= NOW()
             GROUP BY a.id_actividad
             ORDER BY a.fecha_hora_inicio ASC
             LIMIT 5'
        );
        $this->db->bind(':id_familia', $id_familia);
        return $this->db->resultSet();
    }
}
