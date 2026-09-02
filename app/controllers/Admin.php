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
        $estudianteId = $input['estudiante_id'] ?? '';
        $actividadId = $input['actividad_id'] ?? '';
        $requiereHijo = $input['requiere_hijo'] ?? 1;

        if (empty($estudianteId) || empty($actividadId)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit;
        }

        $model = $this->model('AsistenciaModel');
        $actividadModel = $this->model('ActividadModel');
        
        // Get actividad info
        $actividad = $actividadModel->getActividadById($actividadId);
        if (!$actividad) {
            echo json_encode(['success' => false, 'message' => 'Actividad no encontrada']);
            exit;
        }

        // Get estudiante info
        $db = new Database();
        $db->query('SELECT e.*, CONCAT(e.nombres, " ", e.apellidos) as nombre_completo, g.nombre_grupo 
                    FROM estudiantes e 
                    LEFT JOIN grupos g ON e.id_grupo_fk = g.id_grupo 
                    WHERE e.id_estudiante = :id');
        $db->bind(':id', $estudianteId);
        $estudiante = $db->single();

        if (!$estudiante) {
            echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado']);
            exit;
        }

        // Check if already registered
        $db->query('SELECT * FROM asistencia WHERE id_actividad_fk = :act_id AND id_estudiante_fk = :est_id LIMIT 1');
        $db->bind(':act_id', $actividadId);
        $db->bind(':est_id', $estudianteId);
        $existe = $db->single();

        if ($existe) {
            echo json_encode([
                'success' => true, 
                'message' => 'Asistencia ya registrada',
                'estudiante_nombre' => $estudiante->nombre_completo,
                'actividad_nombre' => $actividad->nombre_actividad
            ]);
            exit;
        }

        // Register attendance
        $idFamilia = null;
        if ($requiereHijo) {
            $db->query('SELECT id_familia_fk FROM familia_estudiante WHERE id_estudiante_fk = :id LIMIT 1');
            $db->bind(':id', $estudianteId);
            $fam = $db->single();
            if ($fam) $idFamilia = $fam->id_familia_fk;
        } else {
            // Get any family for general attendance
            $db->query('SELECT id_familia_fk FROM familia_estudiante WHERE id_estudiante_fk = :id LIMIT 1');
            $db->bind(':id', $estudianteId);
            $fam = $db->single();
            if ($fam) $idFamilia = $fam->id_familia_fk;
        }

        if (!$idFamilia) {
            echo json_encode(['success' => false, 'message' => 'Estudiante sin familia asociada']);
            exit;
        }

        $db->query('INSERT INTO asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, registrada_por_profesor_fk, presente) 
                    VALUES (:act_id, :fam_id, :est_id, :prof_id, 1)');
        $db->bind(':act_id', $actividadId);
        $db->bind(':fam_id', $idFamilia);
        $db->bind(':est_id', $estudianteId);
        $db->bind(':prof_id', $_SESSION['user_id'] ?? 1);
        
        if ($db->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Asistencia confirmada',
                'estudiante_nombre' => $estudiante->nombre_completo,
                'actividad_nombre' => $actividad->nombre_actividad
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar']);
        }
        exit;
    }
}
