<?php
/**
 * AdministradorModel
 * Maneja todo lo relacionado con la tabla `administrador` y estadísticas globales.
 */
class AdministradorModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Busca un administrador por correo electrónico.
     */
    public function findByCorreo($correo) {
        $this->db->query('SELECT * FROM administrador WHERE correo = :correo LIMIT 1');
        $this->db->bind(':correo', $correo);
        return $this->db->single();
    }

    /**
     * Obtiene el total de profesores registrados.
     */
    public function countProfesores() {
        $this->db->query('SELECT COUNT(*) AS total FROM profesores');
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    /**
     * Obtiene el total de familias (acudientes) registradas.
     */
    public function countFamilias() {
        $this->db->query('SELECT COUNT(*) AS total FROM familias');
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    /**
     * Obtiene el total de estudiantes registrados.
     */
    public function countEstudiantes() {
        $this->db->query('SELECT COUNT(*) AS total FROM estudiantes');
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    /**
     * Obtiene el total de sedes.
     */
    public function countSedes() {
        $this->db->query('SELECT COUNT(*) AS total FROM sedes');
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    /**
     * Obtiene el total de actividades.
     */
    public function countActividades() {
        $this->db->query('SELECT COUNT(*) AS total FROM actividades');
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    /**
     * Obtiene el total de registros de asistencia.
     */
    public function countAsistencias() {
        $this->db->query('SELECT COUNT(*) AS total FROM asistencia');
        $row = $this->db->single();
        return $row ? (int)$row->total : 0;
    }

    /**
     * Obtiene los últimos registros del log del sistema.
     */
    public function getRecentLogs($limit = 10) {
        $this->db->query(
            'SELECT l.*, r.nombre AS rol_nombre,
                    a.nombres AS admin_nombre, a.apellidos AS admin_apellidos,
                    p.nombres AS prof_nombre, p.apellidos AS prof_apellidos,
                    f.nombre_principal_acudiente AS familia_nombre
             FROM log l
             LEFT JOIN roles r ON l.id_rol_fk = r.id_rol
             LEFT JOIN administrador a ON l.id_administrador_fk = a.id_administrador
             LEFT JOIN profesores p ON l.id_profesor_fk = p.id_profesor
             LEFT JOIN familias f ON l.id_familia_fk = f.id_familia
             ORDER BY l.timestamp DESC
             LIMIT :limit'
        );
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Lista todos los profesores.
     */
    public function getAllProfesores() {
        $this->db->query(
            'SELECT p.*, GROUP_CONCAT(g.nombre_grupo SEPARATOR ", ") AS grupos
             FROM profesores p
             LEFT JOIN profesor_grupo pg ON p.id_profesor = pg.id_profesor_fk
             LEFT JOIN grupos g ON pg.id_grupo_fk = g.id_grupo
             GROUP BY p.id_profesor
             ORDER BY p.apellidos ASC'
        );
        return $this->db->resultSet();
    }

    /**
     * Lista todas las familias con sus estudiantes asociados.
     */
    public function getAllFamilias() {
        $this->db->query(
            'SELECT f.*,
                    GROUP_CONCAT(CONCAT(e.nombres, " ", e.apellidos) SEPARATOR ", ") AS estudiantes
             FROM familias f
             LEFT JOIN familia_estudiante fe ON f.id_familia = fe.id_familia_fk
             LEFT JOIN estudiantes e ON fe.id_estudiante_fk = e.id_estudiante
             GROUP BY f.id_familia
             ORDER BY f.apellidos_principal_acudiente ASC'
        );
        return $this->db->resultSet();
    }

    /**
     * Lista todos los estudiantes con su grado y grupo.
     */
    public function getAllEstudiantes() {
        $this->db->query(
            'SELECT e.*, g.nombre_grupo, gr.nombre_grado
             FROM estudiantes e
             LEFT JOIN grupos g ON e.id_grupo_fk = g.id_grupo
             LEFT JOIN grados gr ON g.id_grado_fk = gr.id_grado
             ORDER BY e.apellidos ASC'
        );
        return $this->db->resultSet();
    }

    /**
     * Lista todas las sedes.
     */
    public function getAllSedes() {
        $this->db->query('SELECT * FROM sedes ORDER BY nombre_sede ASC');
        return $this->db->resultSet();
    }
}
