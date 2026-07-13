<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>

<div class="graph-paper-bg min-h-screen pt-24 pb-20">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 text-center md:text-left animate-fade-in">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full mb-6 font-label-md">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                </span>
                ¡Bienvenidos a la Comunidad!
            </div>
            <h1 class="text-headline-xl text-primary font-headline-xl mb-6 tracking-tight leading-tight">
                Hola, <span class="text-secondary">familia</span>
            </h1>
            <p class="text-body-lg text-on-surface-variant mb-10 max-w-2xl leading-relaxed">
                EduSaft es el puente entre el hogar y el colegio. Juntos fortalecemos el proceso formativo de nuestros estudiantes a través de una participación activa y divertida.
            </p>
            <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                <a href="<?php echo URLROOT; ?>/auth/login" class="bg-primary text-on-primary px-10 py-4 rounded-2xl font-label-lg text-label-lg shadow-lg shadow-primary/20 hover:scale-105 transition-all flex items-center gap-3 group">
                    <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">login</span>
                    Entrar al Portal
                </a>
                <a href="#visitante" class="bg-white border-2 border-outline-variant text-on-surface-variant px-10 py-4 rounded-2xl font-label-lg text-label-lg hover:bg-surface-container transition-all flex items-center gap-3">
                    <span class="material-symbols-outlined">explore</span>
                    Modo Visitante
                </a>
            </div>
        </div>
        <div class="flex-1 relative animate-float">
            <div class="bg-primary/20 rounded-full w-64 h-64 md:w-96 md:h-96 absolute -top-12 -left-12 blur-3xl opacity-50"></div>
            <div class="bg-secondary/10 rounded-full w-48 h-48 md:w-80 md:h-80 absolute -bottom-8 -right-8 blur-3xl opacity-50"></div>
            <img src="<?php echo URLROOT; ?>/assets/img/logo.png" alt="EduSaft Logo" class="relative z-10 w-full max-w-sm mx-auto drop-shadow-[0_20px_50px_rgba(0,0,0,0.15)]">
        </div>
    </div>

    <!-- Misión y Visión -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-32">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 md:p-12 rounded-3xl border border-outline-variant shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <span class="material-symbols-outlined text-9xl">rocket_launch</span>
                </div>
                <h3 class="text-headline-md text-primary font-bold mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined">rocket_launch</span>
                    Misión
                </h3>
                <p class="text-body-md text-on-surface-variant leading-relaxed">
                    Transformar la comunicación escolar mediante tecnología innovadora que incentive la participación activa de las familias, asegurando que cada padre sea un co-educador comprometido en el éxito integral de sus hijos.
                </p>
            </div>
            <div class="bg-white p-8 md:p-12 rounded-3xl border border-outline-variant shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                    <span class="material-symbols-outlined text-9xl">visibility</span>
                </div>
                <h3 class="text-headline-md text-secondary font-bold mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined">visibility</span>
                    Visión
                </h3>
                <p class="text-body-md text-on-surface-variant leading-relaxed">
                    Ser el modelo nacional de integración familia-escuela, logrando que el 100% de los acudientes se involucren emocional y académicamente en la vida escolar, reduciendo la deserción y potenciando el talento de nuestros estudiantes.
                </p>
            </div>
        </div>
    </div>

    <!-- Modo Visitante (Contenido Parcial) -->
    <section id="visitante" class="bg-surface-container/50 backdrop-blur-md py-24 border-y border-outline-variant relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-headline-lg text-on-surface font-headline-lg mb-4">Sección de Noticias y Eventos</h2>
                <p class="text-body-md text-on-surface-variant">Manténgase informado sobre el acontecer institucional</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Noticias Recientes -->
                <div class="lg:col-span-2 space-y-6">
                    <h4 class="text-title-lg text-on-surface font-bold flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-primary">newspaper</span>
                        Últimas Noticias
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-2xl border border-outline-variant overflow-hidden group hover:shadow-xl transition-all">
                            <div class="h-48 bg-surface-container-high relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <span class="absolute bottom-4 left-4 text-white text-label-sm bg-primary/80 px-3 py-1 rounded-full">Académico</span>
                            </div>
                            <div class="p-6">
                                <span class="text-outline text-xs font-medium">25 ABRIL, 2026</span>
                                <h5 class="text-title-md text-on-surface font-bold mt-2 group-hover:text-primary transition-colors">Entrega de informes del primer periodo</h5>
                                <p class="text-body-sm text-on-surface-variant mt-3 line-clamp-2">Invitamos a todos los padres de familia a asistir a la reunión presencial para...</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl border border-outline-variant overflow-hidden group hover:shadow-xl transition-all">
                            <div class="h-48 bg-surface-container-high relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <span class="absolute bottom-4 left-4 text-white text-label-sm bg-secondary/80 px-3 py-1 rounded-full">Comunidad</span>
                            </div>
                            <div class="p-6">
                                <span class="text-outline text-xs font-medium">22 ABRIL, 2026</span>
                                <h5 class="text-title-md text-on-surface font-bold mt-2 group-hover:text-secondary transition-colors">Gran jornada deportiva institucional</h5>
                                <p class="text-body-sm text-on-surface-variant mt-3 line-clamp-2">Venga con su familia a disfrutar de un día lleno de juegos y recreación en la sede...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendario de Eventos -->
                <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                    <h4 class="text-title-lg text-on-surface font-bold flex items-center gap-3 mb-8">
                        <span class="material-symbols-outlined text-primary">calendar_today</span>
                        Próximos Eventos
                    </h4>
                    <div class="space-y-6">
                        <?php if (empty($data['actividades'])): ?>
                            <p class="text-body-sm text-on-surface-variant text-center py-4">No hay eventos programados en este momento.</p>
                        <?php else: ?>
                            <?php foreach ($data['actividades'] as $index => $act): 
                                $fecha = new DateTime($act->fecha_hora_inicio);
                                $dia = $fecha->format('d');
                                $meses = [
                                    1 => 'ENE', 2 => 'FEB', 3 => 'MAR', 4 => 'ABR', 5 => 'MAY', 6 => 'JUN',
                                    7 => 'JUL', 8 => 'AGO', 9 => 'SEP', 10 => 'OCT', 11 => 'NOV', 12 => 'DIC'
                                ];
                                $mes = $meses[(int)$fecha->format('n')];
                                
                                $borderColors = ['border-primary', 'border-secondary', 'border-tertiary'];
                                $textColors = ['text-primary', 'text-secondary', 'text-tertiary'];
                                $borderColor = $borderColors[$index % 3];
                                $textColor = $textColors[$index % 3];

                                // Prepare data for javascript modal
                                $actData = [
                                    'id' => $act->id_actividad,
                                    'nombre' => htmlspecialchars($act->nombre_actividad),
                                    'descripcion' => htmlspecialchars($act->descripcion ?? 'Sin descripción disponible.'),
                                    'tipo' => htmlspecialchars($act->nombre_tipo),
                                    'sede' => htmlspecialchars($act->nombre_sede),
                                    'fecha' => $fecha->format('d/m/Y'),
                                    'hora' => $fecha->format('H:i') . (!empty($act->fecha_hora_fin) ? ' — ' . date('H:i', strtotime($act->fecha_hora_fin)) : ''),
                                    'fotos' => json_decode($act->fotos) ?? []
                                ];
                            ?>
                                <div class="flex gap-4 items-start border-l-4 <?php echo $borderColor; ?> pl-4 cursor-pointer hover:bg-surface-container-low/80 p-2 rounded-xl transition-all hover:translate-x-1" onclick="abrirComicModal(<?php echo htmlspecialchars(json_encode($actData), ENT_QUOTES, 'UTF-8'); ?>)">
                                    <div class="flex flex-col items-center justify-center min-w-[50px]">
                                        <span class="text-headline-sm font-bold <?php echo $textColor; ?>"><?php echo $dia; ?></span>
                                        <span class="text-xs text-outline uppercase font-bold"><?php echo $mes; ?></span>
                                    </div>
                                    <div class="flex-grow">
                                        <h6 class="font-bold text-on-surface hover:text-primary transition-colors"><?php echo htmlspecialchars($act->nombre_actividad); ?></h6>
                                        <p class="text-body-sm text-on-surface-variant"><?php echo htmlspecialchars($act->nombre_sede); ?> - <?php echo date('H:i', strtotime($act->fecha_hora_inicio)); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo URLROOT; ?>/home/calendario" class="block w-full text-center mt-10 text-primary font-bold text-sm border-2 border-primary/20 py-3 rounded-xl hover:bg-primary/5 transition-all">Ver calendario completo</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-headline-lg text-on-surface font-headline-lg">Galería Institucional</h2>
                <p class="text-body-md text-on-surface-variant mt-2">Nuestros momentos en comunidad</p>
            </div>
            <a href="#" class="text-primary font-bold hover:underline flex items-center gap-2">Ver todo <span class="material-symbols-outlined text-sm">open_in_new</span></a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="h-64 bg-surface-container rounded-3xl hover:scale-[1.02] transition-transform cursor-pointer overflow-hidden">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity" alt="School">
            </div>
            <div class="h-64 bg-surface-container rounded-3xl hover:scale-[1.02] transition-transform cursor-pointer overflow-hidden md:mt-8">
                <img src="https://images.unsplash.com/photo-1544717297-fa154da097e6?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity" alt="Library">
            </div>
            <div class="h-64 bg-surface-container rounded-3xl hover:scale-[1.02] transition-transform cursor-pointer overflow-hidden">
                <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity" alt="Classroom">
            </div>
            <div class="h-64 bg-surface-container rounded-3xl hover:scale-[1.02] transition-transform cursor-pointer overflow-hidden md:mt-8">
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity" alt="Students">
            </div>
        </div>
    </div>
