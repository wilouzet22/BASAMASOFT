<?php
    include_once("./modelos/producto.php");
?>

<div class="container">
    <div class="row d-flex flex-grid justify-content-center">
        <div class="col-md-8">
            <div class="card card-color">
                <div class="card-header text-white bg-primary text-center">
                    Creación de nuevos productos
                </div>

                <div class="card-body">
                    <div class="container col-sm-6">
                        <form method="POST" action="<?php echo constant('URL_BASE'); ?>productos/controladorCrearProducto">
                            <div class = "form-group">
                                <label for="inputcodigo"> <strong> Código de producto </strong></label>
                                <input type="text" name="txtcodigo" class="form-control" id="inputcodigo" placeholder="Digite el codigo del producto">
                            </div>

                            <div class = "form-group">
                                <label for="inputnombre"> <strong> Nombre del producto </strong></label>
                                <input type="text" name="txtnombre" class="form-control" id="inputnombre" placeholder="Digite el Nombre del producto">
                            </div>

                            <div class = "form-group">
                                <label for="inputpresentacion"> <strong> Presentación </strong></label>
                                <input type="text" name="txtpresentacion" class="form-control" id="inputpresentacion" placeholder="Digite la presentación">
                            </div>

                            <div class = "form-group">
                                <label for="inputprecio"> <strong> Precio del producto </strong></label>
                                <input type="text" name="txtprecio" class="form-control" id="inputprecio" placeholder="Digite el precio del producto">
                            </div>

                            <div class = "form-group">
                                <label for="inputtipo"> <strong> Tipo del producto </strong></label>
                                <input type="text" name="txttipo" class="form-control" id="inputtipo" placeholder="Digite el tipo del producto">
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary"> Crear producto  </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
