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
     * Busca una familia por ID.
     */
    public function findById($id_familia) {
        $this->db->query('SELECT * FROM familias WHERE id_familia = :id LIMIT 1');
        $this->db->bind(':id', $id_familia);
        return $this->db->single();
    }

    /**
     * Actualiza la foto de perfil de la familia.
     */
    public function actualizarFoto($id_familia, $filename) {
        $this->db->query('UPDATE familias SET foto_perfil = :foto WHERE id_familia = :id');
        $this->db->bind(':foto', $filename);
        $this->db->bind(':id', $id_familia);
        return $this->db->execute();
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

    /**
     * Obtiene todas las actividades (pasadas y futuras) de los grupos de los estudiantes de la familia,
     * incluyendo si la familia asistió o no (presente = 1).
     */
    public function getAllActividadesPath($id_familia) {
        $this->db->query(
            'SELECT a.*, ta.nombre_tipo, s.nombre_sede,
                    (SELECT COUNT(*) 
                     FROM asistencia asi 
                     WHERE asi.id_actividad_fk = a.id_actividad 
                     AND asi.id_familia_fk = :id_familia 
                     AND asi.presente = 1) AS asistencia_registrada
             FROM actividades a
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
             ORDER BY a.fecha_hora_inicio ASC'
        );
        $this->db->bind(':id_familia', $id_familia);
        return $this->db->resultSet();
    }

    /**
     * Retorna asistencias agrupadas por tipo de actividad (para gráfica de torta).
     */
    public function getAsistenciaPorTipo($id_familia) {
        $this->db->query(
            'SELECT ta.nombre_tipo,
                    COUNT(*) AS total,
                    SUM(asi.presente) AS asistidas
             FROM asistencia asi
             INNER JOIN actividades a ON asi.id_actividad_fk = a.id_actividad
             INNER JOIN tipos_actividad ta ON a.id_tipo_actividad_fk = ta.id_tipo_actividad
             WHERE asi.id_familia_fk = :id_familia
             GROUP BY ta.nombre_tipo
             ORDER BY asistidas DESC'
        );
        $this->db->bind(':id_familia', $id_familia);
        return $this->db->resultSet();
    }

    /**
     * Retorna asistencias agrupadas mes a mes (últimos 6 meses).
     */
    public function getAsistenciaPorMes($id_familia) {
        $this->db->query(
            'SELECT DATE_FORMAT(a.fecha_hora_inicio, "%Y-%m") AS mes,
                    DATE_FORMAT(a.fecha_hora_inicio, "%b %Y") AS mes_label,
                    COUNT(*) AS total,
                    SUM(asi.presente) AS asistidas
             FROM asistencia asi
             INNER JOIN actividades a ON asi.id_actividad_fk = a.id_actividad
             WHERE asi.id_familia_fk = :id_familia
               AND a.fecha_hora_inicio >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY mes, mes_label
             ORDER BY mes ASC'
        );
        $this->db->bind(':id_familia', $id_familia);
        return $this->db->resultSet();
    }

    /**
     * Estadísticas globales de asistencia de la familia.
     */
    public function getEstadisticasGlobales($id_familia) {
        $this->db->query(
            'SELECT
                COUNT(*) AS total,
                SUM(presente) AS asistidas,
                SUM(CASE WHEN presente = 0 THEN 1 ELSE 0 END) AS inasistencias
             FROM asistencia
             WHERE id_familia_fk = :id_familia'
        );
        $this->db->bind(':id_familia', $id_familia);
        $row = $this->db->single();
        $total       = $row ? (int)$row->total : 0;
        $asistidas   = $row ? (int)$row->asistidas : 0;
        $inasistencias = $row ? (int)$row->inasistencias : 0;
        $porcentaje  = $total > 0 ? round(($asistidas / $total) * 100) : 0;
        return (object)[
            'total'        => $total,
            'asistidas'    => $asistidas,
            'inasistencias'=> $inasistencias,
            'porcentaje'   => $porcentaje,
        ];
    }

    /**
     * Racha actual: actividades consecutivas asistidas contando desde
     * la más reciente hacia atrás. Se detiene en la primera inasistencia.
     * Solo cuenta actividades pasadas que ya tuvieron asistencia registrada.
     */
    public function getRachaActual($id_familia) {
        // Traer todas las actividades pasadas de los grupos de la familia,
        // ordenadas de más reciente a más antigua, con si asistió o no.
        $this->db->query(
            'SELECT a.id_actividad,
                    a.fecha_hora_inicio,
                    COALESCE(MAX(asi.presente), 0) AS presente
             FROM actividades a
             INNER JOIN actividad_grupo ag ON a.id_actividad = ag.id_actividad_fk
             INNER JOIN grupos g ON ag.id_grupo_fk = g.id_grupo
             INNER JOIN estudiantes e ON g.id_grupo = e.id_grupo_fk
             INNER JOIN familia_estudiante fe ON e.id_estudiante = fe.id_estudiante_fk
             LEFT JOIN asistencia asi
                   ON asi.id_actividad_fk = a.id_actividad
                  AND asi.id_familia_fk = :id_familia
             WHERE fe.id_familia_fk = :id_familia2
               AND a.fecha_hora_inicio < NOW()
             GROUP BY a.id_actividad, a.fecha_hora_inicio
             ORDER BY a.fecha_hora_inicio DESC'
        );
        $this->db->bind(':id_familia',  $id_familia);
        $this->db->bind(':id_familia2', $id_familia);
        $actividades = $this->db->resultSet();

        $racha = 0;
        foreach ($actividades as $act) {
            if ((int)$act->presente === 1) {
                $racha++;
            } else {
                // Primera inasistencia — la racha se rompe
                break;
            }
        }
        return $racha;
    }

    /**
     * Obtiene todos los profesores del sistema.
     */
    public function getAllProfesores() {
        $this->db->query(
            'SELECT p.id_profesor, p.nombres, p.apellidos, p.email, p.telefono,
                    GROUP_CONCAT(g.nombre_grupo SEPARATOR ", ") AS grupos
             FROM profesores p
             LEFT JOIN profesor_grupo pg ON p.id_profesor = pg.id_profesor_fk
             LEFT JOIN grupos g ON pg.id_grupo_fk = g.id_grupo
             GROUP BY p.id_profesor
             ORDER BY p.apellidos ASC'
        );
        return $this->db->resultSet();
    }

    /**
     * Obtiene todos los administradores (directivas).
     */
    public function getAllDirectivas() {
        $this->db->query(
            'SELECT id_administrador, nombres, apellidos, correo AS email, telefono
             FROM administrador
             ORDER BY apellidos ASC'
        );
        return $this->db->resultSet();
    }

    /**
     * Guarda un mensaje de contacto (para profesores o directivas).
     */
    public function guardarMensajeContacto($id_familia, $tipo, $id_destinatario, $titulo, $asunto, $mensaje) {
        if ($tipo === 'profesor') {
            $this->db->query('INSERT INTO mensajes_contacto (id_familia_fk, destinatario_tipo, id_profesor_fk, titulo, asunto, mensaje) VALUES (:id_familia, "profesor", :id_dest, :titulo, :asunto, :mensaje)');
        } else {
            $this->db->query('INSERT INTO mensajes_contacto (id_familia_fk, destinatario_tipo, id_administrador_fk, titulo, asunto, mensaje) VALUES (:id_familia, "directiva", :id_dest, :titulo, :asunto, :mensaje)');
        }
        $this->db->bind(':id_familia', $id_familia);
        $this->db->bind(':id_dest', $id_destinatario);
        $this->db->bind(':titulo', $titulo);
        $this->db->bind(':asunto', $asunto);
        $this->db->bind(':mensaje', $mensaje);
        return $this->db->execute();
    }

    /**
     * Obtiene todos los mensajes enviados por una familia y sus respuestas.
     */
    public function getMensajesByFamilia($id_familia) {
        $this->db->query(
            'SELECT m.*, 
                    p.nombres AS prof_nombres, p.apellidos AS prof_apellidos, p.email AS prof_email,
                    a.nombres AS admin_nombres, a.apellidos AS admin_apellidos, a.correo AS admin_email
             FROM mensajes_contacto m
             LEFT JOIN profesores p ON m.id_profesor_fk = p.id_profesor
             LEFT JOIN administrador a ON m.id_administrador_fk = a.id_administrador
             WHERE m.id_familia_fk = :id_familia
             ORDER BY m.fecha_envio DESC'
        );
        $this->db->bind(':id_familia', $id_familia);
        return $this->db->resultSet();
    }

    /**
     * Marca una respuesta como leída por la familia.
     */
    public function marcarRespuestaLeida($id_mensaje, $id_familia) {
        $this->db->query('UPDATE mensajes_contacto SET leido_familia = 1 WHERE id_mensaje = :id_mensaje AND id_familia_fk = :id_familia');
        $this->db->bind(':id_mensaje', $id_mensaje);
        $this->db->bind(':id_familia', $id_familia);
        return $this->db->execute();
    }

    /**
     * Guarda una opinión enviada por una familia.
     */
    public function guardarOpinion($id_familia, $mensaje) {
        $this->db->query('INSERT INTO opiniones (id_familia_fk, mensaje) VALUES (:f, :m)');
        $this->db->bind(':f', $id_familia);
        $this->db->bind(':m', $mensaje);
        return $this->db->execute();
    }

    /**
     * Devuelve las opiniones de las familias que pertenecen a los grupos asignados a un profesor.
     */
    public function getOpinionesByProfesor($id_profesor, $soloNoLeidas = false) {
        $sql = 'SELECT DISTINCT o.*, f.nombre_principal_acudiente, f.apellidos_principal_acudiente
                FROM opiniones o
                INNER JOIN familias f ON f.id_familia = o.id_familia_fk
                INNER JOIN familia_estudiante fe ON fe.id_familia_fk = f.id_familia
                INNER JOIN estudiantes e ON e.id_estudiante = fe.id_estudiante_fk
                INNER JOIN profesor_grupo pg ON pg.id_grupo_fk = e.id_grupo_fk
                WHERE pg.id_profesor_fk = :id_profesor';
        
        if ($soloNoLeidas) {
            $sql .= ' AND o.leida = 0';
        }
        $sql .= ' ORDER BY o.fecha_creacion DESC';
        
        $this->db->query($sql);
        $this->db->bind(':id_profesor', $id_profesor);
        return $this->db->resultSet();
    }

    /**
     * Marca una opinión como leída.
     */
    public function marcarOpinionLeida($id_opinion) {
        $this->db->query('UPDATE opiniones SET leida = 1 WHERE id_opinion = :id');
        $this->db->bind(':id', $id_opinion);
        return $this->db->execute();
    }
}


