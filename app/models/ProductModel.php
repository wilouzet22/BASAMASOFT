<?php
/**
 * Modelo de Producto
 *
 * Toda la lógica de acceso a datos de la tabla `productos`.
 * El controlador nunca escribe SQL directamente.
 */
class ProductModel extends Model
{
    /** Devuelve todos los productos como array asociativo */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM productos');
        return $stmt->fetchAll();
    }

    /** Busca un producto por su ID */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM productos WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Inserta un nuevo producto */
    public function create(
        string $code,
        string $name,
        string $presentation,
        float  $price,
        int    $type
    ): void {
        $sql  = 'INSERT INTO productos (codigo, nombre, presentacion, precio, id_tipo) VALUES (?,?,?,?,?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$code, $name, $presentation, $price, $type]);
    }

    /** Actualiza un producto existente */
    public function update(
        int    $id,
        string $code,
        string $name,
        string $presentation,
        float  $price,
        int    $type
    ): void {
        $sql  = 'UPDATE productos SET codigo=?, nombre=?, presentacion=?, precio=?, id_tipo=? WHERE id=?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$code, $name, $presentation, $price, $type, $id]);
    }

    /** Elimina un producto por ID */
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM productos WHERE id = ?');
        $stmt->execute([$id]);
    }
}
