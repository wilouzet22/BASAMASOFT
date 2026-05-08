# 🚀 Tutorial Definitivo (Versión Extendida): Creando tu primer proyecto MVC en PHP desde CERO

¡Felicitaciones por llegar hasta aquí! Este tutorial extenso y fundamental está diseñado por **AcademiaAds** para aquellos desarrolladores que no solo quieren copiar código, sino *entender el porqué* de las decisiones de arquitectura de la web moderna. A lo largo de esta guía construiremos desde cero un sistema web con **Patrón MVC** y —lo más importante— con **Rutas Amigables (Friendly URLs)**, el estándar oro en la industria.

---

## 🌟 Módulo 1: Desmitificando MVC en el Mundo Real
MVC es un acrónimo de tres responsabilidades que, jamás, deben cruzarse. Si haces una consulta a la BD dentro del diseño HTML, estás rompiendo el patrón. Es una separación estricta:

1.  **Modelo (M)**: Es el "Bodeguero". Él conoce dónde están los productos, cómo sumarlos y cómo insertarlos. No sabe nada de colores, botones o menús. Él solo habla SQL puro (MySQL).
2.  **Vista (V)**: Es el "Decorador". Aquí vive el HTML, el CSS, Bootstrap, el color de los botones, las imágenes y las alertas JS. La vista **no sabe** si la Base de Datos es Oracle, MySQL ni pide las cosas; solo espera que le den los datos masticados para "mostrarlos".
3.  **Controlador (C)**: Es el "Gerente". Si el usuario da clic en un botón, esa petición llega al Gerente. El Gerente, según la petición, le dice al Bodeguero (Modelo): *"Tráeme la lista de usuarios"*. Cuando el Modelo se la da, el Gerente toma esos datos y se los entrega al Decorador (Vista) para que los pinte en pantalla en forma de tabla.

Esta segregación permite que, si mañana quieres cambiar el diseño de la tabla, no toques ni por error la lógica del negocio ni rompas las funciones SQL.

---

## 🛠️ Módulo 2: Arquitectura y "Rutas Amigables" (Friendly URLs)

En los años 2000, los usuarios y los buscadores (Google) veían rutas espantosas como esta:  
🛑 `tusitio.com/index.php?controlador=productos&accion=mostrar&id=5`

Los motores de búsqueda odian esto porque no saben de qué trata la página. Un humano tampoco la puede memorizar fácilmente. Hoy en día, todos los frameworks modernos (Laravel, Symfony) utilizan "Rutas Amigables", que lucen así:  
✅ `tusitio.com/productos/mostrar/5`

¡Esto es hermoso para Google (SEO) y para el usuario! Lograrlo en PHP nativo requiere de dos trucos fundamentales que haremos a continuación: Magia de Servidor (Apache) y Magia de Código (el Front Controller).

### 2.1 La Magia de Servidor: El archivo `.htaccess`
Antes de tocar PHP, debes saber que por defecto, si escribes `/productos/mostrar/` en el navegador, tu servidor (Laragon/XAMPP) tratará de buscar la carpeta física `productos` y adentro un archivo `mostrar`. Al no encontrarlo, te escupirá un Error 404. 
El archivo `.htaccess` "engaña" a tu computadora para capturar todas las rutas falsas y mandarlas al Index.

