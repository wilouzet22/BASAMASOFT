<?php
    class Producto{
        public $idProducto;
        public $codProducto;
        public $nombreProducto;
        public $presentacionProducto;
        public $precioProducto;
        public $tipoProducto;

    public function __construct ($idP,$codP,$nombreP,$presentacionP,$precioP,$tipoP){
        $this->idProducto=$idP;
        $this->codProducto=$codP;
        $this->nombreProducto=$nombreP;
        $this->presentacionProducto=$presentacionP;
        $this->precioProducto=$precioP;
        $this->tipoProducto=$tipoP;
    }

    // leer o consultar los productos

    public static function consultarProductos(){
        $listaProductos=[];
        $dbConexion=baseDatos::crearInstancia();
        $sql=$dbConexion->query ("SELECT * FROM productos");
        foreach($sql->fetchall() as $producto){
            $listaProductos[]=new Producto($producto['id'],$producto['codigo'],$producto['nombre'],$producto['presentacion'],$producto['precio'],$producto['id_tipo']);
        }
        return $listaProductos;
    }

    public static function CrearProducto($codP,$nombreP,$presentacionP,$precioP,$tipoP){
        $conexionBaseDatos=BaseDatos::crearInstancia();
        $sql=$conexionBaseDatos -> prepare ("INSERT INTO productos(codigo, nombre, presentacion, precio, id_tipo)
                                            values(?,?,?,?,?)");
        $sql -> execute(array($codP,$nombreP,$presentacionP,$precioP,$tipoP));

    }

    public static function BorrarProducto($idProducto){
        $conexionBaseDatos=BaseDatos::crearInstancia();
        $sql=$conexionBaseDatos -> prepare ("DELETE FROM productos WHERE id=?");
        $sql->execute (array($idProducto));

    }


    public static function ActualizarProducto($id,$pro,$nom,$pres,$prec,$tipo){
        $conexionBaseDatos=BaseDatos::crearInstancia();
        $sql=$conexionBaseDatos -> prepare ("UPDATE productos SET codigo=?, nombre=?, presentacion=?, precio=?, id_tipo=?  WHERE id=?");
        $sql->execute (array($pro,$nom,$pres,$prec,$tipo,$id));

    }


    public static function BuscarProducto($idProducto){
        $conexionBaseDatos=BaseDatos::crearInstancia();
        $sql=$conexionBaseDatos -> prepare ("SELECT * FROM productos WHERE id=?");
        $sql->execute (array($idProducto));
        $producto=$sql->fetch();
        return new Producto($producto['id'],$producto['codigo'],$producto['nombre'],$producto['presentacion'],$producto['precio'],$producto['id_tipo']);

    }

    }

?>