<?php
session_start();
require_once("DBHelper.php");
$db = new DBHelper();
$dbc = $db->getConnection();

// Check if game_id is provided in the POST request
if (isset($_POST['game_id']) && is_numeric($_POST['game_id'])) {
    $gameId = intval($_POST['game_id']);
    $quantity = 1; // Default quantity

    // Check if the cart exists in the session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the game is already in the cart
    if (isset($_SESSION['cart'][$gameId])) {
        // Increase quantity if the item is already in the cart
        $_SESSION['cart'][$gameId]['quantity'] += $quantity;
    } else {
        // Fetch game details from the database
        $stmt = $dbc->prepare("SELECT Title, Price FROM Game WHERE GameID = ?");
        $stmt->bind_param('i', $gameId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $game = $result->fetch_assoc();

            // Add the game to the cart
            $_SESSION['cart'][$gameId] = [
                'title' => $game['Title'],
                'unit_price' => $game['Price'],
                'quantity' => $quantity
            ];
        }
    }

    // Debugging information
    print_r($_SESSION['cart']); 
    print_r('test'); 

    // Redirect to the shop page
    header("Location: index.php");
    exit();
} else {
    // Invalid game_id
    header("Location: index.php");
    exit();
}
?>