</div>

<!-- Modal Globo de Diálogo de Cómic -->
<div id="comicModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/45 backdrop-blur-[4px] transition-opacity duration-300 opacity-0" onclick="cerrarComicModal()"></div>
    
    <!-- Comic Bubble Wrapper -->
    <div class="relative w-full max-w-lg transition-all duration-300 transform scale-95 opacity-0 z-10">
        
        <!-- The Comic Speech Bubble -->
        <div class="bg-white border-[5px] border-slate-900 rounded-[32px] p-6 md:p-8 shadow-[8px_8px_0px_0px_#0b1c30] relative overflow-visible">
            
            <!-- Close Button (styled like a comic book badge) -->
            <button onclick="cerrarComicModal()" class="absolute -top-3 -right-3 bg-secondary text-white border-[3px] border-slate-900 rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-[3px_3px_0px_0px_#0b1c30] z-30 font-extrabold text-lg">
                ✕
            </button>
            
            <!-- Activity Category Badge (Comic Burst style) -->
            <div class="absolute -top-6 left-8 bg-yellow-300 text-slate-900 border-[3px] border-slate-900 px-4 py-1.5 rounded-lg rotate-[-3deg] shadow-[3px_3px_0px_0px_#0b1c30] font-black uppercase text-xs tracking-wider z-20">
                <span id="comicType">ACTIVIDAD</span>
            </div>

            <!-- Carousel of activity photos -->
            <div class="relative h-60 w-full rounded-2xl border-[4px] border-slate-900 overflow-hidden mb-6 bg-slate-100 shadow-inner group">
                <div id="carouselSlides" class="absolute inset-0 flex transition-transform duration-500 ease-in-out">
                    <!-- Slides will be inserted dynamically -->
                </div>
                
                <!-- Left/Right controls (Comic styled arrows) -->
                <button id="carouselPrevBtn" onclick="moverCarousel(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 bg-yellow-300 text-slate-900 border-[3px] border-slate-900 rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-[2px_2px_0px_0px_#0b1c30] font-black text-xl z-20 opacity-0 group-hover:opacity-100">
                    &lt;
                </button>
                <button id="carouselNextBtn" onclick="moverCarousel(1)" class="absolute right-3 top-1/2 -translate-y-1/2 bg-yellow-300 text-slate-900 border-[3px] border-slate-900 rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-[2px_2px_0px_0px_#0b1c30] font-black text-xl z-20 opacity-0 group-hover:opacity-100">
                    &gt;
                </button>

                <!-- Carousel Indicators/Dots -->
                <div id="carouselDots" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-20 bg-slate-900/60 px-3 py-1 rounded-full">
                    <!-- Dots will be inserted dynamically -->
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-4">
                <h3 id="comicTitle" class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight leading-none uppercase border-b-4 border-dashed border-slate-200 pb-3">
                    Nombre de Actividad
                </h3>
                
                <!-- Event info table (Comic block style) -->
                <div class="grid grid-cols-2 gap-3 bg-yellow-50/50 border-2 border-slate-900 rounded-xl p-3 text-xs font-bold text-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-secondary">event</span>
                        <span id="comicDate">Fecha</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-secondary">schedule</span>
                        <span id="comicTime">Hora</span>
                    </div>
                    <div class="col-span-2 flex items-center gap-2 border-t border-slate-200 pt-2">
                        <span class="material-symbols-outlined text-[16px] text-secondary">location_on</span>
                        <span id="comicSede">Sede</span>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-400 mb-1">Sobre la actividad:</h4>
                    <p id="comicDesc" class="text-sm text-slate-700 leading-relaxed font-medium line-clamp-4">
                        Descripción detallada...
                    </p>
                </div>
            </div>

            <!-- Ingresar Button (Comic style) -->
            <div class="mt-6 pt-4 border-t-4 border-dashed border-slate-200 flex justify-center">
                <a href="<?php echo URLROOT; ?>/auth/login" class="w-full text-center py-4 bg-primary text-on-primary border-[4px] border-slate-900 rounded-2xl font-black uppercase text-sm tracking-wider shadow-[4px_4px_0px_0px_#0b1c30] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#0b1c30] transition-all duration-150 active:scale-98">
                    ¡Ingresar al Portal!
                </a>
            </div>
        </div>
        
        <!-- Speech Bubble Tail (Simulating the comic voice indicator) -->
        <div class="comic-bubble-tail"></div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 1s ease-out forwards;
}
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}
.animate-float {
    animation: float 6s ease-in-out infinite;
}

