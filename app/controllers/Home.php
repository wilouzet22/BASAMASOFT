<?php
class Home extends Controller {
    private $actividadModel;

    public function __construct() {
        $this->actividadModel = $this->model('ActividadModel');
    }

    public function index() {
        $actividades = $this->actividadModel->getProximasActividadesVisitante(3);
        $data = [
            'title' => 'EduSaft',
            'actividades' => $actividades
        ];

        $this->view('home/index', $data);
    }

    public function calendario() {
        $actividades = $this->actividadModel->getAllActividadesVisitante();
        $data = [
            'title' => 'Calendario de Actividades',
            'actividades' => $actividades
        ];

        $this->view('home/calendario', $data);
    }

    public function terminos() {
        $data = [
            'title' => 'Términos y Condiciones'
        ];

        $this->view('home/terminos', $data);
    }

    public function errores() {
        $data = [
            'title' => 'Error 404'
        ];
        $this->view('home/errores', $data);
    }
}
