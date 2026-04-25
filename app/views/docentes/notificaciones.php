<?php include '../assets/includes/header.php'; ?>


<div class="flex min-h-screen">
    <?php include 'sidebar.php'; ?>
      <main class="flex-1 p-8">
        <header class="mb-8">
          <h2 class="text-3xl font-bold text-[var(--text-primary)]">
            Gestión de Notificaciones
          </h2>
        </header>
        <div class="grid grid-cols-1 gap-8">
          <section class="lg:col-span-3">
            <div class="bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">
                Crear Nueva Notificación
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                  <label
                    class="block text-sm font-medium text-[var(--text-secondary)] mb-1"
                    for="notification-message"
                    >Mensaje</label
                  >
                  <textarea
                    class="form-textarea w-full rounded-md border-[var(--border-color)] focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] transition duration-150 ease-in-out text-sm"
                    id="notification-message"
                    placeholder="Escriba su notificación aquí para los padres de familia..."
                    rows="4"
                  ></textarea>
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-[var(--text-secondary)] mb-1"
                    for="notification-type"
                    >Tipo de Notificación</label
                  >
                  <select
                    class="form-select w-full rounded-md border-[var(--border-color)] focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] transition duration-150 ease-in-out text-sm"
                    id="notification-type"
                  >
                    <option>Alerta en el sistema</option>
                    <option>Pop-up</option>
                    <option>Email</option>
                  </select>
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-[var(--text-secondary)] mb-1"
                    for="notification-audience"
                    >Audiencia</label
                  >
                  <select
                    class="form-select w-full rounded-md border-[var(--border-color)] focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] transition duration-150 ease-in-out text-sm"
                    id="notification-audience"
                  >
                    <option>Todos los padres</option>
                    <option>Padres de 1er Grado</option>
                    <option>Padres de 2do Grado</option>
                    <option>Padres del Taller de Arte</option>
                  </select>
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-[var(--text-secondary)] mb-1"
                    for="schedule-date"
                    >Programar Envío (Opcional)</label
                  >
                  <input
                    class="form-input w-full rounded-md border-[var(--border-color)] focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] transition duration-150 ease-in-out text-sm"
                    id="schedule-date"
                    type="datetime-local"
                  />
                </div>
                <div class="flex items-end">
                  <button
                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-[var(--primary-color)] text-white text-sm font-semibold rounded-md shadow-sm hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)] transition-colors"
                  >
                    <span class="material-symbols-outlined">send</span>
                    <span>Enviar o Programar</span>
                  </button>
                </div>
              </div>
            </div>
          </section>
          <section class="lg:col-span-3">
            <div class="bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">
                Historial de Notificaciones
              </h3>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider"
                        scope="col"
                      >
                        Mensaje
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider"
                        scope="col"
                      >
                        Audiencia
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider"
                        scope="col"
                      >
                        Fecha de Envío
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider"
                        scope="col"
                      >
                        Estado
                      </th>
                      <th class="relative px-6 py-3" scope="col">
                        <span class="sr-only">Acciones</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-primary)] max-w-sm truncate"
                      >
                        Recordatorio: Mañana es el taller de lectura. ¡No
                        falten!
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]"
                      >
                        Todos los padres
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]"
                      >
                        15 de Mayo, 2024 - 09:00 AM
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                          >Enviado</span
                        >
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                      >
                        <a
                          class="text-[var(--primary-color)] hover:text-opacity-80"
                          href="#"
                          >Ver</a
                        >
                      </td>
                    </tr>
                    <tr>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-primary)] max-w-sm truncate"
                      >
                        Nuevo video disponible en la plataforma sobre
                        matemáticas divertidas.
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]"
                      >
                        Padres de 2do Grado
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]"
                      >
                        12 de Mayo, 2024 - 03:30 PM
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                          >Enviado</span
                        >
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                      >
                        <a
                          class="text-[var(--primary-color)] hover:text-opacity-80"
                          href="#"
                          >Ver</a
                        >
                      </td>
                    </tr>
                    <tr>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-primary)] max-w-sm truncate"
                      >
                        Confirmación de asistencia para el evento de fin de año.
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]"
                      >
                        Todos los padres
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]"
                      >
                        20 de Mayo, 2024 - 10:00 AM
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"
                          >Programado</span
                        >
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                      >
                        <a
                          class="text-[var(--primary-color)] hover:text-opacity-80 mr-2"
                          href="#"
                          >Editar</a
                        >
                        <a class="text-red-600 hover:text-red-800" href="#"
                          >Cancelar</a
                        >
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
<?php include '../assets/includes/footer.php'; ?>
</body>
</html>
