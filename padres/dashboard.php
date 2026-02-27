<?php include '../assets/includes/header.php'; ?>
<?php include '../assets/includes/navbar.php'; ?>

<div class="container mx-auto mt-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Hola, Familia [Apellido]</h1>
    
    <!-- Gamification Path Preview -->
    <div class="bg-blue-100 p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-bold mb-4">Tu Camino</h2>
        <div class="h-32 bg-gray-200 rounded flex items-center justify-center">
            <p class="text-gray-500">Visualización del camino (Próximamente)</p>
        </div>
        <div class="mt-4 text-center">
            <a href="camino.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ver Camino Completo</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Mi Avatar</h3>
            <p>Personaliza tu representación familiar.</p>
            <a href="perfil.php" class="text-blue-500 mt-2 inline-block">Editar Avatar</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Notificaciones</h3>
            <p>Tienes 0 notificaciones nuevas.</p>
        </div>
    </div>
</div>

<?php include '../assets/includes/footer.php'; ?>
