<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>

<div class="container mx-auto mt-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Panel de Administrador</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Usuarios</h3>
            <p>Gestionar padres, docentes y alumnos.</p>
            <a href="<?php echo URLROOT; ?>/admin/usuarios" class="text-blue-500 mt-2 inline-block">Ir a Usuarios</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Configuración</h3>
            <p>Ajustes generales del sistema.</p>
            <a href="<?php echo URLROOT; ?>/admin/configuracion" class="text-blue-500 mt-2 inline-block">Ir a Configuración</a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
