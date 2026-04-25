<?php require APPROOT . '/views/inc/header.php'; ?>
<body class="bg-background text-on-background min-h-screen flex flex-col items-center justify-center p-md graph-paper-bg">
<!-- Top Branding Section -->
<header class="flex flex-col md:flex-row items-center gap-md mb-lg max-w-4xl w-full">
    <div class="flex-shrink-0">
        <img class="w-40 h-40 rounded-full border-2 border-outline-variant shadow-sm bg-surface object-cover" src="<?php echo URLROOT; ?>/assets/img/logo.png" alt="Logo">
    </div>
    <div class="flex flex-col items-center md:items-start text-center md:text-left">
        <h2 class="font-headline-md text-headline-md text-on-surface-variant max-w-md">
            INSTITUCION EDUCATIVA BARRIO SANTA MARGARITA
        </h2>
        <h1 class="font-headline-xl text-headline-xl text-primary mt-sm tracking-tight">
            Edusaft
        </h1>
    </div>
</header>

<!-- Main Content Area: Split Cards -->
<main class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-md items-start">
    <!-- Login Form Card -->
    <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-[0_4px_12px_rgba(0,0,0,0.05)]">
        <div class="mb-md">
            <h3 class="font-headline-md text-headline-md text-primary">INICIAR SESIÓN</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Acceda a su cuenta institucional</p>
        </div>
        <form action="<?php echo URLROOT; ?>/auth/login" method="post" class="space-y-sm">
            <div class="flex flex-col gap-xs">
                <label class="font-label-md text-label-md text-on-surface">Usuario</label>
                <div class="relative">
                    <input name="username" class="w-full border <?php echo (!empty($data['username_err'])) ? 'border-error' : 'border-outline-variant'; ?> rounded-lg px-3 py-2 bg-surface text-on-surface font-body-md text-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" placeholder="Ingrese su usuario" type="text" value="<?php echo $data['username']; ?>"/>
                    <span class="text-error text-xs"><?php echo $data['username_err']; ?></span>
                </div>
            </div>
            <div class="flex flex-col gap-xs">
                <label class="font-label-md text-label-md text-on-surface">Contraseña</label>
                <div class="relative">
                    <input name="password" class="w-full border <?php echo (!empty($data['password_err'])) ? 'border-error' : 'border-outline-variant'; ?> rounded-lg pl-3 pr-10 py-2 bg-surface text-on-surface font-body-md text-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" placeholder="••••••••" type="password"/>
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary" type="button">
                        <span class="material-symbols-outlined text-lg">visibility</span>
                    </button>
                    <span class="text-error text-xs"><?php echo $data['password_err']; ?></span>
                </div>
            </div>
            <div class="pt-sm">
                <button class="w-full bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md py-3 rounded-lg transition-colors shadow-sm" type="submit">
                    Enviar
                </button>
            </div>
            <div class="mt-4 text-center">
                <p class="text-sm">Lee nuestros <a href="<?php echo URLROOT; ?>/home/terminos" class="text-primary hover:underline">términos y condiciones</a></p>
            </div>
        </form>
    </div>

    <!-- Registration Form Card -->
    <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-[0_4px_12px_rgba(0,0,0,0.05)]">
        <div class="mb-md flex items-center justify-between">
            <div>
                <h3 class="font-headline-md text-headline-md text-primary">REGÍSTRESE</h3>
                <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Cree una nueva cuenta</p>
            </div>
            <span class="material-symbols-outlined text-primary text-3xl opacity-20" style="font-variation-settings: 'FILL' 1;">person_add</span>
        </div>
        <form action="<?php echo URLROOT; ?>/auth/register" method="post" class="space-y-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-sm">
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface">Rol</label>
                    <select name="role" class="w-full border border-outline-variant rounded-lg px-3 py-2 bg-surface text-on-surface font-body-md text-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors">
                        <option value="">Seleccione rol</option>
                        <option value="student">Estudiante</option>
                        <option value="parent">Acudiente</option>
                    </select>
                </div>
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface">Sede</label>
                    <select name="sede" class="w-full border border-outline-variant rounded-lg px-3 py-2 bg-surface text-on-surface font-body-md text-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors">
                        <option value="">Principal</option>
                        <option value="anexo1">Anexo Norte</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-col gap-xs">
                <label class="font-label-md text-label-md text-on-surface">Nombre Completo</label>
                <input name="name" class="w-full border border-outline-variant rounded-lg px-3 py-2 bg-surface text-on-surface font-body-md text-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" placeholder="Ej. Juan Andrés Pérez" type="text"/>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-sm">
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface">Familia</label>
                    <input name="family" class="w-full border border-outline-variant rounded-lg px-3 py-2 bg-surface text-on-surface font-body-md text-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" placeholder="Ej. Familia Pérez" type="text"/>
                </div>
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface">Grupo</label>
                    <input name="group" class="w-full border border-outline-variant rounded-lg px-3 py-2 bg-surface text-on-surface font-body-md text-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" placeholder="Ej. 10-A" type="text"/>
                </div>
            </div>
            <div class="flex flex-col gap-xs">
                <label class="font-label-md text-label-md text-on-surface">Contraseña</label>
                <div class="relative">
                    <input name="password" class="w-full border border-outline-variant rounded-lg pl-3 pr-10 py-2 bg-surface text-on-surface font-body-md text-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" placeholder="Cree una contraseña segura" type="password"/>
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary" type="button">
                        <span class="material-symbols-outlined text-lg">visibility</span>
                    </button>
                </div>
            </div>
            <div class="pt-sm">
                <button class="w-full border-2 border-primary text-primary hover:bg-surface-container font-label-md text-label-md py-3 rounded-lg transition-colors bg-transparent" type="submit">
                    Registrarse
                </button>
            </div>
        </form>
    </div>
</main>
</body>
</html>
