<?php
session_start();
require_once 'controllers/AuthController.php';
require_once 'controllers/MenuController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/PaymentController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
    case '/login':
        $controller = new AuthController();
        $controller->login();
        break;
    
    case '/register':
        $controller = new AuthController();
        $controller->register();
        break;
    
    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    
    case '/cardapio':
        $controller = new MenuController();
        $controller->index();
        break;
    
    case '/add-to-cart':
        $controller = new MenuController();
        $controller->addToCart();
        break;
    
    case '/remove-from-cart':
        $controller = new MenuController();
        $controller->removeFromCart();
        break;
    
    case '/update-cart-quantity':
        $controller = new MenuController();
        $controller->updateCartQuantity();
        break;
    
    case '/carrinho':
        $controller = new MenuController();
        $controller->cart();
        break;
    
    case '/pagamento':
        $controller = new MenuController();
        $controller->payment();
        break;
    
    case '/checkout':
        $controller = new MenuController();
        $controller->checkout();
        break;
    
    case '/admin':
        $controller = new AdminController();
        $controller->index();
        break;
    
    case '/admin/create-item':
        $controller = new AdminController();
        $controller->createItem();
        break;
    
    case '/admin/update-item':
        $controller = new AdminController();
        $controller->updateItem();
        break;
    
    case '/admin/delete-item':
        $controller = new AdminController();
        $controller->deleteItem();
        break;
    
    case '/admin/update-order':
        $controller = new AdminController();
        $controller->updateOrderStatus();
        break;
    
    case '/api/create-pix':
        $controller = new PaymentController();
        $controller->createPix();
        break;
    
    case '/api/check-payment':
        $controller = new PaymentController();
        $controller->checkPayment();
        break;
    
    default:
        http_response_code(404);
        echo "Página não encontrada";
        break;
}
?>