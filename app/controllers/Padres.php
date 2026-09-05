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
        $this->camino();
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

    // =========================================================================
    // DEPRECATED - flujo QR invertido (padre genera QR, docente escanea)
    // Ya no se usa. El nuevo flujo es: docente genera QR por actividad,
    // padre lo escanea desde su celular via confirmar_asistencia_actividad().
    // =========================================================================

    /** @deprecated */
    public function generar_qr() {
        $model = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];
        $estudiantes = $model->getEstudiantesByFamilia($id_familia);
        $actividades = $model->getProximasActividades($id_familia);
        $data = ['title' => 'Generar QR de Asistencia', 'estudiantes' => $estudiantes, 'actividades' => $actividades];
        $this->view('padres/generar_qr', $data);
    }

    /** @deprecated */
    public function crear_qr() {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Flujo QR invertido — el docente es quien genera el QR ahora.']);
        exit;
    }

    /** @deprecated */
    public function ver_qr($token = null) {
        $data = ['title' => 'Flujo cambiado', 'error' => 'Este flujo ya no está disponible. El docente muestra el QR en pantalla y tú lo escaneas desde tu celular.'];
        $this->view('padres/ver_qr', $data);
    }

    /** @deprecated */
    public function escanear_qr() {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Flujo QR invertido — usa confirmar_asistencia_actividad.']);
        exit;
    }

    // =========================================================================
    // NUEVO FLUJO: padre escanea el QR del docente con su celular
    // =========================================================================

    /**
     * El padre escanea el QR de la actividad (generado por el docente/admin).
     * URL: /padres/confirmar_asistencia_actividad/{token}
     *
     * Valida el token, registra asistencia para todos los hijos de la familia
     * y muestra una vista de confirmación. El token NO se marca como "usado"
     * (permite múltiples familias escanearlo hasta que expire en 60 min).
     */
    public function confirmar_asistencia_actividad($token = null)
    {
        if (!$token) {
            $data = ['title' => 'QR inválido', 'error' => 'Token no proporcionado.'];
            $this->view('padres/confirmacion_asistencia', $data);
            return;
        }

        $id_familia = (int)$_SESSION['user_id'];
        $db = new Database();

        // Buscar QR de actividad (id_familia_fk IS NULL = QR de docente)
        $db->query('SELECT q.*, a.nombre_actividad, a.fecha_hora_inicio, s.nombre_sede
                    FROM qr_asistencia q
                    INNER JOIN actividades a ON q.id_actividad_fk = a.id_actividad
                    LEFT JOIN sedes s ON a.id_sede_fk = s.id_sede
                    WHERE q.token = :token AND q.id_familia_fk IS NULL
                    LIMIT 1');
        $db->bind(':token', $token);
        $qr = $db->single();

        if (!$qr) {
            $data = ['title' => 'QR no válido', 'error' => 'Código QR no encontrado. Pide al docente que muestre el QR actualizado.'];
            $this->view('padres/confirmacion_asistencia', $data);
            return;
        }

        if (strtotime($qr->expira_en) < time()) {
            $data = ['title' => 'QR expirado', 'error' => 'Este código QR ya expiró. Pide al docente que lo renueve.'];
            $this->view('padres/confirmacion_asistencia', $data);
            return;
        }

        // Verificar si esta familia ya confirmó asistencia para esta actividad
        $db->query('SELECT 1 FROM asistencia WHERE id_actividad_fk = :a AND id_familia_fk = :f LIMIT 1');
        $db->bind(':a', $qr->id_actividad_fk);
        $db->bind(':f', $id_familia);
        if ($db->single()) {
            $data = [
                'title'     => 'Ya registrado',
                'actividad' => $qr->nombre_actividad,
                'sede'      => $qr->nombre_sede,
                'fecha'     => $qr->fecha_hora_inicio,
                'mensaje'   => 'Tu familia ya tiene asistencia registrada para esta actividad.',
                'ya_estaba' => true,
            ];
            $this->view('padres/confirmacion_asistencia', $data);
            return;
        }

        // Registrar asistencia para todos los hijos de la familia
        $db->query('SELECT id_estudiante_fk FROM familia_estudiante WHERE id_familia_fk = :f');
        $db->bind(':f', $id_familia);
        $hijos = $db->resultSet();

        $registrados = 0;
        foreach ($hijos as $hijo) {
            $db->query('SELECT 1 FROM asistencia WHERE id_actividad_fk = :a AND id_familia_fk = :f AND id_estudiante_fk = :e LIMIT 1');
            $db->bind(':a', $qr->id_actividad_fk);
            $db->bind(':f', $id_familia);
            $db->bind(':e', $hijo->id_estudiante_fk);
            if ($db->single()) continue;

            $db->query('INSERT INTO asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, registrada_por_profesor_fk, presente) VALUES (:a, :f, :e, NULL, 1)');
            $db->bind(':a', $qr->id_actividad_fk);
            $db->bind(':f', $id_familia);
            $db->bind(':e', $hijo->id_estudiante_fk);
            $db->execute();
            $registrados++;
        }

        if (empty($hijos)) {
            $db->query('SELECT 1 FROM asistencia WHERE id_actividad_fk = :a AND id_familia_fk = :f AND id_estudiante_fk IS NULL LIMIT 1');
            $db->bind(':a', $qr->id_actividad_fk);
            $db->bind(':f', $id_familia);
            if (!$db->single()) {
                $db->query('INSERT INTO asistencia (id_actividad_fk, id_familia_fk, id_estudiante_fk, registrada_por_profesor_fk, presente) VALUES (:a, :f, NULL, NULL, 1)');
                $db->bind(':a', $qr->id_actividad_fk);
                $db->bind(':f', $id_familia);
                $db->execute();
                $registrados++;
            }
        }

        $data = [
            'title'      => '¡Asistencia Confirmada!',
            'actividad'  => $qr->nombre_actividad,
            'sede'       => $qr->nombre_sede,
            'fecha'      => $qr->fecha_hora_inicio,
            'registrados'=> $registrados,
            'ya_estaba'  => false,
        ];
        $this->view('padres/confirmacion_asistencia', $data);
    }
}
