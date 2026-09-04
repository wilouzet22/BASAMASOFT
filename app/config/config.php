<?php
date_default_timezone_set('America/Bogota');
// DB Params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'prueba1_asistencia');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URLROOT (Auto-detectado dinámicamente)
if (!defined('URLROOT')) {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])) : '';
    $baseDir = str_replace('/public', '', $scriptDir);
    $baseDir = rtrim($baseDir, '/');
    define('URLROOT', $protocol . '://' . $host . $baseDir);
}
// Site Name
define('SITENAME', 'EduSaft');

// Encryption Key for Passwords
define('ENCRYPTION_KEY', 'EduSaft_Secret_Key_128');

/**
 * Formatea números grandes en formato abreviado (ej: 8000 -> 8k, 80000 -> 80k).
 */
if (!function_exists('formatCompactNumber')) {
    function formatCompactNumber($num) {
        $num = (float)$num;
        if ($num >= 1000000) {
            $val = $num / 1000000;
            return ($val == (int)$val ? (int)$val : number_format($val, 1)) . 'M';
        }
        if ($num >= 1000) {
            $val = $num / 1000;
            return ($val == (int)$val ? (int)$val : number_format($val, 1)) . 'k';
        }
        return (string)(int)$num;
    }
}
