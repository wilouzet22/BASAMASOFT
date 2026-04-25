<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login Educativo - <?php echo SITENAME; ?></title>
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
  <body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="relative w-full max-w-4xl mx-auto">
      <!-- SVG Decorations -->
      <div class="absolute -left-32 -top-20">
        <svg fill="none" height="400" viewBox="0 0 250 400" width="250" xmlns="http://www.w3.org/2000/svg">
          <path d="M-10.5 400C-10.5 289.873 78.6273 200.5 188.754 200.5C298.88 200.5 388 111.373 388 0.5" stroke="#22c55e" stroke-opacity="0.3" stroke-width="3"></path>
        </svg>
      </div>
      <div class="absolute -right-32 -bottom-20">
        <svg fill="none" height="400" viewBox="0 0 250 400" width="250" xmlns="http://www.w3.org/2000/svg">
          <path d="M260.5 0C260.5 110.127 171.373 199.5 61.2465 199.5C-48.8804 199.5 -138 288.627 -138 399.5" stroke="#3b82f6" stroke-opacity="0.3" stroke-width="3"></path>
        </svg>
      </div>

      <div class="relative bg-white p-12 rounded-2xl shadow-lg z-10 w-full max-w-md mx-auto">
        <div class="text-center mb-8">
          <h1 class="text-4xl font-bold text-gray-800">¡Hola Familia!</h1>
        </div>
        <div class="flex justify-center mb-6">
          <div class="bg-blue-100 p-4 rounded-full">
            <div class="rounded-full overflow-hidden w-40 h-40 flex items-center justify-center">
              <img src="<?php echo URLROOT; ?>/assets/img/logo.png" alt="Logo" class="w-full h-full object-cover rounded-full" />
            </div>
          </div>
        </div>
        <p class="text-center text-gray-500 mb-6">IE Barrio Santa Margarita</p>
        
        <form action="<?php echo URLROOT; ?>/auth/login" method="post">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2" for="username">Usuario</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">person</span>
              <input class="w-full pl-10 pr-4 py-2 border <?php echo (!empty($data['username_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:ring-blue-500 focus:border-blue-500" id="username" name="username" placeholder="Ingrese su usuario" type="text" value="<?php echo $data['username']; ?>" />
              <span class="text-red-500 text-xs"><?php echo $data['username_err']; ?></span>
            </div>
          </div>
          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2" for="password">Contraseña</label>
            <div class="relative">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
              <input class="w-full pl-10 pr-4 py-2 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300'; ?> rounded-lg focus:ring-blue-500 focus:border-blue-500" id="password" name="password" placeholder="Ingrese su contraseña" type="password" />
              <span class="text-red-500 text-xs"><?php echo $data['password_err']; ?></span>
            </div>
          </div>
          <div class="mb-4">
            <p class="text-sm">Lee nuestros <a href="<?php echo URLROOT; ?>/terminos" class="text-blue-500 hover:underline">términos y condiciones</a></p>
          </div>
          <button class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition duration-200" type="submit">
            Iniciar Sesión
          </button>
        </form>
      </div>
    </div>
  </body>
</html>
