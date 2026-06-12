<?php
session_start();
// Load Config
require_once 'app/config/config.php';

// Autoload Core, Models and Controllers
spl_autoload_register(function($className){
    $paths = [
        'app/core/'        . $className . '.php',
        'app/models/'      . $className . '.php',
        'app/controllers/' . $className . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Init Core Library
$init = new App;
