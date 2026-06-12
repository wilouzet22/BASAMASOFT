<?php $data = $data ?? []; ?>
<!DOCTYPE html>
<html lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Camino de Puntuación</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link crossorigin="anonymous" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" referrerpolicy="no-referrer" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#4CAF50",
                        "background-light": "#F7FAFC",
                        "background-dark": "#1A202C",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
<style>
        .thermometer {
            width: 50px;
            height: 200px;
            border: 4px solid #4A5568;
            border-radius: 25px;
            position: relative;
            background-color: #E2E8F0;
            overflow: hidden;
        }
        .thermometer-level {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, #EF4444, #FBBF24, #22C55E);
            transition: height 0.5s ease-in-out;
        }
        .thermometer-base {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(to top, #EF4444, #FBBF24, #22C55E);
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid #4A5568;
        }
        .path-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 4px solid white;
            background-color: #4CAF50;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 10;
            transition: all 0.5s ease-in-out;
        }
        .path-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .mountain-foreground {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('montaña.png');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
        }
        .title-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .dark .title-container {
            background: rgba(26, 32, 44, 0.95);
        }
        .content-overlay {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .dark .content-overlay {
            background: rgba(26, 32, 44, 0.9);
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="min-h-screen relative">
<!-- Imagen de montaña al frente -->
<div class="mountain-foreground"></div>

<!-- Títulos -->
<div class="relative z-20 text-center">
<div class="title-container">
<h1 class="text-3xl sm:text-4xl font-bold text-primary mb-4">Camino de Puntuación</h1>
<p class="text-gray-600 dark:text-gray-400 mb-8">¡Sigue avanzando con tu familia! Cada paso los acerca más a la meta.</p>
</div>
</div>

<!-- Área del camino de montaña -->
<div class="relative z-10 flex items-center justify-center">
<div class="relative w-full h-[600px] max-w-4xl">
<svg class="absolute w-full h-full" preserveAspectRatio="none" viewBox="0 0 800 600">
<path d="M 50 550 C 150 450, 200 300, 300 320 S 450 350, 500 250 S 650 100, 750 80" fill="none" stroke="white" stroke-dasharray="15 15" stroke-linecap="round" stroke-opacity="0.8" stroke-width="6"></path>
<path d="M 50 550 C 150 450, 200 300, 300 320 S 450 350, 500 250 S 650 100, 750 80" fill="none" id="mountainPath"></path>
</svg>
<div class="absolute inset-0" id="path-container">
</div>
</div>
</div>

<!-- Termómetro flotante -->
<aside class="fixed right-4 bottom-1/2 translate-y-1/2 flex items-center space-x-2 z-20">
<div class="thermometer-markers">
<div class="marker">😊</div>
<div class="marker">😐</div>
<div class="marker">😟</div>
</div>
<div class="thermometer">
<div class="thermometer-level" id="thermometer-level"></div>
</div>
</aside>
<script>
        document.addEventListener('DOMContentLoaded', () => {
            const familyProgress = 60; // Example progress percentage for the family
            const fatherProgress = 60; // Example progress percentage for the father
            const thermometerLevel = document.getElementById('thermometerLevel');
            const progressText = document.getElementById('progressText');
            thermometerLevel.style.height = `${fatherProgress}%`;
            progressText.textContent = `${fatherProgress}%`;
            const path = document.getElementById('mountainPath');
            const container = document.getElementById('path-container');
            const pathLength = path.getTotalLength();
        });
    </script>

</body></html>