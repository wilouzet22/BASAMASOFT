<?php
class Home extends Controller {
    public function __construct() {
        // No specific model needed for landing page yet
    }

    public function index() {
        $data = [
            'title' => 'EduSaft'
        ];

        $this->view('home/index', $data);
    }

    public function terminos() {
        $data = [
            'title' => 'Términos y Condiciones'
        ];

        $this->view('home/terminos', $data);
    }

    public function errores() {
        $this->view('home/errores');
    }
}
