<?php
/**
 * Router / Front Controller
 *
 * Lee la URL, extrae controlador + acción + parámetro opcional,
 * instancia el controlador y ejecuta el método.
 *
 * Formato de URL:  /controlador/accion/id
 */
class Router
{
    private string $controller = 'pages';
    private string $action     = 'index';

    public function dispatch(): void
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $segments = explode('/', $url);

            if (!empty($segments[0])) {
                $this->controller = $segments[0];
            }
            if (!empty($segments[1])) {
                $this->action = $segments[1];
            }
            if (!empty($segments[2])) {
                $_GET['id'] = $segments[2];
            }
        }

        $controllerClass = 'Controller' . ucfirst($this->controller);
        $controllerFile  = BASE_PATH . 'app/controllers/' . $controllerClass . '.php';

        if (!file_exists($controllerFile)) {
            $this->notFound();
            return;
        }

        require_once $controllerFile;
        $obj    = new $controllerClass();
        $action = $this->action;

        if (!method_exists($obj, $action)) {
            $this->notFound();
            return;
        }

        $obj->$action();
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '<h1>404 — Página no encontrada</h1>';
    }
}
