<?php
/**
 * Auth Controller
 * Maneja el inicio y cierre de sesión para los tres roles:
 *  - Administrador  (tabla: administrador, campo: correo)
 *  - Profesor       (tabla: profesores,    campo: username)
 *  - Familia        (tabla: familias,      campo: username)
 */
class Auth extends Controller {

    public function __construct() {
        // Carga los modelos necesarios
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'username'      => trim($_POST['username'] ?? ''),
                'password'      => trim($_POST['password'] ?? ''),
                'username_err'  => '',
                'password_err'  => '',
            ];

            // Validaciones básicas
            if (empty($data['username'])) {
                $data['username_err'] = 'Por favor ingrese su usuario o correo.';
            }
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor ingrese su contraseña.';
            }

            if (empty($data['username_err']) && empty($data['password_err'])) {
                $this->_autenticar($data);
                return;
            }

            $this->view('auth/login', $data);

        } else {
            $data = [
                'username'      => '',
                'password'      => '',
                'username_err'  => '',
                'password_err'  => '',
            ];
            $this->view('auth/login', $data);
        }
    }

    /**
     * Intenta autenticar al usuario contra los tres roles disponibles.
     */
    private function _autenticar($data) {
        $passwordEncrypted = $this->encryptPassword($data['password']);

        // 1. ¿Es Administrador? (usa correo)
        $adminModel = $this->model('AdministradorModel');
        $admin = $adminModel->findByCorreo($data['username']);
        if ($admin && $admin->password_hash === $passwordEncrypted) {
            $_SESSION['user_id']    = $admin->id_administrador;
            $_SESSION['username']   = $admin->nombres . ' ' . $admin->apellidos;
            $_SESSION['correo']     = $admin->correo;
            $_SESSION['rol']        = 'administrador';
            header('Location: ' . URLROOT . '/admin/dashboard');
            exit;
        }

        // 2. ¿Es Profesor? (usa username)
        $profModel = $this->model('ProfesorModel');
        $profesor = $profModel->findByUsername($data['username']);
        if ($profesor && $profesor->password_hash === $passwordEncrypted) {
            $_SESSION['user_id']    = $profesor->id_profesor;
            $_SESSION['username']   = $profesor->nombres . ' ' . $profesor->apellidos;
            $_SESSION['correo']     = $profesor->email;
            $_SESSION['rol']        = 'profesor';
            header('Location: ' . URLROOT . '/docentes/dashboard');
            exit;
        }

        // 3. ¿Es Familia/Acudiente? (usa username)
        $familiaModel = $this->model('FamiliaModel');
        $familia = $familiaModel->findByUsername($data['username']);
        if ($familia && $familia->password_hash === $passwordEncrypted) {
            $_SESSION['user_id']    = $familia->id_familia;
            $_SESSION['username']   = $familia->nombre_principal_acudiente . ' ' . $familia->apellidos_principal_acudiente;
            $_SESSION['correo']     = $familia->email_contacto;
            $_SESSION['rol']        = 'familia';
            header('Location: ' . URLROOT . '/padres/dashboard');
            exit;
        }

        // Ninguno coincidió
        $data['password_err'] = 'Usuario o contraseña incorrectos.';
        $this->view('auth/login', $data);
    }

    /**
     * Encripta una contraseña usando AES-128 y luego la pasa a Base64.
     */
    private function encryptPassword($password) {
        $encrypted = openssl_encrypt($password, 'aes-128-ecb', ENCRYPTION_KEY, OPENSSL_RAW_DATA);
        return base64_encode($encrypted);
    }

    public function logout() {
        session_destroy();
        header('Location: ' . URLROOT . '/auth/login');
        exit;
    }
}
