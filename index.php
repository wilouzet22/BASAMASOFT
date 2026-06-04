<?php
session_start();
// Load Config
require_once 'app/config/config.php';

// Autoload Core Libraries
spl_autoload_register(function($className){
    require_once 'app/core/' . $className . '.php';
});

// Init Core Library
$init = new App;
