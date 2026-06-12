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
                <div class="bg-white rounded-3xl border border-outline-variant p-8">
                    <h4 class="text-title-lg text-on-surface font-bold flex items-center gap-3 mb-8">
                        <span class="material-symbols-outlined text-primary">calendar_today</span>
                        Próximos Eventos
                    </h4>
                    <div class="space-y-6">
                        <div class="flex gap-4 items-start border-l-4 border-primary pl-4">
                            <div class="flex flex-col items-center justify-center min-w-[50px]">
                                <span class="text-headline-sm font-bold text-primary">28</span>
                                <span class="text-xs text-outline uppercase font-bold">ABR</span>
                            </div>
                            <div>
                                <h6 class="font-bold text-on-surface">Escuela para Padres</h6>
                                <p class="text-body-sm text-on-surface-variant">Sede Principal - 4:00 PM</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start border-l-4 border-secondary pl-4">
                            <div class="flex flex-col items-center justify-center min-w-[50px]">
                                <span class="text-headline-sm font-bold text-secondary">05</span>
                                <span class="text-xs text-outline uppercase font-bold">MAY</span>
                            </div>
                            <div>
                                <h6 class="font-bold text-on-surface">Día de la Familia</h6>
                                <p class="text-body-sm text-on-surface-variant">Anexo Norte - 8:00 AM</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start border-l-4 border-outline pl-4 opacity-60">
                            <div class="flex flex-col items-center justify-center min-w-[50px]">
                                <span class="text-headline-sm font-bold text-outline">12</span>
                                <span class="text-xs text-outline uppercase font-bold">MAY</span>
                            </div>
                            <div>
                                <h6 class="font-bold text-on-surface">Comité de Convivencia</h6>
                                <p class="text-body-sm text-on-surface-variant">Virtual - 6:30 PM</p>
                            </div>
                        </div>
                    </div>
                    <button class="w-full mt-10 text-primary font-bold text-sm border-2 border-primary/20 py-3 rounded-xl hover:bg-primary/5 transition-all">Ver calendario completo</button>
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
</style>


<?php require APPROOT . '/views/inc/footer.php'; ?>
