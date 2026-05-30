<script>
function confirmarEliminar(id) {
    if (confirm('¿Está seguro de eliminar el registro #' + id + '?')) {
        window.location.href = '<?= URL_BASE ?>products/delete/' + id;
    }
}
</script>

<div class="container">
    <div class="d-flex justify-content-between align-items-center py-3">
        <h2>Productos</h2>
        <a href="<?= URL_BASE ?>products/create" class="btn btn-primary">+ Crear Producto</a>
    </div>

    <table id="productos" class="table table-bordered border-primary table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Id</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Presentación</th>
                <th>Precio</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['codigo']) ?></td>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td><?= htmlspecialchars($p['presentacion']) ?></td>
                <td><?= htmlspecialchars($p['precio']) ?></td>
                <td><?= htmlspecialchars($p['id_tipo']) ?></td>
                <td class="text-center">
                    <a href="<?= URL_BASE ?>products/edit/<?= $p['id'] ?>"
                       class="btn btn-sm btn-warning">Editar</a>
                    <button class="btn btn-sm btn-danger"
                            onclick="confirmarEliminar(<?= $p['id'] ?>)">Eliminar</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
