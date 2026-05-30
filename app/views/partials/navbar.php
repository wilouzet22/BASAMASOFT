<?php
$userProfile = $_SESSION['user']['perfil'] ?? '';
$userName    = $_SESSION['user']['nombre'] ?? '';
?>

<?php if (!isset($_SESSION['user'])): ?>
    <!-- Navbar: Sin sesión -->
    <nav class="navbar navbar-expand-sm navbar-light bg-info">
        <div class="container">
            <a class="navbar-brand" href="<?= URL_BASE ?>pages/index">Inventarios</a>
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL_BASE ?>pages/index">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL_BASE ?>pages/about">Nosotros</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Usuarios</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= URL_BASE ?>users/login">Ingresar</a>
                            <a class="dropdown-item" href="<?= URL_BASE ?>users/register">Registrarse</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<?php elseif ($userProfile === 'administrador'): ?>
    <!-- Navbar: Administrador -->
    <nav class="navbar navbar-expand-sm navbar-light bg-primary">
        <div class="container">
            <a class="navbar-brand text-white" href="<?= URL_BASE ?>pages/index">Inventarios</a>
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= URL_BASE ?>pages/index">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="dropdown">Productos</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= URL_BASE ?>products/list">Ver Productos</a>
                            <a class="dropdown-item" href="<?= URL_BASE ?>products/create">Crear Producto</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= URL_BASE ?>pages/services">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= URL_BASE ?>pages/about">Nosotros</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item d-flex align-items-center me-2">
                        <strong class="text-white">Hola, <?= htmlspecialchars($userName) ?> → admin</strong>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="dropdown">Usuarios</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= URL_BASE ?>users/list">Ver Usuarios</a>
                            <a class="dropdown-item" href="<?= URL_BASE ?>users/register">Registrar Usuario</a>
                            <a class="dropdown-item" href="<?= URL_BASE ?>users/logout">Salir</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<?php elseif ($userProfile === 'usuario'): ?>
    <!-- Navbar: Usuario regular -->
    <nav class="navbar navbar-expand-sm navbar-light bg-warning">
        <div class="container">
            <a class="navbar-brand" href="<?= URL_BASE ?>pages/index">Inventarios</a>
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL_BASE ?>pages/index">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL_BASE ?>pages/services">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL_BASE ?>pages/about">Nosotros</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item d-flex align-items-center me-2">
                        <strong>Hola, <?= htmlspecialchars($userName) ?> → usuario</strong>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL_BASE ?>users/logout">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>
