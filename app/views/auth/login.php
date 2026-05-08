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
<main class="w-full max-w-lg mx-auto">
    <!-- Login Form Card -->
    <div class="bg-surface border border-outline-variant rounded-2xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-sm bg-white/90">
        <div class="mb-8 text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">login</span>
            </div>
            <h3 class="font-headline-md text-headline-md text-primary tracking-tight">INICIAR SESIÓN</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mt-2">Bienvenido de nuevo a su comunidad educativa</p>
        </div>
        
        <form action="<?php echo URLROOT; ?>/auth/login" method="post" class="space-y-6">
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface ml-1">Usuario</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">person</span>
                    <input name="username" 
                           class="w-full border <?php echo (!empty($data['username_err'])) ? 'border-error' : 'border-outline-variant'; ?> rounded-xl pl-11 pr-4 py-3 bg-surface/50 text-on-surface font-body-md text-body-md focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all" 
                           placeholder="Ingrese su usuario" 
                           type="text" 
                           value="<?php echo $data['username']; ?>"/>
                    <?php if(!empty($data['username_err'])): ?>
                        <span class="text-error text-xs mt-1 ml-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <?php echo $data['username_err']; ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface ml-1">Contraseña</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
                    <input id="password-input"
                           name="password" 
                           class="w-full border <?php echo (!empty($data['password_err'])) ? 'border-error' : 'border-outline-variant'; ?> rounded-xl pl-11 pr-12 py-3 bg-surface/50 text-on-surface font-body-md text-body-md focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all" 
                           placeholder="••••••••" 
                           type="password"/>
                    <button type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                        <span id="password-icon" class="material-symbols-outlined text-xl">visibility</span>
                    </button>
                    <?php if(!empty($data['password_err'])): ?>
                        <span class="text-error text-xs mt-1 ml-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <?php echo $data['password_err']; ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-2">
                <button class="w-full bg-primary hover:bg-primary/90 text-on-primary font-label-lg text-label-lg py-4 rounded-xl transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2 group" 
                        type="submit">
                    <span>Entrar al Sistema</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </div>
            
            <div class="pt-4 text-center border-t border-outline-variant/30">
                <p class="text-sm text-on-surface-variant">
                    ¿Olvidó sus credenciales? <br>
                    <span class="text-primary font-medium">Contacte al administrador de la sede</span>
                </p>
                <div class="mt-4">
                    <a href="<?php echo URLROOT; ?>/home/terminos" class="text-xs text-outline hover:text-primary underline decoration-dotted">Términos y condiciones de uso</a>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
function togglePassword() {
    const input = document.getElementById('password-input');
    const icon = document.getElementById('password-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerText = 'visibility_off';
    } else {
        input.type = 'password';
        icon.innerText = 'visibility';
    }
}
</script>
</body>
</html>
