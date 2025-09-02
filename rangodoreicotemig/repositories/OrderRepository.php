<?php
require_once 'config/database.php';
require_once 'models/Order.php';

class OrderRepository {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create(Order $order) {
        $this->conn->beginTransaction();
        try {
            $query = "INSERT INTO orders (user_id, total) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$order->user_id, $order->total]);
            
            $orderId = $this->conn->lastInsertId();
            
            foreach ($order->items as $item) {
                $query = "INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
            }
            
            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function findAll() {
        $query = "SELECT o.*, u.name as user_name FROM orders o 
                  JOIN users u ON o.user_id = u.id 
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order();
            $order->id = $row['id'];
            $order->user_id = $row['user_id'];
            $order->total = $row['total'];
            $order->status = $row['status'];
            $order->created_at = $row['created_at'];
            $order->user_name = $row['user_name'];
            $orders[] = $order;
        }
        return $orders;
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $id]);
    }
}
?>