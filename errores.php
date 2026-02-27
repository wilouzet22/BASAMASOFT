<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración básica del documento HTML -->
    <meta charset="UTF-8">
    <!-- Asegura que la página sea responsiva en dispositivos móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Errores Web</title>
    <style>
        /* Estilos generales del cuerpo de la página */
        body {
            font-family: Arial, sans-serif; /* Fuente principal del sitio */
            margin: 0; /* Elimina márgenes predeterminados */
            padding: 20px; /* Añade espacio alrededor del contenido */
            background-color: #f0f2f5; /* Color de fondo suave */
        }

        /* Contenedor principal que centra el contenido */
        .container {
            max-width: 1200px; /* Ancho máximo del contenido */
            margin: 0 auto; /* Centra el contenedor horizontalmente */
        }

        /* Estilo para cada sección de errores */
        .error-section {
            background: white; /* Fondo blanco para cada sección */
            padding: 20px; /* Espacio interno */
            margin: 20px 0; /* Espacio entre secciones */
            border-radius: 8px; /* Esquinas redondeadas */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Sombra suave */
        }

        /* Estilo para los botones de error */
        .error-button {
            padding: 10px 20px; /* Espacio interno del botón */
            margin: 5px; /* Espacio entre botones */
            border: none; /* Elimina borde predeterminado */
            border-radius: 5px; /* Esquinas redondeadas */
            background-color: #3498db; /* Color de fondo azul */
            color: white; /* Texto en blanco */
            cursor: pointer; /* Cambia el cursor al pasar por encima */
            transition: background-color 0.3s; /* Transición suave al hover */
        }

        /* Efecto hover para los botones */
        .error-button:hover {
            background-color: #2980b9; /* Color más oscuro al pasar el mouse */
        }

        /* Contenedor de la pantalla de error modal */
        .error-screen {
            position: fixed; /* Posición fija en la pantalla */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9); /* Fondo negro semi-transparente */
            display: none; /* Oculto por defecto */
            justify-content: center; /* Centrado horizontal */
            align-items: center; /* Centrado vertical */
            z-index: 1000; /* Asegura que esté por encima de otros elementos */
        }

        /* Contenedor del contenido del error */
        .error-content {
            background: white; /* Fondo blanco */
            padding: 30px; /* Espacio interno */
            border-radius: 10px; /* Esquinas redondeadas */
            text-align: center; /* Texto centrado */
            max-width: 500px; /* Ancho máximo */
            width: 90%; /* Ancho responsivo */
            position: relative; /* Para posicionar el botón de cierre */
        }

        /* Estilo para el icono de error */
        .error-icon {
            font-size: 64px; /* Tamaño grande para el icono */
            margin-bottom: 20px; /* Espacio debajo del icono */
        }

        /* Estilo para el título del error */
        .error-title {
            font-size: 32px; /* Tamaño grande para el título */
            margin-bottom: 15px; /* Espacio debajo del título */
            color: #e74c3c; /* Color rojo para el título */
        }

        /* Estilo para el mensaje de error */
        .error-message {
            font-size: 18px; /* Tamaño del texto del mensaje */
            margin-bottom: 20px; /* Espacio debajo del mensaje */
            color: #2c3e50; /* Color gris oscuro para el texto */
        }

        /* Estilo para el botón de cierre */
        .close-button {
            position: absolute; /* Posición absoluta respecto al contenedor */
            top: 10px;
            right: 10px;
            background: none; /* Sin fondo */
            border: none; /* Sin borde */
            font-size: 24px; /* Tamaño del símbolo × */
            cursor: pointer; /* Cursor tipo pointer */
            color: #666; /* Color gris */
        }

        /* Efecto hover para el botón de cierre */
        .close-button:hover {
            color: #000; /* Color negro al pasar el mouse */
        }

        /* Colores de fondo específicos para cada tipo de error */
        .error-404 { background-color: #ffebee; }
        .error-500 { background-color: #fff3e0; }
        .error-403 { background-color: #e8f5e9; }
        .error-401 { background-color: #e3f2fd; }
        .error-502 { background-color: #f3e5f5; }
        .error-503 { background-color: #fce4ec; }
        .error-400 { background-color: #e8eaf6; }

        /* Contenedor para la pantalla de carga */
        .loading-overlay {
            position: fixed; /* Posición fija en la pantalla */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8); /* Fondo negro semi-transparente */
            display: none; /* Oculto por defecto */
            justify-content: center; /* Centrado horizontal */
            align-items: center; /* Centrado vertical */
            z-index: 1000; /* Asegura que esté por encima de otros elementos */
        }

        /* Estilo para el spinner de carga */
        .loading-spinner {
            border: 8px solid #f3f3f3; /* Borde exterior del spinner */
            border-top: 8px solid #3498db; /* Borde superior azul */
            border-radius: 50%; /* Forma circular */
            width: 60px; /* Ancho del spinner */
            height: 60px; /* Alto del spinner */
            animation: spin 1s linear infinite; /* Animación de rotación */
        }

        /* Definición de la animación de rotación */
        @keyframes spin {
            0% { transform: rotate(0deg); } /* Inicio de la rotación */
            100% { transform: rotate(360deg); } /* Fin de la rotación */
        }

        /* Estilo para el texto de carga */
        .loading-text {
            color: white; /* Texto en blanco */
            margin-top: 20px; /* Espacio arriba del texto */
            font-size: 20px; /* Tamaño del texto */
        }
    </style>
</head>
<body>
    <!-- Contenedor principal -->
    <div class="container">
        <h1>Simulador de Errores Web</h1>

        <!-- Sección de errores HTTP -->
        <div class="error-section">
            <h2>Errores HTTP</h2>
            <!-- Botones para cada tipo de error HTTP -->
            <button class="error-button" onclick="mostrarErrorHTTP(404)">Error 404</button>
            <button class="error-button" onclick="mostrarErrorHTTP(500)">Error 500</button>
            <button class="error-button" onclick="mostrarErrorHTTP(403)">Error 403</button>
            <button class="error-button" onclick="mostrarErrorHTTP(401)">Error 401</button>
            <button class="error-button" onclick="mostrarErrorHTTP(502)">Error 502</button>
            <button class="error-button" onclick="mostrarErrorHTTP(503)">Error 503</button>
            <button class="error-button" onclick="mostrarErrorHTTP(400)">Error 400</button>
        </div>

        <!-- Sección de errores JavaScript -->
        <div class="error-section">
            <h2>Errores de JavaScript</h2>
            <!-- Botones para cada tipo de error JavaScript -->
            <button class="error-button" onclick="mostrarErrorJS('dom')">Error DOM</button>
            <button class="error-button" onclick="mostrarErrorJS('funcion')">Error de Función</button>
            <button class="error-button" onclick="mostrarErrorJS('sintaxis')">Error de Sintaxis</button>
        </div>

        <!-- Sección de problemas de rendimiento -->
        <div class="error-section">
            <h2>Problemas de Rendimiento</h2>
            <button class="error-button" onclick="mostrarCargaInfinita()">Carga Infinita</button>
        </div>
    </div>

    <!-- Overlay para mostrar errores -->
    <div id="errorScreen" class="error-screen">
        <div class="error-content">
            <button class="close-button" onclick="cerrarError()">×</button>
            <div class="error-icon">⚠️</div>
            <h2 class="error-title"></h2>
            <p class="error-message"></p>
        </div>
    </div>

    <!-- Overlay para mostrar carga infinita -->
    <div id="loadingOverlay" class="loading-overlay">
        <div>
            <div class="loading-spinner"></div>
            <p class="loading-text">Cargando...</p>
        </div>
    </div>

    <script>
        // Función para mostrar errores HTTP
        function mostrarErrorHTTP(codigo) {
            // Obtiene referencias a los elementos del DOM
            const errorScreen = document.getElementById('errorScreen');
            const errorContent = errorScreen.querySelector('.error-content');
            const errorTitle = errorContent.querySelector('.error-title');
            const errorMessage = errorContent.querySelector('.error-message');
            const errorIcon = errorContent.querySelector('.error-icon');

            // Objeto con los mensajes y configuraciones para cada tipo de error
            const mensajes = {
                404: {
                    titulo: 'Error 404 - Página no encontrada',
                    mensaje: 'Lo sentimos, la página que buscas no existe.',
                    icono: '🔍'
                },
                500: {
                    titulo: 'Error 500 - Error interno del servidor',
                    mensaje: 'Algo salió mal en el servidor. Por favor, intenta más tarde.',
                    icono: '💥'
                },
                403: {
                    titulo: 'Error 403 - Acceso prohibido',
                    mensaje: 'No tienes permiso para acceder a este recurso.',
                    icono: '🚫'
                },
                401: {
                    titulo: 'Error 401 - No autorizado',
                    mensaje: 'Necesitas iniciar sesión para acceder a este recurso.',
                    icono: '🔒'
                },
                502: {
                    titulo: 'Error 502 - Bad Gateway',
                    mensaje: 'Error de comunicación entre servidores.',
                    icono: '🌐'
                },
                503: {
                    titulo: 'Error 503 - Servicio no disponible',
                    mensaje: 'El servidor está temporalmente sobrecargado.',
                    icono: '⏳'
                },
                400: {
                    titulo: 'Error 400 - Solicitud incorrecta',
                    mensaje: 'La petición tiene un formato inválido.',
                    icono: '❌'
                }
            };

            // Actualiza el contenido del error con la información correspondiente
            errorContent.className = `error-content error-${codigo}`;
            errorTitle.textContent = mensajes[codigo].titulo;
            errorMessage.textContent = mensajes[codigo].mensaje;
            errorIcon.textContent = mensajes[codigo].icono;
            errorScreen.style.display = 'flex';
        }

        // Función para mostrar errores de JavaScript
        function mostrarErrorJS(tipo) {
            // Obtiene referencias a los elementos del DOM
            const errorScreen = document.getElementById('errorScreen');
            const errorContent = errorScreen.querySelector('.error-content');
            const errorTitle = errorContent.querySelector('.error-title');
            const errorMessage = errorContent.querySelector('.error-message');
            const errorIcon = errorContent.querySelector('.error-icon');

            // Objeto con los mensajes y configuraciones para cada tipo de error JavaScript
            const mensajes = {
                dom: {
                    titulo: 'Error DOM',
                    mensaje: 'No se pudo encontrar el elemento en el documento.',
                    icono: '📄'
                },
                funcion: {
                    titulo: 'Error de Función',
                    mensaje: 'La función que intentas llamar no está definida.',
                    icono: '⚙️'
                },
                sintaxis: {
                    titulo: 'Error de Sintaxis',
                    mensaje: 'Hay un error en la sintaxis del código JavaScript.',
                    icono: '📝'
                }
            };

            // Actualiza el contenido del error con la información correspondiente
            errorContent.className = 'error-content';
            errorTitle.textContent = mensajes[tipo].titulo;
            errorMessage.textContent = mensajes[tipo].mensaje;
            errorIcon.textContent = mensajes[tipo].icono;
            errorScreen.style.display = 'flex';
        }

        // Función para mostrar la pantalla de carga infinita
        function mostrarCargaInfinita() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'flex';
            
            // Simula una carga infinita (limitada a 5 segundos para demostración)
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
                alert('Esta carga normalmente sería infinita, pero la hemos limitado para demostración');
            }, 5000);
        }

        // Función para cerrar la ventana de error
        function cerrarError() {
            document.getElementById('errorScreen').style.display = 'none';
        }

        // Event listener para cerrar errores con la tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarError();
                document.getElementById('loadingOverlay').style.display = 'none';
            }
        });
    </script>
</body>
</html> 