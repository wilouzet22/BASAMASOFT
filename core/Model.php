<?php
/**
 * Modelo base
 *
 * Provee acceso a la instancia PDO para los modelos hijos.
 */
abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
