<?php

/**
 * Admin Controller
 * Panel de administración — protegido, solo rol 'administrador'.
 */
class Admin extends Controller
{

    public function __construct()
    {
        $this->_requireAdmin();
    }

    /** Verifica que el usuario sea administrador */
    private function _requireAdmin()
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
    }

    public function index()
    {
        $this->dashboard();
    }

    /**
     * Dashboard con estadísticas reales de la BD.
     */
    public function dashboard()
    {
        $model = $this->model('AdministradorModel');
        $actividadModel = $this->model('ActividadModel');

        $data = [
            'title'                 => 'Panel de Administración',
            'total_profesores'      => $model->countProfesores(),
            'total_familias'        => $model->countFamilias(),
            'total_estudiantes'     => $model->countEstudiantes(),
            'total_sedes'           => $model->countSedes(),
            'total_actividades'     => $model->countActividades(),
            'total_asistencias'     => $model->countAsistencias(),
            'asistencias_presentes' => $model->countAsistenciasPresentes(),
            'logs_recientes'        => $model->getRecentLogs(8),
            'tipos_actividad'       => $actividadModel->getAllTiposActividad(),
            'grupos'                => $actividadModel->getAllGrupos(),
            'sedes'                 => $model->getAllSedes(),
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Vista de usuarios generales (redirige a profesores por defecto)
     */
    public function usuarios()
    {
        $this->profesores();
    }

    /**
     * Vista de profesores
     */
    public function profesores()
    {
        $model = $this->model('AdministradorModel');

        $data = [
            'title'      => 'Gestión de Profesores',
            'profesores' => $model->getAllProfesores(),
        ];

        $this->view('admin/profesores', $data);
    }

    /**
     * Vista de familias
     */
    public function familias()
    {
        $model = $this->model('AdministradorModel');

        $data = [
            'title'    => 'Gestión de Familias',
            'familias' => $model->getAllFamilias(),
        ];

        $this->view('admin/familias', $data);
    }

    /**
     * Vista de estudiantes
     */
    public function estudiantes()
    {
        $model = $this->model('AdministradorModel');

        $data = [
            'title'       => 'Gestión de Estudiantes',
            'estudiantes' => $model->getAllEstudiantes(),
        ];

        $this->view('admin/estudiantes', $data);
    }

    /**
     * Vista de asistencias con registros reales.
     */
    public function asistencias()
    {
        $model = $this->model('AsistenciaModel');

        $data = [
            'title'       => 'Gestión de Asistencias',
            'asistencias' => $model->getAll(100),
            'presentes'   => $model->countPresentes(),
            'ausentes'    => $model->countAusentes(),
        ];

        $this->view('admin/asistencias', $data);
    }

    /**
     * Vista de sedes.
     */
    public function sedes()
    {
        $model = $this->model('AdministradorModel');

        $data = [
            'title' => 'Gestión de Sedes',
            'sedes' => $model->getAllSedes(),
        ];

        $this->view('admin/sedes', $data);
    }

    /**
     * Vista de actividades.
     */
    public function actividades()
    {
        $model = $this->model('ActividadModel');
        $adminModel = $this->model('AdministradorModel');

        $data = [
            'title'           => 'Gestión de Actividades',
            'actividades'     => $model->getAllActividadesVisitante(),
            'tipos_actividad' => $model->getAllTiposActividad(),
            'grupos'          => $model->getAllGrupos(),
            'sedes'           => $adminModel->getAllSedes(),
        ];

        $this->view('admin/actividades', $data);
    }

    /**
     * Procesar la creación de una nueva actividad.
     */
    public function crear_actividad()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('ActividadModel');

            $data = [
                'nombre_actividad'             => trim($_POST['nombre_actividad'] ?? ''),
                'descripcion'                  => trim($_POST['descripcion'] ?? ''),
                'fecha_hora_inicio'            => trim($_POST['fecha_hora_inicio'] ?? ''),
                'fecha_hora_fin'               => trim($_POST['fecha_hora_fin'] ?? ''),
                'id_tipo_actividad_fk'         => trim($_POST['id_tipo_actividad_fk'] ?? ''),
                'id_sede_fk'                   => trim($_POST['id_sede_fk'] ?? ''),
                'requiere_asistencia_por_hijo' => isset($_POST['requiere_asistencia_por_hijo']) ? 1 : 0,
            ];

            $tipo_alcance = $_POST['tipo_alcance'] ?? 'general';
            $grupos = isset($_POST['grupos']) ? $_POST['grupos'] : [];

            // Validación básica
            if (empty($data['nombre_actividad']) || empty($data['fecha_hora_inicio']) || empty($data['id_tipo_actividad_fk']) || empty($data['id_sede_fk'])) {
                header('Location: ' . URLROOT . '/admin/actividades');
                exit;
            }

            // Manejo de imagen principal (opcional para "Inicio de año")
            $imagen = $_FILES['imagen_principal'] ?? null;
            $extensiones = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $esInicioDeAno = strtolower(trim($data['nombre_actividad'])) === 'inicio de año';
            
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
                } else {
                    if (!$esInicioDeAno) {
                        header('Location: ' . URLROOT . '/admin/actividades?error=invalid_image');
                        exit;
                    }
                }
            } elseif (!$esInicioDeAno) {
                // Para actividades normales, la imagen es obligatoria
                header('Location: ' . URLROOT . '/admin/actividades?error=image_required');
                exit;
            }

            // Lógica especial para "Inicio de año"
            if ($esInicioDeAno) {
                if (date('m-d') !== '01-01') {
                    header('Location: ' . URLROOT . '/admin/actividades?error=not_jan1');
                    exit;
                }
                $db = new Database();
                $db->query('SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE asistencia; TRUNCATE TABLE actividad_grupo; TRUNCATE TABLE actividades; SET FOREIGN_KEY_CHECKS = 1;');
                $db->execute();
            }

            // Si no se proporcionó fecha de fin, se deja como nula
            if (empty($data['fecha_hora_fin'])) {
                $data['fecha_hora_fin'] = null;
            }

            $id_actividad = $model->crearActividad($data);

            if ($id_actividad) {
                if ($tipo_alcance === 'grupo' && !empty($grupos)) {
                    $model->asignarGruposAActividad($id_actividad, $grupos);
                }
                header('Location: ' . URLROOT . '/admin/actividades');
                exit;
            } else {
                if (is_file($destinoImagen)) unlink($destinoImagen);
                header('Location: ' . URLROOT . '/admin/actividades?error=duplicate');
                exit;
            }
        } else {
            header('Location: ' . URLROOT . '/admin/actividades');
            exit;
        }
    }

    /**
     * Vista de gestión de grupos.
     */
    public function grupos()
    {
        $model = $this->model('AdministradorModel');
        $data = [
            'title'  => 'Gestión de Grupos',
            'grupos' => $model->getAllGruposDetails(),
            'sedes'  => $model->getAllSedes(),
            // Para obtener los grados, podemos usar un método rápido o pasarlos desde aquí
        ];

        // Obtener grados directamente
        $db = new Database();
        $db->query('SELECT * FROM grados ORDER BY id_grado ASC');
        $data['grados'] = $db->resultSet();

        $this->view('admin/grupos', $data);
    }

    /**
     * Crea un nuevo grupo (Restringido al 1 de enero)
     */
    public function crear_grupo()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (date('m-d') !== '01-01') {
                header('Location: ' . URLROOT . '/admin/grupos?error=not_jan1');
                exit;
            }

            $data = [
                'nombre_grupo' => trim($_POST['nombre_grupo']),
                'id_grado_fk'  => $_POST['id_grado_fk'],
                'id_sede_fk'   => $_POST['id_sede_fk']
            ];

            if (!empty($data['nombre_grupo']) && !empty($data['id_grado_fk']) && !empty($data['id_sede_fk'])) {
                $model = $this->model('AdministradorModel');
                $model->crearGrupo($data);
            }
            header('Location: ' . URLROOT . '/admin/grupos');
            exit;
        }
        header('Location: ' . URLROOT . '/admin/grupos');
    }

    /**
     * Elimina un grupo
     */
    public function eliminar_grupo($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('AdministradorModel');
            $model->eliminarGrupo($id);
        }
        header('Location: ' . URLROOT . '/admin/grupos');
        exit;
    }

    /**
     * Vista de Auditoría — Actividad reciente del sistema.
     */
    public function auditoria()
    {
        $model = $this->model('AdministradorModel');

        $data = [
            'title'  => 'Auditoría del Sistema',
            'logs'   => $model->getRecentLogs(50),
        ];

        $this->view('admin/auditoria', $data);
    }

    /**
     * Mensajes enviados o recibidos por directivas.
     */
    public function mensajes()
    {
        $model = $this->model('AdministradorModel');
        $id_admin = $_SESSION['user_id'];

        // Manejo de envío de nuevo mensaje
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_familia'], $_POST['titulo'], $_POST['mensaje'])) {
            $id_familia = (int)$_POST['id_familia'];
            $titulo = trim($_POST['titulo']);
            $asunto = trim($_POST['asunto'] ?? '');
            $mensaje = trim($_POST['mensaje']);
            
            if (!empty($id_familia) && !empty($titulo) && !empty($mensaje)) {
                $model->enviarMensajeFamilia($id_admin, $id_familia, $titulo, $asunto, $mensaje);
                header('Location: ' . URLROOT . '/admin/mensajes?send=ok');
                exit;
            }
        }

        // Si se marca una como leída vía GET
        if (isset($_GET['leer']) && is_numeric($_GET['leer'])) {
            $model->marcarMensajeLeido((int)$_GET['leer']);
            header('Location: ' . URLROOT . '/admin/mensajes');
            exit;
        }

        $mensajes = $model->getMensajesContacto($id_admin);
        $familias = $model->getAllFamilias();

        $data = [
            'title'    => 'Mensajes',
            'mensajes' => $mensajes,
            'familias' => $familias,
            'no_leidos' => count(array_filter((array)$mensajes, fn($m) => !$m->leido && $m->destinatario_tipo === 'directiva')),
        ];

        $this->view('shared/mensajes', $data);
    }

    /**
     * Elimina un mensaje
     */
    public function eliminar_mensaje($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = $this->model('AdministradorModel');
            $id_admin = $_SESSION['user_id'];
            $model->eliminarMensaje($id, $id_admin);
        }
        header('Location: ' . URLROOT . '/admin/mensajes');
        exit;
    }
    /**
     * Vista de actividades próximas/pendientes — solo informativa, sin puntaje.
     */
    public function actividades_proximas()
    {
        $model = $this->model('ActividadModel');

        $data = [
            'title'             => 'Actividades de la Institución',
            'actividades'       => $model->getAllActividadesVisitante(),
            'proximas'          => $model->getProximasActividadesVisitante(3),
        ];

        $this->view('shared/actividades_proximas', $data);
    }

    /**
     * Vista para escanear QR y confirmar asistencia (Admin)
     */
    public function confirmar_asistencia()
    {
        $model = $this->model('ActividadModel');
        
        $data = [
            'title'       => 'Confirmar Asistencia - Escáner QR',
            'actividades' => $model->getAllActividadesVisitante(),
        ];

        $this->view('admin/confirmar_asistencia', $data);
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
                        if ($model->establecerImagenPrincipal($id_actividad, $rutaUrl)) {
                            header('Location: ' . URLROOT . '/admin/actividades?upload=success');
                            exit;
                        } else {
                            unlink($destPath);
                            header('Location: ' . URLROOT . '/admin/actividades?error=upload_permission');
                            exit;
                        }
                    }
                } else {
                    header('Location: ' . URLROOT . '/admin/actividades?error=invalid_image');
                    exit;
                }
            } else {
                header('Location: ' . URLROOT . '/admin/actividades?error=upload_failed');
                exit;
            }
        }
        header('Location: ' . URLROOT . '/admin/actividades');
        exit;
    }

    /**
     * Procesa el escaneo de QR para confirmar asistencia (Admin)
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
        $registrado_por = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1; // 1 = fallback para admin

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
     * Genera (o reutiliza/renueva) un QR único POR ACTIVIDAD — versión Admin.
     */
    public function generar_qr_actividad($id_actividad = null)
    {
        header('Content-Type: application/json');

        if (!$id_actividad) {
            echo json_encode(['success' => false, 'message' => 'Actividad requerida']);
            exit;
        }

        $db = new Database();
        $db->query('SELECT id_actividad, nombre_actividad FROM actividades WHERE id_actividad = :id LIMIT 1');
        $db->bind(':id', (int)$id_actividad);
        $act = $db->single();
        if (!$act) {
            echo json_encode(['success' => false, 'message' => 'Actividad no encontrada']);
            exit;
        }

        $db->query('SELECT token, expira_en FROM qr_asistencia
                    WHERE id_actividad_fk = :a AND id_familia_fk IS NULL AND usado = 0
                      AND expira_en > NOW()
                      AND creado_en >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
                    ORDER BY creado_en DESC LIMIT 1');
        $db->bind(':a', (int)$id_actividad);
        $existente = $db->single();

        if ($existente) {
            $url = URLROOT . '/padres/confirmar_asistencia_actividad/' . $existente->token;
            echo json_encode(['success' => true, 'token' => $existente->token, 'url' => $url, 'expira_en' => $existente->expira_en, 'renovado' => false]);
            exit;
        }

        $db->query('UPDATE qr_asistencia SET usado = 1 WHERE id_actividad_fk = :a AND id_familia_fk IS NULL AND usado = 0');
        $db->bind(':a', (int)$id_actividad);
        $db->execute();

        $token     = bin2hex(random_bytes(16));
        $expira_en = date('Y-m-d H:i:s', strtotime('+60 minutes'));

        $db->query('INSERT INTO qr_asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, token, expira_en) VALUES (:a, NULL, NULL, :t, :ex)');
        $db->bind(':a', (int)$id_actividad);
        $db->bind(':t', $token);
        $db->bind(':ex', $expira_en);
        $db->execute();

        $url = URLROOT . '/padres/confirmar_asistencia_actividad/' . $token;
        echo json_encode(['success' => true, 'token' => $token, 'url' => $url, 'expira_en' => $expira_en, 'renovado' => true]);
        exit;
    }

    /**
     * Contador en vivo de familias confirmadas para una actividad — Admin.
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
