<?php
/**
 * Docentes Controller
 * Panel de docentes — protegido, solo rol 'profesor'.
 */
class Docentes extends Controller {

    public function __construct() {
        $this->_requireProfesor();
    }

    /** Verifica que el usuario sea profesor */
    private function _requireProfesor() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'profesor') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
    }

    public function index() {
        $this->dashboard();
    }

    /**
     * Dashboard del docente con sus grupos, conteos y asistencias recientes.
     */
    public function dashboard() {
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
    public function actividades() {
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
    public function asistencia() {
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
}
