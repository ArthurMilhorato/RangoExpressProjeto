<?php
require_once 'repositories/ItemRepository.php';
require_once 'repositories/OrderRepository.php';

class MenuController {
    private $itemRepository;
    private $orderRepository;

    public function __construct() {
        $this->itemRepository = new ItemRepository();
        $this->orderRepository = new OrderRepository();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }

        $items = $this->itemRepository->findAll();
        include 'views/menu.php';
    }

    public function addToCart() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemId = $_POST['item_id'] ?? 0;
            $quantity = $_POST['quantity'] ?? 1;

            $item = $this->itemRepository->findById($itemId);
            if ($item) {
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                if (isset($_SESSION['cart'][$itemId])) {
                    $_SESSION['cart'][$itemId]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$itemId] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'quantity' => $quantity
                    ];
                }

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function cart() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }

        include 'views/cart.php';
    }

    public function payment() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
            header('Location: /cardapio');
            return;
        }

        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        include 'views/payment.php';
    }

    public function removeFromCart() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemId = $_POST['item_id'] ?? 0;
            
            if (isset($_SESSION['cart'][$itemId])) {
                unset($_SESSION['cart'][$itemId]);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Item não encontrado']);
            }
        }
    }

    public function updateCartQuantity() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemId = $_POST['item_id'] ?? 0;
            $quantity = $_POST['quantity'] ?? 1;
            
            if (isset($_SESSION['cart'][$itemId]) && $quantity > 0) {
                $_SESSION['cart'][$itemId]['quantity'] = $quantity;
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function checkout() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
            header('Location: /cardapio');
            return;
        }

        $order = new Order($_SESSION['user_id']);
        $total = 0;

        foreach ($_SESSION['cart'] as $item) {
            $order->items[] = $item;
            $total += $item['price'] * $item['quantity'];
        }

        $order->total = $total;

        if ($this->orderRepository->create($order)) {
            unset($_SESSION['cart']);
            unset($_SESSION['payment_id']);
            unset($_SESSION['payment_start']);
            $_SESSION['success'] = 'Pedido realizado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao processar pedido';
        }

        header('Location: /cardapio');
    }
}
?>