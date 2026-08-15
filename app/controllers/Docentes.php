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
        $id_profesor = $_SESSION['user_id'];

        $data = [
            'title'       => 'Mis Actividades',
            'actividades' => $model->getActividadesByProfesor($id_profesor),
        ];

        $this->view('docentes/actividades', $data);
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
}
