<?php include '../assets/includes/header.php'; ?>


<div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
      <main class="flex-1 p-8">
        <header class="mb-8 flex justify-between items-center">
          <h2 class="text-3xl font-bold text-[var(--text-primary)]">
            Gestión de Actividades
          </h2>
          <button
            class="flex items-center gap-2 px-4 py-2 bg-[var(--primary-color)] text-white text-sm font-semibold rounded-md shadow-sm hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)] transition-colors"
          >
            <span class="material-symbols-outlined">add</span>
            <span>Crear Actividad</span>
          </button>
        </header>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <section class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">
                Lista de Actividades
              </h3>
              <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                      <th class="px-6 py-3" scope="col">Actividad</th>
                      <th class="px-6 py-3" scope="col">Fecha</th>
                      <th class="px-6 py-3" scope="col">Estado</th>
                      <th class="px-6 py-3" scope="col">Participación</th>
                      <th class="px-6 py-3 text-right" scope="col">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="bg-white border-b hover:bg-gray-50">
                      <td
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                      >
                        Taller de Lectura Creativa
                      </td>
                      <td class="px-6 py-4">25 de Oct, 2023</td>
                      <td class="px-6 py-4">
                        <span
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                          >Completada</span
                        >
                      </td>
                      <td class="px-6 py-4">85%</td>
                      <td class="px-6 py-4 text-right">
                        <a
                          class="font-medium text-[var(--primary-color)] hover:underline"
                          href="#"
                          >Editar</a
                        >
                        <a
                          class="font-medium text-red-600 hover:underline ml-4"
                          href="#"
                          >Eliminar</a
                        >
                      </td>
                    </tr>
                    <tr class="bg-white border-b hover:bg-gray-50">
                      <td
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                      >
                        Olimpiadas de Matemáticas
                      </td>
                      <td class="px-6 py-4">15 de Nov, 2023</td>
                      <td class="px-6 py-4">
                        <span
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                          >Programada</span
                        >
                      </td>
                      <td class="px-6 py-4">N/A</td>
                      <td class="px-6 py-4 text-right">
                        <a
                          class="font-medium text-[var(--primary-color)] hover:underline"
                          href="#"
                          >Editar</a
                        >
                        <a
                          class="font-medium text-red-600 hover:underline ml-4"
                          href="#"
                          >Eliminar</a
                        >
                      </td>
                    </tr>
                    <tr class="bg-white border-b hover:bg-gray-50">
                      <td
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                      >
                        Feria de Ciencias
                      </td>
                      <td class="px-6 py-4">05 de Dic, 2023</td>
                      <td class="px-6 py-4">
                        <span
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                          >Programada</span
                        >
                      </td>
                      <td class="px-6 py-4">N/A</td>
                      <td class="px-6 py-4 text-right">
                        <a
                          class="font-medium text-[var(--primary-color)] hover:underline"
                          href="#"
                          >Editar</a
                        >
                        <a
                          class="font-medium text-red-600 hover:underline ml-4"
                          href="#"
                          >Eliminar</a
                        >
                      </td>
                    </tr>
                    <tr class="bg-white hover:bg-gray-50">
                      <td
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                      >
                        Excursión al Museo de Historia
                      </td>
                      <td class="px-6 py-4">20 de Sep, 2023</td>
                      <td class="px-6 py-4">
                        <span
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                          >Completada</span
                        >
                      </td>
                      <td class="px-6 py-4">92%</td>
                      <td class="px-6 py-4 text-right">
                        <a
                          class="font-medium text-[var(--primary-color)] hover:underline"
                          href="#"
                          >Editar</a
                        >
                        <a
                          class="font-medium text-red-600 hover:underline ml-4"
                          href="#"
                          >Eliminar</a
                        >
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
          <section class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">
                Resumen de Actividades
              </h3>
              <div class="space-y-6">
                <div class="flex items-center gap-4">
                  <div
                    class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center"
                  >
                    <span class="material-symbols-outlined text-blue-500"
                      >event_upcoming</span
                    >
                  </div>
                  <div>
                    <p class="text-2xl font-bold text-[var(--text-primary)]">
                      2
                    </p>
                    <p class="text-sm text-[var(--text-secondary)]">
                      Actividades Programadas
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-4">
                  <div
                    class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center"
                  >
                    <span class="material-symbols-outlined text-green-500"
                      >event_available</span
                    >
                  </div>
                  <div>
                    <p class="text-2xl font-bold text-[var(--text-primary)]">
                      2
                    </p>
                    <p class="text-sm text-[var(--text-secondary)]">
                      Actividades Completadas
                    </p>
                  </div>
                </div>
              </div>
              <div class="mt-8">
                <h4
                  class="text-base font-semibold text-[var(--text-primary)] mb-3"
                >
                  Diagrama de Flujo de Gestión
                </h4>
                <div
                  class="relative pl-8 space-y-8 border-l-2 border-dashed border-gray-300"
                >
                  <div class="relative">
                    <div
                      class="absolute -left-11 top-1.5 w-6 h-6 bg-[var(--primary-color)] rounded-full flex items-center justify-center"
                    >
                      <span
                        class="material-symbols-outlined text-white text-base"
                        >add</span
                      >
                    </div>
                    <p class="font-medium text-gray-800">Crear Actividad</p>
                    <p class="text-sm text-gray-500">
                      Definir nombre, fecha y detalles.
                    </p>
                  </div>
                  <div class="relative">
                    <div
                      class="absolute -left-11 top-1.5 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center"
                    >
                      <span
                        class="material-symbols-outlined text-white text-base"
                        >edit</span
                      >
                    </div>
                    <p class="font-medium text-gray-800">Editar Actividad</p>
                    <p class="text-sm text-gray-500">
                      Modificar detalles de actividades programadas.
                    </p>
                  </div>
                  <div class="relative">
                    <div
                      class="absolute -left-11 top-1.5 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center"
                    >
                      <span
                        class="material-symbols-outlined text-white text-base"
                        >task_alt</span
                      >
                    </div>
                    <p class="font-medium text-gray-800">
                      Marcar como Completada
                    </p>
                    <p class="text-sm text-gray-500">
                      Registrar asistencia y finalizar.
                    </p>
                  </div>
                  <div class="relative">
                    <div
                      class="absolute -left-11 top-1.5 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center"
                    >
                      <span
                        class="material-symbols-outlined text-white text-base"
                        >delete</span
                      >
                    </div>
                    <p class="font-medium text-gray-800">Eliminar Actividad</p>
                    <p class="text-sm text-gray-500">
                      Cancelar una actividad programada.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
<?php include '../assets/includes/footer.php'; ?>
</body>
</html>
