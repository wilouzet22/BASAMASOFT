<?php
// DB Params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'prueba1_asistencia');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URLROOT
define('URLROOT', 'http://localhost/BASAMASOFT');
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
