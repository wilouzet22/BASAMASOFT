<?php
    include_once("conexion.php");
    include_once("modelos/producto.php");


    class ControladorProductos {

        public function mostrar(){
            $datosProductos=Producto::consultarProductos();
            include_once "vistas/productos/mostrar.php";
        }

        public function crear(){
            include_once "vistas/productos/crear.php";
        }

        public function actualizarRegistro(){
            $idProducto=$_GET['id'];
            $DatosProducto=Producto::BuscarProducto($idProducto);
            //print_r($DatosProducto); para verificar que sirve
            
            include_once "vistas/productos/actualizar.php";
            if($_POST){
                
                $pro=$_POST["txtcodigo"];
                $nom=$_POST["txtnombre"];
                $pres=$_POST["txtpresentacion"];
                $prec=$_POST["txtprecio"];
                $tipo=$_POST["txttipo"];
                Producto::ActualizarProducto($idProducto,$pro,$nom,$pres,$prec,$tipo);
                echo '<script>';
                echo "window.location.href='" . URL_BASE . "productos/mostrar' ";
                echo '</script>';
            }
            include_once "./vistas/productos/actualizar.php";
        }


        public function controladorCrearProducto() {
            if ($_POST){
                $pro=$_POST["txtcodigo"];
                $nom=$_POST["txtnombre"];
                $pres=$_POST["txtpresentacion"];
                $prec=$_POST["txtprecio"];
                $tipo=$_POST["txttipo"];
                Producto::CrearProducto($pro,$nom,$pres,$prec,$tipo);
                header("Location: " . URL_BASE . "productos/mostrar");
            }
           
            
        }

        public function borrarRegistro() {
            if ($_GET['id']){
                $idProducto=$_GET['id'];
                Producto::BorrarProducto($idProducto);
                echo '<script>';
                echo "window.location.href='" . URL_BASE . "productos/mostrar' ";
                echo '</script>';
            }
        }

    }  

?>