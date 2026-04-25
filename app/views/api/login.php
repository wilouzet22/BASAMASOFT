<?php
// Placeholder para lógica de login
// En el futuro validará contra la base de datos y redirigirá según el rol

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// Simulación simple
if ($usuario == 'admin') {
    header('Location: ../admin/dashboard.php');
} elseif ($usuario == 'profe') {
    header('Location: ../docentes/dashboard.php');
} elseif ($usuario == 'familia') {
    header('Location: ../padres/dashboard.php');
} else {
    echo "Usuario no reconocido. Intenta con 'admin', 'profe' o 'familia'. <a href='../index.php'>Volver</a>";
}
?>
