<?php
class Admin extends Controller {
    public function __construct() {
        // Add auth check here later
    }

    public function index() {
        $this->view('admin/dashboard');
    }

    public function dashboard() {
        $this->view('admin/dashboard');
    }

    public function usuarios() {
        $this->view('admin/usuarios');
    }

    public function asistencias() {
        $this->view('admin/asistencias');
    }
}
