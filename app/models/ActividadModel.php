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

    /**
     * Obtiene todos los tipos de actividad.
     */
    public function getAllTiposActividad() {
        $this->db->query('SELECT * FROM tipos_actividad ORDER BY nombre_tipo ASC');
        return $this->db->resultSet();
    }

    /**
     * Obtiene todos los grupos registrados.
     */
    public function getAllGrupos() {
        $this->db->query('SELECT * FROM grupos ORDER BY nombre_grupo ASC');
        return $this->db->resultSet();
    }

    /**
     * Obtiene una actividad por su ID.
     */
    public function getActividadById($id_actividad) {
        $this->db->query(
            'SELECT a.*, ta.nombre_tipo, s.nombre_sede
             FROM actividades a
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
             WHERE a.id_actividad = :id'
        );
        $this->db->bind(':id', $id_actividad);
        return $this->db->single();
    }

    /**
     * Crea una nueva actividad.
     */
    public function crearActividad($data) {
        $this->db->query('INSERT INTO actividades (nombre_actividad, descripcion, fecha_hora_inicio, fecha_hora_fin, id_tipo_actividad_fk, id_sede_fk, requiere_asistencia_por_hijo, imagen_principal, creada_por_profesor_fk) VALUES (:nombre_actividad, :descripcion, :fecha_hora_inicio, :fecha_hora_fin, :id_tipo_actividad_fk, :id_sede_fk, :requiere_asistencia_por_hijo, :imagen_principal, :creada_por_profesor_fk)');
        
        $this->db->bind(':nombre_actividad', $data['nombre_actividad']);
        $this->db->bind(':descripcion', $data['descripcion'] ?? null);
        $this->db->bind(':fecha_hora_inicio', $data['fecha_hora_inicio']);
        $this->db->bind(':fecha_hora_fin', $data['fecha_hora_fin'] ?? null);
        $this->db->bind(':id_tipo_actividad_fk', $data['id_tipo_actividad_fk']);
        $this->db->bind(':id_sede_fk', $data['id_sede_fk']);
        $this->db->bind(':requiere_asistencia_por_hijo', $data['requiere_asistencia_por_hijo'] ?? 1);
        $this->db->bind(':imagen_principal', $data['imagen_principal'] ?? null);
        $this->db->bind(':creada_por_profesor_fk', $data['creada_por_profesor_fk'] ?? null);

        try {
            if ($this->db->execute()) {
                return $this->db->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                return false;
            }
            throw $e;
        }
    }

    /**
     * Asigna grupos a una actividad (para actividades de grupo).
     */
    public function asignarGruposAActividad($id_actividad, $grupos) {
        if (empty($grupos)) return true;

        $success = true;
        foreach ($grupos as $id_grupo) {
            $this->db->query('INSERT INTO actividad_grupo (id_actividad_fk, id_grupo_fk) VALUES (:id_actividad_fk, :id_grupo_fk)');
            $this->db->bind(':id_actividad_fk', $id_actividad);
            $this->db->bind(':id_grupo_fk', $id_grupo);
            if (!$this->db->execute()) {
                $success = false;
            }
        }
        return $success;
    }

/** Registra la URL de la imagen principal directamente en la actividad. */
    public function establecerImagenPrincipal($id_actividad, $ruta_foto, $id_profesor = null) {
        if ($id_profesor === null) {
            $this->db->query(
                'SELECT id_actividad FROM actividades
                 WHERE id_actividad = :id
                   AND COALESCE(fecha_hora_fin, TIMESTAMP(DATE(fecha_hora_inicio), "23:59:59")) < NOW()'
            );
            $this->db->bind(':id', $id_actividad);
        } else {
            $this->db->query(
                'SELECT a.id_actividad FROM actividades a
                 LEFT JOIN actividad_grupo ag ON a.id_actividad = ag.id_actividad_fk
                 LEFT JOIN profesor_grupo pg ON ag.id_grupo_fk = pg.id_grupo_fk
                 WHERE a.id_actividad = :id
                   AND COALESCE(a.fecha_hora_fin, TIMESTAMP(DATE(a.fecha_hora_inicio), "23:59:59")) < NOW()
                   AND (a.creada_por_profesor_fk = :id_profesor OR pg.id_profesor_fk = :id_profesor)
                 GROUP BY a.id_actividad'
            );
            $this->db->bind(':id', $id_actividad);
            $this->db->bind(':id_profesor', $id_profesor);
        }
        $act = $this->db->single();
        if (!$act) return false;

        $this->db->query(
            'UPDATE actividades
             SET imagen_principal = :imagen_principal
             WHERE id_actividad = :id'
        );
        $this->db->bind(':imagen_principal', $ruta_foto);
        $this->db->bind(':id', $id_actividad);
        return $this->db->execute();
    }
}