/* Comic Book Speech Bubble Tail */
.comic-bubble-tail {
    position: absolute;
    bottom: -22px;
    left: 80px;
    width: 0;
    height: 0;
    border-width: 25px 25px 0 0;
    border-style: solid;
    border-color: #0b1c30 transparent transparent transparent;
    z-index: 5;
}
.comic-bubble-tail::after {
    content: '';
    position: absolute;
    top: -29px;
    left: 4px;
    width: 0;
    height: 0;
    border-width: 20px 20px 0 0;
    border-style: solid;
    border-color: #ffffff transparent transparent transparent;
    z-index: 6;
}
</style>

<script>
let currentSlideIndex = 0;
let modalSlides = [];

function abrirComicModal(act) {
    // Fill text content
    document.getElementById('comicTitle').textContent = act.nombre;
    document.getElementById('comicDesc').textContent = act.descripcion;
    document.getElementById('comicType').textContent = act.tipo;
    document.getElementById('comicSede').textContent = act.sede;
    document.getElementById('comicDate').textContent = act.fecha;
    document.getElementById('comicTime').textContent = act.hora;

    // Load Carousel Photos
    const slidesContainer = document.getElementById('carouselSlides');
    const dotsContainer = document.getElementById('carouselDots');
    slidesContainer.innerHTML = '';
    dotsContainer.innerHTML = '';
    
    let fotos = act.fotos;
    // Fallback if empty array
    if (!fotos || fotos.length === 0) {
        fotos = ['/BASAMASOFT/public/assets/img/actividades/actividad_familia.png'];
    }
    
    modalSlides = fotos;
    currentSlideIndex = 0;

    fotos.forEach((foto, i) => {
        // Slide image
        const slide = document.createElement('div');
        slide.className = 'w-full h-full flex-shrink-0';
        slide.innerHTML = `<img src="${foto}" class="w-full h-full object-cover" alt="Foto ${i+1}">`;
        slidesContainer.appendChild(slide);

        // Dot indicator
        const dot = document.createElement('button');
        dot.className = `w-2 h-2 rounded-full transition-all ${i === 0 ? 'bg-yellow-300 scale-125' : 'bg-slate-400'}`;
        dot.onclick = () => irASlide(i);
        dotsContainer.appendChild(dot);
    });

    // Hide/show navigation controls based on image count
    const prevBtn = document.getElementById('carouselPrevBtn');
    const nextBtn = document.getElementById('carouselNextBtn');
    if (fotos.length <= 1) {
        prevBtn.classList.add('hidden');
        nextBtn.classList.add('hidden');
        dotsContainer.classList.add('hidden');
    } else {
        prevBtn.classList.remove('hidden');
        nextBtn.classList.remove('hidden');
        dotsContainer.classList.remove('hidden');
    }

    actualizarCarousel();

    // Show Modal
    const modal = document.getElementById('comicModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-black\\/45').classList.remove('opacity-0');
        modal.querySelector('.bg-black\\/45').classList.add('opacity-100');
        modal.querySelector('.relative.max-w-lg').classList.remove('scale-95', 'opacity-0');
        modal.querySelector('.relative.max-w-lg').classList.add('scale-100', 'opacity-100');
    }, 10);
}

function cerrarComicModal() {
    const modal = document.getElementById('comicModal');
    if (modal) {
        modal.querySelector('.bg-black\\/45').classList.add('opacity-0');
        modal.querySelector('.relative.max-w-lg').classList.add('scale-95', 'opacity-0');
        modal.querySelector('.relative.max-w-lg').classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

function irASlide(index) {
    currentSlideIndex = index;
    actualizarCarousel();
}

function moverCarousel(dir) {
    currentSlideIndex = (currentSlideIndex + dir + modalSlides.length) % modalSlides.length;
    actualizarCarousel();
}

function actualizarCarousel() {
    const slidesContainer = document.getElementById('carouselSlides');
    slidesContainer.style.transform = `translateX(-${currentSlideIndex * 100}%)`;

    // Update dots
    const dots = document.getElementById('carouselDots').children;
    for (let i = 0; i < dots.length; i++) {
        if (i === currentSlideIndex) {
            dots[i].className = 'w-2.5 h-2.5 rounded-full transition-all bg-yellow-300 scale-125';
        } else {
            dots[i].className = 'w-2 h-2 rounded-full transition-all bg-slate-400';
        }
    }
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
