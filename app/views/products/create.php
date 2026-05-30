<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white bg-primary text-center">
                    Creación de Nuevo Producto
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= URL_BASE ?>products/store">
                        <div class="form-group mb-3">
                            <label><strong>Código de Producto</strong></label>
                            <input type="text" name="txtcodigo" class="form-control"
                                   placeholder="Código" required>
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Nombre del Producto</strong></label>
                            <input type="text" name="txtnombre" class="form-control"
                                   placeholder="Nombre" required>
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Presentación</strong></label>
                            <input type="text" name="txtpresentacion" class="form-control"
                                   placeholder="Presentación">
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Precio</strong></label>
                            <input type="number" step="0.01" name="txtprecio"
                                   class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="form-group mb-3">
                            <label><strong>Tipo de Producto</strong></label>
                            <input type="number" name="txttipo" class="form-control"
                                   placeholder="ID tipo" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Crear Producto</button>
                            <a href="<?= URL_BASE ?>products/list" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
