<?php include '../assets/includes/header.php'; ?>


<div class="flex min-h-screen">
    <?php include 'sidebar.php'; ?>
      <main class="flex-1 p-8">
        <header class="mb-8">
          <h2 class="text-3xl font-bold text-[var(--text-primary)]">
            Panel de Control de Asistencia
          </h2>
        </header>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <section class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">
                Código QR para Asistencia
              </h3>
              <div
                class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center"
              >
                <img
                  alt="Código QR de Asistencia"
                  class="rounded-md"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuBCb3iDPHGkMI8NmgO_EH1hL_y4CcP24bzs6y5jJHA8Au6S2JWsyM5hUV-dt-BTN4LEYm8MwUceTrqDn9e7iwBFwO0jlwcXKGPC_vwf-1vxB3qsHISli55vY_UPhmNxe9LN0L8lZs6zUAJB6CkXrdfNYsbLetl-WxmMbC9HDVa-HsHTfi0YYRjqXWHxwCs3sUQv3eWsLB1lDXbdOaZpYRAIX-9zaWse_UX_4mpy0B7QCryZRrTQ7l3xLNvB0L43RQ1Z8moHNgpvrcw"
                />
              </div>
              <p class="text-center text-sm text-gray-500 mt-4">
                Muestre este código a los asistentes para que registren su
                entrada.
              </p>
            </div>
          </section>
          <section class="lg:col-span-2">
            <h3 class="text-xl font-semibold text-[var(--text-primary)] mb-4">
              Estadísticas de Participación
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex justify-between items-start">
                  <p class="text-base font-medium text-[var(--text-secondary)]">
                    Participación por Actividad
                  </p>
                  <span class="material-symbols-outlined text-gray-400"
                    >pie_chart</span
                  >
                </div>
                <p class="text-4xl font-bold text-[var(--text-primary)] mt-2">
                  75%
                </p>
                <div class="flex items-center gap-2 mt-1">
                  <p class="text-sm text-green-600 font-semibold">+5%</p>
                  <p class="text-sm text-[var(--text-secondary)]">
                    que el último mes
                  </p>
                </div>
                <div class="mt-6 h-40 flex items-end gap-4">
                  <div class="flex flex-col items-center gap-2 flex-1">
                    <div
                      class="w-full bg-gray-200 rounded-full h-24 flex items-end"
                    >
                      <div
                        class="bg-[var(--primary-color)] w-full rounded-full"
                        style="height: 40%"
                      ></div>
                    </div>
                    <p class="text-xs text-[var(--text-secondary)] font-medium">
                      Act. 1
                    </p>
                  </div>
                  <div class="flex flex-col items-center gap-2 flex-1">
                    <div
                      class="w-full bg-gray-200 rounded-full h-24 flex items-end"
                    >
                      <div
                        class="bg-[var(--primary-color)] w-full rounded-full"
                        style="height: 80%"
                      ></div>
                    </div>
                    <p class="text-xs text-[var(--text-secondary)] font-medium">
                      Act. 2
                    </p>
                  </div>
                  <div class="flex flex-col items-center gap-2 flex-1">
                    <div
                      class="w-full bg-gray-200 rounded-full h-24 flex items-end"
                    >
                      <div
                        class="bg-[var(--primary-color)] w-full rounded-full"
                        style="height: 65%"
                      ></div>
                    </div>
                    <p class="text-xs text-[var(--text-secondary)] font-medium">
                      Act. 3
                    </p>
                  </div>
                </div>
              </div>
              <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex justify-between items-start">
                  <p class="text-base font-medium text-[var(--text-secondary)]">
                    Tendencia de Asistencia
                  </p>
                  <span class="material-symbols-outlined text-gray-400"
                    >show_chart</span
                  >
                </div>
                <p class="text-4xl font-bold text-[var(--text-primary)] mt-2">
                  92%
                </p>
                <div class="flex items-center gap-2 mt-1">
                  <p class="text-sm text-red-600 font-semibold">-2%</p>
                  <p class="text-sm text-[var(--text-secondary)]">
                    que el último semestre
                  </p>
                </div>
                <div class="mt-6 h-40">
                  <svg
                    fill="none"
                    height="100%"
                    preserveAspectRatio="none"
                    viewBox="0 0 300 100"
                    width="100%"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M0 80 C 40 20, 60 50, 100 40 C 140 30, 160 90, 200 80 C 240 70, 260 20, 300 10"
                      stroke="#13a4ec"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="3"
                    ></path>
                    <path
                      d="M0 80 C 40 20, 60 50, 100 40 C 140 30, 160 90, 200 80 C 240 70, 260 20, 300 10 V 100 H 0 Z"
                      fill="url(#trend-gradient)"
                    ></path>
                    <defs>
                      <linearGradient
                        gradientUnits="userSpaceOnUse"
                        id="trend-gradient"
                        x1="0"
                        x2="0"
                        y1="0"
                        y2="100"
                      >
                        <stop stop-color="#13a4ec" stop-opacity="0.2"></stop>
                        <stop
                          offset="1"
                          stop-color="#13a4ec"
                          stop-opacity="0"
                        ></stop>
                      </linearGradient>
                    </defs>
                  </svg>
                  <div
                    class="flex justify-between text-xs text-[var(--text-secondary)] font-medium -mt-2"
                  >
                    <span>Ene</span><span>Feb</span><span>Mar</span
                    ><span>Abr</span><span>May</span><span>Jun</span>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <section class="lg:col-span-3">
            <div class="bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-4">
                Notificaciones Dinámicas
              </h3>
              <div class="flex flex-col sm:flex-row items-end gap-4">
                <div class="w-full">
                  <label class="sr-only" for="notification-message"
                    >Mensaje de notificación</label
                  >
                  <textarea
                    class="form-textarea w-full rounded-md border-[var(--border-color)] focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] transition duration-150 ease-in-out text-sm"
                    id="notification-message"
                    placeholder="Escriba su notificación aquí para los padres de familia..."
                    rows="4"
                  ></textarea>
                </div>
                <button
                  class="w-full sm:w-auto flex-shrink-0 flex items-center justify-center gap-2 px-6 py-3 bg-[var(--primary-color)] text-white text-sm font-semibold rounded-md shadow-sm hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)] transition-colors"
                >
                  <span class="material-symbols-outlined">send</span>
                  <span>Enviar Notificación</span>
                </button>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
<?php include '../assets/includes/footer.php'; ?>
</body>
</html>
