<?php
/**
 * Controlador de páginas estáticas / informativas
 */
class ControllerPages extends Controller
{
    public function index(): void
    {
        $this->render('pages/home');
    }

    public function about(): void
    {
        $this->render('pages/about');
    }

    public function services(): void
    {
        $this->render('pages/services');
    }

    public function catalog(): void
    {
        $this->render('pages/catalog');
    }
}
