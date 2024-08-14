<?php
session_start();
require_once 'DBHelper.php';
require_once 'CartHandler.php'; 

$db = new DBHelper();
$dbc = $db->getConnection();

if (isset($_POST['game_id']) && is_numeric($_POST['game_id'])) {
    $gameId = intval($_POST['game_id']);
    $quantity = 1; 

    if (!isset($_SESSION['CustomerID'])) {
        header("Location: login.php");
        exit();
    }

    $stmt = $dbc->prepare("SELECT Title, Price FROM Game WHERE GameID = ?");
    $stmt->bind_param('i', $gameId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $game = $result->fetch_assoc();

        if (isset($_SESSION['cart'])) {
            if (is_string($_SESSION['cart'])) {
                $cart = unserialize($_SESSION['cart']);
            } else {
                $cart = new Cart();
            }
        } else {
            $cart = new Cart();
        }

        $cart->addItem($gameId, $game['Title'], $game['Price'], $quantity);

        $_SESSION['cart'] = serialize($cart);

        $customerID = $_SESSION['CustomerID'];  

        if (isset($_SESSION['order_id'])) {
            $stmt = $dbc->prepare("SELECT OrderStatus FROM `Order` WHERE OrderID = ?");
            $stmt->bind_param('i', $_SESSION['order_id']);
            $stmt->execute();
            $orderResult = $stmt->get_result();
            $order = $orderResult->fetch_assoc();

            if ($order['OrderStatus'] === 'Completed') {
                unset($_SESSION['order_id']);
            }
        }

        if (!isset($_SESSION['order_id'])) {
            $stmt = $dbc->prepare("INSERT INTO `Order` (CustomerID, OrderDate, TotalAmount, OrderStatus) VALUES (?, NOW(), ?, 'Pending')");
            $totalAmount = $cart->getTotalAmount();
            $stmt->bind_param('id', $customerID, $totalAmount);
            $stmt->execute();
            $_SESSION['order_id'] = $stmt->insert_id;
        } else {
            $stmt = $dbc->prepare("UPDATE `Order` SET TotalAmount = ? WHERE OrderID = ?");
            $totalAmount = $cart->getTotalAmount();
            $stmt->bind_param('di', $totalAmount, $_SESSION['order_id']);
            $stmt->execute();
        }

        $stmt = $dbc->prepare("SELECT * FROM OrderItem WHERE OrderID = ? AND GameID = ?");
        $stmt->bind_param('ii', $_SESSION['order_id'], $gameId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $stmt = $dbc->prepare("UPDATE OrderItem SET Quantity = Quantity + ? WHERE OrderID = ? AND GameID = ?");
            $stmt->bind_param('iii', $quantity, $_SESSION['order_id'], $gameId);
        } else {
            $stmt = $dbc->prepare("INSERT INTO OrderItem (OrderID, GameID, Quantity, UnitPrice) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iiid', $_SESSION['order_id'], $gameId, $quantity, $game['Price']);
        }
        $stmt->execute();
    }

    header("Location: index.php");
    exit();
} 
?>
