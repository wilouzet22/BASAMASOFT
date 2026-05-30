<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white bg-primary text-center">
                    Actualización de Producto
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= URL_BASE ?>products/update/<?= $product['id'] ?>">
                        <div class="form-group mb-3">
                            <label><strong>ID</strong></label>
                            <input type="text" class="form-control"
                                   value="<?= htmlspecialchars($product['id']) ?>" disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Código</strong></label>
                            <input type="text" name="txtcodigo" class="form-control"
                                   value="<?= htmlspecialchars($product['codigo']) ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Nombre</strong></label>
                            <input type="text" name="txtnombre" class="form-control"
                                   value="<?= htmlspecialchars($product['nombre']) ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Presentación</strong></label>
                            <input type="text" name="txtpresentacion" class="form-control"
                                   value="<?= htmlspecialchars($product['presentacion']) ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Precio</strong></label>
                            <input type="number" step="0.01" name="txtprecio"
                                   class="form-control"
                                   value="<?= htmlspecialchars($product['precio']) ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Tipo</strong></label>
                            <input type="number" name="txttipo" class="form-control"
                                   value="<?= htmlspecialchars($product['id_tipo']) ?>" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="<?= URL_BASE ?>products/list" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
