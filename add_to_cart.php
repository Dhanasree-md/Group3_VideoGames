<?php
session_start();
require_once 'DBHelper.php';
require_once 'CartHandler.php'; 

// Initialize the database connection
$db = new DBHelper();
$dbc = $db->getConnection();

// Check if game_id is provided in the POST request
if (isset($_POST['game_id']) && is_numeric($_POST['game_id'])) {
    $gameId = intval($_POST['game_id']);
    $quantity = 1; // Default quantity

    // Fetch game details from the database
    $stmt = $dbc->prepare("SELECT Title, Price FROM Game WHERE GameID = ?");
    $stmt->bind_param('i', $gameId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $game = $result->fetch_assoc();

        // Retrieve or create the Cart object from the session
        if (isset($_SESSION['cart'])) {
            if (is_string($_SESSION['cart'])) {
                $cart = unserialize($_SESSION['cart']);
            } else {
                $cart = new Cart();
            }
        } else {
            $cart = new Cart();
        }

        // Add the game to the cart
        $cart->addItem($gameId, $game['Title'], $game['Price'], $quantity);

        // Store the updated Cart object back in the session
        $_SESSION['cart'] = serialize($cart);

        // Insert or update the order and order items in the database
        $customerID = $_SESSION['CustomerID'];  // Retrieve CustomerID from session

        if (!isset($_SESSION['order_id'])) {
            // Create a new order
            $stmt = $dbc->prepare("INSERT INTO `Order` (CustomerID, OrderDate, TotalAmount) VALUES (?, NOW(), ?)");
            $totalAmount = $cart->getTotalAmount();
            $stmt->bind_param('id', $customerID, $totalAmount);
            $stmt->execute();
            $_SESSION['order_id'] = $stmt->insert_id;
        } else {
            // Update the order's total amount
            $stmt = $dbc->prepare("UPDATE `Order` SET TotalAmount = ? WHERE OrderID = ?");
            $totalAmount = $cart->getTotalAmount();
            $stmt->bind_param('di', $totalAmount, $_SESSION['order_id']);
            $stmt->execute();
        }

        // Insert or update order items
        $stmt = $dbc->prepare("SELECT * FROM OrderItem WHERE OrderID = ? AND GameID = ?");
        $stmt->bind_param('ii', $_SESSION['order_id'], $gameId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update quantity if the item already exists in the order
            $stmt = $dbc->prepare("UPDATE OrderItem SET Quantity = Quantity + ? WHERE OrderID = ? AND GameID = ?");
            $stmt->bind_param('iii', $quantity, $_SESSION['order_id'], $gameId);
        } else {
            // Insert new order item if it doesn't exist in the order
            $stmt = $dbc->prepare("INSERT INTO OrderItem (OrderID, GameID, Quantity, UnitPrice) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('iiid', $_SESSION['order_id'], $gameId, $quantity, $game['Price']);
        }
        $stmt->execute();
    }

    // Redirect to the shop page
    header("Location: index.php");
    exit();
} else {
    // Invalid game_id, redirect to the shop page
    header("Location: index.php");
    exit();
}
?>
