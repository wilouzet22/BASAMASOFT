<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Aquí puedes agregar la lógica de autenticación
// Por ahora, simplemente verificamos si el usuario está autenticado
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Aquí puedes agregar la lógica de autenticación
    // Por ahora, simplemente verificamos si el usuario está autenticado
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>