1.  En tu carpeta principal (Ej: `c:\laragon\www\proyectoMVC\`), crea un archivo llamado literalmente `.htaccess` (sin nombre, solo esa extensión punto htaccess).
2.  Ponle esto dentro:

```apache
# Activamos el motor para sobreescribir direcciones de carpetas
RewriteEngine On

# Si lo que el usuario escribió NO ES un archivo real existente (-f)...
RewriteCond %{REQUEST_FILENAME} !-f
# ...Y TAMPOCO es un directorio/carpeta real existente (-d)...
RewriteCond %{REQUEST_FILENAME} !-d

# Entonces no des Error 404, en vez de eso agarra TODO lo que escribió
# y envíaselo en secreto a nuestro "index.php" a través de un paquete llamado "?url="
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

**Ejemplo Práctico**: Si pones en Chrome: `localhost/proyectoMVC/usuarios/crear`  
El servidor Apache ejecutará esto en silencio: `localhost/proyectoMVC/index.php?url=usuarios/crear`

---

## 🚦 Módulo 3: El Gran Front Controller (`index.php`) y el Ruteador

### 3.1 El Index Atrapalotodo
El `index.php` ahora recibe el string completo de nuestra URL falsa y usará `explode()` de PHP, herramienta similar a un cuchillo para cortar la ruta pedazo por pedazo, usando las barras `/` como si fuesen rebanadas.

Crea el archivo `index.php` en la raíz de tu proyecto con este código exacto:

```php
<?php 
    // ⚠️ CRÍTICO PARA RUTAS AMIGABLES: Constante Global.
    // Como las rutas parecerán "carpetas profundas" a los ojos del HTML, 
    // si intentas importar imágenes, CSS o scripts con un simple <script src="archivo.js">
    // el navegador lo buscará en "localhost/proyectoMVC/productos/archivo.js" y fallará (Error 404).
    // Con esta constante siempre inyectaremos la ruta padre oficial. (Cámbiala para producción on-line).
    define('URL_BASE', 'http://localhost/proyectoMVC/proyectoMVC/');
    
    // Asumimos que inicialmente el usuario quiere ir a la página principal genérica
    $getcontrolador = "paginas";    // Será la REBANADA [0]
    $getaccion = "inicio";          // Será la REBANADA [1]

    // ¿Nuestro servidor .htaccess nos mandó algo vía url falsa?
    if(isset($_GET['url'])) {
        // 1. Quitamos la barrita final por seguridad: "productos/mostrar/" -> "productos/mostrar"
        $url = rtrim($_GET['url'], '/');
        // 2. Limpiamos basura maliciosa hacker con saneamiento web
        $url = filter_var($url, FILTER_SANITIZE_URL);
        // 3. ¡El Corte! de string a array. Ahora "productos/mostrar/25" es:
        // $url[0] = productos | $url[1] = mostrar | $url[2] = 25
        $url = explode('/', $url);

        // Validamos si hay posición 0 (es decir, el controlador)
        if (isset($url[0])) { $getcontrolador = $url[0]; }
        
        // Validamos si hay posición 1 (es decir, la acción o función)
        if (isset($url[1])) { $getaccion = $url[1]; }
        
        // Validamos si hay posición 2 (generalmente un id, un slug o parámetro)
        if(isset($url[2])){
            // Simulamos lo que antes era un $_GET['id'] para las actualizaciones o deletes
            $_GET['id'] = $url[2]; 
        }
    }

    // Ya tenemos todo decodificado, mandamos a cargar el cascarón HTML principal:
    include_once ("vistas/plantilla.php");
?>
```

### 3.2 El Ejecutor (`ruteador.php`)
1. Crea al lado de `index.php` el archivo `ruteador.php`.
2. Aquí inyectamos automáticamente la clase del Controlador dependiendo de la URL y mandamos a llamar su método.

```php
<?php
    // Si la URL fue "/productos/mostrar", $getcontrolador ahora vale "productos"
    // Incluyo por lo tanto el archivo: controladores/controlador_productos.php
    include_once "controladores/controlador_" . $getcontrolador . ".php";
    
    // PHP permite transformar textos "string" a verdaderas clases instanciables.
    // Por convencion, mis clases se llaman ControladorProductos (con C y P mayúscula)
    // Usamos ucfirst() para volver mayúscula la primera letra (ej: ControladorProductos)
    $controladorClase = "Controlador" . ucfirst($getcontrolador);
    
    // Hacemos que nazca la clase (instanciar)
    $objcontrolador = new $controladorClase();
    
    // Llamamos a la función (la acción). Si la URL era "/productos/mostrar", 
    // entonces $getaccion ahora invoca al método public function mostrar(){} 
    $objcontrolador -> $getaccion();
?>
```

---

## 🔒 Módulo 4: El Modelo Robusto (Conexión PDO Singleton)

PDO (PHP Data Objects) es la librería élite para conectarse a MySQL. Olvídate de la prehistoria `mysqli_`. Usaremos el Patrón de Diseño "Singleton", esto garantiza que no se abran 50 conexiones si hay 50 consultas simultáneas al sistema. Siempre usa la misma "tubería".

1.  Crea `conexion.php` en la raíz.

```php
<?php
class baseDatos {
    // Variable estática persistente (sobrevive durante toda la vida de la peticion)
    private static $instancia = NULL;

    public static function crearInstancia(){
        // Si no hemos creado la instancia aun...
        if(!isset(self::$instancia)){
            // Modo debug severo: PDO tirará Excepciones FATAL ERROR que te ayudarán a detectar bugs SQL rápidos
            $opcionesPDO[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            
            // Reemplaza localhost, basemvc y root por los datos reales de tu hosting/Laragon
            self::$instancia = new PDO ('mysql:host=localhost;dbname=basemvc','root','', $opcionesPDO);
        }
        // Devolvemos el tubito listo
        return self::$instancia;
    }
}
?>
```

---

## 📊 Módulo 5: Capa de Negocio Base (Creando un Controlador MVC)
El controlador pide la data al Modelo y pinta la Vista. Veamos un ejemplo con `usuarios`.

1.  Crea la carpeta `controladores/` y crea el archivo `controlador_usuarios.php`.

```php
<?php
// Siempre trae la conexion DB y el Modelo (las Sentencias DB)
include_once "conexion.php";
include_once "./modelos/usuarios.php";

class ControladorUsuarios {

    // Método 'mostrar' que responde a la URL: /usuarios/mostrar
    public function mostrar(){
        // (Opcional) Traes datos del respectivo modelo Usuario::metodotal();
        
        // Como el ruteador incluirá esto en la Plantilla Maestra, lo único
        // que debemos inyectar es la simple porción HTML de la tabla final
        include_once "vistas/usuarios/mostrar.php";
    }

    // Método 'crear' que responde a la URL: /usuarios/crear
    public function crear(){
        // Si el formulario fue enviado por metodo POST con el botón submitir...
        if($_POST){
            $perfil = $_POST["txtperfil"];
            $nombre = $_POST["txtnombre"];
            // ... (Capturas los demás)
            
            // Llamas al Modleo ESTRICTAMENTE a insertar a DB
            Usuario::crear($perfil, $nombre, ...);
            
            // REDIRECCION CON RUTAS AMIGABLES (¡MAGIA!)
            // Usamos la constante URL_BASE de index.php acoplada a la nueva ruta
            header("Location: " . URL_BASE . "paginas/login"); 
        }
    }
}
?>
```

---

## 🎨 Módulo 6: Capa Visual Maestra (La Plantilla y las Vistas)

### 6.1 El Molde (`plantilla.php`)
Jamás escribas el Header o los tags `<html>` y `<head>` dos veces. Todo irá en `vistas/plantilla.php`.

1. Crea carpeta `vistas/` y dentro `plantilla.php`.

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- REGLA DE ORO DE LAS RUTAS AMIGABLES ⚠️ -->
    <!-- Todos tus assets (CSS, JS) importarlos pegándoles primero tu URL_BASE con PHP -->
    <link href="<?php echo constant('URL_BASE'); ?>recursos/css/miestilo.css">
    <!-- Aquí pega los CDNs de Google, Bootstrap local, DataTables, FontAwesome, etc. -->
</head>
<body class="d-flex flex-column min-vh-100"> <!-- Para el Footer Sticky Abajo -->
    
    <div class="container-fluid mb-5">
        <?php 
            include_once ("modulos/navbar.php");  // Nuestro componente de Navigación HTML puro
            include_once ("./ruteador.php");      // Nuestro cerebro principal cargando todo dinámico
        ?>
    </div>
    
    <?php include_once ("modulos/footer.php"); ?> <!-- Módulo de derechos reservados pie de página -->
</body>
</html>
```

### 6.2 Modificando un Formulario de Vistas a Routing Amigable
Un error de alumno común es que en las rutas amigables ponen `action="guardar.php"`. Esto no existe ya en este MVC puro. Los formularios deben hacer `POST` al index con la ruta amigable.

Ejemplo en `vistas/paginas/registro.php`:
```html
<!-- Fíjate cómo construimos el action combinando URL_BASE y /usuarios/crear -->
<form method="POST" action="<?php echo constant('URL_BASE'); ?>usuarios/crear">
    <div class ="form-group">
        <label>Nombre</label>
        <input type="text" name="txtnombre" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Crear Cuenta</button>
    
    <!-- Todo enlace "a href" también cambia: -->
    <a href="<?php echo constant('URL_BASE'); ?>paginas/login"> Regresar al Login</a>
</form>
```

---

## 🏁 En Resumen
La construcción de este ecosistema le otorga a los estudiantes de **AcademiaAds** los poderes fundamentales que utiliza Facebook, YouTube e Instagram. Las "Rutas Físicas" donde cada archivo `.php` es una página real web están obsoletas desde hace dos décadas. Pasando por un Archivo `.htaccess`, luego diseccionando todo en el Controlador Frontal (`index.php`) y disparando las inyecciones de Clases a través de `$objcontrolador -> $getaccion();`, estarás preparado para aprender Laravel o Symfony en el futuro con los ojos cerrados, porque ya habrás construido la arquitectura interna que usan esos gigantes paso a paso por tu cuenta.

¡Sigue programando con esta estructura sólida y domina el MVC!
