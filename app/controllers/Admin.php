<?php
/**
 * Admin Controller
 * Panel de administración — protegido, solo rol 'administrador'.
 */
class Admin extends Controller {

    public function __construct() {
        $this->_requireAdmin();
    }

    /** Verifica que el usuario sea administrador */
    private function _requireAdmin() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
    }

    public function index() {
        $this->dashboard();
    }

    /**
     * Dashboard con estadísticas reales de la BD.
     */
    public function dashboard() {
        $model = $this->model('AdministradorModel');

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
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Vista de usuarios generales (redirige a profesores por defecto)
     */
    public function usuarios() {
        $this->profesores();
    }

    /**
     * Vista de profesores
     */
    public function profesores() {
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
    public function familias() {
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
    public function estudiantes() {
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
    public function asistencias() {
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
    public function sedes() {
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
    public function actividades() {
        $model = $this->model('ActividadModel');

        $data = [
            'title'       => 'Gestión de Actividades',
            'actividades' => $model->getAllActividadesVisitante(),
        ];

        $this->view('admin/actividades', $data);
    }

    /**
     * Vista de Auditoría — Actividad reciente del sistema.
     */
    public function auditoria() {
        $model = $this->model('AdministradorModel');

        $data = [
            'title'  => 'Auditoría del Sistema',
            'logs'   => $model->getRecentLogs(50),
        ];

        $this->view('admin/auditoria', $data);
    }
}
