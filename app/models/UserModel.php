<?php
/**
 * Modelo de Usuario
 *
 * Toda la lógica de acceso a datos de la tabla `usuarios`.
 *
 * NOTA DE SEGURIDAD: las contraseñas deberían almacenarse
 * con password_hash() y verificarse con password_verify().
 * Esta implementación conserva el comportamiento del proyecto
 * original para no romper una BD existente.
 */
class UserModel extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT id, perfil, nombre, correo FROM usuarios');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, perfil, nombre, correo FROM usuarios WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Verifica credenciales y devuelve el usuario o null */
    public function findByCredentials(string $email, string $password): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM usuarios WHERE correo = :correo AND clave = :clave'
        );
        $stmt->execute([':correo' => $email, ':clave' => $password]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Registra un nuevo usuario */
    public function create(
        string $profile,
        string $name,
        string $email,
        string $password
    ): void {
        $sql  = 'INSERT INTO usuarios (perfil, nombre, correo, clave) VALUES (?,?,?,?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$profile, $name, $email, $password]);
    }
}
