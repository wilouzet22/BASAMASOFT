<?php
session_start();
$isLoggedIn = isset($_SESSION['usuario']);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EduSaft - Inicio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        font-family: "Poppins", sans-serif;
      }
    </style>
  </head>
  <body class="bg-gray-50">
    <!-- Navbar -->
    <?php include 'assets/includes/navbar.php'; ?>

    <!-- Hero Slider -->
    <div class="relative pt-16 h-[600px] overflow-hidden group">
        <!-- Slides -->
        <div class="slider-container relative h-full w-full">
            <!-- Slide 1 -->
            <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-100">
                <img src="assets/img/montaña.png" alt="Bienvenida" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-5xl font-bold mb-4">Bienvenidos a EduSaft</h1>
                        <p class="text-xl">Conectando familia y escuela para un mejor futuro.</p>
                    </div>
                </div>
            </div>
            <!-- Slide 2 (Placeholder color for now) -->
            <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0 bg-blue-600">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-5xl font-bold mb-4">Acompañamiento Familiar</h1>
                        <p class="text-xl">Participa activamente en el proceso educativo de tus hijos.</p>
                    </div>
                </div>
            </div>
            <!-- Slide 3 (Placeholder color for now) -->
            <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0 bg-green-600">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-5xl font-bold mb-4">Gamificación Educativa</h1>
                        <p class="text-xl">Aprende y avanza en el camino del conocimiento.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Controls -->
        <button id="prev-slide" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/30 hover:bg-white/50 p-2 rounded-full text-white transition">
            <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <button id="next-slide" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/30 hover:bg-white/50 p-2 rounded-full text-white transition">
            <span class="material-symbols-outlined">chevron_right</span>
        </button>
    </div>

    <!-- Content Sections -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Nuestras Secciones</h2>
            <p class="mt-4 text-lg text-gray-500">Explora las herramientas que EduSaft tiene para ti.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card Carrera -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-48 bg-blue-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-blue-500">flag</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Carrera</h3>
                    <p class="text-gray-600 mb-4">Sigue el progreso de tu familia a través de un camino interactivo. ¡Asiste a eventos y desbloquea logros!</p>
                    <a href="#" class="text-blue-600 font-semibold hover:text-blue-800">Ver más &rarr;</a>
                </div>
            </div>

            <!-- Card Contenidos -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-48 bg-green-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-green-500">library_books</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Contenidos</h3>
                    <p class="text-gray-600 mb-4">Accede a recursos educativos, videos y guías para apoyar el aprendizaje desde casa.</p>
                    <a href="#" class="text-green-600 font-semibold hover:text-green-800">Explorar &rarr;</a>
                </div>
            </div>

            <!-- Card Eventos -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-48 bg-purple-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-purple-500">event</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Eventos</h3>
                    <p class="text-gray-600 mb-4">Mantente al día con las reuniones, talleres y actividades escolares programadas.</p>
                    <a href="#" class="text-purple-600 font-semibold hover:text-purple-800">Ver calendario &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Include -->
    <?php include 'assets/includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
  </body>
</html>
