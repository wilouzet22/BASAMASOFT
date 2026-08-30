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
}
