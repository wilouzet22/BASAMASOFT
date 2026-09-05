<?php

/**
 * Docentes Controller
 * Panel de docentes — protegido, solo rol 'profesor'.
 */
class Docentes extends Controller
{

    public function __construct()
    {
        $this->_requireProfesor();
    }

    /** Verifica que el usuario sea profesor */
    private function _requireProfesor()
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'profesor') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
    }

    public function index()
    {
        $this->dashboard();
    }

    /**
     * Dashboard del docente con sus grupos, conteos y asistencias recientes.
     */
    public function dashboard()
    {
        $model      = $this->model('ProfesorModel');
        $id_profesor = $_SESSION['user_id'];

        $data = [
            'title'              => 'Panel Docente',
            'grupos'             => $model->getGruposByProfesor($id_profesor),
            'total_actividades'  => $model->countActividadesByProfesor($id_profesor),
            'total_asistencias'  => $model->countAsistenciasByProfesor($id_profesor),
            'asistencias_recientes' => $model->getAsistenciasByProfesor($id_profesor, 5),
        ];

        $this->view('docentes/dashboard', $data);
    }

    /**
     * Vista de actividades del docente.
     */
    public function actividades()
    {
        $model      = $this->model('ProfesorModel');
        $actividadModel = $this->model('ActividadModel');
        $id_profesor = $_SESSION['user_id'];

        $grupos = $model->getGruposByProfesor($id_profesor);
        $gruposIds = array_column($grupos, 'id_grupo');
        $sedesIds = array_unique(array_column($grupos, 'id_sede_fk'));

        $sedes = [];
        if (!empty($sedesIds)) {
            $db = new Database();
            $placeholders = implode(',', array_fill(0, count($sedesIds), '?'));
            $db->query("SELECT * FROM sedes WHERE id_sede IN ($placeholders)");
            foreach ($sedesIds as $i => $id) {
                $db->bind($i + 1, $id);
            }
            $sedes = $db->resultSet();
        }

        $data = [
            'title'       => 'Mis Actividades',
            'actividades' => $model->getActividadesByProfesor($id_profesor),
            'grupos'      => $grupos,
            'sedes'       => $sedes,
            'tipos_actividad' => $actividadModel->getAllTiposActividad(),
        ];

        $this->view('docentes/actividades', $data);
    }

    /**
     * Crear una nueva actividad (solo para grupos asignados al profesor).
     */
    public function crear_actividad()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ActividadModel');
            $profesorModel = $this->model('ProfesorModel');
            $id_profesor = $_SESSION['user_id'];

            $gruposProfesor = $profesorModel->getGruposByProfesor($id_profesor);
            $gruposPermitidos = array_column($gruposProfesor, 'id_grupo');

            $data = [
                'nombre_actividad'             => trim($_POST['nombre_actividad'] ?? ''),
                'descripcion'                  => trim($_POST['descripcion'] ?? ''),
                'fecha_hora_inicio'            => trim($_POST['fecha_hora_inicio'] ?? ''),
                'fecha_hora_fin'               => trim($_POST['fecha_hora_fin'] ?? ''),
                'id_tipo_actividad_fk'         => trim($_POST['id_tipo_actividad_fk'] ?? ''),
                'id_sede_fk'                   => trim($_POST['id_sede_fk'] ?? ''),
                'requiere_asistencia_por_hijo' => isset($_POST['requiere_asistencia_por_hijo']) ? 1 : 0,
                'creada_por_profesor_fk'       => $id_profesor,
            ];

            $grupos = isset($_POST['grupos']) ? $_POST['grupos'] : [];

            if (empty($data['nombre_actividad']) || empty($data['fecha_hora_inicio']) || empty($data['id_tipo_actividad_fk']) || empty($data['id_sede_fk']) || empty($grupos)) {
                header('Location: ' . URLROOT . '/docentes/actividades?error=validation');
                exit;
            }

            foreach ($grupos as $g) {
                if (!in_array((int)$g, $gruposPermitidos)) {
                    header('Location: ' . URLROOT . '/docentes/actividades?error=unauthorized_group');
                    exit;
                }
            }

            $imagen = $_FILES['imagen_principal'] ?? null;
            $extensiones = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if ($imagen && $imagen['error'] === UPLOAD_ERR_OK && is_uploaded_file($imagen['tmp_name']) && $imagen['size'] <= 5 * 1024 * 1024) {
                $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
                if (in_array($extension, $extensiones, true) && @getimagesize($imagen['tmp_name']) !== false) {
                    $directorioImagenes = dirname(APPROOT) . '/public/assets/img/actividades/';
                    if (!is_dir($directorioImagenes)) mkdir($directorioImagenes, 0777, true);
                    $nombreArchivo = 'act_' . bin2hex(random_bytes(8)) . '.' . $extension;
                    $destinoImagen = $directorioImagenes . $nombreArchivo;
                    if (move_uploaded_file($imagen['tmp_name'], $destinoImagen)) {
                        $data['imagen_principal'] = URLROOT . '/public/assets/img/actividades/' . $nombreArchivo;
                    }
                }
            }

            if (empty($data['fecha_hora_fin'])) {
                $data['fecha_hora_fin'] = null;
            }

            $id_actividad = $model->crearActividad($data);

            if ($id_actividad) {
                $model->asignarGruposAActividad($id_actividad, $grupos);
                header('Location: ' . URLROOT . '/docentes/actividades');
                exit;
            } else {
                if (!empty($destinoImagen) && is_file($destinoImagen)) unlink($destinoImagen);
                header('Location: ' . URLROOT . '/docentes/actividades?error=duplicate');
                exit;
            }
        } else {
            header('Location: ' . URLROOT . '/docentes/actividades');
            exit;
        }
    }

    /**
     * Vista de registro de asistencia del docente.
     */
    public function asistencia()
    {
        $model      = $this->model('ProfesorModel');
        $id_profesor = $_SESSION['user_id'];

        $data = [
            'title'       => 'Registro de Asistencia',
            'estudiantes' => $model->getEstudiantesByProfesor($id_profesor),
            'asistencias' => $model->getAsistenciasByProfesor($id_profesor, 50),
            'total_asistencias' => $model->countAsistenciasByProfesor($id_profesor),
        ];

        $this->view('docentes/asistencia', $data);
    }

    /**
     * Notificaciones del docente: opiniones enviadas por familias de sus grupos.
     */
    public function notificaciones()
    {
        $familiaModel = $this->model('FamiliaModel');
        $id_profesor = $_SESSION['user_id'];

        $opiniones = $familiaModel->getOpinionesByProfesor($id_profesor);

        // Si se marca una como leída vía GET
        if (isset($_GET['leer']) && is_numeric($_GET['leer'])) {
            $familiaModel->marcarOpinionLeida((int)$_GET['leer']);
            header('Location: ' . URLROOT . '/docentes/notificaciones');
            exit;
        }

        $data = [
            'title'    => 'Notificaciones',
            'opiniones' => $opiniones,
            'no_leidas' => count(array_filter((array)$opiniones, fn($o) => !$o->leida)),
        ];

        $this->view('docentes/notificaciones', $data);
    }

    /**
     * Mensajes enviados/recibidos por este profesor.
     */
    public function mensajes()
    {
        $model = $this->model('ProfesorModel');
        $id_profesor = $_SESSION['user_id'];

        // Manejo de envío de nuevo mensaje
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_familia'], $_POST['titulo'], $_POST['mensaje'])) {
            $id_familia = (int)$_POST['id_familia'];
            $titulo = trim($_POST['titulo']);
            $asunto = trim($_POST['asunto'] ?? '');
            $mensaje = trim($_POST['mensaje']);
            
            if (!empty($id_familia) && !empty($titulo) && !empty($mensaje)) {
                $model->enviarMensajeFamilia($id_profesor, $id_familia, $titulo, $asunto, $mensaje);
                header('Location: ' . URLROOT . '/docentes/mensajes?send=ok');
                exit;
            }
        }

        // Si se marca una como leída vía GET
        if (isset($_GET['leer']) && is_numeric($_GET['leer'])) {
            $model->marcarMensajeLeido((int)$_GET['leer']);
            header('Location: ' . URLROOT . '/docentes/mensajes');
            exit;
        }

        $mensajes = $model->getMensajesContacto($id_profesor);
        $familias = $model->getFamiliasByProfesor($id_profesor);

        $data = [
            'title'    => 'Mensajes',
            'mensajes' => $mensajes,
            'familias' => $familias,
            'no_leidos' => count(array_filter((array)$mensajes, fn($m) => !$m->leido && $m->destinatario_tipo === 'profesor')),
        ];

        $this->view('shared/mensajes', $data);
    }

    /**
     * Elimina un mensaje
     */
    public function eliminar_mensaje($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = $this->model('ProfesorModel');
            $id_profesor = $_SESSION['user_id'];
            $model->eliminarMensaje($id, $id_profesor);
        }
        header('Location: ' . URLROOT . '/docentes/mensajes');
        exit;
    }
    /**
     * Vista de actividades próximas — informativa, sin puntaje para el docente.
     */
    public function actividades_proximas()
    {
        $model = $this->model('ActividadModel');

        $data = [
            'title'       => 'Actividades de la Institución',
            'actividades' => $model->getAllActividadesVisitante(),
            'proximas'    => $model->getProximasActividadesVisitante(3),
        ];

        $this->view('shared/actividades_proximas', $data);
    }

    /**
     * Vista para escanear QR y confirmar asistencia (Docentes)
     */
    public function confirmar_asistencia()
    {
        $model      = $this->model('ProfesorModel');
        $actividadModel = $this->model('ActividadModel');
        $id_profesor = $_SESSION['user_id'];

        $data = [
            'title'       => 'Confirmar Asistencia - Escáner QR',
            'actividades' => $model->getActividadesByProfesor($id_profesor),
        ];

        $this->view('docentes/confirmar_asistencia', $data);
    }

    /**
     * Sube una foto para una actividad
     */
    public function subir_foto_actividad()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_actividad']) && isset($_FILES['foto_actividad'])) {
            $id_actividad = (int)$_POST['id_actividad'];
            $file = $_FILES['foto_actividad'];

            if ($id_actividad > 0 && $file['error'] === UPLOAD_ERR_OK && is_uploaded_file($file['tmp_name']) && $file['size'] <= 5 * 1024 * 1024) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $imageInfo = @getimagesize($file['tmp_name']);
                if (in_array($ext, $allowed, true) && $imageInfo !== false) {
                    $uploadDir = dirname(APPROOT) . '/public/assets/img/actividades/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $fileName = 'act_' . $id_actividad . '_' . time() . '.' . $ext;
                    $destPath = $uploadDir . $fileName;

                    if (move_uploaded_file($file['tmp_name'], $destPath)) {
                        $rutaUrl = URLROOT . '/public/assets/img/actividades/' . $fileName;
                        $model = $this->model('ActividadModel');
                        if ($model->establecerImagenPrincipal($id_actividad, $rutaUrl, (int)$_SESSION['user_id'])) {
                            header('Location: ' . URLROOT . '/docentes/actividades?upload=success');
                            exit;
                        } else {
                            unlink($destPath);
                            header('Location: ' . URLROOT . '/docentes/actividades?error=upload_permission');
                            exit;
                        }
                    }
                } else {
                    header('Location: ' . URLROOT . '/docentes/actividades?error=invalid_image');
                    exit;
                }
            } else {
                header('Location: ' . URLROOT . '/docentes/actividades?error=upload_failed');
                exit;
            }
        }
        header('Location: ' . URLROOT . '/docentes/actividades');
        exit;
    }

    /**
     * Procesa el escaneo de QR para confirmar asistencia (Docente)
     */
    public function procesar_qr_asistencia()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $token = $input['token'] ?? '';

        if (empty($token)) {
            echo json_encode(['success' => false, 'message' => 'Token QR requerido']);
            exit;
        }

        $db = new Database();
        $db->query('SELECT q.*, a.nombre_actividad, a.fecha_hora_inicio,
                    f.nombre_principal_acudiente, f.apellidos_principal_acudiente
                    FROM qr_asistencia q
                    INNER JOIN actividades a ON q.id_actividad_fk = a.id_actividad
                    INNER JOIN familias f ON q.id_familia_fk = f.id_familia
                    WHERE q.token = :token LIMIT 1');
        $db->bind(':token', $token);
        $qr = $db->single();

        if (!$qr) {
            echo json_encode(['success' => false, 'message' => 'Código QR inválido o no encontrado']);
            exit;
        }

        // Verificar expiración
        if (strtotime($qr->expira_en) < time()) {
            echo json_encode(['success' => false, 'message' => 'El código QR ha expirado (válido 24h)']);
            exit;
        }

        // Verificar si ya fue usado
        if ((int)$qr->usado === 1) {
            echo json_encode(['success' => false, 'message' => 'Este QR ya fue utilizado (' . $qr->usado_en . ')', 'usado' => true]);
            exit;
        }

        $nombre_familia = $qr->nombre_principal_acudiente . ' ' . $qr->apellidos_principal_acudiente;
        $registrado_por = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

        // Registrar asistencia para todos los hijos de la familia
        $db->query('SELECT id_estudiante_fk FROM familia_estudiante WHERE id_familia_fk = :f');
        $db->bind(':f', $qr->id_familia_fk);
        $hijos = $db->resultSet();

        $registrados = 0;
        foreach ($hijos as $hijo) {
            // Verificar si ya existe asistencia para este estudiante
            $db->query('SELECT 1 FROM asistencia WHERE id_actividad_fk = :a AND id_familia_fk = :f AND id_estudiante_fk = :e LIMIT 1');
            $db->bind(':a', $qr->id_actividad_fk);
            $db->bind(':f', $qr->id_familia_fk);
            $db->bind(':e', $hijo->id_estudiante_fk);
            if ($db->single()) continue; // Ya registrado

            $db->query('INSERT INTO asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, registrada_por_profesor_fk, presente) VALUES (:a, :f, :e, :p, 1)');
            $db->bind(':a', $qr->id_actividad_fk);
            $db->bind(':f', $qr->id_familia_fk);
            $db->bind(':e', $hijo->id_estudiante_fk);
            $db->bind(':p', $registrado_por);
            $db->execute();
            $registrados++;
        }

        // Si la familia no tiene hijos asociados, registrar sin estudiante
        if (empty($hijos)) {
            $db->query('SELECT 1 FROM asistencia WHERE id_actividad_fk = :a AND id_familia_fk = :f AND id_estudiante_fk IS NULL LIMIT 1');
            $db->bind(':a', $qr->id_actividad_fk);
            $db->bind(':f', $qr->id_familia_fk);
            if (!$db->single()) {
                $db->query('INSERT INTO asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, registrada_por_profesor_fk, presente) VALUES (:a, :f, NULL, :p, 1)');
                $db->bind(':a', $qr->id_actividad_fk);
                $db->bind(':f', $qr->id_familia_fk);
                $db->bind(':p', $registrado_por);
                $db->execute();
            }
        }

        // Marcar QR como usado
        $db->query('UPDATE qr_asistencia SET usado = 1, usado_en = NOW() WHERE id_qr = :id');
        $db->bind(':id', $qr->id_qr);
        $db->execute();

        echo json_encode([
            'success'   => true,
            'message'   => 'Asistencia confirmada para la familia',
            'familia'   => $nombre_familia,
            'actividad' => $qr->nombre_actividad,
            'hijos_registrados' => $registrados
        ]);
        exit;
    }

    /**
     * Genera (o reutiliza/renueva) un QR único POR ACTIVIDAD para que los
     * padres lo escaneen con su celular. Sin id_familia_fk (es NULL).
     * El token se renueva cada 5 minutos para evitar capturas compartidas.
     */
    public function generar_qr_actividad($id_actividad = null)
    {
        header('Content-Type: application/json');

        if (!$id_actividad) {
            echo json_encode(['success' => false, 'message' => 'Actividad requerida']);
            exit;
        }

        $db = new Database();

        // Verificar que la actividad existe
        $db->query('SELECT id_actividad, nombre_actividad FROM actividades WHERE id_actividad = :id LIMIT 1');
        $db->bind(':id', (int)$id_actividad);
        $act = $db->single();
        if (!$act) {
            echo json_encode(['success' => false, 'message' => 'Actividad no encontrada']);
            exit;
        }

        // Buscar token vigente (creado hace menos de 5 minutos, no expirado globalmente)
        $db->query('SELECT token, expira_en FROM qr_asistencia
                    WHERE id_actividad_fk = :a AND id_familia_fk IS NULL AND usado = 0
                      AND expira_en > NOW()
                      AND creado_en >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
                    ORDER BY creado_en DESC LIMIT 1');
        $db->bind(':a', (int)$id_actividad);
        $existente = $db->single();

        if ($existente) {
            $url = URLROOT . '/padres/confirmar_asistencia_actividad/' . $existente->token;
            echo json_encode([
                'success'   => true,
                'token'     => $existente->token,
                'url'       => $url,
                'expira_en' => $existente->expira_en,
                'renovado'  => false
            ]);
            exit;
        }

        // Invalidar tokens anteriores de esta actividad sin familia
        $db->query('UPDATE qr_asistencia SET usado = 1 WHERE id_actividad_fk = :a AND id_familia_fk IS NULL AND usado = 0');
        $db->bind(':a', (int)$id_actividad);
        $db->execute();

        // Crear token nuevo con expiración de 60 minutos (sesión de check-in)
        $token     = bin2hex(random_bytes(16));
        $expira_en = date('Y-m-d H:i:s', strtotime('+60 minutes'));

        $db->query('INSERT INTO qr_asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, token, expira_en) VALUES (:a, NULL, NULL, :t, :ex)');
        $db->bind(':a', (int)$id_actividad);
        $db->bind(':t', $token);
        $db->bind(':ex', $expira_en);
        $db->execute();

        $url = URLROOT . '/padres/confirmar_asistencia_actividad/' . $token;
        echo json_encode([
            'success'   => true,
            'token'     => $token,
            'url'       => $url,
            'expira_en' => $expira_en,
            'renovado'  => true
        ]);
        exit;
    }

    /**
     * Devuelve cuántas familias ya confirmaron asistencia para una actividad.
     * Usado para el contador en vivo en la vista del docente.
     */
    public function qr_actividad_status($id_actividad = null)
    {
        header('Content-Type: application/json');
        if (!$id_actividad) { echo json_encode(['count' => 0]); exit; }

        $db = new Database();
        $db->query('SELECT COUNT(DISTINCT id_familia_fk) as total FROM asistencia WHERE id_actividad_fk = :a AND presente = 1');
        $db->bind(':a', (int)$id_actividad);
        $row = $db->single();
        echo json_encode(['count' => (int)($row->total ?? 0)]);
        exit;
    }
}
