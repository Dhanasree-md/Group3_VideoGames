<?php
session_start();
require_once 'DBHelper.php';
require_once 'CartHandler.php';

// Initialize the database connection
$db = new DBHelper();
$dbc = $db->getConnection();

// Retrieve the Cart object from the session
if (isset($_SESSION['cart'])) {
    if (is_string($_SESSION['cart'])) {
        $cart = unserialize($_SESSION['cart']);
    } else {
        $cart = new Cart();
    }
} else {
    $cart = new Cart();
}

// Handle form submissions for updating quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        $gameIds = $_POST['game_id'];
        $quantities = $_POST['quantity'];

        foreach ($gameIds as $index => $gameId) {
            $quantity = intval($quantities[$index]);
            $cart->updateItemQuantity($gameId, $quantity);

            // Update the order item in the database
            if (isset($_SESSION['order_id'])) {
                $stmt = $dbc->prepare("UPDATE OrderItem SET Quantity = ? WHERE OrderID = ? AND GameID = ?");
                $stmt->bind_param('iii', $quantity, $_SESSION['order_id'], $gameId);
                $stmt->execute();
            }
        }
        $_SESSION['cart'] = serialize($cart);  // Save the updated cart back to the session

        // Update the order's total amount
        if (isset($_SESSION['order_id'])) {
            $stmt = $dbc->prepare("UPDATE `Order` SET TotalAmount = ? WHERE OrderID = ?");
            $totalAmount = $cart->getTotalAmount();
            $stmt->bind_param('di', $totalAmount, $_SESSION['order_id']);
            $stmt->execute();
        }
    } elseif (isset($_POST['remove_item'])) {
        $gameId = intval($_POST['remove_item']);

        // Remove the item from the cart
        $cart->removeItem($gameId);
        $_SESSION['cart'] = serialize($cart);  // Save the updated cart back to the session

        // Remove the order item from the database
        if (isset($_SESSION['order_id'])) {
            $stmt = $dbc->prepare("DELETE FROM OrderItem WHERE OrderID = ? AND GameID = ?");
            $stmt->bind_param('ii', $_SESSION['order_id'], $gameId);
            $stmt->execute();
        }

        // Update the order's total amount
        if (isset($_SESSION['order_id'])) {
            $stmt = $dbc->prepare("UPDATE `Order` SET TotalAmount = ? WHERE OrderID = ?");
            $totalAmount = $cart->getTotalAmount();
            $stmt->bind_param('di', $totalAmount, $_SESSION['order_id']);
            $stmt->execute();
        }
    } elseif (isset($_POST['clear_cart'])) {
        $cart->clearCart();
        $_SESSION['cart'] = serialize($cart);  // Save the empty cart back to the session

        // Clear the order items from the database
        if (isset($_SESSION['order_id'])) {
            $stmt = $dbc->prepare("DELETE FROM OrderItem WHERE OrderID = ?");
            $stmt->bind_param('i', $_SESSION['order_id']);
            $stmt->execute();

            // Update the order's total amount to 0
            $stmt = $dbc->prepare("UPDATE `Order` SET TotalAmount = 0 WHERE OrderID = ?");
            $stmt->bind_param('i', $_SESSION['order_id']);
            $stmt->execute();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - NEXPLAY</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="banner">
            <h1>NEXPLAY</h1>
        </div>
        <nav class="navbar navbar-expand-lg">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['FirstName'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['FirstName']); ?>!</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container">
        <h2>Your Shopping Cart</h2>
        <?php if (!$cart->isEmpty()): ?>
            <form action="" method="POST">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart->getItems() as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item->title); ?></td>
                                <td>
                                    <div class="form-row align-items-center">
                                        <div class="col-auto">
                                            <input type="number" name="quantity[]" value="<?php echo htmlspecialchars($item->quantity); ?>" min="1" class="form-control" style="width: 80px;">
                                        </div>
                                        <div class="col-auto">
                                            <input type="hidden" name="game_id[]" value="<?php echo htmlspecialchars($item->gameId); ?>">
                                            <button type="submit" name="update_cart" class="btn btn-secondary">Update</button>
                                        </div>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item->unitPrice, 2); ?></td>
                                <td>$<?php echo number_format($item->getTotalPrice(), 2); ?></td>
                                <td>
                                    <button type="submit" name="remove_item" value="<?php echo htmlspecialchars($item->gameId); ?>" class="btn btn-danger">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                            <td><strong>$<?php echo number_format($cart->getTotalAmount(), 2); ?></strong></td>
                            <td>
                                <button type="submit" name="clear_cart" class="btn btn-warning">Clear Cart</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <div class="text-center">
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p>Your cart is empty. <a href="index.php">Continue shopping</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>