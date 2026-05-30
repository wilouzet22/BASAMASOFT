<?php require APPROOT . '/views/inc/header.php'; ?>
<body class="bg-surface-container-lowest text-on-background font-lexend min-h-screen overflow-x-hidden">
    <!-- Navbar (Fixed) -->
    <?php require APPROOT . '/views/inc/navbar.php'; ?>

    <main class="pt-24 pb-20">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-16 animate-fade-in">
                <h1 class="text-headline-xl text-primary font-black tracking-tight mb-4">Camino de Éxito Familiar</h1>
                <p class="text-body-lg text-on-surface-variant max-w-xl mx-auto">
                    Cada asistencia a una reunión o taller te permite avanzar y desbloquear recompensas para tu familia.
                </p>
            </div>

            <!-- Game Map Container -->
            <div class="relative bg-white rounded-[3rem] border border-outline-variant shadow-2xl p-8 md:p-16 overflow-hidden min-h-[1500px] graph-paper-bg">
                
                <!-- SVG Path (ZigZag) -->
                <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 400 1400" preserveAspectRatio="none">
                    <path d="M 200 100 
                             C 350 200, 350 300, 200 400 
                             S 50 500, 200 600 
                             S 350 700, 200 800 
                             S 50 900, 200 1000 
                             S 350 1100, 200 1200 
                             S 50 1300, 200 1400" 
                          fill="none" 
                          stroke="url(#pathGradient)" 
                          stroke-width="12" 
                          stroke-linecap="round"
                          class="opacity-20" />
                    
                    <!-- Progress Path (Colored) -->
                    <path id="progress-path" d="M 200 100 
                             C 350 200, 350 300, 200 400 
                             S 50 500, 200 600 
                             S 350 700, 200 800" 
                          fill="none" 
                          stroke="url(#pathGradient)" 
                          stroke-width="12" 
                          stroke-linecap="round"
                          stroke-dasharray="2000"
                          stroke-dashoffset="0" />

                    <defs>
                        <linearGradient id="pathGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:var(--md-sys-color-primary, #00288e);stop-opacity:1" />
                            <stop offset="100%" style="stop-color:var(--md-sys-color-secondary, #a93349);stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>

                <!-- Points/Nodes -->
                <div class="relative z-10 h-full">
                    <!-- Node 1 (Completed) -->
                    <div class="absolute" style="top: 100px; left: 50%; transform: translate(-50%, -50%);">
                        <button onclick="showDetail('Reunión de Bienvenida', '20 Ene', '50 pts', 'Completado')" 
                                class="w-16 h-16 bg-primary text-on-primary rounded-full shadow-xl ring-8 ring-primary/10 flex items-center justify-center hover:scale-110 transition-transform group">
                            <span class="material-symbols-outlined group-hover:rotate-12">check</span>
                        </button>
                        <span class="absolute top-20 left-1/2 -translate-x-1/2 whitespace-nowrap bg-white px-3 py-1 rounded-full text-[10px] font-bold border border-outline-variant shadow-sm uppercase tracking-tighter">Bienvenida</span>
                    </div>

                    <!-- Node 2 (Completed) -->
                    <div class="absolute" style="top: 400px; left: 50%; transform: translate(-50%, -50%);">
                        <button onclick="showDetail('Taller de Valores', '15 Feb', '30 pts', 'Completado')" 
                                class="w-16 h-16 bg-primary text-on-primary rounded-full shadow-xl ring-8 ring-primary/10 flex items-center justify-center hover:scale-110 transition-transform group">
                            <span class="material-symbols-outlined group-hover:rotate-12">star</span>
                        </button>
                        <span class="absolute top-20 left-1/2 -translate-x-1/2 whitespace-nowrap bg-white px-3 py-1 rounded-full text-[10px] font-bold border border-outline-variant shadow-sm uppercase tracking-tighter">Valores</span>
                    </div>

                    <!-- Node 3 (Active) -->
                    <div class="absolute" style="top: 600px; left: 50%; transform: translate(-50%, -50%);">
                        <div class="absolute -top-12 left-1/2 -translate-x-1/2 animate-bounce">
                            <div class="bg-secondary text-on-secondary px-4 py-2 rounded-2xl text-xs font-bold shadow-lg flex items-center gap-2">
                                <span class="material-symbols-outlined text-xs">location_on</span>
                                Tu Familia
                            </div>
                        </div>
                        <button onclick="showDetail('Entrega de Notas 1', '25 Abr', '50 pts', 'Próximo')" 
                                class="w-20 h-20 bg-secondary text-on-secondary rounded-full shadow-2xl ring-8 ring-secondary/20 flex items-center justify-center hover:scale-110 transition-transform group relative z-20">
                            <span class="material-symbols-outlined text-4xl group-hover:rotate-12" style="font-variation-settings: 'FILL' 1;">face</span>
                        </button>
                        <span class="absolute top-24 left-1/2 -translate-x-1/2 whitespace-nowrap bg-white px-4 py-1.5 rounded-full text-xs font-bold border-2 border-secondary text-secondary shadow-sm uppercase tracking-tighter">Entrega de Notas</span>
                    </div>

                    <!-- Node 4 (Locked - Fog Effect) -->
                    <div class="absolute opacity-40 blur-[1px]" style="top: 800px; left: 50%; transform: translate(-50%, -50%);">
                        <div class="w-16 h-16 bg-surface-container text-outline rounded-full border-4 border-dashed border-outline flex items-center justify-center">
                            <span class="material-symbols-outlined">lock</span>
                        </div>
                    </div>

                    <!-- Node 5 (Locked) -->
                    <div class="absolute opacity-40 blur-[1px]" style="top: 1000px; left: 50%; transform: translate(-50%, -50%);">
                        <div class="w-16 h-16 bg-surface-container text-outline rounded-full border-4 border-dashed border-outline flex items-center justify-center">
                            <span class="material-symbols-outlined">lock</span>
                        </div>
                    </div>

                    <!-- Node 6 (Goal) -->
                    <div class="absolute" style="top: 1300px; left: 50%; transform: translate(-50%, -50%);">
                        <div class="w-24 h-24 bg-tertiary-container text-tertiary rounded-3xl border-4 border-tertiary flex items-center justify-center shadow-lg opacity-30">
                            <span class="material-symbols-outlined text-5xl">workspace_premium</span>
                        </div>
                        <span class="absolute top-28 left-1/2 -translate-x-1/2 whitespace-nowrap text-sm font-black text-tertiary uppercase tracking-widest">Graduación Familiar</span>
                    </div>
                </div>

                <!-- Fog Overlay (Bottom Part) -->
                <div class="absolute bottom-0 left-0 w-full h-[600px] bg-gradient-to-t from-white via-white/80 to-transparent pointer-events-none z-0"></div>
            </div>
        </div>
    </main>

    <!-- Detail Modal (Hidden by default) -->
    <div id="detail-modal" class="fixed inset-0 bg-on-background/40 backdrop-blur-sm z-[60] flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-[2rem] w-full max-w-sm p-8 shadow-2xl animate-fade-in relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-outline hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
            <div id="modal-icon-bg" class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mb-6">
                <span id="modal-icon" class="material-symbols-outlined text-4xl text-primary">event</span>
            </div>
            <h3 id="modal-title" class="text-2xl font-black text-on-surface mb-2 tracking-tight">Evento</h3>
            <p id="modal-date" class="text-sm text-outline font-bold uppercase mb-4 tracking-widest">Fecha</p>
            <div class="flex items-center gap-3 p-4 bg-surface-container rounded-2xl mb-8">
                <span class="material-symbols-outlined text-secondary">workspace_premium</span>
                <span id="modal-points" class="font-bold text-on-surface">Puntos</span>
            </div>
            <p id="modal-status" class="text-sm text-on-surface-variant leading-relaxed mb-8">Estado del evento.</p>
            <button onclick="closeModal()" class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold transition-all hover:opacity-90">Entendido</button>
        </div>
    </div>

    <script>
        function showDetail(title, date, points, status) {
            document.getElementById('modal-title').innerText = title;
            document.getElementById('modal-date').innerText = date;
            document.getElementById('modal-points').innerText = "Premio: " + points;
            document.getElementById('modal-status').innerText = "Este evento se encuentra " + status + ". La participación constante ayuda a que tu familia avance más rápido en el camino.";
            
            const modal = document.getElementById('detail-modal');
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detail-modal').classList.add('hidden');
        }

        // Close on escape
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>

    <?php require APPROOT . '/views/inc/footer.php'; ?>