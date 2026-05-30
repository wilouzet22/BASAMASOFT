<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">Editar Usuario</div>
                <div class="card-body">
                    <form method="POST" action="<?= URL_BASE ?>users/update/<?= $user['id'] ?>">
                        <div class="form-group mb-3">
                            <label>Nombre</label>
                            <input type="text" name="txtnombre" class="form-control"
                                   value="<?= htmlspecialchars($user['nombre']) ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Correo</label>
                            <input type="email" name="txtEmail" class="form-control"
                                   value="<?= htmlspecialchars($user['correo']) ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Perfil</label>
                            <select name="txtperfil" class="form-control">
                                <option value="usuario" <?= $user['perfil'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                                <option value="administrador" <?= $user['perfil'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="<?= URL_BASE ?>users/list" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
