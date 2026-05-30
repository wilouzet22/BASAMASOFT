<?php
/**
 * Controlador base
 *
 * Provee render() para que los controladores hijos
 * puedan cargar vistas sin usar include_once directamente.
 * También inyecta variables en el scope de la vista.
 */
abstract class Controller
{
    /**
     * Renderiza una vista dentro del layout principal.
     *
     * @param string $view   Ruta relativa desde app/views/  (ej. "products/list")
     * @param array  $data   Variables que estarán disponibles en la vista
     * @param bool   $layout Si false, renderiza sin layout (útil para fragmentos AJAX)
     */
    protected function render(string $view, array $data = [], bool $layout = true): void
    {
        // Extraer variables para que estén disponibles en la vista
        extract($data);

        $viewFile = BASE_PATH . 'app/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Vista no encontrada: {$viewFile}");
        }

        if ($layout) {
            // El layout espera que $content sea el HTML de la vista
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            require BASE_PATH . 'app/views/layouts/main.php';
        } else {
            require $viewFile;
        }
    }

    /** Redirige a una URL relativa a URL_BASE */
    protected function redirect(string $path): void
    {
        header('Location: ' . URL_BASE . ltrim($path, '/'));
        exit;
    }
}
