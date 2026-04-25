<?php
class Docentes extends Controller {
    public function index() {
        $this->view('docentes/dashboard');
    }

    public function dashboard() {
        $this->view('docentes/dashboard');
    }

    public function actividades() {
        $this->view('docentes/actividades');
    }

    public function asistencia() {
        $this->view('docentes/asistencia');
    }
}
