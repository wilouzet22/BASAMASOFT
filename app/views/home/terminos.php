<?php $data = $data ?? []; ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo $data['title']; ?> - <?php echo SITENAME; ?></title>
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect" />
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&amp;family=Manrope:wght@400;500;700;800&amp;family=Noto+Sans:wght@400;500;700;900" onload="this.rel='stylesheet'" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
      :root {
        --primary-color: #137fec;
        --secondary-color: #e0effc;
        --background-color: #f8faff;
        --text-primary: #1a202c;
        --text-secondary: #4a5568;
        --accent-color: #137fec;
      }
      body {
        font-family: "Manrope", sans-serif;
        background-color: var(--background-color);
        color: var(--text-primary);
      }
    </style>
  </head>
  <body class="bg-[var(--background-color)] text-[var(--text-primary)]">
    <div class="relative flex size-full min-h-screen flex-col group/design-root overflow-x-hidden">
      <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-gray-200 px-10 py-4 bg-white shadow-sm">
          <div class="flex items-center gap-4 text-[var(--text-primary)]">
            <div class="size-6 text-[var(--primary-color)]">
              <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z"></path>
              </svg>
            </div>
            <h2 class="text-xl font-bold leading-tight tracking-tight"><?php echo SITENAME; ?></h2>
          </div>
          <div class="flex flex-1 justify-end gap-6">
            <nav class="flex items-center gap-8">
              <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)] transition-colors" href="<?php echo URLROOT; ?>">Inicio</a>
            </nav>
          </div>
        </header>
        <main class="flex-1 px-4 py-8">
          <div class="container mx-auto max-w-3xl">
            <div class="bg-white rounded-xl shadow-md p-8">
              <h1 class="text-4xl font-extrabold text-[var(--text-primary)] mb-6 leading-tight border-b pb-4">Términos y Condiciones</h1>
              <div class="space-y-6">
                <div>
                  <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-3">1. Aceptación de los Términos</h2>
                  <p class="text-base text-[var(--text-secondary)] leading-relaxed">
                    Al acceder, navegar o utilizar este aplicativo web, usted (en adelante, el "Usuario") acepta estar sujeto a los presentes Términos y Condiciones de Uso, a nuestra Política de Privacidad y a cualquier otra política o regla operativa publicada en la plataforma. Si no está de acuerdo con estos términos, no debe utilizar la aplicación.
                  </p>
                </div>
                <div>
                  <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-3">2. Descripción del Servicio</h2>
                  <p class="text-base text-[var(--text-secondary)] leading-relaxed">
                    El aplicativo es una herramienta educativa y de gestión diseñada para fomentar la participación e integración de las familias en las actividades del entorno escolar. Incluye funciones como registro de asistencia, gamificación a través de un "camino de puntuación", ranking de familias y grupos, contenido multimedia, y notificaciones.
                  </p>
                </div>
                <div>
                  <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-3">3. Política de Uso Aceptable (PUA)</h2>
                  <p class="text-base text-[var(--text-secondary)] leading-relaxed">
                    La aplicación tiene múltiples roles (padre de familia, profesor, administrador), y cada uno tiene responsabilidades específicas.
                  </p>
                  <ul class="list-disc pl-5 text-[var(--text-secondary)]">
                    <li>Padres de Familia: Podrán acceder a su perfil, generar códigos QR para certificar la asistencia por todas las actividades programadas por la institución, ver su progreso en el "camino de puntuación", y participar en los juegos o talleres y el contenido multimedia.</li>
                  </ul>
                </div>
              </div>
              <div class="mt-8 pt-6 border-t text-center">
                <a href="<?php echo URLROOT; ?>/auth/login" class="inline-flex items-center justify-center rounded-lg h-12 px-8 bg-[var(--primary-color)] text-white text-base font-bold leading-normal tracking-wide shadow-lg hover:bg-[var(--accent-color)]/90 transition-all duration-300">Acepto y Volver al Login</a>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
