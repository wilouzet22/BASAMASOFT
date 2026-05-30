<?php
/**
 * Punto de entrada único (Front Controller)
 * Toda petición HTTP pasa por aquí gracias al .htaccess
 */

define('BASE_PATH', __DIR__ . '/');
define('URL_BASE',  'http://localhost/proyectoMVC/');

// Autoloader simple: carga clases desde app/
spl_autoload_register(function (string $class) {
    $paths = [
        BASE_PATH . 'core/',
        BASE_PATH . 'app/controllers/',
        BASE_PATH . 'app/models/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Iniciar sesión globalmente
session_start();

// Arrancar el router
require_once BASE_PATH . 'core/Router.php';
$router = new Router();
$router->dispatch();
