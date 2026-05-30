<?php
require_once BASE_PATH . 'app/models/ProductModel.php';

/**
 * Controlador de productos — CRUD completo
 */
class ControllerProducts extends Controller
{
    private ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /** GET  /products/list */
    public function list(): void
    {
        $products = $this->productModel->getAll();
        $this->render('products/list', ['products' => $products]);
    }

    /** GET  /products/create */
    public function create(): void
    {
        $this->render('products/create');
    }

    /** POST /products/store */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->productModel->create(
                $_POST['txtcodigo'],
                $_POST['txtnombre'],
                $_POST['txtpresentacion'],
                $_POST['txtprecio'],
                $_POST['txttipo']
            );
        }
        $this->redirect('products/list');
    }

    /** GET  /products/edit/{id} */
    public function edit(): void
    {
        $id      = (int) ($_GET['id'] ?? 0);
        $product = $this->productModel->findById($id);
        $this->render('products/edit', ['product' => $product]);
    }

    /** POST /products/update/{id} */
    public function update(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->productModel->update(
                $id,
                $_POST['txtcodigo'],
                $_POST['txtnombre'],
                $_POST['txtpresentacion'],
                $_POST['txtprecio'],
                $_POST['txttipo']
            );
        }
        $this->redirect('products/list');
    }

    /** GET  /products/delete/{id} */
    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->productModel->delete($id);
        }
        $this->redirect('products/list');
    }
}
