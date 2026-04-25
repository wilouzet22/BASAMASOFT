<?php include '../assets/includes/header.php'; ?>


<div class="container mx-auto mt-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Panel de Docentes</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Asistencia</h3>
            <p>Registrar asistencia por QR.</p>
            <a href="asistencia.php" class="text-blue-500 mt-2 inline-block">Ir a Asistencia</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-2">Reportes</h3>
            <p>Ver estadísticas de participación.</p>
            <a href="reportes.php" class="text-blue-500 mt-2 inline-block">Ir a Reportes</a>
        </div>
    </div>
</div>

<?php include '../assets/includes/footer.php'; ?>
