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
