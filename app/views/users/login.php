<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">Ingreso al Sistema</div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= URL_BASE ?>users/authenticate">
                        <div class="form-group mb-3">
                            <label for="inputEmail">Correo Electrónico</label>
                            <input type="email" name="txtEmail" class="form-control"
                                   id="inputEmail" placeholder="correo@ejemplo.com" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="inputPassword">Contraseña</label>
                            <input type="password" name="txtPassword" class="form-control"
                                   id="inputPassword" placeholder="Contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </form>
                    <p class="text-center mt-3">
                        ¿No tienes cuenta?
                        <a href="<?= URL_BASE ?>users/register">Regístrate aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
