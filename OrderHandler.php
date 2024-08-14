<?php
require_once 'DBHelper.php';

class OrderHandler {
    private $conn;

    public function __construct() {
        $db = new DBHelper();
        $this->conn = $db->getConnection();
    }

    public function getCustomerDetails($customerId) {
        $stmt = $this->conn->prepare("SELECT * FROM Customer WHERE CustomerID = ?");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getOrderDetails($orderId) {
        $stmt = $this->conn->prepare("SELECT * FROM `Order` WHERE OrderID = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getOrderItems($orderId) {
        $query = "
            SELECT 
                OrderItem.Quantity,
                OrderItem.UnitPrice,
                Game.Title,
                Genre.GenreName,
                Platform.PlatformName
            FROM OrderItem
            JOIN Game ON OrderItem.GameID = Game.GameID
            JOIN Genre ON Game.GenreID = Genre.GenreID
            JOIN Platform ON Game.PlatformID = Platform.PlatformID
            WHERE OrderItem.OrderID = ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateOrder($orderId, $shippingAddress, $billingAddress) {
        $OrderStatus="Completed";
        $stmt = $this->conn->prepare("UPDATE `Order` SET ShippingAddress = ?, BillingAddress = ?,OrderStatus =? WHERE OrderID = ?");
        $stmt->bind_param("sssi", $shippingAddress, $billingAddress,$OrderStatus, $orderId);
        return $stmt->execute();
    }

   
}
