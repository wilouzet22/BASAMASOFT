<?php
/**
 * Padres Controller
 * Portal de acudientes — protegido, solo rol 'familia'.
 */
class Padres extends Controller {

    public function __construct() {
        $this->_requireFamilia();
    }

    /** Verifica que el usuario sea una familia/acudiente */
    private function _requireFamilia() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'familia') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
    }

    public function index() {
        $this->dashboard();
    }

    /**
     * Dashboard del acudiente con sus hijos y estadísticas de asistencia.
     */
    public function dashboard() {
        $model     = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];

        // Obtener estudiantes (hijos)
        $estudiantes = $model->getEstudiantesByFamilia($id_familia);

        // Calcular estadísticas individuales por hijo
        $estadisticas = [];
        foreach ($estudiantes as $est) {
            $estadisticas[$est->id_estudiante] = $model->getEstadisticasEstudiante(
                $est->id_estudiante,
                $id_familia
            );
        }

        $data = [
            'title'               => 'Mi Portal',
            'estudiantes'         => $estudiantes,
            'estadisticas'        => $estadisticas,
            'asistencias_recientes' => $model->getAsistenciasByFamilia($id_familia, 5),
            'proximas_actividades'  => $model->getProximasActividades($id_familia),
            'profesores'          => $model->getAllProfesores(),
            'directivas'          => $model->getAllDirectivas(),
        ];

        $this->view('padres/dashboard', $data);
    }

    /**
     * Vista del historial completo de asistencias.
     */
    public function asistencias() {
        $model     = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];

        $estudiantes = $model->getEstudiantesByFamilia($id_familia);
        $estadisticas = [];
        foreach ($estudiantes as $est) {
            $estadisticas[$est->id_estudiante] = $model->getEstadisticasEstudiante(
                $est->id_estudiante,
                $id_familia
            );
        }

        $data = [
            'title'       => 'Historial de Asistencias',
            'asistencias' => $model->getAsistenciasByFamilia($id_familia, 100),
            'estudiantes' => $estudiantes,
            'estadisticas'=> $estadisticas,
        ];

        $this->view('padres/camino', $data);
    }

    /**
     * Vista del mapa de camino / gamificación.
     */
    public function camino() {
        $model     = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];

        $estudiantes = $model->getEstudiantesByFamilia($id_familia);
        $estadisticas = [];
        foreach ($estudiantes as $est) {
            $estadisticas[$est->id_estudiante] = $model->getEstadisticasEstudiante(
                $est->id_estudiante,
                $id_familia
            );
        }

        $actividades_camino = $model->getAllActividadesPath($id_familia);

        $data = [
            'title'              => 'Camino de Éxito',
            'estudiantes'        => $estudiantes,
            'estadisticas'       => $estadisticas,
            'actividades_camino' => $actividades_camino,
            'profesores'         => $model->getAllProfesores(),
            'directivas'         => $model->getAllDirectivas(),
        ];

        $this->view('padres/camino', $data);
    }

    /**
     * Vista de puntos/gamificación con dashboards de progreso.
     */
    public function puntos() {
        $model      = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];

        $data = [
            'title'          => 'Mis Puntos',
            'globales'       => $model->getEstadisticasGlobales($id_familia),
            'por_tipo'       => $model->getAsistenciaPorTipo($id_familia),
            'por_mes'        => $model->getAsistenciaPorMes($id_familia),
            'racha'          => $model->getRachaActual($id_familia),
            'asistencias'    => $model->getAsistenciasByFamilia($id_familia, 10),
            'estudiantes'    => $model->getEstudiantesByFamilia($id_familia),
            'profesores'     => $model->getAllProfesores(),
            'directivas'     => $model->getAllDirectivas(),
        ];

        $this->view('padres/puntos', $data);
    }

    /**
     * Vista de la cueva — destino final del camino.
     */
    public function cueva() {
        $model     = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];

        $estudiantes = $model->getEstudiantesByFamilia($id_familia);
        $estadisticas = [];
        foreach ($estudiantes as $est) {
            $estadisticas[$est->id_estudiante] = $model->getEstadisticasEstudiante(
                $est->id_estudiante,
                $id_familia
            );
        }

        $actividades_camino = $model->getAllActividadesPath($id_familia);

        $data = [
            'title'              => 'La Cueva',
            'estudiantes'        => $estudiantes,
            'estadisticas'       => $estadisticas,
            'actividades_camino' => $actividades_camino,
            'profesores'         => $model->getAllProfesores(),
            'directivas'         => $model->getAllDirectivas(),
        ];

        $this->view('padres/cueva', $data);
    }
    /**
     * Vista de la cima de la montaña.
     */
    public function pico_montana() {
        $model     = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];

        $estudiantes = $model->getEstudiantesByFamilia($id_familia);
        $estadisticas = [];
        foreach ($estudiantes as $est) {
            $estadisticas[$est->id_estudiante] = $model->getEstadisticasEstudiante(
                $est->id_estudiante,
                $id_familia
            );
        }

        $actividades_camino = $model->getAllActividadesPath($id_familia);

        $data = [
            'title'              => 'Pico de la Montaña',
            'estudiantes'        => $estudiantes,
            'estadisticas'       => $estadisticas,
            'actividades_camino' => $actividades_camino,
            'profesores'         => $model->getAllProfesores(),
            'directivas'         => $model->getAllDirectivas(),
        ];

        $this->view('padres/pico_montana', $data);
    }

    /**
     * Recibe y guarda la opinión enviada por una familia.
     */
    public function enviar_opinion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/padres/camino');
            exit;
        }
        $mensaje = trim($_POST['mensaje'] ?? '');
        if (empty($mensaje)) {
            header('Location: ' . URLROOT . '/padres/camino#opinion');
            exit;
        }
        $model = $this->model('FamiliaModel');
        $model->guardarOpinion($_SESSION['user_id'], $mensaje);
        // Redirige con flag de éxito para mostrar toast en la vista
        header('Location: ' . URLROOT . '/padres/camino?opinion=ok');
        exit;
    }

    /**
     * Recibe y guarda un mensaje de contacto (a profesor o directiva).
     */
    public function enviar_mensaje() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/padres/dashboard');
            exit;
        }
        $tipo          = trim($_POST['tipo'] ?? '');
        $id_dest       = (int)($_POST['id_destinatario'] ?? 0);
        $titulo        = trim($_POST['titulo'] ?? '');
        $asunto        = trim($_POST['asunto'] ?? '');
        $mensaje       = trim($_POST['mensaje'] ?? '');
        
        $referer = $_SERVER['HTTP_REFERER'] ?? (URLROOT . '/padres/dashboard');
        $referer = preg_replace('/([&?])msg_(ok|error)=1&?/', '$1', $referer);
        $referer = rtrim($referer, '?&');
        $sep = (strpos($referer, '?') !== false) ? '&' : '?';

        if (empty($tipo) || $id_dest === 0 || empty($titulo) || empty($mensaje)) {
            header('Location: ' . $referer . $sep . 'msg_error=1');
            exit;
        }
        $model = $this->model('FamiliaModel');
        $model->guardarMensajeContacto($_SESSION['user_id'], $tipo, $id_dest, $titulo, $asunto, $mensaje);
        header('Location: ' . $referer . $sep . 'msg_ok=1');
        exit;
    }

    /**
     * Bandeja de entrada para las familias (mensajes recibidos y enviados).
     */
    public function mensajes() {
        $model = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];
        
        // Si se marca un mensaje como leído vía GET
        if (isset($_GET['leer']) && is_numeric($_GET['leer'])) {
            $model->marcarMensajeLeido((int)$_GET['leer'], $id_familia);
            header('Location: ' . URLROOT . '/padres/mensajes');
            exit;
        }

        $mensajes = $model->getMensajesByFamilia($id_familia);

        $data = [
            'title'    => 'Mis Mensajes',
            'mensajes' => $mensajes,
            'no_leidos' => count(array_filter((array)$mensajes, fn($m) => !$m->leido && $m->destinatario_tipo === 'familia')),
            'profesores' => $model->getAllProfesores(),
            'directivas' => $model->getAllDirectivas(),
        ];

        $this->view('padres/mensajes', $data);
    }

    /**
     * Sube o actualiza la foto de perfil de la familia.
     */
    public function subir_foto() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['foto_perfil'])) {
            header('Location: ' . URLROOT . '/padres/dashboard');
            exit;
        }

        $id_familia = $_SESSION['user_id'];
        $file       = $_FILES['foto_perfil'];
        $allowed    = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize    = 3 * 1024 * 1024; // 3 MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            header('Location: ' . URLROOT . '/padres/dashboard?foto_error=upload');
            exit;
        }
        if (!in_array(mime_content_type($file['tmp_name']), $allowed)) {
            header('Location: ' . URLROOT . '/padres/dashboard?foto_error=type');
            exit;
        }
        if ($file['size'] > $maxSize) {
            header('Location: ' . URLROOT . '/padres/dashboard?foto_error=size');
            exit;
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'familia_' . $id_familia . '_' . time() . '.' . strtolower($ext);
        $dest     = APPROOT . '/../public/assets/img/perfiles/' . $filename;

        // Eliminar foto anterior si existe
        $model       = $this->model('FamiliaModel');
        $familiaData = $model->findById($id_familia);
        if ($familiaData && !empty($familiaData->foto_perfil)) {
            $old = APPROOT . '/../public/assets/img/perfiles/' . $familiaData->foto_perfil;
            if (file_exists($old)) @unlink($old);
        }

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            $model->actualizarFoto($id_familia, $filename);
            // Actualizar sesión
            $_SESSION['foto_perfil'] = $filename;
            $referer = $_SERVER['HTTP_REFERER'] ?? URLROOT . '/padres/dashboard';
            header('Location: ' . $referer . (strpos($referer, '?') === false ? '?' : '&') . 'foto_ok=1');
        } else {
            header('Location: ' . URLROOT . '/padres/dashboard?foto_error=move');
        }
        exit;
    }

    /**
     * Vista para generar QR de asistencia (solo actividades pendientes).
     */
    public function generar_qr() {
        $model = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];

        // Obtener estudiantes de la familia
        $estudiantes = $model->getEstudiantesByFamilia($id_familia);

        // Obtener actividades futuras (pendientes) de los grupos de los estudiantes
        $actividades = $model->getProximasActividades($id_familia);

        $data = [
            'title'       => 'Generar QR de Asistencia',
            'estudiantes' => $estudiantes,
            'actividades' => $actividades,
        ];

        $this->view('padres/generar_qr', $data);
    }

    /**
     * Crea un QR de asistencia único para una actividad y estudiante.
     * Retorna JSON con el token y URL del QR.
     */
    public function crear_qr() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $id_actividad = (int)($_POST['id_actividad'] ?? 0);
        $id_estudiante = (int)($_POST['id_estudiante'] ?? 0);
        $id_familia = $_SESSION['user_id'];

        if (!$id_actividad || !$id_estudiante) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit;
        }

        // Verificar que el estudiante pertenece a la familia
        $db = new Database();
        $db->query('SELECT 1 FROM familia_estudiante WHERE id_familia_fk = :f AND id_estudiante_fk = :e LIMIT 1');
        $db->bind(':f', $id_familia);
        $db->bind(':e', $id_estudiante);
        if (!$db->single()) {
            echo json_encode(['success' => false, 'message' => 'Estudiante no autorizado']);
            exit;
        }

        // Verificar que la actividad existe y es futura (pendiente)
        $db->query('SELECT id_actividad FROM actividades WHERE id_actividad = :id AND fecha_hora_inicio >= NOW() LIMIT 1');
        $db->bind(':id', $id_actividad);
        if (!$db->single()) {
            echo json_encode(['success' => false, 'message' => 'Actividad no disponible (ya pasó o no existe)']);
            exit;
        }

        // Verificar si ya existe un QR pendiente para esta combinación
        $db->query('SELECT token, expira_en FROM qr_asistencia WHERE id_actividad_fk = :a AND id_estudiante_fk = :e AND id_familia_fk = :f AND usado = 0 AND expira_en > NOW() LIMIT 1');
        $db->bind(':a', $id_actividad);
        $db->bind(':e', $id_estudiante);
        $db->bind(':f', $id_familia);
        $existente = $db->single();

        if ($existente) {
            // Devolver el QR existente si sigue válido
            $url = URLROOT . '/padres/ver_qr/' . $existente->token;
            echo json_encode(['success' => true, 'message' => 'QR ya generado', 'qr_url' => $url, 'token' => $existente->token, 'expira_en' => $existente->expira_en]);
            exit;
        }

        // Generar token único
        $token = bin2hex(random_bytes(32));
        // Expira en 24 horas
        $expira_en = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $db->query('INSERT INTO qr_asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, token, expira_en) VALUES (:a, :f, :e, :t, :ex)');
        $db->bind(':a', $id_actividad);
        $db->bind(':f', $id_familia);
        $db->bind(':e', $id_estudiante);
        $db->bind(':t', $token);
        $db->bind(':ex', $expira_en);

        if ($db->execute()) {
            $url = URLROOT . '/padres/ver_qr/' . $token;
            echo json_encode(['success' => true, 'message' => 'QR generado', 'qr_url' => $url, 'token' => $token, 'expira_en' => $expira_en]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al generar QR']);
        }
        exit;
    }

    /**
     * Muestra el QR generado para que la familia lo presente.
     */
    public function ver_qr($token) {
        $db = new Database();
        $db->query('SELECT q.*, a.nombre_actividad, a.fecha_hora_inicio, s.nombre_sede, e.nombres AS est_nombres, e.apellidos AS est_apellidos
                    FROM qr_asistencia q
                    INNER JOIN actividades a ON q.id_actividad_fk = a.id_actividad
                    INNER JOIN sedes s ON a.id_sede_fk = s.id_sede
                    INNER JOIN estudiantes e ON q.id_estudiante_fk = e.id_estudiante
                    WHERE q.token = :token LIMIT 1');
        $db->bind(':token', $token);
        $qr = $db->single();

        if (!$qr) {
            $data = ['title' => 'QR no encontrado', 'error' => 'El código QR no existe o ha sido eliminado.'];
            $this->view('padres/ver_qr', $data);
            return;
        }

        // Verificar si pertenece a la familia actual
        if ($qr->id_familia_fk != $_SESSION['user_id']) {
            $data = ['title' => 'Acceso denegado', 'error' => 'No tienes permiso para ver este QR.'];
            $this->view('padres/ver_qr', $data);
            return;
        }

        $url_escanear = URLROOT . '/padres/escanear_qr/' . $token;
        $data = [
            'title'         => 'Tu Código QR',
            'qr'            => $qr,
            'url_escanear'  => $url_escanear,
        ];

        $this->view('padres/ver_qr', $data);
    }

    /**
     * Procesa el escaneo del QR (usado por admin/docente desde confirmar_asistencia).
     * Valida el QR y registra la asistencia si es válido.
     */
    public function escanear_qr() {
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
        $db->query('SELECT q.*, a.nombre_actividad, e.nombres AS est_nombres, e.apellidos AS est_apellidos
                    FROM qr_asistencia q
                    INNER JOIN actividades a ON q.id_actividad_fk = a.id_actividad
                    INNER JOIN estudiantes e ON q.id_estudiante_fk = e.id_estudiante
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

        // Verificar que la actividad ya empezó (opcional: permitir antes)
        if (strtotime($qr->fecha_hora_inicio) > time()) {
            echo json_encode(['success' => false, 'message' => 'La actividad aún no ha comenzado']);
            exit;
        }

        // Verificar si ya existe asistencia registrada para esta familia/estudiante/actividad
        $db->query('SELECT 1 FROM asistencia WHERE id_actividad_fk = :a AND id_estudiante_fk = :e AND id_familia_fk = :f LIMIT 1');
        $db->bind(':a', $qr->id_actividad_fk);
        $db->bind(':e', $qr->id_estudiante_fk);
        $db->bind(':f', $qr->id_familia_fk);
        if ($db->single()) {
            // Marcar QR como usado aunque ya estuviera registrada
            $db->query('UPDATE qr_asistencia SET usado = 1, usado_en = NOW() WHERE id_qr = :id');
            $db->bind(':id', $qr->id_qr);
            $db->execute();
            echo json_encode(['success' => true, 'message' => 'Asistencia ya registrada previamente', 'ya_registrado' => true, 'estudiante' => $qr->est_nombres . ' ' . $qr->est_apellidos, 'actividad' => $qr->nombre_actividad]);
            exit;
        }

        // Registrar asistencia
        $db->query('INSERT INTO asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, registrada_por_profesor_fk, presente) VALUES (:a, :f, :e, :p, 1)');
        $db->bind(':a', $qr->id_actividad_fk);
        $db->bind(':f', $qr->id_familia_fk);
        $db->bind(':e', $qr->id_estudiante_fk);
        // Registrado por "sistema QR" - usamos ID 0 o el admin que escanea
        $registrado_por = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
        $db->bind(':p', $registrado_por);

        if ($db->execute()) {
            // Marcar QR como usado
            $db->query('UPDATE qr_asistencia SET usado = 1, usado_en = NOW() WHERE id_qr = :id');
            $db->bind(':id', $qr->id_qr);
            $db->execute();

            echo json_encode(['success' => true, 'message' => 'Asistencia confirmada correctamente', 'estudiante' => $qr->est_nombres . ' ' . $qr->est_apellidos, 'actividad' => $qr->nombre_actividad]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar asistencia']);
        }
        exit;
    }
}
