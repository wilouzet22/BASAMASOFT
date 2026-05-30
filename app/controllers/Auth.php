<?php
class Auth extends Controller {
    public function __construct() {
        // Load User model if needed
    }

    public function login() {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => ''
            ];

            // Simple Auth Logic
            if ($data['username'] === 'admin' && $data['password'] === 'password') {
                $_SESSION['username'] = 'Administrador';
                header('Location: ' . URLROOT . '/admin/dashboard');
            } elseif ($data['username'] === 'familia' && $data['password'] === 'password') {
                $_SESSION['username'] = 'Bedoya Zuluaga';
                header('Location: ' . URLROOT . '/padres/dashboard');
            } else {
                $data['password_err'] = 'Credenciales incorrectas';
                $this->view('auth/login', $data);
            }

        } else {
            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }

    public function logout() {
        unset($_SESSION['username']);
        session_destroy();
        header('Location: ' . URLROOT . '/auth/login');
    }
}
