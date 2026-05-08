<?php 
    define('URL_BASE', 'http://localhost/proyectoMVC/proyectoMVC/');
    
    $getcontrolador="paginas";
    $getaccion="inicio";

    if(isset($_GET['url'])) {
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        if (isset($url[0])) {
            $getcontrolador = $url[0];
        }
        if (isset($url[1])) {
            $getaccion = $url[1];
        }
        if(isset($url[2])){
            $_GET['id'] = $url[2]; 
        }
    }
    include_once ("vistas/plantilla.php");
?>