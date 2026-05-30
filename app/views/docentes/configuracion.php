<?php include '../assets/includes/header.php'; ?>


<div class="flex min-h-screen">
    <?php include 'sidebar.php'; ?>
      <div class="flex-1 flex flex-col">
        <header
          class="bg-card-light dark:bg-card-dark shadow-md p-4 flex items-center justify-between"
        >
          <button class="invisible">
            <span class="material-symbols-outlined"> menu </span>
          </button>
          <h1 class="text-xl font-bold">Perfil del Usuario</h1>
          <button>
            <span class="material-symbols-outlined"> more_horiz </span>
          </button>
        </header>
        <main class="flex-1 p-6 overflow-y-auto">
          <div class="flex flex-col items-center mb-8">
            <div class="relative">
              <img
                alt="Foto de perfil de Elena Ramírez"
                class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-card-dark shadow-lg"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuC38BB_Ieau6xzsnCJqhfIrHykASN7tTgnnSeTxJkQDYbenWreZevK6vVTR27o59SO2ZYT9FBtlZ-HC4jgYAWN6hlM5CL4pSy1ksuxjHa9-z4FPOmMBPxU4nlNIOb4qqZEpzkmdDVC0T3fXDi5bNF7SajW2JoKNOdu9Pp06IezAK-JEY-kPyj9b6Xkr8YnKOo1JipqTz2FxxUQKl3e0V9q5QRznujqDeivEggAndsbtFuSzjU_2wrjsQ_3rAsI88gaRin_bljGLb1M"
              />
              <button
                class="absolute bottom-0 right-0 bg-primary text-white rounded-full p-1.5 hover:bg-blue-600 transition-colors"
              >
                <span class="material-symbols-outlined text-sm">
                  photo_camera
                </span>
              </button>
            </div>
            <h2 class="text-xl font-bold mt-4">Elena Ramírez</h2>
            <p class="text-sm text-subtext-light dark:text-subtext-dark">
              Profesor
            </p>
          </div>
          <div class="space-y-4">
            <div
              class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm"
            >
              <h3 class="font-semibold mb-3 text-lg">Datos Personales</h3>
              <div class="space-y-3">
                <div class="flex items-center">
                  <span class="material-symbols-outlined mr-4 text-primary"
                    >person</span
                  >
                  <div class="flex-1">
                    <p
                      class="text-xs text-subtext-light dark:text-subtext-dark"
                    >
                      Nombre completo
                    </p>
                    <p class="font-medium">Elena Ramírez</p>
                  </div>
                </div>
                <div class="flex items-center">
                  <span class="material-symbols-outlined mr-4 text-primary"
                    >badge</span
                  >
                  <div class="flex-1">
                    <p
                      class="text-xs text-subtext-light dark:text-subtext-dark"
                    >
                      Documento
                    </p>
                    <p class="font-medium">123.456.789</p>
                  </div>
                </div>
                <div class="flex items-center">
                  <span class="material-symbols-outlined mr-4 text-primary"
                    >email</span
                  >
                  <div class="flex-1">
                    <p
                      class="text-xs text-subtext-light dark:text-subtext-dark"
                    >
                      Correo electrónico
                    </p>
                    <p class="font-medium">elena.ramirez@educoconnect.com</p>
                  </div>
                </div>
              </div>
            </div>
            <div
              class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm"
            >
              <h3 class="font-semibold mb-3 text-lg">Seguridad</h3>
              <a
                class="flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 -m-4 p-4 rounded-lg transition-colors"
                href="#"
              >
                <div class="flex items-center">
                  <span class="material-symbols-outlined mr-4 text-primary"
                    >lock</span
                  >
                  <span class="font-medium">Cambiar Contraseña</span>
                </div>
                <span
                  class="material-symbols-outlined text-subtext-light dark:text-subtext-dark"
                >
                  arrow_forward_ios
                </span>
              </a>
            </div>
            <div
              class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm"
            >
              <h3 class="font-semibold mb-3 text-lg">Mi Código QR</h3>
              <div class="flex flex-col items-center text-center">
                <img
                  alt="Código QR del usuario"
                  class="w-36 h-36 rounded-lg mb-2"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuC2LU8e-MLGuDb8aTflqgCLQ0-bXn-_d0t3s0rFoSkzTPnn3TRCFAl278gfN-nKvSqOritTrHYCU2tE7-zbmjRt0CMbjkBQIuy08jy0UTUNdeMpza3ZCfnBD4-AyqpCg6TKiYWhv4iDxROx1WKFE1eWcbWaDy8BKtTM27kUQkDGGwoZTXcLyNs-0qH6vwFpT_-wN3N3mdbDeGdmqKKJWMzgXbpDnKE8aAUCYQvwkKnItpV0Wjb2tBz4Gw-O-GDz8gcCSAHvXGedlLs"
                />
                <p
                  class="text-xs text-subtext-light dark:text-subtext-dark px-4"
                >
                  Usa este código para registrar tu asistencia de forma rápida.
                </p>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
<?php include '../assets/includes/footer.php'; ?>
</body>
</html>
