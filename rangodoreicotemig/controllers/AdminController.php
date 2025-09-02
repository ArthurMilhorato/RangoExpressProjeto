<?php
require_once 'repositories/ItemRepository.php';
require_once 'repositories/OrderRepository.php';

class AdminController {
    private $itemRepository;
    private $orderRepository;

    public function __construct() {
        $this->itemRepository = new ItemRepository();
        $this->orderRepository = new OrderRepository();
    }

    private function checkAdmin() {
        // if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
        //     header('Location: /login');
        //     exit;
        // }
    }

    public function index() {
        $this->checkAdmin();
        $items = $this->itemRepository->findAll();
        $orders = $this->orderRepository->findAll();
        include 'views/admin.php';
    }

    public function createItem() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $image = $_POST['image'] ?? '';

            if (empty($name) || empty($price)) {
                $_SESSION['error'] = 'Nome e preço são obrigatórios';
                header('Location: /admin');
                return;
            }

            $item = new Item($name, $description, $price, $image);
            if ($this->itemRepository->create($item)) {
                $_SESSION['success'] = 'Item criado com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao criar item';
            }
        }

        header('Location: /admin');
    }

    public function updateItem() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $image = $_POST['image'] ?? '';

            $item = new Item($name, $description, $price, $image);
            $item->id = $id;

            if ($this->itemRepository->update($item)) {
                $_SESSION['success'] = 'Item atualizado com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao atualizar item';
            }
        }

        header('Location: /admin');
    }

    public function deleteItem() {
        $this->checkAdmin();
        
        $id = $_GET['id'] ?? 0;
        if ($this->itemRepository->delete($id)) {
            $_SESSION['success'] = 'Item removido com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao remover item';
        }

        header('Location: /admin');
    }

    public function updateOrderStatus() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['order_id'] ?? 0;
            $status = $_POST['status'] ?? '';

            if ($this->orderRepository->updateStatus($id, $status)) {
                $_SESSION['success'] = 'Status do pedido atualizado!';
            } else {
                $_SESSION['error'] = 'Erro ao atualizar status';
            }
        }

        header('Location: /admin');
    }
}
?>