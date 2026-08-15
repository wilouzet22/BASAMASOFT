<?php
/**
 * ProfesorModel
 * Maneja todo lo relacionado con la tabla `profesores`.
 */
class ProfesorModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Busca un profesor por username.
     */
    public function findByUsername($username) {
        $this->db->query('SELECT * FROM profesores WHERE username = :username LIMIT 1');
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    /**
     * Obtiene los grupos asignados a un profesor con detalle de grado y sede.
     */
    public function getGruposByProfesor($id_profesor) {
        $this->db->query(
            'SELECT g.*, gr.nombre_grado, s.nombre_sede
             FROM grupos g
             INNER JOIN profesor_grupo pg ON g.id_grupo = pg.id_grupo_fk
             INNER JOIN grados gr ON g.id_grado_fk = gr.id_grado
             INNER JOIN sedes s ON g.id_sede_fk = s.id_sede
             WHERE pg.id_profesor_fk = :id_profesor
             ORDER BY g.nombre_grupo ASC'
        );
        $this->db->bind(':id_profesor', $id_profesor);
        return $this->db->resultSet();
    }

    /**
     * Obtiene las actividades creadas por un profesor (con tipo, sede y grupos).
     */
    public function getActividadesByProfesor($id_profesor) {
        $this->db->query(
            'SELECT a.*, ta.nombre_tipo, s.nombre_sede,
                    GROUP_CONCAT(g.nombre_grupo SEPARATOR ", ") AS grupos
             FROM actividades a
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
             LEFT JOIN actividad_grupo ag ON a.id_actividad = ag.id_actividad_fk
             LEFT JOIN grupos g ON ag.id_grupo_fk = g.id_grupo
             WHERE a.creada_por_profesor_fk = :id_profesor
             GROUP BY a.id_actividad
             ORDER BY a.fecha_hora_inicio DESC'
        );
        $this->db->bind(':id_profesor', $id_profesor);
        return $this->db->resultSet();
    }

    /**
     * Obtiene todas las actividades (para profesores con acceso general).
     */
    public function getAllActividades() {
        $this->db->query(
            'SELECT a.*, ta.nombre_tipo, s.nombre_sede,
                    CONCAT(p.nombres, " ", p.apellidos) AS profesor_nombre,
                    GROUP_CONCAT(g.nombre_grupo SEPARATOR ", ") AS grupos
             FROM actividades a
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
             LEFT JOIN profesores p ON a.creada_por_profesor_fk = p.id_profesor
             LEFT JOIN actividad_grupo ag ON a.id_actividad = ag.id_actividad_fk
             LEFT JOIN grupos g ON ag.id_grupo_fk = g.id_grupo
             GROUP BY a.id_actividad
             ORDER BY a.fecha_hora_inicio DESC'
        );
        return $this->db->resultSet();
    }

    /**
     * Obtiene los estudiantes de los grupos asignados a un profesor.
     */
    public function getEstudiantesByProfesor($id_profesor) {
        $this->db->query(
            'SELECT DISTINCT e.*, g.nombre_grupo, gr.nombre_grado
             FROM estudiantes e
             INNER JOIN grupos g ON e.id_grupo_fk = g.id_grupo
             INNER JOIN grados gr ON g.id_grado_fk = gr.id_grado
             INNER JOIN profesor_grupo pg ON g.id_grupo = pg.id_grupo_fk
             WHERE pg.id_profesor_fk = :id_profesor
             ORDER BY e.apellidos ASC'
        );
        $this->db->bind(':id_profesor', $id_profesor);
        return $this->db->resultSet();
    }

    /**
     * Obtiene el resumen de asistencia registrada por un profesor.
     */
    public function getAsistenciasByProfesor($id_profesor, $limit = 20) {
        $this->db->query(
            'SELECT asi.*, 
                    CONCAT(e.nombres, " ", e.apellidos) AS estudiante_nombre,
                    g.nombre_grupo,
                    gr.nombre_grado,
                    a.nombre_actividad,
                    a.fecha_hora_inicio
             FROM asistencia asi
             INNER JOIN estudiantes e ON asi.id_estudiante_fk = e.id_estudiante
             INNER JOIN grupos g ON e.id_grupo_fk = g.id_grupo
             INNER JOIN grados gr ON g.id_grado_fk = gr.id_grado
             INNER JOIN actividades a ON asi.id_actividad_fk = a.id_actividad
             WHERE asi.registrada_por_profesor_fk = :id_profesor
             ORDER BY asi.fecha_registro DESC
             LIMIT :limit'
        );
        $this->db->bind(':id_profesor', $id_profesor);
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Cuenta asistencias registradas por un profesor.
     */
    public function countAsistenciasByProfesor($id_profesor) {
        $this->db->query(
            'SELECT COUNT(*) AS total FROM asistencia WHERE registrada_por_profesor_fk = :id_profesor'
        );
        $this->db->bind(':id_profesor', $id_profesor);
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    /**
     * Cuenta actividades creadas por un profesor.
     */
    public function countActividadesByProfesor($id_profesor) {
        $this->db->query(
            'SELECT COUNT(*) AS total FROM actividades WHERE creada_por_profesor_fk = :id_profesor'
        );
        $this->db->bind(':id_profesor', $id_profesor);
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    /**
     * Obtiene los mensajes de contacto enviados a este profesor o enviados por este profesor.
     */
    public function getMensajesContacto($id_profesor) {
        $this->db->query(
            'SELECT m.*, f.nombre_principal_acudiente, f.apellidos_principal_acudiente, f.email_contacto
             FROM mensajes_contacto m
             INNER JOIN familias f ON m.id_familia_fk = f.id_familia
             WHERE (m.id_profesor_fk = :id_profesor AND m.destinatario_tipo = "profesor")
                OR (m.id_profesor_fk = :id_profesor2 AND m.remitente_tipo = "profesor")
             ORDER BY m.fecha_envio DESC'
        );
        $this->db->bind(':id_profesor', $id_profesor);
        $this->db->bind(':id_profesor2', $id_profesor);
        return $this->db->resultSet();
    }

    /**
     * Marca un mensaje como leído.
     */
    public function marcarMensajeLeido($id_mensaje) {
        $this->db->query('UPDATE mensajes_contacto SET leido = 1 WHERE id_mensaje = :id_mensaje AND destinatario_tipo = "profesor"');
        $this->db->bind(':id_mensaje', $id_mensaje);
        return $this->db->execute();
    }

    /**
     * Guarda un mensaje nuevo enviado por el profesor a una familia.
     */
    public function enviarMensajeFamilia($id_profesor, $id_familia, $titulo, $asunto, $mensaje) {
        $this->db->query('INSERT INTO mensajes_contacto (id_familia_fk, remitente_tipo, destinatario_tipo, id_profesor_fk, titulo, asunto, mensaje) VALUES (:id_familia, "profesor", "familia", :id_prof, :titulo, :asunto, :mensaje)');
        $this->db->bind(':id_familia', $id_familia);
        $this->db->bind(':id_prof', $id_profesor);
        $this->db->bind(':titulo', $titulo);
        $this->db->bind(':asunto', $asunto);
        $this->db->bind(':mensaje', $mensaje);
        return $this->db->execute();
    }

    /**
     * Obtiene las familias de los estudiantes que están en los grupos del profesor.
     */
    public function getFamiliasByProfesor($id_profesor) {
        $this->db->query(
            'SELECT DISTINCT f.id_familia, f.nombre_principal_acudiente, f.apellidos_principal_acudiente
             FROM familias f
             INNER JOIN familia_estudiante fe ON f.id_familia = fe.id_familia_fk
             INNER JOIN estudiantes e ON fe.id_estudiante_fk = e.id_estudiante
             INNER JOIN profesor_grupo pg ON e.id_grupo_fk = pg.id_grupo_fk
             WHERE pg.id_profesor_fk = :id_profesor
             ORDER BY f.apellidos_principal_acudiente ASC'
        );
        $this->db->bind(':id_profesor', $id_profesor);
        return $this->db->resultSet();
    }

    /**
     * Elimina un mensaje (ya sea enviado o recibido por el profesor).
     */
    public function eliminarMensaje($id_mensaje, $id_profesor) {
        $this->db->query('DELETE FROM mensajes_contacto WHERE id_mensaje = :id_mensaje AND id_profesor_fk = :id_profesor');
        $this->db->bind(':id_mensaje', $id_mensaje);
        $this->db->bind(':id_profesor', $id_profesor);
        return $this->db->execute();
    }
}
