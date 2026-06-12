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

        $data = [
            'title'       => 'Camino de Éxito',
            'estudiantes' => $estudiantes,
            'estadisticas'=> $estadisticas,
        ];

        $this->view('padres/camino', $data);
    }

    /**
     * Vista de puntos/gamificación.
     */
    public function puntos() {
        $model     = $this->model('FamiliaModel');
        $id_familia = $_SESSION['user_id'];

        $data = [
            'title'       => 'Mis Puntos',
            'asistencias' => $model->getAsistenciasByFamilia($id_familia, 50),
        ];

        $this->view('padres/puntos', $data);
    }
}
