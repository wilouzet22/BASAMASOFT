<?php
/**
 * Conexión a base de datos — Singleton PDO
 *
 * Uso:  $pdo = Database::getInstance();
 */
class Database
{
    private static ?PDO $instance = null;

    // ── Credenciales ──────────────────────────────────────────────
    private static string $host   = 'localhost';
    private static string $dbname = 'basemvc';
    private static string $user   = 'root';
    private static string $pass   = '';
    // ─────────────────────────────────────────────────────────────

    private function __construct() {}   // Evita instanciación directa
    private function __clone()     {}   // Evita clonación

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbname . ';charset=utf8mb4';
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            self::$instance = new PDO($dsn, self::$user, self::$pass, $options);
        }
        return self::$instance;
    }
}
