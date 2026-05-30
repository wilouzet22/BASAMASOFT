<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header">Registro de Nuevos Usuarios</div>
                <div class="card-body">
                    <form method="POST" action="<?= URL_BASE ?>users/store">
                        <!-- Perfil por defecto, oculto -->
                        <input type="hidden" name="txtperfil" value="usuario">

                        <div class="form-group mb-3">
                            <label for="inputNombre">Nombre Completo</label>
                            <input type="text" name="txtnombre" class="form-control"
                                   id="inputNombre" placeholder="Nombre y apellido" required>
                        </div>
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
                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                    </form>
                    <p class="text-center mt-3">
                        ¿Ya tienes cuenta?
                        <a href="<?= URL_BASE ?>users/login">Ingresar aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
