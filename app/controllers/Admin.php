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
    public function crear_actividad() {
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
                // Flash message or similar would be better, but basic redirect for now
                header('Location: ' . URLROOT . '/admin/actividades');
                exit;
            }

            // Lógica especial para "Inicio de año"
            if (strtolower(trim($data['nombre_actividad'])) === 'inicio de año') {
                if (date('m-d') !== '01-01') {
                    // Si no es estrictamente el 1 de enero
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
    public function grupos() {
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
    public function crear_grupo() {
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
    public function eliminar_grupo($id) {
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
    public function auditoria() {
        $model = $this->model('AdministradorModel');

        $data = [
            'title'  => 'Auditoría del Sistema',
            'logs'   => $model->getRecentLogs(50),
        ];

        $this->view('admin/auditoria', $data);
    }
}
