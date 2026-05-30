<?php
require_once BASE_PATH . 'app/models/UserModel.php';

/**
 * Controlador de usuarios — autenticación y gestión
 */
class ControllerUsers extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /** GET  /users/login  — muestra formulario */
    public function login(): void
    {
        $this->render('users/login');
    }

    /** POST /users/authenticate — procesa login */
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users/login');
        }

        $email    = $_POST['txtEmail']    ?? '';
        $password = $_POST['txtPassword'] ?? '';
        $user     = $this->userModel->findByCredentials($email, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            $this->redirect('pages/index');
        } else {
            $this->render('users/login', ['error' => 'Correo o contraseña inválidos']);
        }
    }

    /** GET  /users/register — muestra formulario */
    public function register(): void
    {
        $this->render('users/register');
    }

    /** POST /users/store — guarda nuevo usuario */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->create(
                $_POST['txtperfil']   ?? 'usuario',
                $_POST['txtnombre'],
                $_POST['txtEmail'],
                $_POST['txtPassword']
            );
        }
        $this->redirect('users/login');
    }

    /** GET /users/list */
    public function list(): void
    {
        $users = $this->userModel->getAll();
        $this->render('users/list', ['users' => $users]);
    }

    /** GET /users/edit/{id} */
    public function edit(): void
    {
        $id   = (int) ($_GET['id'] ?? 0);
        $user = $this->userModel->findById($id);
        $this->render('users/edit', ['user' => $user]);
    }

    /** GET /users/logout */
    public function logout(): void
    {
        session_destroy();
        $this->redirect('users/login');
    }
}
