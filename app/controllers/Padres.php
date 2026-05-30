<?php
class Padres extends Controller {
    public function index() {
        $this->view('padres/dashboard');
    }

    public function dashboard() {
        $this->view('padres/dashboard');
    }

    public function camino() {
        $this->view('padres/camino');
    }

    public function puntos() {
        $this->view('padres/puntos');
    }
}
