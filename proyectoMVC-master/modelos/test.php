<?php
    // Conexión a la base de datos
    $host = "hostname";   //hostname es el nombre o direccion ip del servidor donde esta alojada la base de datos
    $username = "username";   //nombre de usuario con el cual se accede a la base de datos
    $password = "password";   //la contraseña del usuario que se utiliza para acceder a la base de datos
    $dbname = "dbname";    //nombre de la base de datos
    $conn = mysqli_connect($host, $username, $password, $dbname);   //establecer conexion con la base de datos

    // Comprobar la conexión
    if (!$conn) {   //si no se establece conexion
        die("Conexión fallida: " . mysqli_connect_error());  //muestra el error de conexion
    }

    // Recibir datos del formulario
    $nombre_producto = $_POST['nombre_producto'];  //recibe el dato del campo nombre_producto del formulario
    $descripcion = $_POST['descripcion'];   //recibe el dato del campo descripcion del formulario
    $precio = $_POST['precio'];   //recibe el dato del campo precio del formulario
    $categoria = $_POST['categoria'];   //recibe el dato del campo categoria del formulario

    // Crear la consulta
    $query = "INSERT INTO productos (nombre_producto, descripcion, precio, categoria) VALUES ('$nombre_producto', '$descripcion', '$precio', '$categoria')";  //crea la consulta sql para insertar los datos en la tabla productos

    // Ejecutar la consulta
    if (mysqli_query($conn, $query)) {   //ejecuta la consulta
        echo "Producto insertado correctamente.";  //si la consulta se ejecuto correctamente muestra un mensaje de exito
    } else {
        echo "Error al insertar el producto: " . mysqli_error($conn);  //si no se ejecuto correctamente muestra
